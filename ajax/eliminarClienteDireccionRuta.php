<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarRutaDireccionCliente")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");



	$id = $_POST["id"];
	$att = $_POST["att"];
	$nombre = $_POST["nombre"];
	$direccion = $_POST["direccion"];
	$cp = $_POST["cp"];
	$poblacion = $_POST["poblacion"];
	$provincia = $_POST["provincia"];
	$pais = $_POST["pais"];
	$idCliente = $_POST["idCliente"];


	$clayma=$_POST["clayma"];	
	
	$clayma1=0;
	if ($clayma=="true")
	{
		$clayma1=1;		
	}
	


	
	$usuario = $_SESSION['usuario'];
	$tabla = clientesDireccionesRuta_tabla;
	$descripcion = "eliminacion";
	$columna = "todas";
	$datosAntiguos = "clayma: ".$clayma."|id: ".$id."|idCliente: .".$idCliente."|att: ".$att."|nombre: ".$nombre."|direccion: ".$direccion."|cp: ".$cp."|poblacion: ".$poblacion."|provincia: ".$provincia."|pais: ".$pais;	
	$datosNuevos="";
	
	
	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $id,'',$clayma1);		
		
	if ($clayma=="true")
	{		
		echo eliminarClienteDireccionRutaClayma($conexion,$id);	
	}
	else
	{
		echo eliminarClienteDireccionRuta($conexion,$id);	
	}
	
	
	
	
	
	
	
	
			
	
}


?>
