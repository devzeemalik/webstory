<?php
/**
 * Plugin Name: Zts Minhaj
 * Plugin URI: https://zubitechsol.com/
 * Description: This plugin create Google Webstories in bulk.
 * Version: 1.0.0
 * Author: Zeeshan Malik
 * Author URI: https://zubitechsol.com/
 * Text Domain: zts_web_story
 * License: GPL v3
 *

/**
 * This is the main class of Hellowoofy Webstories.
 *
 * @package Max_web_story
 */
class Max_Web_Story {

	/**
	 * HelloWoofy Main Class contructor.
	 *
	 * @since    1.0.1
	 */
	public function __construct() {

		/* enque public styles and scripts */
		add_action( 'wp_enqueue_scripts', array( $this, 'mws_public_enque_scripts' ) );

		/* enque admin styles and scripts */
		add_action( 'admin_enqueue_scripts', array( $this, 'mws_admin_enque_scripts' ) );

		/* Create custom table on plugin activation */
		register_activation_hook( __FILE__, array( $this, 'activate_max_web_story' ) );

		/* Load all files */
		$this->max_load_admin_files();

		/* Load public files */
		$this->max_load_public_files();

		/* custom endpoint */
		add_action( 'rest_api_init', array( $this, 'mws_api_callback' ) );

	}

	/**
	 * Public enque scripts.
	 *
	 * @since    1.0.1
	 */
	public function mws_public_enque_scripts() {
		wp_register_style( 'mws_story_player_css', 'https://cdn.ampproject.org/amp-story-player-v0.css', array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_story_player_css' );

		wp_register_style( 'mws_pubilc_main', plugins_url( 'public/assets/css/main.css', __FILE__ ), array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_pubilc_main' );

		wp_register_style( 'mws_pubilc_header_css', plugins_url( 'public/assets/css/header.css', __FILE__ ), array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_pubilc_header_css' );

		wp_register_style( 'mws_pubilc_hero_css', plugins_url( 'public/assets/css/hero.css', __FILE__ ), array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_pubilc_hero_css' );

		wp_register_style( 'mws_pubilc_card_css', plugins_url( 'public/assets/css/cards.css', __FILE__ ), array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_pubilc_card_css' );

		wp_register_style( 'mws_pubilc_carousel_css', plugins_url( 'public/assets/css/carousel.css', __FILE__ ), array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_pubilc_carousel_css' );

		wp_register_style( 'mws_googleapis_css', 'https://fonts.googleapis.com/css2?family=Poppins&amp;display=swap', array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_googleapis_css' );

		wp_register_style( 'mws_bs_min_css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css', array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_bs_min_css' );

		wp_register_script( 'mws_story_player_js', 'https://cdn.ampproject.org/amp-story-player-v0.js', array( 'jquery' ), '1.1', true );
		wp_enqueue_script( 'mws_story_player_js' );

		wp_register_script( 'mws_publc_main_js', plugins_url( 'public/assets/js/main.js', __FILE__ ), array( 'jquery' ), '1.1', true );
		wp_enqueue_script( 'mws_publc_main_js' );

		wp_register_script( 'mws_slim_js', 'https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js', array( 'jquery' ), '1.1', true );
		wp_enqueue_script( 'mws_slim_js' );

		wp_register_script( 'mws_popper_js', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js', array( 'jquery' ), '1.1', true );
		wp_enqueue_script( 'mws_popper_js' );

		wp_register_script( 'mws_bundel_min_js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js', array( 'jquery' ), '1.1', true );
		wp_enqueue_script( 'mws_bundel_min_js' );

	}


	/**
	 * Admin enque scripts.
	 *
	 * @since    1.0.1
	 */
	public function mws_admin_enque_scripts() {
		// Call predefined enqueue media method.
		wp_enqueue_media();
		wp_register_style( 'mws_select2_css', plugins_url( 'admin/assets/select2/select2.min.css', __FILE__ ), array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_select2_css' );
		wp_register_style( 'mws_admin_player_css', 'https://cdn.ampproject.org/amp-story-player-v0.css', array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_admin_player_css' );

		wp_register_style( 'mws_admin_header_css', plugins_url( 'public/assets/css/header.css', __FILE__ ), array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_admin_header_css' );

		wp_register_style( 'mws_admin_hero_css', plugins_url( 'public/assets/css/hero.css', __FILE__ ), array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_admin_hero_css' );

		wp_register_style( 'mws_admin_card_css', plugins_url( 'public/assets/css/cards.css', __FILE__ ), array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_admin_card_css' );

		wp_register_style( 'mws_admin_carousel_css', plugins_url( 'public/assets/css/carousel.css', __FILE__ ), array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_admin_carousel_css' );

		wp_register_style( 'mws_admin_google_apis', 'https://fonts.googleapis.com/css2?family=Poppins&amp;display=swap', array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_admin_google_apis' );
		wp_register_style( 'mws_admin_boostrap_min_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), '0.1.0', 'all' );
		wp_enqueue_style( 'mws_admin_boostrap_min_css' );

		wp_register_script( 'mws_select2_js', plugins_url( 'admin/assets/select2/select2.min.js', __FILE__ ), array( 'jquery' ), '1.1', true );
		wp_enqueue_script( 'mws_select2_js' );
		/* custom jqquery */
		wp_register_script( 'mws_custom_admin_js', plugins_url( 'admin/assets/admin.js', __FILE__ ), array( 'jquery' ), '1.1', true );
		wp_enqueue_script( 'mws_custom_admin_js' );
		wp_register_script( 'mws_admin_bundle_min_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array( 'jquery' ), '1.1', true );
		wp_enqueue_script( 'mws_admin_bundle_min_js' );

		wp_register_script( 'mws_admin_main_js', plugins_url( 'public/assets/js/main.js', __FILE__ ), array( 'jquery' ), '1.1', true );
		wp_enqueue_script( 'mws_admin_main_js' );

	}

	/**
	 * Create custom table to store request data in case of failure in custom endpoints.
	 *
	 * @since    1.0.1
	 */
	public function activate_max_web_story() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$table_name = $wpdb->prefix . 'max_fail_requests';
		$sql = "CREATE TABLE $table_name (
        id INTEGER NOT NULL AUTO_INCREMENT,
        webhook VARCHAR(255) NOT NULL,
        request_data text,
        timestam VARCHAR(255),
         PRIMARY KEY (id)
         ) $charset_collate;";
		dbDelta( $sql );

	}

	/**
	 * Inlude the file that containse all custom endpoint.
	 *
	 * @since    1.0.1
	 */
	public function mws_api_callback() {

		require_once plugin_dir_path( __FILE__ ) . 'endpoints/class-mws-custom-endpoints.php';

	}

	/**
	 * Load admin files.
	 *
	 * @since    1.0.1
	 */
	public function max_load_admin_files() {
		/* Register custom post types */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-register-post-type.php';
		/* Admin menu for Woofly Api */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-admin-menu.php';
		/* Custom template to show Google web Story */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-custom-post-template.php';
	}

	/**
	 * Load public files.
	 *
	 * @since    1.0.1
	 */
	public function max_load_public_files() {

		require_once plugin_dir_path( __FILE__ ) . 'public/class-display-slider-on-front-end.php';

	}
}
if ( class_exists( 'Max_Web_Story' ) ) {
	$max_enque = new Max_Web_Story();
}


































