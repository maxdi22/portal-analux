<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage auriane
 * @since 1.0.0
 */

get_header();
global $jws_option;
wp_enqueue_script( 'stick-content', JWS_URI_PATH. '/assets/js/sticky_content.js', array(), '', true );
if((isset($jws_option['position_sidebar']) && $jws_option['position_sidebar'] == 'full') || !is_active_sidebar( 'sidebar-main' ) ) {
   $content_col = ' col-12 jws-blog-element'; 
   $sidebar_col = 'postt_sidebar';
   $class = ' no_sidebar';
}else {
   $content_col = 'post_content col-xl-8 col-lg-12 col-12 jws-blog-element';
   $sidebar_col = 'post_sidebar col-xl-4 col-lg-12 col-12'; 
   $class = ' has_sidebar'; 
}

?>
<div id="primary" class="content-area">
		<main id="main" class="site-main jws-blog-archive">
        <div class="container">
        <div class="row">
             <?php if(isset($jws_option['position_sidebar']) && $jws_option['position_sidebar'] == 'left') : ?>
                <div class="<?php echo esc_attr($sidebar_col); ?>">
                    <div class="main-sidebar">
                        	<?php
                                if (isset($jws_option['select-sidebar-post']) && !empty($jws_option['select-sidebar-post'])) { 
                                             echo do_shortcode('[hf_template id="' . $jws_option['select-sidebar-post'] . '"]'); 
                                }else {
                                   if ( is_active_sidebar( 'sidebar-main' ) ) {
                        			     dynamic_sidebar( 'sidebar-main' );
                        		   } 
                                }	
    		                 ?>
                    </div>
                </div>
            <?php endif; ?> 
            <div class="<?php echo esc_attr($content_col); ?>">
                <div class="jws_blog_grid row">
                	<?php if ( have_posts() ) :
            			while ( have_posts() ) :
            				the_post();
            
            				/*
            				 * Include the Post-Format-specific template for the content.
            				 * If you want to override this in a child theme, then include a file
            				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
            				 */
            				get_template_part( 'template-parts/content/content' );
            
            				// End the loop.
            			endwhile;
            
            
            			// If no content, include the "No posts found" template.
            		else :
            			get_template_part( 'template-parts/content/content', 'none' );
            
            		endif;
            		?>
                    </div> 
                    <?php global $wp_query;   echo jws_query_pagination($wp_query); ?>
            </div>
            <?php if((isset($jws_option['position_sidebar']) && $jws_option['position_sidebar'] == 'right') || is_active_sidebar( 'sidebar-main' ) ) : ?>
                <div class="<?php echo esc_attr($sidebar_col); ?>">
                    <div class="main-sidebar jws_sticky_move">
                        	<?php
                                if (isset($jws_option['select-sidebar-post']) && !empty($jws_option['select-sidebar-post'])) { 
                                             echo do_shortcode('[hf_template id="' . $jws_option['select-sidebar-post'] . '"]'); 
                                }else {
                                   if ( is_active_sidebar( 'sidebar-main' ) ) {
                        			     dynamic_sidebar( 'sidebar-main' );
                        		   } 
                                }	
    		                 ?>
                    </div>
                </div>
            <?php endif; ?>    
        </div>
	
        </div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();