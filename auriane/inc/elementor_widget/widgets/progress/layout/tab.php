 <div class="process_content">
 <?php foreach (  $settings['list'] as $item ) {
                if($item['list_number'] < 10) {
                    $number = '0'.$item['list_number'];
                }else {
                    $number = $item['list_number'];
                }
          ?>
          <div id="<?php echo esc_attr($item['_id']); ?>" class="progress_item row<?php if($item['active'] == 'yes') echo " current"; ?>">
                   <div class="progress_left col-xl-5 col-lg-5 col-12">
                           <?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item); ?>
                   </div>
                   <div class="progress_right col-xl-7 col-lg-7 col-12">
                         <span class="progress_step_big">
                            <?php echo esc_html($number); ?>
                         </span>   
                         <div class="propress">
                            <span class="progress_step">
                                <?php echo esc_html__('Step ','auriane').$number; ?>
                             </span>
                             <h3 class="progress_title"><?php echo esc_html($item['list_title']); ?></h3>
                             <div class="progress_description"><?php echo ''.$item['list_description']; ?></div> 
                        </div>
                   </div>
          </div>    
<?php } ?>
</div>
 <div class="process_nav">
     <div class="process_nav_inner">
         <?php foreach (  $settings['list'] as $item ) { ?>
                  <div class="progress_item<?php if($item['active'] == 'yes') echo " current"; ?>">
                                 <a class="progress_number" href="#" data-tab="<?php echo esc_attr($item['_id']); ?>"><span> <?php echo esc_html($item['list_number']); ?></span></a>
                  </div>    
        <?php } ?>
    </div>
</div>