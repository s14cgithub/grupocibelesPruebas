<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verInformePorDiaDetalle")
{
	$ruta = '../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$fechaAbuscar = $_POST["fecha"];
	$fechaAbuscarFin = $_POST["fechaFin"];
	$nombreEmpleado = $_POST["nombreEmpleado"];
	//echo $fechaAbuscar;
	
		$registros = cargarHorasDetallesEmpleado($conexion,$fechaAbuscar,$fechaAbuscarFin,$nombreEmpleado);
		
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