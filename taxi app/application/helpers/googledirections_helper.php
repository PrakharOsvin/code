<?php
/**
* Function for fare calculation
*
* @package         CodeIgniter
* @category        Helper
* @author          Rohit Dhiman
* @license         
* @link            
*/
function googleDirections($params)
{
	$url = "http://phphosting.osvin.net/aberbackusa/admin/api/User/directions?pickup_lat=$params[pickup_lat]&pickup_long=$params[pickup_long]&dropoff_lat=$params[dropoff_lat]&dropoff_long=$params[dropoff_long]&key=AIzaSyAwYTiR3TzUFoByU936OTdqfSQMQT8pa20&language=$params[language]";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	curl_close($ch);

	$data = json_decode($response);
	return $data;
}
?>