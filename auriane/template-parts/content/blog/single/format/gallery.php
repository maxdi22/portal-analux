<?php  
global $jws_option;
$image_size = (isset($jws_option['single_blog_imagesize']) && !empty($jws_option['single_blog_imagesize'])) ? $jws_option['single_blog_imagesize'] : 'full';
$link_audio = get_post_meta(get_the_ID(), 'image_gallery_list', true);
echo '<div class="jws-post-gallery">';
	// Loop through them and output an image
	foreach ( (array) $link_audio as $attachment_id => $attachment_url ) {
		echo '<div class="jws-post-gallery-item">';
		 if (function_exists('jws_getImageBySize')) {

                 $img = jws_getImageBySize(array('attach_id' => $attachment_id, 'thumb_size' => $image_size, 'class' => 'attachment-large wp-post-image'));
                 echo ''.$img['thumbnail'];
        
        }
         
		echo '</div>';
	}
	echo '</div>';
 ?>