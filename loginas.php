<?php
/*
 * Plugin Name: Login As Customer or User
 * Description: Login as User or Customer is very helpful for admins or customer support users to access any user account in one click.
 * Version: 3.8
 * Author: wp-buy
 * Text Domain: login-as-customer-or-user
 * Domain Path: /languages/
 * Author URI: https://wp-buy.com
 * License: GPL2
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'loginas_prefix', "loginas" );
define( 'loginas_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'loginas_PLUGIN_URL', plugin_dir_url(__FILE__) );


function loginas_deactivate() {
	if ( is_plugin_active( 'login-as-customer-or-user-pro/loginasPro.php' ) )
	{
		deactivate_plugins('login-as-customer-or-user-pro/loginasPro.php');
	}
}
register_activation_hook(__FILE__, 'loginas_deactivate');

function loginas_install() {
	$def_data = array();
	$def_data['loginas_status'] = 1;
	add_option( 'loginas_options', $def_data, '', 'yes' );
}
register_activation_hook( __FILE__, 'loginas_install' );


// load translation file
add_action( 'init', 'loginasfree_load_textdomain' );
function loginasfree_load_textdomain() {
  load_plugin_textdomain( 'login-as-customer-or-user', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}



require_once( loginas_PLUGIN_DIR . '/admin/setting.php' );

require_once( loginas_PLUGIN_DIR . '/template.php' );
require_once( loginas_PLUGIN_DIR . '/admin/order-page.php' );
require_once( loginas_PLUGIN_DIR . '/admin/users.php' );

//require_once( loginas_PLUGIN_DIR . '/notification.php' );


function loginasfree_row_meta( $meta_fields, $file ) {

      if ( strpos($file,'loginas.php') == false) {

        return $meta_fields;
      }

      echo "<style>.pluginrows-rate-stars { display: inline-block; color: #ffb900; position: relative; top: 3px; }.pluginrows-rate-stars svg{ fill:#ffb900; } .pluginrows-rate-stars svg:hover{ fill:#ffb900 } .pluginrows-rate-stars svg:hover ~ svg{ fill:none; } </style>";

      $plugin_rate   = "https://wordpress.org/support/plugin/login-as-customer-or-user/reviews/?rate=5#new-post";
      $plugin_filter = "https://wordpress.org/support/plugin/login-as-customer-or-user/reviews/?filter=5";
      $svg_xmlns     = "https://www.w3.org/2000/svg";
      $svg_icon      = '';

      for ( $i = 0; $i < 5; $i++ ) {
        $svg_icon .= "<svg xmlns='" . esc_url( $svg_xmlns ) . "' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>";
      }

      $meta_fields[] = '<a href="' . esc_url( $plugin_filter ) . '" target="_blank"><span class="dashicons dashicons-thumbs-up"></span>' . __( 'Vote!', 'pluginrows' ) . '</a>';
      $meta_fields[] = "<a href='" . esc_url( $plugin_rate ) . "' target='_blank' title='" . esc_html__( 'Rate', 'pluginrows' ) . "'><i class='pluginrows-rate-stars'>" . $svg_icon . "</i></a>";

      return $meta_fields;
    }
	
	
function loginasfree_filter_action_links( $links ) { 
	$links['settings'] = sprintf('<a href="%s">%s</a>', admin_url( 'admin.php?page=loginas' ), __( 'Settings', 'login-as-customer-or-user' )); 
	return $links;
}
add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'loginasfree_filter_action_links', 10, 1 );
add_filter( 'plugin_row_meta', 'loginasfree_row_meta', 10, 4 );