<?php

/* class initialization */

class MCA {

	public static function msg_ui_filter() {
		echo "<div id='custom-mca'>";
		echo "<p class='s-desc'><strong>IMPORTANT:</strong> All material uploaded into UBC Blogs must comply with Canadian 
copyright laws. Uploading and posting content from copyrighted works requires authorization under the Copyright Act or authorization from 
the copyright holder (for example, specific permission from the copyright holder or a UBC licence that permits such use).</p>";
		echo "<hr/>";
	}

	public static function pre_upload_ui_filter() {
		echo "<p class='s-desc'>For record keeping purposes, please confirm the copyright authorization(s) that apply to the 
uploaded material(s), from the list below. <em>Note</em>: If uploaded materials contain content from different sources, authorization is 
required for each source and such information should be recorded. <a target='_blank' title='Documentation about using this form' 
href='http://wiki.ubc.ca/Documentation:UBC_Blogs/Copyright_Upload_Form'>Help</a></p>";
		echo "<p><input type='checkbox' name='cbcr1' value='cbcr1' id='cbcr1'> <label for='cbcr1'><strong>With the permission of 
the copyright holder(s)</strong>: <span class='crdesc'>The use of this material in UBC Blogs has been authorized by the copyright 
holder(s) in one or more of the following ways…</span> <a href='#' id='explain-this' title='Expand'>Expand</a>
		<ul id='explain-later' class='crdesc'>
<li>The individual (for example, the teaching staff member, course participant or other member of the UBC Blogs site) wishing to 
distribute the material in the UBC Blogs site holds copyright (solely or jointly) and has duly authorized its upload</li>
<li>Specific written permission was obtained from the copyright holder(s)</li>
<li>A <a title='Database of UBC Library licenses' target='_blank' href='http://licenses.library.ubc.ca/'>UBC License</a>, a Creative 
Commons license or other license permits such use
UBC holds copyright in the material.</li></ul></label></p>";
		echo "<p><input type='checkbox' name='cbcr2' value='cbcr2' id='cbcr2'> <label for='cbcr2'><strong>Public Domain:</strong> 
<span class='crdesc'>The material is in the <a target='_blank' href='http://copyright.ubc.ca/faq/basics/#basics8' title='More information 
about the public domain'>Public Domain</a></span></label></p>";
		echo "<p><input type='checkbox' name='cbcr3' value='cbcr3' id='cbcr3'> <label for='cbcr3'><strong>Other:</strong> <span 
class='crdesc'>Explain below</span></label></p>";
		echo "<label for='cbcr4more'><strong>Additional Information or Comments</strong></label><br/>";
		echo "<textarea id='cbcr4more' name='cbcr4more' rows='2' cols='80'></textarea><br/><span class='crdesc'>Please use this 
text box to record additional information regarding the copyright authorizations obtained, or to explain the Other classification noted 
above.</span><br/>";
		//echo "<hr/>";
		echo '<input style="margin-top:10px;" type="submit" name="html-upload" id="html-upload-2" class="button" value="Upload" 
disabled="disabled">';
		echo '<p>Maximum upload file size: '.intval(wp_max_upload_size()/1048576).'MB. After a file has been uploaded, you can add 
titles and descriptions.</p>';
		echo "</div>";
	}

	public static function post_upload_ui_filter() {
	?>
	<style>
	.crdesc { color:#777; }
	/*.s-desc, .crdesc { font-size:8px; } */
	div.crdesc li { list-style-type: circle; margin-left:20px; }
	ul#explain-later { list-style-type: circle; margin-left:20px; }
	#explain-later { margin-left:20px; }
	.max-upload-size, .after-file-upload { display:none; }
	</style>

	<script type="text/javascript">

	// This isn't ideal, but it will work for a quick fix. @TODO: Proper errror handling.
	jQuery( window ).on( 'dropzone:enter', function() {

		if( ! jQuery( "#cbcr1, #cbcr2, #cbcr3, #cbcr4" ).is(':checked') ) {
			alert( 'You must select which authorizations you have to upload this material.' );
			return;
		}

	} );

	var $j = jQuery.noConflict();
	$j(function(){
		$j("drag-drop-area").hide();
		$j("#explain-later").hide();
		$j( '.uploader-inline-content .upload-ui' ).hide();

		$j('#explain-this').click(function() {
  			$j('#explain-later').toggle('fast', function() {
  				//$j('#explain-this').
    // Animation complete.
  			});
		});

		$j("#html-upload-ui").show();

		if( $j(".filename.new").length )
 		{
 			//alert('Yes');
 			$j("#custom-mca").hide();
 			$j("#html-upload-ui").hide();
 		}

		if ( $j("#media-items"))
		$j(".upload-html-bypass").hide();
		$j("input#html-upload").hide();
		//$j("#html-upload-ui").hide();
		//$j("input#html-upload-2").hide();
		$j("#plupload-upload-ui").hide();
		$j("input[name=cbcr1], input[name=cbcr2], input[name=cbcr3], input[name=cbcr4]").click(function(){
		if($j("#cbcr1, #cbcr2, #cbcr3, #cbcr4").is(':checked')) {
			$j( '.uploader-inline-content .upload-ui' ).show();
			//$j("#html-upload-ui").show();
			//$j("input#html-upload-2").show();
			//$j('input#html-upload-2').attr('disabled', '');
			$j('input#html-upload-2').removeAttr('disabled');

			//alert($('input#html-upload-2').attr('disabled'));
		} else {
			//$j("#html-upload-ui").hide();
			//$j("input#html-upload-2").hide();
			$j( '.uploader-inline-content .upload-ui' ).hide();
			$j('input#html-upload-2').attr('disabled','disabled');
		}
		});
	});
	</script>
	<?php
	}

	public static function meta_filter_mca($form_fields, $post) {
		$cbcr1 = (bool) get_post_meta($post->ID, '_cbcr1', true);
		$cbcr2 = (bool) get_post_meta($post->ID, '_cbcr2', true);
		$cbcr3 = (bool) get_post_meta($post->ID, '_cbcr3', true);

		$form_fields["copyright_authorization"] = array(
			"label" => __("Copyright Authorization(s)"),
			"input" => "html",
			"html" => "<ul>
				<li><input type='checkbox' ".($cbcr1 ? "checked" : "")."
    name='attachments[{$post->ID}][cbcr1]'
    id='attachments[{$post->ID}][cbcr1]' /> <label for='attachments[{$post->ID}][cbcr1]'>With the permission of the copyright 
holder(s)</label></li>
				<li><input type='checkbox' ".($cbcr2 ? "checked" : "")."
    name='attachments[{$post->ID}][cbcr2]'
    id='attachments[{$post->ID}][cbcr2]' /> <label for='attachments[{$post->ID}][cbcr2]'>The material is in the Public Domain</label></li>
				<li><input type='checkbox' ".($cbcr3 ? "checked" : "")."
    name='attachments[{$post->ID}][cbcr3]'
    id='attachments[{$post->ID}][cbcr3]' /> <label for='attachments[{$post->ID}][cbcr3]'>Other</label></li>
			</ul>" );

			$form_fields["cbcr4more"] = array(
				"label" => __("Additional Informations or Comments"),
				"input" => "textarea",
				"value" => get_post_meta($post->ID, "_cbcr4more", true)
			);


			return $form_fields;
	}

	public static function add_generate_meta($metadata, $attachment_id) {
		update_post_meta($attachment_id, '_cbcr1', $_POST["cbcr1"]);
		update_post_meta($attachment_id, '_cbcr2', $_POST["cbcr2"]);
		update_post_meta($attachment_id, '_cbcr3', $_POST["cbcr3"]);
		update_post_meta($attachment_id, '_cbcr4more', $_POST["cbcr4more"]);
		return $metadata;
	}

	public static function mca_filter_attachment_fields_to_save($post, $attachment) {

     // update_post_meta(postID, meta_key, meta_value);
		update_post_meta($post['ID'], '_cbcr1', $attachment['cbcr1']);
		update_post_meta($post['ID'], '_cbcr2', $attachment['cbcr2']);
		update_post_meta($post['ID'], '_cbcr3', $attachment['cbcr3']);
		#var_dump($attachment);
		#die();

    if( isset($attachment['cbcr4more']) ){
    	update_post_meta($post['ID'], '_cbcr4more', $attachment['cbcr4more']);
    }

		return $post;
	}
}

