<?php 




if(isset($_POST["accion"])&$_POST["accion"]=="verInformePorDia")
{
	$ruta = '../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$fechaAbuscar = $_POST["fecha"];
	$fechaAbuscarFin = $_POST["fechaFin"];
	
	
	
	
	
	$registros = cargarOtPorFechasTotal($conexion,$fechaAbuscar,$fechaAbuscarFin);
		
	//echo $registros[0]["nombreEmpleado"];
	if (count($registros)<=0)
	{
		echo json_encode("");
	}
	else
	{

		echo json_encode($registros);
	}
}






?>