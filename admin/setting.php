<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if( ! class_exists( 'loginas_admin_setting' ) ) {
	class loginas_admin_setting{
        public $before_section;
        public $before_section_right;
        public $after_section;
        public $after_section_right;
		public function __construct() {
			add_action( 'admin_menu', array($this,'loginas_options_page') );
			add_action( 'admin_init', array($this,'loginas_settings_init') );
            $this->before_section = '<div class="col-md-12 col-sm-12 loginas_And_SUC_Free_admin_card"><div class="card">';
            $this->before_section_right = '<div class="col-md-12 col-sm-12 loginas_And_SUC_Free_admin_card"><div class="card">';
            $this->after_section = '</div></div>';
            $this->after_section_right = '</div></div>';
		}
		public function loginas_settings_init() {
			register_setting( 'loginas', 'loginas_options');
            add_settings_section(
                'loginas_section_developers_status',
                __( 'Status:', 'login-as-customer-or-user' ),
                array(),
                'loginas',
                array(
                    'before_section'=>$this->before_section,
                    'after_section'=>$this->after_section,
                    'section_class'=>'section_class'
                )
            );
            add_settings_section(
                'loginas_section_developers_Buttons_Position',
                __( 'Buttons Position:', 'login-as-customer-or-user' ),
                array(),
                'loginas',
                array(
                    'before_section'=>$this->before_section,
                    'after_section'=>$this->after_section,
                    'section_class'=>'section_class'
                )
            );
            add_settings_section(
                'loginas_section_developers_Accessibility',
                __( 'Accessibility:', 'login-as-customer-or-user' ),
                array(),
                'loginas',
                array(
                    'before_section'=>$this->before_section,
                    'after_section'=>$this->after_section,
                    'section_class'=>'section_class'
                )
            );

			add_settings_field(
				'loginas_status', 
				__( 'Plugin Status:', 'login-as-customer-or-user' ),
				array($this,'loginas_field_type_checkbox'),
				'loginas',
				'loginas_section_developers_status',
				[
					'label_for' => 'loginas_status',
					'class' => 'loginas_row',
					'loginas_custom_data' => 'custom',
				]
			);


			
			add_settings_field(
				'loginas_role', 
				__( 'Buttons Accessibility:', 'login-as-customer-or-user' ),
				array($this,'loginas_field_type_roles'),
				'loginas',
				'loginas_section_developers_Accessibility',
				[
					'label_for' => 'loginas_role',
					'class' => 'loginas_row',
					'loginas_custom_data' => 'custom',
				]
			);
            add_settings_field(
                'loginas_button_position',
                __( 'Buttons Position:', 'login-as-customer-or-user' ),
                array($this,'loginas_field_type_position'),
                'loginas',
                'loginas_section_developers_Buttons_Position',
                [
                    'label_for' => 'loginas_button_position',
                    'class' => 'loginas_row',
                    'loginas_custom_data' => 'left',
                ]
            );
			
			
		}


        public function loginas_field_type_position( $args ) {
            $options = get_option( 'loginas_options' );
            $value = isset($options[ $args['label_for']])?$options[ $args['label_for']]:$args['loginas_custom_data'];

                ?>
            <span class="wp-core-ui ">
                    <select name="loginas_options[<?php esc_attr_e( $args['label_for'] ); ?>]" style="width: 300px;">
                        <option value="top" <?php if($value=='top'){?>selected<?php }?>>Top</option>
                        <option value="left" <?php if($value=='left'){?>selected<?php }?>>Left</option>
                        <option value="right" <?php if($value=='right'){?>selected<?php }?>>Right</option>
                        <option value="bottom" <?php if($value=='bottom'){?>selected<?php }?>>Bottom</option>
                    </select>
            </span>
                <?php

        }



        public function loginas_field_type_roles( $args ) {
			$options = get_option( 'loginas_options' );
			$value = isset($options[ $args['label_for']])?$options[ $args['label_for']]:array();
			global $wp_roles;
			$wp_roles = new WP_Roles();

			foreach($wp_roles->get_names() as $name){
			 ?>
			
				<label class="containercheckbox"><?php esc_html_e($name);?>
				  <input type="checkbox" name="loginas_options[<?php esc_attr_e( $args['label_for'] ); ?>]['<?php  esc_attr_e($name);?>']" value="<?php echo esc_attr($name);?>"  <?php if(isset($value["'".$name."'"])){?> checked<?php };?>>
				  <span class="checkmark"></span>
				</label>			 
				
			 <?php
			}
		}
		
		
		public function loginas_field_type_checkbox( $args ) {
			$options = get_option( 'loginas_options' );
			
			?>
			<span class="on_off off"><?php esc_html_e( 'OFF', 'login-as-customer-or-user' ); ?></span>
			<label class="switch">
				<input type="checkbox" value="1" id="<?php echo esc_attr( $args['label_for'] ); ?>" data-custom="<?php echo esc_attr( $args['loginas_custom_data'] ); ?>" name="loginas_options[<?php echo esc_attr( $args['label_for'] ); ?>]"  <?php echo isset( $options[ $args['label_for']] ) ? ( checked( $options[ $args['label_for'] ], '1', false ) ) : ( '' ); ?>>
				<span class="slider round"></span>
			</label>
			<span class="on_off on"><?php esc_html_e( 'ON', 'login-as-customer-or-user' ); ?></span>
			<p class="description">
				<?php esc_html_e( 'Using this option, you can enable or disable the plugin functionality', 'login-as-customer-or-user' ); ?>
			</p>
			 
			<?php
		}
		 

		public function loginas_options_page() {
			 add_menu_page(
				 'loginas',
				 'Login AS',
				 'administrator',
				 'loginas',
				 array($this,'loginas_options_page_html'),
                 'dashicons-admin-network'
			 );
		}

		
		 
		public function loginas_options_page_html() {
		 
			if ( ! current_user_can( 'administrator' ) ) {
				return;
			}
		 
			if ( isset( $_GET['settings-updated'] ) ) {
				add_settings_error( 'loginas_messages', 'loginas_message', __( 'Settings Saved', 'login-as-customer-or-user' ), 'updated' );
			}

			settings_errors( 'loginas_messages' );
			
			?>
			<script>
				document.addEventListener("DOMContentLoaded", function() {
				// Select the <ul> element using its class
				const ulElement = document.querySelector('.other_plugins_rotator_ul');

				function showRandomElements() {
				  // Hide all list items
				  const listItems = ulElement.querySelectorAll('li');
				  listItems.forEach((li) => {
					li.style.display = 'none';
				  });

				  // Generate three random indices
				  const numItems = listItems.length;
				  const randomIndices = [];
				  while (randomIndices.length < 5) {
					const randomIndex = Math.floor(Math.random() * numItems);
					if (!randomIndices.includes(randomIndex)) {
					  randomIndices.push(randomIndex);
					}
				  }

				  // Show the randomly selected list items
				  randomIndices.forEach((index) => {
					listItems[index].style.display = 'block';
				  });
				}

				// Show initial random elements
				showRandomElements();

				// Set interval to change elements every 15 seconds
				setInterval(showRandomElements, 15000);
				});
			</script>
            <div class="col-lg-10 col-md-12 col-sm-12">
                <h3 class="mt-5"><?php _e( 'Login as customer or user', 'login-as-customer-or-user' );?></h3>
                <div id="">
                    <?php settings_errors( 'loginas_messages' );?>
                    <div id="dashboard-widgets" class="metabox-holder">
                        <div id="" class="">
                            <div id="side-sortables" class="meta-box-sortables ui-sortable">
                                <div id="dashboard_quick_press" >

                                    <div class="inside">
                                        <form action="options.php" method="post">


                                            <div class="input-text-wrap row" id="title-wrap">
                                                <div class="col-md-8 col-sm-12">
                                                    <?php
                                                    settings_fields( 'loginas' );
                                                    do_settings_sections( 'loginas' );
                                                    ?>
                                                </div>
												<?php
													$network_dir_append = "";
		
													If (is_multisite()) $network_dir_append = "network/";
													
													$admin_url = admin_url( $network_dir_append . 'plugin-install.php' );
												?>
                                                <div class="col-md-4 col-sm-12" >
                                                    <div class="col-md-12 col-sm-12  loginas_And_SUC_Free_admin_card" >
                                                        <div class="card">
                                                            <h2 style="background-color: hsl(275.56deg 49.69% 31.96%); color: white;">Other products</h2>
															<ul class="other_plugins_rotator_ul">
																<li>
																<div class="plugin-info-container">
																  <h3><a target="blank" href="<?php echo $admin_url; ?>?s=404 Image Redirection Replace Broken Images&tab=search&type=term">404 Image Redirection (Replace Broken Images)</a></h3>
																  <div class="wporg-ratings" title="5 out of 5 stars" style="color:#ffb900;"></div>
																  <p class="active_installs">Active Installs: 600+</p>
																</div>
																</li>
																<li>
																<div class="plugin-info-container">
																  <h3><a target="blank" href="<?php echo $admin_url; ?>?s=Advanced FAQ QA Creator by Category&tab=search&type=term">Advanced FAQ QA Creator by Category</a></h3>
																  <p class="active_installs">Active Installs: Less than 10</p>
																</div>
																</li>
																<li>
																<div class="plugin-info-container">
																  <h3><a target="blank" href="<?php echo $admin_url; ?>?s=All 404 Redirect to Homepage&tab=search&type=term">All 404 Redirect to Homepage</a></h3>
																  <div class="wporg-ratings" title="4 out of 5 stars" style="color:#ffb900;"></div>
																  <p class="active_installs">Active Installs: 200,000+</p>
																</div>
																</li>
																<li>
																<div class="plugin-info-container">
																  <h3><a target="blank" href="<?php echo $admin_url; ?>?s=Captchinoo, admin login page protection with Google recaptcha&tab=search&type=term">Captchinoo, admin login page protection with Google recaptcha</a></h3>
																  <div class="wporg-ratings" title="5 out of 5 stars" style="color:#ffb900;"></div>
																  <p class="active_installs">Active Installs: 300+</p>
																</div>
																</li>
																<li>
																<div class="plugin-info-container">
																  <h3><a target="blank" href="<?php echo $admin_url; ?>?s=Easy Popup Maker&tab=search&type=term">Easy Popup Maker</a></h3>
																  <p class="active_installs">Active Installs: 10+</p>
																</div>
																</li>
																<li>
																<div class="plugin-info-container">
																  <h3><a target="blank" href="<?php echo $admin_url; ?>?s=Limit Login Attempts Spam Protection&tab=search&type=term">Limit Login Attempts (Spam Protection)</a></h3>
																  <div class="wporg-ratings" title="3 out of 5 stars" style="color:#ffb900;"></div>
																  <p class="active_installs">Active Installs: 200+</p>
																</div>
																</li>
																<li>
																<div class="plugin-info-container">
																  <h3><a target="blank" href="<?php echo $admin_url; ?>?s=Login as User or Customer&tab=search&type=term">Login as User or Customer</a></h3>
																  <div class="wporg-ratings" title="3 out of 5 stars" style="color:#ffb900;"></div>
																  <p class="active_installs">Active Installs: 400+</p>
																</div>
																</li>
																<li>
																<div class="plugin-info-container">
																  <h3><a target="blank" href="<?php echo $admin_url; ?>?s=SEO Redirection Plugin - 301 Redirect Manager&tab=search&type=term">SEO Redirection Plugin - 301 Redirect Manager</a></h3>
																  <div class="wporg-ratings" title="4 out of 5 stars" style="color:#ffb900;"></div>
																  <p class="active_installs">Active Installs: 20,000+</p>
																</div>
																</li>
																<li>
																<div class="plugin-info-container">
																  <h3><a target="blank" href="<?php echo $admin_url; ?>?s=Visitor Traffic Real Time Statistics&tab=search&type=term">Visitor Traffic Real Time Statistics</a></h3>
																  <div class="wporg-ratings" title="4 out of 5 stars" style="color:#ffb900;"></div>
																  <p class="active_installs">Active Installs: 50,000+</p>
																</div>
																</li>
																<li>
																<div class="plugin-info-container">
																  <h3><a target="blank" href="<?php echo $admin_url; ?>?s=WooCommerce Email Marketing Cart Abandonment Recovery&tab=search&type=term">WooCommerce Email Marketing & Cart Abandonment Recovery</a></h3>
																  <p class="active_installs">Active Installs: 10+</p>
																</div>
																</li>
																<li>
																<div class="plugin-info-container">
																  <h3><a target="blank" href="<?php echo $admin_url; ?>?s=WP Category Post List wp-buy&tab=search&type=term">WP Category Post List</a></h3>
																  <div class="wporg-ratings" title="5 out of 5 stars" style="color:#ffb900;"></div>
																  <p class="active_installs">Active Installs: 900+</p>
																</div>
																</li>
																<li>
																<div class="plugin-info-container">
																  <h3><a target="blank" href="<?php echo $admin_url; ?>?s=WP Content Copy Protection No Right Click&tab=search&type=term">WP Content Copy Protection & No Right Click</a></h3>
																  <div class="wporg-ratings" title="4 out of 5 stars" style="color:#ffb900;"></div>
																  <p class="active_installs">Active Installs: 100,000+</p>
																</div>
																</li>
																<li>
																<div class="plugin-info-container">
																  <h3><a target="blank" href="<?php echo $admin_url; ?>?s=WP Maintenance Mode Site Under Construction&tab=search&type=term">WP Maintenance Mode & Site Under Construction</a></h3>
																  <div class="wporg-ratings" title="4 out of 5 stars" style="color:#ffb900;"></div>
																  <p class="active_installs">Active Installs: 1,000+</p>
																</div>
																</li>
															</ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="submit">
                                                <?php
                                                submit_button( 'Save Settings' );
                                                ?>
                                                <br class="clear">
                                            </p>

                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>
                </div>
            </div>






            <?php
		}
		
	}
	
	
	new loginas_admin_setting();
}

function loginas_hkdc_admin_styles($page) {
	if(isset($_GET['page']) && $_GET['page'] == 'loginas'){
		wp_enqueue_style( 'admin-css' , loginas_PLUGIN_URL.'/assets/css/admin-css.css');
        wp_enqueue_style( 'bootstrap' , loginas_PLUGIN_URL.'/assets/css/bootstrap.min.css');
	}
}
add_action('admin_print_styles', 'loginas_hkdc_admin_styles');



