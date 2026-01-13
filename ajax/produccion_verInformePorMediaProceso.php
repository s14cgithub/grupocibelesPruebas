<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verInformePorAnio")
{
	$ruta = '../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$anioAbuscar = $_POST["anio"];
	//echo $fechaAbuscar;
	
		$registros = cargarDatosInformeMediaProceso($conexion,$anioAbuscar);
		
	
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