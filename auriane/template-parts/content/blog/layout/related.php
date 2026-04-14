<?php 
    $archive_year  = get_the_time('Y'); 
	$archive_month = get_the_time('m'); 
	$archive_day   = get_the_time('d');
    global $jws_option;
    $image_size = (isset($jws_option['blog_imagesize']) && !empty($jws_option['blog_imagesize'])) ? $jws_option['blog_imagesize'] : 'full'; 
?>
<div class="jws_post_wap">
    <div class="jws_post_image">
      <?php 
           if (function_exists('jws_getImageBySize')) {
                 $attach_id = get_post_thumbnail_id();
                 $img = jws_getImageBySize(array('attach_id' => $attach_id, 'thumb_size' => $image_size, 'class' => 'attachment-large wp-post-image'));
                 echo (!empty($img['thumbnail'])) ? ''.$img['thumbnail'] : '';
        
                 }else {
                 echo ''.$img = get_the_post_thumbnail(get_the_ID(), $settings['blog_image_size']);
          }
        ?>
      
    </div>
    <div class="jws_post_content">
              <span class="post_cat"><?php echo get_the_term_list(get_the_ID(), 'category', '', ', '); ?></span> 
           <h5 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5> 
    </div>
</div>