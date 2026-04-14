<a href="<?php echo esc_url($url); ?>" <?php echo esc_attr($target.$nofollow); ?>>
    <div class="box-icon">
        <span></span>
        <?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );  ?>  
    </div>
    <div class="box-title">
        <?php echo ''.$settings['info_title']; ?>
    </div>
    <div class="box-content">
        <?php echo ''.$settings['info_content']; ?>
    </div>
    <div class="box-more">
       <?php echo esc_html($settings['info_readmore']); ?><i class="jws-icon-arrow-up-right-thin"></i>
    </div>
</a>