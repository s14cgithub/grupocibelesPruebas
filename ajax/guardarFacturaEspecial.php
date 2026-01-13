<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="guardarFacturaEspecial2")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$idCliente=$_POST["idCliente"];
	$ordenTrabajo=$_POST["ordenTrabajo"];
	$fecha=$_POST["fecha"];
	$concepto=$_POST["concepto"];
	$unidades=$_POST["unidades"];
	$importe=$_POST["importe"];
	
	
	
		
	echo guardarFacturaEspecial($conexion,$idCliente,$ordenTrabajo,$fecha,$concepto,$unidades,$importe);
	
	
	
		
}

?>
