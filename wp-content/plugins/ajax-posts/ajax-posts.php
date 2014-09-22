<?php

/**
 * Plugin Name: Ajax posts gallery
 * Plugin URI: http://
 * Description: Adds new post type to existing ones, allows to view posts as gallery with ajax loading
 * Version: 1.0
 * Author: Anton Presnyakov
 * Author URI: http://
 * License: MIT
 */

// Installation and uninstallation hooks
register_activation_hook(__FILE__, array('ajp_activate'));
register_deactivation_hook(__FILE__, array('ajp_deactivate'));

/**
 * activating plugin
 */
function ajp_activate(){
	
}

/**
 * deactivating plugin
 */
function ajp_deactivate(){
	
}