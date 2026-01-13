<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cambiarValorPdf")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$presupuesto = $_POST["numPresupuesto"];
	$valorPdfGenerado = $_POST["valor"];
	
	
	/////////////////////////////////////////////
	
	$resultado = verPresupuesto($conexion,$presupuesto);
	
	$descripcion = "modificacion";
	$tabla = presupuesto_tabla;
	$columna = presupuesto_pdfGenerado;
	//$idRegistro = $presupuesto;
	$idRegistro = 0;
	$usuario = $_SESSION['usuario'];
	
	//$valorGuardado = $resultado[0][$columna];
	
	if ($valorPdfGenerado!=$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna]."";
		$datosNuevos = $valorPdfGenerado;			
		//echo $datosAntiguos."a";
		echo insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	echo modificarPresupuestoValorPdfGenerado($conexion,$presupuesto,$valorPdfGenerado);
	
	
	
	
	
	
	
	
	
			
	
}


?>
