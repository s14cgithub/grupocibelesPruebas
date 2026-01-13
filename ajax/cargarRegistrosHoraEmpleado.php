<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verHistorialDelDia")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$empleadoAbuscar = $_SESSION['idEmpleado'];
	$fechaAbuscar = date('d/m/Y');
	
	
	$registros = cargarRegistrosHoraSumatorio($conexion,$empleadoAbuscar,$fechaAbuscar);
	
	if (count($registros)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($registros);
	}
		
}

?>
