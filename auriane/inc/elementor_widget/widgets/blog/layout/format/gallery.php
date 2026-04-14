<?php  $link_audio = get_post_meta(get_the_ID(), 'image_gallery_list', true);
echo '<div class="jws-post-gallery post-image-slider">';
	// Loop through them and output an image
     if (function_exists('jws_getImageBySize')) {
         $attach_id = get_post_thumbnail_id();
         $img = jws_getImageBySize(array('attach_id' => $attach_id, 'thumb_size' => $image_size, 'class' => 'attachment-large wp-post-image'));
         echo (!empty($img['thumbnail'])) ? '<div class="jws-post-gallery-item slick-slide">'.$img['thumbnail'].'</div>' : '';
     }
	foreach ( (array) $link_audio as $attachment_id => $attachment_url ) {
		echo '<div class="jws-post-gallery-item slick-slide">';
		 if (function_exists('jws_getImageBySize')) {

                 $img = jws_getImageBySize(array('attach_id' => $attachment_id, 'thumb_size' => $image_size, 'class' => 'attachment-large wp-post-image'));
                 echo ''.$img['thumbnail'];
        
        }
         
		echo '</div>';
	}
	echo '</div>';
 ?>