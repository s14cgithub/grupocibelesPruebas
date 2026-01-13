<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verInformePorOt")
{
	$ruta = '../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$ot = $_POST["ot"];
	//echo $fechaAbuscar;
	
		$registros = cargarDatosInformeOtCostesTotales($conexion,$ot);
		
	
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