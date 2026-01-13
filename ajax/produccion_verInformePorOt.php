<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verInformePorOt")
{
	$ruta = '../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$otAbuscar = $_POST["ot"];
	//echo $fechaAbuscar;
	
		$registros = cargarDatosInformeOt($conexion,$otAbuscar);
		
	
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