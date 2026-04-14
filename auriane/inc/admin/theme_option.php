<?php
    /**
     * ReduxFramework Theme Config File
     * For full documentation, please visit: https://docs.reduxframework.com
     * */

    if ( ! class_exists( 'Redux_Framework_theme_config' ) ) {

        class Redux_Framework_theme_config {

            public $args = array();
            public $sections = array();
            public $theme;
            public $ReduxFramework;

            public function __construct() {

                if ( ! class_exists( 'ReduxFramework' ) ) {
                    return;
                }

                // This is needed. Bah WordPress bugs.  ;)
                if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                    $this->initSettings();
                } else {
                    add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
                }
		

            }

            public function initSettings() {

                // Just for demo purposes. Not needed per say.
                $this->theme = wp_get_theme();

                // Set the default arguments
                $this->setArguments();

                // Set a few help tabs so you can see how it's done
         

                // Create the sections and fields
                $this->setSections();

                if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                    return;
                }


                $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
            }

            /**
             * This is a test function that will let you see when the compiler hook occurs.
             * It only runs if a field    set with compiler=>true is changed.
             * */
            function compiler_action( $options, $css, $changed_values ) {
                echo '<h1>The compiler hook has run!</h1>';
                echo "<pre>";
                print_r( $changed_values ); // Values that have changed since the last save
                echo "</pre>";
            }

            /**
             * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
             * Simply include this function in the child themes functions.php file.
             * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
             * so you must use get_template_directory_uri() if you want to use any of the built in icons
             * */
            function dynamic_section( $sections ) {
                //$sections = array();
                $sections[] = array(
                    'title'  => __( 'Section via hook', 'auriane' ),
                    'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'auriane' ),
                    'icon'   => 'el-icon-paper-clip',
                    // Leave this as a blank section, no options just some intro text set above.
                    'fields' => array()
                );

                return $sections;
            }

            /**
             * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
             * */
            function change_arguments( $args ) {
                //$args['dev_mode'] = true;

                return $args;
            }

            /**
             * Filter hook for filtering the default value of any given field. Very useful in development mode.
             * */
            function change_defaults( $defaults ) {
                $defaults['str_replace'] = 'Testing filter hook!';

                return $defaults;
            }

            // Remove the demo link and the notice of integrated demo from the redux-framework plugin
            function remove_demo() {

                // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
                if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                    // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                    remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
                }
            }

            public function setSections() {

                /**
                 * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
                 * */
                // Background Patterns Reader
                $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
                $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
                $sample_patterns      = array();

                if ( is_dir( $sample_patterns_path ) ) :

                    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
                        $sample_patterns = array();

                        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                                $name              = explode( '.', $sample_patterns_file );
                                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                                $sample_patterns[] = array(
                                    'alt' => $name,
                                    'img' => $sample_patterns_url . $sample_patterns_file
                                );
                            }
                        }
                    endif;
                endif;

                ob_start();

                $ct          = wp_get_theme();
                $this->theme = $ct;
                $item_name   = $this->theme->get( 'Name' );
                $tags        = $this->theme->Tags;
                $screenshot  = $this->theme->get_screenshot();
                $class       = $screenshot ? 'has-screenshot' : '';

                $customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'auriane' ), $this->theme->display( 'Name' ) );

                ?>
                <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
                    <?php if ( $screenshot ) : ?>
                        <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                               title="<?php echo esc_attr( $customize_title ); ?>">
                                <img src="<?php echo esc_url( $screenshot ); ?>"
                                     alt="<?php esc_attr_e( 'Current theme preview', 'auriane' ); ?>"/>
                            </a>
                        <?php endif; ?>
                        <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                             alt="<?php esc_attr_e( 'Current theme preview', 'auriane' ); ?>"/>
                    <?php endif; ?>

                    <h4><?php echo esc_attr($this->theme->display( 'Name' )); ?></h4>

                    <div>
                        <ul class="theme-info">
                            <li><?php printf( __( 'By %s', 'auriane' ), $this->theme->display( 'Author' ) ); ?></li>
                            <li><?php printf( __( 'Version %s', 'auriane' ), $this->theme->display( 'Version' ) ); ?></li>
                            <li><?php echo '<strong>' . __( 'Tags', 'auriane' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                        </ul>
                        <p class="theme-description"><?php echo esc_attr($this->theme->display( 'Description' )); ?></p>
                        <?php
                            if ( $this->theme->parent() ) {
                                printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'auriane' ) . '</p>', __( 'http://codex.wordpress.org/Child_Themes', 'auriane' ), $this->theme->parent()->display( 'Name' ) );
                            }
                        ?>

                    </div>
                </div>

                <?php
                $item_info = ob_get_contents();

                ob_end_clean();

                $sampleHTML = '';
           
				
				$of_options_fontsize = array("8px" => "8px", "9px" => "9px", "10px" => "10px", "11px" => "11px", "12px" => "12px", "13px" => "13px", "14px" => "14px", "15px" => "15px", "16px" => "16px", "17px" => "17px", "18px" => "18px", "19px" => "19px", "20px" => "20px", "21px" => "21px", "22px" => "22px", "23px" => "23px", "24px" => "24px", "25px" => "25px", "26px" => "26px", "27px" => "27px", "28px" => "28px", "29px" => "29px", "30px" => "30px", "31px" => "31px", "32px" => "32px", "33px" => "33px", "34px" => "34px", "35px" => "35px", "36px" => "36px", "37px" => "37px", "38px" => "38px", "39px" => "39px", "40px" => "40px");
				$of_options_fontweight = array("100" => "100", "200" => "200", "300" => "300", "400" => "400", "500" => "500", "600" => "600", "700" => "700");
				$of_options_font = array("1" => "Google Font", "2" => "Standard Font", "3" => "Custom Font");

				//Standard Fonts
				$of_options_standard_fonts = array(
					'Arial, Helvetica, sans-serif' => 'Arial, Helvetica, sans-serif',
					"'Arial Black', Gadget, sans-serif" => "'Arial Black', Gadget, sans-serif",
					"'Bookman Old Style', serif" => "'Bookman Old Style', serif",
					"'Comic Sans MS', cursive" => "'Comic Sans MS', cursive",
					"Courier, monospace" => "Courier, monospace",
					"Garamond, serif" => "Garamond, serif",
					"Georgia, serif" => "Georgia, serif",
					"Impact, Charcoal, sans-serif" => "Impact, Charcoal, sans-serif",
					"'Lucida Console', Monaco, monospace" => "'Lucida Console', Monaco, monospace",
					"'Lucida Sans Unicode', 'Lucida Grande', sans-serif" => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
					"'MS Sans Serif', Geneva, sans-serif" => "'MS Sans Serif', Geneva, sans-serif",
					"'MS Serif', 'New York', sans-serif" => "'MS Serif', 'New York', sans-serif",
					"'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "'Palatino Linotype', 'Book Antiqua', Palatino, serif",
					"Tahoma, Geneva, sans-serif" => "Tahoma, Geneva, sans-serif",
					"'Times New Roman', Times, serif" => "'Times New Roman', Times, serif",
					"'Trebuchet MS', Helvetica, sans-serif" => "'Trebuchet MS', Helvetica, sans-serif",
					"Verdana, Geneva, sans-serif" => "Verdana, Geneva, sans-serif"
				);
				
                //lists page
                $lists_page = array();
                $args_page = array(
                    'sort_order' => 'asc',
                    'sort_column' => 'post_title',
                    'hierarchical' => 1,
                    'exclude' => '',
                    'include' => '',
                    'meta_key' => '',
                    'meta_value' => '',
                    'authors' => '',
                    'child_of' => 0,
                    'parent' => -1,
                    'exclude_tree' => '',
                    'number' => '',
                    'offset' => 0,
                    'post_type' => 'page',
                    'post_status' => 'publish'
                );
                $pages = get_pages( $args_page );

                foreach( $pages as $p ){
                    $lists_page[ $p->ID ] = esc_attr( $p->post_title );
                }
// -> START 
$this->sections[] = array(
    'title' => esc_html__('General', 'auriane'),
    'id' => 'general',
    'customizer_width' => '300px',
    'icon' => 'el el-cogs',
    'fields' => array(
        array(
            'id'        => 'favicon',
            'type'      => 'media',
            'url'       => true,
            'title'     => esc_html__('Favicon', 'auriane' ),
            'compiler'  => 'false',
            'subtitle'  => esc_html__('Upload your favicon', 'auriane' ),
        ),
        array(
            'id'        => 'logo',
            'type'      => 'media',
            'url'       => true,
            'title'     => esc_html__('Logo', 'auriane' ),
            'compiler'  => 'false',
            'subtitle'  => esc_html__('Upload your logo', 'auriane' ),
        ),
        array(         
            'id'       => 'bg_body',
            'type'     => 'background',
            'title'    =>  esc_html__('Background', 'auriane'),
            'subtitle' =>  esc_html__('background with image, color, etc.', 'auriane'),
            'desc'     =>  esc_html__('Change background for body.', 'auriane'),
            'default'  => array(
                'background-color' => '#ffffff',
            ),
            'output' => array('body'),
        ),

        array(
            'id'       => 'less',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Less To Css', 'auriane'),
            'default'  => false,
            'desc'      =>  esc_html__('Function Render Less To Css.Please turn off if you not use.', 'auriane'),
        ),
        array(
            'id'       => 'box-change-style',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Box Style', 'auriane'),
            'default'  => false,
            'desc'      =>  esc_html__('Display box change style demo.', 'auriane'),
        ),
        array (
				'id'       => 'rtl',
				'type'     => 'switch',
				'title'    => esc_html__( 'RTL', 'auriane' ),
				'default'  => false
		),

        array(
            'id'        => 'container-width',
            'type'      => 'slider',
            'title'     =>  esc_html__('Website Width Dektop', 'auriane'),
            "default"   => 1200,
            "min"       => 700,
            "step"      => 10,
            "max"       => 1920,
            'display_value' => 'label'
        ),
        array(
            'id'        => 'container-laptop-width',
            'type'      => 'slider',
            'title'     =>  esc_html__('Website Width Laptop', 'auriane'),
            "default"   => 1200,
            "min"       => 700,
            "step"      => 10,
            "max"       => 1920,
            'display_value' => 'label'
        ),

    )
);
// -> START Header Fields
$this->sections[] = array(
    'title' => esc_html__('Header', 'auriane'),
    'id' => 'header',
    'desc' => esc_html__('Custom Header', 'auriane'),
    'customizer_width' => '400px',
    'icon' => 'el el-caret-up',
    'fields' => array(
          array(
            'id' => 'choose-header-absolute',
            'type' => 'select',
            'multi' => true,
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select header absolute.', 'auriane'),
        ),
         array(
            'id' => 'select-header',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for header', 'auriane'),
            'desc' => esc_html__('Select layout for header from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
    )
);

// -> START Title Bar Fields
$this->sections[] = array(
    'title' => esc_html__('Title Bar', 'auriane'),
    'id' => 'title_bar',
    'desc' => esc_html__('Custom title bar', 'auriane'),
    'customizer_width' => '400px',
    'icon' => 'el el-text-height',
    'fields' => array(
        array(
            'id'       => 'title-bar-switch',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Switch On Title Bar', 'auriane'),
            'default'  => true,
        ),
        array(
            'id' => 'select-titlebar',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for title bar elementor', 'auriane'),
            'desc' => esc_html__('Select layout for title bar elementor from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(         
            'id'       => 'bg_titlebar',
            'type'     => 'background',
            'title'    =>  esc_html__('Background', 'auriane'),
            'subtitle' =>  esc_html__('background with image, color, etc.', 'auriane'),
            'desc'     =>  esc_html__('Change background for titler defaul (not woking with title elemetor template).', 'auriane'),
            'default'  => array(
                'background-color' => '#333333',
            ),
            'output' => array('.jws-title-bar-wrap-inner'),
        ),
        array(
            'id'             => 'titlebar-spacing',
            'type'           => 'spacing',
            'output'         => array('.jws-title-bar-wrap-inner'),
            'mode'           => 'padding',
            'units'          => array('em', 'px'),
            'units_extended' => 'false',
            'desc'           =>  esc_html__('You can enable or disable any piece of this field. Top, Right, Bottom, Left, or Units.', 'auriane'),
            'default'            => array(
                'padding-top'     => '150px', 
                'padding-right'   => '15px', 
                'padding-bottom'  => '100px', 
                'padding-left'    => '15px',
                'units'          => 'px', 
         ),
        
    ),
));

// -> START footer Fields
$this->sections[] = array(
    'title' => esc_html__('Footer', 'auriane'),
    'id' => 'footer',
    'desc' => esc_html__('Custom Footer', 'auriane'),
    'customizer_width' => '400px',
    'icon' => 'el el-caret-down',
    'fields' => array(
        array(
            'id' => 'select-footer',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for footer', 'auriane'),
            'desc' => esc_html__('Select layout for footer from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
    )
);

// -> START Color Fields
$this->sections[] = array(
    'title' => esc_html__('Color Styling', 'auriane'),
    'id' => 'global-color',
    'desc' => esc_html__('These are really color fields!', 'auriane'),
    'customizer_width' => '400px',
    'icon' => 'el el-brush',
);
$this->sections[] = array(
    'title' => esc_html__('Color', 'auriane'),
    'id' => 'color-styling',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'main-color',
            'type' => 'color',
            'title' => esc_html__('Main Color', 'auriane'),
            'default' => '#ed1c24',
        ),
        array(
            'id' => 'secondary-color',
            'type' => 'color',
            'title' => esc_html__('Secondary Color', 'auriane'),
            'default' => '#d71b22',
        ),
        array(
            'id' => 'color_heading',
            'type' => 'color',
            'title' => esc_html__('Color Heading', 'auriane'),
            'default' => '#131313',
        ),
        array(
            'id' => 'color_light',
            'type' => 'color',
            'title' => esc_html__('Color Light', 'auriane'),
            'default' => '#ffffff',
        ),
        array(
            'id' => 'color_body',
            'type' => 'color',
            'title' => esc_html__('Color Body', 'auriane'),
            'default' => '#6f6f6f',
        ),
    ),
);
$this->sections[] = array(
    'title' => esc_html__('Back To Top', 'auriane'),
    'id' => 'to-top-styling',
    'subsection' => true,
    'fields' => array(
          array(
            'id'       => 'to-top-layout',
            'type'     => 'select',
            'title'    =>  esc_html__('Select Back To Top Skins', 'auriane'), 
            'options'  => array(
                'with-shadow' => 'With Shadow',
                'with-mancolor' => 'With Main Color',
            ),
            'default'  => 'with-shadow',
        ),
        array(
            'id' => 'to-top-color',
            'type' => 'color',
            'title' => esc_html__('Color', 'auriane'),
            'default' => '#333333',
            'output' => array('.backToTop'),
        ),
    ),
);
$this->sections[] = array(
    'title' => esc_html__('Button', 'auriane'),
    'id' => 'button-styling',
    'subsection' => true,
    'fields' => array(
          array(
            'id'       => 'button-layout',
            'type'     => 'select',
            'title'    =>  esc_html__('Select Button Global Skins', 'auriane'), 
            'options'  => array(
                'default' => 'Default',
            ),
            'default'  => 'default',
        ),
        array(
            'id' => 'button-bgcolor',
            'type' => 'color',
            'title' => esc_html__('Background Color', 'auriane'),
            'default' => '',
        ),
        array(
            'id' => 'button-bgcolor2',
            'type' => 'color',
            'title' => esc_html__('Background Color 2', 'auriane'),
            'default' => '',
        ),
        array(
            'id' => 'button-color',
            'type' => 'color',
            'title' => esc_html__('Color', 'auriane'),
            'default' => '',
        ),
        array(
            'id' => 'button-color2',
            'type' => 'color',
            'title' => esc_html__('Color 2', 'auriane'),
            'default' => '',
        ),
         array(
            'id' => 'opt-typography-button',
            'type' => 'typography',
            'title' => esc_html__('Font', 'auriane'),
            'google' => true,
            'color' => false,
            'text-align' => false,
            'letter-spacing' => true,
            'text-transform' => true,
            'subsets' => false ,
            'output' => array('body.button-default  .elementor-button, body.button-default .jws-cf7-style .wpcf7-submit, body.button-default .elementor-button.rev-btn'),
        ),
        array(
            'title' => esc_html__('Padding', 'auriane'),
            'id'             => 'button-padding',
            'type'           => 'spacing',
            'mode'           => 'padding',
            'units'          => array('em', 'px'),
            'units_extended' => 'false',
            'output' => array('body.button-default  .elementor-button, body.button-default .jws-cf7-style .wpcf7-submit, body.button-default .elementor-button.rev-btn'),
        ),

    ),
);


// -> START Blogs Fields
$this->sections[] = array(
    'title' => esc_html__('Blogs', 'auriane'),
    'id' => 'blogs',
    'customizer_width' => '300px',
    'icon' => 'el el-blogger',
    'fields' => array(
        array(
            'id' => 'select-titlebar-blog-archive',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for title bar elementor', 'auriane'),
            'desc' => esc_html__('Select layout for title bar elementor from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'position_sidebar',
            'type' => 'select',
            'title' => esc_html__('Select Position Sidebar', 'auriane'),
            'options' => array(
                'left' => 'Left',
                'right' => 'Right',
                'full' => 'No Sidebar',
            ),
            'default' => 'right',
        ),
        array(
            'id' => 'select-sidebar-post-columns',
            'type' => 'select',
            'title' => esc_html__('Select Columns Default', 'auriane'),
            'options' => array(
                '1' => '1 Columns',
                '2' => '2 Columns',
                '3' => '3 Columns',
                '4' => '4 Columns',
            ),
            'default' => '1',
        ),
        array(
            'id'       => 'blog_layout',
            'type'     => 'select',
            'title'    =>  esc_html__('Select Blog Skin', 'auriane'), 
            'options'  => array(
                'grid' => 'Grid',
                'list' => 'List',
            ),
            'default'  => 'grid',
        ),
        array(
            'id' => 'select-sidebar-post',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for sidebar', 'auriane'),
            'desc' => esc_html__('Select layout from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
            'required' => array('position_sidebar', '!=', 'no_sidebar'),
        ),
        array(
            'id'       => 'blog_imagesize',
            'type'     => 'text',
            'title'    =>  esc_html__('Single Image Size', 'auriane'),
            'default'  => '866x505'
        ),
        array(
            'id' => 'exclude-blog',
            'type' => 'select',
            'multi' => true,
            'data' => 'posts',
            'args' => array('post_type' => array('post'), 'posts_per_page' => -1),
            'title' => esc_html__('Select blog types not show in blog archive page.', 'auriane'),
        ),
      
    )
);
$this->sections[] = array(
    'title' => esc_html__('Blog Single', 'auriane'),
    'id' => 'blog-single',
    'subsection' => true,
    'fields' => array(
        array(
            'id'       => 'blog-title-bar-switch',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Switch On Title Bar', 'auriane'),
            'default'  => true,
        ),
        array(
            'id' => 'select-titlebar-blog',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for title bar elementor', 'auriane'),
            'desc' => esc_html__('Select layout for title bar elementor from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
            'required' => array('blog-title-bar-switch', '=', true),
        ),
        array(
            'id' => 'select-header-blog',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for header', 'auriane'),
            'desc' => esc_html__('Select layout for header from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
         array(
            'id'       => 'blog_single_layout',
            'type'     => 'select',
            'title'    =>  esc_html__('Select Single Blog Skin', 'auriane'), 
            'subtitle' =>  esc_html__('No validation can be done on this field type', 'auriane'),
            'desc'     =>  esc_html__('Choose layout for single blog (comment,meta, author box...)', 'auriane'),
            'options'  => array(
                'layout1' => 'Layout 1',
                'layout2' => 'Layout 2',
            ),
            'default'  => 'layout1',
        ),
        array(
            'id' => 'select-related-blog',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for Related Post', 'auriane'),
            'desc' => esc_html__('Select layout from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'position_sidebar_blog_single',
            'type' => 'select',
            'title' => esc_html__('Select Position Sidebar', 'auriane'),
            'options' => array(
                'left' => 'Left',
                'right' => 'Right',
                'full' => 'No Sidebar',
            ),
            'default' => 'right',
        ),
         array(
            'id' => 'select-sidebar-post-single',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for sidebar', 'auriane'),
            'desc' => esc_html__('Select layout from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
            'required' => array('position_sidebar_blog_single', '!=', 'no_sidebar'),
        ),
        array(
            'id'       => 'single_blog_imagesize',
            'type'     => 'text',
            'title'    =>  esc_html__('Single Blog Image Size', 'auriane'),
            'default'  => '1170x550'
        ),
        array(
            'id' => 'select-content-before-footer-blog-single',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select content before footer for archive', 'auriane'),
            'desc' => esc_html__('Select layout for title bar elementor from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
    ),
);

// -> START Blogs Fields

if ( ! function_exists( 'jws_product_rent_available_fields' ) ) {
	/**
	 * All available fields for Theme Settings sorter option.
	 *
	 * @since 4.5
	 */
	function jws_product_rent_available_fields() {
		$product_attributes = array();
        $fields = array();
		if( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$product_attributes = wc_get_attribute_taxonomies();
		}
    
		if ( count( $product_attributes ) > 0 ) {
			foreach ( $product_attributes as $attribute ) {
				$fields[ 'pa_'.$attribute->attribute_name ] = $attribute->attribute_label;
			}	
		}

		return $fields;
	}
}


// -> START Projects Fields
$this->sections[] = array(
    'title' => esc_html__('Projects', 'auriane'),
    'id' => 'projects',
    'customizer_width' => '300px',
    'icon' => 'el el-th',
);
$this->sections[] = array(
    'title' => esc_html__('Projects Single', 'auriane'),
    'id' => 'projects-single',
    'subsection' => true,
    'fields' => array(
        array(
            'id'       => 'projects-title-bar-switch',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Switch On Title Bar', 'auriane'),
            'default'  => false,
        ),
        array(
            'id' => 'select-titlebar-projects',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for title bar elementor', 'auriane'),
            'desc' => esc_html__('Select layout for title bar elementor from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'select-header-projects',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for header', 'auriane'),
            'desc' => esc_html__('Select layout for header from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
       
    ),
);

// -> START Login Register Fields
$this->sections[] = array(
    'title' => esc_html__('Login/Register', 'auriane'),
    'id' => 'login_register',
    'customizer_width' => '300px',
    'icon' => 'el el-unlock',
    'fields' => array(
            array(
                'id'       => 'select-page-login-register-author',
                'type'     => 'switch', 
                'title'    =>  esc_html__('Switch On Login Register To User Page', 'auriane'),
                'default'  => false,
            ),
            array(
                'id'       => 'verify_email',
                'type'     => 'switch', 
                'title'    =>  esc_html__('Turn on email verification when the user registers', 'auriane'),
                'default'  => false,
            ),
            array(
                'id' => 'login_form_redirect',
                'type' => 'select',
                'data' => 'posts',
                'args' => array('post_type' => array('page'), 'posts_per_page' => -1),
                'title' => esc_html__('Select Page Form Login Redirect', 'auriane'),
                'desc' => esc_html__('Select Page Form Login Redirect From: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=page')) . '" target="_blank">Pages</a>',
            ),
            array(
                'id' => 'logout_form_redirect',
                'type' => 'select',
                'data' => 'posts',
                'args' => array('post_type' => array('page'), 'posts_per_page' => -1),
                'title' => esc_html__('Select Page Form Logout Redirect', 'auriane'),
                'desc' => esc_html__('Select Page Form Logout Redirect From: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=page')) . '" target="_blank">Pages</a>',
            ),
            array(
                'id' => 'page_mail',
                'type' => 'select',
                'data' => 'posts',
                'args' => array('post_type' => array('page'), 'posts_per_page' => -1),
                'title' => esc_html__('Select Page Verify Email', 'auriane'),
                'desc' => esc_html__('Select Page Verify Email From: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=page')) . '" target="_blank">Pages</a>',
            ),
    )
);




if ( ! function_exists( 'jws_user_featured_list' ) ) {
	/**
	 * All available fields for Theme Settings sorter option.
	 *
	 * @since 4.5
	 */
	function jws_user_featured_list() {

        $blogusers = get_users( );
        // Array of WP_User objects.
        foreach ( $blogusers as $user ) {
            $fields[$user->ID] = $user->display_name;
        }

		return $fields;
	}
}


// -> START 404
$this->sections[] = array(
    'title' => esc_html__('404 Page', 'auriane'),
    'id' => '404_page',
    'desc' => esc_html__('Select Layout For 404 Page.', 'auriane'),
    'customizer_width' => '300px',
    'icon' => 'el el-error',
    'fields' => array(
         array(
            'id'       => '404-off-header',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Turn Off Header', 'auriane'),
            'default'  => false,
        ),  
         array(
            'id'       => '404-off-footer',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Turn Off Footer', 'auriane'),
            'default'  => false,
        ), 
         array(
            'id'       => '404-off-titlebar',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Turn Off TitleBar', 'auriane'),
            'default'  => false,
        ),   
         array(
                'id' => 'select-content-404',
                'type' => 'select',
                'data' => 'posts',
                'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
                'title' => esc_html__('Select Content 404', 'auriane'),
                'desc' => esc_html__('Select content 404 from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
         ),
    )
);
if ( ! function_exists( 'jws_product_available_fields' ) ) {
	/**
	 * All available fields for Theme Settings sorter option.
	 *
	 * @since 4.5
	 */
	function jws_product_available_fields() {
		$product_attributes = array();
        $fields = array();
		if( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$product_attributes = wc_get_attribute_taxonomies();
		}
    
		if ( count( $product_attributes ) > 0 ) {
			foreach ( $product_attributes as $attribute ) {
				$fields[ 'pa_' . $attribute->attribute_name ] = $attribute->attribute_label;
			}	
		}

		return $fields;
	}
}
// -> START WooCommerce
$this->sections[] = array(
    'title' => esc_html__('WooCommerce', 'auriane'),
    'id' => 'woocommerce',
    'desc' => esc_html__('Option for WooCommerce', 'auriane'),
    'customizer_width' => '300px',
    'icon' => 'el el-shopping-cart-sign',
    'fields' => array(
      
     array(
            'id' => 'choose-attr-display',
            'type' => 'select',
            'multi' => true,
            'options'  => jws_product_rent_available_fields(),
            'title' => esc_html__('Select Attributes Display In Grid Product.', 'auriane'),
     ),
     array(
            'id' => 'choose-attr-after-title',
            'type' => 'select',
            'multi' => true,
            'options'  => jws_product_rent_available_fields(),
            'title' => esc_html__('Select Attributes Display After Title Product.', 'auriane'),
     ),
      
    )
);
// -> START Shop
$this->sections[] = array(
    'title' => esc_html__('Shop', 'auriane'),
    'id' => 'shop',
    'customizer_width' => '300px',
    'icon' => 'el el-shopping-cart',
    'fields' => array( 
        array(
            'id' => 'select-header-shop',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for header elementor', 'auriane'),
            'desc' => esc_html__('Select layout for header elementor from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'select-footer-shop',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for footer elementor', 'auriane'),
            'desc' => esc_html__('Select layout for footer elementor from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'exclude-product-in-shop',
            'type' => 'select',
            'multi' => true,
            'data' => 'posts',
            'args' => array('post_type' => array('product'), 'posts_per_page' => -1),
            'title' => esc_html__('Select product and remove in shop page', 'auriane'),
        ), 
    )
);
$this->sections[] = array(
    'title' => esc_html__('Shop Page', 'auriane'),
    'id' => 'shop_page',
    'subsection' => true,
    'fields' => array(
         array(
            'id' => 'select-titlebar-shop',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for title bar elementor', 'auriane'),
            'desc' => esc_html__('Select layout for title bar elementor from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'shop_position_sidebar',
            'type' => 'select',
            'title' => esc_html__('Select Position Sidebar', 'auriane'),
            'options' => array(
                'left' => 'Left',
                'right' => 'Right',
                'no_sidebar' => 'No Sidebar',
            ),
            'default' => 'no_sidebar',
        ),
          array(
            'id'       => 'shop-fullwidth-switch',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Switch On Shop Full Width', 'auriane'),
            'default'  => false,
        ),
        array(
            'id' => 'shop_layout',
            'type' => 'select',
            'title' => esc_html__('Archive Layout', 'auriane'),
            'options' => array(
                'layout1' => 'Layout 1',
                'layout2' => 'Layout 2',
                'layout3' => 'Layout 3',
                'layout4' => 'Layout 4',
            ),
            'default' => 'layout1',
        ),
        
        array(
            'id' => 'select-banner-before-product',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for banner before product elementor', 'auriane'),
            'desc' => esc_html__('Select layout for banner before product elementor from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        
        array(
            'id' => 'shop_click_filter',
            'type' => 'select',
            'title' => esc_html__('Fillter Layout', 'auriane'),
            'options' => array(
                'top' => 'Top',
                'bottom' => 'Toggle',
                'modal' => 'Modal',
                'sideout' => 'Side Out',
            ),
            'default' => 'bottom',
        ),
        
        array(
            'id' => 'select-shop-top_filter',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('elementor_library'), 'posts_per_page' => -1),
            'title' => esc_html__('Shop Top Filter', 'auriane'),
            'desc' => esc_html__('Select layout for shop top filter from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=elementor_library')) . '" target="_blank">Save Templates</a>',
            'required' => array('shop_click_filter', '=', 'bottom'),
        ),
        
        
      

        array(
            'id'=>'product_per_page',
            'type' => 'text',
            'title' =>  esc_html__('Product Per Page', 'auriane'),
            'subtitle' => __('Please enter the number', 'auriane'),
            'validate' => 'no_html',
            'default' => '12',
        ),
        array(
            'id' => 'shop_columns',
            'type' => 'select',
            'title' => esc_html__('Select Columns Default', 'auriane'),
            'options' => array(
                '1' => '1 Columns',
                '2' => '2 Columns',
                '3' => '3 Columns',
                '4' => '4 Columns',
            ),
            'default' => '3',
        ),
        array(
            'id'       => 'columns_review',
            'type'     => 'checkbox',
            'title'    => __('Columns Review', 'auriane'), 
            'options'  => array(
                '1' => '1 Columns',
                '2' => '2 Columns',
                '3' => '3 Columns',
                '4' => '4 Columns'
            ),
            'default' => array(
                '1' => '1', 
                '2' => '0', 
                '3' => '1',
                '4' => '0'
            )
        ),
        array(
            'id' => 'shop_pagination_layout',
            'type' => 'select',
            'title' => esc_html__('Shop Pagination Layout', 'auriane'),
            'options' => array(
                'number' => 'Number',
                'loadmore' => 'Load More',
                'infinity' => 'Infinity Loader',
            ),
            'default' => 'number',
        ),

    )
);
$this->sections[] = array(
    'title' => esc_html__('Shop Single', 'auriane'),
    'id' => 'shop_single',
    'subsection' => true,
    'fields' => array(
         array(
            'id'       => 'product-single-title-bar-switch',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Switch On Title Bar', 'auriane'),
            'default'  => true,
         ), 
         array(
            'id'       => 'product-single-breadcrumb',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Switch On Breadcrumb Content Right', 'auriane'),
            'default'  => true,
         ),  
         array(
            'id' => 'select-titlebar-shop-single',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for title bar elementor', 'auriane'),
            'desc' => esc_html__('Select layout for title bar elementor from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
        array(
            'id' => 'shop_single_layout',
            'type' => 'select',
            'title' => esc_html__('Shop Layout', 'auriane'),
            'options' => array(
                'default' => 'Default',
                'gid_two_coloms' => 'Grid 2 Columns',
                'vertical' => 'Vertical',
                'vertical_metro' => 'Vertical Metro'
            ),
            'default' => 'default',
        ),
        array(
            'id' => 'shop_single_thumbnail_position',
            'type' => 'select',
            'title' => esc_html__('Thumbnail Position', 'auriane'),
            'options' => array(
                'left' => 'Left',
                'right' => 'Right',
                'bottom' => 'Bottom',
                'bottom2' => 'Bottom 4 Item',
            ),
            'default' => 'left',
        ),
        array(
            'id' => 'shop_related_item',
            'type' => 'select',
            'title' => esc_html__('Select Related Item Number', 'auriane'),
            'options' => array(
                '3' => '3 Item',
                '4' => '4 Item',
            ),
            'default' => '4',
        ),
        array(
            'id' => 'shop_related_layout',
            'type' => 'select',
            'title' => esc_html__('Related Layout', 'auriane'),
            'options' => array(
                'layout1' => 'Layout 1',
                'layout2' => 'Layout 2',
                'layout3' => 'Layout 3',
                'layout4' => 'Layout 4',
            ),
            'default' => 'layout1',
        ),
		array(
    		'id'       => 'shop_single_info_more',
    		'type'     => 'editor',
    		'args'     => array(
    			'teeny'         => false,
    			'wpautop'       => false,
    			'quicktags'     => 1,
    			'textarea_rows' => 10,
    		),
    		'title'    => esc_html__( 'Shop Single Info More', 'auriane' ),
    		'desc'     => esc_html(	'Displat info Shipping tax ....'), 
    	),
        array(
    		'id'       => '_shipping_content_tab',
    		'type'     => 'editor',
    		'args'     => array(
    			'teeny'         => false,
    			'wpautop'       => false,
    			'quicktags'     => 1,
    			'textarea_rows' => 10,
    		),
    		'title'    => esc_html__( 'Shipping & Delivery ', 'auriane' ),
    	),
         array(
    		'id'       => 'size-guide-content',
    		'type'     => 'editor',
    		'args'     => array(
    			'teeny'         => false,
    			'wpautop'       => false,
    			'quicktags'     => 1,
    			'textarea_rows' => 14,
    		),
    		'title'    => esc_html__( 'Size Guide Content', 'auriane' ),
    	),
        array(
            'id' => 'size-guide-use',
            'type' => 'select',
            'multi' => false,
            'options'  => jws_product_rent_available_fields(),
            'title' => esc_html__('Select Attributes Use Size Guide.', 'auriane'),
        ),
         array(
            'id'       => 'product-single-related',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Enable Related', 'auriane'),
            'default'  => true,
         ), 
         array(
            'id'       => 'product-single-share',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Enable Share', 'auriane'),
            'default'  => true,
         ), 
		array(
			'id' => 'enable_zoom',
			'type'     => 'switch', 
			'title' => esc_html__('Enable Zoom Main Image', 'auriane'),
			'subtitle' => __('Switch on to enable zoom image', 'auriane'),
			'default' => true,
		),
    )
);

$this->sections[] = array(
    'title' => esc_html__('Wait Product Form', 'auriane'),
    'id' => 'wait_product',
    'subsection' => true,
    'fields' => array(
       array(
            'id'       => 'title_form_waitlist',
            'type'     => 'text',
            'title'    =>  esc_html__('Text Top Form', 'auriane'),
            'default'  => 'Notify me when this product is available:'
      ), 
      array(
            'id'       => 'placeholder_input_waitlist',
            'type'     => 'text',
            'title'    =>  esc_html__('Placeholder Input Waitlist', 'auriane'),
            'default'  => 'Your email address...'
      ), 
      array(
            'id'       => 'submit_waitlist',
            'type'     => 'text',
            'title'    =>  esc_html__('Text Submit', 'auriane'),
            'default'  => 'Send'
      ), 
      array(
            'id'       => 'subject_waitlist_email',
            'type'     => 'text',
            'title'    =>  esc_html__('Subject Waitlist Email', 'auriane'),
            'default'  => 'A product you are waiting for is back in stock'
      ), 
       array(
            'id'       => 'email_heading_waitlist',
            'type'     => 'text',
            'title'    =>  esc_html__('Email Heading Waitlist', 'auriane'),
            'default'  => '{product_title} is now back in stock on {blogname}'
      ),
       array(
            'id'       => 'email_content_waitlist',
            'type'     => 'textarea',
            'title'    =>  esc_html__('Email Content Waitlist', 'auriane'),
            'default'  => 'Hi, {product_title} is now back in stock on {blogname}. You have been sent this email because your email address was registered in a waiting list for this product. If you would like to purchase {product_title}, please visit the following link: {product_link}'
      ),
    )
);


$this->sections[] = array(
    'title' => esc_html__('Questions & Answers', 'auriane'),
    'id' => 'questions_answers',
    'subsection' => true,
    'fields' => array(
         array(
            'id'       => 'auestions-enble',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Enble Questions & Answers', 'auriane'),
            'default'  => true,
            'desc'      =>  esc_html__('Show Questions & Answers To Single Product.', 'auriane'),
        ),
        array(
            'id'        => 'auestions-number',
            'type'      => 'slider',
            'title'     =>  esc_html__('Number Questions & Answers Display', 'auriane'),
            "default"   => 3,
            "min"       => 1,
            "step"      => 1,
            "max"       => 99,
            'display_value' => 'label'
        ),
 
    )
);



$this->sections[] = array(
    'title' => esc_html__('My Account', 'auriane'),
    'id' => 'shop_account',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'select-shop-form-login',
            'type' => 'select',
            'data' => 'posts',
            'args' => array('post_type' => array('hf_template'), 'posts_per_page' => -1),
            'title' => esc_html__('Select layout for header elementor', 'auriane'),
            'desc' => esc_html__('Select layout for header elementor from: ', 'auriane') . '<a href="' . esc_url(admin_url('/edit.php?post_type=hf_template')) . '" target="_blank">Header Footer Template</a>',
        ),
    )
);

$this->sections[] = array(
    'title' => esc_html__('Checkout', 'auriane'),
    'id' => 'shop_checkout',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'checkout_layout',
            'type' => 'select',
            'title' => esc_html__('Chooose Checkout Layout', 'auriane'),
            'options' => array(
                'default' => 'Default',
                'modern' => 'Modern',
            ),
            'default' => 'modern',
        ),  
        array(
            'id'       => 'checkout_switch',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Checkout Switch', 'auriane'),
            'default'  => true,
        ),
        array(
            'id' => 'checkout_coupon_text',
            'type' => 'text',
            'title' => esc_html__('Checkout Coupon Modern Text', 'auriane'),
        ),
    )
);


$this->sections[] = array(
    'title' => esc_html__('Wishlist', 'auriane'),
    'id' => 'wishlist',
    'subsection' => true,
    'fields' => array(
       			array (
				'id'       => 'wishlist',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable wishlist', 'auriane' ),
				'subtitle' => esc_html__( 'Enable wishlist functionality built in with the theme. Read more information in our documentation.', 'auriane' ),
				'default'  => true
			),
			array(
				'id'       => 'wishlist_page',
				'type'     => 'select',
				'multi'    => false,
				'data'     => 'posts',
				'args'     => array( 'post_type' =>  array( 'page' ), 'numberposts' => -1 ),
				'title'    => esc_html__( 'Wishlist page', 'auriane' ),
				'subtitle' => esc_html__( 'Select a page for wishlist table. It should contain the shortcode: [auriane_wishlist]', 'auriane' ),
			),
			array (
				'id'       => 'empty_wishlist_text',
				'type'     => 'textarea',
				'title'    => esc_html__('Empty wishlist text', 'auriane'),
				'subtitle' => esc_html__('Text will be displayed if user don\'t add any products to wishlist', 'auriane'),      
				'default'  => 'No products added in the wishlist list. You must add some products to wishlist them.<br> You will find a lot of interesting products on our "Shop" page.',
				'class'   => 'without-border'
			),
    )
);

// -> START Typography
$this->sections[] = array(
    'title' => esc_html__('Typography', 'auriane'),
    'id' => 'typography',
    'icon' => 'el el-text-width',
    'fields' => array(
        array(
            'id' => 'opt-typography-body',
            'type' => 'typography',
            'title' => esc_html__('Body Font', 'auriane'),
            'subtitle' => esc_html__('Specify the body font properties.', 'auriane'),
            'google' => true,
            'color' => true,
            'subsets' => false,
            'output' => array('body'),
            'default' => array(
                'font-size' => '17px',
                'font-family' => 'Heebo',
                'font-weight' => '400',
                'line-height' => '28px'
            ),
        ),
        
         array(
            'id' => 'opt-typography-font2',
            'type' => 'typography',
            'title' => esc_html__('Font 2', 'auriane'),
            'google' => true,
            'color' => false,
            'text-align' => false,
            'letter-spacing' => false,
            'text-transform' => false,
            'font-size' => false,
            'subsets' => false,
            'font-weight' => false,
            'line-height' => false
        ),
        
        array(
            'id' => 'opt-typography-h1',
            'type' => 'typography',
            'title' => esc_html__('H1 Font', 'auriane'),
            'subtitle' => esc_html__('Specify the h1 font properties.', 'auriane'),
            'google' => true,
            'subsets' => false,
            'color' => false,
            'output' => array('h1'),
        ),
        array(
            'id' => 'opt-typography-h2',
            'type' => 'typography',
            'title' => esc_html__('H2 Font', 'auriane'),
            'subtitle' => esc_html__('Specify the h2 font properties.', 'auriane'),
            'google' => true,
            'subsets' => false,
            'color' => false,
            'output' => array('h2'),
        ),
        array(
            'id' => 'opt-typography-h3',
            'type' => 'typography',
            'title' => esc_html__('H3 Font', 'auriane'),
            'subtitle' => esc_html__('Specify the h3 font properties.', 'auriane'),
            'google' => true,
            'subsets' => false,
            'color' => false,
            'output' => array('h3'),
        ),
        array(
            'id' => 'opt-typography-h4',
            'type' => 'typography',
            'title' => esc_html__('H4 Font', 'auriane'),
            'subtitle' => esc_html__('Specify the h4 font properties.', 'auriane'),
            'google' => true,
            'color' => false,
            'subsets' => false,
            'output' => array('h4'),
        ),
        array(
            'id' => 'opt-typography-h5',
            'type' => 'typography',
            'title' => esc_html__('H5 Font', 'auriane'),
            'subtitle' => esc_html__('Specify the h5 font properties.', 'auriane'),
            'google' => true,
            'subsets' => false,
            'color' => false,
            'output' => array('h5'),
        ),
        array(
            'id' => 'opt-typography-h6',
            'type' => 'typography',
            'title' => esc_html__('H6 Font', 'auriane'),
            'subtitle' => esc_html__('Specify the h6 font properties.', 'auriane'),
            'google' => true,
            'color' => false,
            'subsets' => false,
            'output' => array('h6'),
        ),
        
        
  
  
    )
);

// -> START API Fields
$this->sections[] = array(
    'title' => esc_html__('API And Other Setting', 'auriane'),
    'id' => 'api',
    'customizer_width' => '300px',
    'icon' => 'el el-network',
    'fields' => array(
        array(
    		'id'     => 'google-api-section-start',
    		'type'   => 'section',
    		'title'  => esc_html__( 'Google Settings', 'auriane' ),
    		'indent' => true,
    	),
        array(
            'id' => 'google_api',
            'type' => 'text',
            'title' => esc_html__('Google API', 'auriane'),
            'default' => '',
        ),
        array(
    		'id'     => 'google-api-section-end',
    		'type'   => 'section',
    		'indent' => false,
    	),

        array(
    		'id'     => 'instagram-token-section-start',
    		'type'   => 'section',
    		'title'  => esc_html__( 'Instagram Settings', 'auriane' ),
    		'indent' => true,
    	),
        array(
            'id' => 'instagram_token',
            'type' => 'text',
            'title' => esc_html__('Instagram Token', 'auriane'),
            'default' => '',
        ),
        array(
    		'id'     => 'facebook-section-start',
    		'type'   => 'section',
    		'title'  => esc_html__( 'Facebook Login Settings', 'auriane' ),
    		'indent' => true,
    	),
        array(
            'id' => 'facebook_app_id',
            'type' => 'text',
            'title' => esc_html__('App Id', 'auriane'),
        ),
        array(
            'id' => 'facebook_app_secret',
            'type' => 'text',
            'title' => esc_html__('App Secret', 'auriane'),
        ),
        
        array(
    		'id'     => 'google-section-start',
    		'type'   => 'section',
    		'title'  => esc_html__( 'Google Login Settings', 'auriane' ),
    		'indent' => true,
    	),
        array(
            'id' => 'google_app_id',
            'type' => 'text',
            'title' => esc_html__('App Id', 'auriane'),
        ),
        array(
            'id' => 'google_app_secret',
            'type' => 'text',
            'title' => esc_html__('App Secret', 'auriane'),
        ),
   
    )
);

// -> START API Fields
$this->sections[] = array(
    'title' => esc_html__('Toolbar Popup', 'auriane'),
    'id' => 'toolbarfix',
    'customizer_width' => '300px',
    'icon' => 'el el-minus',
    'fields' => array(
        array (
				'id'       => 'toolbar_fix',
				'type'     => 'switch',
				'title'    => esc_html__( 'Toolbar Nav For Tablet And Mobile', 'auriane' ),
				'default'  => true
		),
        array (
				'id'       => 'toolbar_shop',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Shop', 'auriane' ),
				'default'  => true
		),
        array (
				'id'       => 'toolbar_search',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Search', 'auriane' ),
				'default'  => true
		),
        array (
				'id'       => 'toolbar_wishlist',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Wishlist', 'auriane' ),
				'default'  => true
		),
        array (
				'id'       => 'toolbar_account',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Account', 'auriane' ),
				'default'  => true
		),
        array(
            'id'         => 'toolbar_custom',
            'type'       => 'repeater',
            'title'      => __( 'Tool Bar Custom', 'meathouse' ),
            'subtitle'   => __( '', 'meathouse' ),
            'desc'       => __( '', 'meathouse' ),
            'group_values' => true, // Group all fields below within the repeater ID
            'item_name' => '', // Add a repeater block name to the Add and Delete buttons
            'fields'     => array(
                array(
                    'id'          => 'toolbar_custom_name',
                    'type'        => 'text',
                    'placeholder' => __( 'Name', 'meathouse' ),
                ),
                array(
                    'id'          => 'toolbar_custom_icon',
                    'type'        => 'text',
                    'placeholder' => __( 'Icon Class', 'meathouse' ),
                ),
                array(
                    'id'          => 'toolbar_custom_link',
                    'type'        => 'text',
                    'placeholder' => __( 'Link', 'meathouse' ),
                ),

            )
        )

    )
);

// -> START API Fields
$this->sections[] = array(
    'title' => esc_html__('Newsletter Popup', 'auriane'),
    'id' => 'newsletter_popup',
    'customizer_width' => '300px',
    'icon' => 'el el-envelope',
    'fields' => array(
       array(
            'id'       => 'newsletter_enble',
            'type'     => 'switch', 
            'title'    =>  esc_html__('Enble Newsletter', 'auriane'),
            'default'  => false,
            'desc'      =>  esc_html__('Enble Newsletter Popup For Site.', 'auriane'),
       ), 
       array(
    		'id'       => 'newsletter_content',
    		'type'     => 'editor',
    		'args'     => array(
    			'teeny'         => false,
    			'wpautop'       => false,
    			'quicktags'     => 1,
    			'textarea_rows' => 10,
    		),
    		'title'    => esc_html__( 'Content Form', 'auriane' ),
    	),
        array(
            'id' => 'newsletter_no_thank',
            'type' => 'text',
            'title' => esc_html__('No Thank Text', 'auriane'),
       ),
       array(         
            'id'       => 'newsletter_bg',
            'type'     => 'background',
            'title'    =>  esc_html__('Background', 'auriane'),
            'subtitle' =>  esc_html__('background with image, color, etc.', 'auriane'),
            'desc'     =>  esc_html__('Change background for newsletter popup.', 'auriane'),
            'default'  => array(
                'background-color' => '#ebebeb',
            ),
            'output' => array('.newsletter-bg'),
        ),
    )
);

if (file_exists(dirname(__FILE__) . '/../README.md')) {
    $this->sections[] = array(
        'icon' => 'el el-list-alt',
        'title' => esc_html__('Documentation', 'auriane'),
        'fields' => array(
            array(
                'id' => '17',
                'type' => 'raw',
                'markdown' => true,
                'content_path' => dirname(__FILE__) . '/../README.md', // FULL PATH, not relative please
                //'content' => 'Raw content here',
            ),
        ),
    );

}
				
            }

            public function setHelpTabs() {

                // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-1',
                    'title'   => __( 'Theme Information 1', 'auriane' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'auriane' )
                );

                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-2',
                    'title'   => __( 'Theme Information 2', 'auriane' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'auriane' )
                );

                // Set the help sidebar
                $this->args['help_sidebar'] = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'auriane' );
            }

            /**
             * All the possible arguments for Redux.
             * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
             * */
            public function setArguments() {

                $theme = wp_get_theme(); // For use with some settings. Not necessary.

                $this->args = array(
                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'             => 'jws_option',
                    // This is where your data is stored in the database and also becomes your global variable name.
                    'display_name'         => $theme->get( 'Name' ),
                    // Name that appears at the top of your panel
                    'display_version'      => $theme->get( 'Version' ),
                    // Version that appears at the top of your panel
                    'menu_type'            => 'submenu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'       => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'           => __( 'Theme Options', 'auriane' ),
                    'page_title'           => __( 'Theme Options', 'auriane' ),
                    // You will need to generate a Google API key to use this feature.
                    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                    'google_api_key'       => '',
                    // Set it you want google fonts to update weekly. A google_api_key value is required.
                    'google_update_weekly' => false,
                    // Must be defined to add google fonts to the typography module
                    'async_typography'     => true,
                    // Use a asynchronous font on the front end or font string
                    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                    'admin_bar'            => true,
                    // Show the panel pages on the admin bar
                    'admin_bar_icon'     => 'dashicons-portfolio',
                    // Choose an icon for the admin bar menu
                    'admin_bar_priority' => 50,
                    // Choose an priority for the admin bar menu
                    'global_variable'      => '',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'             => false,
                    // Show the time the page took to load, etc
                    'update_notice'        => false,
                    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                    'customizer'           => true,
                    // Enable basic customizer support
                    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                    // OPTIONAL -> Give you extra features
                    'page_priority'        => null,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'          => 'jws_settings.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'     => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'            => '',
                    // Specify a custom URL to an icon
                    'last_tab'             => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'            => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'            => 'jws_option',
                    // Page slug used to denote the panel
                    'save_defaults'        => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'         => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'         => '',
                    // What to print by the field's title if the value shown is default. Suggested: *
                    'show_import_export'   => true,
                    // Shows the Import/Export panel when not used as a field.

                    // CAREFUL -> These options are for advanced use only
                    'transient_time'       => 60 * MINUTE_IN_SECONDS,
                    'output'               => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'           => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'             => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                    'system_info'          => false,
                    // REMOVE

                    // HINTS
                    'hints'                => array(
                        'icon'          => 'icon-question-sign',
                        'icon_position' => 'right',
                        'icon_color'    => 'lightgray',
                        'icon_size'     => 'normal',
                        'tip_style'     => array(
                            'color'   => 'light',
                            'shadow'  => true,
                            'rounded' => false,
                            'style'   => '',
                        ),
                        'tip_position'  => array(
                            'my' => 'top left',
                            'at' => 'bottom right',
                        ),
                        'tip_effect'    => array(
                            'show' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'mouseover',
                            ),
                            'hide' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'click mouseleave',
                            ),
                        ),
                    )
                );
				
                // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
                $this->args['share_icons'][] = array(
                    'url'   => '#',
                    'title' => 'Visit us on GitHub',
                    'icon'  => 'el-icon-github'
                    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
                );
                $this->args['share_icons'][] = array(
                    'url'   => '#',
                    'title' => 'Like us on Facebook',
                    'icon'  => 'el-icon-facebook'
                );
                $this->args['share_icons'][] = array(
                    'url'   => '#',
                    'title' => 'Follow us on Twitter',
                    'icon'  => 'el-icon-twitter'
                );
                $this->args['share_icons'][] = array(
                    'url'   => '#',
                    'title' => 'Find us on LinkedIn',
                    'icon'  => 'el-icon-linkedin'
                );
            }

            public function validate_callback_function( $field, $value, $existing_value ) {
                $error = true;
                $value = 'just testing';

                /*
              do your validation

              if(something) {
                $value = $value;
              } elseif(something else) {
                $error = true;
                $value = $existing_value;
                
              }
             */

                $return['value'] = $value;
                $field['msg']    = 'your custom error message';
                if ( $error == true ) {
                    $return['error'] = $field;
                }

                return $return;
            }

            public function class_field_callback( $field, $value ) {
                print_r( $field );
                echo '<br/>CLASS CALLBACK';
                print_r( $value );
            }

        }

        global $reduxConfig;
        $reduxConfig = new Redux_Framework_theme_config();
    } else {
        echo "The class named Redux_Framework_theme_config has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ):
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    endif;

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ):
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error = true;
            $value = 'just testing';

            /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            
          }
         */

            $return['value'] = $value;
            $field['msg']    = 'your custom error message';
            if ( $error == true ) {
                $return['error'] = $field;
            }

            return $return;
        }
    endif;


    if( ! function_exists('jws_theme_get_option') ){
        function jws_theme_get_option($name, $default=false){
            global $jws_option;
            return isset( $jws_option[ $name ] ) ? $jws_option[ $name ] : $default;
        }
    }

    if( ! function_exists('jws_theme_update_option') ){
        function jws_theme_update_option($name, $value){
            global $jws_option;
            $jws_option[ $name ] = $value;
        }
    }

