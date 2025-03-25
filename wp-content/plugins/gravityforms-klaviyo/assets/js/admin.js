/**
 * Gravity Forms Klaviyo Add-On
 *
 * @package gf_klaviyo
 */

jQuery(
	function () {
		jQuery( ".gf_klaviyo_resubmit" ).click(
			function (e) {
				e.preventDefault();
				var jqThis  = jQuery( this );
				var feedID  = jqThis.data( 'feedid' );
				var spinner = jQuery( '#klaviyo_please_wait_container_' + feedID );
				spinner.fadeIn();
				jQuery.ajax(
					{
						url: window.ajaxurl,
						type: 'post',
						dataType: 'json',
						data: {
							action: 'gfklav_resubmit_feed',
							nonce: jqThis.data( 'nonce' ),
							entryId: jqThis.data( 'entryid' ),
							formId: jqThis.data( 'formid' ),
							feedId: feedID
						},
						success: function (response) {
							spinner.hide();
							displayMessage( response.message, "success", "#klaviyo" );
						},
						error: function () {
							spinner.hide();
							displayMessage( "Failed.", "error", "#klaviyo" );
						}
					}
				);
			}
		);

		function resetResendUI() {
			jQuery( '.gform_klaviyo' ).prop( 'checked' , false );
			jQuery( '#klaviyo_container .message, #klaviyo_override_settings' ).hide();
		}

		jQuery( '#doaction, #doaction2' ).click(
			function () {
				var action = jQuery( this ).siblings( 'select' ).val();

				if ("resend_klaviyo" !== action) {
					return;
				}

				var entryIds = getLeadIds();

				if ( entryIds.length == 0 ) {
					alert( cp_gf_klaviyo.no_entries_error_message );
					return false;
				}

				resetResendUI();
				tb_show( cp_gf_klaviyo.resend_button_text, '#TB_inline?width=350&amp;inlineId=klaviyo_modal_container', '' );
				return false;
			}
		);
	},
);

function CPBulkResendKlaviyo() {
	var selectedFeeds = new Array();
	jQuery( ".gform_klaviyo:checked" ).each(
		function () {
			selectedFeeds.push( jQuery( this ).val() );
		}
	);
	var leadIds = getLeadIds();

	if (selectedFeeds.length <= 0) {
		displayMessage( cp_gf_klaviyo.resend_error_message , "error", "#notifications_container" );
		return;
	}

	jQuery( '#cp_gf_klaviyo_please_wait_container' ).fadeIn();

	jQuery.post(
		ajaxurl,
		{
			action             : "cp_gf_resend_klaviyo",
			cp_gf_klaviyo_nonce: cp_gf_klaviyo.resend_nonce,
			feeds              : jQuery.toJSON( selectedFeeds ),
			leadIds            : leadIds,
			filter             : cp_gf_klaviyo.filter,
			search             : cp_gf_klaviyo.search,
			operator           : cp_gf_klaviyo.operator,
			fieldId            : cp_gf_klaviyo.fieldId,
			formId             : cp_gf_klaviyo.formId
		},
		function (response) {
			jQuery( '#cp_gf_klaviyo_please_wait_container' ).hide();
			if (response.success) {
				displayMessage( response.message, "success", "#klaviyo_container" );
			} else if ( response.success === false ) {
				displayMessage( response.message, 'error', '#klaviyo_container' );
			} else {
				displayMessage( response, 'error', '#klaviyo_container' );
			}
		},
		"json"
	);

}