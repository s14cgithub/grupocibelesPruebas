<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarRegistro")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
			
	
	$id= $_POST["id"];
	$idCliente= $_POST["idCliente"];
	$Lruta= $_POST["Lruta"];
	$Lhora= $_POST["Lhora"];
	$Mruta= $_POST["Mruta"];
	$Mhora= $_POST["Mhora"];
	$Xruta= $_POST["Xruta"];
	$Xhora= $_POST["Xhora"];
	$Jruta= $_POST["Jruta"];
	$Jhora= $_POST["Jhora"];
	$Vruta= $_POST["Vruta"];
	$Vhora= $_POST["Vhora"];
	
	$contacto= $_POST["contacto"];
	$incidencia= $_POST["incidencia"];
	
	
	/*if ($Lruta=="")
	{
		$Lruta="null";
	}
	if ($Mruta=="")
	{
		$Mruta="null";
	}
	if ($Xruta=="")
	{
		$Xruta="null";
	}
	if ($Jruta=="")
	{
		$Jruta="null";
	}
	if ($Vruta=="")
	{
		$Vruta="null";
	}*/
	
	if ($Lhora=="")
	{
		$Lhora=null;
	}
	if ($Mhora=="")
	{
		$Mhora=null;
	}
	if ($Xhora=="")
	{
		$Xhora=null;
	}
	if ($Jhora=="")
	{
		$Jhora=null;
	}
	if ($Vhora=="")
	{
		$Vhora=null;
	}
	
	
	
	
	echo modificarRutasPlantilla($conexion,$id,$idCliente,$Lruta,$Lhora,$Mruta,$Mhora,$Xruta,$Xhora,$Jruta,$Jhora,$Vruta,$Vhora,$contacto,$incidencia);
	
}


?>
