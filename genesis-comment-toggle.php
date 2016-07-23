<?php
/**
 * Plugin Name: Genesis Comments Toggle
 * Plugin URI: http://www.christophherr.com
 * Description: This plugin adds a button above the comments section that changes whether or not comments are visible.
 * Author:      Christoph Herr
 * Author URI:	http://www.christophherr.com
 * Version:     0.5.0
 * Text Domain: genesis-comments-toggle
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package   GenesisCommentsToggle
 * @author    Christoph Herr
 * @version   0.5.0
 * @license   GPL-2.0+
 *
 * Genesis Comments Toggle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Genesis Comments Toggle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Genesis Comments Toggle. If not, see <http://www.gnu.org/licenses/>.
 */

// Prevent direct access to the plugin.
if ( ! defined( 'ABSPATH' ) ) {
	wp_die( _e( 'Sorry, you are not allowed to access this page directly.', 'gct' , 'genesis-comments-toggle') );
}

$gct_version = "0.5.0";

/**
 * This function runs on plugin activation. It checks to make sure the
 * Genesis Framework is active. If not, it deactivates the plugin.
 *
 * @since 0.5.0
 */
function gct_activation() {
	if ( ! function_exists( 'genesis' ) ) {
		// Deactivate.
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'gct_admin_notice_message' );
	}
}

register_activation_hook( __FILE__, 'gct_activation' );

/**
 * This function is triggered when the WordPress theme is changed.
 * It checks if the Genesis Framework is active. If not, it deactivates the plugin.
 *
 * @since 0.5.0
 */
function gct_plugin_deactivate() {
	if ( ! function_exists( 'genesis' ) ) {
		// Deactivate.
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'gct_admin_notice_message' );
	}
}

add_action( 'admin_init', 'gct_plugin_deactivate' );
add_action( 'switch_theme', 'gct_plugin_deactivate' );

/**
 * Error message if you're not using the Genesis Framework.
 *
 * @since 0.5.0
 */
function gct_admin_notice_message() {
	$error = sprintf( _e( 'Sorry, you can\'t use the Genesis Comments Toggle Plugin unless the <a href="%s">Geneis Framework</a> is active. The plugin has been deactivated.', 'gcfws' , 'genesis-connect-for-woothemes-sensei'), 'http://www.studiopress.com' );

	echo '<div class="error"><p>' . $error . '</p></div>';

	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}

/**
 * Load plugin textdomain.
 *
 * @since 0.5.0
 */
function gct_load_textdomain() {
	load_plugin_textdomain( 'genesis-comments-toggle', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'gct_load_textdomain' );

/**
 * Register and Enqueues Styles and Scripts.
 *
 * @since   0.5.0
 */
function gct_admin_enqueue_scripts() {
  global $gct_version;

	wp_register_script( 'gct-js', plugins_url( 'js/gct.js', __FILE__ ), array( 'jquery' ), $gct_version );
	wp_enqueue_script( 'gct-js' );
	wp_enqueue_style( 'gct-css', plugins_url( 'css/gct.css', __FILE__ ), array(), $gct_version, 'all' );
}

add_action( 'wp_enqueue_scripts', 'gct_admin_enqueue_scripts' );

/**
 * Add a Show comments button.
 *
 * @since   0.5.0
 */
function gct_add_button_before_comments() {
	if ( ! is_single() )
	return;

	echo '<button id="show-comments" class="show-comments">Show Comments</button>';
}

add_action('genesis_before_comments', 'gct_add_button_before_comments');
