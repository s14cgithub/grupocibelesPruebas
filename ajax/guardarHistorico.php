<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="guardarHistoricoRuta")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
			
	
	$idCliente= $_POST["idCliente"];	
	$firma= $_POST["firma"];
	$firmaValor = "";
	$horaRuta = $_POST["horaRuta"];
	$dni= $_POST["dni"];
	$nombre = $_POST["nombre"];
	
	
	$idEmpleado = $_SESSION["idEmpleado"];
	$resultado = verSiExisteRuta($conexion,$idCliente,$idEmpleado,$horaRuta);
		
	if ($firma=="si")
	{
		//$firmaValor = date('Y-m-d')."_".$idCliente.".jpg";
		$firmaValor = date('Y-m-d')."_".str_replace(":","-",$horaRuta)."_".$idCliente.".jpg";
		//$firmaValor = str_replace(":","-",$horaRuta).".jpg";
		if (count($resultado)<=0)
		{
			echo guardarHistoricoRuta($conexion,$idCliente,$firmaValor,$idEmpleado,$horaRuta);
		}
		else
		{
			$id = $resultado[0]["id"];
			echo modificarHistoricoRutaFirma($conexion,$id,$firmaValor);
		}
	}
	else
	{
		if (count($resultado)<=0)
		{
			echo guardarHistoricoRutaNombre($conexion,$idCliente,$nombre, $dni,$idEmpleado,$horaRuta);
		}
		else
		{
			$id = $resultado[0]["id"];
			//echo ("id: ".$id."; nombre: ".$nombre. "; dni: ".$dni. ";");
			echo modificarHistoricoRutaNombreDni($conexion,$id,$nombre,$dni);
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}


?>
