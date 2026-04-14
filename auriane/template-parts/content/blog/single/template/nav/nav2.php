<?php
$archive_year  = get_the_time('Y'); 
$archive_month = get_the_time('m'); 
$archive_day   = get_the_time('d');
$prev_post = get_previous_post(); $next_post = get_next_post(); 
?>
<nav class="navigation post-navigation" role="navigation">
    			<?php 
                        if(!empty($prev_post)){    
                           
                           
                            echo '<div class="left">
                                   <div class="content_nav">
                                        <span><i class="jws-icon-arrow-right-thin"></i>'.esc_html__('previous post','auriane').'</span>
                                        <h5 class="title">
                                              <a href="'.get_the_permalink($prev_post->ID).'">'.get_the_title($prev_post->ID).'</a>
                                        </h5>
                                   </div>
                            </div>'; 
                      
                            }else {
                                $first = new WP_Query('posts_per_page=1&order=DESC'); $first->the_post(); 
                  
                                echo '<div class="left">
                                   <div class="content_nav">
                                        <span>'.esc_html__('previous post','auriane').'<i class="jws-icon-arrow-right-thin"></i></span>
                                        <h5 class="title">
                                              <a href="'.get_the_permalink($first->ID).'">'.get_the_title($first->ID).'</a>
                                        </h5>
                                       
                                   </div>
                                </div>';  
                        
                                 wp_reset_postdata();   
                            }
                            
                            ?>
                                <a href="<?php echo get_option( 'page_for_posts' ) ? esc_url(get_permalink( get_option( 'page_for_posts' ) )) : get_home_url(); ?>"><span class="jws-icon-icon_grid-2x2"></span></a>
                            <?php
                            
                            if(!empty($next_post)){
                          
                                   echo '<div class="right">
                                            
                                            <div class="content_nav">
                                               <span>'.esc_html__('next post','auriane').'<i class="jws-icon-arrow-right-thin"></i></span> 
                                               <h5 class="title">
                                                   <a href="'.get_the_permalink($next_post->ID).'">'.get_the_title($next_post->ID).'</a>
                                               </h5>
                            
                                            </div>
                                         
                                   </div>';   
                            }else {
                                $last  = new WP_Query('posts_per_page=1&order=ASC'); $last->the_post();
                           
                                 echo '<div class="right">
                                            <div class="content_nav">
                                               <span>'.esc_html__('next post','auriane').'<i class="jws-icon-arrow-right-thin"></i></span>  
                                               <h5 class="title">
                                                   <a href="'.get_the_permalink($last->ID).'">'.get_the_title($last->ID).'</a>
                                               </h5>
                                               
                                            </div>
                                            
                                   </div>';
                                   wp_reset_postdata(); 
                            }
                 ?>
</nav><!-- .navigation -->
