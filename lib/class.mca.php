<?php

/* class initialization */

class MCA {

	public function pre_upload_ui_filter() {
		echo "<p><strong>IMPORTANT:</strong> All material uploaded into UBC Blogs must comply with Canadian copyright laws.</p>";
		echo "<p>Uploading and posting content from copyrighted works requires authorization under the Copyright Act (for example, fair dealing or other exceptions) or authorization from the copyright holder (for example, specific permission from the copyright holder or a UBC licence that permits such use).</p>";
		echo "<hr/>";
		echo "<p>For record keeping purposes, please confirm the copyright authorization(s) that apply to the uploaded material(s), from the list below. <em>Note</em>: If uploaded materials contain content from different sources, authorization is required for each source and such information should be recorded. <a target='_blank' href=''>Help</a></p>";
		echo "<p><input type='checkbox' name='cbcr1' value='cbcr1' id='cbcr1'> <label for='cbcr1'>With the permission of the copyright holder(s)<div class='crdesc'>
	 The use of this material in Connect has been authorized by the copyright holder(s) in one or more of the following ways:<ul>
<li>The individual (for example, the teaching staff member, course participant or other member of the Connect site) wishing to distribute the material in the Connect site holds copyright (solely or jointly) and has duly authorized its upload</li>
<li>Specific written permission was obtained from the copyright holder(s)</li>
<li>A <a target='_blank' href='http://licenses.library.ubc.ca/'>UBC License</a>, a Creative Commons license or other license permits such use
UBC holds copyright in the material.</li></ul></div></label></p>";
		echo "<p><input type='checkbox' name='cbcr2' value='cbcr2' id='cbcr2'> <label for='cbcr2'>The material is in the <a target='_blank' href='http://copyright.ubc.ca/faq/basics/#basics8'>Public Domain</a></label></p>";
		echo "<p><input type='checkbox' name='cbcr3' value='cbcr3' id='cbcr3'> <label for='cbcr3'>Other: <span class='crdesc'>Explain below</span></label></p>";
		echo "<p><label for='cbcr4more'>Additional Information or Comments</label><br/>
	<span class='crdesc'>Please use this text box to record additional information regarding the copyright authorizations obtained, or to explain the Other classification noted above.</span></p>";
		echo "<p></p>";
		echo "<textarea id='cbcr4more' name='cbcr4more' rows='5' cols='40'></textarea>";
		echo "<hr/>";
	}

	public function post_upload_ui_filter() {
	?>
	<style>
	.crdesc { color:#999; }
	div.crdesc li { list-style-type: circle; margin-left:20px; }
	</style>

	<script type="text/javascript">
	var $j = jQuery.noConflict();
	$j(function(){
		$j(".upload-html-bypass").hide();
		$j("#html-upload-ui").hide();
		$j("#plu-upload-ui").hide();
		$j("input[name=cbcr1], input[name=cbcr2], input[name=cbcr3], input[name=cbcr4]").click(function(){
		if($j("#cbcr1, #cbcr2, #cbcr3, #cbcr4").is(':checked')) {
			$j("#html-upload-ui").show();
		} else {
			$j("#html-upload-ui").hide();
		}
		});
	});
	</script>
	<?php
	}

	public function meta_filter_mca($form_fields, $post) {
		$cbcr1 = (bool) get_post_meta($post->ID, '_cbcr1', true);
		$cbcr2 = (bool) get_post_meta($post->ID, '_cbcr2', true);
		$cbcr3 = (bool) get_post_meta($post->ID, '_cbcr3', true);	
			
		$form_fields["copyright_authorization"] = array(
			"label" => __("Copyright Authorization(s)"),
			"input" => "html",
			"html" => "<ul>
				<li><input type='checkbox' ".($cbcr1 ? "checked" : "")."
    name='attachments[{$post->ID}][cbcr1]' 
    id='attachments[{$post->ID}][cbcr1]' /> <label for='attachments[{$post->ID}][cbcr1]'>With the permission of the copyright holder(s)</label></li>
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
	
	public function add_generate_meta($metadata, $attachment_id) {
		update_post_meta($attachment_id, '_cbcr1', $_POST["cbcr1"]);
		update_post_meta($attachment_id, '_cbcr2', $_POST["cbcr2"]);
		update_post_meta($attachment_id, '_cbcr3', $_POST["cbcr3"]);
		update_post_meta($attachment_id, '_cbcr4more', $_POST["cbcr4more"]);
		return $metadata;
	}
	
	public function mca_filter_attachment_fields_to_save($post, $attachment) {
	
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