<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarTipoImpresoras")
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
		'tipoImpresora',
		'precioClick'	
	];

	$filtros = [
			//'cliente' => 'EMPRESA SL'
		];

	$filtrosOperadores = [
			//'cliente' => 'EMPRESA SL'
		];

	$order = [			
			['campo' => 'tipo', 'dir' => 'ASC']
		];
	
	$tipoImpresora = mostrarTarifasTipoImpresora($conn,$bbddSql, $campos, $filtros,$filtrosOperadores, $order);
	
	sqlsrv_close($conn);	
	
	echo json_encode($tipoImpresora);
	
	

}

?>
