<?php
	$exampleID = '"slider1"';
	if(!empty($arrSliders))
		$exampleID = '"'.$arrSliders[0]->getAlias().'"';
		
	$outputTemplates = false;
?>

	<div class='wrap'>
		<div class="clear_both"></div> 

		<div class="title_line" style="margin-bottom:10px">
			<div id="icon-options-general" class="icon32"></div>					
			<a href="<?php echo GlobalsRevSlider::LINK_HELP_SLIDERS?>" class="button-secondary float_right mtop_10 mleft_10" target="_blank"><?php _e("Help",REVSLIDER_TEXTDOMAIN)?></a>			
			
		</div>
		
		<div class="clear_both"></div> 
				
		<?php
		$no_sliders = false;
		if(empty($arrSliders)){
			_e("No Sliders Found",REVSLIDER_TEXTDOMAIN);
			$no_sliders = true;
			?>
			<br><?php
		}else{
			
		?>		
			<div class="title_line nobgnopd">
			<div class="view_title">
			<?php _e("Revolution Sliders",REVSLIDER_TEXTDOMAIN)?>
			</div>
			</div>
		<?php	
		
			require self::getPathTemplate("sliders_list");
		}
				
		?>
		
	<br>
	<?php if($permission): ?>
	
	<p>			
		<a class='button-primary revblue' href='<?php echo $addNewLink?>'><?php _e("Create New Slider",REVSLIDER_TEXTDOMAIN)?> </a>
		<a id="button_import_slider" class='button-primary float_right revgreen' href='javascript:void(0)'><?php _e("Import Slider",REVSLIDER_TEXTDOMAIN)?> </a>		
	</p>
	
	<?php endif; ?>
	
		
	
	<!-- Import slider dialog -->
	<div id="dialog_import_slider" title="<?php _e("Import Slider",REVSLIDER_TEXTDOMAIN)?>" class="dialog_import_slider" style="display:none">
		<form action="<?php echo UniteBaseClassRev::$url_ajax?>" enctype="multipart/form-data" method="post">
		    <br>
		    <input type="hidden" name="action" value="uniterevolution_ajax_action">
		    <input type="hidden" name="client_action" value="import_slider_slidersview">
		    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce("revslider_actions"); ?>">
		    <?php _e("Choose the import file",REVSLIDER_TEXTDOMAIN)?>:   
		    <br>
			<input type="file" size="60" name="import_file" class="input_import_slider">
			<br><br>
			<span style="font-weight: 700;"><?php _e("Note: custom styles will be updated if they exist!",REVSLIDER_TEXTDOMAIN)?></span><br><br>
			<table>
				<tr>
					<td><?php _e("Custom Animations:",REVSLIDER_TEXTDOMAIN)?></td>
					<td><input type="radio" name="update_animations" value="true" checked="checked"> <?php _e("overwrite",REVSLIDER_TEXTDOMAIN)?></td>
					<td><input type="radio" name="update_animations" value="false"> <?php _e("append",REVSLIDER_TEXTDOMAIN)?></td>
				</tr>
				<tr>
					<td><?php _e("Static Styles:",REVSLIDER_TEXTDOMAIN)?></td>
					<td><input type="radio" name="update_static_captions" value="true" checked="checked"> <?php _e("overwrite",REVSLIDER_TEXTDOMAIN)?></td>
					<td><input type="radio" name="update_static_captions" value="false"> <?php _e("append",REVSLIDER_TEXTDOMAIN)?></td>
				</tr>
			</table>
			<br><br>
			<input type="submit" class='button-primary' value="<?php _e("Import Slider",REVSLIDER_TEXTDOMAIN)?>">
		</form>		
		
	</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function(){
			RevSliderAdmin.initSlidersListView();
		});
	</script>
	