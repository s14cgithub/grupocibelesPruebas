<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarListFacPendAnt")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$clayma = $_POST["clayma"];
	$correos = $_POST["correos"];
	$anio = $_POST["anio"];	
	
	$resultado=null;
	
	if ($clayma=="false" && $correos=="false" && $anio=="2021")
	{
		//exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/importarFacturas2021Cibeles.bat');
		$resultado = mostrarListadoFacturasPendienteAnterioresCibeles($conexion, $anio);
	}
	else if ($clayma=="false" && $correos=="true" && $anio=="2021")
	{		
		//exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/importarFacturas2021Correos.bat');
		$resultado = mostrarListadoFacturasPendientesAnterioresCibelesCD($conexion, $anio);
	}
	else if ($clayma=="true" && $correos=="true")
	{		
		//exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/importarFacturas2021Correos.bat');
		$resultado = "";
	}
	else if ($clayma=="true" && $anio=="2021")
	{		
		//exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/importarFacturas2021Clayma.bat');		
		$resultado = mostrarListadoFacturasPendientesAnterioresClayma($conexion, $anio);
	}
	else if ($clayma=="false" && $correos=="false" && $anio=="2020")
	{
		//exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/importarFacturas2020Cibeles.bat');
		$resultado = mostrarListadoFacturasPendienteAnterioresCibeles($conexion, $anio);
	}
	else if ($clayma=="false" && $correos=="true" && $anio=="2020")
	{		
		//exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/importarFacturas2020Correos.bat');
		$resultado = mostrarListadoFacturasPendientesAnterioresCibelesCD($conexion, $anio);
	}
	else if ($clayma=="true" && $anio=="2020")
	{		
		//exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/importarFacturas2020Clayma.bat');		
		$resultado = mostrarListadoFacturasPendientesAnterioresClayma($conexion, $anio);
	}
	else if ($clayma=="false" && $correos=="false" && $anio=="2019")
	{
		//exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/importarFacturas2019Cibeles.bat');
		$resultado = mostrarListadoFacturasPendienteAnterioresCibeles($conexion, $anio);
	}
	else if ($clayma=="false" && $correos=="true" && $anio=="2019")
	{		
		//exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/importarFacturas2019Correos.bat');
		$resultado = mostrarListadoFacturasPendientesAnterioresCibelesCD($conexion, $anio);
	}
	else if ($clayma=="true" && $anio=="2019")
	{		
		//exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/importarFacturas2019Clayma.bat');		
		$resultado = mostrarListadoFacturasPendientesAnterioresClayma($conexion, $anio);
	}
	else if ($clayma=="false" && $correos=="false" && $anio=="2018")
	{
		//exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/importarFacturas2018Cibeles.bat');
		$resultado = mostrarListadoFacturasPendienteAnterioresCibeles($conexion, $anio);
	}
	else if ($clayma=="false" && $correos=="true" && $anio=="2018")
	{		
		//exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/importarFacturas2018Correos.bat');
		$resultado = mostrarListadoFacturasPendientesAnterioresCibelesCD($conexion, $anio);
	}
	else if ($clayma=="true" && $anio=="2018")
	{		
		//exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/importarFacturas2018Clayma.bat');		
		$resultado = mostrarListadoFacturasPendientesAnterioresClayma($conexion, $anio);
	}
	
	
	
	
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