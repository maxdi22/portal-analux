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
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
get_header();

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

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
