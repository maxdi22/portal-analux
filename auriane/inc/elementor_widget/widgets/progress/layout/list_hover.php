 <?php foreach (  $settings['list'] as $item ) { ?>
        <div class="progress_item<?php echo ''.($item['active'] == 'yes') ? ' active' : ''; ?>">
                 <h3 class="progress_title"><?php echo esc_html($item['list_title']); ?></h3>
                 <div class="progress_description"><?php echo ''.$item['list_description']; ?></div> 
        </div>    
<?php } ?>