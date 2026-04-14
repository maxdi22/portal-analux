
<div class="jws-services-item-inner">
    <a href="<?php the_permalink(); ?>">
        <div class="jws-service-images">
            <?php $images = get_post_meta(get_the_ID(),'image_items',true); if(!empty($images)) echo  wp_get_attachment_image($images, 'full', "", array( "class" => "img-responsive" ) ); ?>
        </div>
        <div class="jws-service-content">
            <h3 class="jws-service-title">
                 <?php the_title();?>
            </h3>
        </div>
     </a>
</div>