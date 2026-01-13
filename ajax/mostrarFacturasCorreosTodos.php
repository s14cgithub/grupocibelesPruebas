<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarFacturaCorreos")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idCliente = $_POST["idCliente"];
	$fechaInicio = $_POST["fechaInicio"];
	$fechaFin = $_POST["fechaFin"];
	$orden = $_POST["orden"];
	$desc = $_POST["desc"];
	//$anioSeleccionado = $_POST["anioSeleccionado"];
	
	$condicion = "";
	
	if ($idCliente!="0" && $idCliente!="" )
	{
		$condicion = " where t2.codigo_Saldo=".$idCliente;
	}
	if ($fechaInicio!="")
	{
		if ($condicion=="")
		{
			$condicion = " where t1.fecha >= '".$fechaInicio."'";
		}
		else
		{
			$condicion = $condicion." and t1.fecha >= '".$fechaInicio."'";
		}
	}
	
	if ($fechaFin!="")
	{
		if ($condicion=="")
		{
			$condicion = " where t1.fecha <= '".$fechaFin."'";
		}
		else
		{
			$condicion = $condicion." and t1.fecha <= '".$fechaFin."'";
		}
	}
	
	//$condicion = $condicion." and t1.fecha>='01-01-".$anioSeleccionado."'  and  t1.fecha<='31-12-".$anioSeleccionado."'";
	
	
	
	
	$condicion = $condicion." order by ".$orden;
	
	if ($desc=="true")
	{
		$condicion = $condicion." desc";
	}
	
	
	
	$resultado = mostrarFacturasCorreosTodos($conexion,$condicion);
	
	
	
	if (count($resultado)<=0)
	{
		echo  json_encode("");
	}	
	else
	{
		echo  json_encode($resultado);
	}
}


?>