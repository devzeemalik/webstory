<?php
/**
 * Google Web Story Slider In Modal.
 *
 * This file display the google webstrory slider.
 *
 * @link       https://maxenius.com/
 * @since      1.0.1
 *
 * @package    Max_web_story
 * @subpackage Max_web_story/public/assets/partials
 */

$total_path  = plugin_dir_url( __FILE__ );
$img_bg_url  = dirname( $total_path ) . '/public/assets/img/1.png';
$position    = get_option( 'max_select_position' );

if ( 'right' == $position ) {
	$icon_pos = 'right:30px;';
} else {
	$icon_pos = 'left:30px;';
}

$attachment_id = get_option( 'default_webstory_icon' );
global $wpdb;
$table_name    = $wpdb->prefix . 'posts';
$img = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}posts WHERE ID = %d", $attachment_id ) );

if ( empty( $img ) ) {
	$img = $img_bg_url;
} else {
	$img = $img->guid;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">

</head>
<body>
  <style type="text/css">
	  .woocommerce-page img {
		height: inherit;
	  }
	  #myBtn {
		<?php echo esc_html( $icon_pos ); ?>
		display: none;
		position: fixed;
		bottom: 20px;
		z-index: 99;
		font-size: 18px;
		border: none;
		outline: none;
		cursor: pointer;
	  }
	  .max_small_device{
		display: none;
	  }
	  .max_large_device{
		display: block;
	  }
	  .modal-header{
		border:none !important;
	  }
	  .modal-dialog {
		width: 100%;
		height: 100%;
	  }
	  @media only screen and (max-width: 912px) {
		.max_large_device{
		  display: none;
		}
		.max_small_device{
		  display: block;
		}
	  }
  </style>

  <div class="container">
	<!-- Button to Open the Modal -->
	<a data-toggle="modal" data-target="#myModal"  id="myBtn" > 
	  <img src="<?php echo esc_html( $img ); ?>" style='width:60px;'>
	</a>

	<!-- The Modal -->
	<div class="modal" id="myModal">
	  <div class="modal-dialog " style="max-width: 100% !important; ">
		<div class="modal-content">
		
		  <!-- Modal Header -->
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" style="background-color: white;color:black;font-size: 40px;">&times;</button>
		  </div>
	
		  <!-- Modal body -->
		  <div class="modal-body ">
			<div class="container max_large_device">
			  <div class="row">
						<?php
						$defaults     = array(
							'numberposts'      => 5,
							'category'         => 0,
							'orderby'          => 'date',
							'order'            => 'DESC',
							'include'          => array(),
							'exclude'          => array(),
							'meta_key'         => '',
							'meta_value'       => '',
							'post_type'        => 'webstories',
							'suppress_filters' => true,
						);
						$parsed_args  = wp_parse_args( $defaults );
						if ( empty( $parsed_args['post_status'] ) ) {
							$parsed_args['post_status'] = ( 'attachment' === $parsed_args['post_type'] ) ? 'inherit' : 'publish';
						}

						$get_posts = new WP_Query();
						$get_posts = $get_posts->query( $parsed_args );
						foreach ( $get_posts as $key => $get_post ) {
							$get_post_id    = $get_post->ID;
							$get_title      = $get_post->post_title;
							$description    = $get_post->post_content;
							$permalink      = $get_post->guid;
							$story_meta     = get_post_meta( $get_post_id, 'story_meta', true );
							$slider_logo    = $story_meta['publisher-logo-src'];
							$slider_potrait = $story_meta['poster-portrait-src'];
							?>
						  <div class="col-sm-3 col-6  p-4 ">

							<a href="<?php echo esc_html( $permalink ); ?>">
								<div class="entry-point-card-container  " >
								  <div class="background-cards">
									<div class="background-card-1"></div>
									<div class="background-card-2"></div>
								  </div>
									<img src="<?php echo esc_html( $slider_potrait ); ?>" class="entry-point-card-img" alt="A cat">
									<div class="author-container">
									  <div class="logo-container">
										<div class="logo-ring"></div>
										<img class="entry-point-card-logo" src="<?php echo esc_html( $slider_logo ); ?>" alt="Publisher logo">
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
	   

			<!--For mobile device -->
			  <!-- carousel -->
			  <div class="carousel-section max_small_device">
				<div class="carousel-container">
				  <div class="carousel-cards-container">
					<div class="entry-points">
						<?php

						$defaults     = array(
							'numberposts'      => 5,
							'category'         => 0,
							'orderby'          => 'date',
							'order'            => 'DESC',
							'include'          => array(),
							'exclude'          => array(),
							'meta_key'         => '',
							'meta_value'       => '',
							'post_type'        => 'webstories',
							'suppress_filters' => true,
						);

						$parsed_args  = wp_parse_args( $args, $defaults );

						if ( empty( $parsed_args['post_status'] ) ) {
							$parsed_args['post_status'] = ( 'attachment' === $parsed_args['post_type'] ) ? 'inherit' : 'publish';
						}

						$get_posts = new WP_Query();
						$max_posts = $get_posts->query( $parsed_args );
						foreach ( $max_posts as $key => $get_post ) {
							$get_post_id    = $get_post->ID;
							$get_title          = $get_post->post_title;
							$description    = $get_post->post_content;
							$permalink      = $get_post->guid;
							$story_meta     = get_post_meta( $get_post_id, 'story_meta', true );
							$slider_logo    = $story_meta['publisher-logo-src'];
							$slider_potrait = $story_meta['poster-portrait-src'];
							?>

						  <a href="<?php echo esc_html( $permalink ); ?>">
							<div class="entry-point-card-container">
							  <div class="background-cards">
								<div class="background-card-1"></div>
								<div class="background-card-2"></div>
							  </div>
							  <img src="<?php echo esc_html( $slider_potrait ); ?>" class="entry-point-card-img" alt="A cat">
							  <div class="author-container">
								<div class="logo-container">
								  <div class="logo-ring"></div>
								  <img class="entry-point-card-logo" src="<?php echo esc_html( $slider_logo ); ?>" alt="Publisher logo">
								</div>
								<span class="entry-point-card-subtitle"><?php echo esc_html( $get_title ); ?> </span>
							  </div>
							  <div class="card-headline-container">
								<span class="entry-point-card-headline"  ><?php echo esc_html( $description ); ?></span>
							  </div>
						  </div>
						  </a>

							<?php
						}
						?>
					</div>
					<!-- Carousel arrows -->
					<button class="entry-point-left-arrow entry-point-arrow "></button>
					<button class="entry-point-right-arrow entry-point-arrow "></button>
				  </div>
				</div>
			  </div>
			  <!-- Lightbox and player -->
			  <div class="lightbox closed max_small_device">
				<amp-story-player class="my-player">
				  <script type="application/json">
					{
					  "behavior": {
						"pageScroll": false,
						"autoplay": false
					  },
					  "controls": [{
						  "name": "close",
						  "position": "start"
						},
						{
						  "name": "skip-to-next"
						}
					  ]
					}
				  </script>
				  <?php
						$defaults = array(
							'numberposts'      => 5,
							'category'         => 0,
							'orderby'          => 'date',
							'order'            => 'DESC',
							'include'          => array(),
							'exclude'          => array(),
							'meta_key'         => '',
							'meta_value'       => '',
							'post_type'        => 'webstories',
							'suppress_filters' => true,
						);
						$parsed_args = wp_parse_args( $args, $defaults );
						if ( empty( $parsed_args['post_status'] ) ) {
							$parsed_args['post_status'] = ( 'attachment' === $parsed_args['post_type'] ) ? 'inherit' : 'publish';
						}
						$get_posts = new WP_Query();
						$get_posts     = $get_posts->query( $parsed_args );
						foreach ( $get_posts as $key => $get_post ) {
							$permalink  = $get_post->guid;
							?>
							<?php
						}
						?>

				</amp-story-player>
			  </div>
		  </div>
		   
		</div>
	  </div>
	</div>
	
  </div>

  <script>
	  //Get the button
	  var mybutton = document.getElementById("myBtn");

	  // When the user scrolls down 20px from the top of the document, show the button
	  window.onscroll = function() {scrollFunction()};

	  function scrollFunction() {
		if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
		  mybutton.style.display = "block";
		} else {
		  mybutton.style.display = "none";
		}
	  }

	  // When the user clicks on the button, scroll to the top of the document
	  function topFunction() {
		document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	  }
  </script>

</body>
</html>
