<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarMultiUsuario")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$idMultiEmpleado = $_POST["idMultiEmpleado"];
	$idUsuario = $_SESSION["idEmpleado"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	//comprobar si el empleado a añadir tiene un proceso abierto
	$campos = ['todos','cliente','descripcion'];
	$joins = ['tabla_presupuestos','tabla_presupuestosDetalle','tabla_procesos','tabla_presupuestadores','tabla_clientesUnion','tabla_comerciales'];
	$filtrosRegistro = ['idEmpleado' => $idMultiEmpleado];
	$filtrosOperadoresRegistro = [['campo1' => 'estado', 'valor' => estadoCerrado, 'operador' => '!=']];
	$resRegistro = cargarRegistrosHoras($conn, $bbddSql, $campos, $joins, $filtrosRegistro, $filtrosOperadoresRegistro, array());
	$registroTrabajo = $resRegistro['datos'];

	$res = array('error' => '');
	$iniciarTrabajo=false;			
		
	if ($idUsuario == $idMultiEmpleado)
	{
		$res['error'] = "Error1: No te puedes añadir a tí mismo. Contigo ya se cuenta.";
	}
	else if (count($registroTrabajo)<=0)
	{   
		$iniciarTrabajo=true;		
	}
	else if ($registroTrabajo[0]["estado"]==estadoCerrado)
	{
		$iniciarTrabajo=true;
	}
	else
	{
		$res['error'] = "Error2: No se puede añadir a ".$registroTrabajo[0]["nombreEmpleado"]." porque tiene abierto el proceso: <br>".$registroTrabajo[0]["codigoBarras"]. "<br>Cliente: ".$registroTrabajo[0]["cliente"]. "<br>Descripcion: ".$registroTrabajo[0]["descripcion"];
	}
	
	
	if ($iniciarTrabajo == true)
	{	
		$resInsert = insertarRegistroHoras_multiusuario($conn, $bbddSql, ['idUsuario' => $idUsuario, 'idEmpleado' => $idMultiEmpleado]);
		if ($resInsert['error'] != '')
		{
			$res['error'] = "Error: no se ha podido añadir el Empleado.\n".$resInsert['error'];
		}
	}

	echo json_encode($res);
	
	
}


?>