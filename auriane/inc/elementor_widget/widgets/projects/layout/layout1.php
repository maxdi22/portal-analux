<div class="jws_project_wap">
        <div class="jws_project_image">
          <a href="<?php echo get_the_permalink(); ?>">
            <?php 
              echo jws_get_post_thumbnail($image_size);
            ?> 
          </a>   
        </div>
        <div class="jws_project_content">
               <div class="jws_project_content_inner">
                   <div class="projects_cat"><?php echo get_the_term_list(get_the_ID(), 'projects_cat', '', ' <span> & </span> '); ?></div> 
                   <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
               </div> 
        </div>
    
</div>

  
