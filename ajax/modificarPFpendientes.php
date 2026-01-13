<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarPFpendientes")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idPF = $_POST["idPF"];
	$cobrada = $_POST["cobrada"];
	$fecha = $_POST["fecha"];
	$formaPago = $_POST["formaPago"];
	$importe = $_POST["importe"]; 
	
	$numPresupuesto = $_POST["numPresupuesto"];
	$clayma = $_POST["clayma"];
	
	
	$idRegistro = $idPF;
	
	
	$clayma1=0;
	if ($clayma=="true")
	{
		$clayma1=1;
	}
	else
	{
		
	}
	
	
	
	$usuario = $_SESSION['usuario'];
	$tabla = provisionDeFondo_tabla;
	$descripcion = log_modificacion;
	
	
	$datos=mostrarProvisionDeFondosPorId($conexion, $idPF);
	
	$columna = provisionDeFondo_cobrada;	
	$datosAntiguos=$datos[0][$columna];
	$datosNuevos = $cobrada;
	
	if ($datosNuevos!=$datosAntiguos && $datosNuevos!="")
	{	
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto,$clayma1);	
	}
	
	$columna = provisionDeFondo_fechaCobro;		
	$datosNuevos = $fecha;
	
	
	if ($datos[0][$columna]!="" && $datos[0][$columna]!=null)
	{
		$datosAntiguos=$datos[0][$columna]->format('d-m-Y');
	}
	else
	{
		$datosAntiguos="";
	}
	
	
	
	
	if ($datosNuevos!=$datosAntiguos && $datosNuevos!="")
	{	
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto,$clayma1);	
	}
	
	$columna = provisionDeFondo_formaPago;	
	$datosAntiguos=$datos[0][$columna];
	$datosNuevos = $formaPago;
	
	if ($datosNuevos!=$datosAntiguos && $datosNuevos!="")
	{	
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto,$clayma1);	
	}
	
	
	$columna = provisionDeFondo_importe;	
	$datosAntiguos=$datos[0][$columna];
	$datosNuevos = $importe;
	
	if ($datosNuevos!=$datosAntiguos && $datosNuevos!="")
	{	
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto,$clayma1);	
	}
	
	
	
	
	
		
	echo modificarPFpendientes($conexion,$idPF,$cobrada,$fecha,$formaPago,$importe);	
	
	
	/////////////////////TRASPASAR DATOS A ACCESS
	
	insertarTemporalProvisionTraspaso($conexion, $numPresupuesto); //esto no se para qué se hizo
	
	
	
}


?>
