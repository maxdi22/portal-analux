<div class="jws_search_wap">
    <div class="jws_search_content">
           <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3> 
           <div class="jws_search_meta">
               <span class="entry-date"><?php echo get_the_date(); ?></span> 
               <span class="search_author"><?php echo '<span>'.esc_html__('By ','auriane').'</span>'.get_the_author(); ?></span> 
           </div>

             <div class="jws_search_excerpt">
                 <?php  echo get_the_excerpt(); ?>
             </div> 
       
    </div>
</div>

  
