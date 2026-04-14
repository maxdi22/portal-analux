<?php

global $jws_option;

$get_columns = (isset($jws_option['select-sidebar-post-columns'])) ? $jws_option['select-sidebar-post-columns'] : '1';

if($get_columns == '1') {
    $columns = ' col-12';
}
if($get_columns == '2') {
    $columns = ' col-xl-6 col-lg-6 col-12';
}

if($get_columns == '3' || (isset($_GET['column']) && $_GET['column'] == '3')) {
    $columns = ' col-xl-4 col-lg-4 col-12';
}

if($get_columns == '4') {
    $columns = ' col-xl-3 col-lg-3 col-12';
}


$layout = (isset($jws_option['blog_layout']) && !empty($jws_option['blog_layout'])) ? $jws_option['blog_layout'] : 'grid';

if(isset($_GET['layout']) && $_GET['layout'] == 'list') {  
    $layout = 'list'; 
}

if($layout == 'list') { ?>
    
    <div class="jws_blog_item true layout2 col-12">
        <?php 
            get_template_part( 'template-parts/content/blog/layout/content' );
        ?>
    </div> 
    
<?php } else { ?>
    
    <div class="jws_blog_item<?php echo esc_attr($columns); ?>">
        <?php 
            get_template_part( 'template-parts/content/blog/layout/content' );
        ?>
    </div>   
    
<?php } ?>
  
