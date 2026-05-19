<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarRegistro")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();
	

	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	
	

	//LOG

	/*
	$campos2 = [
		'idComercial',
		'detallada',
		'cliente',
		'campana',
		'cantidad',
		'pedcli',
		'direccion',
		'cp',
		'poblacion',
		'idFormaPago',
		'persona',
		'idVisualizarTotalPresu',
		'idVisualizarTotalFranqueo',
		'importeFranqueo',
		'notaCibeles',
		'trabajoIniciado',
		'clayma'
	];
	*/

	$campos2 = array_keys($datos);

	$joins2 = array();

	$numPresupuesto = $filtros['presupuesto'];

	$filtros2 = [
		'presupuesto' => $numPresupuesto
	];

	$filtrosOperadores2 = array();
	$order2 = array();

	$cargarPresupuestos = cargarPresupuestos($conn,$bbddSql, $campos2, $joins2, $filtros2,$filtrosOperadores2, $order2);


	$i = 0;
	while ($i < count($campos2)) {
		
		$columna2 = $columna = $campos2[$i];
		//$columna2=$columna;
		//echo "\n".$columna;

		if ($columna=='origen')
		{
			$columna2=='clayma';
		}

		if ($datos[$columna] != $cargarPresupuestos['datos'][0][$columna2])
		{
			$datos2 = array(
				'usuario' => $_SESSION['usuario'],
				'descripcion' => log_modificacion,
				'tabla' => presupuesto_tabla ,
				'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna2],
				'datosNuevos' => $datos[$columna],
				'columna' => $columna,
				'idRegistro' => 0,
				'presupuesto' => $numPresupuesto
			);
			insertarRegistro($conn,$bbddSql, $datos2);		
		}

		


		//echo $campos[$i] . "<br>";
		$i++;
	}

	/*
	$columna = presupuesto_ColumnaIdComercial;
	if ($datos['idComercial'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['idComercial'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_ColumnaDetallada;
	if ($datos['detallada'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['detallada'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_ColumnaCliente;
	if ($datos['cliente'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['cliente'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_ColumnaCampana;
	if ($datos['campana'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['campana'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_ColumnaCantidad;
	if ($datos['cantidad'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['cantidad'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_ColumnaPedCliente;
	if ($datos['pedcli'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['pedcli'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_ColumnaDireccion;
	if ($datos['direccion'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['direccion'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_ColumnaCP;
	if ($datos['cp'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['cp'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_ColumnaPoblacion;
	if ($datos['poblacion'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['poblacion'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_ColumnaFormaPago;
	if ($datos['idFormaPago'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['idFormaPago'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_ColumnaPersona;
	if ($datos['persona'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['persona'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_ColumnaMostrarTotalPresu;
	if ($datos['idVisualizarTotalPresu'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['idVisualizarTotalPresu'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_ColumnaMostrarTotalFranqueo;
	if ($datos['idVisualizarTotalFranqueo'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['idVisualizarTotalFranqueo'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_ColumnaImportelFranqueo;
	if ($datos['importeFranqueo'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['importeFranqueo'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_notaCibeles;
	if ($datos['notaCibeles'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['notaCibeles'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}

	$columna = presupuesto_trabajoIniciado;
	if ($datos['trabajoIniciado'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['trabajoIniciado'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}
	
	$columna = presupuesto_clayma;
	if ($datos['clayma'] != $cargarPresupuestos['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => $cargarPresupuestos['datos'][0][$columna],
			'datosNuevos' => $datos['clayma'],
			'columna' => $columna,
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		);
		insertarRegistro($conn,$bbddSql, $datos2);		
	}
*/

	$res =  modificarPresupuesto($conn,$bbddSql, $datos, $filtros,$filtrosOperadores);
	
	sqlsrv_close($conn);
	
	echo json_encode($res);

}


?>
