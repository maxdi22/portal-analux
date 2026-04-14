<div class="jws_team_image">
    <a href="<?php echo esc_url($url); ?>" <?php echo esc_attr($target.$nofollow); ?>>
        <?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item ); ?>
    </a>
</div>
<a class="team-overlay" href="<?php echo esc_url($url); ?>" <?php echo esc_attr($target.$nofollow); ?>></a>
<div class="jws_team_content">
    <div class="team_content_inner">
    <h6 class="team_job">
            <?php echo esc_html($item['team_job']); ?>
    </h6>  
    <h3 class="team_title">
        <a href="<?php echo esc_url($url); ?>" <?php echo esc_attr($target.$nofollow); ?>>
            <?php echo esc_html($item['team_title']); ?>
        </a>
    </h3>
     <ul class="team_icon ct_ul_ol">
         <?php if(!empty($item['team_icon_url1']['url'])) : ?>   
         <li>
            <a href="<?php echo esc_url($item['team_icon_url1']['url']); ?>" <?php if($item['team_icon_url1']['is_external']) echo esc_attr('target="_blank"'); if($item['team_icon_url1']['nofollow']) echo esc_attr('rel="nofollow"'); ?> ><?php \Elementor\Icons_Manager::render_icon( $item['team_icon1'], [ 'aria-hidden' => 'true' ] );  ?></a>
         </li> 
         <?php endif; ?>
         <?php if(!empty($item['team_icon_url2']['url'])) : ?>
          <li>
            <a href="<?php echo esc_url($item['team_icon_url2']['url']); ?>" <?php if($item['team_icon_url2']['is_external']) echo esc_attr('target="_blank"'); if($item['team_icon_url2']['nofollow']) echo esc_attr('rel="nofollow"'); ?> ><?php \Elementor\Icons_Manager::render_icon( $item['team_icon2'], [ 'aria-hidden' => 'true' ] );  ?></a>
         </li> 
         <?php endif; ?>
         <?php if(!empty($item['team_icon_url3']['url'])) : ?>
          <li>
            <a href="<?php echo esc_url($item['team_icon_url3']['url']); ?>" <?php if($item['team_icon_url3']['is_external']) echo esc_attr('target="_blank"'); if($item['team_icon_url3']['nofollow']) echo esc_attr('rel="nofollow"'); ?> ><?php \Elementor\Icons_Manager::render_icon( $item['team_icon3'], [ 'aria-hidden' => 'true' ] );  ?></a>
         </li> 
         <?php endif; ?>
         <?php if(!empty($item['team_icon_url4']['url'])) : ?>
          <li>
            <a href="<?php echo esc_url($item['team_icon_url4']['url']); ?>" <?php if($item['team_icon_url4']['is_external']) echo esc_attr('target="_blank"'); if($item['team_icon_url4']['nofollow']) echo esc_attr('rel="nofollow"'); ?> ><?php \Elementor\Icons_Manager::render_icon( $item['team_icon4'], [ 'aria-hidden' => 'true' ] );  ?></a>
         </li>
         <?php endif; ?> 
    </ul>
    </div>
</div>
