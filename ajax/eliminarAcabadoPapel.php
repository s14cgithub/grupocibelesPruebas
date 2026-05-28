<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="borrarAcabadoPapel")
{	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();


	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = array();

	$campos2 = ['id'];
	$joins2=array();
	$filtros2 = ['idPapelAcabado' => $filtros["id"]];
	$filtrosOperadores2=array();	
	$order2=array();

	$registrosHoras = cargarRegistrosHoraInformatica($conn, $bbddSql, $campos2, $joins2, $filtros2, $filtrosOperadores2, $order2);

	if (count($registrosHoras["datos"])>0)
	{
		$resultado["error"] = "No puede eliminar porque hay registros de Informatica guardados con este Tamaño";
	}
	else 
	{
		$campos3 = ['id'];
		$joins3= ['tabla5','tabla8'];
		$filtros3 = ['acabado_id' => $filtros["id"]];
		$filtrosOperadores3=array();	
		$order3=array();

		$detallePresupuesto = cargarDetallesPresupuesto($conn, $bbddSql, $campos3, $joins3, $filtros3, $filtrosOperadores3, $order3);
	
		if (count($detallePresupuesto["datos"])>0)
		{
			$resultado["error"] = "No puede eliminar porque hay registros de presupuestos guardados con este Tamaño";
		}
		else
		{
			$resultado =  eliminarAcabadosPapel($conn,$bbddSql, $filtros, $filtrosOperadores);
		}
	}

	sqlsrv_close($conn);
	echo json_encode($resultado);

}


?>