/**
 * Toggle SMS signup settings
 *
 * @package gf_klaviyo
 */

jQuery(
	function () {
		window.gravityforms_klaviyo = {
			toggle_sms: function (checked) {
				var sms_list = jQuery( "#gform_setting_sms_signup_list,#gform_setting_sms_consent_field,#gform_setting_sms_disclaimer_text_field" );
				if (checked) {
					sms_list.show();
				} else {
					sms_list.hide();
				}
			}
		};
		if (jQuery( "#enable_sms_signup" ).length) {
			var is_currently_checked = jQuery( "#enable_sms_signup" ).is( ":checked" );
			gravityforms_klaviyo.toggle_sms( is_currently_checked );
		}
	}
);
