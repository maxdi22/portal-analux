<?php 
    global $jws_option;
    $feature_cat = (isset($jws_option['select_rent_features']) && !empty($jws_option['select_rent_features'])) ? $jws_option['select_rent_features'] : '';
    if(!empty($feature_cat)) :  


    $content = get_the_terms( get_the_ID(), 'pa_'.$feature_cat );

    if(!empty($content) && !is_wp_error($content)) { ?> 
        <div class="f-include">
            <div class="left"><?php echo esc_html__('Include','auriane'); ?></div>
            <div class="right">
                <?php 
                    if(!empty($content)) {
                         $include = array();   
                         foreach ($content  as $term  ) {
                            $include[] = $term->name;
                            $product_cat_id = $term->term_id;
                            $product_cat_name = $term->name;
                            echo '<span><i class="jws-icon-icon_check"></i>'.$product_cat_name.'</span>';
                        }   
                    }else {
                        echo '<span>'.esc_html__('not found','auriane').'</span>';
                    }   
              
                ?>
            </div>
        </div>
        
        
        <?php 
        $categories = get_categories( array(
			'orderby' => 'name',
			'order'   => 'ASC',
			'taxonomy' => 'pa_'.$feature_cat,
            'hide_empty' => false
		) );
      
        if(!empty($categories)) :
        
        $exclude = array();
      
        foreach ($categories as $category) {
            $exclude[] = $category->name;  
        } 
          
        $result = array_diff($exclude,$include);
            if(!empty($result)) { 
        ?>
        <div class="f-exclude">
            <div class="left"><?php echo esc_html__('Exclude','auriane'); ?></div>
            <div class="right">
                <?php 
                    foreach ($result  as $name  ) {
                        echo '<span><i class="jws-icon-icon_close"></i>'.$name.'</span>';
                    }   
                ?>
            </div>
        </div>
        <?php } endif; ?>
    <?php }
endif;
?>