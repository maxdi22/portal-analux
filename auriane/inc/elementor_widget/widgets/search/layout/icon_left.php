<div class="jws-search-form">
    	<form role="search" method="get" class="searchform jws-ajax-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" data-count="20" data-post_type="product" data-thumbnail="1" data-price="1">
            <input type="text" class="s" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'auriane' ); ?>..." value="<?php echo get_search_query(); ?>" name="s" />
			<input type="hidden" name="post_type" value="product">
			<button type="submit" class="searchsubmit">
		       <?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
			</button>
            <span class="form-loader"></span>
		</form>
</div>