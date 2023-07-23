<?php
/**
 * Custom Post Content.
 *
 * PHP version 7
 *
 * @package  Custom_Post_Content
 */

/**
 * Custom Post Content.
 *
 * Template Class
 *
 * @package  Custom_Post_Content
 */
class Custom_Post_Content {
	/** Max show Story */
	public function max_show_story() {

		$post_id         = get_the_ID();

		$post_meta       = get_post_meta( $post_id, 'max_webstory_pages', true );

		$story_meta      = get_post_meta( $post_id, 'story_meta', true );

		$platform        = $story_meta['for-platform'];

		$woofy_id        = $story_meta['woofy-id'];

		$pub_logo        = $story_meta['publisher-logo-src'];

		$pos_src         = $story_meta['poster-portrait-src'];

		$title           = get_the_title( $post_id );

		$story_permalink = get_permalink();

		$google_id = get_option( 'max_google_analytic_id' );
		if ( empty( $google_id ) ) {
			$google_id = '';
		}

		?>

	  <!doctype html>

		<html ⚡>

		  <head>

			<meta charset="utf-8">

			<title><?php echo esc_html( $title ); ?></title>

			<link rel="canonical" href="<?php echo esc_html( $story_permalink ); ?>">

			<meta name="viewport" content="width=device-width">

			<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>

			<script async 

				src="https://cdn.ampproject.org/v0.js"

			></script>

			<script async custom-element="amp-video"

				src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>

			<script async custom-element="amp-story"

				src="https://cdn.ampproject.org/v0/amp-story-1.0.js"></script> 

	
			<script async custom-element="amp-analytics" 

			src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>

 
			<style amp-custom>

			</style>

		  </head>

		  <body>
			<!-- Cover page -->
			<amp-story standalone 

				title="Joy of Pets"

				publisher="AMP tutorials"

				publisher-logo-src="<?php echo esc_attr( $pub_logo ); ?>"

				poster-portrait-src="<?php echo esc_attr( $pos_src ); ?>">
				<amp-analytics type="gtag" data-credentials="include">
					<script type="application/json">
					   {
						 "vars": {
						   "gtag_id": "<?php echo esc_html( $google_id ); ?>",
						   "config": {
							 "<?php echo esc_html( $google_id ); ?>": {
							   "groups": "default"
							 }
						   }
						 },
						 "triggers": {
						   "storyProgress": {
							 "on": "story-page-visible",
							 "vars": {
							   "event_name": "custom",
							   "event_action": "story_progress",
							   "event_category": "<?php echo esc_html( $title ); ?>",
							   "event_label": "<?php echo esc_html( $story_permalink ); ?>",
							   "send_to": ["<?php echo esc_html( $google_id ); ?>"]
							 }
						   },
						   "storyEnd": {
							 "on": "story-last-page-visible",
							 "vars": {
							   "event_name": "custom",
							   "event_action": "story_complete",
							   "event_category": "<?php echo esc_html( $title ); ?>",
							   "send_to": ["<?php echo esc_html( $google_id ); ?>"]
							 }
						   }
						 }
					   }
					</script>
				</amp-analytics>
			<?php
			$index = 0;
			$story_img = '';
			$story_vid = '';
			$get_products = '';
			$img_id = '';
			$vid_id = '';
			foreach ( $post_meta as $key => $value ) {
				global $wpdb;
				$this->img_id = '';
				$this->vid_id = '';
				$this->story_img = $value['page-image'];
				$this->story_vid = $value['page-video'];
				if ( ! empty( $this->story_img ) ) {
					$this->img_id = $this->story_img;
				} elseif ( $this->story_vid ) {
					$this->vid_id = $this->story_vid;
				}
				$story_text = $value['page-title'];
				$story_btn = $value['button-info'];
				$btn_text = $story_btn['button-text'];
				$btn_link = $story_btn['button-link'];
				$btn_color = $story_btn['button-color'];
				?>
				<amp-story-page id="<?php echo esc_html( $index ); ?>" auto-advance-after="7s"  >

					<amp-story-grid-layer template="fill">

						<?php

						if ( ! empty( $this->img_id ) ) {

							?>

							<amp-img src="<?php echo esc_html( $this->img_id ); ?>" width="720" height="1280" layout="responsive"  >
							</amp-img>

							<?php

						} elseif ( ! empty( $this->vid_id ) ) {

							?>

							 <amp-video autoplay 

								width="640"

								height="360"

								layout="responsive"

								poster="https://i.picsum.photos/id/1005/5760/3840.jpg?hmac=2acSJCOwz9q_dKtDZdSB-OIK1HUcwBeXco_RMMTUgfY">

								<source src="<?php echo esc_html( $this->vid_id ); ?>"

								  type="video/webm" />

								<source src="<?php echo esc_html( $this->vid_id ); ?>"

								  type="video/mp4" />

								<div fallback>

								  <p>This browser does not support the video element.</p>

								</div>

							  </amp-video>

							<?php

						}

						?>

						

					</amp-story-grid-layer>

					<amp-story-grid-layer template="vertical">
					  <h1 
			><?php echo esc_html( $story_text ); ?></h1>

					  <?php
						if ( ! empty( $btn_text ) ) {

							?>

					  <a href="<?php echo esc_html( $btn_link ); ?>" style="width:auto; border-radius: 5px;color:white; text-decoration: none;padding:15px 30px; background-color: <?php echo esc_html( $btn_color ); ?>"><?php echo esc_html( $btn_text ); ?></a>

							<?php

						}
						?>

					</amp-story-grid-layer>

				</amp-story-page>



				<?php

				$index++;

				// code...

			}

			?>

			</amp-story>





		  </body>

		</html>

		<?php

	}

}

$obj = new Custom_Post_Content();

$obj->max_show_story();







