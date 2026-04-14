<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

if(function_exists('jws_add_less')) {
    jws_add_less();
}
/** proces less files and generate auriane-style.css in uploads folder */
{
    /**
     * Must be called in admin area to process less files on option save
     *
     * @param string $less_file
     * @param string $extension_name
     */

  
    function jws_action_theme_generate_styles()
    {
        
        global $auriane_less_files, $auriane_stylesheet_directory, $auriane_template_directory;

        $auriane_stylesheet_directory = get_stylesheet();
        $auriane_template_directory = get_template();
        $auriane_template_directory_uri = get_template_directory_uri();
        $upload_dir = wp_upload_dir();
        $style_dir = $upload_dir['basedir'];
        $autosave = true;
        $siteid = get_current_blog_id();
        $filename = 'jws-style.css';
      
        /**
         * Less files are generated in one file auriane-style.css, in upload folder, after options are saved or if
         * auriane-style.css file doesn't exist yet
         */
        if (!file_exists($style_dir . '/'.$filename.'') || $autosave ) {
          
            $errors = array();
            $style_less = locate_template('assets/css/less/style.less');
            /** show errors in admin if less files not found */
            if (!empty($errors)) {
             
                    /**
                     * backward compatibility if unyson isn't installed.
                     * this function jws_action_theme_generate_styles() is hooked to admin_notices action
                     */
                    ?>
                    <div class="error">
                        <p><?php echo implode("<br />\n", $errors); ?></p>
                    </div>
                    <?php
               

                /** stop trying to process less files if we got error */

                return;
            }
  
            try {
                /** Don't compress generated css file if developer mode is on */
                $options = (!WP_DEBUG) ? array('compress' => true) : array();


                /** Import less files from child theme if it is overwritten */
                $options['import_callback'] = 'verify_less_in_child_theme';
                if (!function_exists('verify_less_in_child_theme')) {
                    function verify_less_in_child_theme($evald)
                    {
                        global $auriane_stylesheet_directory, $auriane_template_directory;

                        $evaldPath = $evald->PathAndUri();
                        $full_path = preg_replace('/' . $auriane_template_directory . '/', $auriane_stylesheet_directory, $evaldPath[0]);

                        if (file_exists($full_path)) {
                            $uri = preg_replace('/' . $auriane_template_directory . '/', $auriane_stylesheet_directory, $evaldPath[1]);

                            return array($full_path, $uri);
                        }
                    }
                }
                
                if (class_exists('Less_Parser')) {
                    $sourceMap = false;
                    $options = array('sourceMap' => $sourceMap);
                    $parser = new Less_Parser($options);
                    $parser->parseFile($style_less, $auriane_template_directory_uri);
                    if (is_rtl()) {
                        $parser->parseFile($rtl_less, $auriane_template_directory_uri);
                    }

                    /**
                     * get values from admin styling and overwrite them when processing less files
                     */
                    $auriane_modified_variables = array();
            


                    /**
                     * Parse all css and write generated styles to auriane-style.css in upload folder,
                     * where should be granted write permission
                     */

                    // set time limit
                    @set_time_limit(600);

                    $css = $parser->getCss();


                    // init $wp_filesystem
                    {
                        if (!function_exists('request_filesystem_credentials')) {
                            load_template(ABSPATH . '/wp-admin/includes/file.php');
                        }

                        if (!defined('FS_METHOD')) {
                            define('FS_METHOD', 'direct');
                        }

                        if (false === request_filesystem_credentials('', 'direct')) {
                            return false;
                        }

                        if (!WP_Filesystem()) {
                            // our credentials were no good, ask the user for them again
                            request_filesystem_credentials('', 'direct', true);
                            return false;
                        }

                        global $wp_filesystem;
                    }
                    
                    if (!$wp_filesystem->put_contents($style_dir . '/'.$filename.'', $css)) {
                        esc_html_e("error write content", "auriane");
                    }
                }

            } catch (Exception $e) {
                /** show parser errors in admin */
                    /**
                     * backward compatibility if unyson isn't installed.
                     * this function jws_action_theme_generate_styles() is hooked to admin_notices action
                     */
                    ?>
                    <div class="error">
                        <p><?php printf(esc_html__('lessphp fatal error:  %s', 'auriane'), $e->getMessage()); ?></p>
                    </div>
                    <?php
               
            }


            /** show parsed files in admin on save options for debugging */
            if (WP_DEBUG) {
                $imported_files = $parser->allParsedFiles();
            }
        }
    }   
add_action ('redux/page/jws_option/load', 'jws_action_theme_generate_styles'); 
add_action ('redux/options/jws_option/saved', 'jws_action_theme_generate_styles');
}