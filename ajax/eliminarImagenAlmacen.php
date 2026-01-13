<?php 
	

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarFotoAlmacenPreEntrada")
{	

	$ot = "almacen-preEntrega";
	$ruta = "./../../imagenesAdjuntas/".$ot;
	$ruta2 = "./../imagenesAdjuntas/".$ot;
	$nombreImagen = $_POST["nombreImagen"];
	
	//$ruta3="C:/xampp/htdocs/gestionGrupocibeles/imagenesAdjuntas/almacen-preEntrega";
	$ruta3="../../imagenesAdjuntas/almacen-preEntrega";
	
	$rutaCompleta =  $ruta3."/".$nombreImagen;
	
	//unlink($rutaCompleta);
	if (file_exists($rutaCompleta)) 
	{	
		unlink($rutaCompleta);
	}
	else
	{
		echo "Error";	
	}
}	


?>
