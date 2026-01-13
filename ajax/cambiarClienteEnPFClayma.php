<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cambiarClienteEnPFClayma")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$numPresupuesto=$_POST["numPresupuesto"];
	$idCliente=$_POST["idCliente"];
	$clayma=$_POST["clayma"];
	
	$clayma1=0;
	if ($clayma=="true")
	{
		$registros = cargarClientesClayma($conexion,$condicion=" where codigo_saldo=".$idCliente);
		$clayma1 = 1;
	}
	else
	{
		$registros = cargarClientes($conexion,$condicion=" where codigo_saldo=".$idCliente);
	}
	
	if (count($registros)<=0)
	{
		echo "Error: no se ha encontrado el cliente";
	}
	else
	{	
		/////SE CAMBIA EL CLIENTE EN LA TABLA DE PRESUPUESTOS.
		$nombreCliente = $registros[0]["nombre_empresa"];
		$direccion = $registros[0]["direccion"];
		$poblacion = $registros[0]["localidad"]; 
		$cp = $registros[0]["codigo_postal"]; 
		
		
		
		echo modificarPresupuesto_clienteClayma($conexion,$numPresupuesto,$nombreCliente, $direccion, $poblacion, $cp, $clayma1);
		
		
		//LOG
		$usuario = $_SESSION['usuario'];
		$descripcion = log_modificacion;
		$tabla = presupuesto_tabla;
		$datosAntiguos = '';
		$datosNuevos = "idCliente: ".$idCliente."; Clayma: ".$clayma1;	
		$columna = 'cliente, direccion y clayma';
		$idRegistro = 0;	



		echo insertarRegistro ($conexion, $usuario, $descripcion,$datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);
		
		
		//SE CAMBIA EL CLIENTE EN LA PROVISION DE FONDOS
		
		echo modificarPFClienteClayma($conexion, $numPresupuesto, $idCliente, $clayma1);
		
		$tabla = provisionDeFondo_tabla;
		$datosNuevos = "idCliente: ".$idCliente."; Clayma: ".$clayma1;	
		$columna = 'idCliente y Clayma';
		echo insertarRegistro ($conexion, $usuario, $descripcion,$datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);
		
	}
	
	
	
	if (count($registros)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($registros);
	}
		
}

?>
