<!DOCTYPE html>
<html>
<head>
<script
src="http://maps.googleapis.com/maps/api/js">
</script>
<?php 
// $even = $this->db->get('tbl_even')->result();
// echo "<pre>"; print_r($information); echo "</pre>"; die;
?>
<script>
var x=new google.maps.LatLng(35.69103,51.44714);


function initialize()
{
var mapProp = {
  center:x,
  zoom:12,
  mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  
var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);

<?php foreach ($information as $info) { ?>

    var marker = new google.maps.Marker({
    position: {lat: <?php echo $info->latitude ?>, lng: <?php echo $info->longitude ?>},
    title:"<?php echo('#'); echo $info->id; ?><?php echo ', Name: '.$info->name.$info->first_name; ?><?php echo ' Vehicle type: '.$info->vehicle_type; ?>"
    });
    // To add the marker to the map, call setMap();
    marker.setMap(map);

<?php } ?>


var myTrip = [];

var points = [];
var items = [];
<?php $z=0;
$even = $this->db->get('tbl_even')->result();
    foreach ($even as $k) { ?>
    points.push(new Point("<?php echo($k->camera_position)?>", <?php echo($k->latitude)?>, <?php echo($k->longitude)?>));
    items.push([<?php echo($k->latitude)?>, <?php echo($k->longitude)?>]);
      <?php $z++;
    } ?>


// for each (var item in points) {
//   console.log(points);
//  var points = [
//     new Point("Stuttgard", 48.7771056, 9.1807688),
//     new Point("Rotterdam", 51.9226899, 4.4707867),
//     new Point("Paris", 48.8566667, 2.3509871),
//     new Point("Hamburg", 53.5538148, 9.9915752),
//     new Point("Praha", 50.0878114, 14.4204598),
//     new Point("Amsterdam", 52.3738007, 4.8909347),
//     new Point("Bremen", 53.074981, 8.807081),
//     new Point("Calais", 50.9580293, 1.8524129),
// ];


var upper = upperLeft(points);

console.log("points :: " + points);
console.log("upper  :: " + upper);
points.sort(pointSort);
console.log("sorted :: " + points);
console.log("even :: " + items);

result = [];

points.forEach(function(key) {
    var found = false;
    items = items.filter(function(item) {
        if(!found && item[1] == key) {
            result.push(item);
            found = true;
            return false;
        } else 
            return true;
    })

})

/*$.ajax({        
       type: "POST",
       url: "<?php echo base_url(); ?>Dashboard/googlemaps",
       data: { resultArray : result },
       success: function() {
            // alert(result);     
       }
    }); */
/*result.forEach(function(item) {
    document.writeln(item[0]) /// Bob Jason Henry Thomas Andrew
})*/
console.log("result :: " + result);
// A representation of a 2D Point.
function Point(label, lat, lon){
  this.lat = lat;
  this.lon = lon;
    this.label = label;
    this.x = (lon + 180) * 360;
    this.y = (lat + 90) * 180;

    this.distance=function(that) {
        var dX = that.x - this.x;
        var dY = that.y - this.y;
        return Math.sqrt((dX*dX) + (dY*dY));
    }

    this.slope=function(that) {
        var dX = that.x - this.x;
        var dY = that.y - this.y;
        return dY / dX;
    }

    this.toString=function(){
        return this.lon;
    }
}

// A custom sort function that sorts p1 and p2 based on their slope
// that is formed from the upper most point from the array of points.
function pointSort(p1, p2) {
    // Exclude the 'upper' point from the sort (which should come first).
    if(p1 == upper) return -1;
    if(p2 == upper) return 1;

    // Find the slopes of 'p1' and 'p2' when a line is 
    // drawn from those points through the 'upper' point.
    var m1 = upper.slope(p1);
    var m2 = upper.slope(p2);

    // 'p1' and 'p2' are on the same line towards 'upper'.
    if(m1 == m2) {
        // The point closest to 'upper' will come first.
        return p1.distance(upper) < p2.distance(upper) ? -1 : 1;
    }

    // If 'p1' is to the right of 'upper' and 'p2' is the the left.
    if(m1 <= 0 && m2 > 0) return -1;

    // If 'p1' is to the left of 'upper' and 'p2' is the the right.
    if(m1 > 0 && m2 <= 0) return 1;

    // It seems that both slopes are either positive, or negative.
    return m1 > m2 ? -1 : 1;
}

// Find the upper most point. In case of a tie, get the left most point.
function upperLeft(points) {
    var top = points[0];
    for(var i = 1; i < points.length; i++) {
        var temp = points[i];
        if(temp.y > top.y || (temp.y == top.y && temp.x < top.x)) {
            top = temp;
        }
    }
    return top;
}
var i = 0;
<?php  
  
  // print_r($even);die;
  foreach ($even as $k) { ?>
    myTrip.push(new google.maps.LatLng(result[i][0], result[i][1]));
// console.log(result[0]);
i++;
      <?php $z++;
  }
?>
// var myTrip=[stavanger,amsterdam,london,x];
var flightPath=new google.maps.Polygon({
  path:myTrip,
  strokeColor:"#FF0000",
  strokeOpacity:0.4,
  strokeWeight:2,
  fillColor:"#FF0000",
  fillOpacity:0.4
  });

flightPath.setMap(map);
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>

<body>
<div id="googleMap" style="width:90%;height:800px;"></div>
</body>
</html>