<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="guardarAlbaran")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$idEmpleado=$_POST["idEmpleado"];
	$idTipoAlbaran=$_POST["idTipoAlbaran"];
	$idCliente=$_POST["idCliente"];
	$fecha=$_POST["fecha"];
	$cantidad=$_POST["cantidad"];
	$importe=$_POST["importe"];
	$descripcion=$_POST["descripcion"];
	
	//$fecha = DateTime($fecha);
	//$fecha2 = $fecha->format('Y-m-d H:i:s');
	
	$fecha2=	str_replace("T", " ",$fecha);
	echo guardarAlbaran($conexion,$idEmpleado,$idTipoAlbaran,$idCliente,$fecha2,$cantidad,$importe,$descripcion);
	
	
	
		
}

?>
