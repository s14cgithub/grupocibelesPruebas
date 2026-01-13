<?php

$ruta="/";
require($ruta."Archivos Comunes/constantes.php");
require($ruta.$rutaConexionSql);


$consulta = "select * from dbo.nombre";

$resultado = sqlsrv_query($conn_sis, $consulta);

if( $resultado === false) {
    die( print_r( sqlsrv_errors(), true) );
}

//echo $resultado[0] ;
while ($row= sqlsrv_fetch_array($resultado))
	
{
	echo $row[0];
}





?>