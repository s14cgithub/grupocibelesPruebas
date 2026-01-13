<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verHoraInicio")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	//require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
		
			
	session_start();
			
	$idEmpleado = $_SESSION["idEmpleado"];
	
	$resultado = verUltimoRegistroTrabajo($conexion,$idEmpleado);
	
	echo json_encode($resultado);
			
	
}


?>
