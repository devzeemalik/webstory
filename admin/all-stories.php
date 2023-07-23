<?php
/**
 * Listing of All WebStories.
 *
 * Template Class
 *
 * @package  all-stories
 */

$attachment_id = get_option( 'default_webstory_icon' );
global $wpdb;
$img = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}posts WHERE ID = %d", $attachment_id ) );
if ( isset( $_POST['mwc_get_all_stories_field'] ) &&
		wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mwc_get_all_stories_field'] ) ), 'mwc_get_all_stories_action' ) ) {
	if ( isset( $_POST['max_admin_search'] ) ) {
		$search_parameter  = sanitize_text_field( wp_unslash( $_POST['max_admin_search'] ) );
		$get_posts = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = %s && post_status = %s && post_title like %s ORDER BY ID DESC", 'webstories', 'publish', '%' . $search_parameter . '%' ) );
		$count = count( $get_posts );
	}
} else {
		$get_posts  = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'webstories' && post_status = 'publish' ORDER BY ID DESC" );
		$count = count( $get_posts );
}

if ( empty( $img ) ) {
	$img = $img_bg_url;
} else {
	$img = $img->guid;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
</head>
<body>
<div class="container mt-4">
  <div class="row">
	<div class="col-md-3 "> 
		  <div class="row">
			<div class="col-md-3 ">
			  <img src="<?php echo esc_attr( $img ); ?>" style="width:100%;">
			</div>
			<div class="col-md-9 pt-4">
			   <span style="color: #00bdff;font-size: 30px; font-weight: 700; margin-left: -18px;">HelloWoofy</span>
			</div>
		 </div>
	</div>
	<div class="col-md-9 " style="padding-top: 30px;"> 
		<div class="row  ">
		  <div class="col-md-6  ">
			<h2 >All Web Stories</h2>
		  </div>
		  <div class="col-md-6 " >
			<div class="text-right;">
			  <form method="post">
				<?php wp_nonce_field( 'mwc_get_all_stories_action', 'mwc_get_all_stories_field' ); ?>
				<input type="search" class="max_admin_search" name="max_admin_search">
				<input type="submit" name="btn_admin_search"  value="Search" style="background-color: #00bdff;
				border: 1px solid #00bdff; border: 1px solid #8ed1fc; padding: 4px 12px; border-radius: 5px; color: #ffff;">
			  </form>
			</div>
		  </div>
		  <div class="col-md-12">
			<hr> 
			<h6>Viewing all <?php echo esc_html( $count ); ?> webstories</h6>
		  </div>
		</div>  
		<div class="row">
				  <?php
					foreach ( $get_posts as $key => $get_post ) {
						$get_post_id        = $get_post->ID;
						$get_title          = $get_post->post_title;
						$description    = $get_post->post_content;
						$permalink      = $get_post->guid;
						$story_meta     = get_post_meta( $get_post_id, 'story_meta', true );
						$slider_logo    = $story_meta['publisher-logo-src'];
						$slider_potrait = $story_meta['poster-portrait-src'];
						?>
					<div class="col-sm-3 col-6  p-4 ">
					  <a href="<?php echo esc_html( $permalink ); ?>">
						  <div class="entry-point-card-container max_width " >
							  <img src="<?php echo esc_attr( $slider_potrait ); ?>" class="entry-point-card-img" alt="A cat">
							  <div class="author-container">
								<div class="logo-container">
								  <div class="logo-ring"></div>
								  <img class="entry-point-card-logo" src="<?php echo esc_attr( $slider_logo ); ?>" alt="Publisher logo">
								</div>
								<span class="entry-point-card-subtitle"><?php echo esc_html( $get_title ); ?> </span>
							  </div>
							  <div class="card-headline-container">
								<span class="entry-point-card-headline"><?php echo esc_html( $description ); ?></span>
							  </div>
						  </div>
					  </a> 
					</div>
						<?php
					}
					?>
		</div>
	</div>
  </div>
</div>

</body>
</html>



