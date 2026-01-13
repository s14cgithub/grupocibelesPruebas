<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="gestionAjustarSaldo")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
		
	
	
	$idCliente = $_POST["idCliente"];	
	$importe = $_POST["importe"];
	$concepto = $_POST["concepto"];
	//$fechaCuadre = $_POST["fecha"];
	
	//$fechaCuadre = date("d-m-Y", strtotime($fechaCuadre));
	
	$fechaCuadre = date('d-m-Y');
	$fecha = $fechaCuadre;
	
	
	
	$clayma1=0;
	$informacionCuadre='Ajuste de Saldo';
	
	$saldo = cargarClientes($conexion," where codigo = ".$idCliente);
	
	
	
	$nuevoSaldo = $saldo[0]["importePF"] + $importe;
	$presupuesto = '';
	$formaPago = '';
	
	
	echo insertarMovimientoPF($conexion,$idCliente,$fecha,$formaPago,$importe,$presupuesto,$fechaCuadre,$informacionCuadre,$nuevoSaldo,$clayma1);	
	
	
	echo modificarDatosPFenCliente($conexion,$idCliente,$fecha,$importe);
	
	
	/*$usuario = $_SESSION['usuario'];
	$tabla = provisionDeFondoMovimientos_tabla;
	$descripcion = "creacion";
	$columna = "todas";
	$datosAntiguos = "";
	$idRegistro=0;
	
	$datosNuevos = "codigoCliente: ".$codigoCliente."||Clayma: ".$clayma1."||fecha: ".$fecha."||formaPago: ".$formaPago."||importe: ".$importe."||presupuesto: ".$presupuesto."||fechaCuadre: ".$fechaCuadre."||informacionCuadre: ".$informacionCuadre;
	
	//echo("\n".$datosAntiguos."\n");
	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');*/
	
	
	
			
	
}


?>
