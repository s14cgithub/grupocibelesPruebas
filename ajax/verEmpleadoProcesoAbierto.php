<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verSiHayProcesoAbierto")
{
	session_start();
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$idEmpleado = $_SESSION["idEmpleado"];

	$resultado=verSiHayProcesoAbiertoPorEmpleado($conexion, $idEmpleado);


	if (count($resultado)<=0)
	{		
		//siempre debe de haber un registro
		echo ("Error: no existe ese presupuesto");
	}	
	else
	{
		echo  json_encode($resultado);
	}


		
}

?>
