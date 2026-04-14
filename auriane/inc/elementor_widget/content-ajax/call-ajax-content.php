<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
function alnuar_auto_login_new_user_after_registration( $user_id ) {

		if (isset($_POST['password'])) {
			wp_set_password( $_POST['password'], $user_id ); //Password previously checked in add_filter > registration_errors
		}
	

}
add_action( 'user_register', 'alnuar_auto_login_new_user_after_registration' );

function auto_redirect_after_logout(){
   global $jws_option;  
   if(isset($jws_option['logout_form_redirect']) && !empty($jws_option['logout_form_redirect'])) {
      $login_redirect = get_page_link($jws_option['logout_form_redirect']);
   }else {
      $login_redirect = home_url('/');
   }  
  wp_safe_redirect($login_redirect);
  exit;
}
add_action('wp_logout','auto_redirect_after_logout');


if (!function_exists('jws_get_content_form_login')) {
    function jws_get_content_form_login($show_login,$show_register,$active)
    {       
    $registration_enabled = get_option( 'users_can_register' );
    $active_login = $active_signup = '';
    if($active == 'login' && !isset($_GET['signup'])) {
        $active_class = ' in-login';
    }
    if($active == 'signup' || (isset($_GET['signup']) && $_GET['signup'] == 'true')) {
        $active_class = ' in-register';
    }
    
    $account_link = class_exists('Woocommerce') ? wc_get_page_permalink( 'myaccount' ) : home_url( '/' );  
    $wc_lostpassword_url = class_exists('Woocommerce') ? wc_lostpassword_url( ) : home_url( '/' );  
    
    $fb_id = jws_theme_get_option('facebook_app_id');
    $google_id = jws_theme_get_option('google_app_id');
   
     ?>

    <div id="jws-login-form" class="jws-login-form<?php echo esc_attr($active_class); ?>">
		<div class="jws-login-container">
            <div class="jws-animation">
            <div class="heading-form">
              <h4 class="heading-form-login"><?php echo esc_html__('Login','auriane') ?></h4>
              <h4 class="heading-form-register"><?php echo esc_html__('Register','auriane') ?></h4>
            </div>  
          
               
                    <div class="login-width-social">
                        <?php 
                        
                           if(class_exists('NextendSocialLogin', false)){
                                    echo NextendSocialLogin::renderButtonsWithContainer();    
                             
                            } else {
                                 if(!empty($google_id) || !empty($fb_id)) : ?>  
                                 <div class="social-list">
                                    <?php if(!empty($google_id)) : ?>
                                    <a class="jws-connect-google" href="<?php echo add_query_arg( 'connect_auth', 'google', $account_link ); ?>"><?php echo esc_html__('Continue with Google','auriane'); ?></a>
                                    <?php endif; ?>
                                    <?php if(!empty($fb_id)) : ?>
                                    <a class="jws-connect-facebook" href="<?php echo add_query_arg( 'connect_auth', 'facebook', $account_link ); ?>"><?php echo esc_html__('Continue with Facebook','auriane'); ?></a>
                                    <?php endif; ?>
                                </div>
                               <?php endif; } ?> 

                        <div class="social-line">
                            <span><?php echo esc_html__('or','auriane'); ?></span>
                        </div>
                    </div>
              
            <div class="form-contaier">
            <?php if($show_login) :  ?>
			<div class="jws-login slick-slide">
               
				<form name="loginpopopform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
                
					<p class="login-username">
						<input type="text" name="log" placeholder="<?php esc_attr_e( 'Username or email', 'auriane' ); ?>" class="input required" value="" size="20" />
					</p>
					<p class="login-password">
						<input type="password" name="pwd" placeholder="<?php esc_attr_e( 'Password', 'auriane' ); ?>" class="input required" value="" size="20" />
                        <span class="field-icon toggle-password2 jws-icon-eye-thin"></span>
					</p>
				
                    <p class="forgetmenot login-remember">
						<label for="popupRememberme"><input name="rememberme" type="checkbox" value="forever" id="popupRememberme" /> <?php esc_html_e( 'Remember me', 'auriane' ); ?>
                        
						</label><?php echo '<a class="lost-pass-link" href="' . $wc_lostpassword_url . '" title="' . esc_attr__( 'Lost Password', 'auriane' ) . '">' . esc_html__( 'Lost your password?', 'auriane' ) . '</a>'; ?>
					</p>
					<div class="g-recaptcha" id="recaptcha7"></div>
					<p class="submit login-submit jws-button">
						<input type="submit" name="wp-submit" class="button elementor-button btn-main" value="<?php esc_attr_e( 'Login', 'auriane' ); ?>" />
						<input type="hidden" name="testcookie" value="1" />
					</p>


				</form>
				<?php
				if ( $show_login && ($show_register && $registration_enabled) ) {
					echo '<a class="register change-form" href="' . esc_url( jws_get_register_url() ) . '">' . esc_html__( 'Not registered yet? ', 'auriane' ) .'<span>'. esc_html__( 'Create an Account', 'auriane' ).'</span></a>';
				}
				?>
                <div class="popup-message"></div>
			</div>
            <?php endif; ?>
			<?php if ( $registration_enabled && $show_register ): ?>
				<div class="jws-register slick-slide">
					<form class="auto_login" name="registerformpopup"  method="post" novalidate="novalidate">

                        <?php wp_nonce_field( 'ajax_register_nonce', 'register_security' ); ?>

						<p>
							<input placeholder="<?php esc_attr_e( 'Username', 'auriane' ); ?>" type="text" name="user_login" class="input required" />
						</p>

						<p>
							<input placeholder="<?php esc_attr_e( 'Email', 'auriane' ); ?>" type="email" name="user_email" class="input required" />
						</p>

				
							<p>
								<input placeholder="<?php esc_attr_e( 'Password', 'auriane' ); ?>" type="password" name="password" class="input required" />
                                <span class="field-icon toggle-password2 jws-icon-eye-thin"></span>
                              
							</p>
							<p>
								<input placeholder="<?php esc_attr_e( 'Repeat Password', 'auriane' ); ?>" type="password" name="repeat_password" class="input required" />
							</p>

		                <div class="g-recaptcha" id="recaptcha8"></div>  
						<p>

							<input type="hidden" name="modify_user_notification" value="1">
						</p>

						<?php do_action( 'signup_hidden_fields', 'create-another-site' ); ?>

						<p class="submit jws-button">
							<input type="submit" name="wp-submit" class="button elementor-button btn-main" value="<?php echo esc_attr_x( 'Sign up', 'Login popup form', 'auriane' ); ?>" />
						</p>
					</form>
					<?php  if($show_login && ($show_register && $registration_enabled)) echo '<a class="login change-form" href="' . esc_url( jws_get_login_page_url() ) . '">' . esc_html__( 'Already have an account? ', 'auriane' ) .'<span>'. esc_html__( 'Log in', 'auriane' ).'</span></a>'; ?>
                      <div class="meter">
                        <div class="meter-box">
                            <span class="box1"></span>
                            <span class="box2"></span>
                            <span class="box3"></span>
                            <span class="box4"></span>
                              <span class="text-meter"></span>
                        </div>
                      
                      </div>  
                      <div class="jws-password-hint">
                           <?php echo esc_html__('Hint: The password should be at least eight characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ & ).','auriane'); ?>
                     </div>
					<div class="popup-message"></div>
				</div>
			<?php endif; ?>
            </div>
            </div>
		</div>
	</div>
    <?php
    }
}     


/**
 * Filter lost password link
 *
 * @param $url
 *
 * @return string
 */
if ( ! function_exists( 'jws_get_lost_password_url' ) ) {
	function jws_get_lost_password_url() {
		$url = add_query_arg( 'action', 'lostpassword', jws_get_login_page_url() );

		return $url;
	}
}

/**
 * Get login page url
 *
 * @return false|string
 */
if ( ! function_exists( 'jws_get_login_page_url' ) ) {
	function jws_get_login_page_url() {

		if ( function_exists('jws_plugin_active') && !jws_plugin_active( 'js_composer/js_composer.php' ) ) {
			return wp_login_url();  
		}

	
		global $wpdb;
		$page = $wpdb->get_col(
		$wpdb->prepare(
					"SELECT p.ID FROM $wpdb->posts AS p INNER JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id
			WHERE 	pm.meta_key = %s
			AND 	pm.meta_value = %s
			AND		p.post_type = %s
			AND		p.post_status = %s",
					'jws_login_page',
					'1',
					'page',
					'publish'
				)
			);
			if ( ! empty( $page[0] ) ) {
				return get_permalink( $page[0] );
			}
	

		return wp_login_url();

	}
}


/**
 * Filter register link
 *
 * @param $register_url
 *
 * @return string|void
 */
if ( ! function_exists( 'jws_get_register_url' ) ) {
	function jws_get_register_url() {
		$url = add_query_arg( 'action', 'register', jws_get_login_page_url() );

		return $url;
	}
}
if ( ! is_multisite() ) {
	add_filter( 'register_url', 'jws_get_register_url' );
}
if ( ! function_exists( 'jws_register_ajax_callback' ) ) {
	function jws_register_ajax_callback() {

		// First check the nonce, if it fails the function will break
		$secure = check_ajax_referer( 'ajax_register_nonce', 'register_security', false );

		if ( ! $secure ) {
			$response_data = array(
				'message' => '<p class="jws-dealer-note red">' .$secure. '</p>'
			);

			wp_send_json_error( $response_data );
		}

		parse_str( $_POST['data'], $data );
        $code    = -1;
		foreach ( $data as $k => $v ) {
			$_POST[ $k ] = $v;
		}

		$_POST['is_popup_register'] = 1;

		if ( ! empty( $data['modify_user_notification'] ) ) {
			$_REQUEST['modify_user_notification'] = 1;
		}

		$info = array();

		$info['user_login'] = sanitize_user( $data['user_login'] );
		$info['user_email'] = sanitize_email( $data['user_email'] );
		$info['user_pass']  = sanitize_text_field( $data['password'] );
        
        
          if(!empty($info['user_login']) && isset( $info['user_login'] )) { 
                if (mb_strlen($info['user_login']) < 3) {
                        $response_data = array(
    					   'message' => esc_html__( 'Your User Name Must Contain At Least 3 Characters!', 'auriane' )
    				    );
                        wp_send_json_error( $response_data );
                }
        }

        if(!empty($info['user_pass']) && isset( $info['user_pass'] )) {
                $password = $info['user_pass'];
                $cpassword = $data['repeat_password'];
                if (mb_strlen($info['user_pass']) < 8) {
                    $response_data = array(
					   'message' => esc_html__( 'Your Password Must Contain At Least 8 Characters!', 'auriane' )
				    );
                    wp_send_json_error( $response_data );
                }

                elseif(!preg_match("#[A-Z]+#",$password)) {
                    $response_data = array(
					   'message' => esc_html__( 'Your Password Must Contain At Least 1 Capital Letter!', 'auriane' )
				    );
                    wp_send_json_error( $response_data );
                }

                elseif (strcmp($password, $cpassword) !== 0) {
                    $response_data = array(
					   'message' => esc_html__( 'Passwords must match!', 'auriane' )
				    );
                    wp_send_json_error( $response_data );
                }

            } 
         
         
		// Register the user
		$user_register = register_new_user( $info['user_login'], $info['user_email'] );

		if ( is_wp_error( $user_register ) ) {
			$error = $user_register->get_error_codes();
      
			if ( in_array( 'empty_username', $error ) ) {
				$response_data = array(
					'message' => '<p class="jws-dealer-note red">' . esc_html__( 'Please enter a username!', 'auriane' ) . '</p>'
				);
			}elseif ( in_array( 'empty_password', $error ) ) {
				$response_data = array(
					'message' => '<p class="jws-dealer-note red">' . esc_html__( 'Please enter a password!', 'auriane' ) . '</p>'
				);
			} elseif ( in_array( 'invalid_username', $error ) ) {
				$response_data = array(
					'message' => '<p class="jws-dealer-note red">' . esc_html__( 'The username is invalid. Please try again!', 'auriane' ) . '</p>'
				);
			} elseif ( in_array( 'username_exists', $error ) ) {
				$response_data = array(
					'message' => '<p class="jws-dealer-note red">' . esc_html__( 'This username is already registered. Please choose another one.', 'auriane' ) . '</p>'
				);
			} elseif ( in_array( 'empty_email', $error ) ) {
				$response_data = array(
					'message' => '<p class="jws-dealer-note red">' . esc_html__( 'Please type your e-mail address!', 'auriane' ) . '</p>'
				);
			} elseif ( in_array( 'invalid_email', $error ) ) {
				$response_data = array(
					'message' => '<p class="jws-dealer-note red">' . esc_html__( 'The email address isn\'t correct. Please try again!', 'auriane' ) . '</p>'
				);
			} elseif ( in_array( 'email_exists', $error ) ) {
				$response_data = array(
					'message' => '<p class="jws-dealer-note red">' . esc_html__( 'This email is already registered. Please choose another one!', 'auriane' ) . '</p>'
				);
			}

			wp_send_json_error( $response_data );
		} else {
    		      
			    $code  = 1; 
                global $jws_option;
                $user = get_user_by('login', $info['user_login']);
                if($jws_option['verify_email'] && !empty($jws_option['page_mail'])) {
                    $response_data = array(
    					'message' => '<p class="message red">' .  esc_html__( 'Thank you for creating your account. You will need to confirm your email address in order to activate your account. An email containing the activation link has been sent to your email address. If the email does not arrive within a few minutes, check your spam folder or','auriane').'<a href="'.get_page_link($jws_option['page_mail']).'?u='.$user->ID.'">'.esc_html__('resend mail','auriane').'</a></p>'
    				);  
                }else{  
                    
                    $user_id = $user->ID;
                    if( $user ) {
                        wp_set_current_user( $user_id, $user->user_login );
                        wp_set_auth_cookie( $user_id );

                        do_action('wp_login', $user->user_login, $user);
                    }
            		if($jws_option['select-page-login-register-author']) {
            		    $login_redirect   = get_author_posts_url($user_id); 
            		}
                    elseif(isset($jws_option['login_form_redirect']) && !empty($jws_option['login_form_redirect'])) {
                        $login_redirect = get_page_link($jws_option['login_form_redirect']);
                    }
                    else {
            		    $current_page_id = get_queried_object_id();	 
            			$login_redirect = get_permalink( $current_page_id );
            		}
                    
                    $response_data = array(
    				    'code'    => $code,
        				'message' => '<p class="jws-dealer-note green">' . esc_html__( 'Login successful, redirecting...', 'auriane' ) . '</p>',
                        'redirect' => $login_redirect
    				); 
  
                }

				wp_send_json_success( $response_data );
		
		}
  
	}
}

if ( get_option( 'users_can_register' ) ) {
	add_action( 'wp_ajax_nopriv_jws_register_ajax', 'jws_register_ajax_callback' );
    add_action( 'wp_ajax_jws_register_ajax', 'jws_login_ajax_callback' );
}

if ( ! function_exists( 'jws_login_ajax_callback' ) ) {
	function jws_login_ajax_callback() {
		//ob_start();
     
		if ( empty( $_REQUEST['data'] ) ) {
			$response_data = array(
				'code'    => - 1,
				'message' => '<p class="jws-dealer-note red">' . esc_html__( 'Something wrong. Please try again.', 'auriane' ) . '</p>'
			);
		} else {

			parse_str( $_REQUEST['data'], $login_data );

			foreach ( $login_data as $k => $v ) {
				$_POST[ $k ] = $v;
			}
   
			$creds = array();
            $creds['user_login'] = $login_data['log'];
            $creds['user_password'] = $login_data['pwd'];
            $creds['remember'] = $login_data['rememberme'];
            $secure_cookie = is_ssl() ? true : false;


            $user = wp_signon($creds, $secure_cookie);
			$user_verify = wp_signon( array(), is_ssl() );

			$code    = 1;
			$message = '';

            global $jws_option;    
			if($jws_option['select-page-login-register-author']) {
			    $login_redirect   = get_author_posts_url($user->ID); 
			}
            elseif(isset($jws_option['login_form_redirect']) && !empty($jws_option['login_form_redirect'])) {
                $login_redirect = get_page_link($jws_option['login_form_redirect']);
            }
            else {
			    $current_page_id = get_queried_object_id();	 
				$login_redirect = get_permalink( $current_page_id );
			}


			if ( is_wp_error( $user_verify ) ) {
				if ( ! empty( $user_verify->errors ) ) {
					$errors = $user_verify->errors;

					if ( ! empty( $errors['invalid_username'] ) ) {
						$message = $errors['invalid_username'];
					}elseif(! empty( $errors['incorrect_password'] )) {
					   $message = $errors['incorrect_password'];
					}elseif ( is_array( $errors['invalid_email']) ) {
        				$message =  $errors['invalid_email'];
        			}else {
						$message = $user_verify;
					}
				} else {
					$message = esc_html__( 'Something wrong. Please try again.', 'auriane' );
				}
				$code = - 1;
			} else {
				$message = '<p class="jws-dealer-note green">' . esc_html__( 'Login successful, redirecting...', 'auriane' ) . '</p>';
			}

			$response_data = array(
				'code'    => $code,
				'message' => $message,
                'redirect' => $login_redirect
			);

		}
		wp_send_json_success( $response_data );

	}
}
add_action( 'wp_ajax_nopriv_jws_login_ajax', 'jws_login_ajax_callback' );
add_action( 'wp_ajax_jws_login_ajax', 'jws_login_ajax_callback' );


function wp_authenticate_user( $userdata ) {            // when the user logs in, checks whether their email is verified
    global $jws_option; 
    if(isset($jws_option['verify_email']) && !empty($jws_option['page_mail'])) {
        $user = get_userdata( $userdata->ID );   
        $user_roles = $user->roles; 
        if ( in_array( 'editor' , $user_roles, true ) || in_array( 'administrator' , $user_roles, true ) ) {
             update_user_meta($userdata->ID, 'is_activated', 1);
           
        }
        $has_activation_status = get_user_meta($userdata->ID, 'is_activated', false);
        if ($has_activation_status) {                           // checks if this is an older account without activation status; skips the rest of the function if it is
            $isActivated = get_user_meta($userdata->ID, 'is_activated', true);
            if ( !$isActivated ) {
                my_user_register( $userdata->ID );              // resends the activation mail if the account is not activated
                $userdata = new WP_Error(
                    'my_theme_confirmation_error',
                    '<strong>'.__('Error:','auriane').'</strong>'.  esc_html__( 'Your account has to be activated before you can login. Please click the link in the activation email that has been sent to you. If you do not receive the activation email within a few minutes, check your spam folder or','auriane').'<a href="'.get_page_link($jws_option['page_mail']).'?u='.$userdata->ID.'">'.esc_html__('resend mail','auriane').'</a>.'
                );
            }
        }
    }
    return $userdata;
}

function my_user_register($user_id) {               // when a user registers, sends them an email to verify their account
    global $jws_option;
    
    if($jws_option['verify_email'] && !empty($jws_option['page_mail'])) {
    $user_info = get_userdata($user_id);                                            // gets user data
    $code = md5($user_id);                                                            // creates md5 code to verify later
    $string = array('id'=>$user_id, 'code'=>$code);                                 // makes it into a code to send it to user via email
    update_user_meta($user_id, 'is_activated', 0);                                  // creates activation code and activation status in the database
    update_user_meta($user_id, 'activationcode', $code);
    $loc = function_exists('ct_64') ? ct_64( serialize($string)) : '';
    $url = get_page_link($jws_option['page_mail']).'?p=' .$loc;       // creates the activation url
    $html = ( '
    
            <h1 style="text-align: center;">Hello '.$user_info->user_login.'</h1>
            <h2 style="text-align: center;">Welcome to auriane</h2>
            <p style="text-align: center;">Please verify your email address and complete the registration process.</p>
            <a style="    display: block;
                margin: 0 auto;
                background: #0052cc;
                color: #ffffff;
                border-radius: 3px;
                width: 170px;
                height: 45px;
                text-align: center;
                font-size: 14px;
                line-height: 45px;
                text-decoration: none;
                margin-top: 25px;"
             href="'.$url.'">Verify Your Email</a>
    
    ' ); // This is the html template for your email message body
        // sends the email to the user
    
    
    $to = $user_info->user_email;
    
    $body = $html;
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if(function_exists('jws_sv_ct3')) {
       jws_sv_ct3( $to, get_bloginfo( 'name' ) , $body, $headers ); 
    }
    } 
   
}


add_filter('wp_authenticate_user', 'wp_authenticate_user',10,2);
add_action('user_register', 'my_user_register',10,2);
remove_action( 'register_new_user', 'wp_send_new_user_notifications' );

if( current_user_can('editor') || current_user_can('administrator') ) {  
 $user_id = wp_get_current_user();   
 update_user_meta($user_id->ID, 'is_activated', 1); 
} 

function new_modify_user_table( $column ) {
    $column['activated'] = 'Activated';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'activated' :
            return get_user_meta($user_id, 'is_activated', true); 
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );

/*
* Function ajax filter
*/
if (!function_exists('jws_ajax_product_filter')) {
    function jws_ajax_product_filter()
    {
        $inc_product_ids = $ex_product_ids = $asset_type = $filter_categories = $posts_per_page = $orderby = $order = null;
        if (isset($_POST['ex_product_ids'])) {
            $ex_product_ids = $_POST['ex_product_ids'];
        }
        if (isset($_POST['inc_product_ids'])) {
            $inc_product_ids = $_POST['inc_product_ids'];
        }
        if (isset($_POST['asset_type'])) {
            $asset_type = sanitize_text_field($_POST['asset_type']);
        }
        if (isset($_POST['filter_categories'])) {
            $filter_categories = sanitize_text_field($_POST['filter_categories']);
        }
        if (isset($_POST['posts_per_page'])) {
            $posts_per_page = intval($_POST['posts_per_page']);
        }
        if (isset($_POST['orderby'])) {
            $orderby = sanitize_text_field($_POST['orderby']);
        }
        if (isset($_POST['order'])) {
            $order = strtoupper(sanitize_text_field($_POST['order']));
        }

        if (is_front_page()) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }
        
        if(isset($_POST['paged'] ) && $_POST['paged'] > 1 ) $paged = $_POST['paged'];
        
        if($_POST['layout'] == 'carousel'){
            $grid_class = 'product-item product slick-slide '.$_POST['display'].'';
        }else {
            $class    = 'grid-layout row';
            $grid_class = 'product-item product '.$_POST['display'].' col-xl-' . $_POST['columns'] . ' col-lg-' . $_POST['columns_tablet'] . ' col-' . $_POST['columns_mobile'] .'';
        }
        
        
        
        
         if($_POST['layout'] == 'carousel') {
            
                
        $_POST['show_nav'] ? $_POST['show_nav'] : $_POST['show_nav'] = 'false';
        $_POST['show_nav_tablet'] ? $_POST['show_nav_tablet'] : $_POST['show_nav_tablet'] = 'false';
        $_POST['show_nav_mobile'] ? $_POST['show_nav_mobile'] : $_POST['show_nav_mobile'] = 'false';
        
        $_POST['show_pag'] ? $_POST['show_pag'] : $_POST['show_pag'] = 'false';
        $_POST['show_pag_tablet'] ? $_POST['show_pag_tablet'] : $_POST['show_pag_tablet'] = 'false';
        $_POST['show_pag_mobile'] ? $_POST['show_pag_mobile'] : $_POST['show_pag_mobile'] = 'false';
        
        $_POST['autoplay'] ? $_POST['autoplay'] : $_POST['autoplay'] = 'false';
        $_POST['autoplay_tablet'] ? $_POST['autoplay_tablet'] : $_POST['autoplay_tablet'] = 'false';
        $_POST['autoplay_mobile'] ? $_POST['autoplay_mobile'] : $_POST['autoplay_mobile'] = 'false';  
         $infinite = ($_POST['infinite']) ? 'true' : 'false';
        if($_POST['enble_muntirow'] == 'yes') {
           $_POST['number_row'] ? $_POST['number_row'] : $_POST['number_row'] = 1;
           $_POST['number_row_tablet'] ? $_POST['number_row_tablet'] : $_POST['number_row_tablet'] = 1;
           $_POST['number_row_mobile'] ? $_POST['number_row_mobile'] : $_POST['number_mobile'] = 1;
           
           $_POST['number_col_row'] ? $_POST['number_col_row'] : $_POST['number_col_row'] = 1;
           $_POST['number_col_row_tablet'] ? $_POST['number_col_row_tablet'] : $_POST['number_col_row_tablet'] = 1;
           $_POST['number_col_row_mobile'] ? $_POST['number_col_row_mobile'] : $_POST['number_col_row_mobile'] = 1;
    
        
       
            
            $data_slick = 'data-slick=\'{"rows":"'.$_POST['number_row'].'","slidesPerRow":"'.$_POST['number_col_row'].'","slidesToShow":1 ,"slidesToScroll":1,"autoplay": '.$_POST['autoplay'].',"arrows": '.$_POST['show_nav'].', "dots":'.$_POST['show_pag'].',
            "speed": '.$_POST['speed'].', "responsive":[{"breakpoint": 1024,"settings":{"rows":"'.$_POST['number_row_tablet'].'","slidesPerRow":"'.$_POST['number_col_row_tablet'].'"}},
            {"breakpoint": 768,"settings":{"rows":"'.$_POST['number_row_mobile'].'","slidesPerRow":"'.$_POST['number_col_row_mobile'] .'"}}]}\''; 
              }else{
                   $data_slick = 'data-slick=\'{"slidesToShow":'.$_POST['slides_to_show'].' ,"slidesToScroll": '.$_POST['scroll'].',"autoplay": '.$_POST['autoplay'].',"arrows": '.$_POST['show_nav'].', "dots":'.$_POST['show_pag'].',
                    "speed": '.$_POST['speed'].', "infinite":'.$infinite.' , "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": '.$_POST['slides_to_show_tablet'].',"slidesToScroll": '.$_POST['scroll'].'}},
                    {"breakpoint": 768,"settings":{"slidesToShow": '.$_POST['slides_to_show_mobile'].',"slidesToScroll": '.$_POST['scroll'].'}}]}\''; 
              }   
          }else {
                $data_slick = '';
          }
        
        
        $wc_attr = array(
            'post_type' => 'product',
            'product_cat' =>  $filter_categories,
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'orderby' => $orderby,
            'order' => $order,  
        );
        
        
        if(!empty($ex_product_ids)) {
           $wc_attr['post__not_in'] = $ex_product_ids;
        }
        
  
        
        if(!empty($inc_product_ids)) {
           $wc_attr['post__in'] = $inc_product_ids;
        }

        if ($asset_type) {
            switch ($asset_type) {
                case 'featured':
                    $meta_query[] = array(
                        array(
                            'taxonomy' => 'product_visibility',
                            'field'    => 'name',
                            'terms'    => 'featured',
                            'operator' => 'IN'
                        ),
                    );
                    $wc_attr['tax_query'] = $meta_query;
                    break;
                case 'onsale':
                    $product_ids_on_sale = wc_get_product_ids_on_sale();
                    $wc_attr['post__in'] = $product_ids_on_sale;
                    break;
                case 'best-selling':
                    $wc_attr['meta_key'] = 'total_sales';
                    $wc_attr['orderby']  = 'meta_value_num';
                    break;
                case 'latest':
                    $wc_attr['orderby'] = 'date';
                    break;
                case 'toprate':
                    $wc_attr['orderby'] = 'meta_value_num';
                    $wc_attr['meta_key'] = '_wc_average_rating';
                    $wc_attr['order'] = 'DESC';
                    break;
                case 'deal':
                    $product_ids_on_sale = wc_get_product_ids_on_sale();
                    $wc_attr['post__in'] = $product_ids_on_sale;
                    $wc_attr['meta_query'] = array(
                        'relation' => 'AND',
                        array(
                            'key' => '_sale_price_dates_to',
                            'value' => time(),
                            'compare' => '>'
                        )
                    );
                    break;
                default:
                    break;
            }
        }


        if (isset($_POST['product_attribute']) && isset($_POST['attribute_value'])) {
            if (is_array($_POST['product_attribute'])) {
                foreach ($_POST['product_attribute'] as $key => $value) {
                    $tax_query[] = array(
                        'taxonomy' => $value,
                        'terms'    => array_map('sanitize_title', (array)$_POST['attribute_value'][$key]),
                        'field'    => 'slug',
                        'operator' => 'IN'
                    );
                }
            } else {
                $tax_query[] = array(
                    'taxonomy' => sanitize_title($_POST['product_attribute']),
                    'terms'    => array_map('sanitize_title', (array)$_POST['attribute_value']),
                    'field'    => 'slug',
                    'operator' => 'IN'
                );
            }
        }

        if (isset($_POST['product_tag'])) {
            $wc_attr['product_tag'] = sanitize_title($_POST['product_tag']);
        }

        if (isset($_POST['price_filter']) && $_POST['price_filter'] > 0) {
            $min = (intval($_POST['price_filter']) - 1)*intval($_POST['price_filter_range']);
            $max = intval($_POST['price_filter'])*intval($_POST['price_filter_range']);
            $meta_query[] = array(
                'key'     => '_price',
                'value'   => array($min, $max),
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC'
            );
        }

        if (isset($_POST['s']) && $_POST['s'] != '') {
            $wc_attr['s'] = esc_attr($_POST['s']);
        }

        $product_query = new WP_Query($wc_attr);
        
        ob_start(); ?>
        
        <?php if(isset($_POST['paged'] ) && $_POST['paged'] > 1 ) {
             while ($product_query->have_posts()) {
                 $product_query->the_post();
                 echo '<div class="'.esc_attr($grid_class).'">';    
                 include( JWS_ABS_PATH_WC.'/archive-layout/content-'.$_POST['display'].'.php'  );
                 echo '</div>';
            } 
            
        } else { ?>
            
 
            <?php 
                if($_POST['layout'] == 'carousel') echo '<div class="carousel" '.$data_slick.'>';  
                        while ($product_query->have_posts()) {
                             $product_query->the_post();
                             echo '<div class="'.esc_attr($grid_class).'">';    
                             wc_get_template_part( 'archive-layout/content', $_POST['display'] );
                             echo '</div>';
                        }
                if($_POST['layout'] == 'carousel') echo '</div>'; 
            ?>
          
            
        <?php } ?>
        <?php
        wp_reset_postdata();
        $output = ob_get_clean();
        
        $output =  array(
    	    		'items' => $output,
    	    		'status' => ( $product_query->max_num_pages > $paged ) ? 'have-posts' : 'no-more-posts'
    	 );

        
        
      
      
       echo json_encode( $output );

		die();
    }

    add_action('wp_ajax_jws_ajax_product_filter', 'jws_ajax_product_filter');
    add_action('wp_ajax_nopriv_jws_ajax_product_filter', 'jws_ajax_product_filter');
}

/*
* Function ajax filter
*/
if (!function_exists('jws_ajax_category_tabs_filter')) {
    function jws_ajax_category_tabs_filter()
    {

     if(!empty($_POST['filter_categories'])) {
      
        if($_POST['filter_categories'] == 'all'){
             if($_POST['filter_categories_for_asset']){
                foreach ($_POST['filter_categories_for_asset'] as $product_cat_slug) {
                    $product_cat = get_term_by('slug', $product_cat_slug, 'product_cat');
  
                    ?>
                    
                    <div class="<?php echo esc_attr($_POST['columns']); ?>">
                        <a href="<?php echo get_term_link( $product_cat->term_id, 'product_cat' );  ?>">
                            <?php echo wp_get_attachment_image( get_term_meta( $product_cat->term_id, 'thumbnail_id', 1 ), 'full' ); ?>
                            <h4><?php echo esc_html($product_cat->name); ?></h4>
                        </a>
                    </div>
                    
                    <?php
   
                } 
            }     
        }else{
            $term = get_queried_object();
        
            $category = get_term_by( 'slug', $_POST['filter_categories'], 'product_cat' );
        
            $id = $category->term_id;
            
            $children = get_categories(
              array(
                'taxonomy' => 'product_cat',
                'parent' =>$id
              )
            );
        
            if ( $children ) { 
                foreach( $children as $product_cat )
                {
                    ?>
                    
                    <div class="<?php echo esc_attr($_POST['columns']); ?>">
                        <a href="<?php echo get_term_link( $product_cat->term_id, 'product_cat' );  ?>">
                            <?php echo wp_get_attachment_image( get_term_meta( $product_cat->term_id, 'thumbnail_id', 1 ), 'full' ); ?>
                            <h4><?php echo esc_html($product_cat->name); ?></h4>
                        </a>
                    </div>
                    
                    <?php
                }
            }  
        }
        wp_die();
    }  
       
    }

    add_action('wp_ajax_jws_ajax_category_tabs_filter', 'jws_ajax_category_tabs_filter');
    add_action('wp_ajax_nopriv_jws_ajax_category_tabs_filter', 'jws_ajax_category_tabs_filter');
}
