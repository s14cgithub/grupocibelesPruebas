<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarTarifasPapel")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$idMaterialPapel_tamano = $_POST["tamanio"];
	$idMaterialPapel_tipo = $_POST["tipo"];
	$idMaterialPapel_acabado = $_POST["acabado"];	
	$idMaterialPapel_gramaje = $_POST["gramaje"];
	$precio = $_POST["precio"];
	

	$idPapel = verIdPapel($conexion, $idMaterialPapel_tamano, $idMaterialPapel_tipo, $idMaterialPapel_acabado, $idMaterialPapel_gramaje);
	

	if (count($idPapel)>0)
	{
		echo ("Error: ya existe ese material");
	}
	else
	{
		echo insertarTarifaPapel($conexion, $idMaterialPapel_tamano, $idMaterialPapel_tipo, $idMaterialPapel_acabado, $idMaterialPapel_gramaje,$precio);
	}
			
	
}


?>
