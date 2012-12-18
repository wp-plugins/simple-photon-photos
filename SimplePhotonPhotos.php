<?php
/*
 * Plugin Name: Simple Photon Photos
 * Plugin URI: http://simplephotonphotos.com
 * Description: Simply and easily adjust the size and add effects to your photos utilizing the Jetpack Photon API. This plugin requires <a href="http://wordpress.org/extend/plugins/jetpack/" target="_blank">Jetpack by WordPress.com</a>.
 * Version: 0.5
 * Author: Scott Sousa, Slocum Design Studio
 * Author URI: http://www.slocumstudio.com
 * License: GPL2+
*/

if ( !class_exists( 'SimplePhotonPhotos' ) ) {
	/**
	 * Simple Photon Photos Class - This class makes it simple to add Photon API "GET" queries to images inserted into WordPress content
	 */
	class SimplePhotonPhotos {

		/**
		 * Upon instantiation register activation/deactivation hooks, add all actions, filters
		 */
		function __construct( ) {
			// Plugin activation (handled by the SimplePhotonPhotosInit Class, @see includes/simple-photon-photos-init.php)
			register_activation_hook( __FILE__, array( 'SimplePhotonPhotosInit', 'spp_activation' ) );

			add_filter( 'attachment_fields_to_edit', array( $this, 'spp_attachment_fields_to_edit' ), 10, 2 ); // Add attachment fields
			add_filter( 'attachment_fields_to_save', array( $this, 'spp_attachment_fields_to_save' ), 10, 2 ); // Save attachment fields

			add_filter( 'image_send_to_editor', array( $this, 'spp_image_send_to_editor' ), 10, 8 ); // Modify image src url to include GET queries

			add_filter( 'jetpack_photon_post_image_args', array( $this, 'spp_jetpack_photon_post_image_args' ), 10, 2 ); // Modify output of Photon image based on GET queries

			add_action( 'admin_print_scripts', array( $this, 'spp_admin_print_scripts' ) ); // Print media scripts
		}


		/**
		 * This filter adds the necessary fields to edit after image details in media modal
		 *
		 * @param array $form_fields Default form fields
		 * @param object $post Attachment (post object)
		 * @return array $form_fields Array of form fields
		 */
		function spp_attachment_fields_to_edit( $form_fields, $post ) {
			// Bail if the attachment is not an image
			if ( !wp_attachment_is_image( $post->ID ) )
				return;

			$photon_api_docs_url = 'http://developer.wordpress.com/docs/photon/api'; // API Docs URL
			$attached_image = wp_get_attachment_image_src( $post->ID, 'full' ); // Get full size image details

			// Simple Photon Photos Label
			$form_fields["spp-label"]["tr"] = '<tr class="spp-tr">
				<th valign="top" scope="row" id="spp-label-th" class="label spp-full-width" colspan="2">
					<h3 id="spp-label">Photon API</h3>
					<span class="spp-preview-message"><strong>Please Note:</strong> Photon API effects cannot be seen directly in the post editor at this time. Please preview the post or page to see the Photon API effects in action on your website.</span>
				</th>
			</tr>';

			// Simple Photon Photos - Width
			$form_fields["spp-width"]["tr"] = '<tr class="spp-tr">
				<th valign="top" scope="row" class="label spp-full-width" colspan="2">
					<label for="attachments-'.$post->ID.'-spp-width" class="spp-label">
						<span class="alignleft">Adjust Width (<a href="'.$photon_api_docs_url.'/#w" target="_blank">?</a>)</span>
						<br class="clear">
					</label>
					<input type="text" class="text attachments-spp-width spp-input spp-width-input" id="attachments-'.$post->ID.'-spp-width" name="attachments['.$post->ID.'][spp-width]" value="'.$attached_image[1].'" autocomplete="off" />
					<div class="spp-slider spp-width-slider"></div>
				</th>
			</tr>';

			// Simple Photon Photos - Height
			$form_fields["spp-height"]["tr"] = '<tr class="spp-tr">
				<th valign="top" scope="row" class="label spp-full-width" colspan="2">
					<label for="attachments-'.$post->ID.'-spp-height" class="spp-label">
						<span class="alignleft">Adjust Height (<a href="'.$photon_api_docs_url.'/#w" target="_blank">?</a>)</span>
						<br class="clear">
					</label>
					<input type="text" class="text attachments-spp-height spp-input spp-height-input" id="attachments-'.$post->ID.'-spp-height" name="attachments['.$post->ID.'][spp-height]" value="'.$attached_image[2].'" autocomplete="off" />
					<div class="spp-slider spp-height-slider"></div>
				</th>
			</tr>';

			// Simple Photon Photos - Resize
			$form_fields["spp-resize"]["tr"] = '<tr class="spp-tr spp-last-tr">
				<th valign="top" scope="row" class="label spp-full-width" colspan="2">
					<label for="attachments-'.$post->ID.'-spp-resize" class="spp-label">
						<span class="alignleft">Resize? (<a href="'.$photon_api_docs_url.'/#resize" target="_blank">?</a>)</span>
						<br class="clear">
					</label>
					<input type="checkbox" class="checkbox attachments-spp-resize spp-input spp-resize-checkbox" id="attachments-'.$post->ID.'-spp-resize" name="attachments['.$post->ID.'][spp-resize]" value="true" />
				</th>
			</tr>';

			// Simple Photon Photos - Fit
			$form_fields["spp-fit"]["tr"] = '<tr class="spp-tr spp-last-tr">
				<th valign="top" scope="row" class="label spp-full-width" colspan="2">
					<label for="attachments-'.$post->ID.'-spp-fit" class="spp-label">
						<span class="alignleft">Fit (<a href="'.$photon_api_docs_url.'/#fit" target="_blank">?</a>)</span>
						<br class="clear">
					</label>
					<input type="checkbox" class="checkbox attachments-spp-fit spp-input spp-fit-checkbox" id="attachments-'.$post->ID.'-spp-fit" name="attachments['.$post->ID.'][spp-fit]" value="true" />
				</th>
			</tr>';

			// Simple Photon Photos - Ulb
			$form_fields["spp-ulb"]["tr"] = '<tr class="spp-tr spp-last-tr">
				<th valign="top" scope="row" class="label spp-full-width" colspan="2">
					<label for="attachments-'.$post->ID.'-spp-ulb" class="spp-label">
						<span class="alignleft">Ulb (<a href="'.$photon_api_docs_url.'/#ulb" target="_blank">?</a>)</span>
						<br class="clear">
					</label>
					<input type="checkbox" class="checkbox attachments-spp-ulb spp-input spp-ulb-checkbox" id="attachments-'.$post->ID.'-spp-ulb" name="attachments['.$post->ID.'][spp-ulb]" value="true" />
				</th>
			</tr>';

			// Simple Photon Photos - Filter
			$form_fields["spp-filter"]["tr"] = '<tr class="spp-tr spp-last-tr">
				<th valign="top" scope="row" class="label spp-full-width" colspan="2">
					<label for="attachments-'.$post->ID.'-spp-fit" class="spp-label">
						<span class="alignleft">Filter (<a href="'.$photon_api_docs_url.'/#filter" target="_blank">?</a>)</span>
						<br class="clear">
					</label>
					<select class="select attachments-spp-filter spp-input spp-filter-select" id="attachments-'.$post->ID.'-spp-filter" name="attachments['.$post->ID.'][spp-filter]">
						<option value="">None</option>
						<option value="blurguassian">Gaussian Blur</option>
						<option value="edgedetect">Edge Detect</option>
						<option value="emboss">Emboss</option>
						<option value="meanremoval">Mean Removal</option>
						<option value="negate">Negate</option>
						<option value="grayscale">Grayscale</option>
						<option value="blurselective">Selective Blur</option>
						<option value="sepia">Sepia</option>
					</select>
				</th>
			</tr>';

			// Simple Photon Photos - Brightness
			$form_fields["spp-brightness"]["tr"] = '<tr class="spp-tr">
				<th valign="top" scope="row" class="label spp-full-width" colspan="2">
					<label for="attachments-'.$post->ID.'-spp-brightness" class="spp-label">
						<span class="alignleft">Adjust Brightness (<a href="'.$photon_api_docs_url.'/#brightness" target="_blank">?</a>)</span>
						<br class="clear">
					</label>
					<input type="text" class="text attachments-spp-brightness spp-input spp-brightness-input" id="attachments-'.$post->ID.'-spp-brightness" name="attachments['.$post->ID.'][spp-brightness]" value="0" autocomplete="off" />
					<div class="spp-slider spp-brightness-slider"></div>
				</th>
			</tr>';

			// Simple Photon Photos - Contrast
			$form_fields["spp-contrast"]["tr"] = '<tr class="spp-tr">
				<th valign="top" scope="row" class="label spp-full-width" colspan="2">
					<label for="attachments-'.$post->ID.'-spp-contrast" class="spp-label">
						<span class="alignleft">Adjust Contrast (<a href="'.$photon_api_docs_url.'/#contrast" target="_blank">?</a>)</span>
						<br class="clear">
					</label>
					<input type="text" class="text attachments-spp-contrast spp-input spp-contrast-input" id="attachments-'.$post->ID.'-spp-contrast" name="attachments['.$post->ID.'][spp-contrast]" value="0" autocomplete="off" />
					<div class="spp-slider spp-contrast-slider"></div>
				</th>
			</tr>';

			// Simple Photon Photos - Colorize
			$form_fields["spp-colorize"]["tr"] = '<tr class="spp-tr">
				<th valign="top" scope="row" class="label spp-full-width" colspan="2">
					<label for="attachments-'.$post->ID.'-spp-colorize" class="spp-label">
						<span class="alignleft">Colorize (RGB) (<a href="'.$photon_api_docs_url.'/#colorize" target="_blank">?</a>)</span>
						<br class="clear">
					</label>
					<input type="text" class="text attachments-spp-colorize-red spp-input spp-colorize-input spp-colorize-red-input" id="attachments-'.$post->ID.'-spp-colorize-red" name="attachments['.$post->ID.'][spp-colorize-red]" value="0" autocomplete="off" />
					<div class="spp-slider spp-colorize-slider spp-colorize-red-slider"></div>
					<input type="text" class="text attachments-spp-colorize-green spp-input spp-colorize-input spp-colorize-green-input" id="attachments-'.$post->ID.'-spp-colorize-green" name="attachments['.$post->ID.'][spp-colorize-green]" value="0" autocomplete="off" />
					<div class="spp-slider spp-colorize-slider spp-colorize-green-slider"></div>
					<input type="text" class="text attachments-spp-colorize-blue spp-input spp-colorize-input spp-colorize-blue-input" id="attachments-'.$post->ID.'-spp-colorize-blue" name="attachments['.$post->ID.'][spp-colorize-blue]" value="0" autocomplete="off" />
					<div class="spp-slider spp-colorize-slider spp-colorize-blue-slider"></div>
				</th>
			</tr>';

			// Simple Photon Photos - Smooth
			$form_fields["spp-smooth"]["tr"] = '<tr class="spp-tr">
				<th valign="top" scope="row" class="label spp-full-width" colspan="2">
					<label for="attachments-'.$post->ID.'-spp-smooth" class="spp-label">
						<span class="alignleft">Smooth? (<a href="'.$photon_api_docs_url.'/#smooth" target="_blank">?</a>)</span>
						<br class="clear">
					</label>
					<input type="text" class="text attachments-spp-smooth spp-input spp-smooth-input" id="attachments-'.$post->ID.'-spp-smooth" name="attachments['.$post->ID.'][spp-smooth]" value="0" autocomplete="off" />
					<div class="spp-slider spp-smooth-slider"></div>
				</th>
			</tr>';

			// Simple Photon Photos - Zoom
			$form_fields["spp-zoom"]["tr"] = '<tr class="spp-tr">
				<th valign="top" scope="row" class="label spp-full-width" colspan="2">
					<label for="attachments-'.$post->ID.'-spp-zoom" class="spp-label">
						<span class="alignleft">Zoom? (<a href="'.$photon_api_docs_url.'/#zoom" target="_blank">?</a>)</span>
						<br class="clear">
					</label>
					<input type="text" class="text attachments-spp-zoom spp-input spp-zoom-input" id="attachments-'.$post->ID.'-spp-zoom" name="attachments['.$post->ID.'][spp-zoom]" value="0" autocomplete="off" />
					<div class="spp-slider spp-zoom-slider"></div>
				</th>
			</tr>';

			return $form_fields;
		}


		/**
		 * This filter saves the fields above as custom post meta (data is used when inserting image into editor)
		 *
		 * @param array $post Attachment (post object)
		 * @param array $attachment Attachment (POST data)
		 * @return array $post Array of attachment (post object)
		 */
		function spp_attachment_fields_to_save( $post, $attachment ) {
			// Bail if the attachment is not an image
			if ( !wp_attachment_is_image( $post['ID'] ) )
				return;

			$attached_image = wp_get_attachment_image_src( $post['ID'], 'full' ); // full size image
			$attachment_width = ( isset( $attachment['spp-width'] ) ) ? intval( $attachment['spp-width'] ): 0;
			$attachment_height = ( isset( $attachment['spp-height'] ) ) ? intval( $attachment['spp-height'] ): 0;

			$photon_api_data = array();


			/*
			 * Dimension parameters - allow resize or fit (not both), and if those aren't set check if width and/or height are set
			 */
			// Simple Photon Photos - Resize
			if ( isset( $attachment['spp-resize'] ) ) {
				// Width & Height
				if( $attachment_width !== 0 && $attachment_width >= 16 && $attachment_width < intval( $attached_image[1] ) && $attachment_height !== 0 && $attachment_height >= 16 && $attachment_height < intval( $attached_image[2] ) )
					$photon_api_data['resize'] = $attachment_width.','.$attachment_height;
				// Width
				else if( ( $attachment_width !== 0 && $attachment_width >= 16 && $attachment_width < intval( $attached_image[1] ) ) )
					$photon_api_data['resize'] = $attachment_width.','.$attached_image[2];
				// Height
				else if( ( $attachment_height !== 0 && $attachment_height >= 16 && $attachment_height < intval( $attached_image[2] ) ) )
					$photon_api_data['resize'] = $attached_image[1].','.$attachment_height;
			}
			// Simple Photon Photos - Fit
			else if ( isset( $attachment['spp-fit'] ) ) {
				// Width & Height
				if( $attachment_width !== 0 && $attachment_width >= 16 && $attachment_width < intval( $attached_image[1] ) && $attachment_height !== 0 && $attachment_height >= 16 && $attachment_height < intval( $attached_image[2] ) )
					$photon_api_data['fit'] = $attachment_width.','.$attachment_height;
				// Width
				else if( ( $attachment_width !== 0 && $attachment_width >= 16 && $attachment_width < intval( $attached_image[1] ) ) )
					$photon_api_data['fit'] = $attachment_width.','.$attached_image[2];
				// Height
				else if( ( $attachment_height !== 0 && $attachment_height >= 16 && $attachment_height < intval( $attached_image[2] ) ) )
					$photon_api_data['fit'] = $attached_image[1].','.$attachment_height;
			}
			else {
				// Simple Photon Photos - Width
				if( isset( $attachment['spp-width'] ) && !empty( $attachment['spp-width'] ) && (int) $attachment['spp-width'] < (int) $attached_image[1] )
					$photon_api_data['w'] = $attachment['spp-width'];

				// Simple Photon Photos - Height
				if( isset( $attachment['spp-height'] ) && !empty( $attachment['spp-height'] ) && (int) $attachment['spp-height'] < (int) $attached_image[2] )
					$photon_api_data['h'] = $attachment['spp-height'];
			}

			// Simple Photon Photos - Ulb
			if ( isset( $attachment['spp-ulb'] ) )
				$photon_api_data['ulb'] = 'true';
			
			// Simple Photon Photos - Filter
			if( isset( $attachment['spp-filter'] ) && !empty( $attachment['spp-filter'] ) )
				$photon_api_data['filter'] = $attachment['spp-filter'];

			// Simple Photon Photos - Brightness
			if( isset( $attachment['spp-brightness'] ) && !empty( $attachment['spp-brightness'] ) && (int) $attachment['spp-brightness'] !== 0 )
				$photon_api_data['brightness'] = $attachment['spp-brightness'];

			// Simple Photon Photos - Contrast
			if( isset( $attachment['spp-contrast'] ) && !empty( $attachment['spp-contrast'] ) && (int) $attachment['spp-contrast'] !== 0 )
				$photon_api_data['contrast'] = $attachment['spp-contrast'];


			// Colorize parameters (lots of sanity checks)
			if( ( isset( $attachment['spp-colorize-red'] ) && !empty( $attachment['spp-colorize-red'] ) && (int) $attachment['spp-colorize-red'] !== 0 ) || ( isset( $attachment['spp-colorize-green'] ) && !empty( $attachment['spp-colorize-green'] ) && (int) $attachment['spp-colorize-green'] !== 0 ) || ( isset( $attachment['spp-colorize-blue'] ) && !empty( $attachment['spp-colorize-blue'] ) && (int) $attachment['spp-colorize-blue'] !== 0 ) )
				$photon_api_data['colorize'] = $attachment['spp-colorize-red'].','.$attachment['spp-colorize-green'].','.$attachment['spp-colorize-blue'];


			// Simple Photon Photos - Smooth
			if( isset( $attachment['spp-smooth'] ) && !empty( $attachment['spp-smooth'] ) && (int) $attachment['spp-smooth'] !== 0 )
				$photon_api_data['smooth'] = $attachment['spp-smooth'];

			// Simple Photon Photos - Zoom
			if( isset( $attachment['spp-zoom'] ) && !empty( $attachment['spp-zoom'] ) && floatval( $attachment['spp-zoom'] ) !== 0.0 )
				$photon_api_data['zoom'] = $attachment['spp-zoom'];


			update_post_meta( $post['ID'], '_simple_photon_photos', $photon_api_data ); // Used to retrieve data before sending to editor

			return $post;
		}


		/**
		 * This filter parses the post meta, determines the GET query string, deletes the post meta,
		 * and replaces the image source with updated GET query string appended to the end of it
		 *
		 * @param string $html HTML string to be inserted into editor
		 * @param int $id Attachment ID
		 * @param unknown_type $caption Caption string?
		 * @param unknown_type $title Title string?
		 * @param unknown_type $align Align string?
		 * @param string $url URL for anchor
		 * @param unknown_type $size Size string?
		 * @param string $alt Alternate text
		 * @return string $html HTML to be inserted into post
		 */
		function spp_image_send_to_editor($html, $id, $caption, $title, $align, $url, $size, $alt) {
			// Check image source
			if ( false != preg_match( '#(<a.+?href=["|\'](.+?)["|\'].+?>\s*)?(<img.+?src=["|\'](.+?)["|\'].+?/?>){1}(\s*</a>)?#i', $html, $image ) ) {
				$photon_api_data = get_post_meta( $id, '_simple_photon_photos' );
				delete_post_meta( $id, '_simple_photon_photos' ); // Remove post meta since it isn't needed anymore	

				// If the user has set any values (other than default)
				if ( !empty( $photon_api_data[0] ) ) {
					$photon_query_string = '?';
					$photon_get_count = 0;
					foreach ( $photon_api_data[0] as $key => $value ) {
						// We're not at the last key
						if ( $photon_get_count !== ( count( $photon_api_data[0] ) - 1 ) )
							$photon_query_string .= $key.'='.$value.'&';
						else // Last key
							$photon_query_string .= $key.'='.$value;

						$photon_get_count++;
					}

					// Add Photon GET query (images[4] is URL match from above)
					return str_replace( $image[4], $image[4].$photon_query_string, $html );
				}

				// Return un-alterted HTML (no Photon GET parameters)
				return $html;
			}
			else // No image source found
				return $html;			
		}


		/**
		 * This action filter saves the fields above as custom post meta (data is used when inserting image into editor)
		 *
		 * @param array $post Attachment (post object)
		 * @param array $attachment Attachment (POST data)
		 * @return array $post Array of attachment (post object)
		 */
		function spp_admin_print_scripts() {
			global $wp_version, $post;
			$current_screen = get_current_screen(); // Used for < 3.5

			// If the user has 3.4.2 or less, enqueue a different script
			if ( version_compare( $wp_version, '3.4.2', '<=' ) ) {
				wp_enqueue_script( 'simple-photon-photos-script-3.4', plugins_url( '/js/simple-photon-photos-3.4.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse', 'jquery-ui-slider' ), '0.5', true );
				wp_enqueue_style( 'simple-photon-photos-styles-3.4', plugins_url( '/css/simple-photon-photos-3.4.css', __FILE__ ) );
			}
			else {
				wp_enqueue_script( 'simple-photon-photos-script', plugins_url( '/js/simple-photon-photos.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse', 'jquery-ui-slider' ), '0.5', true );
				wp_enqueue_style( 'simple-photon-photos-styles', plugins_url( '/css/simple-photon-photos.css', __FILE__ ) );
			}

			// Hide all Simple Photon Photos table rows on attachment view
			if ( ( version_compare( $wp_version, '3.5', '>=' ) && $post->post_type === 'attachment' ) || $current_screen->base === 'media' || $current_screen->base === 'media-new' )
				wp_enqueue_script( 'simple-photon-photos-hide', plugins_url( '/js/simple-photon-photos-hide.js', __FILE__ ), array( 'jquery' ), '0.5', true );


			wp_enqueue_style( 'wp-jquery-ui-custom', 'http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css' ); // Custom jQuery-UI CSS due to core not working correctly

		}


		/**
		 * This filter requires Jetpack and adds the necessary arguments to the array. It is used for Photon URL generation
		 * with the correct arguments.
		 *
		 * @param array $args Default arguments, if any
		 * @param array $image_args Image arguments such as tag, src, src_orig, width, and height
		 * @return array $args Array of arguments
		 */
		function spp_jetpack_photon_post_image_args($args, $image_args) {
			// Parse the image url (change encoded ampersands (&#038) to regular ampersands)
			$image_query = parse_url( str_replace('&#038;', '&', $image_args['src'] ), PHP_URL_QUERY ); // Just the query

			if ( $image_query ) // Do we have an image query?
				parse_str( $image_query, $args ); // Parse query into $args

			return $args;
		}
	}

}
$SimplePhotonPhotos = new SimplePhotonPhotos();

/**
 * Include all required files and instantiate classes if necessary (see individual includes)
 */
include_once plugin_dir_path( __FILE__ ).'/includes/simple-photon-photos-init.php'; // Plugin activation/deactivation/uninstall
