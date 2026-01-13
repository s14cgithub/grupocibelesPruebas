<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="eliminarRegistroFranqueo2")
{	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$id = $_POST["id"];
	$referencia = $_POST["referencia"];	
	
	$anioSeleccionado = $_POST["anioSeleccionado"];
	
	$resultado = verCodigoSaldoPorReferenciaFranqueo($conexion,$referencia,$anioSeleccionado);
	
	
	if ($resultado[0]["comprobado"]==1)
	{
		$fecha = date("d-m-Y");
		$importe = floatval($resultado[0]["importe"]);
		$codigoCliente = $resultado[0]["codigo_saldo"];

		$saldo = cargarClientes($conexion," where codigo_saldo = ".$codigoCliente);
		$nuevoSaldo = $saldo[0]["importePF"] + $importe;	
		$informacionCuadre='registro borrado en franqueo';
		$fechaCuadre='';
		$formaPago='';
		$presupuesto='';
		echo insertarMovimientoPF($conexion,$codigoCliente,$fecha,$formaPago,$importe,$presupuesto,$fechaCuadre,$informacionCuadre,$nuevoSaldo);	


		echo modificarDatosPFenCliente($conexion,$codigoCliente,$fecha,$importe);
	}
	
	eliminarRegistroFranqueo($conexion,$id,$referencia,$anioSeleccionado);
	echo eliminarRegistrosFranqueoTipos($conexion,$referencia,$anioSeleccionado);
	
}



?>