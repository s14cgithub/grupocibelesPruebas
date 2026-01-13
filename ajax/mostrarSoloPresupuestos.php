<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarPresupuesto")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$condicion=isset($_POST["condicion"])?" and presupuesto = '".$_POST["condicion"]."'":"";
	
	$condicion = " where fechaAceptacion != '' and fechaAceptacion is not null ".$condicion;
	
	 
	
	
	$resultado=mostrarSoloPresupuestos($conexion,$condicion);
	
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
