<?php
/**
* Plugin Name: Old Upload Method
* Plugin URI: http://wordpress.org/support/topic/is-there-a-way-to-disable-the-new-media-manager
* Description: Replace the new media upload with the old thickbox
* Author: A.Morita, brasofilo
* Version: 1.0
* License: GPLv2 or later
*
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU
* General Public License version 2, as published by the Free Software Foundation. You may NOT assume
* that you can use any other version of the GPL.
*
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
* even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/


if( function_exists( 'add_filter' ) ) {
	add_action( 'plugins_loaded', array( 'I_Want_The_Old_Uploader', 'get_object' ) );
}

 
/**
* Add Tags and Categories taxonmies to Attachment with WP 3.5
*/
class I_Want_The_Old_Uploader
{
	static private $classobj;
	 
	/**
	* Constructor, init WP functions
	*
	*/
	public function __construct()
	{
		// p2 theme front end add media removal
		if( class_exists( 'P2' ) ):
			require_once( ABSPATH . '/wp-admin/includes/media.php' );
		endif;
		
		add_action( 'get_header', array( $this, 'remove_media_buttons' ) );
		// Old Upload buttons and thickbox
		add_action( 'admin_head', array( $this, 'remove_media_buttons' ) );
		 
		// Featured Image
		add_action( 'wp_default_scripts', array( $this, 'unset_media_views' ), 999, 1 );
		add_action( 'admin_head', array( $this, 'remove_wp_print_media_templates' ) );
		 
		// Full screen behavior
		add_action( 'after_wp_tiny_mce', array( $this, 'fullscreen_media_button' ) );
	}
	 
	/**
	* Handler for the action 'init'. Instantiates this class.
	*
	* @return $classobj
	*/
	public function get_object()
	{
		if( NULL === self::$classobj )
			self::$classobj = new self;
		
		return self::$classobj;
	}
	 
	/**
	* Old Upload Buttons & Thickbox
	*/
	public function remove_media_buttons()
	{
		remove_action( 'media_buttons', 'media_buttons' );
		add_action( 'media_buttons', array( $this, 'old_media_buttons' ) );
	}
	 
	 
	public function old_media_buttons( $editor_id = 'content' )
	{
		$context = apply_filters( 'media_buttons_context', __( 'Upload/Insert %s' ) );
		 
		$img = '<img src="'
		. esc_url( admin_url( 'images/media-button.png?ver=20111005' ) )
		. '" width="15" height="15" />';
		 
		echo '<a href="'
		. esc_url( get_upload_iframe_src() )
		. '" class="thickbox add_media" id="'
		. esc_attr( $editor_id )
		. '-add_media" title="'
		. esc_attr__( 'Upload/Insert Media' )
		. '" onclick="return false;">'
		. sprintf( $context, $img )
		. '</a>';
	}
	 
	 
	/**
	* Featured image
	*/
	public function unset_media_views( $scripts )
	{
		unset( $scripts->registered['media-views'] );
	}
	 
	 
	public function remove_wp_print_media_templates()
	{
		remove_action( 'admin_footer', 'wp_print_media_templates' );
		remove_action( 'wp_footer', 'wp_print_media_templates' );
	}
	 
	 
	/**
	* Adjust Full Screen behavior
	*/
	public function fullscreen_media_button()
	{
		?>
		<script type="text/javascript">
			fullscreen.medialib = function()
			{
				var href = jQuery('div#wp-content-media-buttons a.thickbox').attr('href') || '';
				tb_show('', href);
			}
		</script>
		<?php
	}
 
}
 
// end class
