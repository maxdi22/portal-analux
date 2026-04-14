<?php
/**
 * Template for displaying search forms in Twenty Seventeen
 *
 * @package WordPress
 * @subpackage auriane
 * @since 1.0
 * @version 1.0
 */

?>


<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <button type="submit" class="search-submit"><span class="jws-icon-magnifying-glass-light"></span></button>
	<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'auriane' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <div class="jws-search-results"></div>
</form>
