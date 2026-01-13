<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="borrarAcabadoPapel")
{	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$id = $_POST["id"];
	
	$condicion = " where t1.idPapelAcabado = ".$id;

	$datos = cargarRegistrosHoraInformatica($conexion,$condicion);
	if (count($datos)>0)
	{
		echo "No puede eliminar porque hay registros en produccion guardados con este Acabado";
	}
	else 
	{
		$condicion = " where t11.id = ".$id; 
		$datosPresu  = mostrarPresupuestosDetalles($conexion,$condicion);

		if (count($datosPresu)>0)
		{
			echo "No se puede eliminar porque hay registros en presupuestos guardados con este Acabado";
		}
		else
		{
			echo eliminarAcabadosPapel($conexion, $id);
			
		}			
	}
	
}


?>