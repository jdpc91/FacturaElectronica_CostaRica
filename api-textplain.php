<?php 
include 'token.php';
include 'Firmadohaciendacr.php';
include 'config.php';
date_default_timezone_set ('America/Costa_Rica');
if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
    throw new Exception('Request method must be POST!');
}
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'text/plain') != 0){
    throw new Exception('Content type must be: text/plain');
}
$content = trim(file_get_contents("php://input"));
$csv = explode("\n", $content);

foreach ($csv as $key => $line)
{
	$csv[$key] = str_getcsv($line);
}
$DetalleServicio = array();
foreach ($csv as $key => $value) {
	if ($key > 0) {
		$DetalleServicio[] = array(
			'LineaDetalle' => array(
				'NumeroLinea' => $key,
				'Codigo' => array(
					'Tipo' => '04',
					'Codigo' => $value[0],
				),
				'Cantidad' => round($value[1], 3),
				'UnidadMedida' => 'Sp',
				'Detalle' => $value[2],
				'PrecioUnitario' => round($value[3], 5),
				'MontoTotal' => round($value[3]*$value[1], 5),
				'SubTotal' => round($value[3]*$value[1], 5),
				'MontoTotalLinea' => round($value[3]*$value[1], 5),
			)
		);
	}
}
$casaMatriz = "001";
$terminal = "00001";
$TipoComprobante = "01";
$numDocumento = str_pad($csv[0][0], 10, "0", STR_PAD_LEFT);
$NumeroConsecutivo = $casaMatriz.$terminal.$TipoComprobante.$numDocumento;
$fechaClave = date('dmy');
$cedulaClave = str_pad($NumeroID, 12, "0", STR_PAD_LEFT);
$situacionComprobante = "1";
$seg = (string)rand(1,99999999);
$numSeguridad = str_pad($seg, 8, "0", STR_PAD_LEFT);
$Clave = "506".$fechaClave.$cedulaClave.$NumeroConsecutivo.$situacionComprobante.$numSeguridad;
foreach ($DetalleServicio as $key => $value) {
	$TotalComprobante += $value['LineaDetalle']['MontoTotalLinea'];
}
$TotalComprobante = round($TotalComprobante, 5);
$myArray = array(
	'Clave' => $Clave,
	'NumeroConsecutivo' => $NumeroConsecutivo,        
	'FechaEmision' => date('c'),
	'Emisor' => array(
		'Nombre' => $Nombre,
		'Identificacion' => array(
			'Tipo' => $TipoID,
			'Numero' => $NumeroID ,
		),
		'NombreComercial' => $NombreComercial,
		'Ubicacion' => array(
			'Provincia' => $Provincia,
			'Canton' => $Canton,
			'Distrito' => $Distrito,
			'Barrio' => $Barrio,
			'OtrasSenas' => $OtrasSenas,
		),
		'Telefono' => array(
			'CodigoPais' => $CodigoPais,
			'NumTelefono' =>  $NumTelefono,
		),
		'CorreoElectronico' => $CorreoElectronico,
	),	
	'CondicionVenta' => '01',
	'MedioPago' => '01',
	'DetalleServicio' => $DetalleServicio,		
	'ResumenFactura' => array(
		'CodigoMoneda' => $CodigoMoneda,
		'TipoCambio' => round($TipoCambio, 5),
		'TotalVenta' => $TotalComprobante,
		'TotalVentaNeta' => $TotalComprobante,
		'TotalComprobante' => $TotalComprobante,
	),
	'Normativa' => array(
		'NumeroResolucion' => 'DGT-R-48-2016',
		'FechaResolucion' => '20-02-2017 13:22:22',
	),
);
$xmltext = array_to_xml($myArray);
$source = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronica xmlns="https://tribunet.hacienda.go.cr/docs/esquemas/2017/v4.2/facturaElectronica" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:vc="http://www.w3.org/2007/XMLSchema-versioning" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" targetNamespace="https://tribunet.hacienda.go.cr/docs/esquemas/2017/v4.2/facturaElectronica">'.$xmltext.'</FacturaElectronica>';
$inXmlUrl = $inPath.$myArray['Clave'].'.xml';
$xml = fopen($inXmlUrl,"w+");
$source = utf8_encode($source);
fwrite($xml, $source);
fclose($xml);
$docxml = file_get_contents($inXmlUrl);
$outXmlUrl = $outPath.$myArray['Clave'].'FMD.xml';
$fac = new Firmadocr();
$fac->firmar($p12Url, $pinP12,$inXmlUrl,$outXmlUrl );
$docxml = file_get_contents($outXmlUrl);
$xmlFMD = base64_encode($docxml);
$token = getToken($username, $password);
$jsonData = array(
    "clave" => $myArray['Clave'],
  "fecha" => $myArray['FechaEmision'],
  "emisor" => array(
  	"tipoIdentificacion" => $myArray['Emisor']['Identificacion']['Tipo'],
  	"numeroIdentificacion" => $myArray['Emisor']['Identificacion']['Numero']),
  "comprobanteXml" => $xmlFMD
);
$jsonDataEncoded = json_encode($jsonData);
$curl = curl_init(); 
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt_array($curl, array(
		CURLOPT_HEADER => true,
          CURLOPT_URL => "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion",
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $jsonDataEncoded,
          CURLOPT_HTTPHEADER => array(
            "authorization: Bearer ".$token->{"access_token"} ,
            "content-type: application/json"
          ),
        ));
$response = curl_exec($curl);
$err = curl_error($curl);
if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo $response;
}
curl_close($curl);
function array_to_xml($array) {
    foreach($array as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $i = array_to_xml($value);
                $x .= "<".$key.">".$i."</".$key.">";
            }else{
            	$i = array_to_xml($value);
            	$x .= $i;
            }
        }else {
        	$x .= "<".$key.">".$value."</".$key.">";
        }
    }
    return $x;
}
 ?>
