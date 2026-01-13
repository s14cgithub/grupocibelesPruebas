<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarConcepto")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$idConcepto=$_POST["idConcepto"];
	
	$condicion= " where idConcepto=".$idConcepto;
	
	$tamanio=cargarSubConceptos1GF($conexion,$condicion);

	
	if (count($tamanio)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($tamanio);
	}
		
}

?>
