<?php

session_start(); 
	$ruta = '../../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	

verEstado(38066,$urlCibeles,$apiKeyCibeles);

anularFacturaCibeles(38066,$urlCibeles,$apiKeyCibeles);


verEstado(38066,$urlCibeles,$apiKeyCibeles);




?>