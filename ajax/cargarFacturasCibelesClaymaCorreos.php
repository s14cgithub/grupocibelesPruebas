<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarFacturasCibelesClaymaCorreos")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$campos=isset($_POST["campos"])?json_decode($_POST["campos"], true):array();

	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();

	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();

	$filtrosLike=isset($_POST["filtrosLike"])?json_decode($_POST["filtrosLike"], true):array();

	$order=isset($_POST["order"])?json_decode($_POST["order"], true):array();

	if (isset($_POST["pantallaDeOrigen"]) && $_POST["pantallaDeOrigen"]=="js_facturasSinCobrarTotal.js")
	{
		$guardarBusqueda = isset($_POST["guardarBusqueda"]) ? $_POST["guardarBusqueda"] : "";

		$partes = explode('|', $guardarBusqueda);
		$datosBusqueda = array();

		foreach ($partes as $parte)
		{
			$clave_valor = explode('=', $parte, 2);
			if (count($clave_valor)==2)
			{
				$datosBusqueda[$clave_valor[0]] = $clave_valor[1];
			}
		}

		$_SESSION["facturasSinCobrar_queBusca"] = isset($datosBusqueda['buscarCampo']) ? $datosBusqueda['buscarCampo'] : '';
		$_SESSION["facturasSinCobrar_texto"] = isset($datosBusqueda['buscarTexto']) ? $datosBusqueda['buscarTexto'] : '';
		$_SESSION["facturasSinCobrar_orden"] = isset($datosBusqueda['ordenBuscar']) ? $datosBusqueda['ordenBuscar'] : '';
		$_SESSION["facturasSinCobrar_ordenDesc"] = isset($datosBusqueda['ordenDesc']) ? $datosBusqueda['ordenDesc'] : '';
		$_SESSION["facturasSinCobrar_fechaInicio"] = isset($datosBusqueda['fechaInicio']) ? $datosBusqueda['fechaInicio'] : '';
		$_SESSION["facturasSinCobrar_fechaFin"] = isset($datosBusqueda['fechaFin']) ? $datosBusqueda['fechaFin'] : '';
		$_SESSION["facturasSinCobrar_origen"] = isset($datosBusqueda['origen']) ? $datosBusqueda['origen'] : '';
		$_SESSION["facturasSinCobrar_domiciliada"] = isset($datosBusqueda['domiciliada']) ? $datosBusqueda['domiciliada'] : '';
	}

	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$cargarFacturasCibelesClaymaCorreos = mostrarFacturasCibelesClaymaCorreos($conn,$bbddSql, $campos, $filtros,$filtrosOperadores, $filtrosLike, $order);

	sqlsrv_close($conn);

	echo json_encode($cargarFacturasCibelesClaymaCorreos);

}

?>
