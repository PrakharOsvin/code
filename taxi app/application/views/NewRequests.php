<section id="main-content">
    <section class="wrapper">
<?php
  // echo "<pre>"; print_r($polygon1); echo "</pre>"; die;
?>
<div class="map-OUtER row">
<div class="col-md-9 col-sm-9">
  <div id="map_canvas3" style="height: 700px;  margin-left: 10px; margin-top: 0px;" >
  </div>
</div>
<div class="col-md-3 col-sm-3">
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
        <li id="count">New Requests</li>
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
    <div class="clear"></div>
  </div>  

  <div class="status_list">
    <div class="lists">
      <ul>
<div id="notificationDiv">
<?php
foreach ($mapNotifications as $key => $value) {
  // echo $value->status;
        echo '<li><span class="dfr">';
        if ($value->status==1) {
          $action = "Booked Ride";
          echo "$action";
        }elseif ($value->status==2) {
          $action = "Arrived";
          echo "$action";
        }elseif ($value->status==3) {
          $action = "Started";
          echo "$action";
        }elseif ($value->status==4) {
          $action = "Completed";
          echo "$action";
        }elseif ($value->status==50) {
          $action = "Cancelled";
          echo "$action";
        }elseif ($value->status==52) {
          $action = "Cancelled";
          echo "$action";
        }elseif ($value->status==6) {
          $action = "No Driver Found";
          echo "$action";
        }elseif ($value->status==7) {
          $action = "Arriving";
          echo "$action";
        } else {
          $action = "Searching";
          echo "$action";
        }
        
        echo '</span><br>';
        echo "Passenger name: ".$value->passenger."<br>Action: ".$action."<br>Driver name: ".$value->driver."<br>Date: ".$value->modified_on;
        echo '</li>';
}
?>
</div>
        <!-- <li><span class="dfr_b">two</span> Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
        <li><span class="dfr">three</span> Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
        <li><span class="dfr">four</span> Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
        <li><span class="dfr_b">five</span> Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
        <li><span class="dfr">six</span> Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li> -->
      </ul>
    </div>
  </div>
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
    mapNotifications();
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
        var count = data.length;
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
          /*marker3['infowindow'] = new google.maps.InfoWindow({
                  content: "hjsfghj"
              });

          google.maps.event.addListener(marker3, 'click', function() {
              this['infowindow'].open(map3, this);
          });*/
          // console.log(markersA3);
          $("#count").html(count+" New Requests");
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
  /*------------------------mapNotifications--------------------------*/

  function mapNotifications() {   
      $.ajax(
        {
       type: 'get',
       url: '<?php echo base_url();?>Dashboard/mapNotifications',
       data : '',
       dataType:'json',
       success: function(data) 
       {
        // console.log(data);
        for (var i = 0; i < data.length; i++) {
          // console.log(data[i]);
          if (data[i].status==1) {
            var str = 'Booked Ride';
          }else if(data[i].status==2){
            var str = 'Arrived';
          }else if(data[i].status==3){
            var str = 'Started';
          }else if(data[i].status==4){
            var str = 'Completed';
          }else if(data[i].status==50){
            var str = 'Cancelled';
          }else if(data[i].status==52){
            var str = 'Cancelled';
          }else if(data[i].status==6){
            var str = 'No Driver Found';
          }else if(data[i].status==7){
            var str = 'Arriving';
          } else{
            var str = 'Searching';
          };
          $("#notificationDiv").prepend('<li><span class="dfr">'+str+'</span><br>Passenger name: '+data[i].passenger+'<br>Action: '+str+'<br>Driver name: '+data[i].driver+'<br>Date: '+data[i].modified_on+'</li>');
        };
       }
      }); // end ajax 
  }
  /*-------------------------mapNotifications End------------------------------*/
}

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