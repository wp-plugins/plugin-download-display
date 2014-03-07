<?php
/**
 * Plugin Name: Plugin Download Display
 * Plugin URI: http://www.tailoredinternetmarketing.com/blog/new-twitter-button/
 * Description: A plugin that allows you to display the number of times a plugin has been downloaded. Can either be displayed in posts with a shortcode or in templates using a function.
 * Version: 1.0
 * Author: Dan Taylor
 * Author URI: http://www.tailoredinternetmarketing.com/
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
 if (!class_exists("drt_pds_plugin")) {
    class drt_pds_plugin {
		
		public function __construct() {
			
			add_shortcode( 'show_downloads', array($this, 'downloads_shortcode'));
			
		}
		
		function downloads_shortcode( $atts ) {
			 extract( shortcode_atts( array(
				  'slug' => ''
			 ), $atts ) );
			 return $this->get_plugin_downloads($slug);
		}
			
		public function plugins_api($action, $args = null) {
 
			if ( is_array($args) )
				$args = (object)$args;
		 
			if ( !isset($args->per_page) )
				$args->per_page = 24;
		 
			// Allows a plugin to override the WordPress.org API entirely.
			// Use the filter 'plugins_api_result' to merely add results.
			// Please ensure that a object is returned from the following filters.
			$args = apply_filters('plugins_api_args', $args, $action);
			$res = apply_filters('plugins_api', false, $action, $args);
		 
			if ( false === $res ) {
				$url = 'http://api.wordpress.org/plugins/info/1.0/';
				if ( wp_http_supports( array( 'ssl' ) ) )
					$url = set_url_scheme( $url, 'https' );
		 
				$request = wp_remote_post( $url, array(
					'timeout' => 15,
					'body' => array(
						'action' => $action,
						'request' => serialize( $args )
					)
				) );
		 
				if ( is_wp_error($request) ) {
					$res = new WP_Error('plugins_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="http://wordpress.org/support/">support forums</a>.' ), $request->get_error_message() );
				} else {
					$res = maybe_unserialize( wp_remote_retrieve_body( $request ) );
					if ( ! is_object( $res ) && ! is_array( $res ) )
						$res = new WP_Error('plugins_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="http://wordpress.org/support/">support forums</a>.' ), wp_remote_retrieve_body( $request ) );
				}
			} elseif ( !is_wp_error($res) ) {
				$res->external = true;
			}
		 
			return apply_filters('plugins_api_result', $res, $action, $args);
		}
		
		public function get_plugin_downloads($slug) {
			if ( false === ( $call_api = get_transient( $slug ) ) ) {
			  $call_api =  plugins_api( 'plugin_information', array( 'slug' => $slug ) );
			  set_transient( $slug, $call_api, 7 * DAY_IN_SECONDS );
			}
		 
			if ( is_wp_error( $call_api ) ) {
		 
				return '<pre>' . print_r( $call_api->get_error_message(), true ) . '</pre>';
		 
			} else {
		  
				if ( ! empty( $call_api->downloaded ) ) {
		 
					return print_r( $call_api->downloaded, true );
		 
				}
		 
			}
		}
		
	}
 }
if (class_exists("drt_pds_plugin")) {
	$drt_pds_plugin = new drt_pds_plugin();
}
if(!function_exists('plugin_downloads')) {
	function plugin_downloads($slug) {
		echo $drt_pds_plugin->get_plugin_downloads($slug);
	}
}