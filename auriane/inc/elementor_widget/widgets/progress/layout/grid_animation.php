 <?php 
$class_column = ' col-xl-'.$settings['process_columns'].'';
$class_column .= (!empty($settings['process_columns_tablet'])) ? ' col-lg-'.$settings['process_columns_tablet'].'' : ' col-lg-'.$settings['process_columns'].'' ;
$class_column .= (!empty($settings['process_columns_mobile'])) ? ' col-'.$settings['process_columns_mobile'].'' :  ' col-'.$settings['process_columns'].'';
foreach (  $settings['list'] as $item ) {
    
    ?>
          <div class="progress_items elementor-repeater-item-<?php echo esc_attr($item['_id']); echo esc_attr($class_column);?>">  
              <div class="progress_item<?php if($item['active'] == 'yes') echo " active"; ?>">
                        <div class="dots-animation">
                            <span></span><span></span><span></span><span></span><span></span>
                        </div>
                        <div class="process_icon">
                            <?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] );  ?> 
                            <span class="progress_number"> <span><?php echo esc_html($item['list_number']); ?></span></span>
                        </div>
                        <div class="process_content">
                            <h3 class="progress_title"><?php echo esc_html($item['list_title']); ?></h3>
                            <div class="progress_description"><?php echo ''.$item['list_description']; ?></div> 
                        </div>
              </div>
          </div>    
<?php } ?>