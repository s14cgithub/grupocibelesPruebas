<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="ejecutarF12")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
		
	$resultado = verImportesTotalesFranqueo($conexion);
	
	$contador=0;
	
	while ($contador<count($resultado))
	{
		$fecha = date("d-m-Y");
		$importe = floatval($resultado[$contador]["importeTotal"])*-1;
		$codigoCliente = $resultado[$contador]["idCodigoSaldo"];
		
		
		///////////////////////////////////////
		
		$saldo = cargarClientes($conexion," where codigo_saldo = ".$codigoCliente);
		$nuevoSaldo = $saldo[0]["importePF"] + $importe;	
		$informacionCuadre='';
		$fechaCuadre='';
		$formaPago='';
		$presupuesto='';
		insertarMovimientoPF($conexion,$codigoCliente,$fecha,$formaPago,$importe,$presupuesto,$fechaCuadre,$informacionCuadre,$nuevoSaldo);	
		
		
		////////////////////////////////////////
		
		echo modificarDatosPFenCliente($conexion,$codigoCliente,$fecha,$importe);
			
		$contador++;
	}
	echo ejecutarF12($conexion);

	cambiar_clienteAutorizadosUndia($conexion);
	
	/*echo ejecutarF12($conexion);
	
	$resultado = verImportesTotalesFranqueo($conexion);
	
	$contador=0;
	
	while ($contador<count($resultado))
	{
		$fecha = date("d-m-Y");
		$importe = floatval($resultado[$contador]["importeTotal"])*-1;
		$codigoCliente = $resultado[$contador]["idCodigoSaldo"];
		
		
		///////////////////////////////////////
		
		$saldo = cargarClientes($conexion," where codigo_saldo = ".$codigoCliente);
		$nuevoSaldo = $saldo[0]["importePF"] + $importe;	
		$informacionCuadre='';
		$fechaCuadre='';
		$formaPago='';
		$presupuesto='';
		insertarMovimientoPF($conexion,$codigoCliente,$fecha,$formaPago,$importe,$presupuesto,$fechaCuadre,$informacionCuadre,$nuevoSaldo);	
		
		
		////////////////////////////////////////
		
		echo modificarDatosPFenCliente($conexion,$codigoCliente,$fecha,$importe);
			
		$contador++;
	}
	
	*/
	
	
	
}



?>