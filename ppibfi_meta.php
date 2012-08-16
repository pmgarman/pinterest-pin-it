<?php

if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'ppibfi_meta.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) die ( 'Stop! Hammer time!' );

/*
=============================================================================
	Meta box for single / page to disable the button on that post / page
=============================================================================
*/

/* Adds a box to the main column on the Post and Page edit screens */
function xcp_optin() {
	add_meta_box( 'xcp_optin_sectionid', __( 'Pinterest plugin', 'ppibfi_translate' ), 'xcp_optin_custombox', '', 'side' );
}

/*
================
	Meta box
================
*/
function xcp_optin_custombox( $post ) {

	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'xcp_optin_noncename' );

	$meta_values = get_post_meta($post->ID, 'xcp_optin_post' );
	if( 'on' == $meta_values[0] ) $xc_check = 'checked="yes"';
	else $xc_check = '';

	// The actual fields for data entry
	echo '<label for="xc_optin_field_disable">';
	echo '<input type="checkbox" name="xc_optin_field_disable" id="xc_optin_field_disable" value="on" '.$xc_check.' /> ';
	_e('Disable Pinterest Pin It button on this page', 'ppibfi_translate' );
}

/*
============
	Save
============
*/
add_action( 'save_post', 'xcp_optin_save' );

/* When the post is saved, saves our custom data */
function xcp_optin_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! wp_verify_nonce( $_POST['xcp_optin_noncename'], plugin_basename( __FILE__ ) ) ) return;
	$meta_values = get_post_meta( $post->ID, 'xcp_optin_post' );
	$disable_field = $_POST['xc_optin_field_disable'];
	update_post_meta( $post_id, 'xcp_optin_post', $disable_field );
}

/*
=================
	Functions
=================
*/

// Error messages if content width is not set or seems to be WP default:
function pibifi_check_content_width() {

	$standardImageW = get_option( 'large_size_w' );
	$contentWidth = get_option( 'ppibfi_content_width' );
	// Set to standard:
	if ( '1024' == $standardImageW && ! $contentWidth ) add_action( 'admin_notices' , create_function( '', "echo '<div class=error><p>".__("Your maximum image width is thought to be 1024 pixels. <strong>This might be incorrect and might affect the functionality of the Pinterest Pin It plugin</strong>. Please check the <a href=\"options-general.php?page=pibfi_engine_id\">plugins settings</a>", 'ppibfi_translate').".</p></div>';" ) );

	// Non existent:
	elseif ( ! $standardImageW && ! $contentWidth ) add_action('admin_notices' , create_function( '', "echo '<div class=error><p>".__("No maximum image width has been detected. This might affect the functionality of the <strong>Pinterest Pin It</strong> plugin. Please check the <a href=\"options-general.php?page=pibfi_engine_id\">plugin settings</a>", 'ppibfi_translate')."</div>';" ) );
}

function pibfi_CheckImagesWidth() {
	$standardImageW = get_option( 'large_size_w' );
	if ( 1024 != $standardImageW && $standardImageW != false ) update_option( 'ppibfi_content_width', $standardImageW );
}

?>