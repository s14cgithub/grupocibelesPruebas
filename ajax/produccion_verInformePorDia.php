<?php 




if(isset($_POST["accion"])&$_POST["accion"]=="verInformePorDia")
{
	$ruta = '../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$fechaAbuscar = $_POST["fecha"];
	$fechaAbuscarFin = $_POST["fechaFin"];
	
	
	$newDate = date("d-m-Y", strtotime($fechaAbuscar));
	$newDateFin = date("d-m-Y", strtotime($fechaAbuscarFin));
	//die ("Error: ".$newDate);
	
	
	//$CantidadDiasHabiles = Evalua(DiasHabiles('19-10-2010','28-12-2010'));
	//$CantidadDiasHabiles = Evalua(DiasHabiles($newDate,$newDateFin));
	$CantidadDiasHabiles = Evalua();
	
	//die ("Error: ".$CantidadDiasHabiles);
	
	$registros = cargarHorasTotalEmpleados($conexion,$fechaAbuscar,$fechaAbuscarFin,$CantidadDiasHabiles);
		
	//echo $registros[0]["nombreEmpleado"];
	if (count($registros)<=0)
	{
		echo json_encode("");
	}
	else
	{

		echo json_encode($registros);
	}
}


function DiasHabiles($fecha_inicial,$fecha_final)
{
	$newArray = array();
	list($dia,$mes,$year) = explode("-",$fecha_inicial);
	$ini = mktime(0, 0, 0, $mes , $dia, $year);
	list($diaf,$mesf,$yearf) = explode("-",$fecha_final);
	$fin = mktime(0, 0, 0, $mesf , $diaf, $yearf);

	$r = 1;
	while($ini != $fin)
	{
	$ini = mktime(0, 0, 0, $mes , $dia+$r, $year);
	$newArray[] .=$ini; 
	$r++;
	}
	return $newArray;
}

//function Evalua($arreglo)
function Evalua()
{
	$fechaAbuscar = $_POST["fecha"];
	$fechaAbuscarFin = $_POST["fechaFin"];
	
	
	$newDate = date("d-m-Y", strtotime($fechaAbuscar));
	$newDateFin = date("d-m-Y", strtotime($fechaAbuscarFin));
	
	$arreglo=[];
	$arreglo = DiasHabiles($newDate,$newDateFin);
	//$arreglo[] = $newArray[];
	$feriados        = array(
	'1-1',  //  Año Nuevo (irrenunciable)
	'6-1',  //  Viernes Santo (feriado religioso)
	'19-3',  //  Sábado Santo (feriado religioso)
	'1-4',  //  
	'2-4',  // 
	'1-5',  //  
	'3-5',  // 
	'12-10',  //
	'1-11',  // 
	'6-12',  // 
	'8-12',  //  
	'25-12',  //  Natividad del Señor (feriado religioso) (irrenunciable)
	);

	$j= count($arreglo);
	//$j= count($newArray);
	$dia_=0;
	$dia="";
	$contador=0;
	//for($i=0;$i<=$j;$i++)
	while ($contador<$j)
	{
		$dia = $arreglo[$contador];

		$fecha = getdate($dia);
		$feriado = $fecha['mday']."-".$fecha['mon'];
		if($fecha["wday"]==0 or $fecha["wday"]==6)
		{
			$dia_ ++;
		}
		elseif(in_array($feriado,$feriados))
		{   
			$dia_++;
		}
		$contador++;
	}
	$rlt = $j - $dia_+1;
	return $rlt;

	
}



?>