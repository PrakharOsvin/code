
<h2>Convex Hull of random set of points</h2>
<div> 
  <h1></h1>
<label>Restricted Area</label>
<br><br>
<label>Odd/Even Area</label>
</div>
<table border="0">
<tr><td>
<button onclick="polyline.setMap(null);">hide polygon</button>
<button onclick="calculateConvexHull();">calculate Convex Hull</button>
<button onclick="displayHullPts();">display Hull Points</button>
</td></tr>

<tr><td valign="top">
    <div id="map_canvas" style="width: 1200px; height: 800px"></div>
<table border="1" width="100%">
<tr><th>random pts</th><th>hull points</th></tr>
<tr><td valign="top">
    <div id="input_points" style="display:block"></div>
</td><td valign="top">
    <div id="hull_points" style="display:block"></div>
</td></tr></table>
</td>

<td>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&amp;libraries=geometry"></script> 

<script type="text/javascript" src="<?php echo base_url();?>/public/js/convex_hull.js"></script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script type="text/javascript">
//<![CDATA[ 
    var gmarkers = [];
    var points = [];
    var hullPoints = [];
    var map = null;
    var polyline;

    var gmarkers_n = [];
    var points_n = [];
    var hullPoints_n = [];
    var map_n = null;
    var polyline_n;

    var infowindow = new google.maps.InfoWindow(
      { 
        size: new google.maps.Size(150,150)
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

$( document ).ready(function() {
initialize();
});


function initialize() {
 
// alert("sdsd");
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
 
  // google.maps.event.addListener(map, 'click', function() {
  //       infowindow.close();
  //       });
 
  

google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
        // Add 10 markers to the map at random locations
        var bounds = map.getBounds();
        var southWest = bounds.getSouthWest();
        var northEast = bounds.getNorthEast();
        var lngSpan = northEast.lng() - southWest.lng();
        var latSpan = northEast.lat() - southWest.lat();
  map.setCenter(map.getCenter());
        map.setZoom(map.getZoom()-1);
/* */ 
<?php $z=0;
$even = $this->db->get('tbl_even')->result();
foreach ($even as $k) { ?>
       // for (var i = 0; i < 10; i++) {
          var point = new google.maps.LatLng(<?php echo($k->latitude)?>, <?php echo($k->longitude)?>);
          points.push(point);
    var marker = createMarker(point, i);
    gmarkers_n.push(marker);
        // } 
  <?php $z++;
} ?>

<?php $z=0;
$even = $this->db->get('tbl_camera')->result();
foreach ($even as $k) { ?>
       // for (var i = 0; i < 10; i++) {
          var point = new google.maps.LatLng(<?php echo($k->latitude)?>, <?php echo($k->longitude)?>);
          points_n.push(point);
    var marker = createMarker(point, i);
    gmarkers.push(marker);
        // } 
  <?php $z++;
} ?>
        // console.log(gmarkers);
        // return false;
/* */
/*
        for (var i = 0; i<points.length; i++) {
    var marker = createMarker(points[i], i);
    gmarkers.push(marker);
          map.addOverlay(marker);
        }
*/
      for (var i=0; i < points.length; i++) {
        document.getElementById("input_points").innerHTML += i+": "+points[i].toUrlValue()+"<br>";
      }
   
      calculateConvexHull();
      calculateConvexHull_n();
    });
//         google.maps.event.addListener(map, "click", function(evt) {
//           if (evt.latLng) {
//             var latlng = evt.latLng;
// //            alert("latlng:"+latlng.toUrlValue());
//             var marker = createMarker(latlng, gmarkers.length-1);
//       points.push(latlng);
//             gmarkers.push(marker);
//       calculateConvexHull();
      
//       }
//       });
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
        calculateConvexHull();               
  }

function createMarker(latlng, marker_number) {
    var html = "marker "+marker_number;
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        zIndex: Math.round(latlng.lat()*-100000)<<5
        });
marker.setVisible(false);
    // google.maps.event.addListener(marker, 'click', function() {
    //     var contentString = html + "<br>"+marker.getPosition().toUrlValue()+"<br><a href='javascript:removeMarker(new google.maps.LatLng("+marker.getPosition().toUrlValue()+"));'>Remove Marker</a>";
    //     infowindow.setContent(contentString); 
    //     infowindow.open(map,marker);
    //     });
    return marker;
}
 


     function calculateConvexHull() {
      if (polyline) polyline.setMap(null);
      document.getElementById("hull_points").innerHTML = "";
      points = [];
      for (var i=0; i < gmarkers.length; i++) {
        points.push(gmarkers[i].getPosition());
      }
      points.sort(sortPointY);
      points.sort(sortPointX);
      DrawHull();
    }

    function calculateConvexHull_n() {
      if (polyline_n) polyline_n.setMap(null);
      document.getElementById("hull_points").innerHTML = "";
      points_n = [];
      for (var i=0; i < gmarkers_n.length; i++) {
        points_n.push(gmarkers_n[i].getPosition());
      }
      points_n.sort(sortPointY);
      points_n.sort(sortPointX);
      DrawHull_n();
    }



     function sortPointX(a,b) { return a.lng() - b.lng(); }
     function sortPointY(a,b) { return a.lat() - b.lat(); }

     function DrawHull() {
     hullPoints = [];
     chainHull_2D( points, points.length, hullPoints );
     polyline = new google.maps.Polygon({
      map: map,
      paths:hullPoints, 
      fillColor:"#EA4335",
      strokeWidth:6, 
      fillOpacity:0.5, 
      strokeColor:"#FF0000",
      strokeOpacity:0.5
     });
     displayHullPts();
}

function DrawHull_n() {
     hullPoints_n = [];
     chainHull_2D( points_n, points_n.length, hullPoints_n );
     polyline = new google.maps.Polygon({
      map: map,
      paths:hullPoints_n, 
      fillColor:"#FFFB12",
      strokeWidth:6, 
      fillOpacity:0.5, 
      strokeColor:"#FF0000",
      strokeOpacity:0.5
     });
     displayHullPts();
}



function displayHullPts() {
     document.getElementById("hull_points").innerHTML = "";
     for (var i=0; i < hullPoints.length; i++) {
       document.getElementById("hull_points").innerHTML += hullPoints[i].toUrlValue()+"<br>";
     }
}

//]]>
    </script>

<script type="text/javascript"><!--
google_ad_client = "pub-8586773609818529";
/* Verticle Ad */
google_ad_slot = "4289667470";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>

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

