<div class="jws-banner-inner">
    <a <?php echo ''.$this->get_render_attribute_string($link_key); ?>>
        <div class="jws-banner-image">
            <?php if(!empty($item['image']['id'])) echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item );?> 
        </div>
        <div class="jws-banner-content">
        <h4 class="text-1">
            <?php echo esc_html($item['text1']); ?>
        </h4>
        <p class="text-2"><?php echo esc_html($item['text2']); ?></p>
        <span class="button"><?php echo esc_html($item['text3']); ?></span>
        </div>
    </a>
</div>