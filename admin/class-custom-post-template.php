<?php
/**
 * Custom Post Template.
 *
 * PHP version 7
 *
 * @package  Custom_Post_Template
 */

/**
 * Custom Post Template.
 *
 * Template Class
 *
 * @package  Custom_Post_Template
 */
class Custom_Post_Template {
	/** Constructor or curretn class */
	public function __construct() {
		add_filter( 'single_template', array( 'Custom_Post_Template', 'max_single_story_template' ) );
	}
	/**
	 * Override Post Template For Custom Post Type.
	 *
	 * @param string $template This will return the post template.
	 */
	public static function max_single_story_template( $template ) {
		global $post;
		if ( 'webstories' === $post->post_type && locate_template( array( 'class-custom-post-content.php' ) ) !== $template ) {
			return plugin_dir_path( __FILE__ ) . 'class-custom-post-content.php';
		}
		return $template;
	}
}
new Custom_Post_Template();
