<?php
/**
 * Display slider on front end.
 *
 * PHP version 7
 *
 * @package  Register_Post_Type
 */

/**
 * Display slider on front end.
 *
 * Template Class
 *
 * @package  Register_Post_Type
 */
class Register_Post_Type {

	/** Create the custom post types */
	public function __construct() {

		/* Register custom post types */
		add_action( 'init', array( $this, 'max_custom_post_type' ) );

		/* Bottom two hooks add custom column for  feature img in custom post types */
		add_action( 'manage_webstories_posts_columns', array( $this, 'max_custom_post_type_manage_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'max_custom_post_type_custom_columns' ), 10, 2 );

	}

	/**
	 * Register custom post type.
	 *
	 * @since    1.0.1
	 */
	public function max_custom_post_type() {

		// Set UI labels for Custom Post Type.

		$labels = array(

			'name'                => _x( 'WebStories', 'Post Type General Name' ),

			'singular_name'       => _x( 'WebStory', 'Post Type Singular Name' ),

			'menu_name'           => __( 'HelloWoofy WebStories' ),

			'parent_item_colon'   => __( 'Parent WebStory' ),

			'all_items'           => __( 'All WebStories' ),

			'view_item'           => __( 'View WebStory' ),

			'add_new_item'        => __( 'Add New WebStory' ),

			'add_new'             => __( 'Add New' ),

			'edit_item'           => __( 'Edit WebStory' ),

			'update_item'         => __( 'Update WebStory' ),

			'search_items'        => __( 'Search WebStory' ),

			'not_found'           => __( 'Not Found' ),

			'not_found_in_trash'  => __( 'Not found in Trash' ),

		);

		// Set other options for Custom Post Type.

		$args = array(

			'label'               => __( 'webstories', 'storefront' ),

			'description'         => __( 'HelloWoofy Web Stories' ),

			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' ),
			'taxonomies'          => array( 'genres' ),
			'hierarchical'        => false,

			'public'              => true,

			'show_ui'             => true,

			'show_in_menu'        => true,

			'menu_icon'           => 'dashicons-google',

			'show_in_nav_menus'   => true,

			'show_in_admin_bar'   => true,

			'menu_position'       => 5,

			'can_export'          => true,

			'has_archive'         => true,

			'exclude_from_search' => false,

			'publicly_queryable'  => true,

			'capability_type'     => 'post',

			'capabilities'        => array( 'create_posts' => false ),

			'map_meta_cap' => true,

			'show_in_rest' => true,

		);

		// Registering your Custom Post Type.

		register_post_type( 'webstories', $args );

	}

	/**
	 * Manage custom post type columns.
	 *
	 * @param string $columns This will return columns in custom post type.
	 */
	public function max_custom_post_type_manage_columns( $columns ) {
		$new_columns = array(
			'cb' => '<input type="checkbox" />',
			'featured-image' => 'Featured img',
		);
		return array_merge( $new_columns, $columns );
	}

	/**
	 * Add custom post type columns.
	 *
	 * @param string  $column This will return column in custom post type.
	 *
	 * @param integer $post_id This will return id of post in custom post type.
	 */
	public function max_custom_post_type_custom_columns( $column, $post_id ) {
		global $post;
		switch ( $column ) {
			case 'featured-image':
				?>
				<a href='<?php echo esc_html( get_permalink( $post_id ) ); ?>'> 
					<?php echo get_the_post_thumbnail( $post->ID, array( 60, 60 ) ); ?>
				 </a> 
				<?php
				break;
		}
	}

}

new Register_Post_Type();

