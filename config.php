<?php
$Nombre = ''; //Nomdre del Emisor
$TipoID = ''; //Tipo de identificacion."01":Fisica,"02":Juridica,"03":DIMEX,"04":NITE
$NumeroID = ''; //Numero de identificacion
$NombreComercial = ''; //Nombre de fatacia registrado en hacienda
//ver codificacion de la ubicacion en https://tribunet.hacienda.go.cr/docs/esquemas/2016/v4.2/Codificacionubicacion_V4.2.pdf
$Provincia = ''; 
$Canton = ''; 
$Distrito = ''; 
$Barrio = ''; 
$OtrasSenas = ''; //ejemplo: "de la esquina noreste de la catedral 300 m norte"
$CodigoPais = 506; //codigo telefonico del pais, Costa Rica es 506, debe ser un int
$NumTelefono = ; //debe ser un int de 8 digitos
$CorreoElectronico = ''; //debe ser el mismo registrado en hacienda
$CodigoMoneda = ''; ////ver tabla de codigos en https://tribunet.hacienda.go.cr/docs/esquemas/2016/v4.2/Codigodemoneda_V4.2.pdf Colon:"CRC" Dolar:"USD"
$TipoCambio = ''; //Tipo de cambio de la moneda indicada
$p12Url = ''; //ubicacion del certificado .p12
$pinP12 = ''; //pin de del certificado, int de cuatro digitos
$inPath = ''; //ubicacion del directorio en donde se crearan los xml sin firmar. Ejemplo "/tmp/"
$outPath = '';  //ubicacion del directorio en donde se crearan los xml firmados. Ejemplo "/tmp/"
$username = ''; //nombre de usuario para obtener el token
$password = ''; //password para obtener el token
 ?>
