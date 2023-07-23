<?php
/**
 * Create admin menu.
 *
 * PHP version 7
 *
 * @package  Admin_Menu
 */

/**
 * Create Admin menu.
 *
 * Template Class
 *
 * @package  Admin_Menu
 */
class Admin_Menu {

	/** Constructor call admin menu hook */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'max_create_admin_menu' ) );

	}
	/** Create the admin menu */
	public function max_create_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=webstories',
			'HelloWoofy.com',
			'HelloWoofy.com',
			'manage_options',
			'max_hellowoofy',
			array( $this, 'max_hellowoofy_callback' )
		);

		add_submenu_page(
			'edit.php?post_type=webstories',
			'Setting',
			'Setting',
			'manage_options',
			'max_hellow_woofy_setting',
			array( $this, 'max_hellowoofy_setting_callback' )
		);

		add_submenu_page(
			'edit.php?post_type=webstories',
			'All Stories',
			'All Stories',
			'manage_options',
			'max_hellow_all_stories',
			array( $this, 'max_hellow_all_stories_callback' )
		);

	}
	/** All WebStrories Callback */
	public function max_hellow_all_stories_callback() {
		require_once plugin_dir_path( __FILE__ ) . 'all-stories.php';
	}
	/** HelloWoofy Setting Callback */
	public function max_hellowoofy_setting_callback() {
		$default_tab = null;
		$tab = ( isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : $default_tab );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
				<nav class="nav-tab-wrapper">
					<a href="?post_type=webstories&page=max_hellow_woofy_setting"
					   class="nav-tab 
					   <?php
						if ( null === $tab ) :
							?>
							nav-tab-active<?php endif; ?>">Hello Woofy Setting</a>
					<a href="?post_type=webstories&page=max_hellow_woofy_setting&tab=google_analytic"
					   class="nav-tab 
					   <?php
						if ( 'google_analytic' === $tab ) :
							?>
							nav-tab-active<?php endif; ?>">Google Analytic ID</a>      
				</nav>
				<div class="tab-content">
					<?php
					switch ( $tab ) :
						case 'google_analytic':
							$this->max_google_analytic_fun();
							break;
						default:
							$this->max_default_setting();
							break;
					endswitch;
					?>
				</div>
		</div>
		<?php
	}
	/**
	 * Get all pages.
	 *
	 * @param integer $page_ids This will return page ids.
	 */
	public function get_all_pages( $page_ids = array() ) {
		$param = array();
		$param['post_type'] = 'page';
		$param['post_status'] = 'publish';
		$param['order'] = 'ASC';
		$param['orderby'] = 'title';

		if ( ! empty( $page_ids ) ) {
			$param['post__in'] = $page_ids;
		}

		// $param['post__not_in'] = [];
		$get_pages = new WP_Query( $param );
		$pages = array();
		while ( $get_pages->have_posts() ) {
			$get_pages->the_post();
			$page_content = get_the_content();
			$pages[] = array(
				'id' => get_the_ID(),
				'slug' => basename( get_permalink() ),
				'title' => get_the_title(),
			);
		}
		wp_reset_postdata();
		wp_reset_query();
		$num_index = $get_pages->found_posts + 1;
		$home = array(
			'id' => 0,
			'slug' => 'home',
			'title' => 'Home',
		);
		$pages[ $num_index ] = $home;
		return $pages;
	}
	/** Google Analytic function */
	public function max_google_analytic_fun() {
		if ( isset( $_POST['mws_google_analytic_field'] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mws_google_analytic_field'] ) ), 'mwc_google_analytic_id_action' ) ) {
			if ( isset( $_POST['max_google_analytic_id'] ) ) {

				$key  = sanitize_text_field( wp_unslash( $_POST['max_google_analytic_id'] ) );

			}
			update_option( 'max_google_analytic_id', $key );
		}
			$key = get_option( 'max_google_analytic_id' );
		?>
		<style type="text/css">
			.max_heading{
				font-size: 22px;
				font-weight: bold;
			}
			.max_woofly_api_form button{
				background-color: #0073B1;
				border: none;
				color: white;
				padding:8px 20px;
				margin-top: 18px;
			}
			.max_woofly_api_form p{
				font-size: 18px;
			}
			.max_woofly_api_form input{
				margin-left: 20px;
				width: 400px;
				margin-top: 18px;
			}
		</style>

		<h3 class="max_heading">Google Analytic ID</h3>

		<form method="post" class="max_woofly_api_form">
			 <?php wp_nonce_field( 'mwc_google_analytic_id_action', 'mws_google_analytic_field' ); ?>
			<div style="display: flex; width: 100%;">
				<div >
					<p>Enter  IDs</p>
				</div>
				<div>
					<input type="text" name="max_google_analytic_id" value="<?php echo ! empty( esc_html( $key ) ) ? esc_html( $key ) : ''; ?>">
				</div>
			</div>
			<br>
			<button type='submit' name='max_save_google_id'>Save Changes</button>
		</form>
		<?php
	}
	/** Defaul tab of admin setting page */
	public function max_default_setting() {

		if ( isset( $_POST['mws_admin_setting_field'] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mws_admin_setting_field'] ) ), 'mws_admin_setting_action' ) ) {

			if ( isset( $_POST['max_enable'] ) ) {
				$max_enable  = sanitize_text_field( wp_unslash( $_POST['max_enable'] ) );
				update_option( 'max_enable', $max_enable );
			} else {
				update_option( 'max_enable', '0' );
			}

			if ( ! empty( $_POST['max_select_page'] ) ) {
				$max_select_page = map_deep( wp_unslash( $_POST['max_select_page'] ), 'sanitize_text_field' );
				update_option( 'max_select_page', $max_select_page );
			}

			if ( ! empty( $_POST['max_select_position'] ) ) {
				$max_select_position  = sanitize_text_field( wp_unslash( $_POST['max_select_position'] ) );
				update_option( 'max_select_position', $max_select_position );
			}

			if ( ! empty( $_POST['web_story_icon'] ) ) {
				$web_story_icon  = sanitize_text_field( wp_unslash( $_POST['web_story_icon'] ) );
				update_option( 'default_webstory_icon', $web_story_icon );
			}
		}

		$check_enable       = get_option( 'max_enable' );
		$check_seleted_page = get_option( 'max_select_page' );
		$position           = get_option( 'max_select_position' );
		$attachment_id      = get_option( 'default_webstory_icon' );

		global $wpdb;
		$img = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}posts WHERE ID = %d", $attachment_id ) );
		$all_page = $this->get_all_pages();
		if ( empty( $check_seleted_page ) ) {
			$check_seleted_page = array();
		}
		?>
		<style type="text/css">
			span.select2-selection.select2-selection--multiple {
				width: 300px;
			}
			span.select2-dropdown.select2-dropdown--below {
				width: 300px !important;
			}
		</style>
		<form method="post" >
			<?php wp_nonce_field( 'mws_admin_setting_action', 'mws_admin_setting_field' ); ?>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" >
							<label for="max_enable">Enable / Disable</label>
						</th>
						<td class="forminp forminp-checkbox">
							<label for="max_enable">
							<input name="max_enable"   type="checkbox" class="max_enable" value="1" <?php echo ! empty( $check_enable ) ? 'checked' : ''; ?> >
							 Enable WebStories to display on Front Pages.                           
							</label>             
						</td>   
					</tr>
					<tr valign="top" class="select_page_tr">
							<th scope="row" class="titledesc">
								<label for="max_select_page">Select Pages</label>
							</th>
							<td class="forminp forminp-select">
								<select name="max_select_page[]" multiple="multiple"  style="" class="js-example-basic-multiple" >
									<?php

									foreach ( $all_page as $page ) {
										if ( ! empty( $page ) ) {
											if ( in_array( $page['slug'], $check_seleted_page ) ) {
												?>
												<option value="<?php echo esc_html( $page['slug'] ); ?>" selected><?php echo esc_html( $page['title'] ); ?></option>
												<?php
											} else {
												?>
												<option value="<?php echo esc_html( $page['slug'] ); ?>" ><?php echo esc_html( $page['title'] ); ?></option>
												<?php
											}
										}
									}

									?>
								  
								</select>
							</td>
					</tr>
					<tr valign="top" class="select_position_tr">
							<th scope="row" class="titledesc">
								<label for="max_select_position">Select Position </label>
							</th>
							<td class="forminp forminp-select">
								<select name="max_select_position"   >
									<option value="">Select Position</option>
									<option value="left" <?php echo ( 'left' == $position ) ? 'selected' : ''; ?>>Left</option>
									<option value="right" <?php echo ( 'right' == $position ) ? 'selected' : ''; ?>>Right</option>
								</select>
							</td>
					</tr>
					 <tr valign="top"  class="select_page_tr">
							<th scope="row" class="titledesc">
								<label for="max_select_position">Upload Icon </label>
							</th>
							<td class="forminp forminp-select" style="padding-top: -10px;">
								 <div style="display: flex;justify-content: space-around; width:34%;">
									 <div><button type="button" name="web_story_icon_upload" id="web_story_icon_upload" style="padding: 10px 20px;color: #00bdff; border: 2px solid #00bdff; border-radius: 20px; margin-left: -60px;">Image upload</button></div>
									 <div><input type="hidden" id="web_story_icon" name="web_story_icon"><img width="60" id="web_story_img" src="<?php echo esc_attr( $img->guid ); ?>"></div>
								 </div>
								
								
							</td>
					   
					</tr>
		   
				</tbody>
			</table>
			<br><br>
			<input type="submit" name="max_save_setting" id="submit" class="button button-primary" value="Save Changes">
		</form>
		<script type="text/javascript">
		let maxWebSelectBtn = 0;
		let maxWebMedia = 0;
		// This method is used to upload image file of base field for update.
			jQuery(document).on('click','#web_story_icon_upload', function(e){
				e.preventDefault();
				maxWebSelectBtn = jQuery(this);
				// Extend the wp.media object
				maxWebMedia = wp.media.frames.file_frame = wp.media({
					title: 'Select media',
					button: {
					text: 'Select media'
				}, multiple: false });
				// When a file is selected, grab the URL and set it as the text field's value
				maxWebMedia.on('select', function() {
					var attachment = maxWebMedia.state().get('selection').first().toJSON();
					jQuery('#web_story_icon').val(attachment.id);
					jQuery('#web_story_img').attr('src', attachment.url);
				});
				// Open the upload dialog
				maxWebMedia.open();
			});
		</script>
		<?php
	}
	/** Max HelloWoofy callback */
	public function max_hellowoofy_callback() {
		?>
		<style type="text/css">
			@font-face {
				font-family: 'ProximaNova Regular';
				src: url('../fonts/ProximaNova-Regular.eot');
				src: local('Proxima Nova Regular'), local('ProximaNova-Regular'),
				url('../fonts/ProximaNova-Regular.eot?#iefix') format('embedded-opentype'),
				url('../fonts/ProximaNova-Regular.woff') format('woff'),
				url('../fonts/ProximaNova-Regular.ttf') format('truetype');
				font-weight: normal;
				font-style: normal;
			}
			.woofy__container {
				font-family: 'ProximaNova Regular';
				font-size: 18px;
				height: 100vh;
				display: flex;
				justify-content: space-between;
				flex-wrap: wrap;
				padding-top: 45px;
			}

			.woofy__container p {
				font-size: 20px;
			}

			.woofy__content-block--right {
				overflow: hidden;
				flex-basis: 100%;
				padding-top: 50px;
				padding-left: 25px;
			}


		   
			 .woofy__content-block--center {
				flex-basis: 100%;
				overflow: hidden;
				margin-left: 15%;
			}

		  
		</style>
		<?php
		$key_for_display = base64_encode( get_current_user_id() . '=' . AUTH_SALT . parse_url( home_url() )['host'] );
		$total_path = plugin_dir_url( __FILE__ );
		$img_bg_url = dirname( $total_path ) . '/img/image.png';
		?>
				<div class="woofy__container">
					 <div class="woofy__content-block--center">
					<iframe width="800" height="500" src="https://www.youtube.com/embed/xtkRi7QCCro" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
					<div class="woofy__content-block--right">
						<p>Hey there👋, thanks for installing the <a href="https://hellowoofy.com/" target="_blank">HelloWoofy.com</a> plugin for Google Web Stories. You are just one step away from automating your web stories to your Wordpress blog! Woot!</p>
						<p>Please remember, you will need to use this API key below in order to start working with <a href="https://hellowoofy.com/" target="_blank">HelloWoofy.com</a> plugin when you connect your <a href="https://hellowoofy.com/" target="_blank">HelloWoofy.com</a> account with your blog on Wordpress.</p>
						<p>API Key: <input id='api_key' size='140' type='text' disabled value='<?php echo esc_html( $key_for_display ); ?>'></p>                                            
						<p>In case you have any questions or need help installing the plugin, please, visit our <a href="#">FAQ page</a></p>
						<p>Wishing you and your small business the very best.🤝</p>
						<p>Best,<br>
						   Arjun Rai,<br>
						   Founder + CEO, <a href="https://hellowoofy.com/" target="_blank">HelloWoofy.com</a></p>
					</div>
				   
				 
				</div>      
		<?php

	}

}

new Admin_Menu();



