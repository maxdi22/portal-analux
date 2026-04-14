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

$page_turn_off_footer = get_post_meta(get_the_ID(), 'turn_off_footer', true); 


?>
	</div><!-- #content -->
	<footer id="colophon" class="site-footer">
        <?php 
     
            if((isset($page_turn_off_footer) && !$page_turn_off_footer) || is_search()) {
               if(function_exists('jws_footer')) jws_footer();   
            }
        ?>
	</footer><!-- #colophon -->

</div><!-- #page -->
   
<?php wp_footer(); ?>
</body>
</html>
