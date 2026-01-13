<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarRegistro3")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$presupuesto = $_POST["numPresupuesto"];
	
	$fechaAceptacion = $_POST["fechaAceptacion"];
	$fechaCompromiso = $_POST["fechaCompromiso"];

	
	if ($fechaAceptacion==null||$fechaAceptacion=="null" ||$fechaAceptacion=="")
	{
		$fechaAceptacion=null;
	}
	if ($fechaCompromiso==null||$fechaCompromiso=="null" ||$fechaCompromiso=="")
	{
		$fechaCompromiso=null;
	}
	
	
	
	
	$resultado = verPresupuesto($conexion,$presupuesto);
	
	$descripcion = log_modificacion;
	$tabla = presupuesto_tabla;
	$columna = presupuesto_fechaAceptacion;	
	$idRegistro = 0;
	$usuario = $_SESSION['usuario'];
	
	if ($fechaAceptacion!=$resultado[0][$columna])
	{
		if ($resultado[0][$columna]==null || $resultado[0][$columna]=="null" || $resultado[0][$columna]=="")
		{
			$datosAntiguos = $resultado[0][$columna];
		}
		else
		{
			$datosAntiguos = $resultado[0][$columna]->format('d/m/Y');
		}
		
		$datosNuevos = $fechaAceptacion;			
		//echo $datosNuevos." ".$datosAntiguos;
		//insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	$columna = presupuesto_fechaCompromiso;	
	if ($fechaCompromiso!=$resultado[0][$columna])
	{
		if ($resultado[0][$columna]==null || $resultado[0][$columna]=="null" || $resultado[0][$columna]=="")
		{
			$datosAntiguos = $resultado[0][$columna];
		}
		else
		{
			$datosAntiguos = $resultado[0][$columna]->format('d/m/Y');
		}
		$datosNuevos = $fechaCompromiso;			
		
		//insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	
	
	
	
	
		
	echo modificarPresupuesto3($conexion,$presupuesto,$fechaAceptacion,$fechaCompromiso);	
	
			
	
}


?>
