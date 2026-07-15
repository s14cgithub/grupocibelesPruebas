<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="eliminarRutaDireccionCliente")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");


	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();

	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	


	$campos = [
		'id',
		'att',
		'nombre',
		'direccion',
		'cp',
		'poblacion',
		'provincia',
		'pais',
		'activo',
		'idCliente'
	];
	$filtrosOperadores = array();
	$order = array();

	$datosRutaDireccionCliente = cargarClientesDirecRutasClayma ($conn, $bbddSql, $campos, $filtros, $filtrosOperadores, $order);


	$id = $datosRutaDireccionCliente["datos"][0]["id"];
	$att = $datosRutaDireccionCliente["datos"][0]["att"];
	$nombre = $datosRutaDireccionCliente["datos"][0]["nombre"];
	$direccion = $datosRutaDireccionCliente["datos"][0]["direccion"];
	$cp = $datosRutaDireccionCliente["datos"][0]["cp"];
	$poblacion = $datosRutaDireccionCliente["datos"][0]["poblacion"];
	$provincia = $datosRutaDireccionCliente["datos"][0]["provincia"];
	$pais = $datosRutaDireccionCliente["datos"][0]["pais"];
	$idCliente = $datosRutaDireccionCliente["datos"][0]["idCliente"];
	


	$datosAntiguos = "clayma: 1 |id: ".$id."|idCliente: .".$idCliente."|att: ".$att."|nombre: ".$nombre."|direccion: ".$direccion."|cp: ".$cp."|poblacion: ".$poblacion."|provincia: ".$provincia."|pais: ".$pais;	
	$datos = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_eliminacion,
			'tabla' => clientesDireccionesRuta_tabla ,
			'datosAntiguos' => $datosAntiguos,
			'datosNuevos' => '',
			'columna' => "todas",
			'idRegistro' => $filtros["id"]
		);

	insertarRegistro($conn,$bbddSql, $datos);	



	$res = eliminarClientesDirecRutasClayma($conn, $bbddSql, $filtros, $filtrosOperadores);

	sqlsrv_close($conn);	
	echo json_encode($res);
	
	
}


?>
