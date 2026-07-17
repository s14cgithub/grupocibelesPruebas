<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarSaldo")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
	
	

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	if ($datos["formaPago"] == "")
	{
		$res["error"] = "La forma de pago esta vacia";
	}
	else
	{
		$campos2 = ['importePF'];
		$filtros2 = array(
			"codigo" => $datos["codigoCliente"]	
		);
		$filtrosOperadores2 = array();
		$order2 = array();
		$joins2 = array();
		$filtrosLike2 = array();

		$saldo = array();
		if ($datos["clayma"]==1)
		{
			$saldo = cargarClientesClayma($conn, $bbddSql, $campos2, $filtros2, $filtrosOperadores2, $order2, $joins2, $filtrosLike2);
		}
		else
		{
			$saldo = cargarClientes($conn, $bbddSql, $campos2, $filtros2, $filtrosOperadores2, $order2, $joins2, $filtrosLike2);
		}
		
		
		
		$nuevoSaldo = $saldo["datos"][0]["importePF"] + $datos["importe"];

		

		$datos3 = [
				'codigoCliente' => $datos["codigoCliente"],
				'fecha' => date("d-m-Y", strtotime($datos["fecha"])),
				'formaPago' => $datos["formaPago"],
				'importe' => $datos["importe"],
				'presupuesto' => $datos["presupuesto"],
				'fechaCuadre' => date("d-m-Y"),
				'informacionCuadre' => $datos["informacionCuadre"],
				'saldoPostPF' => $nuevoSaldo,
				'clayma' => $datos["clayma"]
			];

		insertarProvisionDeFondo_movimientos($conn, $bbddSql, $datos3);
		
		
		$datos4 = [
			'fechaCobroPF' => $datos["fecha"],
			'importePF' => $nuevoSaldo
		];

		$datosIncremento4 = array();		

		$filtros4 = [
			'codigo' => $datos["codigoCliente"]
		];

		$filtrosOperadores4 = array();

		$res = modificarClientes($conn, $bbddSql, $datos4, $filtros4, $filtrosOperadores4, $datosIncremento4);
		
	}
	
	
	
	sqlsrv_close($conn);	
	echo json_encode($res);				
	
}


?>
