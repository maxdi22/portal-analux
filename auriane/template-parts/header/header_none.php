<?php
/**
 * Displays the post header
 *
 * @package WordPress
 * @subpackage auriane
 * @since 1.0.0
 */
global $jws_option; 
if((is_single() && 'post' == get_post_type()) && isset($jws_option['header_absolute_blog'])) {
           $header_absolute = (isset($jws_option['header_absolute_blog']) && $jws_option['header_absolute_blog']) ? ' header_absolute_yes' : '';
       } 
       else {
           $header_absolute = (isset($jws_option['header_absolute']) && $jws_option['header_absolute']) ? ' header_absolute_yes' : '';
} 

?>  
     <header id="jws_header_default">
                    <div class="row jws_menu_default">
                        <div class="col-xl-2 col-6">
                            <div class="jws_logo text-left">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="jws_logo_inner">
                                    <span class="jws_logo_svg">
                                        <img src="<?php echo JWS_URI_PATH . '/assets/image/logo.svg' ?>"/>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-10 col-6 elementor_jws_menu_layout_menu_horizontal elementor-before-menu-skin-line hidden_mobile hidden_tablet">
                            <div class="elementor-widget-container">
                            <div class="jws_main_menu text-right">
                                <div class="jws_main_menu_inner">
                                <?php 
                                
                                if ( has_nav_menu( 'main_navigation' ) ) {

                                    
                                       $attr = array(
                                             'menu_id' => 'nav',
                                             'container' => '',
                                             'menu_class' => 'nav',
                                             'theme_location' => 'main_navigation',
                                             'echo' => true,
                                             'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                             'depth' => 0,
                                       );
                                       wp_nav_menu($attr);

								} 
                                
                                ?>
                                   
                                </div> 
                            </div>
                        </div>
                        </div>
                         <div class="col-xl-10 col-6 elemetor-menu-mobile hidden_dektop">
                         <span class="jws-tiger-mobile">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none"><path d="M1 7H21" stroke="#040202" stroke-linecap="round"></path><path d="M1 12H21" stroke="#040202" stroke-linecap="round"></path><path d="M1 17H21" stroke="#040202" stroke-linecap="round"></path></svg>
                         </span>
                        <div class="menu-mobile-default">
                                  <div class="elementor_jws_menu_layout_menu_vertical">
                            <div class="jws_main_menu text-right ">
                                <div class="jws_main_menu_inner">
                                <?php 
                                
                                if ( has_nav_menu( 'main_navigation' ) ) {

                                    
                                       $attr = array(
                                             'menu_id' => 'nav',
                                             'container' => '',
                                             'menu_class' => 'nav',
                                             'theme_location' => 'main_navigation',
                                             'echo' => true,
                                             'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                             'depth' => 0,
                                       );
                                       wp_nav_menu($attr);

								} else {

									?>
                                    
                                    <ul id="nav" class="nav">
                                       <?php wp_list_pages(
    										array(
    											'match_menu_classes' => true,
    											'show_sub_menu_icons' => true,
    											'title_li' => false,
    											'walker'   => new Jws_Walker_Page(),
    										)
    									   );
                                       ?>
                                    </ul>
                                    
                                    <?php

								}
                                
                                ?>
                                   
                                </div> 
                            </div>
            
                        </div>
                        </div>
                        <span class="overlay"></span>
                        </div>
            
                    </div>
        </header> 
 