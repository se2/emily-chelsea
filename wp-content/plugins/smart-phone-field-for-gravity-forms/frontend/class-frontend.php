<?php

if (!defined('ABSPATH')) {
    exit;
}

class GF_smart_phone_field_frontend {

    function __construct() {
        add_action('gform_field_content', array($this, 'gf_spf_validation_message_content'), 10, 2);
        add_action('gform_enqueue_scripts', array($this, 'spf_enqueue_scripts'), 10, 2);
    }

    function gf_spf_validation_message_content($content, $field) {

        if ($field->type == 'phone' && $field->smartPhoneFieldGField) {
            $content = str_replace("<input", "<span class='spf-phone valid-msg hide'></span><span class='spf-phone error-msg hide'></span><input", $content);
        }

        return $content;
    }


    function spf_enqueue_scripts($form, $is_ajax) {

        $form_id = $form['id'];
        $field_arr = [];

        foreach ($form['fields'] as $field) {

            if (property_exists($field, 'smartPhoneFieldGField') && $field->smartPhoneFieldGField) {

                $lvl = "input_{$field->formId}_{$field->id}";

                $field_arr[$lvl] = [];

                $field_arr[$lvl][] = "#input_{$field->formId}_{$field->id}";
                $field_arr[$lvl][] = $field['smartPhoneAutoIpGField'];
                $field_arr[$lvl][] = empty($field['defaultCountryGField']) ? 'us' : $field['defaultCountryGField'];
                $field_arr[$lvl][] = empty($field['preferredCountriesGField']) ? 'none' : $field['preferredCountriesGField'];
                $field_arr[$lvl][] = "input_{$field->id}";
                $field_arr[$lvl][] = $field['multiStepGField'];
                $field_arr[$lvl][] = empty($field['countryFlagGField']) ? 'flag' : $field['countryFlagGField'];
            }
        }

        if (count($field_arr) === 0) {
            return;
        }


        wp_enqueue_style('spf_intlTelInput', GF_SMART_PHONE_FIELD_URL . 'frontend/css/intlTelInput.min.css', array(), GF_SMART_PHONE_FIELD_VERSION_NUM);
        wp_enqueue_style('spf_style', GF_SMART_PHONE_FIELD_URL . 'frontend/css/spf_style.css', array('spf_intlTelInput'), GF_SMART_PHONE_FIELD_VERSION_NUM);

        wp_enqueue_script('spf_intlTelInput', GF_SMART_PHONE_FIELD_URL . 'frontend/js/intlTelInput-jquery.min.js', array('jquery'), GF_SMART_PHONE_FIELD_VERSION_NUM);
        wp_enqueue_script('spf_utils', GF_SMART_PHONE_FIELD_URL . 'frontend/js/utils.js', array('jquery'), GF_SMART_PHONE_FIELD_VERSION_NUM);
        wp_enqueue_script('spf_intlTelInput_main', GF_SMART_PHONE_FIELD_URL . 'frontend/js/spf_main.js', array('spf_intlTelInput'), GF_SMART_PHONE_FIELD_VERSION_NUM);

        wp_localize_script('spf_intlTelInput_main', 'spfMainData_' . $form_id, array(
            'utilsScript' => GF_SMART_PHONE_FIELD_URL . 'frontend/js/utils.js',
            'elements' =>  $field_arr
        ));
    }
}

new GF_smart_phone_field_frontend();
