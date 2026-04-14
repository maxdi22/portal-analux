<?php 
    $link_name = get_post_meta(get_the_ID(), 'blog_name_link', true);
    $link_url = get_post_meta(get_the_ID(), 'blog_url_link', true);
 ?>
 <div class="jws-post-link">
    <a target="_blank" href="<?php echo esc_url($link_url); ?>"><?php echo esc_html($link_name); ?></a>
 </div>