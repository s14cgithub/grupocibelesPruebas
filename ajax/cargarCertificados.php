<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarCertificados")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	
	
	
	$certificados=cargarCertificados($conexion);
	
	if (count($certificados)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($certificados);
	}
		
}

?>
