<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="anadirDetalle")
{
	
		session_start(); 
		$ruta = '../';	
		require($ruta."Archivos Comunes/constantes.php");
		require($ruta."Archivos Comunes/codigoInclude.php");

		$idPedido = $_POST["idPedido"];
		$numPresupuesto = $_POST["numPresupuesto"];
		$descripcion = $_POST["descripcion"];
		$cantidad = $_POST["cantidad"];
		$precioUnitario = $_POST["precioUnitario"];	
		$precioVenta = $_POST["precioVenta"];
		$precioTotal = $_POST["precioTotal"];
		$margen = $_POST["margen"];

		
		echo insertarDetalleCompraTercero ($conexion,$idPedido,$descripcion,$cantidad,$precioUnitario,$precioVenta,$precioTotal,$margen);

	
	
		$usuario = $_SESSION['usuario'];
		$descripcion = log_creacion;
		$tabla = comprasATercerosDetalles_tabla;
		$datosAntiguos = '';
		$datosNuevos = "descripcion: ".$descripcion."|cantidad: ".$cantidad."|precioUnitario: ".$precioUnitario."|precioVenta: ".$precioVenta."|precioTotal: ".$precioTotal."|margen: ".$margen;
		$columna = "todas";
		$idRegistro = 0;	
	
	
		echo insertarRegistro ($conexion, $usuario, $descripcion,$datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);
		
	
	
	
}

?>