<?php
/**
 * Displays the post header
 *
 * @package WordPress
 * @subpackage auriane
 * @since 1.0.0
 */
global $jws_option; 

$page_turn_off_header = get_post_meta(get_the_ID(), 'turn_off_header', true);
$page_header = get_post_meta(get_the_ID(), 'page_select_header', true);

?>  
<header id="masthead" class="site-header">
	<div class="site-branding-container">
    	<?php	
            if ((isset($jws_option['select-header']) && !empty($jws_option['select-header'])) && (isset($page_turn_off_header) && !$page_turn_off_header) ) { ?>
            <div class="jws_header">
                <?php 
                    Jws_Elementor::display_header();    
                 ?>
            </div>
        <?php } ?>	
	</div><!-- .site-branding-container -->
</header><!-- #masthead -->
