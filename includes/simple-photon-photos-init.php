<?php
/**
 * This file handles the activation/deactivation/uninstall callbacks for the plugin
 */
if ( !class_exists( 'SimplePhotonPhotosInit' ) ) {
	class SimplePhotonPhotosInit {

		function __construct( ) {
			// Handle our custom plugin ctivation message
			add_action( 'admin_notices', array( $this, 'spp_activation_messages' ) );
		}

		/**
		 * Plugin preparation/activation
		 * @param unknown_type $activation_flag
		 */
		function spp_activation( $activation_flag ) {
			$required_jetpack_ver = '2.0'; // Required Jetpack version (2.0 is first version to include Photon API Module)

			// Get Jetpack plugin data
			$jetpack_data = get_plugin_data( plugins_url( 'jetpack/jetpack.php' ) );

			// Necessary checks to verify that Jetpack is installed, activated, and connected with WordPress.com to prevent abuse to TOS @link http://en.wordpress.com/tos/
			if ( !isset( $jetpack_data['Name'] ) ) // Jetpack is not installed
				SimplePhotonPhotosInit::prevent_activation( '<strong><span style="color: #f00;">Important:</span> Simple Photon Photos</strong> requires <a href="'.admin_url( 'plugin-install.php?tab=search&s=Jetpack+by+WordPress.com' ).'" target="_parent">Jetpack by WordPress.com</a>. Please install the latest version.', true );

			else if ( !class_exists( 'Jetpack' ) ) // Jetpack is not activated
				SimplePhotonPhotosInit::prevent_activation( '<strong><span style="color: #f00;">Important:</span> Simple Photon Photos</strong> requires Jetpack by WordPress.com. Please activate Jetpack by WordPress.com.', true );

			else if ( defined( 'JETPACK__VERSION' ) && version_compare( JETPACK__VERSION, 2.0, '<' ) ) // Jetpack is activated but Photon API Module doesn't exist
				SimplePhotonPhotosInit::prevent_activation( '<strong><span style="color: #f00;">Important:</span> Simple Photon Photos</strong> requires the Photon API Module. Please update to the latest version of Jetpack by WordPress.com.', true );

			else if ( method_exists( 'Jetpack', 'get_active_modules' ) && !in_array( 'photon', Jetpack::get_active_modules() ) ) // Jetpack is activated but Photon API Module is not active (site is not connected to WordPress.com?)
				SimplePhotonPhotosInit::prevent_activation( '<strong><span style="color: #f00;">Important:</span> Simple Photon Photos</strong> requires that your site is connected to WordPress.com via Jetpack. Please connect your website to WordPress.com on the <a href="'.admin_url( 'admin.php?page=jetpack' ).'" target="_parent">Jetpack settings page</a>. If your site is already connected to WordPress.com, please enable the Photon API.' );
			//else if ( !get_option( 'jetpack_user_token' ) || !get_option( 'jetpack_master_user' ) ) // Check to see if Jetpack is connected with WordPress.com?
				//update_option( 'spp-display-jetpack-not-connected-message', 1 );

			else // Activate plugin
				update_option( 'spp-display-activation-message', 1 ); // Set flag to display custom activation message
		}

		/**
		 * This function displays a fatal error on activation if the checks don't pass
		 * @param string $error_message HTML error message 
		 * @param bool $center_message Should the message be centered via CSS?
		 */
		function prevent_activation( $error_message, $center_message = false ) {
	?>
			<!DOCTYPE html>
			<html>
				<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<style type="text/css">
				body {
					margin: 0;
					padding: 0;
				}
				p {
					margin: 20px 0 0;
					padding: 0;
					font-family: "Lucida Grande", Verdana, Arial, "Bitstream Vera Sans", sans-serif;
					font-size: 16px;
					<?php echo ( $center_message ) ? 'text-align: center;' : false; ?>
				}
				a {
					color: #21759b;
					text-decoration: none;
				}
				a:hover {
					color: #d54e21;
				}
			</style>
			<body>
				<p><?php echo $error_message; ?></p>
			</body>
			</html>
	<?php
			// Deactivate Simple Photon Photos plugin
			$plugins = get_option( 'active_plugins' ); // Active plugins
			$spp_basename = 'simple-photon-photos/SimplePhotonPhotos.php';

			foreach ( $plugins as $i => $plugin )
				if ( $plugin === $spp_basename ) {
					$plugins[$i] = false;

					// Update active plugins option (this effectively deactivates the plugin)
					update_option( 'active_plugins', array_filter( $plugins ) );
					break;
				}

			exit;
		}


		/**
		 * This function displays a custom activation message based on options
		 */
		function spp_activation_messages() {
			// Activation Message
			if ( get_option( 'spp-display-activation-message' ) ) {
			?>
				<div class="spp-activated">
					<p><strong>Simple Photon Photos</strong> features can now be found in the Add Media Panel underneath Attachment Details. Let us know how we're doing <a href="http://simplephotonphotos.com" target="_blank">here</a>.</p>
				</div>
				<img src="http://simplephotonphotos.com/wp-content/plugins/simple-photon-photos/images/intro.png?123" alt="Simple Photon Photos" class="spp-intro-photo" />
			<?php
				delete_option( 'spp-display-activation-message' ); // Remove activation flag so message is not displayed on any other pages
			}
		}
	}
}

$SimplePhotonPhotosInit = new SimplePhotonPhotosInit();