<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarRegistro")
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
	

	$res =  modificarPresupuesto($conn,$bbddSql, $datos, $filtros,$filtrosOperadores);
	
	sqlsrv_close($conn);
	
	echo json_encode($res);

}


?>
