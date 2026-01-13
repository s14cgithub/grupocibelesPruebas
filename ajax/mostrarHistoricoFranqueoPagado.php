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

	
	$anioSeleccionado =   date("Y", strtotime($fecha));	
	$fecha1=  date("d-m-Y", strtotime($fecha));
	
	$condicion = " where idProductoPadre = ".$idProducto." and fecha = '".$fecha1."' and idEmpleado = ".$idEmpleado. "order by id desc" ;


	$franqueo=mostrarHistoricoFranqueoPagado($conexion,$anioSeleccionado, $condicion);
	
	
	
	
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
