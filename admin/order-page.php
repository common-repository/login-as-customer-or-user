<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if( ! class_exists( 'loginas_order_page' ) ) {
	class loginas_order_page{

        private $order_lasts_id = array();
		
		public function __construct() {
			add_filter( 'manage_edit-shop_order_columns', array($this,'custom_shop_order_column'), 20 );
			add_action( 'manage_shop_order_posts_custom_column' , array($this,'custom_orders_list_column_content'), 20, 2 );
			add_action( 'admin_footer', array($this,'my_action_javascript') );
			add_action( 'wp_ajax_my_action_loginas', array($this,'my_action') );
            add_action('restrict_manage_posts',array($this,'restrict_manage_movie_sort_by_genre'));
            add_action( 'add_meta_boxes', array($this,'login_as_order_box' ));
        }
        function login_as_order_box() {
            $options = get_option( 'loginas_options' );
            if($this->UserConditional($options)){
                return;
            }
            add_meta_box( 'login-as-order-box', __( 'Login as', 'login-as-customer-or-user' ), array($this,'login_as_order_box_callback'), 'shop_order' ,'side','high' );
        }
        function login_as_order_box_callback( $post ) {
			$post_id = $post;
            $order = wc_get_order($post_id);
			$user_id = $order->get_user_id();
			if($user_id == get_current_user_id()){
				return _e('Current user','login-as-customer-or-user');
			}
            $user_info = get_userdata($user_id);
			if(!empty($user_info)){
            $user_roles=$user_info->roles;
            if(in_array('administrator', $user_roles)){
                return __('Administrator user','login-as-customer-or-user');
            }

			
				if(in_array($post_id->ID, $this->restrict_manage_movie_sort_by_genre('shop_order'))){
				?>
					<br><a href="#" class="page-title-action btn-click-login-as none_set "
					data-user="<?php esc_attr_e($user_id);?>"
					data-admin="<?php esc_attr_e(get_current_user_id());?>"><?php _e( 'Login as this user','login-as-customer-or-user');?></a>
				<?php
				}else{
					?>
					<a href="https://www.wp-buy.com/product/login-as-customer-or-user-pro" title="<?php _e( 'To unlock this limit and get more features, Please upgrade to our premium version','login-as-customer-or-user');?>" target="_blank" ><b style="color:#ef860e"><?php _e( 'Unlock Feature','login-as-customer-or-user');?></b></a>
					<?php
				}
			}else{
				_e('Guest','login-as-customer-or-user');
			}
        }
        function restrict_manage_movie_sort_by_genre($post_type) {
            if( 'shop_order' !== $post_type ){
                return;
            }
            global $wpdb;
            $this->order_lasts_id = $wpdb->get_results( "SELECT ID FROM $wpdb->posts WHERE post_type = 'shop_order' ORDER BY ID DESC LIMIT 20",ARRAY_N  );
            $this->order_lasts_id = array_column($this->order_lasts_id, '0');
            return $this->order_lasts_id;
		}
		public function UserConditional($options = array()){

			if(empty($options)){
				return true;
			}
			if(!isset($options['loginas_status']) || $options['loginas_status'] == 0){
				return true;
			}

			if(is_user_logged_in()){
				$user = wp_get_current_user();
				if(isset($options['loginas_role']) && !empty($options['loginas_role'])){
					$in_role = false;
                    foreach($options['loginas_role'] as $name){
                        $name = str_replace(' ','_',$name);
                        if(in_array(strtolower($name), $user->roles)){
                            $in_role = true;
                        }
                    }
					if(!$in_role){
						return true;
					}
				}

			}

			return false;
		}


		public function custom_shop_order_column($columns){
			$options = get_option( 'loginas_options' );
			$reordered_columns = array();
			foreach( $columns as $key => $column){
				$reordered_columns[$key] = $column;
				if( $key ==  'order_status' ){
					if(!$this->UserConditional($options)){

						$reordered_columns['Login-as'] = __( 'Login As','login-as-customer-or-user');
					}
				}
			}
			return $reordered_columns;
		}

		public function custom_orders_list_column_content( $column, $post_id ){
			$options = get_option( 'loginas_options' );
			if($this->UserConditional($options)){
				return;
			}
			switch ( $column )
			{
				case 'Login-as' :
					$order = wc_get_order($post_id);
					$user_id = $order->get_user_id();
					if($user_id == get_current_user_id()){
						return _e('Current user','login-as-customer-or-user');
					}
                    $user_info = get_userdata($user_id);
					if(!empty($user_info)){
                    $user_roles=$user_info->roles;
					
					
					
						if(in_array('administrator', $user_roles)){
							return __('Administrator user','login-as-customer-or-user');
						}
					
					
						if(in_array($post_id, $this->order_lasts_id) && (!isset($_GET["paged"]) || $_GET["paged"] == 1)){

						?>
							<a href="#" class="page-title-action btn-click-login-as none_set "
							data-user="<?php esc_attr_e($user_id);?>"
							data-admin="<?php esc_attr_e(get_current_user_id());?>"><?php _e( 'Login as this user','login-as-customer-or-user');?></a>
						<?php
						}else{

							?>
							<a href="https://www.wp-buy.com/product/login-as-customer-or-user-pro" title="<?php _e( 'To unlock this limit and get more features, Please upgrade to the premium version','login-as-customer-or-user');?>" target="_blank" ><b style="color:#ef860e"><?php _e( 'Unlock Feature','login-as-customer-or-user');?></b></a>
							<?php

						}
					}else{
						_e('Visitor','login-as-customer-or-user');
					}

					break;
			}
		}
		public function my_action() {
			$options = get_option( 'loginas_options' );
			if($this->UserConditional($options)){
				return;
				wp_die();
			}


			$user_id = intval( $_POST['user_id'] );
			$admin_id = intval( $_POST['admin_id'] );
			update_user_meta(get_current_user_id(),'wploginas_user_ip',$this->get_the_user_ip());
			update_user_meta(get_current_user_id(),'login_in_user',$user_id);
			$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
			setcookie('wploginas_new_user_id', $user_id, time()+31556926, '/', $domain, false);
			setcookie('loginas_old_user_id', $admin_id, time()+31556926, '/', $domain, false);
			$_SESSION["wploginas_new_user_id"] = $user_id;	$_SESSION["loginas_old_user_id"] = $admin_id;
			wp_die();
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
		public function my_action_javascript(){
		$login_as_back_to="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			?>
			<script type="text/javascript" >
			jQuery(document).ready(function($) {
				$( ".btn-click-login-as" ).on( "click", function(event) {
					localStorage.setItem('login_as_back_to', '<?php echo esc_js($login_as_back_to);?>');
					event.preventDefault();
					var user_id = $(this).data("user");
					var admin_id = $(this).data("admin");
					var data = {
						'action': 'my_action_loginas',
						'user_id': user_id,
						'admin_id': admin_id
					};

					// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
					jQuery.post(ajaxurl, data, function(response) {
						window.location.replace("<?php echo esc_js(get_home_url());?>");
					});
				});



			});
			</script> <?php
		}


	}
	$loginas_order_page = new loginas_order_page();
}
