<?php
error_reporting(0);
$document	= JFactory::getDocument();
$renderer	= $document->loadRenderer('module');
$base = JURI::base();

$contents	= '';
?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCu7I7oNx-DtcR9K94Citxxffg4lyRzwnk"></script> 

<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/colorbox.css">
<script src="<?php echo JURI::root();?>/assets/js/jquery-ui.min.js"></script>
<script src="<?php echo JURI::root();?>/assets/js/jquery.colorbox.js"></script>
<script>
jQuery(document).ready(function(){
	//Examples of how to assign the Colorbox event to elements
	jQuery(".iframe").colorbox({iframe:true, width:"60%", height:"70%"});
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
			
			$url = JURI::root().$links->internal_notes."&tmpl=component";
			
			if(($links->typeFlag == "Member")){ ?>
				<li><a class='iframe' href="<?php echo $url; ?>"><?php echo $links->link_name; ?></li></a>
			<?php }
			if(($links->typeFlag == "Supplier") && ($links->link_name == "United Farmers")){ ?>
				<li><a class='iframe' href="<?php echo $url; ?>"><?php echo $links->link_name; ?></li></a>
			<?php }
		}
	} ?>
	</ul>
</div>

<div class="map-center" style="position:relative; height:576px; float:left; width:79%;">
	<div id="map" style="width: 100%; height: 100%"></div>
	
	<?php foreach($this->links as $links):
		if(($links->typeFlag=='Member') && ($links->postcode!='')){
			$zip[] = $links->postcode;
			$members[] = $links->link_name; 
		} else if(($links->typeFlag=='Supplier') && ($links->postcode!='') && ($links->link_name=='United Farmers')){
			$zip1[] = $links->postcode;
			$members1[] = $links->link_name; 
		}
		$resultZip = array_merge($zip, $zip1);
		$resultMember = array_merge($members, $members1);
	endforeach;
	
	$fzip = implode("','",$resultZip);
	$fmember = implode("','",$resultMember); ?>
	
<script type="text/javascript">
//<![CDATA[

// delay between geocode requests - at the time of writing, 100 miliseconds seems to work well
var delay = 50;

// ====== Create map objects ======
var infowindow = new google.maps.InfoWindow();
var latlng = new google.maps.LatLng(55.942799,-3.2802544);
var mapOptions = {
	zoom: 6,
	center: latlng,
	mapTypeId: google.maps.MapTypeId.ROADMAP
}

var geo = new google.maps.Geocoder(); 
var map = new google.maps.Map(document.getElementById("map"), mapOptions);
var bounds = new google.maps.LatLngBounds();

// ====== Geocoding ======
function getAddress(search, next, memberName) {
	geo.geocode({address:search}, function (results,status){
		// If that was successful
		if (status == google.maps.GeocoderStatus.OK) 
		{	
			// Lets assume that the first marker is the one we want
			var p = results[0].geometry.location;
			var lat=p.lat();
			var lng=p.lng();
			
			createMarker(search,lat,lng,memberName);
        }
			
		// ====== Decode the error status ======
		else {
			// === if we were sending the requests to fast, try this one again and increase the delay
			if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
				nextAddress--;
				delay++;
			} else {
				var reason="Code "+status;
				var msg = 'address="' + search + '" error=' +reason+ '(delay='+delay+'ms)<br>';
				document.getElementById("messages").innerHTML += msg;
			}
		}
		next();
	});
}

// ======= Function to create a marker
function createMarker(add,lat,lng,memberName) {
	var contentString = memberName;
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(lat,lng),
		map: map,
		zIndex: Math.round(latlng.lat()*-100000)<<5
	});
	
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.setContent(contentString); 
		infowindow.open(map,marker);
	});
	
	bounds.extend(marker.position);
}

// ======= An array of locations that we want to Geocode ========
var addresses = ['<?=$fzip?>'];
var members = ['<?=$fmember?>'];

// ======= Global variable to remind us what to do next
var nextAddress = 0;

// ======= Function to call the next Geocode operation when the reply comes back
function theNext() {
	if (nextAddress < addresses.length) {
		setTimeout('getAddress("'+addresses[nextAddress]+'",theNext, "'+members[nextAddress]+'")', delay);
		nextAddress++;
	} else {
		// We're done. Show map bounds
		map.fitBounds(bounds);
	}
}

// ======= Call that function for the first time =======
theNext();
//]]>
</script>
		
</div>
</div>
