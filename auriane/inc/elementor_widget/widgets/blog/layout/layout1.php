<?php 
    $archive_year  = get_the_time('Y'); 
	$archive_month = get_the_time('m'); 
	$archive_day   = get_the_time('d');
if($format == 'quote'){ ?>
     <div class="jws_post_quote">
         <i class="quote_icon"></i>
         <div class="jws_quote_content">
               <h4 class="jws_quote_excerpt">
                        <?php  echo get_the_excerpt();?>
               </h4>
               <?php $quote_name = get_post_meta(get_the_ID(), 'blog_name_quote', true); if(isset($quote_name)) echo '<div class="quote_name">'.$quote_name.'</div>';  ?>
         </div>
     </div>
<?php }elseif($format == 'link'){ ?>
    <div class="jws_post_link">
         <i class="link_icon"></i>
         <div class="jws_link_content">
               <?php $link_name = get_post_meta(get_the_ID(), 'blog_name_link', true); if(isset($link_name)) echo '<h4 class="link_name"><a href="'.get_post_meta(get_the_ID(), 'blog_url_link', true).'">'.$link_name.'</a></h4>';  ?>
         </div>
     </div>
<?php }else {?>
   <div class="jws_post_wap">
        <div class="jws_post_image">
          <?php 
          if($format == 'gallery'){  
                include( 'format/gallery.php' );
          }else {
            if (function_exists('jws_getImageBySize')) {
                     $attach_id = get_post_thumbnail_id();
                     $img = jws_getImageBySize(array('attach_id' => $attach_id, 'thumb_size' => $image_size, 'class' => 'attachment-large wp-post-image'));
                     echo (!empty($img['thumbnail'])) ? ''.$img['thumbnail'] : '';
            
                     }else {
                     echo ''.$img = get_the_post_thumbnail(get_the_ID(), $image_size);
              }
              if($format == 'video') {
                $link_video = get_post_meta(get_the_ID(), 'blog_video', true);
                ?>
                 <div class="video_format">
                     <a class="url" href="<?php echo esc_url($link_video); ?>">
                        <span class="video_icon">
                            <i class="fas fa-play"></i>
                        </span>
                     </a>
                 </div>
                 <?php
              }
          }
         ?>
    
        </div>
        <div class="jws_post_content">
               <div class="jws_post_meta">
                    <span class="post_cat"><?php echo get_the_term_list(get_the_ID(), 'category', '', ', '); ?></span> 
                    <span class="separator"></span>
                    <span class="entry-date"><a href="<?php echo esc_url(get_day_link($archive_year, $archive_month, $archive_day)); ?>"><?php echo get_the_date(); ?></a></span>
               </div>
               <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4> 
               <?php if($settings['show_excerpt']): ?>
               <div class="jws_post_excerpt">
                        <?php  echo (!empty($settings['excerpt_length'])) ? wp_trim_words( get_the_excerpt(), $settings['excerpt_length'], $settings['excerpt_more'] ) : get_the_excerpt();?>
               </div>
               <?php endif; ?>
               <?php if($settings['show_readmore']): ?>
               <a href="<?php the_permalink(); ?>" class="jws_post_readmore">
                    <?php echo esc_html($settings['readmore_text']); ?>
               </a>
               <?php endif; ?>
        </div>
    </div>   
<?php }


  
