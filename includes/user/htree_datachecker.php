<?php
function gregory_enqueue_stylesheets() {
    $base = get_stylesheet_directory_uri();
    wp_register_style('stylefront',DATA_CHECKER_ASSETS. 'css/stylefront.css');wp_enqueue_style('stylefront');
    return;
}
add_action( 'wp_enqueue_scripts', 'gregory_enqueue_stylesheets', 18 );
add_shortcode('data-checker-form','htree_datachecker');
function htree_datachecker( $atts = null ){	
	global $wpdb;
	?>
	<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
     <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<div class="htreeDatachecker">
		<div class="container htreeDatachecker">
			<div class="row"> 
				<div>
					<div class="small-12 large-9 large-collapse columns">
						<h2>Move the slider as required to see our suggested Monthly Package see our suggested Monthly Package</h2>
						<p>**  The chosen package is only a suggestion based on your usage.  Heavy usage may require a higher package after purchase.</p>
					 </div>
					 <div class="clear"></div>
					 
					 <div class="row sliderstart">
					 <?php 
						$data_result = $wpdb->get_results("select * from " . Htree_Customer_Tbl ." order by id asc ", 'ARRAY_A');
						
						//print_r($data_result);
						$i=1;
						foreach($data_result as $datacontrol){
							$image_src = wp_get_attachment_url( $datacontrol['icons_id'] );
							$attachment = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE guid='".$image_src."' ");				
							$image_id = $attachment->ID;
							$image_src = wp_get_attachment_image_src($image_id, 'thumbnail');
							$image_src = $image_src[0];
							$controlnamewithoutspace = str_replace(" ","_",$datacontrol['control_type']);
							
							if(!empty($datacontrol['custom_html'])){
								$classwidth = 	"col-lg-12";
								$subclasswidth = 	"col-lg-6";
							}else{
								$classwidth = 	"col-lg-6";
								$subclasswidth = 	"col-lg-12";
							}
							?>
							<style>#controlname_<?php echo $i; ?>{color:<?php echo $datacontrol['control_name_color'];  ?>}
							#controlname_<?php echo $i; ?> p{color:<?php echo $datacontrol['control_text_color'];  ?>}
							#slider-<?php echo $controlnamewithoutspace; ?> .ui-slider-handle{background:<?php echo $datacontrol['control_custom_css'];?> }
							#slider-<?php echo $controlnamewithoutspace; ?> .ui-widget-header{background:<?php echo $datacontrol['control_bg_color'];?>}
							#control_customtext_<?php echo $i; ?>{color:<?php echo $datacontrol['control_text_color'];  ?>}
							</style>
							<div class="slider-row <?php echo $classwidth; ?> columns">
							<div class="row">
								<div class="<?php echo $subclasswidth; ?>">	
									<div class="slider-controlname">
											<img class="htree-datachecker-icon" src="<?php echo $image_src; ?>" width="40px" height="40px" /> 
											<h3 class="htree-controlname" id="controlname_<?php echo $i; ?>"><?php echo $datacontrol['control_type']; ?>
											<p>&nbsp;<span> <?php echo $datacontrol['header_text']; ?> </span></p></h3>
									</div>
										<span class="slider-label"><?php echo $datacontrol['control_label']; ?></span>
										<span id="minval-<?php echo $controlnamewithoutspace; ?>" class="slider_value"><?php echo $datacontrol['control_min_value']; ?></span>
										<div class="clear"></div>
										<div id="slider-<?php echo $controlnamewithoutspace; ?>"></div>
										<div class="number-fix">
											<div class="number-fix-prev"><?php echo $datacontrol['control_min_value']; ?></div>
											<div class="number-fix-next"><?php echo $datacontrol['control_max_value']; ?></div>
											<div class="clear"></div>
										</div>
										<script>
										jQuery(document).ready(function($){
											$( "#slider-<?php echo $controlnamewithoutspace; ?>" ).slider({
											   orientation:"horzontal",
											   range: "min",
											   value:<?php echo $datacontrol['control_min_value']; ?>,
											   max:<?php echo $datacontrol['control_max_value']; ?>,
												animate: true,
											   slide: function( event, ui ) {
												  $( "#minval-<?php echo $controlnamewithoutspace; ?>" ).text( ui.value );
											   }	
											});
											$( "#slider-<?php echo $controlnamewithoutspace; ?>" ).on( "slidechange", function( event, ui ) {
													var getslider<?php echo $controlnamewithoutspace; ?> = $( "#slider-<?php echo $controlnamewithoutspace; ?>" ).slider( "value" );
													var no_of_devices = $("#minval-<?php echo $controlnamewithoutspace; ?>").text();
													
											});
										});
										</script>
								</div>
								<?php 
								if(!empty($datacontrol['custom_html'])){
								?>
								<div class="col-lg-6 col-sm-12">
									<div id="control_customtext_<?php echo $i; ?>" class="control_customhtml"><?php echo nl2br($datacontrol['custom_html']); ?></div>
								</div>
								<?php } ?>	
								</div>
							</div>	
						<?php 
						$i++;
						}
						?>
					 
					 </div>
					 <?php
					 $data_package = $wpdb->get_results("select * from " . Htree_Package_Tbl ." order by package_id asc ", 'ARRAY_A');					
					 ?>
					 <div class="datapackage">
						<div class="packageheading"><h2>Suggested Lightnet Package</h2></div>
						
						<div class="row">
							<div class="col-lg-12 col-sm-12">
								<ul class="list-inline">
									<?php foreach($data_package as $datapack){
										$packageName = str_replace(" ","",$datapack['package_name']);
 										?>
										<li>
											<a href="#" class="button info slider-btn" id="<?php echo $packageName; ?>" role="button"><?php echo $datapack['package_name'];?><br><span><?php echo $datapack['package_description']; ?> - €<?php echo $datapack['package_price']; ?></span></a>
										</li>
									<?php } ?>
								</ul>
							</div>
						</div>
						
						<div class="row">
							<div class="col-lg-12 col-sm-12">
								<p><b>Estimated Monthly Data Usage:</b> <span class="data_first"> 0.00gb</span></p>
								<h3 class="dataaddon-title">Suggested Data Burst Add-on</h3>
							</div>	
							<div class="col-lg-4 col-sm-12">	
								<div class="data_addon">
									<span class="addontext col-lg-6">
										Based on your data choices, we suggest the following "Data Burst" add-on to ensure best value surfing..
									</span>
									<span class="dataaddon_box ">Data Add-on<br>
										<span class="data">0GB</span><br>
										<span id="price_data_add_on">€0 Per Month </span>
									</span>
								</div>
							</div>	
						</div>
						
					 </div>

				</div>
			</div>
		</div>
	</div>	
	<?php

}
?>