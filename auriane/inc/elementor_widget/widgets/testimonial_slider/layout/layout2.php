<div class="slider-content">
        <span class="average-rating">
               <span class="jws-star-rating"><span class="jws-star-rated" style="width:100%"></span></span>
        </span>  
        <span class="testimonials-icon"><i class="icon_quotations"></i></span>
        <div class="testimonials-description"><?php echo ''.$item['list_description']; ?></div>
       <?php if(!empty($item['image']['id'])) echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item );?> 
       <div class="testimonials-info">
           <div class="testimonials-title"><?php echo ''.$item['list_name']; ?></div>
           <div class="testimonials-job"><?php echo ''.$item['list_job']; ?></div>  
       </div>  
           
</div>

