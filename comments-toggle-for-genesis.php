<?php
/**
 * Plugin Name: Comments Toggle for Genesis
 * Plugin URI: https://www.christophherr.com
 * Description: This plugin adds a button above the comments section that toggles whether or not comments are visible.
 * Author:      Christoph Herr
 * Author URI:	https://www.christophherr.com
 * Version:     0.5.0
 * Text Domain: comments-toggle-for-genesis
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package   CommentsToggleForGenesis
 * @author    Christoph Herr
 * @version   0.5.0
 * @license   GPL-2.0+
 *
 * Comments Toggle for Genesis is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Comments Toggle for Genesis is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Comments Toggle for Genesis. If not, see <http://www.gnu.org/licenses/>.
 */

// Prevent direct access to the plugin.
if ( ! defined( 'ABSPATH' ) ) {
	wp_die( _e( 'Sorry, you are not allowed to access this page directly.', 'ctfg' , 'comments-toggle-for-genesis') );
}

$ctfg_version = '0.5.0';

/**
 * This function runs on plugin activation. It checks to make sure the
 * Genesis Framework is active. If not, it deactivates the plugin.
 *
 * @since 0.5.0
 */
function ctfg_activation() {
	if ( ! function_exists( 'genesis' ) ) {
		// Deactivate.
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'ctfg_admin_notice_message' );
	}
}

register_activation_hook( __FILE__, 'ctfg_activation' );

/**
 * This function is triggered when the WordPress theme is changed.
 * It checks if the Genesis Framework is active. If not, it deactivates the plugin.
 *
 * @since 0.5.0
 */
function ctfg_plugin_deactivate() {
	if ( ! function_exists( 'genesis' ) ) {
		// Deactivate.
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'ctfg_admin_notice_message' );
	}
}

add_action( 'admin_init', 'ctfg_plugin_deactivate' );
add_action( 'switch_theme', 'ctfg_plugin_deactivate' );

/**
 * Error message if you're not using the Genesis Framework.
 *
 * @since 0.5.0
 */
function ctfg_admin_notice_message() {
	$error = sprintf( _e( 'Sorry, you can\'t use the Comments Toggle for Genesis Plugin unless the <a href="%s">Geneis Framework</a> is active. The plugin has been deactivated.', 'gcfws' , 'genesis-connect-for-woothemes-sensei'), 'http://www.studiopress.com' );

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
function ctfg_load_textdomain() {
	load_plugin_textdomain( 'comments-toggle-for-genesis', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'ctfg_load_textdomain' );

/**
 * Register and Enqueues Styles and Scripts.
 *
 * @since   0.5.0
 */
function ctfg_admin_enqueue_scripts() {
	global $ctfg_version;

	wp_enqueue_script( 'ctfg-js', plugins_url( 'js/ctfg.js', __FILE__ ), array( 'jquery' ), $ctfg_version );
	wp_enqueue_style( 'ctfg-css', plugins_url( 'css/ctfg.css', __FILE__ ), array(), $ctfg_version, 'all' );
}

add_action( 'wp_enqueue_scripts', 'ctfg_admin_enqueue_scripts' );

/**
 * Add aria-expanded attribute to entry-comments
 *
 * @param array $attributes Extra attributes to merge with defaults.
 * @return string Amended HTML attributes and values.
 *
 * @since 0.5.0
 */
function ctfg_add_aria_expanded( $attributes ) {
	$attributes['aria-expanded'] = 'false';

	return $attributes;
}

add_filter( 'genesis_attr_entry-comments', 'ctfg_add_aria_expanded' );

/**
 * Add a Show comments button.
 *
 * @since   0.5.0
 */
function ctfg_add_button_before_comments() {
	if ( ! is_single() ) {
		return;
	}
	echo '<button id="show-comments" class="show-comments" aria-pressed="false" role="button" tabindex="0">Show Comments</button>';
}

add_action( 'genesis_before_comments', 'ctfg_add_button_before_comments' );
