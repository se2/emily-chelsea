<?php
/*
Plugin Name: TTG Gravity Forms Klaviyo Add-On
Plugin URI: http://www.gravityforms.com
Description: Integrates Gravity Forms with Klaviyo allowing form submissions to be automatically sent to your Klaviyo account.
Version: 1.1
Author: TTG
Author URI: https://technologytherapy.com/
*/

if (!method_exists('GFForms', 'include_feed_addon_framework')) {
	return;
}

define('TTG_GF_KLAVIYO_PREFIX', 'TTG');
define('TTG_GF_KLAVIYO_API_VERSION', '1.0');
define('TTG_GF_KLAVIYO_TEXT_DOMAIN', 'ttg_klaviyoaddon');

require_once('vendor/autoload.php');
require_once('includes/ttg-klaviyo.php');
require_once('class-gfklaviyofeedaddon.php');
require_once('admin/ttg-gf-klaviyo-list.php');


add_action('gform_loaded', array('TTG_GF_KLAVIYO_API', 'load'), 5);

class TTG_GF_KLAVIYO_API
{

	public static function load()
	{
		GFAddOn::register('TTG_GFKlaviyoAPI');
	}
}
