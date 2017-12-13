<section id="main-content">
    <section class="wrapper">
      <div>
      <form id="form">
        <div class="form-group">
          <label for="message">Message</label>
          <input type="textarea" class="form-control" placeholder="Message to Available Drivers" value="" id="message">
        </div>
        <div class="form-group">
          <input type="hidden" class="form-control"  value="1" id="to" >
        </div>
        <button type="button" id="sendPush" class="btn btn-primary">Send</button>
      </form>
      </div>
      <div id="div1" style="color:white"><h2></h2></div>
<?php
  // echo "<pre>"; print_r($mapNotifications); echo "</pre>"; die;
?>
<div class="map-OUtER row">
<div class="col-md-9 col-sm-9">
  <div id="map_canvas2" style="height: 700px;  margin-left: 10px; margin-top: 0px;" >
  </div>
</div>
<div class="col-md-3 col-sm-3">
  <div class="test" >
    <h3 class="text-center">Stats</h3>
    <div class="leftsect">
      <ul class="list-unstyled">
        <li><i class="glyphicon glyphicon-map-marker"></i>Marker</li>
        <li><i class="glyphicon glyphicon-map-marker"></i>B</li>
        <li><i class="glyphicon glyphicon-map-marker"></i>C</li>
        
      </ul>
    </div>
    <div class="rightsect text-right">
      <ul class="list-unstyled">
        <li id="count">Available Drivers</li>
        <li>Sun, Tue, Thur</li>
        <li>Sat, Mon, Wed</li>
        
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
          $action = "Booked";
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
        }elseif ($value->status==50&&$value->driver_id==0) {
          $action = "Request Cancelled";
          echo "$action";
        }elseif ($value->status==50&&$value->driver_id!=0) {
          $action = "Client Cancelled";
          echo "$action";
        }elseif ($value->status==51) {
          $action = "Admin Cancelled";
          echo "$action";
        }elseif ($value->status==52) {
          $action = "Driver Cancelled";
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

       
        echo'<a href = detailbookData/'.$value->id.'>Ride Details</a></br>';
        echo "Trip Id : ".$value->id."<br>Passenger name:<a href='profile/$value->user_id'> ".$value->passenger."</a>";
        if($value->status == 4){

        echo "<br> Price : ".$value->fare; 
      }
      else{
        $imp = explode(',',$value->estimate);

         echo "<br> Price : ";
        print_r($imp[2]); 
      }
        echo "<br>Driver name:<a href='profile/$value->driver_id'>".$value->driver."</a><br>Pickup: ".$value->pickup_location."<br>Vehicle Type: ".$value->client."<br>Destination: ".$value->dropoff_location."<br>Date: ".$value->modified_on;
        if($value->status == 52 || $value->status == 50 ){
        echo '<br>Reason : '.$value->reason;
        }
        echo '</li>';

     
}
?>
</div>
      </ul>
    </div>
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


$(document).ready(function(){
    $("button").click(function(){
      var message = $("#message").val();
      var to = $("#to").val();
      if (message!="") {
        $.ajax({
          type: "GET",
          url: "<?php echo base_url() ?>Dashboard/pushMessage", 
          data: {to: to, message: message},
          success: function(result){
            $("#div1").html(result);
          }
        });
      } 
      else{
        alert("Message Field is empty!");
      };
      // alert(to);
      // console.log(to);   
    });
});

var marker;
var markersA = [];
var markersA2 = [];
var markersA3 = [];
var otm=0;

function initMap() {
  // create the maps
  var map2 = new google.maps.Map(document.getElementById('map_canvas2'), {
        zoom: 11,
        center: {lat: 35.701054, lng: 51.382940},
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });

  var trafficLayer = new google.maps.TrafficLayer();
      trafficLayer.setMap(map2);
  function DeleteMarkers2() {
    // console.log(markersA2);
      //Loop through all the markers and remove
      for (var i = 0; i < markersA2.length; i++) {
          markersA2[i].setMap(null);
      }
      markersA2 = [];
      markers2();
      // mapNotifications();
  };
function markers2(){

  // alert("uidhsfiuh");
  $.ajax({
      type: "GET",
      url: "<?php echo base_url() ?>Dashboard/marker/1",
      data: '',
      dataType: "json", // Set the data type so jQuery can parse it for you
      success: function (data) {
        var count = data.length;
          for (var i = 0; i < data.length; i++) {
            // console.log(data[i]["longitude"]);
            var marker2 = new google.maps.Marker({
              map: map2,
              draggable: false,
              icon: "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld="+data[i]["vehicle_permit_type"]+"|9999FF|000000",
              title: "#"+data[i]["id"]+", Name:"+data[i]["name"]+", vehicle type:"+data[i]["vehicle_type"],
              // animation: google.maps.Animation.DROP,
              url: "<?php echo base_url() ?>Dashboard/profile/"+data[i]["id"],
              position: {lat: parseFloat(data[i]["latitude"]), lng: parseFloat(data[i]["longitude"])}
              // position: {lat: 35.7009855, lng: 51.3518852}
            });
            google.maps.event.addListener(marker2, 'click', (function(marker2, i) {
                return function() {
                    // window.location.href = marker2.url;
                    window.open(marker2.url,'Profile');
                }
            })(marker2, i));
            markersA2.push(marker2);
            /*google.maps.event.addListener(marker, 'click', (function(marker, i) {
              return function() {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
              }
            })(marker, i));*/
          }
          
          // console.log(count);
          $("#count").html(count+" Available Drivers");
      }
  });
      /*if(otm==0){
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markersA2.length; i++) {
         bounds.extend(markersA2[i].getPosition());
        }

        map2.fitBounds(bounds);
        var otm=1;
      }*/
  // console.log(count);
}

  var myVar2 = setInterval(function(){ DeleteMarkers2() }, 3000);
  var myVar3 = setInterval(function(){ mapNotifications() }, 1000);


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
          $('#notificationDiv').empty();
        // console.log(data);
        for (var i = data.length-1; i >= 0; i--) {
          // console.log(data[i]);
          if (data[i].status==1) {
            var str = 'Booked Ride';
          }else if(data[i].status==2){
            var str = 'Arrived';
          }else if(data[i].status==3){
            var str = 'Started';
          }else if(data[i].status==4){
            var str = 'Completed';
          }else if(data[i].status==50&&data[i].driver_id==0){
            var str = 'Request Cancelled';
          }else if(data[i].status==50&&data[i].driver_id!=0){
            var str = 'Client Cancelled';
          }else if(data[i].status==51){
            var str = 'Admin Cancelled';
          }else if(data[i].status==52){
            var str = 'Driver Cancelled';
          }else if(data[i].status==6){
            var str = 'No Driver Found';
          }else if(data[i].status==7){
            var str = 'Arriving';
          } else{
            var str = 'Searching';
          };
          // alert(data[i].user_id);
          // alert(data[i].driver_id);
          // console.log(data[i].driver_id);
          $("#notificationDiv").prepend('<li><span class="dfr">'
            +str+'</span><br><a href =detailbookData/'+data[i].id+'>Ride Details</a><br>Trip Id: '+data[i].id+'<br>Passenger name: <a href=profile/'+data[i].user_id+'>'+data[i].passenger+'</a><br>Driver name: <a href=profile/'+data[i].driver_id+'>'+data[i].driver+'</a><br>Vehicle Type: '+data[i].client+'<br>Pickup: '+data[i].pickup_location+'<br>Destination: '+data[i].dropoff_location+'<br>Action: '+str+'<br>Reason: '+data[i].reason+'<br>Date: '+data[i].modified_on);
          if(data[i].status == 4){
            '<br>Price: '+data[i].fare;
          }
          else
          {
            var str = data[i].estimate;
            var array = str.split(",");
            '<br>Price: '+array[2]+'</li>';
          }
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