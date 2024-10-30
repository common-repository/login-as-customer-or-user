<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if( ! class_exists( 'loginas_user_page' ) ) {
	class loginas_user_page{

		public function __construct() {
			add_filter( 'manage_users_columns', array($this,'new_modify_user_table') );
			add_filter( 'manage_users_custom_column', array($this,'new_modify_user_table_row'), 10, 3 );
			add_filter( 'admin_head', array($this,'admin_head_css'), 10, 3 );
		}
		public function UserLimit($options = array())
        {

            if (empty($options)) {
                return true;
            }
            if (!isset($options['loginas_status']) || $options['loginas_status'] == 0) {
                return true;
            }
            $users = get_users( array(
                'fields' => 'IDs',
                'orderby' => 'rand',
                'number'  => 20
            ));

            return $users;

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
		public function new_modify_user_table( $column ) {
			$options = get_option( 'loginas_options');
			if(!$this->UserConditional($options)){
				$column['loginas'] = __('Login As','login-as-customer-or-user');
			}

			return $column;
		}

		public function new_modify_user_table_row( $val, $column_name, $user_id ) {
			switch ($column_name) {
				case 'loginas' :
					$options = get_option( 'loginas_options' ,array());
					if(!$this->UserConditional($options) && in_array($user_id, $this->UserLimit($options))){
						$user_info = get_userdata($user_id);
						if($user_id == get_current_user_id()){
							return __('Current user','login-as-customer-or-user');
						}
                        $user_meta=get_userdata($user_id);
                        $user_roles=$user_meta->roles;
						
						if(!empty($user_info) && !empty($user_roles))
						{
											
							if(in_array('administrator', $user_roles)){
								return __('Administrator user','login-as-customer-or-user');
							}
						}
						$links = sprintf('<a href="#" class="page-title-action btn-click-login-as login_as_btn" data-user="%d" data-admin="%d">%s</a>', absint($user_id),absint(get_current_user_id()), __( 'Login as this user', 'login-as-customer-or-user' ));

						return $links;
					}else{

						$links = '<a href="https://www.wp-buy.com/product/login-as-customer-or-user-pro" title="'.__( "To unlock the limit and get more features, Please upgrade to the Premium version","login-as-customer-or-user").' target="_blank">'.__( "Upgrade to Pro","login-as-customer-or-user").'</a>';

						return $links;
                    }
					break;
			}
			return $val;
		}
		public function admin_head_css(  ) {
			?>
			<style>
				.login_as_btn {
					top: 3px !important;
				}
			</style>
			<?php
		}

	}
	new loginas_user_page();
}