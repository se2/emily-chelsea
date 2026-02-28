<?php
/**
 * Gravity Forms Entries Page
 *
 * @package gf_klaviyo
 */

namespace CrossPeakSoftware\GravityForms\Klaviyo;

use GFAPI;

/**
 * Add a new meta box for Klaviyo on the individual entry page.
 *
 * @param array $meta_boxes Meta box arguments.
 * @param array $entry Entry currently being processed.
 * @param array $form Form currently being processed.
 * @return array
 */
function add_klaviyo_meta_box( $meta_boxes, $entry, $form ) {

	if ( ! isset( $meta_boxes['klaviyo'] ) ) {
		$meta_boxes['klaviyo'] = array(
			'title'         => esc_html__( 'Klaviyo Feeds', 'gravityforms-klaviyo' ),
			'callback'      => __NAMESPACE__ . '\meta_box_klaviyo_details',
			'context'       => 'side',
			'callback_args' => array( $entry, $form ),
		);
	}

	return $meta_boxes;
}
add_filter( 'gform_entry_detail_meta_boxes', __NAMESPACE__ . '\add_klaviyo_meta_box', 10, 3 );

/**
 * Add a new meta box for Klaviyo on the individual entry page.
 *
 * @param array $args Meta box arguments.
 */
function meta_box_klaviyo_details( $args ) {

	$entry = $args['entry'];
	$form  = $args['form'];
	$feeds = GFAPI::get_feeds( null, $form['id'], 'gravityforms-klaviyo', null );

	if ( is_wp_error( $feeds ) ) {
		$setup_url = get_admin_url( null, 'admin.php?subview=gravityforms-klaviyo&page=gf_edit_forms&view=settings&id=' . $form['id'] );
		echo '<p>This form has no active Klaviyo feeds. <a href="' . esc_url( $setup_url ) . '">Set one up</a>.</p>';
		return;
	}
	?>
	<div class="detail-view-klaviyo">
		<div class="message"></div>
		<table class="klaviyo-feeds-table">
			<?php
			foreach ( $feeds as $feed ) {
				$resubmit_nonce = wp_create_nonce( 'gfklav_resubmit_feed' );
				$active_class   = ( $feed['is_active'] ? 'gf-klav-status-active' : 'gf-klav-status-inactive' );
				$feed_admin_url = get_admin_url( null, 'admin.php?subview=gravityforms-klaviyo&page=gf_edit_forms&view=settings&id=' . $form['id'] . '&fid=' . $feed['id'] );
				?>
					<tr>
						<td class="gf-klav-status <?php echo esc_attr( $active_class ); ?>">
							<span title="<?php echo $feed['is_active'] ? esc_html_e( 'Feed active', 'gravityforms-klaviyo' ) : esc_html_e( 'Feed inactive', 'gravityforms-klaviyo' ); ?>"></span>
						</td>
						<td>
							<?php echo esc_html( $feed['meta']['feed_name'] ); ?>
						</td>
						<td><a href="<?php echo esc_url( $feed_admin_url ); ?>">Edit</a></td>
					</tr>
					<tr>
						<td colspan="4" id="klaviyo_resubmit_<?php echo esc_attr( $feed['id'] ); ?>"><button class="gf_klaviyo_resubmit button" data-entryid="<?php echo esc_attr( $entry['id'] ); ?>" data-formid="<?php echo esc_attr( $form['id'] ); ?>" data-feedid="<?php echo esc_attr( $feed['id'] ); ?>" data-nonce="<?php echo esc_attr( $resubmit_nonce ); ?>">Resubmit</button></td>
					</tr>
					<?php
					if ( \as_has_scheduled_action(
						'cp_gf_klaviyo_resubmit_feed',
						array(
							'feed_id'  => $feed['id'],
							'entry_id' => $entry['id'],
							'form_id'  => $form['id'],
						),
						'cp_gf_klaviyo'
					) ) {
						?>
						<tr>
							<td colspan="4">
								<?php esc_html_e( 'This entry is queued for submission.', 'gravityforms-klaviyo' ); ?>
							</td>
						</tr>
						<?php
					}
					?>
					<tr id="klaviyo_please_wait_container_<?php echo esc_attr( $feed['id'] ); ?>" style="display:none;">
						<td colspan="4">
							<i class='gficon-gravityforms-spinner-icon gficon-spin'></i> <?php esc_html_e( 'Resubmitting...', 'gravityforms-klaviyo' ); ?>
						</td>
					</tr>
				<?php
			}
			?>
		</table>
	</div>

	<?php
}

/**
 * Resend the feed subscription to Klaviyo.
 */
function resubmit_feed() {

	check_ajax_referer( 'gfklav_resubmit_feed', 'nonce' );

	$feed_id  = rgpost( 'feedId' );
	$entry_id = rgpost( 'entryId' );
	$form_id  = rgpost( 'formId' );
	$feed     = GFAPI::get_feed( $feed_id );
	$entry    = GFAPI::get_entry( $entry_id );
	$form     = GFAPI::get_form( $form_id );

	/* Send submission to Klaviyo */
	$submission = gf_klaviyo_feed_addon();
	if ( $submission ) {
		$submission->process_feed( $feed, $entry, $form );

		echo wp_json_encode(
			array(
				'success' => true,
				'message' => __( 'Successfully attempted submission. Check notes for status.', 'gravityforms-klaviyo' ),
			)
		);
	} else {
		echo wp_json_encode(
			array(
				'success' => false,
				'message' => __( 'Error resubmitting to Klaviyo', 'gravityforms-klaviyo' ),
			)
		);
	}
	die();
}
add_action( 'wp_ajax_gfklav_resubmit_feed', __NAMESPACE__ . '\resubmit_feed' );

/**
 * Enqueue admin scripts and styles for the entries page.
 */
function admin_scripts_styles() {
	$current_page = get_current_screen();

	// Strip numbers from page id.
	$page_id = preg_replace( '/[^a-zA-Z_]/', '', $current_page->id );

	if ( str_contains( $page_id, 'page_gf_entries' ) ) {
		wp_enqueue_style( 'gravityforms-klaviyo-admin', plugins_url( basename( dirname( __DIR__ ) ) . '/assets/css/admin.css' ), array(), CP_GF_KLAVIYO_FEED_VERSION );
		wp_enqueue_script( 'gravityforms-klaviyo-admin', plugins_url( basename( dirname( __DIR__ ) ) . '/assets/js/admin.js' ), array( 'jquery' ), CP_GF_KLAVIYO_FEED_VERSION, true );
		wp_localize_script(
			'gravityforms-klaviyo-admin',
			'cp_gf_klaviyo',
			array(
				'resend_nonce'             => wp_create_nonce( 'cp_gf_resend_klaviyo' ),
				'filter'                   => rgget( 'filter' ),
				'search'                   => rgget( 's' ),
				'operator'                 => rgget( 'operator' ),
				'fieldId'                  => rgget( 'field_id' ),
				'formId'                   => rgget( 'id' ),
				'no_entries_error_message' => __( 'Please select at least one entry..', 'gravityforms' ),
				'resend_button_text'       => __( 'Resend to Klaviyo', 'gravityforms-klaviyo' ),
				'resend_error_message'     => __( 'You must select at least one Klaviyo feed to resend.', 'gravityforms-klaviyo' ),
			)
		);
	}
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_scripts_styles' );

/**
 * Add a bulk action to resubmit to Klaviyo.
 *
 * @param array $actions Bulk actions.
 * @return array
 */
function gform_entry_list_bulk_actions( $actions ) {
	if ( isset( $actions['resend_notifications'] ) ) {
		// Insert resend to klaviyo after resend notifications.
		$offset  = array_search( 'resend_notifications', array_keys( $actions ), true );
		$first   = array_slice( $actions, 0, $offset + 1, true );
		$last    = array_slice( $actions, $offset + 1, null, true );
		$actions = array_merge(
			$first,
			array(
				'resend_klaviyo' => esc_html__( 'Resend to Klaviyo', 'gravityforms-klaviyo' ),
			),
			$last
		);
	}
	return $actions;
}
add_filter( 'gform_entry_list_bulk_actions', __NAMESPACE__ . '\gform_entry_list_bulk_actions', 10, 1 );


/**
 * Handle the bulk action to resend to Klaviyo.
 */
function bulk_resend_klaviyo() {
	check_admin_referer( 'cp_gf_resend_klaviyo', 'cp_gf_klaviyo_nonce' );

	$feeds = json_decode( rgpost( 'feeds' ) );
	if ( ! is_array( $feeds ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'message' => __( 'No Klaviyo Feeds have been selected. Please select a Klaviyo Feeds to be processed.', 'gravityforms-klaviyo' ),
			)
		);
		die();
	}

	/**
	 * Get leeds from the post data.
	 * From gravityforms.php in the resend_notifications() function.
	 */
	$form_id = absint( rgpost( 'formId' ) );
	$leads   = rgpost( 'leadIds' ); // may be a single ID or an array of IDs.
	if ( 0 === $leads || '0' === $leads ) {
		// get all the lead ids for the current filter / search.
		$filter = rgpost( 'filter' );
		$search = rgpost( 'search' );
		$star   = 'star' === $filter ? 1 : null;
		$read   = 'unread' === $filter ? 0 : null;
		$status = in_array( $filter, array( 'trash', 'spam' ), true ) ? $filter : 'active';

		$search_criteria['status'] = $status;

		if ( $star ) {
			$search_criteria['field_filters'][] = array(
				'key'   => 'is_starred',
				'value' => (bool) $star,
			);
		}
		if ( ! is_null( $read ) ) {
			$search_criteria['field_filters'][] = array(
				'key'   => 'is_read',
				'value' => (bool) $read,
			);
		}

		$search_field_id = rgpost( 'fieldId' );

		if ( isset( $_POST['fieldId'] ) && '' !== $_POST['fieldId'] ) {
			$key            = $search_field_id;
			$val            = $search;
			$strpos_row_key = strpos( $search_field_id, '|' );
			if ( false !== $strpos_row_key ) { // multi-row.
				$key_array = explode( '|', $search_field_id );
				$key       = $key_array[0];
				$val       = $key_array[1] . ':' . $val;
			}
			$search_criteria['field_filters'][] = array(
				'key'      => $key,
				'operator' => rgempty( 'operator', $_POST ) ? 'is' : rgpost( 'operator' ),
				'value'    => $val,
			);
		}

		$hash = substr( wp_hash( 'cp_gf_klaviyo_queue_leads_' . time() . wp_rand(), 'nonce' ), -12, 10 );
		update_option(
			'cp_gf_klaviyo_queue_leads_' . $hash,
			array(
				'feeds'                => $feeds,
				'search_criteria'      => $search_criteria,
				'search_criteria_page' => 0,
				'form_id'              => $form_id,
			),
			'no'
		);
		// Queue to send later.
		\as_enqueue_async_action(
			'cp_gf_klaviyo_queue_leads',
			array(
				'hash' => $hash,
			),
			'cp_gf_klaviyo'
		);

		$message = esc_html__( 'All entries were queued for processing to send to Klaviyo.', 'gravityforms-klaviyo' );
		echo wp_json_encode(
			array(
				'success' => true,
				'message' => $message,
			)
		);
		die();
	}

	$leads = ! is_array( $leads ) ? array( $leads ) : $leads;

	// If less than 5 leads, send in real time.
	if ( count( $leads ) <= 5 ) {
		$feed_objects = array();
		foreach ( $feeds as $feed_id ) {
			$feed_objects[] = GFAPI::get_feed( $feed_id );
		}

		$form = GFAPI::get_form( $form_id );

		/* Send submission to Klaviyo */
		$submission = gf_klaviyo_feed_addon();
		if ( $submission ) {
			foreach ( $leads as $lead_id ) {
				foreach ( $feed_objects as $feed ) {
					$entry = GFAPI::get_entry( $lead_id );
					$submission->process_feed( $feed, $entry, $form );
				}
			}
		}
		$message = sprintf(
			ngettext(
				/* translators: %d: Number of entries. */
				esc_html__( '%d entry was resent to Klaviyo.', 'gravityforms-klaviyo' ),
				/* translators: %d: Number of entries. */
					esc_html__( '%d entries were resent to Klaviyo.', 'gravityforms-klaviyo' ),
				count( $leads )
			),
			count( $leads )
		);

	} else {
		$hash = substr( wp_hash( 'cp_gf_klaviyo_queue_leads_' . time() . wp_rand(), 'nonce' ), -12, 10 );
		update_option(
			'cp_gf_klaviyo_queue_leads_' . $hash,
			array(
				'feeds'   => $feeds,
				'entries' => $leads,
				'form_id' => $form_id,
			),
			'no'
		);
		// Queue to send later.
		\as_enqueue_async_action(
			'cp_gf_klaviyo_queue_leads',
			array(
				'hash' => $hash,
			),
			'cp_gf_klaviyo'
		);

		/* translators: %d: Number of entries. */
		$message = sprintf( esc_html__( '%d entries were queued for processing to send to Klaviyo.', 'gravityforms-klaviyo' ), count( $leads ) );
	}
	echo wp_json_encode(
		array(
			'success' => true,
			'message' => $message,
		)
	);
	die();
}
add_action( 'wp_ajax_cp_gf_resend_klaviyo', __NAMESPACE__ . '\bulk_resend_klaviyo' );

/**
 * Queue the entries to be able to send.
 *
 * @param string $hash Hash of the queue.
 * @return void
 */
function queue_leads( $hash ) {
	$data = get_option( 'cp_gf_klaviyo_queue_leads_' . $hash );
	if ( ! $data || ! is_array( $data ) || empty( $data['feeds'] ) || empty( $data['form_id'] ) ) {
		delete_option( 'cp_gf_klaviyo_queue_leads_' . $hash );
		return;
	}

	if ( isset( $data['search_criteria'] ) ) {
		$paging  = array(
			'offset'    => $data['search_criteria_page'] * 1000,
			'page_size' => 1000,
		);
		$sorting = array(
			'key'       => 'id',
			'direction' => 'ASC',
		);
		$entries = GFAPI::get_entry_ids( $data['form_id'], $data['search_criteria'], $sorting, $paging );
		if ( empty( $entries ) ) {
			delete_option( 'cp_gf_klaviyo_queue_leads_' . $hash );
		} else {
			foreach ( $entries as $entry_id ) {
				foreach ( $data['feeds'] as $feed_id ) {
					\as_enqueue_async_action(
						'cp_gf_klaviyo_resubmit_feed',
						array(
							'feed_id'  => $feed_id,
							'entry_id' => $entry_id,
							'form_id'  => $data['form_id'],
						),
						'cp_gf_klaviyo'
					);
				}
			}
			++$data['search_criteria_page'];
			update_option(
				'cp_gf_klaviyo_queue_leads_' . $hash,
				$data,
				'no'
			);
			// Queue the rest to run again.
			\as_enqueue_async_action(
				'cp_gf_klaviyo_queue_leads',
				array(
					'hash' => $hash,
				),
				'cp_gf_klaviyo'
			);
		}
	} elseif ( ! empty( $data['entries'] ) ) {
		// Queue up to 1000 entries at a time.
		$entries = array_splice( $data['entries'], 0, 1000 );
		foreach ( $entries as $entry_id ) {
			foreach ( $data['feeds'] as $feed_id ) {
				\as_enqueue_async_action(
					'cp_gf_klaviyo_resubmit_feed',
					array(
						'feed_id'  => $feed_id,
						'entry_id' => $entry_id,
						'form_id'  => $data['form_id'],
					),
					'cp_gf_klaviyo'
				);
			}
		}
		if ( empty( $data['entries'] ) ) {
			delete_option( 'cp_gf_klaviyo_queue_leads_' . $hash );
		} else {
			update_option(
				'cp_gf_klaviyo_queue_leads_' . $hash,
				$data,
				'no'
			);
			// Queue the rest to run again.
			\as_enqueue_async_action(
				'cp_gf_klaviyo_queue_leads',
				array(
					'hash' => $hash,
				),
				'cp_gf_klaviyo'
			);
		}
	} else {
		delete_option( 'cp_gf_klaviyo_queue_leads_' . $hash );
	}
}
add_action( 'cp_gf_klaviyo_queue_leads', __NAMESPACE__ . '\queue_leads', 10, 3 );

/**
 * Send the feed subscription to Klaviyo.
 *
 * @param int $feed_id The Feed ID we are working with.
 * @param int $entry_id The Entry ID we are working with.
 * @param int $form_id The Form ID we are working with.
 * @return void
 */
function send_to_klaviyo( $feed_id, $entry_id, $form_id ) {
	$feed  = GFAPI::get_feed( $feed_id );
	$entry = GFAPI::get_entry( $entry_id );
	$form  = GFAPI::get_form( $form_id );

	/* Send submission to Klaviyo */
	$submission = gf_klaviyo_feed_addon();
	if ( $submission ) {
		$submission->process_feed( $feed, $entry, $form );
	}
}
add_action( 'cp_gf_klaviyo_resubmit_feed', __NAMESPACE__ . '\send_to_klaviyo', 10, 3 );

/**
 * Add a bulk action to resubmit to Klaviyo.
 *
 * @param int $form_id The Form ID we are working with.
 */
function bulk_action_selection( $form_id ) {
	?>
		<div id="klaviyo_modal_container" style="display:none;">
			<div id="klaviyo_container">

				<div id="post_tag" class="tagsdiv">
					<div id="resend_klaviyo_options">

						<?php
						$klaviyo = \GFAPI::get_feeds( null, $form_id, 'gravityforms-klaviyo' );

						if ( ! is_array( $klaviyo ) || empty( $klaviyo ) ) {
							?>
							<p class="description"><?php esc_html_e( 'You cannot resend to klaviyo for these entries because this form does not currently have any klaviyo feeds configured.', 'gravityforms-klaviyo' ); ?></p>

							<a href="<?php echo esc_url( admin_url( "admin.php?page=gf_edit_forms&view=settings&subview=notification&id={$form_id}" ) ); ?>" class="button"><?php esc_html_e( 'Configure Klaviyo', 'gravityforms-klaviyo' ); ?></a>
							<?php
						} else {
							?>
							<p class="description"><?php esc_html_e( 'Specify which klaviyo feed you would like to resend for the selected entries.', 'gravityforms-klaviyo' ); ?></p>
							<?php
							foreach ( $klaviyo as $klavio_feed ) {
								?>
								<input type="checkbox" class="gform_klaviyo" value="<?php echo esc_attr( $klavio_feed['id'] ); ?>" id="klaviyo_<?php echo esc_attr( $klavio_feed['id'] ); ?>" />
								<label for="klaviyo_<?php echo esc_attr( $klavio_feed['id'] ); ?>"><?php echo esc_html( $klavio_feed['meta']['feed_name'] ?? 'Klaviyo' ); ?></label>
								<br /><br />
								<?php
							}

							?>

							<input type="button" name="klaviyo_resend" id="klaviyo_resend" value="<?php esc_attr_e( 'Resend to Klaviyo', 'gravityforms-klaviyo' ); ?>" class="button" onclick="CPBulkResendKlaviyo();" />
							<span id="cp_gf_klaviyo_please_wait_container" style="display:none; margin-left: 5px;">
								<i class='gficon-gravityforms-spinner-icon gficon-spin'></i> <?php esc_html_e( 'Resending...', 'gravityforms' ); ?>
							</span>
							<?php
						}
						?>

					</div>

					<div id="resend_klaviyo_close" style="display:none;margin:10px 0 0;">
						<input type="button" name="resend_klaviyo_close_button" value="<?php esc_attr_e( 'Close Window', 'gravityforms' ); ?>" class="button" onclick="closeModal(true);" />
					</div>

				</div>

			</div>
		</div>
		<!-- / Resend Klaviyo -->
	<?php
}
add_action( 'gform_pre_entry_list', __NAMESPACE__ . '\bulk_action_selection', 10, 1 );
