<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="eliminarContactoCliente")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	

	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
	$clayma = $_POST["clayma"];

	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	


	$campos = [
		'idSexo',
		'nombre',
		'apellidos',
		'departamento',
		'cargo',
		'telefono',
		'movil',
		'email',
		'comentario'		
	];
	$filtrosOperadores = array();
	$order = array();

	$datosClientesContactos = cargarClientesContactos ($conn, $bbddSql, $campos, $filtros, $filtrosOperadores, $order);


	$idSexo = $datosClientesContactos["datos"][0]["idSexo"];
	$nombre = $datosClientesContactos["datos"][0]["nombre"];
	$apellidos = $datosClientesContactos["datos"][0]["apellidos"];
	$departamento = $datosClientesContactos["datos"][0]["departamento"];
	$cargo = $datosClientesContactos["datos"][0]["cargo"];
	$telefono = $datosClientesContactos["datos"][0]["telefono"];
	$movil = $datosClientesContactos["datos"][0]["movil"];
	$email = $datosClientesContactos["datos"][0]["email"];
	$comentario = $datosClientesContactos["datos"][0]["comentario"];
	


	$datosAntiguos = "clayma: 0 |idSexo: ".$idSexo."|nombre: .".$nombre."|apellidos: ".$apellidos."|departamento: ".$departamento."|cargo: ".$cargo."|telefono: ".$telefono."|movil: ".$movil."|email: ".$email."|comentario: ".$comentario;	
	$datos = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_eliminacion,
			'tabla' => clientesContactos_tabla ,
			'datosAntiguos' => $datosAntiguos,
			'datosNuevos' => '',
			'columna' => "todas",
			'idRegistro' => $filtros["id"]
		);

	insertarRegistro($conn,$bbddSql, $datos);	


	if ($clayma=="true")
	{		
		$res = eliminarClientesContactosClayma($conn, $bbddSql, $filtros, $filtrosOperadores);
	}
	else
	{
		$res = eliminarClientesContactos($conn, $bbddSql, $filtros, $filtrosOperadores);	
	}

	sqlsrv_close($conn);	
	echo json_encode($res);
	
}


?>
