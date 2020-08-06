<?php
/*
Add control 
*/
function htree_addcontrol(){
	global $wpdb;
	$error = array();
	if(isset($_POST['savedata'])){
		$icons_id 			= $_POST['icons_id'];
		$header_text 		= $_POST['header_text'];
		$control_type	    = $_POST['control_type'];
		$control_label	    = $_POST['control_label'];
		$control_min_value  = $_POST['control_min_value'];
		$control_max_value  = $_POST['control_max_value'];
		$minimum_mb_percontrol = $_POST['minimum_mb_percontrol'];
		$control_name_color = $_POST['control_name_color'];
		$control_custom_css = $_POST['control_custom_css'];
		$control_bg_color 	= $_POST['control_bg_color'];
		$control_text_color = $_POST['control_text_color'];
		$custom_html 		= $_POST['custom_html'];
		$custom_css		 	= $_POST['custom_css'];
		
		if(empty($icons_id)){
			$error[] = "Please select icon";
		}
		if(empty($header_text)){
			$error[] = "Please enter small header text";
		}
		if(empty($control_type)){
			$error[] = "Please enter control name";
		}
		if(empty($control_label)){
				$error[] = "Please enter control label";
			}
		if(!isset($control_min_value)){
			$error[] = "Please enter control min value";
		}
		if(!isset($control_max_value)){
			$error[] = "Please enter control max value";
		}
		if(!isset($control_min_value) && ($control_min_value < 0) && ($control_min_value < $control_max_value) ){
			$error[] = "Minimum value don't enter zero less then max value";
		}
		if(!isset($control_max_value)){
			if(($control_max_value < 0) || ($control_max_value < $control_min_value) ){
				$error[] = "Maximum value don't enter zero or max value not less then min value";
			}
		}
		if(empty($minimum_mb_percontrol)){
			$error[] = "Please enter minimum MB value";
		}
		if(empty($error)){			
				$success 	= $wpdb->insert(Htree_Customer_Tbl, 
								array( "icons_id" => $icons_id,
										"header_text" => $header_text,
										"control_type" => $control_type,
										"control_label" => $control_label,
										"control_min_value" => $control_min_value,
										"control_max_value" => $control_max_value,
										"control_name_color" => $control_name_color,
										"minimum_mb_percontrol" => $minimum_mb_percontrol,
										"control_custom_css" => $control_custom_css,
										"control_bg_color" => $control_bg_color,
										"control_text_color" => $control_text_color,
										"custom_html" => $custom_html,
										"custom_css" => $custom_css										
										));
				 wp_redirect("admin.php?page=internet-data-setting&msg=successadd" );
				 exit;
		}
	}	
		
	if(isset($_GET['action']) =="edit" && isset($_POST['updatedata']) ){
		
			$controlId			= $_GET['edit-control'];
			$icons_id 			= $_POST['icons_id'];
			$header_text 		= $_POST['header_text'];
			$control_type	    = $_POST['control_type'];
			$control_label	    = $_POST['control_label'];
			$control_min_value  = $_POST['control_min_value'];
			$control_max_value  = $_POST['control_max_value'];
			$minimum_mb_percontrol = $_POST['minimum_mb_percontrol'];
			$control_name_color = $_POST['control_name_color'];
			$control_custom_css = $_POST['control_custom_css'];
			$control_bg_color 	= $_POST['control_bg_color'];
			$control_text_color = $_POST['control_text_color'];
			$custom_html 		= $_POST['custom_html'];
			$custom_css		 	= $_POST['custom_css'];
			
			if(empty($icons_id)){
				$error[] = "Please select icon";
			}
			if(empty($header_text)){
				$error[] = "Please enter small header text";
			}
			if(empty($control_type)){
				$error[] = "Please enter control name";
			}
			if(empty($control_label)){
				$error[] = "Please enter control label";
			}
			if(!isset($control_min_value)){
				$error[] = "Please enter control min value";
			}
			if(!isset($control_max_value)){
				$error[] = "Please enter control max value";
			}
			if(!isset($control_min_value) && ($control_min_value < 0) && ($control_min_value < $control_max_value) ){
				$error[] = "Minimum value don't enter zero less then max value";
			}
			if(!isset($control_max_value) && ($control_max_value < 0) && ($control_max_value > $control_min_value) ){
				$error[] = "Maximum value don't enter zero max then min value";
			}
			if(empty($minimum_mb_percontrol)){
				$error[] = "Please enter minimum MB value";
			}
			
			if(empty($error)){			
					$success 	= $wpdb->update(Htree_Customer_Tbl, 
									array(  "icons_id" => $icons_id,
											"header_text" => $header_text,
											"control_type" => $control_type,
											"control_label" => $control_label,
											"control_min_value" => $control_min_value,
											"control_max_value" => $control_max_value,
											"minimum_mb_percontrol" => $minimum_mb_percontrol,
											"control_name_color" => $control_name_color,
											"control_custom_css" => $control_custom_css,
											"control_bg_color" => $control_bg_color,
											"control_text_color" => $control_text_color,
											"custom_html" => $custom_html,
											"custom_css" => $custom_css	
											),
									array( "id" => $controlId ));
					 wp_redirect("admin.php?page=internet-data-setting&msg=successupdate" );
					 exit;
			}
	}
	
	?>
	<div class="wrap">
		<h1><a href="admin.php?page=internet-data-setting">Manage Control</a> | Add Control</h1>
	</div>
	<?php
		
	 if(isset($_GET['edit-control'])){
		$controlId 	  = $_GET['edit-control'];
		$data_row   = $wpdb->get_row("select * from " . Htree_Customer_Tbl ." WHERE id = '" . $controlId."' ");
		$icons_id   		= $data_row->icons_id;
		$header_text  		= $data_row->header_text;
		$control_type   	= $data_row->control_type;
		$control_label   	= $data_row->control_label;
		$control_min_value  = $data_row->control_min_value;
		$control_max_value  = $data_row->control_max_value;
		$minimum_mb_percontrol = $data_row->minimum_mb_percontrol;
		$control_name_color = $data_row->control_name_color;
		$control_custom_css = $data_row->control_custom_css;
		$control_bg_color 	= $data_row->control_bg_color;
		$control_text_color = $data_row->control_text_color;
		$custom_html  	 	= $data_row->custom_html;
		$custom_css  	 	= $data_row->custom_css;
	 }
	 
	 //display error if empty or other
	 if(!empty($error)) {
			$i=1;
			$class = "<span class='error'>";
			foreach($error as $errorData){
				$class .=  '<span style="color:black">'.$i.")</span> ".$errorData." ";
				$i++;
			}
			echo $class .="</span>";
			$icons_id   		 = $_POST['icons_id'] ? $_POST['icons_id'] : $icons_id;
			$header_text   		 = $_POST['header_text'] ? $_POST['header_text'] : $header_text;
			$control_type   	 = $_POST['control_type'] ? $_POST['control_type'] : $control_type;
			$control_label   	 = $_POST['control_label'] ? $_POST['control_label'] : $control_label;
			$control_min_value   = $_POST['control_min_value'] ? $_POST['control_min_value'] : $control_min_value;
			$control_max_value   = $_POST['control_max_value'] ? $_POST['control_max_value'] : $control_max_value;
			$minimum_mb_percontrol = $_POST['minimum_mb_percontrol'] ? $_POST['minimum_mb_percontrol'] : $minimum_mb_percontrol;
			$control_name_color  = $_POST['control_name_color'] ? $_POST['control_name_color'] : $control_name_color;
			$control_custom_css  = $_POST['control_custom_css'] ? $_POST['control_custom_css'] : $control_custom_css;
			$control_bg_color  	 = $_POST['control_bg_color'] ? $_POST['control_bg_color'] : $control_bg_color;
			$control_text_color  = $_POST['control_text_color'] ? $_POST['control_text_color'] : $control_text_color;
			$custom_html    	 = $_POST['custom_html'] ? $_POST['custom_html'] : $custom_html;
			$custom_css    		 = $_POST['custom_css'] ? $_POST['custom_css'] : $custom_css;
	 }
	 ?>
	
	<form action="" method = "post" id="manage_form" name="manage_form" class="form-table">
		<table class="wp-list-table widefat fixed hfcm-form-width form-table">
			<tr>
				<?php
				if(isset($_POST['icons_id']) || isset($icons_id)){
					$icons_id = $_POST['icons_id'] ? $_POST['icons_id'] : $icons_id;
					$image_src = wp_get_attachment_url( $icons_id );				
					$attachment = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE guid='".$image_src."' ");				
					$image_id = $attachment->ID;
					$image_src = wp_get_attachment_image_src($image_id, 'thumbnail');
					$image_src = $image_src[0];
				}
				?>
				<td class="th-width">
				    <label>Icon:<span class="required">*</span></label>
					<a href="javascript:void(0);" onclick="edit_post_image('post_image_div');" class="" >Add Icon</a>
						<?php if(!empty($image_src)){ ?>
							<div id="post_image_div"><img src="<?php echo $image_src; ?>" width=""></div>
						<?php }else{ ?>
							<div id="post_image_div"></div>
						<?php } ?>
				</td>
				<input type="hidden" name="icons_id" id="icons_id" value="<?php if(isset($icons_id)){ echo $icons_id; } ?>" >
			</tr>
			<tr>
				<td class="th-width">
				    <label>Header Text:<span class="required">*</span></label>
					<input type="text" placeholder="Header Text" class="htree-text" id="header_text" name="header_text" value="<?php if(isset($header_text)){ echo $header_text; } ?>">
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Control Type:<span class="required">*</span></label>
					<input type="text" placeholder="Control Name" class="htree-text" id="control_type" name="control_type" value="<?php if(isset($control_type)){ echo $control_type; } ?>">
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Control Label:<span class="required">*</span></label>
					<input type="text" placeholder="Control Label(Devices, Hours, GB etc.)" class="htree-text" id="control_label" name="control_label" value="<?php if(isset($control_label)){ echo $control_label; } ?>">
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Control Value:<span class="required">*</span></label>
					<input type="number" placeholder="Control Min Value" class="htree-text-small" id="control_min_value" name="control_min_value" value="<?php if(isset($control_min_value)){ echo $control_min_value; } ?>">
					<input type="number" placeholder="Control Max Value" class="htree-text-small" id="control_max_value" name="control_max_value" value="<?php if(isset($control_max_value)){ echo $control_max_value; } ?>">
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Minimum MB:</label>
					<input  type="number" placeholder="Minimum MB" class="htree-text" id="minimum_mb_percontrol" name="minimum_mb_percontrol" value="<?php if(isset($minimum_mb_percontrol)){ echo $minimum_mb_percontrol; } ?>">
					(For ex. 1 Device = 10MB)
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Control Heading color :</label>
					<span id="controltypecolor"><input  type="text" placeholder="Control Heading Color" class="htree-text" id="control_name_color" name="control_name_color" value="<?php if(isset($control_name_color)){ echo $control_name_color; } ?>">
					</span>
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Control Color :</label>
					<span id="controlcolor"><input  type="text" placeholder="Control Color" class="htree-text" id="control_custom_css" name="control_custom_css" value="<?php if(isset($control_custom_css)){ echo $control_custom_css; } ?>"></span>
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Control Background Color :</label>
					<span id="controlbgcolor"><input  type="text" placeholder="Control Background Color" class="htree-text" id="control_bg_color" name="control_bg_color" value="<?php if(isset($control_bg_color)){ echo $control_bg_color; } ?>"></span>
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Control Text Color :</label>
					<span id="controltextcolor"><input  type="text" placeholder="Control Text Color" class="htree-text" id="control_text_color" name="control_text_color" value="<?php if(isset($control_text_color)){ echo $control_text_color; } ?>"></span>
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Custom Html :</label>
					<?php 
						$my_content=$custom_html; // this var may contains previous data that is stored in mysql. 
						wp_editor($my_content,"custom_html", array('teeny'=>false,'media_buttons' => false,'textarea_rows'=>8, 'editor_class'=>'htree-tinymce')); 
					?>
					</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Custom Css :</label>
					<textarea placeholder="Custom Css" class="htree-textarea-control" id="custom_css" name="custom_css" ><?php if(isset($custom_css)){ echo $custom_css; } ?></textarea>
				</td>
			</tr>
		</table>
		<div class="wrap">
			<div class="wrap">				
				<div class="wp-core-ui">
					<?php 
					if(isset($_GET['edit-control'])){ ?>
						<input type="submit" name="updatedata" id="updatedata" value="Update" class="button button-primary button-large" /></br></br>
					<?php }else{  ?>
						<input type="submit" name="savedata" id="savedata" value="Save" class="button button-primary button-large" /></br></br>
					<?php } ?>
				</div>
			</div>
		</div>
	</form>
	
	<script type="text/javascript">
	jQuery(document).ready(function($){
		  var controltypecolor = document.querySelector('#controltypecolor');
		  var controlcolor = document.querySelector('#controlcolor');
		  var controlbgcolor = document.querySelector('#controlbgcolor');
		  var controltextcolor = document.querySelector('#controltextcolor');
		  
		  var controltypecolor = new Picker(controltypecolor);
		  controltypecolor.onChange = function(color) {
				$('#control_name_color').val(color.hex);
		  };
		  var controlcolor = new Picker(controlcolor);
		  controlcolor.onChange = function(color) {
				$('#control_custom_css').val(color.hex);
		  };
		  var controlbgcolor = new Picker(controlbgcolor);
		  controlbgcolor.onChange = function(color) {
				$('#control_bg_color').val(color.hex);
		  };
		  
		  var controltextcolor = new Picker(controltextcolor);
		  controltextcolor.onChange = function(color) {
				$('#control_text_color').val(color.hex);
		  };
	});
	function edit_post_image(profile_image_id){
				var image_gallery_frame;
                image_gallery_frame = wp.media.frames.downloadable_file = wp.media({
                    // Set the title of the modal.
                    title: 'Select Device Image',
                    button: {
                        text: 'Add',
                    },
                    multiple: false,
                    displayUserSettings: true,
                });                
                image_gallery_frame.on( 'select', function() {
                    var selection = image_gallery_frame.state().get('selection');
                    selection.map( function( attachment ) {
                        attachment = attachment.toJSON();
						console.log(attachment);
                        if ( attachment.id ) {
							jQuery('#'+profile_image_id).html('<img  class="img-responsive"  src="'+attachment.sizes.full.url+'">');
							jQuery('#icons_id').val(attachment.id ); 
							//jQuery('#event_image_edit').html('<button type="button" onclick="event_post_image(\'event_image_div\');"  class="btn btn-xs green-haze">Edit</button> &nbsp;<button type="button" onclick="remove_event_image(\'event_image_div\');"  class="btn btn-xs green-haze">Remove</button>');  
						   
						}
					});
                   
                });               
				image_gallery_frame.open(); 
	}
	</script>
		
<?php
}
function Deletefolder($path)
{
    if (is_dir($path) === true)
    {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file)
        {
            Deletefolder(realpath($path) . '/' . $file);
        }

        return rmdir($path);
    }

    else if (is_file($path) === true)
    {
        return unlink($path);
    }

    return false;
}
?>