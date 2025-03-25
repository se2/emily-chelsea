<?php
/**
 * Manages the WordPress updater for the plugin.
 *
 * @package    CrossPeakSoftware
 * @subpackage Updater
 */

namespace CrossPeakSoftware\Updater;

if ( ! class_exists( '\CrossPeakSoftware\EDD_SL_Plugin_Updater', false ) ) {
	require __DIR__ . '/EDD_SL_Plugin_Updater.php';
}

/**
 * Get current plugins for CrossPeak Software.
 *
 * @return array<string,array<string,string>>
 */
function get_plugins() {
	return apply_filters( 'crosspeak_software_plugins', array() );
}

/**
 * Register the plugin updater.
 *
 * @return void
 */
function plugin_updater() {
	// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
	$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
	if ( ! $doing_cron && ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$plugins = get_plugins();

	foreach ( $plugins as $plugin ) {
		$license_key = trim( (string) get_option( 'crosspeak_software_' . $plugin['slug'] . '_license_key' ) );
		// setup the updater.
		new \CrossPeakSoftware\EDD_SL_Plugin_Updater(
			'https://www.crosspeaksoftware.com',
			$plugin['file'],
			array(
				'version' => $plugin['version'],
				'license' => $license_key,
				'item_id' => $plugin['id'],
				'author'  => 'CrossPeak Software',
				'beta'    => false,
			)
		);
		$action = plugin_basename( $plugin['file'] );
		add_filter( 'plugin_action_links_' . $action, __NAMESPACE__ . '\add_license_settings_link', 10, 1 );
		if ( empty( $license_key ) ) {
			add_action( 'in_plugin_update_message-' . $action, __NAMESPACE__ . '\add_license_add_notice' );
		} else {
			$status = (string) get_option( 'crosspeak_software_' . $plugin['slug'] . '_license_status' );
			if ( 'valid' !== $status ) {
				add_action( 'in_plugin_update_message-' . $action, __NAMESPACE__ . '\add_license_update_notice' );
			}
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\plugin_updater' );

/**
 * Add settings link to plugin actions
 *
 * @param array<string> $links Current links.
 * @return array<string>
 */
function add_license_settings_link( $links ) {
	$settings_link = '<a href="plugins.php?page=crosspeak-software-license">' . esc_html__( 'License Settings', 'crosspeak-software' ) . '</a>';
	array_push( $links, $settings_link );
	return $links;
}

/**
 * Add message when update available and can't update.
 *
 * @return void
 */
function add_license_add_notice() {
	$license_page_url = add_query_arg(
		array(
			'page' => 'crosspeak-software-license',
		),
		admin_url( 'plugins.php' )
	);

	printf(
		wp_kses(
		/* translators: 1: CrossPeak Software license page */
			__( ' <a href="%1$s">Enter your license key</a> to upgrade.', 'crosspeak-software' ),
			array(
				'a' => array(
					'href'  => array(),
					'class' => array(),
				),
			)
		),
		esc_url( $license_page_url )
	);
}

/**
 * Add message when update available and can't update.
 *
 * @return void
 */
function add_license_update_notice() {
	$license_page_url = add_query_arg(
		array(
			'page' => 'crosspeak-software-license',
		),
		admin_url( 'plugins.php' )
	);

	printf(
		wp_kses(
		/* translators: 1: CrossPeak Software license page */
			__( ' <a href="%1$s">Update your license key</a> to upgrade.', 'crosspeak-software' ),
			array(
				'a' => array(
					'href'  => array(),
					'class' => array(),
				),
			)
		),
		esc_url( $license_page_url )
	);
}

/**
 * Adds the plugin license page to the admin menu.
 *
 * @return void
 */
function license_menu() {
	add_plugins_page(
		'CrossPeak Software',
		'CrossPeak Software',
		'manage_options',
		'crosspeak-software-license',
		__NAMESPACE__ . '\license_page'
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\license_menu' );

/**
 * Display the license page.
 *
 * @return void
 */
function license_page() {
	add_settings_section(
		'crosspeak_software_license',
		'CrossPeak Software',
		__NAMESPACE__ . '\license_key_settings_section',
		'crosspeak-software-license'
	);

	foreach ( get_plugins() as $plugin ) {
		add_settings_field(
			'crosspeak_software_' . $plugin['slug'] . '_license_key',
			'<label for="crosspeak_software_license_key">' . esc_html( $plugin['name'] ) . '</label>',
			__NAMESPACE__ . '\license_key_settings_field',
			'crosspeak-software-license',
			'crosspeak_software_license',
			$plugin
		);
	}
	?>
	<div class="wrap">
		<h2><?php esc_html_e( 'CrossPeak Software Licenses', 'crosspeak-software' ); ?></h2>
		<p>
		<?php
		/* translators: The link to the site. */
		printf( __( 'See your <a href="%1s" target="_blank">Account on crosspeaksoftware.com</a> to access your licenses.', 'crosspeak-software' ), 'https://www.crosspeaksoftware.com/account/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		<form method="post" action="plugins.php">

			<?php
			do_settings_sections( 'crosspeak-software-license' );
			settings_fields( 'crosspeak_software_license' );
			?>

		</form>
	<?php
}

/**
 * Adds content to the settings section.
 *
 * @return void
 */
function license_key_settings_section() {
	wp_nonce_field( 'crosspeak_software_nonce', __NAMESPACE__ . '\nonce' );
}

/**
 * Outputs the license key settings field.
 *
 * @param array<string,string> $plugin The plugin to show settings for.
 * @return void
 */
function license_key_settings_field( $plugin ) {
	$license = (string) get_option( 'crosspeak_software_' . $plugin['slug'] . '_license_key' );
	$status  = (string) get_option( 'crosspeak_software_' . $plugin['slug'] . '_license_status' );
	if ( 'valid' === $status ) {
		?>
		<p class="description">
			<mark class="yes" style="color: #7ad03a;background: transparent none;">
				<span class="dashicons dashicons-yes"></span>
			</mark>
			<?php esc_html_e( 'License Active', 'crosspeak-software' ); ?>
		</p>
		<?php
	} else {
		?>
		<p class="description"><?php esc_html_e( 'License Inactive', 'crosspeak-software' ); ?></p>
		<?php
	}
	?>
	<p class="description"><?php esc_html_e( 'Enter your license key.', 'crosspeak-software' ); ?></p>
	<?php
	printf(
		'<input type="text" class="regular-text" id="crosspeak_software_' . esc_attr( $plugin['slug'] ) . '_license_key" name="crosspeak_software_' . esc_attr( $plugin['slug'] ) . '_license_key" value="%s" />',
		esc_attr( $license )
	);
	$button = array(
		'name'  => 'crosspeak_software_' . esc_attr( $plugin['slug'] ) . '_license_deactivate',
		'label' => __( 'Deactivate License', 'crosspeak-software' ),
	);
	if ( 'valid' !== $status ) {
		$button = array(
			'name'  => 'crosspeak_software_' . esc_attr( $plugin['slug'] ) . '_license_activate',
			'label' => __( 'Activate License', 'crosspeak-software' ),
		);
	}

	?>
	<input type="submit" class="button-secondary" name="<?php echo esc_attr( $button['name'] ); ?>" value="<?php echo esc_attr( $button['label'] ); ?>"/>
	<?php
}

/**
 * Registers the license key setting in the options table.
 *
 * @return void
 */
function register_option() {
	foreach ( get_plugins() as $plugin ) {
		register_setting(
			'crosspeak_software_' . $plugin['slug'] . '_license',
			__NAMESPACE__ . '\license_key',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\register_option' );

/**
 * Activates the license key.
 *
 * @return void
 */
function activate_license() {
	$plugins         = get_plugins();
	$have_activation = false;
	foreach ( $plugins as $key => $plugin ) {
		// listen for our activate button to be clicked.
		if ( isset( $_POST[ 'crosspeak_software_' . $plugin['slug'] . '_license_activate' ] ) ) {
			$have_activation               = true;
			$plugins[ $key ]['activation'] = true;
		} else {
			$plugins[ $key ]['activation'] = false;
		}
	}

	// No activation required.
	if ( ! $have_activation ) {
		return;
	}

	// run a quick security check.
	if ( ! check_admin_referer( 'crosspeak_software_nonce', __NAMESPACE__ . '\nonce' ) ) {
		return; // get out if we didn't click the Activate button.
	}

	// Check permission.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	foreach ( $plugins as $key => $plugin ) {
		if ( ! $plugin['activation'] ) {
			continue;
		}
		$slug = 'crosspeak_software_' . $plugin['slug'] . '_license_key';
		// retrieve the license from the database.
		$license = trim( get_option( $slug ) );
		if ( ! empty( $_POST[ $slug ] ) ) {
			$license = sanitize_text_field( $_POST[ $slug ] );
		}
		if ( ! $license ) {
			$redirect = add_query_arg(
				array(
					'page'             => 'crosspeak-software-license',
					'cp_sl_activation' => 'false',
					/* translators: %s: Plugin name. */
					'message'          => rawurlencode( sprintf( __( '%s license activation key required.', 'crosspeak-software' ), $plugin['name'] ) ),
				),
				admin_url( 'plugins.php' )
			);

			wp_safe_redirect( $redirect );
			exit();
		}

		// data to send in our API request.
		$api_params = array(
			'edd_action'  => 'activate_license',
			'license'     => $license,
			'item_id'     => $plugin['id'],
			'item_name'   => rawurlencode( (string) $plugin['name'] ),
			'url'         => home_url(),
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);

		// Call the custom API.
		$response = wp_remote_post(
			'https://www.crosspeaksoftware.com',
			array(
				'timeout' => 15,
				'body'    => $api_params,
			)
		);

			// make sure the response came back okay.
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				/* translators: %s: WordPress error message */
				$message = sprintf( __( 'An error occurred activating your license, please try again. WordPress Error: %s', 'crosspeak-software' ), $response->get_error_message() );
			} else {
				$response_code    = wp_remote_retrieve_response_code( $response );
				$response_message = wp_remote_retrieve_response_message( $response );
				/* translators: %1$s: response code, %2$s: response message */
				$message = sprintf( __( 'An error occurred activating your license, please try again. Got response code: %1$s and message: %2$s', 'crosspeak-software' ), $response_code, $response_message );
			}
		} else {
			$body         = wp_remote_retrieve_body( $response );
			$license_data = json_decode( $body );

			if ( false === $license_data->success ) {

				switch ( $license_data->error ) {

					case 'expired':
						$message = sprintf(
							/* translators: the license key expiration date */
							__( 'Your license key expired on %s.', 'crosspeak-software' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) ) // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
						);
						break;

					case 'disabled':
					case 'revoked':
						$message = __( 'Your license key has been disabled.', 'crosspeak-software' );
						break;

					case 'missing':
						$message = __( 'Invalid license.', 'crosspeak-software' );
						break;

					case 'invalid':
					case 'site_inactive':
						$message = __( 'Your license is not active for this URL.', 'crosspeak-software' );
						break;

					case 'item_name_mismatch':
						/* translators: the plugin name */
						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'crosspeak-software' ), $plugin['name'] );
						break;

					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'crosspeak-software' );
						break;

					default:
						/* translators: %s: response from server */
						$message = sprintf( __( 'An error occurred activating your license, please try again. Received from server: %s', 'crosspeak-software' ), $body );
						break;
				}
			} else {
				// $license_data->license will be either "valid" or "invalid"
				if ( 'valid' === $license_data->license ) {
					update_option( $slug, $license );
				}
				update_option( 'crosspeak_software_' . $plugin['slug'] . '_license_status', $license_data->license );
			}
		}

		// Check if anything passed on a message constituting a failure.
		if ( ! empty( $message ) ) {
			$redirect = add_query_arg(
				array(
					'page'             => 'crosspeak-software-license',
					'cp_sl_activation' => 'false',
					'message'          => rawurlencode( $message ),
				),
				admin_url( 'plugins.php' )
			);

			wp_safe_redirect( $redirect );
			exit();
		}

		$redirect = add_query_arg(
			array(
				'page'             => 'crosspeak-software-license',
				'cp_sl_activation' => 'true',
				/* translators: %s: Plugin name. */
				'message'          => rawurlencode( sprintf( __( '%s has been activated successfully.', 'crosspeak-software' ), $plugin['name'] ) ),
			),
			admin_url( 'plugins.php' )
		);

		wp_safe_redirect( $redirect );
		exit();
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\activate_license' );

/**
 * Deactivates the license key.
 * This will decrease the site count.
 *
 * @return void
 */
function deactivate_license() {
	$needs_deactivation = false;
	$plugins            = get_plugins();
	foreach ( $plugins as $plugin ) {
		// listen for our activate button to be clicked.
		if ( isset( $_POST[ 'crosspeak_software_' . esc_attr( $plugin['slug'] ) . '_license_deactivate' ] ) ) {
			$needs_deactivation = true;
		}
	}
	if ( ! $needs_deactivation ) {
		return;
	}

	// run a quick security check.
	if ( ! check_admin_referer( 'crosspeak_software_nonce', __NAMESPACE__ . '\nonce' ) ) {
		return; // get out if we didn't click the Deactivate button.
	}

	foreach ( $plugins as $plugin ) {
		// listen for our activate button to be clicked.
		if ( isset( $_POST[ 'crosspeak_software_' . esc_attr( $plugin['slug'] ) . '_license_deactivate' ] ) ) {

			// retrieve the license from the database.
			$license_key_slug = 'crosspeak_software_' . esc_attr( $plugin['slug'] ) . '_license_key';
			$license          = trim( get_option( $license_key_slug ) );

			// data to send in our API request.
			$api_params = array(
				'edd_action'  => 'deactivate_license',
				'license'     => $license,
				'item_id'     => $plugin['id'],
				'item_name'   => rawurlencode( $plugin['name'] ), // the name of our product in EDD.
				'url'         => home_url(),
				'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
			);

			// Call the custom API.
			$response = wp_remote_post(
				'https://www.crosspeaksoftware.com',
				array(
					'timeout' => 15,
					'body'    => $api_params,
				)
			);

			// make sure the response came back okay.
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				if ( is_wp_error( $response ) ) {
					/* translators: %s: WordPress error message */
					$message = sprintf( __( 'An error occurred deactivating your license, please try again. WordPress Error: %s', 'crosspeak-software' ), $response->get_error_message() );
				} else {
					$response_code    = wp_remote_retrieve_response_code( $response );
					$response_message = wp_remote_retrieve_response_message( $response );
					/* translators: %1$s: response code, %2$s: response message */
					$message = sprintf( __( 'An error occurred deactivating your license, please try again. Got response code: %1$s and message: %2$s', 'crosspeak-software' ), $response_code, $response_message );
				}

				$redirect = add_query_arg(
					array(
						'page'             => 'crosspeak-software-license',
						'cp_sl_activation' => 'false',
						'message'          => rawurlencode( $message ),
					),
					admin_url( 'plugins.php' )
				);

				wp_safe_redirect( $redirect );
				exit();
			}

			// decode the license data.
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed".
			if ( 'deactivated' === $license_data->license ) {
				delete_option( 'crosspeak_software_' . $plugin['slug'] . '_license_status' );
				delete_option( $license_key_slug );
				$redirect = add_query_arg(
					array(
						'page'             => 'crosspeak-software-license',
						'cp_sl_activation' => 'true',
						/* translators: %s: Plugin name */
						'message'          => rawurlencode( sprintf( __( '%s has been deactivated successfully.', 'crosspeak-software' ), $plugin['name'] ) ),
					),
					admin_url( 'plugins.php' )
				);

				wp_safe_redirect( $redirect );
				exit();
			}
		}
	}
	wp_safe_redirect( admin_url( 'plugins.php?page=crosspeak-software-license' ) );
	exit();
}
add_action( 'admin_init', __NAMESPACE__ . '\deactivate_license' );

/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 *
 * @return void
 */
function admin_notices() {
	if ( isset( $_GET['cp_sl_activation'] ) && ! empty( $_GET['message'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$message = urldecode( $_GET['message'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		switch ( $_GET['cp_sl_activation'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			case 'false':
				?>
				<div class="notice notice-error">
					<p><?php echo esc_html( $message ); ?></p>
				</div>
				<?php
				break;

			case 'true':
			default:
				?>
				<div class="notice notice-success">
					<p><?php echo esc_html( $message ); ?></p>
				</div>
				<?php
				break;

		}
	}
}
add_action( 'admin_notices', __NAMESPACE__ . '\admin_notices' );
