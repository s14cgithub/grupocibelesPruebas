<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarClienteDireccionesRutas")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	
	$idCliente = $_POST["idCliente"];
	$att = $_POST["att"];
	$nombre = $_POST["nombre"];
	$direccion = $_POST["direccion"];
	$cp = $_POST["cp"];
	$poblacion = $_POST["poblacion"];
	$provincia = $_POST["provincia"];
	$pais = $_POST["pais"];

	//$activo = $_POST["activo"];
	$activo="false";

	$activo1=0;
	if ($activo =="true")
	{
		$activo1=1;
	}
	
	
	
	$clayma = $_POST["clayma"];
	
	if ($clayma=="true")
	{
		echo insertarDireccionRutaClienteClayma($conexion,$idCliente,$att,$nombre,$direccion,$cp,$poblacion,$provincia,$pais,$activo1);
	}
	else
	{
		echo insertarDireccionRutaCliente($conexion,$idCliente,$att,$nombre,$direccion,$cp,$poblacion,$provincia,$pais,$activo1);
	}	
}


?>
