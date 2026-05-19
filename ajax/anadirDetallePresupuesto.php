<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="anadirDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : array();

	$idMaterialPapel_tamano = $_POST["idMaterialPapel_tamano"];
	$idMaterialPapel_tipo =  $_POST["idMaterialPapel_tipo"];
	$idMaterialPapel_acabado =  $_POST["idMaterialPapel_acabado"];
	$idMaterialPapel_gramaje =  $_POST["idMaterialPapel_gramaje"];

	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = [
		'id',
		'precio'
	];

	$joins = array();

	$filtros = [
			'idTamanio' => $idMaterialPapel_tamano ,
			'idTipo' => $idMaterialPapel_tipo,	
			'idAcabado' => $idMaterialPapel_acabado,	
			'idGramaje' => $idMaterialPapel_gramaje
		];

	$filtrosOperadores = array();
	$order = array();

	$idPapel = verIdPapel($conn,$bbddSql, $campos, $joins, $filtros,$filtrosOperadores, $order);

	$numFilas = isset($idPapel['datos']) ? count($idPapel['datos']) : 0;
	
	
	$resultado = array();
	$error = "";

	if ($numFilas<=0)
	{
		$error = "No existe el material seleccionado";
	}
	/*else if ($idPapel[0]["precio"]<=0 &&)
	{
		$error = "El precio del material introducido es 0. Primero hay que cambiar el precio en las tarifas del Papel";
	}*/
	else 
	{
		
		$datos['idMaterialPapel'] = $idPapel['datos'][0]['id'];
		$insertarDetalle = insertarDetallePresupuesto($conn, $bbddSql, $datos);
	 	$idGenerado = $insertarDetalle['id'];
		
		$resultado = $insertarDetalle;
		//echo json_encode($insertarDetalle);
		//echo 'Aqui: '.$idGenerado;
		//LOG
		if ($insertarDetalle['ok']==true)
		{
			$campos = [
				'presupuesto',
				'departamento',
				'tipoProceso',
				'proceso',
				'descripcion',
				'notaAdmonProd',
				'notaCibeles',
				'orden',
				'unidades',
				'unidades2',
				'precio'
			];

			$joins = array();

			$filtros = [
					'id' => $idGenerado				
				];

			$filtrosOperadores = array();
			$order = [			
				['campo' => 'ordenTipoProceso', 'dir' => 'ASC'],
				['campo' => 'idTipo', 'dir' => 'ASC'],
				['campo' => 'orden', 'dir' => 'ASC'],
				['campo' => 'id', 'dir' => 'ASC']

			];

			$detallesPresupuesto = cargarDetallesPresupuesto($conn,$bbddSql, $campos, $joins, $filtros,$filtrosOperadores, $order);
		//echo json_encode($detallesPresupuesto);
			
		$numFilas = isset($detallesPresupuesto['datos']) ? count($detallesPresupuesto['datos']) : 0;
			
			$datosNuevos = '';	
			if ($numFilas>0)
			{
				$datosNuevos = "Presupuesto: ".$detallesPresupuesto['datos'][0]["presupuesto"]."|Departamento: ".$detallesPresupuesto['datos'][0]["departamento"]."|Tipo Proceso: ".$detallesPresupuesto['datos'][0]["tipoProceso"]."|Proceso: ".$detallesPresupuesto['datos'][0]["proceso"]."|Descripcion: ".$detallesPresupuesto['datos'][0]["descripcion"]."|NotaAdmonProd: ".$detallesPresupuesto['datos'][0]["notaAdmonProd"]."|Nota: ".$detallesPresupuesto['datos'][0]["notaCibeles"]."|Orden: ".$detallesPresupuesto['datos'][0]["orden"]."|Unidades: ".$detallesPresupuesto['datos'][0]["unidades"]."|Unidades2: ".$detallesPresupuesto['datos'][0]["unidades2"]."|Precio: ".$detallesPresupuesto['datos'][0]["precio"];
			}

			$datos = array(
				'usuario' => $_SESSION['usuario'],
				'descripcion' => log_creacion,
				'tabla' => presupuestoDetalle_tabla ,
				'datosAntiguos' => '',
				'datosNuevos' => $datosNuevos,
				'columna' => "todas",
				'idRegistro' => $insertarDetalle['id']
			);

			insertarRegistro($conn,$bbddSql, $datos);	
		}		

		sqlsrv_close($conn);	

		if ($error!="")
		{
			$resultado['error'] = $error;
			$resultado['ok'] = false;
		}
		
		echo json_encode($resultado);
	}	
}

?>