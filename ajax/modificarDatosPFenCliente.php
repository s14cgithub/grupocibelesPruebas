<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="actualizarDatosPFcliente")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$codigoCliente= $_POST["codigoCliente"];	
	$fecha = $_POST["fecha"];
	$importe = $_POST["importe"];
	$clayma = $_POST["clayma"];
	
	if ($fecha=="" || $fecha==null)
	{
		$fecha= date('d-m-Y');
	}
	
	
	
	
	$usuario = $_SESSION['usuario'];
	
	$descripcion = "modificacion";
	$idRegistro = $codigoCliente;
	
	
	$clayma1=0;
	if ($clayma=="true")
	{
		$clayma1=1;
		$datos=cargarClientesClayma($conexion, " where codigo_saldo =".$codigoCliente);
		$tabla = clientes_tabla + "Clayma";
	}
	else
	{
		$datos=cargarClientes($conexion, " where codigo_saldo =".$codigoCliente);
		$tabla = clientes_tabla;
	}
	
	
	$columna = clientes_fechaCobro;	
	
	
	if ($datos[0][$columna]!=""&&$datos[0][$columna]!=null)
	{
		$datosAntiguos=$datos[0][$columna]->format('d-m-Y');
	}
	else
	{
		$datosAntiguos="";
	}

	
	$datosNuevos = $fecha;
	
	if ($datosNuevos!=$datosAntiguos && $datosNuevos!="")
	{	
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'',$clayma1);	
	}
	
	
	$columna = clientes_importePF;	
	
	if ($datos[0][$columna]==null||$datos[0][$columna]=="")
	{
		$datosAntiguos = 0;
	}
	else
	{
		$datosAntiguos=floatval($datos[0][$columna]);
	}
	
	$datosNuevos = floatval($datosAntiguos) + floatval($importe);
	
	
	if ($datosNuevos!=$datosAntiguos && $datosNuevos!="")
	{	
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'',$clayma1);	
	}
	
	if ($clayma=="true")
	{
		echo modificarDatosPFenClienteClayma($conexion,$codigoCliente,$fecha,$importe);
	}
	else
	{
		echo modificarDatosPFenCliente($conexion,$codigoCliente,$fecha,$importe);
	}
		
	
			
	
}


?>
