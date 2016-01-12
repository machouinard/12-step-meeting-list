<?php

//for all users
add_action('init', 'tsml_init');

function tsml_init(){

	//register post types and taxonomies
	tsml_custom_post_types();
	
	//run any necessary upgrades
	tsml_upgrades();
	
	//load internationalization
	add_action('plugins_loaded', 'tsml_plugins_loaded');
	function tsml_plugins_loaded() {
		load_plugin_textdomain('12-step-meeting-list', false, basename(dirname(__FILE__)) . '/languages/');
	}

	//meeting list page
	add_filter('archive_template', 'tsml_archive_template');
	function tsml_archive_template($template) {
		if (is_post_type_archive('meetings')) {
			$user_theme_file = get_stylesheet_directory() . '/archive-meetings.php';
			if (file_exists($user_theme_file)) return $user_theme_file;
			return dirname(__FILE__) . '/../templates/archive-meetings.php';
		}
		return $template;
	}	

	//meeting & location detail pages
	add_filter('single_template', 'tsml_single_template');
	function tsml_single_template($template) {
		global $post;
		if ($post->post_type == 'meetings') {
			$user_theme_file = get_stylesheet_directory() . '/single-meetings.php';
			if (file_exists($user_theme_file)) return $user_theme_file;
			return dirname(__FILE__) . '/../templates/single-meetings.php';
		} elseif ($post->post_type == 'locations') {
			$user_theme_file = get_stylesheet_directory() . '/single-locations.php';
			if (file_exists($user_theme_file)) return $user_theme_file;
			return dirname(__FILE__) . '/../templates/single-locations.php';
		}
		return $template;
	}
		
	//add api identification tag to header. more info: https://github.com/intergroup/api
	add_action('wp_head', 'tsml_head');
	function tsml_head() {
		echo '<meta name="12_step_meetings_api" content="' . admin_url('admin-ajax.php') . '?action=api">' . PHP_EOL;
	}

}