<?php
/**
 * This file contains all custom endpoinits.
 *
 * PHP version 7
 *
 * @package  Mws_Custom_Endpoints
 */

/**
 * This file contains all custom endpoints.
 *
 * Template Class
 *
 * @package  Mws_Custom_Endpoints
 */
class Mws_Custom_Endpoints {

	/** Register all the routes */
	public function __construct() {

		register_rest_route(
			'wp/v2',
			'/create',
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'max_create_post' ),
			)
		);

		/** For Edit */
		register_rest_route(
			'wp/v2',
			'/get/(?P<id>\d+)',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'max_edit_post' ),
			)
		);

		/** For Update */
		register_rest_route(
			'wp/v2',
			'/update',
			array(
				'methods' => 'PUT',
				'callback' => array( $this, 'max_create_post' ),
			)
		);

		/** For Delete */
		register_rest_route(
			'wp/v2',
			'/delete/(?P<id>\d+)',
			array(
				'methods' => 'Delete',
				'callback' => array( $this, 'max_delete_post' ),
			)
		);

		/** For listing */
		register_rest_route(
			'wp/v2',
			'/listing',
			array(
				'methods' => 'get',
				'callback' => array( $this, 'max_listing' ),
			)
		);

		/** For listing */
		register_rest_route(
			'wp/v2',
			'/listing',
			array(
				'methods' => 'get',
				'callback' => array( $this, 'max_listing' ),
			)
		);

		/** HelloWoofy.com rest rout */
		register_rest_route(
			'woofy/v1',
			'/post/',
			array(
				array(
					'methods' => WP_REST_Server::READABLE,
					'callback' => array( $this, 'max_woofy_set_cat_func' ),
				),
				array(
					'methods' => WP_REST_Server::CREATABLE,
					'callback' => array( $this, 'max_woofy_response' ),
					'args' => array(
						'title' => array(
							'type' => 'string',
							'required' => true,
						),
						'content' => array(
							'type' => 'string',
							'required' => true,
						),
						'category' => array(
							'type' => 'string',
							'required' => true,
						),
						'tags' => array(
							'type' => 'string',
							'required' => true,
						),
						'status' => array(
							'type' => 'string',
							'required' => true,
						),
						'auth' => array(
							'type' => 'string',
							'required' => true,
						),
					),
					'permission_callback' => function ( $request ) {
						$encode_key = $request->get_param( 'auth' );
						$decode_unique_key = base64_decode( $encode_key );
						// separating the user id from the key.
						$decode_unique_key = substr( stristr( $decode_unique_key, '=' ), 1 );
						if ( AUTH_SALT . parse_url( home_url() )['host'] === $decode_unique_key ) {
							return true;
						}
						return false;
					},
				),
			)
		);

	}

	/** HelloWoofy.com rest route function */
	public function max_woofy_set_cat_func() {
		$args_for_send = array( 'category', 'post_tag' );
		// Added to DB.
		$categories = get_terms( $args_for_send, 'orderby=name&hide_empty=0' );
		$categories_for_send = array();
		foreach ( $categories as $cat ) {
			if ( 'category' === $cat->taxonomy ) {
				$args = array(
					'category_name' => $cat->name,
					'category_slug' => $cat->slug,
				);
			} else {
				$args = array(
					'tag_name' => $cat->name,
					'tag_slug' => $cat->slug,
				);
			}
			array_push( $categories_for_send, $args );
		}
		return $categories_for_send;
	}

	/**
	 * WebStories Listings.
	 *
	 * @param WP_REST_Request $request This endpoints for HelloWoofy verification.
	 */
	public function max_woofy_response( WP_REST_Request $request ) {
		// get the key.
		$unique_user_key = $request->get_param( 'auth' );
		// get user id.
		$key_decode = base64_decode( $unique_user_key );
		$user_id = stristr( $key_decode, '=', true );
		$image_url = $request->get_param( 'journalPostFeaturedImage' );

		// create array for Post.
		$post_data = array(
			'post_title' => $request->get_param( 'title' ),
			'post_content' => $request->get_param( 'content' ),
			'post_status' => $request->get_param( 'status' ),
			'post_author' => $user_id,
		);
		// get post category.
		$post_category = explode( ',', $request->get_param( 'category' ) );
		$post_tags = explode( ',', $request->get_param( 'tags' ) );

		// Added iframe.
		add_filter( 'wp_kses_allowed_html', 'woofy_prefix_add_source_tag', 10, 2 );
		/**
		 * Upload img and video.
		 *
		 * @param string $tags get all tags.
		 *
		 * @param int    $context get contect.
		 */
		function woofy_prefix_add_source_tag( $tags, $context ) {
			if ( 'post' === $context ) {
				$tags['iframe'] = array(
					'src'    => true,
					'srcdoc' => true,
					'width'  => true,
					'height' => true,
				);
			}
			return $tags;
		}

		// Added to DB.
		$post_id = wp_insert_post( wp_slash( $post_data ) );

		if ( is_wp_error( $post_id ) ) {
			return $post_id->get_error_message();
		}

		wp_set_object_terms( $post_id, $post_category, 'category' );
		wp_set_object_terms( $post_id, $post_tags, 'post_tag' );

		if ( $image_url ) {
			$thumbnail = $this->max_woofy_insert_attachment( $image_url, $post_id, $user_id );
		} else {
			$thumbnail = "Image wasn't set in request";
		}

		if ( is_wp_error( $thumbnail ) ) {
			return $thumbnail->get_error_messages();
		}

		$result = array(
			'post_category' => $post_category,
			'post_id' => $post_id,
			'post_url' => get_permalink( $post_id ),
			'thumbnail' => $thumbnail ? $thumbnail : '',
		);

		return $result;
	}

	/**
	 * Upload img and video.
	 *
	 * @param string $image_url get img url.
	 *
	 * @param int    $post_id get post_id.
	 *
	 * @param int    $user_id get user_id.
	 */
	public function max_woofy_insert_attachment( $image_url, $post_id = null, $user_id ) {
		if ( ! function_exists( 'media_handle_upload' )
		   && user_can( $user_id, 'edit_posts' ) ) {
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';

			$img_id = media_sideload_image( $image_url, $post_id, null, 'id' );
			set_post_thumbnail( $post_id, $img_id );

			if ( is_wp_error( $img_id ) ) {
				return new WP_Error( 400, __( "Image wasn't uploaded", 'woofy' ) );
			}
			return wp_get_attachment_image_src( $img_id );
		} else {
			return new WP_Error( 400, __( "Check 'media_handle_upload' failed", 'woofy' ) );
		}
	}


	/**
	 * WebStories Listings.
	 *
	 * @param WP_REST_Request $request This will return all webstories.
	 */
	public function max_listing( WP_REST_Request $request ) {
		$encode_key        = $request->get_header( 'token' );
		$decode_unique_key = base64_decode( $encode_key );
		$decode_unique_key = substr( stristr( $decode_unique_key, '=' ), 1 );
		if ( AUTH_SALT . parse_url( home_url() )['host'] === $decode_unique_key ) {
			global $wpdb;
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'webstories' AND post_status = 'publish' ", ARRAY_A ) );
			foreach ( $results as $key => $value ) {
				$post_id = $value->ID;
				$post_meta       = get_post_meta( $post_id, 'max_webstory_pages', true );
				$story_meta      = get_post_meta( $post_id, 'story_meta', true );
				if ( ! empty( $story_meta ) ) {
					$platform        = $story_meta['for-platform'];
					$woofy_id        = $story_meta['woofy-id'];
					$pub_logo        = $story_meta['publisher-logo-src'];
					$pos_src         = $story_meta['poster-portrait-src'];

					$title           = get_the_title( $post_id );
					$description     = get_post_field( 'post_content', $post_id );
					$page_index = 0;
					foreach ( $post_meta as $key => $value ) {
						$page_img   = $value['page-image'];
						$page_video = $value['page-video'];
						$page_title = $value['page-title'];
						$btn_info   = $value['button-info'];
						$all_pages[ $page_index ] = array(
							'page-title' => $page_title,
							'page-image' => ! empty( $page_img ) ? $page_img : $page_video,
							'button-info' => $btn_info,
						);
						$page_index++;
					}
					$edit_story[] = array(
						'story-title'         => $title,
						'for-platform'        => $story_meta['for-platform'],
						'woofy-id'            => $story_meta['woofy-id'],
						'post-id'             => $post_id,
						'publisher-logo-src'  => $story_meta['publisher-logo-src'],
						'poster-portrait-src' => $story_meta['poster-portrait-src'],
						'description'         => $description,
						'pages'               => $all_pages,
					);

				} else {
					$post_id = $request['id'];
					$path = get_site_url() . '/wp-json/wp/v2/max_edit/' . $post_id;
					global $wpdb;
					$table = $wpdb->prefix . 'max_fail_requests';
					$wpdb->insert(
						$table,
						array(
							'webhook'           => $path,
							'request_data'      => 'No post exist against this id',
							'timestam'          => gmdate( 'Y/m/d' ),
						),
					);
					echo json_encode( 'No Record found' );
				}
			}
			if ( ! empty( $edit_story ) ) {
				print_r( json_encode( $edit_story ) );
				exit;
			}
		} else {
			$path = get_site_url() . '/wp-json/wp/v2/max_edit';
			$request_data = $request->get_body();
			global $wpdb;
			$table = $wpdb->prefix . 'max_fail_requests';
			$wpdb->insert(
				$table,
				array(
					'webhook'           => $path,
					'request_data'      => $request_data,
					'timestam'          => gmdate( 'Y/m/d' ),
				),
			);
			echo json_encode( 'Unable to authenticate' );
		}
	}

	/**
	 * WebStories Edit Post.
	 *
	 * @param WP_REST_Request $request This will return single story.
	 */
	public function max_edit_post( WP_REST_Request $request ) {
		$encode_key        = $request->get_header( 'token' );
		$decode_unique_key = base64_decode( $encode_key );
		$decode_unique_key = substr( stristr( $decode_unique_key, '=' ), 1 );
		if ( AUTH_SALT . parse_url( home_url() )['host'] === $decode_unique_key ) {
			$post_id = $request['id'];
			$post_meta       = get_post_meta( $post_id, 'max_webstory_pages', true );
			$story_meta      = get_post_meta( $post_id, 'story_meta', true );
			if ( ! empty( $story_meta ) ) {
				$platform        = $story_meta['for-platform'];
				$woofy_id        = $story_meta['woofy-id'];
				$pub_logo        = $story_meta['publisher-logo-src'];
				$pos_src         = $story_meta['poster-portrait-src'];

				$title           = get_the_title( $post_id );
				$description     = get_post_field( 'post_content', $post_id );
				$page_index = 0;
				foreach ( $post_meta as $key => $value ) {
					$page_img   = $value['page-image'];
					$page_video = $value['page-video'];
					$page_title = $value['page-title'];
					$btn_info   = $value['button-info'];
					$all_pages[ $page_index ] = array(
						'page-title' => $page_title,
						'page-image' => ! empty( $page_img ) ? $page_img : $page_video,
						'button-info' => $btn_info,
					);
					$page_index++;
				}
				$edit_story = array(
					'story-title'         => $title,
					'for-platform'        => $story_meta['for-platform'],
					'woofy-id'            => $story_meta['woofy-id'],
					'post-id'             => $post_id,
					'publisher-logo-src'  => $story_meta['publisher-logo-src'],
					'poster-portrait-src' => $story_meta['poster-portrait-src'],
					'description'         => $description,
					'pages'               => $all_pages,
				);
				print_r( json_encode( $edit_story ) );
			} else {
				$post_id = $request['id'];
				$path = get_site_url() . '/wp-json/wp/v2/max_edit/' . $post_id;
				global $wpdb;
				$table = $wpdb->prefix . 'max_fail_requests';
				$wpdb->insert(
					$table,
					array(
						'webhook'           => $path,
						'request_data'      => 'No post exist against this id',
						'timestam'          => gmdate( 'Y/m/d' ),
					),
				);
				echo json_encode( 'No Record found' );
			}
		} else {
			$path = get_site_url() . '/wp-json/wp/v2/max_edit';
			$request_data = $request->get_body();
			global $wpdb;
			$table = $wpdb->prefix . 'max_fail_requests';
			$wpdb->insert(
				$table,
				array(
					'webhook'           => $path,
					'request_data'      => $request_data,
					'timestam'          => gmdate( 'Y/m/d' ),
				),
			);
			echo json_encode( 'Unable to authenticate' );
		}
	}

	/**
	 * WebStories Delete Post.
	 *
	 * @param WP_REST_Request $request This will delete webstory.
	 */
	public function max_delete_post( WP_REST_Request $request ) {
		$encode_key        = $request->get_header( 'token' );
		$decode_unique_key = base64_decode( $encode_key );
		$decode_unique_key = substr( stristr( $decode_unique_key, '=' ), 1 );
		if ( AUTH_SALT . parse_url( home_url() )['host'] === $decode_unique_key ) {
			$post_id = $request['id'];
			$dlt_post = wp_delete_post( $post_id );
			if ( $dlt_post->ID == $post_id ) {
				echo json_encode( 'Deleted Successfully' );
			} else {
				echo json_encode( 'No record found against this id :' . $post_id );
			}
		} else {
			$path = get_site_url() . '/wp-json/wp/v2/max_delete';
			$request_data = $request->get_body();
			global $wpdb;
			$table = $wpdb->prefix . 'max_fail_requests';
			$wpdb->insert(
				$table,
				array(
					'webhook'           => $path,
					'request_data'      => $request['id'],
					'timestam'          => gmdate( 'Y/m/d' ),
				),
			);
			echo json_encode( 'Unable to authenticate' );
		}
	}


	/**
	 * WebStories Create Post.
	 *
	 * @param WP_REST_Request $request This will create new webstory.
	 */
	public function max_create_post( WP_REST_Request $request ) {
		$encode_key        = $request->get_header( 'token' );
		$decode_unique_key = base64_decode( $encode_key );
		$decode_unique_key = substr( stristr( $decode_unique_key, '=' ), 1 );
		if ( AUTH_SALT . parse_url( home_url() )['host'] === $decode_unique_key ) {
			$body = $request->get_body();
			$data = json_decode( $body, true );
			foreach ( $data as $key => $value ) {
				$exs_post_id    = $value['post-id'];
				$get_pages      = $value['pages'];
				$story_platform = $value['for-platform'];
				$woofy_id       = $value['woofy-id'];
				$pub_logo       = $value['publisher-logo-src'];
				$pos_src        = $value['poster-portrait-src'];
				$max_logo_src   = '';
				$max_pos_src    = '';
				$max_logo_src   = $this->max_upload_img_video_from_src( $pub_logo );
				$attachment_id  = $max_logo_src['id'];

				if ( ! empty( $max_logo_src ) && 'success' == $max_logo_src['status'] ) {
					$max_logo_src = $max_logo_src['src'];
				} else {
					$max_logo_src = '';
				}
				$max_pos_src = $this->max_upload_img_video_from_src( $pos_src );
				if ( ! empty( $max_pos_src ) && 'success' == $max_pos_src['status'] ) {
					$max_pos_src = $max_pos_src['src'];
				} else {
					$max_pos_src = '';
				}

				$story_meta = array(
					'for-platform'        => $story_platform,
					'woofy-id'            => $woofy_id,
					'publisher-logo-src'  => $max_logo_src,
					'poster-portrait-src' => $max_pos_src,
				);

				$create_post    = array(
					'post_title'   => $value['story-title'],
					'post_type'    => 'webstories',
					'post_status'  => 'publish',
					'post_content' => $value['description'],
				);
				$page_index = 0;
				$send = array();
				foreach ( $get_pages as $key => $page ) {
					$get_img        = $page['page-image'];
					$page_title     = $page['page-title'];
					$page_btn       = $page['button-info'];
					$path_info      = pathinfo( $page['page-image'] );
					$max_page_vid   = '';
					$max_page_img   = '';
					if ( ! empty( $path_info['extension'] ) && 'mp4' == $path_info['extension'] ) {
						$max_page_vid = $this->max_upload_img_video_from_src( $get_img );
						if ( ! empty( $max_page_vid ) && 'success' == $max_page_vid['status'] ) {
							$max_page_vid = $max_page_vid['src'];
						} else {
							$max_page_vid = '';
						}
					} elseif ( ! empty( $path_info['extension'] ) && 'mp4' != $path_info['extension'] ) {
						$max_page_img = $this->max_upload_img_video_from_src( $get_img );
						if ( ! empty( $max_page_img ) && 'success' == $max_page_img['status'] ) {
							$max_page_img = $max_page_img['src'];
						} else {
							$max_page_img = '';
						}
					}
					$send[ $page_index ] = array(
						'page-image'  => $max_page_img,
						'page-video'  => $max_page_vid,
						'page-title'  => $page_title,
						'button-info' => $page_btn,
					);
					$page_index++;
				}
				if ( empty( $exs_post_id ) ) {
					$post_id = wp_insert_post( $create_post );
					$story_url = get_permalink( $post_id );
					set_post_thumbnail( $post_id, $attachment_id );
					update_post_meta( $post_id, 'max_webstory_pages', $send );
					update_post_meta( $post_id, 'story_meta', $story_meta );
					$send_res = array(
						'Status'  => 'Created Successfully',
						'post-id' => $post_id,
						'story-url' => $story_url,
					);

					print_r( json_encode( $send_res ) );
				} else {
					$upd_post    = array(
						'ID'           => $exs_post_id,
						'post_title'   => $value['story-title'],
						'post_content' => $value['description'],
					);
					wp_update_post( $upd_post );
					$story_url = get_permalink( $exs_post_id );
					set_post_thumbnail( $exs_post_id, $attachment_id );
					update_post_meta( $exs_post_id, 'max_webstory_pages', $send );
					update_post_meta( $exs_post_id, 'story_meta', $story_meta );
					$send_res = array(
						'Status'          => 'Updated Successfully',
						'post-id'         => $exs_post_id,
						'story_url' => $story_url,
					);
					print_r( json_encode( $send_res ) );

				}
			}
		} else {

			if ( $request->get_route() == '/wp/v2/max_update' ) {
				$path = get_site_url() . '/wp-json/wp/v2/max_update';
			} else {
				$path = get_site_url() . '/wp-json/wp/v2/max_create';
			}

			$request_data = $request->get_body();
			global $wpdb;
			$table = $wpdb->prefix . 'max_fail_requests';
			$wpdb->insert(
				$table,
				array(
					'webhook'           => $path,
					'request_data'      => $request_data,
					'timestam'          => gmdate( 'Y/m/d' ),
				),
			);
			echo json_encode( 'Unable to authenticate' );
		}
	}

	/**
	 * Upload img and video.
	 *
	 * @param string $file This willl get file.
	 *
	 * @param int    $post_id This willl get post id.
	 *
	 * @param string $desc This willl get description.
	 *
	 * @param string $return This willl return html.
	 */
	public function max_upload_img_video_from_src( $file, $post_id = 0, $desc = null, $return = 'html' ) {
		if ( ! empty( $file ) ) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			$allowed_extensions = array( 'jpg', 'jpeg', 'jpe', 'png', 'gif', 'webp', 'mp4' );

			/**
			 * Filters the list of allowed file extensions when sideloading an image from a URL.
			 *
			 * The default allowed extensions are:
			 *
			 *  - `jpg`
			 *  - `jpeg`
			 *  - `jpe`
			 *  - `png`
			 *  - `gif`
			 *
			 * @since 5.6.0
			 *
			 * @param string[] $allowed_extensions Array of allowed file extensions.
			 * @param string   $file               The URL of the image to download.
			 */
			$allowed_extensions = apply_filters( 'image_sideload_extensions', $allowed_extensions, $file );
			$allowed_extensions = array_map( 'preg_quote', $allowed_extensions );

			// Set variables for storage, fix file filename for query strings.
			preg_match( '/[^\?]+\.(' . implode( '|', $allowed_extensions ) . ')\b/i', $file, $matches );
			if ( ! $matches ) {
				return new WP_Error( 'image_sideload_failed', __( 'Invalid image URL.' ) );
			}

			$file_array         = array();
			$file_array['name'] = wp_basename( $matches[0] );

			// Download file to temp location.
			$file_array['tmp_name'] = download_url( $file );

			// If error storing temporarily, return the error.
			if ( is_wp_error( $file_array['tmp_name'] ) ) {
				return $file_array['tmp_name'];
			}

			// Do the validation and storage stuff.
			$id = media_handle_sideload( $file_array, $post_id, $desc );

			// If error storing permanently, unlink.
			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] );
				return $id;
			}

			// Store the original attachment source in meta.
			add_post_meta( $id, '_source_url', $file );

			// If attachment ID was requested, return it.
			if ( 'id' === $return ) {
				return $id;
			}

			$src = wp_get_attachment_url( $id );
		}

		// Finally, check to make sure the file has been saved, then return the HTML.
		if ( ! empty( $src ) ) {
			if ( 'src' === $return ) {
				return $src;
			}
			$alt  = isset( $desc ) ? esc_attr( $desc ) : '';
			$html = "<img src='$src' alt='$alt' />";

			$media_success = array(
				'status' => 'success',
				'src'    => $src,
				'id'     => $id,
			);
			return $media_success;
		} else {
			return new WP_Error( 'image_sideload_failed' );
		}
	}

}

new Mws_Custom_Endpoints();

