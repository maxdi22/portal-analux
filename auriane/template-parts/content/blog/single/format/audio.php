<?php  $link_audio = get_post_meta(get_the_ID(), 'audio_url_embed', true); ?>
 <div class="jws-post-audio">
    <iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="<?php echo esc_url($link_audio); ?>"></iframe>
</div>