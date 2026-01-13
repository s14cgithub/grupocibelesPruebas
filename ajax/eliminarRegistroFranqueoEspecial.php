<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="eliminarRegistroFranqueoEspecial2")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$id = $_POST["id"];
	
	
	$resultado = cargarDatosFranqueoTipoPorId($conexion,$id);
	
	
	
	if ($resultado[0]["comprobado"]==1)
	{
		$fecha = date("d-m-Y");
		$importe = floatval($resultado[0]["importe"]);
		$codigoCliente = $resultado[0]["codigo_saldo"];

		$saldo =  $resultado[0]["importePF"];
		$nuevoSaldo = $resultado[0]["importePF"] + $importe;	
		$informacionCuadre='registro borrado en franqueo f12';
		$fechaCuadre='';
		$formaPago='';
		$presupuesto='';
		echo insertarMovimientoPF($conexion,$codigoCliente,$fecha,$formaPago,$importe,$presupuesto,$fechaCuadre,$informacionCuadre,$nuevoSaldo);

		echo modificarDatosPFenCliente($conexion,$codigoCliente,$fecha,$importe);
	}
	
	
	echo eliminarRegistrosFranqueoTiposPorId($conexion,$id);
	
	
	
	
}



?>