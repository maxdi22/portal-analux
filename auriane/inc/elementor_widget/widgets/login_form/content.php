<?php 
    if($settings['layout'] == 'popup') {
        if(!empty($settings['icon']['value'])) {
           \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );  
        }  
    }
    jws_get_content_form_login($show_login,$show_register,$active);
?>