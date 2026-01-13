<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="aumentarLetra")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$numPresupuesto = $_POST["numPresupuesto"];
	
	$resultado = cargarLetraNumPresupuesto($conexion,$numPresupuesto);
	
	
	
	if (count($resultado)<=0)
	{
		echo ("Error: no se ha encontrado el numero de presupuesto: ".$numPresupuesto);
	}
	else
	{
		$nuevaLetra = "";
		$letra = $resultado[0]["letra"];
		if ($letra == "null" || $letra == null || $letra=="")
		{
			$nuevaLetra = "B";
		}
		else
		{
			
			$nuevaLetra = ++$letra;			
		}
		
		modificarLetraPresupuesto($conexion,$nuevaLetra,$numPresupuesto);
		
		
		
		//LOG
		$usuario = $_SESSION['usuario'];
		$descripcion = log_creacion;
		$tabla = presupuesto_tabla;
		$datosAntiguos = $resultado[0]["letra"];
		$datosNuevos = $nuevaLetra;
		$columna = presupuesto_Letra;
		$idRegistro = 0;	



		echo insertarRegistro ($conexion, $usuario, $descripcion,$datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto." - ".$nuevaLetra);
		
		
	}
	
	
	
	echo ($nuevaLetra);
	
}


?>