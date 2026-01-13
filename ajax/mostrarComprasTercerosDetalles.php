<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarDetalles")
{
	
		session_start(); 
		$ruta = '../';	
		require($ruta."Archivos Comunes/constantes.php");
		require($ruta."Archivos Comunes/codigoInclude.php");

		$idPedido = $_POST["idPedido"];
		

		
		$resultado =  mostrarComprarTerceroDetalles ($conexion,$idPedido);

	
	
	
		if (count($resultado)<=0)
		{		
			//siempre debe de haber un registro
			//echo ("");
		}	
		else
		{
			echo  json_encode($resultado);
		}
	
	
	
}

?>