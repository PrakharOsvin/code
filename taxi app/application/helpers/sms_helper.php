<?php

/**
 * Function queue the SMS
 *
 */
function _sms($to,$text){
	$serverName = $_SERVER['HTTP_HOST'];
	if($serverName != "localhost"){
		$id = file_get_contents("http://localhost/portal/queueSMS?to={$to}&text={$text}&code=mapp123");
		return intval($id); 
	}
}
/**
 * Example:
 * _sms("0981234657890","test text");
 *
 **/
    