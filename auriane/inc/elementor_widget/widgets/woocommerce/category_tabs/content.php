<?php 

if($settings['display'] == 'grid') {
    $settings['columns_tablet'] = isset($settings['columns_tablet']) ? $settings['columns_tablet'] : $settings['columns'];
    $settings['columns_mobile'] = isset($settings['columns_mobile']) ? $settings['columns_mobile'] : $settings['columns'];
    $class_column = 'category-tab-item col-xl-' . $settings['columns'] . ' col-lg-' . $settings['columns_tablet'] . ' col-' . $settings['columns_mobile'] .'';
    $data_slick = '';
    $class_row = ' row';  
}else {
    $class_row = ' jws-slider';
    $class_column = ' category-tab-item slider-item slick-slide'; 
    $dots = ($settings['enable_dots'] == 'yes') ? 'true' : 'false';
    $arrows = ($settings['enable_nav'] == 'yes') ? 'true' : 'false';
    $autoplay = ($settings['autoplay'] == 'yes') ? 'true' : 'false';
    $pause_on_hover = ($settings['pause_on_hover'] == 'yes') ? 'true' : 'false';
    $infinite = ($settings['infinite'] == 'yes') ? 'true' : 'false';
    $variableWidth = ($settings['variablewidth'] == 'yes') ? 'true' : 'false';
    $center = ($settings['center'] == 'yes') ? 'true' : 'false';
    
    
    $settings['slides_to_show'] = isset($settings['slides_to_show']) && !empty($settings['slides_to_show']) ? $settings['slides_to_show'] : '1';
    $settings['slides_to_show_tablet'] = isset($settings['slides_to_show_tablet']) && !empty($settings['slides_to_show_tablet']) ? $settings['slides_to_show_tablet'] : $settings['slides_to_show'];
    $settings['slides_to_show_mobile'] = isset($settings['slides_to_show_mobile']) && !empty($settings['slides_to_show_mobile']) ? $settings['slides_to_show_mobile'] : $settings['slides_to_show'];
    
    $settings['slides_to_scroll'] = isset($settings['slides_to_scroll']) && !empty($settings['slides_to_scroll']) ? $settings['slides_to_scroll'] : '1';
    $settings['slides_to_scroll_tablet'] = isset($settings['slides_to_scroll_tablet']) && !empty($settings['slides_to_scroll_tablet']) ? $settings['slides_to_scroll_tablet'] : $settings['slides_to_scroll'];
    $settings['slides_to_scroll_mobile'] = isset($settings['slides_to_scroll_mobile']) && !empty($settings['slides_to_scroll_mobile']) ? $settings['slides_to_scroll_mobile'] : $settings['slides_to_scroll']; 
    
    
    $autoplay_speed = isset($settings['autoplay_speed']) ? $settings['autoplay_speed'] : '5000';
    $data_slick = 'data-slick=\'{"slidesToShow":'.$settings['slides_to_show'].' ,"slidesToScroll": '.$settings['slides_to_scroll'].', "autoplay": '.$autoplay.',"arrows": '.$arrows.', "dots":'.$dots.', "autoplaySpeed": '.$autoplay_speed.',"variableWidth":'.$variableWidth.',"pauseOnHover":'.$pause_on_hover.',"centerMode":'.$center.', "infinite":'.$infinite.',
    "speed": '.$settings['transition_speed'].', "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": '.$settings['slides_to_show_tablet'].',"slidesToScroll": '.$settings['slides_to_scroll_tablet'].'}},
    {"breakpoint": 768,"settings":{"slidesToShow": '.$settings['slides_to_show_mobile'].',"slidesToScroll": '.$settings['slides_to_scroll_mobile'].'}}]}\'';    
}




if(!empty($settings['image_size']['width']) && !empty($settings['image_size']['height'])) {
    $image_size = $settings['image_size']['width'].'x'.$settings['image_size']['height'];
 }else {
    $image_size = 'full';
 } 

if(!empty($settings['image_size2']['width']) && !empty($settings['image_size2']['height'])) {
    $image_size2 = $settings['image_size2']['width'].'x'.$settings['image_size2']['height'];
 }else {
    $image_size2 = 'full';
 }  




?>
<div class="jws-category-list">
<div class="category-content<?php echo esc_attr($class_row.' '.$settings['layouts']); ?>" <?php echo ''.$data_slick; ?>>
  <?php
        if($settings['filter_categories']){
            $i = 0;
            foreach ($settings['filter_categories'] as $product_cat_slug) {
                $product_cat = get_term_by('slug', $product_cat_slug, 'product_cat');
                $selected = '';
                if(isset($product_cat->slug)){
                    if (isset($settings['wc_attr']['product_cat']) && $settings['wc_attr']['product_cat'] == $product_cat->slug) {
                        $selected = 'jws-selected';
                    }
                
                        ?>
                            <div class="<?php echo esc_attr($class_column); ?>">
                                <a href="<?php echo esc_url(get_term_link($product_cat->slug, 'product_cat')); ?>">
                                    <?php 
                                        if($settings['layouts'] == 'layout1') { 
                                            echo wp_get_attachment_image( get_term_meta( $product_cat->term_id, 'image2', 1 ), 'full' ); ?>
                                            <h4><?php echo esc_html($product_cat->name); ?></h4> 
                                            <p><?php echo esc_html($product_cat->count).esc_html__(' items','auriane'); ?></p>  
                                        <?php 
                                        }elseif($settings['layouts'] == 'layout2') {?>
                                             <div class="category-image"> <?php  
                                             if (function_exists('jws_getImageBySize')) {
                                                 $attach_id = get_term_meta( $product_cat->term_id, 'thumbnail_id', 1 );
                                                 $img = jws_getImageBySize(array('attach_id' => $attach_id, 'thumb_size' => $image_size, 'class' => 'attachment-large wp-post-image'));
                                                 echo (!empty($img['thumbnail'])) ? ''.$img['thumbnail'] : '';
                                             }  ?>
                                             </div> 
                                            <h4><?php echo esc_html($product_cat->name); ?></h4> 
                                        <?php } elseif($settings['layouts'] == 'layout3') {
                                            
                                            if($i % 2 == 0){ 
                                               $size = $image_size;  
                                            }else{
                                               $size = $image_size2;   
                                            }
                                            
                                            ?>
                                             <div class="category-image"> <?php  
                                             if (function_exists('jws_getImageBySize')) {
                                                 $attach_id = get_term_meta( $product_cat->term_id, 'thumbnail_id', 1 );
                                                 $img = jws_getImageBySize(array('attach_id' => $attach_id, 'thumb_size' => $size, 'class' => 'attachment-large wp-post-image'));
                                                 echo (!empty($img['thumbnail'])) ? ''.$img['thumbnail'] : '';
                                             }  ?>
                                             </div> 
                                            <h4><?php echo esc_html($product_cat->name); ?></h4> 
                                            <p><?php echo esc_html($product_cat->count).esc_html__(' items','auriane'); ?></p> 
                                        <?php }
                                    ?>
                                </a>
                            </div>
                        <?php

                }
                
            $i++; } 
        }
    ?>
</div> 
<?php if($settings['enable_dots'] == 'yes') : ?>
<div class="slider-dots-box"></div>
<?php endif; ?>
</div>