<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modPedido")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	//$id = $_POST["id"];
	
	$pedido = $_POST["pedido"];
	$notas = $_POST["notas"];
	$numPresupuesto = $_POST["numPresupuesto"];
	
	
	
	$usuario = $_SESSION['usuario'];
	$tabla = presupuesto_tabla;
	$descripcion = log_modificacion;
	$idRegistro = 0;
	
	
	$resultado = verPedidoClientePresupuesto($conexion, $numPresupuesto);
	
		
	
	$columna = presupuesto_ColumnaPedCliente;	
	$datosAntiguos=$resultado[0]["pedcli"];
	$datosNuevos = $pedido + "|"+ $notas;
	
		
	echo insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	
	
		
	echo modificarPresupuestoPedidoNotasCliente($conexion,$numPresupuesto,$pedido,$notas);	
	
	
	
	
}


?>
