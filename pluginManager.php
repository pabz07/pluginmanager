<?php
/*
   Plugin Name: Plugin Manager
   Plugin URI: http://localhost
   Description: Plugin that manages your plugin and loading only necessary plugin for post type.
   Version: 1
   Author: Pabz Veroy
   Author URI: https://github.com/pabz07
   License: GPL2
   Date: April 15, 2017
   */

function pluginManagerMenu() {
	add_menu_page( 
		'Plugin Manager',
		'Plugin Manager',
		'manage_options',
		'plugin_manager_options',
		'pluginManagerOptionPage',
		'dashicons-list-view'
	);

	add_submenu_page( 
		'plugin_manager_options',
		'Post Types', 
		'Post Types', 
		'manage_options', 
		'plugin_manager_post_types', 
		'pluginManagerPostType'
	);
}

add_action( 'admin_menu', 'pluginManagerMenu' );

function pluginManagerLoadScripts($page) {
	if(is_admin() && strpos($page, "plugin_manager") > 0) {  // check if admin page and in Plugin Manager Option
		wp_enqueue_script("plugin_manager_jquery", plugin_dir_url( __FILE__ ) . "js/jquery.min.js");
		wp_enqueue_script("plugin_manager_bootstrap", plugin_dir_url( __FILE__ ) . "js/bootstrap/bootstrap.min.js");
		wp_enqueue_script("plugin_manager_group_function", plugin_dir_url( __FILE__ ) . "js/group-functions.js");
	}
}

function pluginManagerLoadStyles($page) {
	if(is_admin() && strpos($page, "plugin_manager") > 0) {  // check if admin page and in Plugin Manager Option
		wp_enqueue_style("plugin_manager_bootstrap", plugin_dir_url( __FILE__ ) . "css/bootstrap/bootstrap.min.css");
		wp_enqueue_style("plugin_manager_bootstrap_map", plugin_dir_url( __FILE__ ) . "css/bootstrap/bootstrap.min.css.map");
		wp_enqueue_style("plugin_manager_css", plugin_dir_url( __FILE__ ) . "css/pluginmanager.css");
	}
}

add_action("admin_enqueue_scripts", "pluginManagerLoadScripts");
add_action("admin_enqueue_scripts", "pluginManagerLoadStyles");

function getAllPlugins() {
	$plugins = get_plugins();
	$activePlugins = wp_get_active_and_valid_plugins();
	foreach($plugins as $key => &$plugin) {
		if(in_array(WP_PLUGIN_DIR . "/" . $key, $activePlugins))
			$plugin["is_active"] = 1;
		else
			$plugin["is_active"] = 0;
	}
	
	return $plugins;
}

function pm_print_r($object) {
	echo "<pre>";
	print_r($object);
	echo "</pre>";
}

function pluginManagerOptionPage() {
	if(isset($_GET["plugin"]) && isset($_GET["type"])) {
		$type = $_GET["type"];
		if($type == "activate") {
			$success = activate_plugin($_GET["plugin"]);
			if(!($success instanceof WP_Error))
				$message = [ "content" => "Plugin Activated", "type" => "success"];
			else
				$message = [ "content" => "Something went wrong. Please try again later.", "type" => "danger"];
		} else if($type == "deactivate") {
			deactivate_plugins($_GET["plugin"]);
			$message = [ "content" => "Plugin Deactivated", "type" => "success"];
		}
	}

	load_template(dirname( __FILE__ ) . "/templates/option_page.php", 1);
}

function pluginManagerPostType() {

}

function pluginManagerMovePluginToGroup() {
	$groups = get_transient("plugin_manage_groups");
	$to = $_POST["to"];
	$from = $_POST["from"];
	$pluginName = $_POST["plugin"];
	$plugin = $groups[$from]["plugins"][$pluginName];
	unset($groups[$from]["plugins"][$pluginName]);
	$groups[$to]["plugins"][$pluginName] = $plugin;
	set_transient("plugin_manage_groups", $groups);
}

add_action("wp_ajax_move_plugin_to_group", "pluginManagerMovePluginToGroup");