<?php
/* index.php -- show locations on the map
 *
 * Get coordinates out of the database (and show them on a Google Map)
 *
 */

$link = mysql_connect('localhost', 'database', 'password');
mysql_select_db('database');

$when = $_GET['when'] ? strtotime($_GET['when']) : strtotime('today');;

// get last known coordinates
$query = 'SELECT * FROM coordinates WHERE DATE(time) = FROM_UNIXTIME("' . $when . '") ORDER BY time ASC';
$result = mysql_query($query);

$line = '';
while ($location = mysql_fetch_assoc($result)) {
	$line .= 'new google.maps.LatLng(' . $location['lat'] . ',' .  $location['lon']. ') ,';
} // endwhile looping through today's coordinates

$query = 'SELECT * FROM coordinates ORDER BY time DESC LIMIT 1';
$lastloc = mysql_fetch_assoc(mysql_query($query));
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
  html { height: 100% }
  body { height: 100%; margin: 0; padding: 0 }
  #map_canvas {height: 100% }
</style>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
  function initialize() {
	  var latlng = new google.maps.LatLng( <?php print $lastloc['lat'] ?>, <?php print $lastloc['lon'] ?>);
    var myOptions = {
      zoom: 14,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.HYBRID
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    var todayline = [
<?php print $line ?>
	    ];
    var todaysline = new google.maps.Polyline({
      path: todayline,
	      strokeColor: "#ff0000",
	      strokeOpacity: 0.8,
	      strokeWeight: 2,
	     });
    todaysline.setMap(map);

    var marker = new google.maps.Marker({
      position: latlng,
      map: map,
      title: "<?php print $lastloc['time'] ?>"
    });
  }
</script>
</head>
<body onload="initialize()">
<h1>Where I've been <?php print date('d M Y' , $when) ?></h1>
<div id="map_canvas" style="width: 100%; height:100%"></div>
</body>
</html>

