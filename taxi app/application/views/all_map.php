<section id="main-content">
    <section class="wrapper">
<?php
  // echo "<pre>"; print_r($polygon1); echo "</pre>"; die;
?>
<div class="map-OUtER">
  <div id="map_canvas" style="height: 400px; width: 1080px; margin-left: 10px; margin-top: 0px;" >
  </div>
  <div class="test" >
    <h3 class="text-center">Stats</h3>
    <div class="leftsect">
      <ul class="list-unstyled">
        <li><i class="glyphicon glyphicon-map-marker"></i>Marker</li>
        <!-- <li><i class="glyphicon glyphicon-map-marker"></i> lorem</li>
        <li><i class="glyphicon glyphicon-map-marker"></i> lorem</li>
        <li><i class="glyphicon glyphicon-map-marker"></i> lorem</li> -->
      </ul>
    </div>
    <div class="rightsect text-right">
      <ul class="list-unstyled">
        <li>Online Driver</li>
        <!-- <li>Lorem ipsum dolor sit amet</li>
        <li>Lorem ipsum dolor sit amet</li>
        <li>Lorem ipsum dolor sit amet</li> -->
      </ul>
    </div>
  <div class="leftsect">
      <ul class="list-unstyled">
        <li><hr></li>
        <li><hr></li>
      </ul>
    </div>
    <div class="rightsect text-right">
      <ul class="list-unstyled">
        <li>Odd/Even Zone</li>
        <li>Zone A-restricted area</li>
     </ul>
    </div>
  </div>  
  <div id="map_canvas2" style="height: 500px; width: 525px; margin-left: 10px; margin-top: 10px; float: left;"></div>
  <div id="map_canvas3" style="height: 500px; float: right; width: 550px; margin-right: 48px; margin-top: 10px;"></div>
</div>

    </section>
</section>
<script src="<?php echo base_url(); ?>public/js/jquery.js"></script>
<script type="text/javascript">
// The following example creates a marker in Stockholm, Sweden using a DROP
// animation. Clicking on the marker will toggle the animation between a BOUNCE
// animation and no animation.

var marker;
var markersA = [];
var markersA2 = [];
var markersA3 = [];
// console.log("kjhsaidk");
function initMap() {
    // create the maps

     var map = new google.maps.Map(document.getElementById('map_canvas'), {
          zoom: 11,
          center: {lat: 35.701054, lng: 51.382940},
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });
     map.setTilt(45);
     var trafficLayer = new google.maps.TrafficLayer();
        trafficLayer.setMap(map);

// console.log(markersA);return 0;
var myVar = setInterval(function(){ DeleteMarkers() }, 3000);
function DeleteMarkers() {
    //Loop through all the markers and remove
    for (var i = 0; i < markersA.length; i++) {
        markersA[i].setMap(null);
    }
    markersA = [];
    markers();
};

function markers(){

  // alert("uidhsfiuh");
  $.ajax({
      type: "GET",
      url: "<?php echo base_url() ?>Dashboard/marker/2",
      data: '',
      dataType: "json", // Set the data type so jQuery can parse it for you
      success: function (data) {
          for (var i = 0; i < data.length; i++) {
            // console.log(data);
            var marker1 = new google.maps.Marker({
            map: map,
            draggable: false,
            icon: "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld="+data[i]["vehicle_permit_type"]+"|9999FF|000000",
            title: "#"+data[i]["id"]+", Name: "+data[i]["name"]+" ",
            // animation: google.maps.Animation.DROP,
            position: {lat: parseFloat(data[i]["latitude"]), lng: parseFloat(data[i]["longitude"])}
            // position: {lat: 35.7009855, lng: 51.3518852}
            });
            markersA.push(marker1);
          }
      }
  });
}

  //***********ROUTING****************//
 
  //Initialize the Path Array
  /*var path = new google.maps.MVCArray();

  //Initialize the Direction Service
  var service = new google.maps.DirectionsService();

  //Set the Path Stroke Color
  var poly = new google.maps.Polyline({ map: map, strokeColor: '#4986E7' });

  //Loop and Draw Path Route between the Points on MAP
  for (var i = 0; i < lat_lng.length; i++) {
      if ((i + 1) < lat_lng.length) {
          var src = lat_lng[i];
          var des = lat_lng[i + 1];
          path.push(src);
          poly.setPath(path);
          service.route({
              origin: src,
              destination: des,
              travelMode: google.maps.DirectionsTravelMode.DRIVING
          }, function (result, status) {
              if (status == google.maps.DirectionsStatus.OK) {
                  for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
                      path.push(result.routes[0].overview_path[i]);
                  }
              }
          });
      }
  }*/

// var myVars = setInterval(function(){ setMapOnAll() }, 2000);
    // marker.setMap( map );

    
        // marker.addListener('click', toggleBounce);

    // Define the LatLng coordinates for the polygon's path.
        var rzCoords = [
        <?php
          // $polygon1 = $this->db->get("tbl_polygon1")->result();
          foreach ($polygon1 as $key => $value) { ?>
            {lat: <?php echo "$value->latitude";?>, lng: <?php echo "$value->longitude";?>},
        <?php }
        ?>
        ];

        // Construct the polygon.
        var tehranRZ = new google.maps.Polygon({
          paths: rzCoords,
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#FF0000',
          fillOpacity: 0.35
        });
        tehranRZ.setMap(map);

        // Define the LatLng coordinates for the polygon's path.
        var oeCoords = [
        <?php
          // $polygon2 = $this->db->get("tbl_polygon2")->result();
          foreach ($polygon2 as $key => $value) { ?>
            {lat: <?php echo "$value->latitude";?>, lng: <?php echo "$value->longitude";?>},
        <?php }
        ?>
        ];

        // Construct the polygon.
        var tehranOE = new google.maps.Polygon({
          paths: oeCoords,
          strokeColor: '#3D404D',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#3D404D',
          fillOpacity: 0.35
        });
        tehranOE.setMap(map);

    var map2 = new google.maps.Map(document.getElementById('map_canvas2'), {
          zoom: 11,
          center: {lat: 35.701054, lng: 51.382940},
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

    var trafficLayer = new google.maps.TrafficLayer();
        trafficLayer.setMap(map2);

var myVar2 = setInterval(function(){ DeleteMarkers2() }, 3000);
function DeleteMarkers2() {
    //Loop through all the markers and remove
    for (var i = 0; i < markersA2.length; i++) {
        markersA2[i].setMap(null);
    }
    markersA2 = [];
    markers2();
};

function markers2(){

  // alert("uidhsfiuh");
  $.ajax({
      type: "GET",
      url: "<?php echo base_url() ?>Dashboard/marker/1",
      data: '',
      dataType: "json", // Set the data type so jQuery can parse it for you
      success: function (data) {
          for (var i = 0; i < data.length; i++) {
            // console.log(data[i]["longitude"]);
            var marker2 = new google.maps.Marker({
            map: map2,
            draggable: false,
            icon: "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld="+data[i]["vehicle_permit_type"]+"|9999FF|000000",
            title: "#"+data[i]["id"]+", Name"+data[i]["name"]+" ",
            // animation: google.maps.Animation.DROP,
            position: {lat: parseFloat(data[i]["latitude"]), lng: parseFloat(data[i]["longitude"])}
            // position: {lat: 35.7009855, lng: 51.3518852}
            });
            markersA2.push(marker2);
          }
      }
  });
}

    // Define the LatLng coordinates for the polygon's path.
        var rzCoords = [
        <?php
          // $polygon1 = $this->db->get("tbl_polygon1")->result();
          foreach ($polygon1 as $key => $value) { ?>
            {lat: <?php echo "$value->latitude";?>, lng: <?php echo "$value->longitude";?>},
        <?php }
        ?>
        ];

        // Construct the polygon.
        var tehranRZ = new google.maps.Polygon({
          paths: rzCoords,
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#FF0000',
          fillOpacity: 0.35
        });
        tehranRZ.setMap(map2);

        // Define the LatLng coordinates for the polygon's path.
        var oeCoords = [
        <?php
          // $polygon2 = $this->db->get("tbl_polygon2")->result();
          foreach ($polygon2 as $key => $value) { ?>
            {lat: <?php echo "$value->latitude";?>, lng: <?php echo "$value->longitude";?>},
        <?php }
        ?>
        ];

        // Construct the polygon.
        var tehranOE = new google.maps.Polygon({
          paths: oeCoords,
          strokeColor: '#3D404D',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#3D404D',
          fillOpacity: 0.35
        });
        tehranOE.setMap(map2);

    var map3 = new google.maps.Map(document.getElementById('map_canvas3'), {
          zoom: 11,
          center: {lat: 35.701054, lng: 51.382940},
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

    var trafficLayer = new google.maps.TrafficLayer();
        trafficLayer.setMap(map3);

var myVar3 = setInterval(function(){ DeleteMarkers3() }, 3000);
function DeleteMarkers3() {
    //Loop through all the markers and remove
    for (var i = 0; i < markersA3.length; i++) {
        markersA3[i].setMap(null);
    }
    markersA3 = [];
    markers3();
};

function markers3(){

  // alert("uidhsfiuh");
  $.ajax({
      type: "GET",
      url: "<?php echo base_url() ?>Dashboard/marker/3",
      data: '',
      dataType: "json", // Set the data type so jQuery can parse it for you
      success: function (data) {
        // console.log(data);
          for (var i = 0; i < data.length; i++) {
            // console.log(data.length);
            var marker3 = new google.maps.Marker({
              map: map3,
              draggable: false,
              
              title: "#"+data[i]["id"]+", Name: "+data[i]["name"]+" ",
              // animation: google.maps.Animation.DROP,
              position: {lat: parseFloat(data[i]["latitude"]), lng: parseFloat(data[i]["longitude"])}
              // position: {lat: 35.7009855, lng: 51.3518852}
            });
            markersA3.push(marker3);
          }
          // console.log(markersA3);
      }
  });
}

    // Define the LatLng coordinates for the polygon's path.
        var rzCoords = [
        <?php
          // $polygon1 = $this->db->get("tbl_polygon1")->result();
          foreach ($polygon1 as $key => $value) { ?>
            {lat: <?php echo "$value->latitude";?>, lng: <?php echo "$value->longitude";?>},
        <?php }
        ?>
        ];

        // Construct the polygon.
        var tehranRZ = new google.maps.Polygon({
          paths: rzCoords,
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#FF0000',
          fillOpacity: 0.35
        });
        tehranRZ.setMap(map3);

        // Define the LatLng coordinates for the polygon's path.
        var oeCoords = [
        <?php
          // $polygon2 = $this->db->get("tbl_polygon2")->result();
          foreach ($polygon2 as $key => $value) { ?>
            {lat: <?php echo "$value->latitude";?>, lng: <?php echo "$value->longitude";?>},
        <?php }
        ?>
        ];

        // Construct the polygon.
        var tehranOE = new google.maps.Polygon({
          paths: oeCoords,
          strokeColor: '#3D404D',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#3D404D',
          fillOpacity: 0.35
        });
        tehranOE.setMap(map3);
}

function toggleBounce() {
  if (marker.getAnimation() !== null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}



// function moveMarker( map, marker ) {
    
//     //delayed so you can see it move
//     setTimeout( function(){ 
    
//         marker.setPosition( new google.maps.LatLng(35.701054, 51.382940 ) );
//         // map.panTo( new google.maps.LatLng( 32.345, 52.345 ) );
        
// }, 5000 )};

// google.maps.event.addDomListener(window, 'load', initMap);
// var myVar = setInterval(function(){ getCoords() }, 2000);

//   function getCoords() {
// // alert("returnedData");
// // console.log("kjhsaidk");
//     $.ajax({
//     url: "<?php echo base_url(); ?>Dashboard/marker",
//     type: "POST",
//     data: '',
//     success: function(returnedData) {
//           console.log(returnedData);
//         moveMarkerMap(returnedData);
//     }
//     });

//   }

  // function moveMarkerMap(newCoords) {

  //   var newLatLang = new google.maps.LatLng(newCoords);
  //   map.panTo(newLatLang);
  //   marker.setPosition(newLatLang);

  // }
// window.setInterval(getCoords, 3000);

</script> 
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=&callback=initMap">
</script>
<script src="<?php echo base_url(); ?>public/js/jquery-1.8.3.min.js"></script>

<script src="<?php echo base_url(); ?>public/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo base_url(); ?>public/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url(); ?>public/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>public/js/jquery.sparkline.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>public/js/owl.carousel.js" ></script>
<script src="<?php echo base_url(); ?>public/js/jquery.customSelect.min.js"></script>
<script src="<?php echo base_url(); ?>public/js/respond.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.dcjqaccordion.2.7.js"></script>

<script src="<?php echo base_url(); ?>/public/js/common-scripts.js"></script>