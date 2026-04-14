<?php
    echo jws_get_post_thumbnail($settings['blog_image_size']);
    $link_video = get_post_meta(get_the_ID(), 'blog_video', true);
 ?>
 <div class="video_format">
     <a class="url" href="<?php echo esc_url($link_video); ?>">
        <span class="video_icon">
            <i class="fas fa-play"></i>
        </span>
     </a>
 </div>