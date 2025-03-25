<?php
/*
Plugin Name: GForm Content Validation
Description: Check the content before submitting your Gravity Forms form. If the content contains another website, do not submit.
Version: 1.1
Author: tthai
Author URI: https://www.facebook.com/tranthanhhai97/

*/

add_filter('gform_validation', 'custom_gform_content_validation');

function custom_gform_content_validation($validation_result)
{
    $form = $validation_result['form'];

    // Mảng các từ khóa không được phép
    $disallowed_keywords = [
        'Fuck', 'Admin', 'Register', 'GoDaddy'
    ];
    // Loop through form fields
    foreach ($form['fields'] as &$field) {
        // Check if field is a text field (or textarea)
        if ($field->type == 'textarea' || $field->type == 'text') {
            $field_value = rgpost("input_{$field['id']}");

            if (preg_match("/\b(?:(?:https?|ftp|http):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $field_value)) {
                $validation_result['is_valid'] = false;
                $field->failed_validation = true;
                $field->validation_message = 'Content containing website is not allowed.';
            }

            // Kiểm tra các từ khóa cấm
            foreach ($disallowed_keywords as $keyword) {
                if (stripos($field_value, $keyword) !== false) {
                    $validation_result['is_valid'] = false;
                    $field->failed_validation = true;
                    $field->validation_message = 'Your submission contains disallowed content.';
                    break;
                }
            }

        }
    }

    $validation_result['form'] = $form;
    return $validation_result;
}
