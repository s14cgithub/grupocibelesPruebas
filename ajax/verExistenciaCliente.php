<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verExistenciaCliente")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$campo=$_POST["campo"];
	$valor=$_POST["valor"];
	
	$clayma = $_POST["clayma"];
	
	if ($clayma=="true")
	{
		$resultado=verExistenciaClienteClayma($conexion,$campo,$valor);
	}
	else
	{
		$resultado=verExistenciaCliente($conexion,$campo,$valor);
	}
	
	
	
	
	
	
	if (count($resultado)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($resultado);
	}
		
}

?>
