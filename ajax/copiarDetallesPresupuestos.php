<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="copiarDetalle")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$viejoPresupuesto = $_POST["viejoPresupuesto"];
	$nuevoPresupuesto = $_POST["nuevoPresupuesto"];	
	
	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];
	//se copia en el presupuesto detalle
	$copiaDetalles =  insertarDetallePresupuesto_Select($conn,$bbddSql, $viejoPresupuesto, $nuevoPresupuesto);
	echo json_encode($copiaDetalles);
	
	
	//cambiarDireccionadoPresupuesto($conexion,$nuevoPresupuesto);
	
	
	sqlsrv_close($conn);
	

	
	
		
		
	
}


?>