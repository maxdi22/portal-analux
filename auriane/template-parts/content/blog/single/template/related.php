<?php
global $jws_option;
 $result = ''; 
if(isset($jws_option['exclude-blog']) && !empty($jws_option['exclude-blog'])) {
 array_push($jws_option['exclude-blog'], $post->ID);  
 $result = array_map('intval', array_filter($jws_option['exclude-blog'], 'is_numeric')); 
}

$related = get_posts( array( 'category__in' => wp_get_post_categories($post->ID), 'numberposts' => 100,'post_type' => 'post', 'post__not_in' => $result ) );
              
if( isset($related[0]) ) foreach( $related as $post ) {
setup_postdata($post); 
    $format = has_post_format() ? get_post_format() : 'no_format'; ?> 
   <div class="jws_blog_item col-xl-4 col-lg-4 col-12 slick-slide">
        <?php 
            get_template_part( 'template-parts/content/blog/layout/related' );
        ?>
    </div>       
<?php
}
wp_reset_postdata();

?>