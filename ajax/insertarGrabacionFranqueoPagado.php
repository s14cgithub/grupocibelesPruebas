<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="grabarFranqueo")
{
	session_start(); 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();
	
	$anioSeleccionado =   date("Y", strtotime($datos["fecha"]));
	$anioActual = date('Y');
	if ($anioActual==$anioSeleccionado)
	{
		$idEmpleado = $_SESSION["idEmpleado"];

		if (strlen($idEmpleado)==1)
		{
			$idDelEmpleado = "0".$idEmpleado;
		}
		else if (strlen($idEmpleado)==2)
		{
			$idDelEmpleado =$idEmpleado;
		}


		$datos["idEmpleado"] = $idDelEmpleado;

		$conn1 = conectarSQL($conexion);
		$conn = $conn1['conn'];
		$bbddSql = $conn1['bbdd'];		

		$res = insertarFranqueoPagado($conn, $bbddSql, $datos);

		sqlsrv_close($conn);
		
	}
	else
	{
		$res["error"] = "Solo se puede grabar si la fecha esta dentro de este año";
	}

	echo json_encode($res);
	
}

?>
