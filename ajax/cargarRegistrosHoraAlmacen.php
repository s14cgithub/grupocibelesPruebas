<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarRegistrosHoras")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$otBuscar=$_POST["ot"];

	$empleadoAbuscar = $_SESSION["idEmpleado"];
	
	$fechaInicio=$_POST["fechaInicio"];
	$fechaFin=$_POST["fechaFin"];
	$orden=$_POST["orden"];
	$desc=$_POST["desc"];
	//$meses = $_POST["meses"];
	$meses = 7; //dias
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
				
		//$fechaInicio_1 = date("Y-m-01",strtotime($fechaActual."- ".$meses." month")); 
		$fechaInicio_1 = date("Y-m-d",strtotime($fechaActual."- ".$meses." days")); 

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


	$condicion="";
	/////////////////////////////////////////////////////
	if ($otBuscar != "" )
	{
		$condicion = "where t1.".registroHora_columnaCodigoBarras." like '%-".$otBuscar."'";
	}
	
	if ($empleadoAbuscar!="" and $condicion=="")
	{
		$condicion = "where t1.".registroHora_columnaIdEmpleado. " = ".$empleadoAbuscar;
	}
	else if ($empleadoAbuscar!="")
	{
		$condicion = $condicion." and t1.".registroHora_columnaIdEmpleado. " = '".$empleadoAbuscar."'";
	}
	
	$fechaInicio1;
	if ($fechaInicio!="")//la fecha viene con formato 'yyyy-mm-dd' y hay que convertirlo en 'dd-mm-yyy'
	{
		$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	}
	
	$fechaFin1;
	if ($fechaFin!="")//la fecha viene con formato 'yyyy-mm-dd' y hay que convertirlo en 'dd-mm-yyy'
	{
		$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
	}
	
	if ($fechaInicio!="" and $fechaFin!="" and $condicion=="") 
	{
		$condicion = "where t1.".registroHora_columnaHoraInicio. " >= '".$fechaInicio1."' and t1.".registroHora_columnaHoraInicio. " < '".date("d-m-Y",strtotime($fechaFin1."+ 1 days"))."'";
	}
	else if ($fechaInicio!="" and $fechaFin!="")
	{
		$condicion = $condicion." and t1.".registroHora_columnaHoraInicio. " >= '".$fechaInicio1."' and t1.".registroHora_columnaHoraInicio. " < '".date("d-m-Y",strtotime($fechaFin1."+ 1 days"))."'";
	}
	else if ($fechaInicio!="" and $condicion=="") 
	{		
		$condicion = "where t1.".registroHora_columnaHoraInicio. " >= '".$fechaInicio1."'";
	}
	else if ($fechaInicio!="" )
	{
		$condicion  = $condicion." and t1.".registroHora_columnaHoraInicio. " >= '".$fechaInicio1."'";
	}

	

	/*if ($meses>0)
	{
		$meses = $meses-1;
		$fechaActual = date('d-m-Y');
		$fechaInicio = date("01-m-Y",strtotime($fechaActual."- ".$meses." month")); 
		$fecha = " fecha >='".$fechaInicio."'";
	}*/


	$condicion = $condicion. " order by ".$orden. " ".$desc;


	/////////////////////////////////////////////////////
	



	
	

	
	$registros = cargarRegistrosHoraInformatica($conexion,$condicion);
	
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
