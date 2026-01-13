<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="crearMovimientoAlmacen")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$almacen = $_POST["almacen"];
	$fecha = $_POST["fecha"];
	$cliente = $_POST["cliente"];
	$producto = $_POST["producto"];
	$modalidad = $_POST["modalidad"];
	$hueco = $_POST["hueco"];
	$cantidad = $_POST["cantidad"];
	$observacion= $_POST["observacion"];
	$ot= $_POST["ot"];
	
	
	
	$condicion = " where t1.idSubcliente = ".$cliente." and idProducto = ".$producto." and idHueco= ".$hueco." order by id desc";
	$resultado  = mostrarAlmacenMovimientos($conexion, $condicion);
	
	//echo $resultado;
	
	
	$cantidadTotal=0;
	if (count($resultado)>0)
	{
		$cantidadTotal = $resultado[0]["cantidadTotal"];
	}
	
	if ($cantidad>0 && $modalidad==1)
	{
		$cantidad = $cantidad * (-1);
	}
	
	$cantidadTotal = $cantidadTotal + $cantidad;
	
	
	echo insertarMovimientoAlmacen($conexion, $almacen, $fecha, $cliente, $producto, $modalidad, $hueco, $cantidad, $observacion, $ot, $cantidadTotal);
	
	echo actualizarCantidadTotalAmacen($conexion, $producto, $cantidad);
	
	//echo $condicion;
	
	
	
	
	
	
	
		
	
	
}


?>
