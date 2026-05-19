<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="aumentarLetra")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = ['letra'];

	$joins = array();	
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):false;
	$filtrosOperadores = array();
	$order = array();

	$nuevaLetra="Error";

	if ($filtros!=false)
	{
		$resultado = cargarPresupuestos($conn,$bbddSql, $campos, $joins, $filtros,$filtrosOperadores, $order);


		$letra = $resultado["datos"][0]["letra"];

		if ($letra == "null" || $letra == null || $letra=="")
		{
			$nuevaLetra = "B";
		}
		else
		{		
			$nuevaLetra = $letra;
			$nuevaLetra++;			
		}

		$datos = ["letra" => $nuevaLetra];
			
		$res =  modificarPresupuesto($conn,$bbddSql, $datos, $filtros,$filtrosOperadores);
		
		$datos2 = array(
				'usuario' => $_SESSION['usuario'],
				'descripcion' => log_modificacion,
				'tabla' => presupuesto_tabla ,
				'datosAntiguos' => $letra,
				'datosNuevos' => $nuevaLetra,
				'columna' => presupuesto_Letra,
				'idRegistro' => 0,
				'presupuesto' => $filtros["presupuesto"]
		);
		insertarRegistro($conn,$bbddSql, $datos2);
	}
	

	sqlsrv_close($conn);
	
	echo ($nuevaLetra);
		
}


?>