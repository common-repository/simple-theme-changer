<?php
/**
 * @package theme_changer
 */
/*
Plugin Name: Simple Theme Changer
Description: This plugin allows people visiting your website to change the theme. Users do not need to be logged in
Author: Darek Rycyk
Author URI: http://darendev.com/
Version: 1.0
License: GPL v3 or later
*/


// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Plugin can not be called directly.';
	exit;
}

if (!class_exists('theme_changer')) {
	
	include_once("class_theme_changer.php");
	
	if (class_exists('theme_changer')) {
		
		$theme_changer = new theme_changer();
		
		//If current request is for an administrative interface page load admin panel
		if ( is_admin()  ){		
			$theme_changer->admin_zone_start();
		}
		
		$theme_changer->user_zone_start();
	}
}





register_uninstall_hook( __FILE__, 'theme_changer_uninstall' );

function theme_changer_uninstall(){
	delete_option('display_type');
	delete_option( 'filtered_theme_list' ); 
	delete_option( 'change_theme_button_name' ); 
}


 
?>