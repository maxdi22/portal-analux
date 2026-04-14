<?php  
    $name = get_post_meta(get_the_ID(), 'blog_name_quote', true);
    $description = get_post_meta(get_the_ID(), 'blog_description_quote', true);
        
 ?>
<div class="jws-post-quote">
    <?php if(!empty($description)) : ?><h3><?php echo esc_html($description); ?></h3> <?php endif; ?>
    <?php if(!empty($name)) : ?><p><?php echo esc_html($name); ?></p> <?php endif; ?>

</div>
