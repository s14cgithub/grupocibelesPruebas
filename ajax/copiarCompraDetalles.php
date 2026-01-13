<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="duplicarDetalleCompra")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$numeroPedidoNuevo = $_POST["numeroPedidoNuevo"];
	$numeroPedioCopiar = $_POST["numeroPedioCopiar"];

	
	
	
	$resultado = copiarDetallesCompras($conexion,$numeroPedidoNuevo,$numeroPedioCopiar);	
	
	
	echo ($resultado);
	

	
	
		
		
	
}


?>