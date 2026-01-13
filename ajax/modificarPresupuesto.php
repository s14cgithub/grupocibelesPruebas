<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarRegistro")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$presupuesto = $_POST["numPresupuesto"];
	$comercial = $_POST["comercial"];
	$detallada = $_POST["detallada"];
	$cliente = $_POST["cliente"];
	$campania = $_POST["campania"];
	$campaniaObservacion = $_POST["campaniaObservacion"];
	$cantidad = $_POST["cantidad"];
	$pedCliente = $_POST["pedCliente"];
	$direccion = $_POST["direccion"];
	$cp = $_POST["cp"];
	$poblacion = $_POST["poblacion"];
	$formaPago = $_POST["formaPago"];
	$persona = $_POST["persona"];
	
	$mostrarTotalPresupuesto = $_POST["mtp"];
	$mostrarTotalFranqueo = $_POST["mtf"];
	$mostrarTotalFranqueoImporte = $_POST["mtfImporte"];
	
	$notaPresupuesto = $_POST["nota"];
	
	
	$origen = $_POST["origen"];
		
	$origenValor = 0;
	if ($origen=="true")
	{
		$origenValor = 1;
	}

	$trabajoIniciado = $_POST["trabajoIniciado"];

	$trabajoIniciadoValor = 0;
	if ($trabajoIniciado=="true")
	{
		$trabajoIniciadoValor = 1;
	}
	
	
	/////////////////////////////////////////////
	
	$resultado = verPresupuesto($conexion,$presupuesto);
	
	$descripcion = "modificacion";
	$tabla = presupuesto_tabla;
	$columna = presupuesto_ColumnaIdComercial;
	//$idRegistro = $presupuesto;
	$idRegistro = 0;
	
	$usuario = $_SESSION['usuario'];
	
	if ($comercial<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $comercial;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	
	$columna = presupuesto_ColumnaDetallada;
	if ($detallada<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $detallada;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	$columna = presupuesto_ColumnaCliente;
	if ($cliente<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $cliente;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	$columna = presupuesto_ColumnaCampana;
	if ($campania<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $campania;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	$columna = presupuesto_ColumnaCantidad;
	if ($cantidad<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $cantidad;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	
	
	$columna = presupuesto_ColumnaPedCliente;
	if ($pedCliente<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $pedCliente;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	
	$columna = presupuesto_ColumnaDireccion;
	if ($direccion<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $direccion;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	
	$columna = presupuesto_ColumnaCP;
	if ($cp<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $cp;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	$columna = presupuesto_ColumnaPoblacion;
	if ($poblacion<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $poblacion;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	$columna = presupuesto_ColumnaFormaPago;
	if ($formaPago<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $formaPago;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
		
	
	$columna = presupuesto_ColumnaPersona;
	if ($persona<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $persona;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	
	$columna = presupuesto_ColumnaMostrarTotalPresu;
	if ($mostrarTotalPresupuesto<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $mostrarTotalPresupuesto;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	
	$columna = presupuesto_ColumnaMostrarTotalFranqueo;
	if ($mostrarTotalFranqueo<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $mostrarTotalFranqueo;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	
	
	$columna = presupuesto_ColumnaImportelFranqueo;
	if ($mostrarTotalFranqueoImporte<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $mostrarTotalFranqueoImporte;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	
	$columna = presupuesto_notaCibeles;
	if ($notaPresupuesto<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $notaPresupuesto;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}

	$columna = presupuesto_trabajoIniciado;
	if ($trabajoIniciadoValor<>$resultado[0][$columna])
	{		
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $trabajoIniciadoValor;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	//////////////////////////////////////////
	
	
	
	
	
			
		
	modificarPresupuesto($conexion,$presupuesto,$comercial,$detallada,$cliente,$campania,$cantidad,$pedCliente,$direccion,$cp,$poblacion,$formaPago,$persona,$mostrarTotalPresupuesto,$mostrarTotalFranqueo,$mostrarTotalFranqueoImporte,$notaPresupuesto, $origenValor, $campaniaObservacion,$trabajoIniciadoValor);
	
	
	
	
	
	
	
	
	
	
			
	
}


?>
