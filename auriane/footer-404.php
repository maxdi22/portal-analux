<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage auriane
 * @since 1.0.0
 */

global $jws_option; 

$parallax = (isset($jws_option['footer-switch-parallax']) && $jws_option['footer-switch-parallax']) ? ' footer-parallax' : '';
?>
	</div><!-- #content -->
	<footer id="colophon" class="site-footer<?php echo esc_attr($parallax); ?>">
        <?php 
            if(isset($jws_option['404-off-footer']) && !$jws_option['404-off-footer']) {
                jws_footer();   
            }
        ?>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
