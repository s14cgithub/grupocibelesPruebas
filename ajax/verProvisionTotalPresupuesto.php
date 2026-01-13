<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verProvisionTotalPresupuesto")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$presupuesto = isset($_POST["presupuesto"])?$_POST["presupuesto"]:"";	
	
	
	
	$resultado = verProvisionDeFondoPorPresupuestoTodo($conexion,$presupuesto);
	
	
	if (count($resultado)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($resultado);
	}
		
}

?>
