<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="borrarTipoPapel")
{	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$id = $_POST["id"];
	
	$condicion = " where t1.idPapelTipo = ".$id;

	$datos = cargarRegistrosHoraInformatica($conexion,$condicion);
	if (count($datos)>0)
	{
		echo "No puede eliminar porque hay registros en produccion guardados con este Tipo";
	}
	else 
	{
		$condicion = " where t10.id = ".$id; 
		$datosPresu  = mostrarPresupuestosDetalles($conexion,$condicion);

		if (count($datosPresu)>0)
		{
			echo "No se puede eliminar porque hay registros en presupuestos guardados con este Tipo";
		}
		else
		{
			echo eliminarTiposPapel($conexion, $id);
			
		}			
	}
	
}


?>