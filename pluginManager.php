<?php
/*
   Plugin Name: Plugin Manager
   Plugin URI: http://localhost
   Description: Plugin that manages your plugin and loading only necessary plugin for post type.
   Version: 1
   Author: Pabz Veroy
   Author URI: https://github.com/pabz07
   License: GPL2
   */

function alterPlugins($value) {
	print_r($value);
	echo "Test";

	return $value;
}

add_filter("option_active_plugins", "alterPlugins");