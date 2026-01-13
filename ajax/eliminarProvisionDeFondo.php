<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarProvisionFondo")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
			
	$idRegistro = $_POST["id"];
	
	$usuario = $_SESSION['usuario'];
	$tabla = provisionDeFondo_tabla;
	$descripcion = "eliminacion";
	$columna = "todas";
	$datosNuevos = "";
	
	$resultado = mostrarProvisionDeFondosPorId($conexion,$idRegistro);
	
	
	$presupuesto = $resultado[0][provisionDeFondo_presupuesto];
	$importe = $resultado[0][provisionDeFondo_importe];
	
	if ($resultado[0][provisionDeFondo_fechaCreacion] == "" or $resultado[0][provisionDeFondo_fechaCreacion] == null )
	{
		$fechaCreacion = null;
	}
	else
	{
		$fechaCreacion = $resultado[0][provisionDeFondo_fechaCreacion]->format('Y-m-d H:i:s');
	}
	
	
	$tipo = $resultado[0][provisionDeFondo_tipo]; 
	$cobrada = $resultado[0][provisionDeFondo_cobrada]; 
	
	if ($resultado[0][provisionDeFondo_fechaCobro] == "" or $resultado[0][provisionDeFondo_fechaCobro] == null )
	{
		$fechaCobro = null;
	}
	else
	{
		$fechaCobro = $resultado[0][provisionDeFondo_fechaCobro]->format('Y-m-d H:i:s');
	}

	$formaPago = $resultado[0][provisionDeFondo_formaPago];
	
	
	
	
	$datosAntiguos = "presupuesto: ".$presupuesto."||"."importe: ".$importe."||"."fechaCreacion: ".$fechaCreacion."||"."tipo: ".$tipo."||"."cobrada: ".$cobrada."||"."fechaCobro: ".$fechaCobro."||"."formaPago: ".$formaPago;
	
	//echo("\n".$datosAntiguos."\n");
	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');
		
	eliminarProvisionFondos($conexion,$idRegistro);
	
	
	
	
	
	
	
	
			
	
}


?>
