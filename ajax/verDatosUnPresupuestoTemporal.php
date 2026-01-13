<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verDatosUnPresupuesto")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$numPresupuesto = $_POST["numPresupuesto"];	
	$usuario = $_SESSION["idEmpleado"];
	
	$resultado = verSiHayDatosCombinacionPrefactura2($conexion,$numPresupuesto,$usuario);
	
	
	
		
	if (count($resultado)<=0)
	{
		echo ("Error: no existe ese presupuesto");//este caso nunca deberia darse
	}	
	else
	{
		echo  json_encode($resultado);
	}
	
}


?>