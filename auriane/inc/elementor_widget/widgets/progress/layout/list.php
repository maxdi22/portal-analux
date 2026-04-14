 <?php foreach (  $settings['list'] as $item ) { ?>
                                    <div class="progress_item">
                                            <div class="progress_left">
                                                <span class="progress_number<?php echo ''.($item['active'] == 'yes') ? ' active' : ''; ?>"> <?php echo esc_html($item['list_number']); ?><?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] );  ?> </span>
                                            </div>
                                            <div class="progress_right">
                                                <h3 class="progress_title"><?php echo esc_html($item['list_title']); ?></h3>
                                                <div class="progress_description"><?php echo ''.$item['list_description']; ?></div> 
                                            </div>
                                    </div>    
<?php } ?>