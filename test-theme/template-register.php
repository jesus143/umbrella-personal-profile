<?php
/**
 * Template Name: Register
*/
$wp_user_search = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY ID");

foreach ( $wp_user_search as $userid ) {
	$user_id       = (int) $userid->ID;
	$user_login    = stripslashes($userid->user_login);
	$display_name  = stripslashes($userid->display_name);

	$return  = '';
	$return .= "\t" . '<li>'. $display_name .'</li>' . "\n";

	print($return);
}
?>