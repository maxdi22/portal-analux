<div class="process_slider">
 <?php foreach (  $settings['list'] as $item ) {
        $url = $item['process_button_url']['url'];
        $target = $item['process_button_url']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $item['process_button_url']['nofollow'] ? ' rel="nofollow"' : '';     
 ?>
                                    <div class="progress_item slick-slide">
                                        <div class="row row-eq-height">
                                            <div class="col-xl-6 col-lg-6 col-12 order-xl-1 order-lg-1 order-12">
                                                <div class="progress_content">
                                                        <span class="progress_number"> <?php echo esc_html($item['list_number']); ?>.</span>
                                                        <span class="progress_top_title"><?php echo esc_html($item['top_list_title']); ?></span>
                                                         <h3 class="progress_title"><?php echo esc_html($item['list_title']); ?></h3>
                                                        <div class="progress_description"><?php echo ''.$item['list_description']; ?></div> 
                                                        <a class="progress_button" href="<?php echo esc_url($url); ?>" <?php echo esc_attr($target.$nofollow); ?>>
                                                            <span class="lnr lnr-chevron-right-circle"></span>
                                                            <span> <?php      
                                                                 echo esc_html($item['process_button']);
                                                             ?> </span> 
                                                        </a>
                                                    </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-12 order-xl-12 order-lg-12 order-1">
                                                <?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item ); ?>
                                            </div>
                                        </div>     
                                    </div>    
<?php } ?>
 <div class="slider-nav-wrap">   
    <div class="slider-nav"></div>
 </div>
</div>

 