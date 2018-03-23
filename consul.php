<?php 
include 'token.php';
$token = getToken();
$curlGet = curl_init();
// Get cURL resource
// Set some options - we are passing in a useragent too here
curl_setopt_array($curlGet, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/50622031800011476009400100001010000000004156539731",
    CURLOPT_HTTPHEADER =>  array('Authorization: bearer '.$token->{"access_token"})
));
// Send the request & save response to $resp
$responseGet = curl_exec($curlGet);
$resp = json_decode($responseGet, true);
$xml = base64_decode($resp['respuesta-xml']);
echo $xml;
 ?>