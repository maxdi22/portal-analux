<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Zahar
 * @since 1.0.0
 */

get_header();
wp_enqueue_script('addthis-widget');
global $jws_option; 
$layout_custom = get_post_meta(get_the_ID(), 'project_layout', true);

if(!empty($layout_custom) && $layout_custom != 'none') {
   $layout = $layout_custom; 
}else {
  $layout = isset($jws_option['projects_single_layout']) ? $jws_option['projects_single_layout'] : 'layout1';  
}


?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
            <div class="single-projects-container <?php echo esc_attr($layout); if($layout == 'layout4' || $layout == 'layout5') echo ' layout3'; ?>"> 
                        <?php
                			/* Start the Loop */
                			while ( have_posts() ) :
                				the_post();
                                the_content();
                				
                			endwhile; // End of the loop.
            			?>
            </div>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();