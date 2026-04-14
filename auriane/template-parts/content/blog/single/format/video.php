<?php  
    $link_video = get_post_meta(get_the_ID(), 'video_url_embed', true);
    $video_caption = get_post_meta(get_the_ID(), 'video_caption', true);
        
 ?>
 <?php if(!empty($link_video)) : ?>
    <div class="jws-post-video">
        <iframe width="100%" height="432" src="<?php echo esc_url($link_video); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <?php if(!empty($video_caption)) : ?><p><?php echo esc_html($video_caption); ?></p> <?php endif; ?>
    </div>
 <?php endif; ?>