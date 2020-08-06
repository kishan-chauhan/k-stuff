<?php
function htree_addpackage(){
	global $wpdb;
	$error = array();
	if(isset($_POST['savedata'])){
		$package_name 				= $_POST['package_name'];
		$package_description 		= $_POST['package_description'];
		$package_long_description 	= $_POST['package_long_description'];
		$package_price 			= $_POST['package_price'];
		$package_data 			= $_POST['package_data'];
		$package_formula  		= $_POST['package_formula'];
		$package_addon_formula  = $_POST['package_addon_formula']; 
		
		if(empty($package_name)){
			$error[] = "Please enter package name";
		}
		if(empty($package_description)){
			$error[] = "Please enter package description";
		}
		if(empty($package_price)){
			$error[] = "Please enter package price";
		}
		if(empty($package_data)){
			$error[] = "Please enter package data";
		}
		if(empty($package_formula)){
			$error[] = "Please enter package formula";
		}
		
		if(empty($error)){			
				$success = $wpdb->insert(Htree_Package_Tbl, 
								array( "package_name" => $package_name,
										"package_description" => $package_description,
										"package_long_description" => $package_long_description,
										"package_price" => $package_price,
										"package_data" => $package_data,
										"package_formula" => $package_formula,
										"package_addon_formula" => $package_addon_formula				
										));
				 wp_redirect("admin.php?page=package-settings&msg=successadd" );
				 exit;
		}
	}
	if(isset($_GET['action']) =="edit" && isset($_POST['updatedata']) ){
		$packageID					= $_GET['edit-package'];
		$package_name 				= $_POST['package_name'];
		$package_description 		= $_POST['package_description'];
		$package_long_description 	= $_POST['package_long_description'];
		$package_price 			= $_POST['package_price'];
		$package_data 			= $_POST['package_data'];
		$package_formula  		= $_POST['package_formula'];
		$package_addon_formula  = $_POST['package_addon_formula']; 
		
		if(empty($package_name)){
			$error[] = "Please enter package name";
		}
		if(empty($package_description)){
			$error[] = "Please enter package description";
		}
		if(empty($package_price)){
			$error[] = "Please enter package price";
		}
		if(empty($package_data)){
			$error[] = "Please enter package data";
		}
		if(empty($package_formula)){
			$error[] = "Please enter package formula";
		}
		
		if(empty($error)){			
					$success 	= $wpdb->update(Htree_Package_Tbl, 
									array("package_name" => $package_name,
										"package_description" => $package_description,
										"package_long_description" => $package_long_description,
										"package_price" => $package_price,
										"package_data" => $package_data,
										"package_formula" => $package_formula,
										"package_addon_formula" => $package_addon_formula
											),
									array( "package_id" => $packageID ));
					 wp_redirect("admin.php?page=package-settings&msg=successupdate" );
					 exit;
		}
	}
	?>
	<div class="wrap">
		<h1><a href="admin.php?page=package-settings">Manage Package</a></h1>
	<?php
	if(isset($_GET['edit-package'])){
		$package_id 	  = $_GET['edit-package'];
		$data_row	  	  = $wpdb->get_row("select * from " . Htree_Package_Tbl ." WHERE package_id = '" . $package_id."' ");
		$package_name   			= $data_row->package_name;
		$package_description    	= $data_row->package_description;
		$package_long_description   = $data_row->package_long_description;
		$package_price     			= $data_row->package_price;
		$package_data     			= $data_row->package_data;
		$package_formula     		= $data_row->package_formula;
		$package_addon_formula      = $data_row->package_addon_formula;

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
			$package_name   		 = $_POST['package_name'] ? $_POST['package_name'] : $package_name;
			$package_description   		 = $_POST['package_description'] ? $_POST['package_description'] : $package_description;
			$package_long_description   	 = $_POST['package_long_description'] ? $_POST['package_long_description'] : $package_long_description;
			$package_price   = $_POST['package_price'] ? $_POST['package_price'] : $package_price;
			$package_data   = $_POST['package_data'] ? $_POST['package_data'] : $package_data;
			$package_formula  = $_POST['package_formula'] ? $_POST['package_formula'] : $package_formula;
			$package_addon_formula    	 = $_POST['package_addon_formula'] ? $_POST['package_addon_formula'] : $package_addon_formula;
	 }
	?>
	</div>
	<form action="" method = "post" id="manage_form" name="manage_form" class="form-table">
		<table class="wp-list-table widefat fixed hfcm-form-width form-table">
			<tr>
				<td class="th-width">
				    <label>Package Name:<span class="required">*</span></label>
					<input type="text" placeholder="Package Name" class="htree-text" id="package_name" name="package_name" value="<?php if(isset($package_name)){ echo $package_name; } ?>">
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Package description:<span class="required">*</span></label>
					<textarea placeholder="Short Description" class="htree-textarea" name="package_description" id="package_description"><?php if(isset($package_description)) { echo $package_description; }?></textarea>
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Package Long description:</label>
					<textarea placeholder="Long Description" class="htree-textarea" name="package_long_description" id="package_long_description"><?php if(isset($package_long_description)) { echo $package_long_description; }?></textarea>
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Package Price(€):<span class="required">*</span></label>
					<input type="text" placeholder="Package Price" class="htree-text" id="package_price" name="package_price" value="<?php if(isset($package_price)){ echo $package_price; } ?>">
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Package Data:<span class="required">*</span></label>
					<input type="text" placeholder="GB" class="htree-text" id="package_data" name="package_data" value="<?php if(isset($package_data)){ echo $package_data; } ?>">
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Package Formula:<span class="required">*</span></label>
					<input type="text" placeholder="Formula" class="htree-text" id="package_formula" name="package_formula" value="<?php if(isset($package_formula)){ echo $package_formula; } ?>">
				</td>
			</tr>
			<tr>
				<td class="th-width">
				    <label>Package Addon Formula:</label>
					<input type="text" placeholder="Addon Formula" class="htree-text" id="package_addon_formula" name="package_addon_formula" value="<?php if(isset($package_addon_formula)){ echo $package_addon_formula; } ?>">
				</td>
			</tr>
		</table>		
		<div class="wrap">
			<div class="wrap">				
				<div class="wp-core-ui">
					<?php 
					if(isset($_GET['edit-package'])){ ?>
						<input type="submit" name="updatedata" id="updatedata" value="Update" class="button button-primary button-large" /></br></br>
					<?php }else{  ?>
						<input type="submit" name="savedata" id="savedata" value="Save" class="button button-primary button-large" /></br></br>
					<?php } ?>
				</div>
			</div>
		</div>

	

	<?php
}
?>