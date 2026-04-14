<button>
    <?php 
        if(!empty($settings['icon']['value'])) {
          \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );  
        }else {
          echo '<i aria-hidden="true" class="  jws-icon-magnifying-glass-light"></i>';  
        }
    ?>
</button>
 <div class="form_content_popup"> 
<div class="form-content-head row row-eq-height">
    <div class="col-xl-6 col-6">
        <h3 class="heading-form"><?php echo esc_html__('Search','auriane'); ?></h3>
    </div>
    <div class="col-xl-6 col-6">
        <span class="close-form">
            <?php if(!empty($settings['icon3']['value'])) {
              \Elementor\Icons_Manager::render_icon( $settings['icon3'], [ 'aria-hidden' => 'true' ] );  
            }else {
                echo '<i aria-hidden="true" class="jws-icon-cross"></i>';
            }  ?>
        </span>
    </div>
</div>
<div class="jws-search-form">
    	<form role="search" method="get" class="searchform jws-ajax-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" data-count="20" data-post_type="product" data-thumbnail="1" data-price="1">
			<?php 
            
                if($settings['query_type'] == 'all') {
                    
                }
                
                $args = array( 
        			'hide_empty' => 1,
        			'parent' => 0
        		);
        		$terms = get_terms('product_cat', $args);
        		if( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        			?>
        			<div class="search-by-category input-dropdown">
        				
        					<input type="hidden" name="product_cat" value="0">
        					<div class="list-wrapper">
        						<ul>
        							<li><a class="active" href="#" data-val="0"><?php echo esc_html__('All categories','auriane');?></a></li>
        							<?php
        							    if($settings['query_type'] == 'manual' && !empty($settings['filter_categories'])) {
                                            foreach ( $settings['filter_categories'] as $terms ) {
                                                $term = get_term_by('slug', $terms, 'product_cat');
        								    	?>
        											<li><a href="#" data-val="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_attr( $term->name ); ?></a></li>
        								    	<?php
        								    }
                                        }else {
                                            foreach ( $terms as $term ) {
        								    	?>
        											<li><a href="#" data-val="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_attr( $term->name ); ?></a></li>
        								    	<?php
        								    }
                                        } 
        								
        								
        							?>
        						</ul>
        					</div>
        				
        			</div>
        			<?php
        		}
               
            ?>
            <input type="text" class="s" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'auriane' ); ?>..." value="<?php echo get_search_query(); ?>" name="s" />
			<input type="hidden" name="post_type" value="product">
			<button type="submit" class="searchsubmit">
		       <i aria-hidden="true" class="  jws-icon-magnifying-glass-thin"></i>
			</button>
            <span class="form-loader">
                <span class="jws-reset-search">
                    <i aria-hidden="true" class="jws-icon-cross"></i>
                </span>
            </span>
		</form>
        <div class="search-results-wrapper"><div class="jws-search-results jws_scrollbar"></div></div>
</div>   
</div>