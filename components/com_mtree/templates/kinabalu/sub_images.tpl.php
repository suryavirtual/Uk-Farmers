<?php 
JHtml::_('stylesheet',$this->jconf['live_site'].$this->mtconf['relative_path_to_js'].'jquery.fancybox-1.3.4.css');
if ( 
	is_array($this->images) 
	&& 
	!empty($this->images)
	): ?>
<div class="row-fluid">
	<div class="images">
	<?php 
		if(isset($showImageSectionTitle) && $showImageSectionTitle) { ?>
		<div class="title"><?php echo JText::_( 'COM_MTREE_IMAGES' ); ?> (<?php 
			if ($this->config->getTemParam('skipFirstImage','0') == 1) {
				echo ($this->total_images-1);
			} else {
				echo $this->total_images;
			}
		 ?>)</div><?php } ?>
		<ul class="thumbnails">
		<?php

		      $database	= JFactory::getDBO();
		     $linkids = $this->link->link_id;
		     $uQuery = "select folder_name from #__folder where link_id='$linkids'";
		     $database->setQuery($uQuery);
             $resfolder = $database->loadObject();
             $foldername=$resfolder->folder_name;
              $usertypes=$this->link->cust_30;
             /* $foldername = preg_replace('/[-`~!@#$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '',$foldername);
               if (preg_match('/\s/',$foldername) )
			   {
			   	$foldername = str_replace(' ','_', $foldername);
			   }
			   else
			   {
			   	$foldername = $foldername;
			   }*/
			   
			   if($usertypes=="Supplier")
					{
                    $usertypes="suppliers";
					}
					if($usertypes=="Member")
					{
					$usertypes="members";	
					}
	/*custom code ends here */				
			$i = 0;
			$totalImages = count($this->images);
			foreach ($this->images AS $image): 

            $new_imagespath=$this->jconf['live_site']."/uf_data/".$usertypes."/".$foldername."/logos/".$image->filename;
				//if( $i == 0 && $this->config->getTemParam('showBigImage','1') == 1 ) 

				if( $i == 0 ) 
				{
					 $new_imagespath=$this->jconf['live_site']."/uf_data/".$usertypes."/".$foldername."/logos/".$image->filename;
					?>
					<!--<li class="span12 uk_pro_output">
					<div class="uk_pro_inner">
					<!--<img id="mainimage" src="<?php echo $this->jconf['live_site'] . $this->mtconf['relative_path_to_listing_medium_image'] . $image->filename;?>" alt="<?php echo $image->filename; ?>" />-->
					<!--<img id="mainimage" src="<?php  echo $new_imagespath;?>" alt="<?php echo $image->filename; ?>" />
						</div>
						<div class="clearfix"></div>
					</li>-->
					<?php 
					//$i++;
					//if( $totalImages == 1 )	continue;
				}
			?>
			<li class="uk_pro_list">
				<a class="listingimage" rel="group1" id="img<?php echo $i; ?>">
					<!--<img src="<?php echo $this->jconf['live_site'] . $this->mtconf['relative_path_to_listing_small_image'] . $image->filename;?>" alt="<?php echo $image->filename; ?>" />-->
					<a class="listingimage" rel="group1" id="img<?php echo $i; ?>">
					<img src="<?php echo $new_imagespath;?>" alt="<?php echo $image->filename; ?>" />
				</a>
			</li>
			<?php 
				$i++;
			endforeach; 
			?>
		</ul>
	</div>
</div>
<style>
.clearfix{clear:both;}
.uk_pro_list {float: left;/*border: 1px solid #eee;*/ padding: 10px;line-height:35px; cursor: pointer;width:80px;}
.uk_pro_output .uk_pro_inner {float: left;/*border: 5px solid #eee;*/padding: 10px; min-width: 150px;}
.uk_pro_output .uk_pro_inner img {width:100%;}
</style>
<script type="text/javascript">
jQuery(function () {
		jQuery(".listingimage").click(function(){
			
   
	var idimg = jQuery(this).attr('id');
	var srcimg=jQuery(this).children('img').attr('src')
    //alert('ID is: '+ idimg+ '\n SRC: '+ srcimg);
    jQuery("#mainimage").attr('src',srcimg);
	});
});
</script>
<?php endif; ?>
