<?php
class TTG_Blocks_ACF
{
	public $acf_path;
	function __construct()
	{
		$this->acf_path = TTG_Blocks_Utils::get_path('acf-json');

		add_filter('acf/settings/load_json', [$this, 'json_load_point']);
		add_filter('acf/settings/save_json', [$this, 'save_json_path'], 9999);
		add_action('acf/update_field_group', [$this, 'update_field_group'], 9999);
	}

	public function is_acf($title)
	{
		return strpos($title, '[TTG]') !== false;
	}

	public function json_load_point($paths)
	{
		$paths[] = $this->acf_path;
		// return
		return $paths;
	}

	public function save_json_path($path)
	{
		$post_title = isset($_POST['post_title']) ? $_POST['post_title'] : 0;
		if (!empty($post_title)) {
			if ($this->is_acf($post_title)) {
				return $this->acf_path;
			}
		}

		return $path;
	}

	public function update_field_group($group)
	{
		if ($this->is_acf($group['title'])) {
			add_action('acf/settings/save_json', [$this, 'save_json_path'], 9999);
			return $group;
		} else {
			return $group;
		}
	}
}

new TTG_Blocks_ACF();
