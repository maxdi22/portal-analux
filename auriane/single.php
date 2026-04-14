<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage auriane
 * @since 1.0.0
 */

get_header();
wp_enqueue_script( 'stick-content', JWS_URI_PATH. '/assets/js/sticky_content.js', array(), '', true );
global $jws_option; 

$sidebar = (isset($_GET['sidebar']) && $_GET['sidebar'] == 'full' ) ? $_GET['sidebar'] : (isset($jws_option['position_sidebar_blog_single']) && $jws_option['position_sidebar_blog_single'] ? $jws_option['position_sidebar_blog_single'] : 'right');

if($sidebar == 'full'|| ((did_action( 'elementor/loaded' )) && \Elementor\Plugin::$instance->editor->is_edit_mode()) || empty($jws_option)) {
   $content_col = 'col-xl-12 col-12'; 
   $sidebar_col = 'postt_sidebar sidebar-has_sidebar main-sidebar';
   $class = ' no_sidebar';
}else {
   $content_col = 'post_content col-xl-8 col-lg-12 col-12';
   $sidebar_col = 'post_sidebar sidebar-has_sidebar main-sidebar col-xl-4 col-lg-12 col-12'; 
   $class = ' has_sidebar'; 
}
$position_sidebar = (isset($jws_option['position_sidebar_blog_single'])) ? $jws_option['position_sidebar_blog_single'] : 'right';
$class .= ' sidebar_'.$position_sidebar;
$layout = (isset($jws_option['blog_single_layout'])) ? $jws_option['blog_single_layout'] : 'layout1';
if(isset($_GET['layout'])) {
  $layout =  $_GET['layout']; 
}
$class .= ' layout_'.$layout;

$format = get_post_format();
?>
	<div id="primary" class="content-area single_blog">
		<main id="main" class="site-main">
            <div class="single-blog<?php echo esc_attr($class); ?>">
            <div class="container">
                <div class="row">
                    <?php if($sidebar == 'left') : ?>
                        <div class="<?php echo esc_attr($sidebar_col); ?>">
                            <?php
                                if (isset($jws_option['select-sidebar-post-single']) && !empty($jws_option['select-sidebar-post-single'])) { 
                                             echo do_shortcode('[hf_template id="' . $jws_option['select-sidebar-post-single'] . '"]'); 
                                }else {
                                   if ( is_active_sidebar( 'sidebar-single-blog' ) ) {
                        			     dynamic_sidebar( 'sidebar-single-blog' );
                        		   } 
                                }	
    		                 ?>
                        </div>
                    <?php endif; ?>    
                    <div class="<?php echo esc_attr($content_col); ?>">
                        <?php
                			/* Start the Loop */
                			while ( have_posts() ) :
                				the_post();
                                if(get_post_format() == 'quote') {
                                   get_template_part( 'template-parts/content/blog/single/format/quote' ); 
                                }else {
                                   get_template_part( 'template-parts/content/blog/single/layout/'.$layout.'' );
                                }
                                the_posts_navigation();
                			endwhile; // End of the loop.
            			?>
                    </div>
                    <?php if($sidebar == 'right') : ?>
                        <div class="<?php echo esc_attr($sidebar_col); ?>">
                              <div class="main-sidebar jws_sticky_move">
                                    <div class="jws-filter-modal">
                                    <div class="modal-overlay"></div>
                                    <div class="siderbar-inner jws-scrollbar modal-content sidebar">
                                    <div class="modal-top">
                                        <span class="modal-title">FILTERS</span>
                                        <span class="modal-close">Close</span>
                                    </div>
                                	<?php
                                         if (isset($jws_option['select-sidebar-post-single']) && !empty($jws_option['select-sidebar-post-single'])) { 
                                                 echo do_shortcode('[hf_template id="' . $jws_option['select-sidebar-post-single'] . '"]'); 
                                        }else {
                                           if ( is_active_sidebar( 'sidebar-single-blog' ) ) {
                                			     dynamic_sidebar( 'sidebar-single-blog' );
                                		   } 
                                        }	
            		                 ?>
                                     </div>
                                     </div>
                            </div>
                        </div>
                    <?php endif; ?> 
                </div>
                <?php 
                if(isset($jws_option['select-content-before-footer-blog-single']) && !empty($jws_option['select-content-before-footer-blog-single'])) { ?>
                    <div class="content-before-footer">
                        <?php echo do_shortcode('[hf_template id="'.esc_attr($jws_option['select-content-before-footer-blog-single']).'"]'); ?> 
                    </div> 
                <?php } ?>
           </div>      
            </div>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();