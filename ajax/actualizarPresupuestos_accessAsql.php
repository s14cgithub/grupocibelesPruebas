<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="actualizarDatos")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	//echo traspasarPresupuestosAccessAsql($conexion);
	echo exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/traspasoPresupuesto_accessAsql.bat');
	
	
	/*if( pclose(popen("cmd /c "."C:\xampp\htdocs\gestionGrupocibelesPreproduccion\traspasoPresupuesto_accessAsql.bat", "r")) ) 
		echo true;
	else
		echo false;*/
		
}

?>
