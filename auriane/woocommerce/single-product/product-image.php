<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 */

defined( 'ABSPATH' ) || exit;


        // Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
        if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
        	return;
        }

        global $post, $product , $jws_option;
        $placeholder_size = 'woocommerce_thumbnail';
        $video_class = '';
        $video = get_post_meta( get_the_ID(), 'product_video', true );
        $video_type = get_post_meta( get_the_ID(), 'shop_single_video_type', true );
        $image_360 = get_post_meta( get_the_ID(), '360_images', true );
        $video_class .= ' '.$video_type;
        if(!empty($video)) {
             if (strpos($video, 'youtube') > 0) {
                $video_class .=  ' youtube';
            } elseif (strpos($video, 'vimeo') > 0) {
                $video_class .=  ' vimeo';
            } else {
                $video_class .=  ' unknown';
            }  
            $video_class .= ' has_video';
        }
        
        $gallery_image_ids = $product->get_gallery_image_ids();
        $gallery = 'gallery_false';
        if ( ! empty($gallery_image_ids) ||  (!empty($video) && $video_type == 'inner') ) {
            $gallery = 'gallery_true';
        } 
        
        $thumb_image_size = 'woocommerce_single';
        $thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
        $post_thumbnail_id = $product->get_image_id();
        $full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
        $wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
        	'woocommerce-product-gallery',
            'image-action-popup', /* themeoption */
        	'woocommerce-product-gallery--' . ( $product->get_image_id() ? 'with-images' : 'without-images' ),
        	'images',
            'image_slider',
            $gallery
        ) );
        ?>
        <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>"  style="opacity: 0; transition: opacity .25s ease-in-out;">
        	<div class="jws_main_image">
            <?php jws_product_label(); ?>
          	<figure class="woocommerce-product-gallery__wrapper <?php echo esc_attr($video_class);?>">
			<?php
				$attributes = array(
					'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
					'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
					'data-src'                => $full_size_image[0],
					'data-large_image'        => $full_size_image[0],
					'data-large_image_width'  => $full_size_image[1],
					'data-large_image_height' => $full_size_image[2],
					'class'                   => 'wp-post-image',  
				);
      
                if (!empty($video) && $video_type == 'inner') : 

                        
                        $video_html  = '<div class="product-image-wrap"><figure class="woocommerce-product-gallery__image video_inner">';
                		$video_html .= ' <div class="video_product"><iframe class="embed-player slide-media" width="100%" height="100%" src="'.$video.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
                 		$video_html .= '</figure></div>';
                        
                        
                      ?>
                  
                  <?php
                        
                        
                        
                        echo apply_filters( 'woocommerce_video_gallery',  $video_html );
                endif; 

				if ( $product->get_image_id() ) {
					$html  = '<div class="product-image-wrap"><figure data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'woocommerce_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
					$html .= get_the_post_thumbnail( $post->ID, $thumb_image_size, $attributes );
					$html .= '</a></figure></div>';
				} else {
					$html  = '<div class="product-image-wrap"><figure data-thumb="' . esc_url( wc_placeholder_img_src( $placeholder_size ) ) . '" class="woocommerce-product-gallery__image--placeholder"><a href="' . esc_url( wc_placeholder_img_src( $placeholder_size ) ) . '">';

					$html .= sprintf( '<img src="%s" alt="%s" data-src="%s" data-large_image="%s" data-large_image_width="700" data-large_image_height="800" class="attachment-woocommerce_single size-woocommerce_single wp-post-image" />', esc_url( wc_placeholder_img_src( $placeholder_size ) ), esc_html__( 'Awaiting product image', 'auriane' ), esc_url( wc_placeholder_img_src( $placeholder_size ) ), esc_url( wc_placeholder_img_src( $placeholder_size ) ) );
					
					$html .= '</a></figure></div>';
				}

				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );


			    do_action( 'woocommerce_product_thumbnails' );

			?>
		</figure>
        <div class="product-video-popup">
        <?php 
              
             
             if(!empty($image_360)) {
                 wp_enqueue_script( '360product', JWS_URI_PATH. '/assets/js/lib/360-threesixty.js', [], '2.9', true );   
                 wp_enqueue_style( '360product', JWS_URI_PATH . '/assets/css/360product.css', false);

                $i = '';
                foreach ($image_360 as $id) {
                    $i++;
                }
              
                echo '<a href="#360-view" class="action-360-images">' . esc_html__('View 360', 'auriane') . '</a>';
                ?>
    
                <div class="product-360-view-wrapper threesixty mfp-hide" data-mfp-src="#360-view" id="360-view">
                    <div class="360-view-container">
                        <div class="spinner">
                            <span>0%</span>
                        </div>
                        <ol class="threesixty_images"></ol>
                    </div>
                    <script style="text/javascript">
                        jQuery(document).ready(function ($) {
                          $('.action-360-images').magnificPopup({
                    		type: 'inline',
                    		mainClass: 'mfp-fade',
                    		removalDelay: 360,
                    		disableOn: false,
                    		preloader: false,
                    		fixedContentPos: false,
                    		callbacks: {
                    		    beforeOpen: function() {
                                    this.st.mainClass = 'quick-view-main';
                                },  
                    			open: function() {
                    				$(window).trigger("resize")
                    			},
                    		},
                    	});    
                        $('.360-view-container').ThreeSixty({
                        totalFrames: <?php echo wp_kses_post($i); ?> ,
                        endFrame: <?php echo wp_kses_post($i); ?> ,
                        currentFrame: 1,
                        imgList: '.threesixty_images',
                        progress: '.spinner',
                        imgArray: [<?php
                   
                            foreach ($image_360 as $id) {

                                echo "'" . $id . "'" . ",";
                            }
    
    
                        ?>],
                        width: 840,
                        responsive: true,
                        navigation: true
                        });
                        });
                    </script>
                 </div>  
                <?php
             }
             if (!empty($video) && $video_type == 'popup') : 

                echo '<a href="' . esc_url($video) . '" class="action-popup-url"><i aria-hidden="true" class="jws-icon-play-circle"></i>' . esc_html__('View video', 'auriane') . '</a>';
 
             endif;
             ?>
         </div>
        
        <?php
        wp_enqueue_script( 'jws-photoswipe' ); wp_enqueue_style( 'photoswipe' ); 	wp_enqueue_style( 'photoswipe-default-skin' ); add_action( 'wp_footer', 'jws_photoswipe_template');  ?>
        </div>
        <div class="jws_thumbnail_image">
			     <div class="thumbnails"></div>
		    </div>
        </div>