<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
} 
    global $jws_option;
    $page_id     = get_queried_object_id();
    $main_color_custom = get_post_meta($page_id, 'main_color', true);
    $bg_btn_color_custom = get_post_meta($page_id, 'button-bgcolor', true);
    $bg_btn_color2_custom = get_post_meta($page_id, 'button-bgcolor2', true);

    $main_color = (isset($main_color_custom) && !empty($main_color_custom)) ? $main_color_custom : (isset($jws_option['main_color']) ? $jws_option['main_color'] : '#ed1c24');
    $button_bg_color = (isset($bg_btn_color_custom) && !empty($bg_btn_color_custom)) ? $bg_btn_color_custom : (isset($jws_option['button-bgcolor']) ? $jws_option['button-bgcolor'] : '#ed1c24');
    $button_bg_hover_color = (isset($bg_btn_color2_custom) && !empty($bg_btn_color2_custom)) ? $bg_btn_color2_custom : (isset($jws_option['button-bgcolor2']) ? $jws_option['button-bgcolor2'] : '#ed1c24');
    
    
 ?>
<div id="panel-style-selector">
	<div class="panel-wrapper">
		<div class="panel-selector-open"><i class="jws-icon-002-settings"></i></div>
		<div class="panel-selector-header">Style Selector</div>
		<div class="panel-selector-body clearfix">
			<div class="panel-selector-section clearfix">
				
				<div class="panel-selector-row clearfix">
                        <div class="color-item">
    				        <h3 class="panel-selector-title"><?php echo esc_html__('Primary Color','auriane'); ?></h3>
                            <div style="background-color: <?php echo esc_attr($main_color); ?>" class="colorSelector" data-type="main"></div>
                        </div>
                        
                        <div class="color-item">
                            <h3 class="panel-selector-title"><?php echo esc_html__('Color 1','auriane'); ?></h3>
                            <div style="background-color: <?php echo isset($jws_option['color_heading']) ? $jws_option['color_heading'] : '#131313';?>" class="colorSelector" data-type="heading"></div>
                        </div>
                        
                        <div class="color-item">
                            <h3 class="panel-selector-title"><?php echo esc_html__('Body Color','auriane'); ?></h3>
                            <div style="background-color: <?php echo isset($jws_option['color_body']) ? $jws_option['color_body'] : '#6f6f6f';?>" class="colorSelector" data-type="body"></div>
                        </div>
                        
                        <div class="color-item">
                            <h3 class="panel-selector-title"><?php echo esc_html__('Light Color','auriane'); ?></h3>
                            <div style="background-color: <?php echo isset($jws_option['color_light']) ? $jws_option['color_light'] : '#ffffff';?>" class="colorSelector" data-type="light"></div>
                        </div>
                        
                        <div class="color-item">
                            <h3 class="panel-selector-title"><?php echo esc_html__('Button Background','auriane'); ?></h3>
                            <div style="background-color: <?php echo esc_attr($button_bg_color); ?>" class="colorSelector" data-type="button"></div>
                        </div>
                        
                        <div class="color-item">
                            <h3 class="panel-selector-title"><?php echo esc_html__('Button Background Hover','auriane'); ?></h3>
                            <div style="background-color: <?php echo esc_attr($button_bg_hover_color);?>" class="colorSelector" data-type="button_hover"></div>
                        </div>
                        
                        <div class="color-item">
                            <h3 class="panel-selector-title"><?php echo esc_html__('Color Custom 1','auriane'); ?></h3>
                            <div style="background-color:#f6f6f6" data-var="--e-global-color-2c59605" class="colorSelector" data-type="custom1"></div>
                        </div>
                        
                        <div class="color-item">
                            <h3 class="panel-selector-title"><?php echo esc_html__('Color Custom 2','auriane'); ?></h3>
                            <div style="background-color:#e9e9e9" class="colorSelector" data-type="custom2"></div>
                        </div>
				
				</div>
			</div>
			<div class="panel-selector-section clearfix">
				<div class="panel-selector-row text-center">
					<a id="panel-selector-reset" href="<?php echo get_the_permalink(); ?>" class="panel-selector-btn">Reset</a>
				</div>
			</div>
		</div>
	</div>
</div>