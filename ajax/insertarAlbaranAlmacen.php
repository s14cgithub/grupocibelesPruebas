<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="grabarAlbaranAlmacen")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$idMovimientos = $_POST["idMovimientos"];
	$observaciones = $_POST["observaciones"];
	
	$empresa = $_POST["empresa"];
	$direccion = $_POST["direccion"];
	$cp = $_POST["cp"];
	$localidad = $_POST["localidad"];
	$provincia = $_POST["provincia"];	
	
	
	
	
	$resultado = mostrarUltimoAlbaranAlmacen($conexion);
	
	$primerAlbaran=false;
	
	if (count($resultado)<=0)
	{
		$primerAlbaran=true;
	}
	
	$fechaActual = date('d-m-Y');
	$diaActual =  date('d');
	$mesActual =  date('m');
	$anioActual =  date('y');
	$secuencialNum=0;
	$secuencial="0000";
	
	if ($primerAlbaran==false)	
	{		
		$fechaUltimoGuardado = $resultado[0]["fecha"]->format('d-m-Y');
		$secuencialUltimoGuardado = $resultado[0]["secuencial"];
		
		if ($fechaActual==$fechaUltimoGuardado)
		{			
			$secuencialNum = intval($secuencialUltimoGuardado);
			$secuencialNum++;
			$secuencial = strval($secuencialNum);
		}		
	}
	
	
	while (strlen($secuencial)<4)
	{
		$secuencial = "0".$secuencial;
	}
	
	$numeroAlbaran=$anioActual.$mesActual.$diaActual.$secuencial;
	
	$movimientos = explode("|||", $idMovimientos);
	
	
	
	
	$empresaEnvio="";
	$direccionEnvio="";
	$cpEnvio="";
	$localidadEnvio="";
	$provinciaEnvio="";
	
	
	if ($direccion!="" && $direccion!="null" && $direccion!=null)
	{
		$empresaEnvio = $empresa;
		$direccionEnvio = $direccion;
		$cpEnvio = $cp;
		$localidadEnvio = $localidad;
		$provinciaEnvio = $provincia;
	}
	else
	{
		$datosEnvio = verDatosDeEnvioPorIdMovimientoAlmacen($conexion, $movimientos[0]);
	
		if ($datosEnvio[0]["envio_domicilio"]!="" && $datosEnvio[0]["envio_domicilio"]!="null" && $datosEnvio[0]["envio_domicilio"]!=null)
		{
			$empresaEnvio=$datosEnvio[0]["envio_nombre"];
			$direccionEnvio=$datosEnvio[0]["envio_domicilio"];
			$cpEnvio=$datosEnvio[0]["envio_cp"];
			$localidadEnvio=$datosEnvio[0]["envio_poblacion"];
			$provinciaEnvio=$datosEnvio[0]["envio_provincia"];
		}
		else
		{
			$empresaEnvio=$datosEnvio[0]["nombre_empresa"];
			$direccionEnvio=$datosEnvio[0]["direccion"];
			$cpEnvio=$datosEnvio[0]["codigo_postal"];
			$localidadEnvio=$datosEnvio[0]["localidad"];
			$provinciaEnvio=$datosEnvio[0]["provincia"];
		}
	}
	
	echo insertarAlbaranAlmacen($conexion, $numeroAlbaran, $secuencial, $fechaActual, $observaciones, $empresaEnvio, $direccionEnvio, $cpEnvio, $localidadEnvio, $provinciaEnvio);
	
	
	
	
	$contador = 0;
	
	while ($contador<count($movimientos)-1)
	{		
		echo insertarAlbaranDetalle($conexion, $numeroAlbaran, $movimientos[$contador]);
		modificarValorAlbaranEnMovimientos($conexion, $movimientos[$contador]);
	
		$contador++;
	}
	
	echo "NumeroAlbaran:".$numeroAlbaran;
	
}


?>
