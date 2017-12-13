<html>
<head>
	<title><?php echo $title; ?></title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>/public/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<style>
		body{
			margin:0 auto;
		}
		#map{
			width:100%; 
			height:90%;
		}
.logo_MASIR {
  background: #3D404D none repeat scroll 0 0;
}
.masir_logo_Penal {
  display: inline-block;
  margin: 3px 0;
  width: 120px;
}
.masir_logo_Penal img {
  display: inline-block;
  max-width: 100%;
}
.social:hover {
     -webkit-transform: scale(1.1);
     -moz-transform: scale(1.1);
     -o-transform: scale(1.1);
 }
 .social {
     -webkit-transform: scale(0.8);
     /* Browser Variations: */
     
     -moz-transform: scale(0.8);
     -o-transform: scale(0.8);
     -webkit-transition-duration: 0.5s;
     -moz-transition-duration: 0.5s;
     -o-transition-duration: 0.5s;
 }

/*
    Multicoloured Hover Variations
*/
 
 #social-fb:hover {
     color: #3B5998;
 }
 #social-tw:hover {
     color: #4099FF;
 }
 #social-gp:hover {
     color: #d34836;
 }
 #social-em:hover {
     color: #f39c12;
 }


	</style>
</head>
<body>
<section class="logo_MASIR">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="masir_logo_Penal"><a href="http://www.masirapp.com" target="_blank"><img src="<?php echo base_url('public/images/logo.png'); ?>"></a></div>
			</div>
<!-- 			<div class="col-md-6">
				<div class="text-center center-block">
	                <a href="https://www.facebook.com/bootsnipp"><i id="social-fb" class="fa fa-facebook-square fa-3x social"></i></a>
		            <a href="https://twitter.com/bootsnipp"><i id="social-tw" class="fa fa-twitter-square fa-3x social"></i></a>
		            <a href="https://plus.google.com/+Bootsnipp-page"><i id="social-gp" class="fa fa-google-plus-square fa-3x social"></i></a>
		            <a href="mailto:bootsnipp@gmail.com"><i id="social-em" class="fa fa-envelope-square fa-3x social"></i></a>
				</div>
			</div> -->
		</div>
	</div>
</section>
	<?php
		$driver_id = $driver_detail[0]->driver_id;
		$name = $driver_detail[0]->name;
	    $lat =  $driver_detail[0]->latitude;
		$long =  $driver_detail[0]->longitude;
	?>

	<div id="map"></div>
	<script language="javascript" src='http://maps.google.com/maps/api/js?sensor=false&key='></script>
	<script src="<?php echo base_url(); ?>public/js/jquery-1.8.3.min.js"></script>

	<script>
	var base_url = window.location.origin;
			  console.log(base_url);
	var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var oceanBeach = new google.maps.LatLng(37.7683909618184, -122.51089453697205);


		function initialize(){
		     var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $long; ?>);
		     var myOptions = {
		         zoom: 18,
		         center: myLatlng,
		         mapTypeId: google.maps.MapTypeId.ROADMAP
		         }
		      map = new google.maps.Map(document.getElementById("map"), myOptions);
		      var icon = {
				    url: base_url+'/Admin/public/images/ic_car_drivers.png', // url
				    scaledSize: new google.maps.Size(37, 72), // scaled size
				    origin: new google.maps.Point(0,0), // origin
				    anchor: new google.maps.Point(0, 0) // anchor
				};
		      var marker = new google.maps.Marker({
		          position: myLatlng, 
		          map: map,
		          icon: icon,
		          // zoom=18,
	      		  title:"# <?php echo $name; ?>"
		     });
		    var myVar2 = setInterval(function(){ changeMarkerPosition(marker) }, 3000);	
		}
		
		function changeMarkerPosition(marker) {
			  // alert("uidhsfiuh");
			  
			  $.ajax({
			      type: "GET",
			      url: "<?php echo base_url('Location/marker?driver_id=').$driver_id ?>",
			      data: '',
			      dataType: "json", // Set the data type so jQuery can parse it for you
			      success: function (data) {
			      	// console.log(data[0]);
			      	var latlng = new google.maps.LatLng(parseFloat(data[0]["latitude"]),parseFloat(data[0]["longitude"]));
		    		marker.setPosition(latlng);
		    		map.panTo(latlng);	
			        var request = {
					      origin: latlng,
					      destination: oceanBeach,
					      // Note that Javascript allows us to access the constant
					      // using square brackets and a string value as its
					      // "property."
					      
					      travelMode: 'DRIVING'
					  };
					  directionsService.route(request, function(response, status) {
					    if (status == 'OK') {
					      directionsDisplay.setDirections(response);
					    }
					  });

			      }
			  });

		}
		google.maps.event.addDomListener(window,'load', initialize);
	</script>
</body>
</html>