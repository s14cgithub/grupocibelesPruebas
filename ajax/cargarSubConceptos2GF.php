<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarConcepto")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$idSubConcepto=$_POST["idSubConcepto1"];
	
	$condicion= " where idSubconcepto1=".$idSubConcepto;
	$tamanio=cargarSubConceptos2GF($conexion,$condicion);
	
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
