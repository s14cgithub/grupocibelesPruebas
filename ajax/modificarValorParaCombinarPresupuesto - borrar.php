<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cambiarValorParaCombinarPresupuesto")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	$presupuesto = $_POST["numPresupuesto"];
	$valor = $_POST["valor"];	
	$usuario = $_SESSION["idEmpleado"];	
	
	
	
	
	
	echo modificarValorParaCombinarPresupuesto($conexion,$presupuesto,$valor, $usuario);
	
		
}

?>
