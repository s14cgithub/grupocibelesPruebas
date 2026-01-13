<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarMovimiento")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$codigoCliente = $_POST["codigoCliente"];
	$fecha = $_POST["fecha"];
	
	if ($fecha=="" || $fecha==null)
	{		
		$fecha= date('d-m-Y');
	}
	
	
	$formaPago = $_POST["formaPago"];
	$importe=$_POST["importe"];
	$presupuesto=$_POST["presupuesto"];
	$fechaCuadre=$_POST["fechaCuadre"];
	
	
	$informacionCuadre=$_POST["informacionCuadre"];	
	
	$clayma=$_POST["clayma"];	
	$clayma1=0;
	
	if ($clayma=="1")
	{
		$saldo = cargarClientesClayma($conexion," where codigo_saldo = ".$codigoCliente);
		$clayma1=1;
	}
	else
	{
		$saldo = cargarClientes($conexion," where codigo_saldo = ".$codigoCliente);
	}
	
	
	$nuevoSaldo = $saldo[0]["importePF"] + $importe;
	
	
	echo insertarMovimientoPF($conexion,$codigoCliente,$fecha,$formaPago,$importe,$presupuesto,$fechaCuadre,$informacionCuadre,$nuevoSaldo,$clayma1);	
	
	
	$usuario = $_SESSION['usuario'];
	$tabla = provisionDeFondoMovimientos_tabla;
	$descripcion = "creacion";
	$columna = "todas";
	$datosAntiguos = "";
	$idRegistro=0;
	
	$datosNuevos = "codigoCliente: ".$codigoCliente."||Clayma: ".$clayma1."||fecha: ".$fecha."||formaPago: ".$formaPago."||importe: ".$importe."||presupuesto: ".$presupuesto."||fechaCuadre: ".$fechaCuadre."||informacionCuadre: ".$informacionCuadre;
	
	//echo("\n".$datosAntiguos."\n");
	echo insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');
	
	
	
			
	
}


?>
