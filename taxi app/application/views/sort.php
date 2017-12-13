
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Maps JavaScript API Example: Polygon</title>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&amp;libraries=geometry"></script> 

<script type="text/javascript" src="<?php echo base_url();?>/public/js/convex_hull.js"></script> 

    <script type="text/javascript">
//<![CDATA[
    
    var gmarkers = [];
    var points = [];
    var hullPoints = [];
    var map = null;
    var polyline;

    var infowindow = new google.maps.InfoWindow(
      { 
        size: new google.maps.Size(150,50)
      });
/*
points = [
new GLatLng(37.455949,-122.184578),
new GLatLng(37.426063,-122.112959),
new GLatLng(37.442271,-122.099669),
new GLatLng(37.462248,-122.160239),
new GLatLng(37.454942,-122.140154),
new GLatLng(37.434649,-122.151661),
new GLatLng(37.439245,-122.119776),
new GLatLng(37.441485,-122.163136),
new GLatLng(37.450725,-122.119423),
new GLatLng(37.457801,-122.117583)
]
*/


function initialize() {
  var myOptions = {
    zoom: 13,
    center: new google.maps.LatLng(35.693323,51.423146),
    mapTypeControl: true,
    mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
    navigationControl: true,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  map = new google.maps.Map(document.getElementById("map_canvas"),
                                myOptions);
 
  google.maps.event.addListener(map, 'click', function() {
        infowindow.close();
        });
 
  google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
        // Add 3 markers to the map at random locations
        var bounds = map.getBounds();
        var southWest = bounds.getSouthWest();
        var northEast = bounds.getNorthEast();
        var lngSpan = northEast.lng() - southWest.lng();
        var latSpan = northEast.lat() - southWest.lat();
	map.setCenter(map.getCenter());
        map.setZoom(map.getZoom()-1);
/* */ 
var i = 0;
<?php $z=0;
$even = $this->db->get('tbl_polygon1')->result();
// print_r($even);die;
foreach ($even as $k) { ?>
       // for (var i = 0; i < 10; i++) {
          var point = new google.maps.LatLng(<?php echo($k->latitude)?>, <?php echo($k->longitude)?>);
          points.push(point);
          i++;
    var marker = createMarker(point, i);
    gmarkers.push(marker);
        // } 
  <?php $z++;
} ?>

// var i = 0;
// var points_v = [];
// <?php $z=0;
// $even = $this->db->get('tbl_users')->result();
// // print_r($even);die;
// foreach ($even as $k) { ?>
//        // for (var i = 0; i < 10; i++) {
//         if (<?php echo($k->latitude)?>!="") {
//           var point_v = new google.maps.LatLng(<?php echo($k->latitude)?>, <?php echo($k->longitude)?>);
//           points_v.push(point_v);
//           i++;
//     var marker_v = createMarker_visible(point_v, i);
//         };
          
//     // gmarkers.push(marker);
//         // } 
//   <?php $z++;
// } ?>

/* */
/*

        for (var i = 0; i<points.length; i++) {
	  var marker = createMarker(points[i], i);
	  gmarkers.push(marker);
          map.addOverlay(marker);
        }
*/
      for (var i=0; i < points.length; i++) {
        document.getElementById("input_points").innerHTML += points[i].toUrlValue()+"<br>";
      }
   
      sortPoints2Polygon();
    });
        google.maps.event.addListener(map, "click", function(evt) {
          if (evt.latLng) {
            var latlng = evt.latLng;
//            alert("latlng:"+latlng.toUrlValue());
            var marker = createMarker(latlng, gmarkers.length-1);
	    points.push(latlng);
            gmarkers.push(marker);
            sortPoints2Polygon();
	    }
      // console.log(points);
      
      });
}

function removeMarker(latlng) {
       for (var i= 0; i < gmarkers.length;i++) {
         if (google.maps.geometry.spherical.computeDistanceBetween(
               latlng, gmarkers[i].getPosition()) < 0.1) 
         {
	    gmarkers[i].setMap(null);
            gmarkers.splice(i,1);
         }
       }
       sortPoints2Polygon();
}

function createMarker(latlng, marker_number) {
    var html = "marker "+marker_number;
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
	      draggable: true
        });
    google.maps.event.addListener(marker, 'dragend', function() {
      sortPoints2Polygon();
    });
    // marker.setVisible(false);
    google.maps.event.addListener(marker, 'click', function() {
        var contentString = html + "<br>"+marker.getPosition().toUrlValue()+"<br><a href='javascript:removeMarker(new google.maps.LatLng("+marker.getPosition().toUrlValue()+"));'>Remove Marker</a>";
        infowindow.setContent(contentString); 
        infowindow.open(map,marker);
        });
    return marker;
}

function createMarker_visible(latlng, marker_number) {
    var html = "marker "+marker_number;
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        draggable: true,
        zIndex: Math.round(latlng.lat()*-100000)<<5
        });
    google.maps.event.addListener(marker, 'dragend', function() {
      sortPoints2Polygon();
    });
    // marker.setVisible(false);
    google.maps.event.addListener(marker, 'click', function() {
        var contentString = html + "<br>"+marker.getPosition().toUrlValue()+"<br><a href='javascript:removeMarker(new google.maps.LatLng("+marker.getPosition().toUrlValue()+"));'>Remove Marker</a>";
        infowindow.setContent(contentString); 
        infowindow.open(map,marker);
        });
    return marker;
}
 
function sortPoints2Polygon() {
      if (polyline) polyline.setMap(null);
      document.getElementById("hull_points").innerHTML = "";
      points = [];
      var bounds = new google.maps.LatLngBounds(); 
      for (var i=0; i < gmarkers.length; i++) {
        points.push(gmarkers[i].getPosition());
	bounds.extend(gmarkers[i].getPosition());
      }
      var center = bounds.getCenter();
      var bearing = [];
      for (var i=0; i < points.length; i++) {
        points[i].bearing = google.maps.geometry.spherical.computeHeading(center,points[i]);
      }
      points.sort(bearingsort);

      for (var i=0; i < points.length; i++) {
        document.getElementById("hull_points").innerHTML += points[i].toUrlValue()+"<br>";
      }
      // console.log(points);
      polyline = new google.maps.Polygon({
        map: map,
        paths:points, 
        fillColor:"#FF0000",
        strokeWidth:2, 
        fillOpacity:0.5, 
        strokeColor:"#0000FF",
        strokeOpacity:0.5
      });
}
function bearingsort(a,b) {
  return (a.bearing - b.bearing);
}


//]]>
    </script>
  </head>
  <body onload="initialize()">
<h2>Create Polygon from random set of points</h2>
<table border="0">
<tr><td>
<button onclick="polyline.setMap(null);">hide polygon</button>
<button onclick="sortPoints2Polygon();">sort polygon point</button>
</td></tr>

<tr><td valign="top">
    <div id="map_canvas" style="width: 1200px; height: 800px"></div>
<table border="1" width="100%">
<tr><th>random pts</th><th>hull points</th></tr>
<tr><td valign="top">
    <div id="input_points"></div>
</td><td valign="top">
    <div id="hull_points"></div>
</td></tr></table>
</td>


</tr>
<tr><td><a href="http://www.geocodezip.com/map-markers_ConvexHull_Polygon.asp">v2 version of convex hull</a></td></tr>
</table>
<div id="info"></div>
<div id="w3valid">
    <a href="http://validator.w3.org/check?uri=referer" ><!-- target="validationresults" --><img
        src="http://www.w3.org/Icons/valid-xhtml10"
        alt="Valid XHTML 1.0 Transitional" height="31" width="88" /></a>
</div>   
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"> 
</script> 
<script type="text/javascript"> 
_uacct = "UA-162157-1";
urchinTracker();
</script> 
  </body>
</html>
