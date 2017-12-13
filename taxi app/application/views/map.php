<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple markers</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
    </style>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?callback=initMap">
    </script>
  </head>
  <body>
    <div id="map"></div>

    <script>

      // function initMap() {
      //   var myLatLng = {lat: -25.363, lng: 131.044};

      //   var map = new google.maps.Map(document.getElementById('map'), {
      //     zoom: 4,
      //     center: myLatLng
      //   });

      //   var marker = new google.maps.Marker({
      //     position: myLatLng,
      //     map: map,
      //     title: 'Hello World!'
      //   });
      // }
    </script>

    <script>

    function initMap() {
      var myLatLng = new google.maps.LatLng(41,14);

      var myOptions = {
          zoom: 16,
          center: myLatLng,
          scrollwheel: false,
          panControl: true,
          zoomControl: true,
          mapTypeControl: true,
          scaleControl: true,
          streetViewControl: true,
          overviewMapControl: true,
          mapTypeId: google.maps.MapTypeId.SATELLITE,
      }

      map = new google.maps.Map(document.getElementById('map'), myOptions);

      marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          draggable: false
      });

    }
    google.maps.event.addDomListener(window, 'load', initMap);

    function getCoords() {

      $.ajax({
        url: "<?php echo base_url(); ?>/Dashboard/getCoords",
        type: "POST",
        data: {
        foo : "bar"
        },
        dataType: "text",
        success: function(returnedData) {
              alert(returnedData);
            moveMarkerMap(returnedData);
        }
      });

    }

    function moveMarkerMap(newCoords) {
      var newLatLang = new google.maps.LatLng(newCoords);
      map.panTo(newLatLang);
      marker.setPosition(newLatLang);

    }
   window.setInterval(getCoords, 10);

    // setInterval(function(){ getCoords(); }, 10000);

    </script>
    
  </body>
</html>