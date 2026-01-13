<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarRegistroHoraManual")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	$idEmpleado=$_POST["idEmpleado"];
	
	$proceso=$_POST["proceso"];
	$fechaInicio=$_POST["fechaInicio"];
	$fechaFin=$_POST["fechaFin"];
	$cantidad=$_POST["cantidad"];
	$observaciones=$_POST["observaciones"];
	
	$modo = modoManual."-";
	
	
	
	
	
	//$fechaActual = date('d/m/Y H:i:s');
	
	
	$datosFecha = explode("T",$fechaInicio);
	$datosFecha2 = explode("-",$datosFecha[0]);
	$anio = $datosFecha2[0];
	$mes = $datosFecha2[1];
	$dia = $datosFecha2[2];
	
	$hora = $datosFecha[1];
	
	$fechaFormato = $dia."/".$mes."/".$anio." ".$hora.":00";
	
	if ($fechaFin=="")
	{
		echo $registros = insertarRegistroTrabajoInicio($conexion,$proceso,$idEmpleado, $fechaFormato,$modo,$cantidad,$observaciones);
	}
	else
	{
		$datosFechaFin = explode("T",$fechaFin);
		$datosFechaFin2 = explode("-",$datosFechaFin[0]);
		$anioFin = $datosFechaFin2[0];
		$mesFin = $datosFechaFin2[1];
		$diaFin = $datosFechaFin2[2];

		$horaFin = $datosFechaFin[1];

		$fechaFormatoFin = $dia."/".$mesFin."/".$anioFin." ".$horaFin.":00";
		
		$estado = estadoCerrado;
		echo $registros = insertarRegistroTrabajoCompleto($conexion,$proceso,$idEmpleado, $fechaFormato, $fechaFormatoFin,$modo,$estado,$cantidad,$observaciones);
	}
	
	
		
	
	$registroTrabajo2=verUltimoRegistroTrabajo($conexion, $idEmpleado);
	$idUltimoTrabajo2 = $registroTrabajo2[0]["id"];
					
	insertarUsuarioRegistroTrabajo($conexion,$idUltimoTrabajo2);
		
	
		
}

?>
