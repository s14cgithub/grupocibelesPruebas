<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="guardarCertificado")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$idCliente=$_POST["idCliente"];
	$unidad=$_POST["unidad"];
	$idProducto=$_POST["producto"];
	$fecha=$_POST["fecha"];
	
		
	echo guardarCertificado($conexion,$idCliente,$unidad,$idProducto, $fecha);
	
	
	
		
}

?>
