<?php
/*
Plugin Name: Media Copyright Attribution
Plugin URL: http://github.com/ubc/mca
Description: Allows user to add copyright attribution for media files
Version: 0.9.2
Author: Michael Ha, Richard Tape
Author URI: http://ctlt.ubc.ca/
License: GPLv2
*/

/* initialization */

if ( !defined('ABSPATH') )
	die('-1');

define( 'MCA_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'MCA_BASENAME', plugin_basename(__FILE__) );
define( 'MCA_DIR_URL',  plugins_url( ''  , MCA_BASENAME ) );

require( 'lib/class.mca.php' );

/* actions, filters and hooks */
add_action( 'post-upload-ui', array('MCA', 'pre_upload_ui_filter') );
add_action( 'post-upload-ui', array('MCA', 'post_upload_ui_filter') );
add_action( 'pre-upload-ui', array('MCA', 'msg_ui_filter') );
add_filter( 'attachment_fields_to_edit', array('MCA','meta_filter_mca'), null, 2);
add_filter( 'attachment_fields_to_save', array('MCA','mca_filter_attachment_fields_to_save'), null , 2);
add_filter( 'wp_generate_attachment_metadata', array('MCA', 'add_generate_meta'), 1, 2 );

register_deactivation_hook( __FILE__, 'mca_uninstall');
register_uninstall_hook( __FILE__, 'mca_uninstall');

/* uninstall */

function mca_uninstall() {
	/* perform uninstall actions */
}

