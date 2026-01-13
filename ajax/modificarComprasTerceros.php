<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarComprarTerceros")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$id = $_POST["id"];
	
	$nombreProveedor = $_POST["nombreProveedor"];	
	$contactoP = $_POST["contactoP"];
	$contactoC = $_POST["contactoC"];
	$comercial = $_POST["comercial"];
	$presupuesto = $_POST["presupuesto"];
	$fechaEntrega = $_POST["fechaEntrega"];
	$formaPago = $_POST["formaPago"];
	$numFacCompra = $_POST["numFacCompra"];
	$fechaFacCompra = $_POST["fechaFacCompra"];
	$observacionInterna = $_POST["observacionInterna"];
		
	
	
	if ($numFacCompra=="")
	{
		$numFacCompra=NULL;
	}
	
	
	echo modificarComprasTerceros($conexion,$id,$nombreProveedor,$contactoP,$contactoC,$comercial,$presupuesto,$fechaEntrega,$formaPago,$numFacCompra,$fechaFacCompra,$observacionInterna);	
			
	
}


?>
