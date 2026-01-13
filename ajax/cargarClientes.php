<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarClientes")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$condicion=isset($_POST["condicion"])?$_POST["condicion"]:"";
	
	
	if ($condicion=="A")
	{		
		$condicion = " where activo = 1 and codigo_saldo = codigo order by nombre_empresa";
	}
	else if ($condicion=="B")
	{		
		$condicion = " where codigo_saldo = codigo  order by nombre_empresa ";
	}
	//echo "<br>".$condicion."<br>";
	
	
	$clientes=cargarClientes($conexion,$condicion);
	
	
	if (count($clientes)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($clientes);
	}
		
}

?>
