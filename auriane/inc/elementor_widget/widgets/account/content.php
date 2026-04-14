<div class="jws_account">
    <?php
        if ( is_user_logged_in() || isset($_GET['p']) ) {
            ?>
             
             <a class="jws_ac_noajax has_user" href="<?php echo (!empty($url2)) ? esc_url($url2) : get_permalink( wc_get_page_id( 'myaccount' )); ?>" <?php echo esc_attr($target2.$nofollow2); ?>>
             <?php if($show_text && $settings['text_position'] == 'left'): ?>
                    <span class="jws_account_text text_position_<?php echo esc_attr($settings['text_position']);  ?>"><?php echo esc_html($text_after_login); ?></span>
             <?php endif; ?>
             <span class="jws_a_icon">
                 <?php
       
                    if ( isset($settings['icon']) && !empty($settings['icon']['value']) ) {
						\Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
					} else{ ?>
					    <i class="jws-icon-account" aria-hidden="true"></i>   
					<?php }  
                 
                 ?> 
             </span>
              <?php if($show_text && $settings['text_position'] != 'left'): ?>
                    <span class="jws_account_text text_position_<?php echo esc_attr($settings['text_position']);  ?>"><?php echo esc_html($text_after_login); ?><i class="jws-icon-arrow_carrot-down"></i></span>
             <?php endif; ?>
             </a>
            
             
        <?php } else {
            ?>
               
                <a class="jws_ac_noajax no_user" href="<?php echo esc_url($url); ?>">
                <?php if($show_text && $settings['text_position'] == 'left'): ?>
                    <span class="jws_account_text text_position_<?php echo esc_attr($settings['text_position']);  ?>"><?php echo esc_html($text); ?></span>
                <?php endif; ?>
                <span class="jws_a_icon">
                    <?php
                         if ( isset($settings['icon']) && !empty($settings['icon']['value']) ) {
    						\Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
    					} else{ ?>
    					    <i class="jws-icon-account" aria-hidden="true"></i>   
    					<?php } 
                 	?> 
                </span>
                <?php if($show_text && $settings['text_position'] != 'left'): ?>
                    <span class="jws_account_text text_position_<?php echo esc_attr($settings['text_position']);  ?>"><?php echo esc_html($text); ?><i class="jws-icon-arrow_carrot-down"></i></span>
                <?php endif; ?> 
                </a>
                 
            
       <?php }
    ?>
    <div class="">
    
    
    </div>   
</div>