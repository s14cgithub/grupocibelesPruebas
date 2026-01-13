<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idDetalle = $_POST["idDetalle"];		
	$proceso = $_POST["proceso"];	
	$descripcion = $_POST["descripcion"];
	$nota = $_POST["nota"];
	$unidad = $_POST["unidad"];
	$precio = $_POST["precio"];
	$orden = $_POST["orden"];
	$numPresupuesto = $_POST["numPresupuesto"];
	$notaAdmonProd = $_POST["notaAdmonProd"];
	
	$descripcion = str_replace("'",'"',$descripcion);
	$nota = str_replace("'",'"',$nota);
	$notaAdmonProd = str_replace("'",'"',$notaAdmonProd);
	
	if ($_POST["exentoIVA"]=="true")
		$exentoIVA = 1;
	else
		$exentoIVA = 0;
		
	
	$peso =  $_POST["peso"];

	//echo "\nDescripcion:".$descripcion."\n";
	/////////////////////////////////////////////
	
	$resultado = cargarUnDetallePresupuesto($conexion,$idDetalle);
	
	$descripcion1 = "modificacion";
	$tabla = presupuestoDetalle_tabla;	
	$idRegistro = $idDetalle;
	$usuario = $_SESSION['usuario'];
	
	
	
	$columna = presupuestoDetalle_ColumnaConcepto;
	if ($proceso<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $proceso;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);	
	}
	
	
	$columna = presupuestoDetalle_ColumnaDescripcion;
	if ($descripcion<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $descripcion;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);	
	}
	
	$columna = presupuestoDetalle_NotaCibeles;
	if ($nota<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $nota;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);	
	}
	
	$columna = presupuestoDetalle_Unidad;
	if ($unidad<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $unidad;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);	
	}
	
	$columna = presupuestoDetalle_Precio;
	if ($precio<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $precio;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);	
	}
	
	$columna = presupuestoDetalle_Orden;
	if ($orden<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $orden;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);	
	}

	$columna = presupuestoDetalle_exentoIVA;
	if ($exentoIVA<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $exentoIVA;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);	
	}

	$columna = presupuestoDetalle_pesoGramos;
	if ($peso<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $peso;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);	
	}
	
	////////////////////////////////////////////////////////////////////////
	
	
	
	
		
echo modificarDetallePresupuesto($conexion,$idDetalle,$proceso,$descripcion,$nota,$unidad,$precio,$orden,$notaAdmonProd,$exentoIVA,$peso);
	
	
}

?>