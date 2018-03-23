# FacturaElectronica_CostaRica
Api web en PHP para firma y enviar comprobantes electrónicos a Hacienda.
Recibe un texto en formato CSV vía POST Request del cual se crea un XML según los formatos del Ministerio de Hacienda.

CSV

El fichero CSV que recibe debe de tener la siguiente estructura:
 
 Primera línea:(string NumFactura)
 Segunda línea en adelante:( String Codigo),(fload Cantidad),(string Detalle),(flad Precio)
 
NumFactura=>Un entero positivo, no puede ser igual a 0 ni mayor a 9999999999.
Codigo=>Código del servicio o producto
Cantidad=>Cantidad del producto o servicio, precisión de 3 dígitos decimales
Detalle=>Detalle del producto o Servicio
Precio=>Precio unitario del producto o servicio, precisión de 5 dígitos decimales

De la segunda línea en adelante puede incluir todas los productos y servicios a facturar siempre respetando la misma estructura (Codigo,Cantidad,Detalle,Precio)
Se debe de respetar el orden de los campos.

Configuración

En el fichero conf.php están la lista de variables a configurar, todos los valores deben ser modificados según necesidad del obligado tributario.
En la variable $ se debe de poner la ubicación de la clave criptográfica ce hacienda, recomendamos que no sea en un directorio público del servidor.

La URL del fichero "api-textplain.php" es a la que se debe enviar el CSV vía post.

Firmadohaciendacr.php tomado de https://github.com/CRLibre/API_Hacienda/blob/master/api/contrib/signXML/Firmadohaciendacr.php del repositorio de CRLibre.
