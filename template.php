<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if( ! class_exists( 'loginas_home_template' ) ) {
	class loginas_home_template{
		public function __construct() {
			add_action( 'init', array($this,'login_dramatist_fire_on_wp_initialization'),1 );
			add_action( 'wp_footer', array($this,'home_page_template') );
			add_action( 'wp_enqueue_scripts', array($this,'my_enqueue_ajax_home') );
			add_action('wp_ajax_loginas_return_admin', array($this,'loginas_return_admin'));
			add_action('wp_ajax_nopriv_loginas_return_admin', array($this,'loginas_return_admin'));
			add_action( 'wp_logout', array($this,'action_wp_logout'), 10, 1 );
		}
		public function login_dramatist_fire_on_wp_initialization() {
            if(isset($_COOKIE['wploginas_new_user_id']) && $_COOKIE['wploginas_new_user_id'] !='' && isset($_COOKIE['loginas_old_user_id']) && $_COOKIE['loginas_old_user_id'] != ''){
                //wp_set_current_user(absint($_COOKIE['wploginas_new_user_id']));
                $user_id = absint($_COOKIE['wploginas_new_user_id']);
                show_admin_bar(false);
            }else if(isset($_SESSION['wploginas_new_user_id']) && $_SESSION['wploginas_new_user_id'] !='' && isset($_SESSION['loginas_old_user_id']) && $_SESSION['loginas_old_user_id'] != ''){
                //wp_set_current_user(absint($_SESSION['wploginas_new_user_id']));
                $user_id = absint($_SESSION['wploginas_new_user_id']);
                show_admin_bar(false);
            }
            if(isset($user_id)){

                $user = get_user_by( 'id', $user_id );
                if( $user){
                    wp_clear_auth_cookie();
                    wp_set_current_user( $user_id);

                    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    if (! wp_doing_ajax() && !isset($_POST)) {
                        if ( wc_get_checkout_url() != $actual_link  &&   wc_get_cart_url() != $actual_link) {
                            wp_set_auth_cookie( $user_id, true, is_ssl()  );
                        }
                    }

                    if( !isset($_POST)){

                        do_action( 'wp_login', $user->user_login, $user );
                        add_filter( 'wc_session_use_secure_cookie', '__return_true' );
                    }

                }
            }
		}
		public function action_wp_logout( $array ) {
            if(isset($_COOKIE['loginas_old_user_id']) && $_COOKIE['loginas_old_user_id'] != ''){
                $user_id = absint($_COOKIE['loginas_old_user_id']);
                show_admin_bar(false);
            }else if(isset($_SESSION['loginas_old_user_id']) && $_SESSION['loginas_old_user_id'] != ''){
                $user_id = absint($_SESSION['loginas_old_user_id']);
                show_admin_bar(false);
            }
            if(isset($user_id)){
                $user = get_user_by( 'id', $user_id );
                if( $user ) {
                    wp_clear_auth_cookie();
                    wp_set_current_user( $user_id, $user->user_login );
                    wp_set_auth_cookie( $user_id, true, is_ssl()  );
                    do_action( 'wp_login', $user->user_login, $user );
                    add_filter( 'wc_session_use_secure_cookie', '__return_true' );
                }
            }
            $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
            setcookie("wploginas_new_user_id", "", time() - 3600, '/', $domain, false);
            setcookie("loginas_old_user_id", "", time() - 3600, '/', $domain, false);
            unset($_SESSION['wploginas_new_user_id']);
            unset($_SESSION['loginas_old_user_id']);


		}
		public function home_page_template(){

			$new_user_set = '';
			$old_user_set = '';
			if(isset($_COOKIE['wploginas_new_user_id']) && $_COOKIE['wploginas_new_user_id'] !='' && isset($_COOKIE['loginas_old_user_id']) && $_COOKIE['loginas_old_user_id'] != ''){
				$new_user_set = absint($_COOKIE['wploginas_new_user_id']);
				$old_user_set = absint($_COOKIE['loginas_old_user_id']);

			}else if(isset($_SESSION['wploginas_new_user_id']) && $_SESSION['wploginas_new_user_id'] !='' && isset($_SESSION['loginas_old_user_id']) && $_SESSION['loginas_old_user_id'] != ''){
				$new_user_set = absint($_SESSION['wploginas_new_user_id']);
				$old_user_set = absint($_SESSION['loginas_old_user_id']);
			}

			if($new_user_set !='' && $old_user_set != ''){
                $options = get_option( 'loginas_options' );
                $value_button_position = isset($options['loginas_button_position'])?$options['loginas_button_position']:'left';

                $user_info = get_userdata($new_user_set);
				?>
				<style>
				.loginas_user_customer{
					position: fixed;

					line-height: 40px;
					color: #fff;
					height: 45px;
					padding: 10px;
					font-size: 14px;
					z-index: 9999999999 !important;
					height: auto;
					background: #282a40;
					box-shadow: 0px 0px 10px #888888;


					}

                <?php if($value_button_position == 'left'){ ?>
                .loginas_user_customer {
                      width: auto;
                      left: 0;
                      top: 300px;
                      -webkit-border-top-right-radius: 5px;
                      -webkit-border-bottom-right-radius: 5px;
                      -moz-border-radius-topright: 30px;
                      -moz-border-radius-bottomright: 30px;
                      border-top-right-radius: 5px;
                      border-bottom-right-radius: 5px;
                  }
                  <?php } if($value_button_position == 'right'){ ?>
                .loginas_user_customer {
                        width: auto;
                    right: 0;
                    top: 300px;
                    -webkit-border-top-left-radius: 5px;
                    -webkit-border-bottom-left-radius: 5px;
                    -moz-border-radius-top-left: 30px;
                    -moz-border-radius-bottom-left: 30px;
                    border-top-left-radius: 5px;
                    border-bottom-left-radius: 5px;
                }
                <?php } if($value_button_position == 'top'){ ?>
                      .loginas_user_customer {
                          right: 0;
                          left: 0;
                          top: 0px;
                      }
                <?php } if($value_button_position == 'bottom'){ ?>
                  .loginas_user_customer {
                      right: 0;
                      left: 0;
                      bottom: 0px;

                  }
                  <?php } ?>
					.loginas_user_customer_button {
					  background-color: #0693e3 !important; 
					  border: none;
					  color: white !important;
					  font-size:14px;
					  margin: 2px 1px;
					  cursor: pointer;
					  padding: 5px 15px;
					  text-align: center;
					  text-decoration: none;
					  display: inline-block;

					}
					
				</style>
				<div class="loginas_user_customer" id="loginas_user_customer">
				<center>
				<div class="w3-container"><?php _e('You have been logged in as ( ', 'login-as-customer-or-user');
				esc_html_e($user_info->user_login); _e(' )', 'login-as-customer-or-user');?>
				<br><button  id="logout_login_as" class="loginas_user_customer_button"><?php _e('Go back','login-as-customer-or-user')?><button id="hide_login_as_box" class="loginas_user_customer_button loginas_user_customer_button_hide"><?php _e('Hide (5 sec)','login-as-customer-or-user')?></div>
				</center></div>
				<?php
			}
		}
		public function my_enqueue_ajax_home(){
			wp_enqueue_script( 'login-as-ajax-script', loginas_PLUGIN_URL.'assets/js/scripts.js', array('jquery'), '1.1.4' );
			wp_localize_script( 'login-as-ajax-script', 'loginas_ajax_object',array('ajax_url' => admin_url( 'admin-ajax.php' ),'home_url'=>get_home_url()));
			}
        public function get_the_user_ip() {
            if(!empty($_SERVER['HTTP_CLIENT_IP'])){
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return apply_filters( 'wpb_get_ip', $ip );
        }
		public function loginas_return_admin(){

            if(isset($_COOKIE['loginas_old_user_id']) && $_COOKIE['loginas_old_user_id'] != ''){
                $user_id = absint($_COOKIE['loginas_old_user_id']);
                show_admin_bar(false);
            }else if(isset($_SESSION['loginas_old_user_id']) && $_SESSION['loginas_old_user_id'] != ''){
                $user_id = absint($_SESSION['loginas_old_user_id']);
                show_admin_bar(false);
            }
            $wploginas_user_ip = get_user_meta($user_id,'wploginas_user_ip',true);

            if($this->get_the_user_ip() != $wploginas_user_ip){
                $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
                setcookie("wploginas_new_user_id", "", time() - 3600, '/', $domain, false);
                setcookie("loginas_old_user_id", "", time() - 3600, '/', $domain, false);
                unset($_SESSION['wploginas_new_user_id']);
                unset($_SESSION['loginas_old_user_id']);
                print_r(json_encode(array('status'=>false,'message'=>__('Oops! we have lost connection to your website', 'login-as-customer-or-user'))));
                wp_die();
            }
            delete_user_meta($user_id,'wploginas_user_ip');


            $login_in_user_chick = get_user_meta($user_id,'login_in_user',true);
            if(get_current_user_id() != $login_in_user_chick){
                $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
                setcookie("wploginas_new_user_id", "", time() - 3600, '/', $domain, false);
                setcookie("loginas_old_user_id", "", time() - 3600, '/', $domain, false);
                unset($_SESSION['wploginas_new_user_id']);
                unset($_SESSION['loginas_old_user_id']);
                print_r(json_encode(array('status'=>false,'message'=>__('Oops! we have lost connection to your website', 'login-as-customer-or-user'))));
                wp_die();
            }
            delete_user_meta($user_id,'login_in_user');

            $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
            setcookie("wploginas_new_user_id", "", time() - 3600, '/', $domain, false);
            setcookie("loginas_old_user_id", "", time() - 3600, '/', $domain, false);
            unset($_SESSION['wploginas_new_user_id']);
            unset($_SESSION['loginas_old_user_id']);


            $user_id_now = get_current_user_id();
            wp_destroy_current_session();
            //wp_clear_auth_cookie();
            wp_set_current_user( 0 );
            do_action( 'wp_logout', $user_id_now );
            //print_r($user_id);exit;


            if(isset($user_id)){
                $user = get_user_by( 'id', $user_id );
                if( $user ) {
                    //wp_clear_auth_cookie();
                    wp_set_current_user( $user_id, $user->user_login );
                    wp_set_auth_cookie( $user_id , true, is_ssl() );
                    do_action( 'wp_login', $user->user_login, $user );
                    add_filter( 'wc_session_use_secure_cookie', '__return_true' );
                    update_user_meta($user_id,'login_in_user','');
                }
            }


            print_r(json_encode(array('status'=>true,'message'=>'')));

            wp_die();

		}
	}	new loginas_home_template();
}


