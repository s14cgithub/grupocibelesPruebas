<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="cambiarValorLiquidado")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$clayma = $_POST["clayma"];
	$anio = $_POST["anio"];
	$tipo = $_POST["tipo"];
	$numero = $_POST["numero"];
	$liquidado = $_POST["liquidado"];
	
	$clayma1=0;
	if ($clayma=="true")
	{
		$clayma1=1;
	}
	else
	{
		
	}
	
	$liquidado1=0;
	if ($liquidado=="true")
	{
		$liquidado1=1;
	}
	else
	{
		
	}
	
	$condicion=" where t1.numero=".$numero;
	
	$usuario = $_SESSION['usuario'];
	$tabla = $tipo.$anio;
	$descripcion = log_modificacion;
	

	
	if ($tipo=="factura" && $clayma=="true")
	{echo "entra1";
		$datos=mostrarFacturasClayma($conexion,$condicion,$anio);
		echo modificarFacturaClayma_liquidado($conexion, $numero,$anio, $liquidado1);
	}
	else if ($tipo=="factura")
	{	
		echo "entra2";
		$datos=mostrarFacturas($conexion,$condicion,$anio);
		echo modificarFactura_liquidado($conexion, $numero,$anio, $liquidado1);
	}
	else if ($tipo=="abono" && $clayma=="true")
	{echo "entra3";
		$datos=mostrarAbonosClayma($conexion,$condicion,$anio);	
		echo modificarAbonoClayma_liquidado($conexion, $numero,$anio, $liquidado1);
	}
	else
	{echo "entra4";
		$datos=mostrarAbonos($conexion,$condicion,$anio);
		echo modificarAbono_liquidado($conexion, $numero,$anio, $liquidado1);
	}	
	
	
	$idRegistro=$numero;
	$numPresupuesto="";
	$columna = 'liquidado';	
	$datosAntiguos = $datos[0][$columna];
	$datosNuevos = $liquidado1;
		
	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla, $columna, $idRegistro,$numPresupuesto,$clayma1);	
	
	
	
	
	
	
	
	
}


?>
