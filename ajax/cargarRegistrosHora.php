<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarRegistrosHoras")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$otBuscar=$_POST["ot"];
	$empleadoAbuscar=$_POST["empleado"];
	$fechaInicio=$_POST["fechaInicio"];
	$fechaFin=$_POST["fechaFin"];
	$orden=$_POST["orden"];
	$desc=$_POST["desc"];
	$meses = $_POST["meses"];
	$masde10horas = $_POST["masde10horas"];
	
	if ($desc=="false")
	{
		$desc = "asc";
	}
	else
		$desc = "desc";
	
	

	$fecha = "";
	
	if ($meses>0)
	{
		$meses = $meses-1;
		$fechaActual = date('d-m-Y');
		//$fechaInicio = date("01-m-Y",strtotime($fechaActual."- ".$meses." month")); ç
		$fechaInicio_1 = date("Y-m-01",strtotime($fechaActual."- ".$meses." month")); 

		if($fechaInicio!="" &&  $fechaInicio_1>$fechaInicio)
		{			
			$fechaInicio=$fechaInicio_1;
		}
		else if($fechaInicio=="")
		{
			$fechaInicio=$fechaInicio_1;
		}
		
		//$fecha = " fecha >='".$fechaInicio."'";
	}
	
	
	$registros = cargarRegistrosHora($conexion,$otBuscar,$empleadoAbuscar,$fechaInicio,$fechaFin,$orden,$desc,$masde10horas)	;
	
	if (count($registros)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($registros);
	}
		
}

?>
