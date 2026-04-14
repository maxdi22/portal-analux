<?php $img_atts = get_the_post_thumbnail_url(get_the_ID(),'full');  ?>

<div class="jws-services-item-inner">
    <a href="<?php echo get_the_permalink(); ?>">
        <div class="overlay"></div>
        <div class="jws-service-images" style="background-image: url(<?php echo ''.$img_atts; ?>);"></div>
        <div class="jws-service-content">
            <h3 class="jws-service-title"><?php the_title(); ?></h3>
            <span class="jws-service-readmore">
               <?php echo esc_html__('Read More','auriane'); ?>
               <span class="jws-icon-arrow_carrot-right_alt2"></span>
            </span>
        </div>
    </a>
</div>