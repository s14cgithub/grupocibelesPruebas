<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="rellenarHistoricoFranqueo")
{
	session_start(); 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	$idEmpleado = $_SESSION["idEmpleado"];
	$idProducto = $_POST["idProducto"];
	$fecha = $_POST["fecha"];

	
	$permisoF12=$_SESSION["permiso_franqueoF12"];
	
	$franqueo=mostrarHistoricoFranqueo($conexion,$idEmpleado,$idProducto,$permisoF12,$fecha);
	
	
	
	if (count($franqueo)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($franqueo);
	}
		
}

?>
