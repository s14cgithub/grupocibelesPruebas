<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarClienteDireccionRutas")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");


	$id = $_POST["id"];
	$att = $_POST["att"];
	$nombre = $_POST["nombre"];
	$direccion = $_POST["direccion"];
	$cp = $_POST["cp"];
	$poblacion = $_POST["poblacion"];
	$provincia = $_POST["provincia"];
	$pais = $_POST["pais"];
	$activo = $_POST["activo"];
	$idCliente = $_POST["idCliente"];
	$clayma=$_POST["clayma"];

	$activo1=0;
	if ($activo =="true")
	{
		$activo1=1;
		if ($clayma=="true") 
		{			
			cambiarActivosDireccionesRutaClayma($conexion,$id,$idCliente);
		}
		else
		{
			cambiarActivosDireccionesRuta($conexion,$id,$idCliente);
		}
		
	}

	

	
		
	if ($clayma=="true")
	{
		echo modificarClienteDireccionRutaClayma($conexion,$id,$att,$nombre,$direccion,$cp,$poblacion,$provincia,$pais,$activo1);	
	}
	else
	{
		echo modificarClienteDireccionRuta($conexion,$id,$att,$nombre,$direccion,$cp,$poblacion,$provincia,$pais,$activo1);	
	}
	
	
	
		
	
	
			
	
}


?>
