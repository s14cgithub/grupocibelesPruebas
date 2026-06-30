<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarFranqueo")
{
	session_start(); 
	
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$campos=isset($_POST["campos"])?json_decode($_POST["campos"], true):array();
	$joins = isset($_POST["joins"])?json_decode($_POST["joins"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();

	$filtrosOperadores=isset($_POST["filtrosOperadores"])?$_POST["filtrosOperadores"]:array();
	$order=isset($_POST["order"])?json_decode($_POST["order"], true):array();

	$origen = isset($_POST["origen"])?$_POST["origen"]:'';

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	
	

	if ($origen == "franqueoGrabacion")
	{
		$permisoF12 = $_SESSION["permiso_franqueoF12"];

		if ($permisoF12 == 1 || $permisoF12 ==2)
		{
			$filtros2 = $filtros;
			$filtros = array();
			$filtros = [
				'producto' => $filtros2["producto"],
				'fecha' => $filtros2["fecha"]
			];
			
			$order = array();
			$order = [
				['campo' => 'fecha', 'dir' => 'DESC'],
				['campo' => 'id', 'dir' => 'DESC']
			];
		}
		else
		{
			$idEmpleado = $_SESSION["idEmpleado"];

			$idDelEmpleado = "";
			if (strlen($idEmpleado)==1)
			{
				$idDelEmpleado = "0".$idEmpleado;
			}
			else if (strlen($idEmpleado)==2)
			{
				$idDelEmpleado =$idEmpleado;
			}
			else
			{				
				$res["Error"] = "Error: el id del empleado debe tener un maximo de 2 digitos";//en este caso habria que quitar un digito al secuencial y dárselo al idEmpleado
				sqlsrv_close($conn);
				echo json_encode($res);
				exit;
			}

			$filtros2 = $filtros;
			$filtros = array();
			$filtros = [
				'producto' => $filtros2["producto"],
				'fecha' => $filtros2["fecha"],
				'idEmpleado_Por_referencia' => $idDelEmpleado
			];

			$filtrosOperadores = array();
			$filtrosOperadores = array(
				array(
					'campo1' => 'comprobado',
					'operador' => '!=',
					'campo2' => 1
				)
			);
			
			$order = array();
			$order = [
				['campo' => 'id', 'dir' => 'DESC']				
			];



		}
	}

	$res = cargarFranqueo($conn,$bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order);
	
	sqlsrv_close($conn);
	
	echo json_encode($res);
	

}

?>
