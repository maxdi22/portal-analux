<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage auriane
 * @since 1.0.0
 */
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
} 
global $jws_option;
get_header('404');


?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main">
            <?php if(class_exists('Jws_Elementor') && Jws_Elementor::get_404_id()) {
                
                Jws_Elementor::display_404();
                
            }else { ?>
            <div class="container">
                <div class="error-404 not-found">
    				<header class="page-header">
    					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'auriane' ); ?></h1>
    				</header><!-- .page-header -->
    
    				<div class="page-content">
    					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'auriane' ); ?></p>
    					<?php get_search_form(); ?>
    				</div><!-- .page-content -->
    			</div><!-- .error-404 -->
             </div>     
            <?php } ?> 
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer('404');
