<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage auriane
 * @since 1.0.0
 */
global $jws_option; 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <header>
                <div class="post_thumbnail">
                    <?php 
               
                        if(isset($jws_option['single_blog_imagesize']) && !empty($jws_option['single_blog_imagesize'])) {
                             echo jws_get_post_thumbnail($jws_option['single_blog_imagesize']);
                        }else {
                            echo jws_get_post_thumbnail('full');
                        } 
                       
                    ?>
   
                </div>
                <h2 class="entry_title">
                    <?php echo get_the_title(); ?>
                </h2>
               
           </header>
           <div class="entry_content">
                <?php the_content(); ?> 
           </div>


</article><!-- #post-<?php the_ID(); ?> -->
