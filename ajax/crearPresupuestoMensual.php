<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="proximoNumero")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : array();
	$numMensual = $datos['presupuesto'];	
	$anioActual = date("y");

	$anio = substr($numMensual,0,2);
	$mes = substr($numMensual,2,2);
	$secuencial = substr($numMensual,4);

	//rocio: 000 al 100 - manualmente
	//comerciales: 101 al infinito - automatico	
	$resultado = array();
	$error = "";
	//$resultado['error1'] = "entra";
	if (intval($secuencial)>=0 && intval($secuencial)<=100)
	{//$resultado['error1'] = "entra1";
		if (intval($mes)>0 && intval($mes<=12))
		{//$resultado['error1'] = "entra2";
			if (intval($anio)>=$anioActual)
			{
				//$resultado['error1'] = "entra3";
				
				$conn1 = conectarSQL($conexion);
				$conn = $conn1['conn'];
				$bbddSql = $conn1['bbdd'];

				$campos = [
					'presupuesto'					
					];
				$joins = [];

				$filtros = [
					'presupuesto' => $numMensual
				];
				
				$filtrosOperadores = [];
				$order = [];

				$cargarPresupuestos = cargarPresupuestos($conn,$bbddSql, $campos, $joins, $filtros,$filtrosOperadores, $order);
				
				$numFilas = isset($cargarPresupuestos['datos']) ? count($cargarPresupuestos['datos']) : 0;
				//$resultado['error1'] = $numFilas;
				if ($numFilas>0)
				{//$resultado['error1'] = "entra4";

					$error = "El presupuesto ". $numMensual." ya existe";
	
				}
				else
				{//$resultado['error1'] = "entra5";
					$anio = "20".$anio;					
	
					$fechaInicio= "01-".$mes."-".$anio;					
					$fechaFin =  date("t-m-Y", strtotime($fechaInicio));

					$datos['fechaAceptacion'] = $fechaInicio;
					$datos['fechaCompromiso'] = $fechaFin;

					$insertarPresupuesto =  insertarPresupuesto($conn,$bbddSql, $datos);

					$insertarPresupuesto['presupuestoNuevo'] = $numMensual;

					//echo json_encode($insertarPresupuesto);
					$resultado = $insertarPresupuesto;
					//LOG
					$datos = array(
						'usuario' => $_SESSION['usuario'],
						'descripcion' => log_creacion,
						'tabla' => presupuesto_tabla ,
						'datosAntiguos' => '',
						'datosNuevos' => $numMensual,
						'columna' => presupuesto_Presupuesto,
						'idRegistro' => 0 
					);
					insertarRegistro($conn,$bbddSql, $datos);	

				}
				sqlsrv_close($conn);
			}
			else
			{
				$error = "El año debe ser igual o superior al año actual";				
			}
		}
		else
		{
			$error = "Mes fuera de rango";				
		}
	}
	else
	{
		$error= "Secuencial fuera de rango";			
	}

	if ($error!="")
	{
		$resultado['error'] = $error;
		$resultado['ok'] = false;
	}
	
	echo json_encode($resultado);
	

	
	
}

?>