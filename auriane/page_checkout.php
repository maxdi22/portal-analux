<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 * Template Name: Checkout Page
 * @package WordPress
 * @subpackage auriane
 * @since 1.0.0
 */
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
global $jws_option; 
$layout = jws_checkout_layout();
if($layout == 'modern' && !is_wc_endpoint_url( 'order-received' )) {
   get_header('modern'); 
}else {
   get_header(); 
}


?>

<div id="primary" class="content-area">
		<main id="main" class="site-main">
            <?php if(!is_wc_endpoint_url( 'order-received' ) && jws_theme_get_option('checkout_switch')) : ?>
            <div class="switch_checkout_layout">
                <a class="sw-classic<?php echo ''.$layout == 'classic' ? ' active' : ''; ?>" href="<?php echo wc_get_checkout_url().'?checkout=classic'; ?>" title="<?php echo esc_attr__('Classic', 'auriane'); ?>" rel="nofollow"><?php echo esc_attr__('Classic', 'auriane'); ?></a>
                <a class="sw-modern<?php echo ''.$layout == 'modern' ? ' active' : ''; ?>" href="<?php echo wc_get_checkout_url().'?checkout=modern'; ?>" title="<?php echo esc_attr__('Modern', 'auriane'); ?>" rel="nofollow"><?php echo esc_attr__('Modern', 'auriane'); ?></a>
            </div>
            <?php endif; ?>
			<?php
            if (!defined('aurianecore') ) echo '<div class="container page-no-builder">';
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.

				if ( comments_open() || get_comments_number() ) {
			
					comments_template();
                   
				}

			endwhile; // End of the loop.
            if (!defined('aurianecore') ) echo '</div>';
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php

get_footer(); 