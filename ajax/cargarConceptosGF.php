<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarConcepto")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = [
		'id',
		'nombreConcepto'		
	];

	$filtros = [
			//'cliente' => 'EMPRESA SL'
		];

	$filtrosOperadores = [
			//'cliente' => 'EMPRESA SL'
		];

	$order = [			
			['campo' => 'nombreConcepto', 'dir' => 'ASC']
		];
	
	$Conceptos = cargarConceptosGF($conn,$bbddSql, $campos, $filtros,$filtrosOperadores, $order);
	
	sqlsrv_close($conn);
	
	
	echo json_encode($Conceptos);
		
}

?>
