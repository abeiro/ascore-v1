<?php

class coreGmaps {
	 
	var $buffer;
	
	function coreGmaps($gcode,$container) {
		
		$this->buffer='<script type="text/javascript" 
src="http://www.google.com/jsapi?key='.$gcode.'
"></script>';
		
	}
	
	
	function showPoint($x,$y,$leyend,$html) {
		
		$this->buffer.='
<script type="text/javascript">
google.load("maps", "2");
google.load("search", "1");
  
function initialize() {
        var map = new google.maps.Map2(document.getElementById("map"));
        map.setCenter(new google.maps.LatLng('.$x.','.$y.'), 13);
		map.setZoom(15);
		map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());	
		//map.disableDragging();
		markerOptions = { title:"'.$leyend.'" };
        var point = new GLatLng('.$x.','.$y.');
		var marker = new GMarker(point, markerOptions);
        map.addOverlay(marker);
		GEvent.addListener(marker, "click", function() {
            marker.openInfoWindowHtml("'.$html.'");
        });
		
      }
  google.setOnLoadCallback(initialize);
</script>
';
		
	}
	function GetUrlEncoded($address,$city)
	{
			return  "http://maps.google.es/maps?f=q&hl=es&geocode=&q=".urlencode($address).",".urlencode($city);

	}

 
}
 
 
?>