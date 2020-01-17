<?php

//$numOfColumns = $this->config->getTemParam('numOfColumns',2);
//$displayIndexListingCount = $this->config->getTemParam('displayIndexListingCount',1);
//$displayIndexCatCount = $this->config->getTemParam('displayIndexCatCount',0);
//$numOfSubcatsToDisplay = $this->config->getTemParam('numOfSubcatsToDisplay',3);

#
# Load category#-header-id# modules
#

$document	= JFactory::getDocument();
$renderer	= $document->loadRenderer('module');
$base = JURI::base();

$contents	= '';
?>

<link rel="stylesheet" type="text/css" href="<?php echo JURI::root(); ?>/assets/css/tooltipster.css" />
<script type="text/javascript" src="<?php echo JURI::root(); ?>/assets/js/jquery.maphilight.js"></script>
<script type="text/javascript" src="<?php echo JURI::root(); ?>/assets/js/jquery.tooltipster.js"></script>

<script type="text/javascript">
jQuery(window).load(function(){
	var url1 = '<?=JURI::root()?>';
	var furl = url1+'/index.php?option=com_content&view=article&layout=edit&id=29&tmpl=component';
	jQuery.get( furl, function( data ) {
		//jQuery( "#member-content" ).html( data );
	});
});

jQuery(function() {
	jQuery('.tooltip').tooltipster();
	var url1 = '<?=JURI::root()?>';
	jQuery('.memName').click(function(e){
		e.preventDefault();
		var cur_class = jQuery(this).find('li').attr('class');
		jQuery('p#'+cur_class).click();
	});
	
	jQuery('.memImg').click(function(e){
		e.preventDefault();
		var cur_class = jQuery(this).find('li').attr('class');
		jQuery('img#'+cur_class).click();
	});
	
	jQuery('.mapCircle').click(function() { 
        var url = jQuery(this).attr('data-url');
		var furl = url1+'/'+url+'&tmpl=component';
		
		var id = jQuery(this).attr('id');
		console.log(id);
		jQuery('ul#memberName li').removeClass('highlight');
		jQuery('ul#memberName li.' + id).addClass('highlight');
        		
		jQuery('p.mapCircle').css({'background':'#c6d941', 'border':'1px solid #ff0000'});
		jQuery(this).css({'background':'#c6d941', 'border':'1px solid #6B7712'});
		
		jQuery('p.mapCircle').removeClass('mapCircleH');
		jQuery(this).addClass('mapCircleH');
		
		var top = jQuery(this).css('top');
		top = top.split('px');
		top = (parseFloat(top[0]) - 3) + 'px';

		var left = jQuery(this).css('left');
		left =  left.split('px'); 
		left = (parseFloat(left[0]) - 3) + 'px';
		
		jQuery(this).css('top', top).css('left', left);
		
		if(url != '#'){
			jQuery.get( furl, function( data ) {
				//jQuery( "#member-content" ).html( data );
			});
		}
    }); 
	
	jQuery('.mapImg').click(function() { 
        var url = jQuery(this).attr('data-url');
		var furl = url1+'/'+url+'&tmpl=component';
        
		var id = jQuery(this).attr('id');
		
		jQuery('ul#memberName li').removeClass('highlight');
		jQuery('ul#memberName li.' + id).addClass('highlight');
		
		jQuery('p.mapCircle').removeClass('mapCircleH');
		jQuery('p.mapCircle').css({'background':'#c6d941', 'border':'1px solid #ff0000'});
		jQuery(this).addClass('mapCircleH');
		
		if(url != '#'){
			jQuery.get( furl, function( data ) {
				//jQuery( "#member-content" ).html( data );
			});
		}
    });
});
</script>

<div>
<div class="mem-name" style="float:left; width:20%;">
	<h1>Members</h1>
	<ul id="memberName">
	<?php foreach($this->links as $links){

		if($links->showMap == 'Yes'){
			$cord = explode(",",$links->cordinate);
			$id = $cord[0].$cord[1].'IMAGE';
			$temp = explode(".",$id);
			$fid = implode("_",$temp);
			
			if(($links->typeFlag == "Member")){ ?>
				<a href="#" class="memName"><li class="<?php echo $fid; ?>"><?php echo $links->link_name; ?></li></a>
			<?php }
			if(($links->typeFlag == "Supplier") && ($links->link_name == "United Farmers")){ ?>
				<a href="#" class="memImg"><li class="<?php echo $fid; ?>"><?php echo $links->link_name; ?></li></a>
			<?php }
		}
	} ?>
	</ul>
</div>
<div class="map-center" style="position:relative; height:576px; float:left; width:40%; margin-left: 20%;">
	<img src="<?php echo JURI::root() . 'images/htmlmaps/mem-map.png' ?>" width="450" height="521" class="map" usemap="#simple" style="position:absolute;">
	<?php foreach($this->links as $links){

		if($links->showMap == 'Yes'){
			$cord = explode(",",$links->cordinate);
			$id = $cord[0].$cord[1].'IMAGE';
			$temp = explode(".",$id);
			$fid = implode("_",$temp);
			
			if(!empty($links->internal_notes)){
				$url = $links->internal_notes;
			} else {
				$url = "#";
			}
			
			if($links->typeFlag == "Member"){ ?>
				<p class="tooltip mapCircle" id="<?php echo $fid; ?>" style="position:absolute;top:<?php echo $cord[1]-10; ?>px;left:<?php echo $cord[0]-10; ?>px;z-index:99999; opacity:1 !important;" title="<?php echo $links->link_name; ?>" data-url="<?php echo $url; ?>">&nbsp;</p>
			<?php }
			if(($links->showMap == 'Yes') && ($links->typeFlag == "Supplier") && ($links->link_name == "United Farmers")){ ?>
				<img class="tooltip mapImg" src="<?php echo JURI::root();?>/logo-circle-a.png" id="<?php echo $fid; ?>" style="position:absolute;top:<?php echo $cord[1]-20; ?>px;left:<?php echo $cord[0]-20; ?>px;z-index:99999; opacity:1 !important; border:1px solid #6B7712; border-radius:13px; box-shadow:2px 2px 4px #000;" title="<?php echo $links->link_name; ?>" data-url="<?php echo $url; ?>">
			<?php }
		}
	} ?>
	
</div>
<!--<div class="" style="float:left; width:40%; text-align:left;" id="member-content">
		
</div>-->
</div>
