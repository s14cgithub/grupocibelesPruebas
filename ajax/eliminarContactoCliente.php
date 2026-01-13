<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarContactoCliente")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
			
	$id = $_POST["id"];
	$sexo = $_POST["sexo"];
	$nombre = $_POST["nombre"];
	$apellidos = $_POST["apellidos"];
	$departamento = $_POST["departamento"];
	$cargo = $_POST["cargo"];
	$telefono = $_POST["telefono"];
	$movil = $_POST["movil"];
	$email = $_POST["email"];
	$comentario = $_POST["comentario"];
	$clayma=$_POST["clayma"];	
	$idCliente=$_POST["idCliente"];	


	
	
	$clayma1=0;
	if ($clayma=="true")
	{
		$clayma1=1;		
	}
	


	
	$usuario = $_SESSION['usuario'];
	$tabla = clientesContactos_tabla;
	$descripcion = "eliminacion";
	$columna = "todas";
	$datosAntiguos = "clayma: ".$clayma."|id: ".$id."|idCliente: ".$idCliente."|sexo: .".$sexo."|nombre: ".$nombre."|apellidos: ".$apellidos."|departamento: ".$departamento."|cargo: ".$cargo."|telefono: ".$telefono."|movil: ".$movil."|email: ".$email."|comentario: ".$comentario;	
	$datosNuevos="";
	
	
	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $id,'',$clayma1);		
		
	if ($clayma=="true")
	{		
		echo eliminarClienteContactoClayma($conexion,$id);	
	}
	else
	{
		echo eliminarClienteContacto($conexion,$id);	
	}
	
	
	
	
	
	
	
	
			
	
}


?>
