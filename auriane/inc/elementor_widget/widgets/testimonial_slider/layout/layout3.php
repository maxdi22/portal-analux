<div class="slider-content">
        <span class="testimonials-icon"><i class="jws-icon-icon_quotations"></i></span>
        <div class="testimonials-description"><?php echo ''.$item['list_description']; ?></div>

       <?php if(!empty($item['image']['id'])) echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item );?> 
       <div class="testimonials-info">
           <h3 class="testimonials-title"><?php echo ''.$item['list_name']; ?></h3>
           <h6 class="testimonials-job"><?php echo ''.$item['list_job']; ?></h6>  
       </div>  
           
</div>

