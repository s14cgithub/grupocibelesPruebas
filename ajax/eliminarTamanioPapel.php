<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="borrarTamanioPapel")
{	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$id = $_POST["id"];
	
	$condicion = "where t1.idPapelTamano = ".$id;

	$datos = cargarRegistrosHoraInformatica($conexion,$condicion);
	if (count($datos)>0)
	{
		echo "No puede eliminar porque hay registros de Informatica guardados con este Tamaño";
	}
	else 
	{
		$condicion = "where t9.id = ".$id; 
		$datosPresu  = mostrarPresupuestosDetalles($conexion,$condicion);

		if (count($datosPresu)>0)
		{
			echo "No puede eliminar porque hay registros de presupuestos guardados con este Tamaño";
		}
		else
		{
			echo eliminarTamaniosPapel($conexion, $id);
		}			
	}
	
}


?>