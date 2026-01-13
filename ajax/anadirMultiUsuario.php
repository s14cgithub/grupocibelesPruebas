<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarMultiUsuario")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$idMultiEmpleado = $_POST["idMultiEmpleado"];
	$idUsuario = $_SESSION["idEmpleado"];
	

	
	$registroTrabajo = verSiHayProcesoAbiertoPorEmpleado($conexion, $idMultiEmpleado);

	$iniciarTrabajo=false;			
		
	if ($idUsuario == $idMultiEmpleado)
	{
		echo ("Error1: No te puede añadir a tí mismo. Contigo ya se cuenta.");		
	}
	else if (count($registroTrabajo)<=0)
	{   
		$iniciarTrabajo=true;		
	}
	else if ($registroTrabajo[0]["estado"]==estadoCerrado)
	{
		$iniciarTrabajo=true;
	}
	else
	{
		echo ("Error2: No se puede añadir a ".$registroTrabajo[0]["nombreEmpleado"]." porque tiene abierto el proceso: <br>".$registroTrabajo[0]["codigoBarras"]. "<br>Cliente: ".$registroTrabajo[0]["cliente"]. "<br>Descripcion: ".$registroTrabajo[0]["descripcion"]);		
	}
	
	
	if ($iniciarTrabajo == true)
	{	
		echo insertarMultiUsuario($conexion, $idUsuario, $idMultiEmpleado);
	}
	
	
}


?>