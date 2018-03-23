<?php 
include 'conf.php';
function getToken($username, $password){
	$url = 'https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token';
	$data = array('client_id' => 'api-stag',
	              'client_secret' => '',
	              'grant_type' => 'password', 	              
	              'username' => $username, 
	              'password' => $password, 
	              'scope' =>'');
	
	$options = array(
	    'http' => array(
	        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
	        'method'  => 'POST',
	        'content' => http_build_query($data)
	    )
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE) { echo $result; }
	$result = utf8_encode($result);
	return json_decode($result); 
}
?>
