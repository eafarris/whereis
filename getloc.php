<?php
/* getloc.php -- accept location from iPhone app SendLocation
 *
 * This script writes gps coordinates in a database; index.php will read 
 * them and display them on a Google Map
 *
 */

// Coordinates are sent with GET variables
$link = mysql_connect('localhost', 'database', 'password');
mysql_select_db('database');

$lat = mysql_real_escape_string($_GET['lat']);
$lon = mysql_real_escape_string($_GET['lon']);

$query = "INSERT INTO coordinates (user, lat, lon, time) VALUES ('user1', $lat, $lon, CURRENT_TIMESTAMP());";
mysql_query($query);


