<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarRegistroHoraManual")
{
	$ruta = '../';
	session_start(); 
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");


	$idEmpleado = $_SESSION["idEmpleado"];
	
	$proceso=$_POST["proceso"];
	$fechaInicio=$_POST["fechaInicio"];
	$fechaFin=$_POST["fechaFin"];
	$cantidad=$_POST["cantidad"];
	$observaciones=$_POST["observaciones"];


	$idSubConcepto2=$_POST["idSubConcepto2"];	

	$impresoras="NULL";
	$tamanio="NULL";
	$tipo="NULL";
	$acabado="NULL";
	$gramaje="NULL";
	$origen="NULL";

	



	$sinProceso_idProceso=$_POST["sinProceso_idProceso"];
	$sinProceso_idCliente=$_POST["sinProceso_idCliente"];
	//$sinProceso_observaciones=$_POST["sinProceso_observaciones"];


	
	
	if ($proceso=="")
	{
		$proceso="0-9999999";
	}
	else
	{
		$sinProceso_idProceso="NULL";
		$sinProceso_idCliente="NULL";
		//$sinProceso_observaciones="";
	}
	
	
	//$fechaActual = date('d/m/Y H:i:s');
	
	
	$datosFechaInicio = explode("T",$fechaInicio);
	$datosFecha2Inicio = explode("-",$datosFechaInicio[0]);
	$anioInicio = $datosFecha2Inicio[0];
	$mesInicio = $datosFecha2Inicio[1];
	$diaInicio = $datosFecha2Inicio[2];
	
	$horaInicio = $datosFechaInicio[1];
	
	$fechaFormatoInicio = $diaInicio."/".$mesInicio."/".$anioInicio." ".$horaInicio.":00";


	$datosFechaFin = explode("T",$fechaFin);
	$datosFecha2Fin = explode("-",$datosFechaFin[0]);
	$anioFin = $datosFecha2Fin[0];
	$mesFin = $datosFecha2Fin[1];
	$diaFin = $datosFecha2Fin[2];
	
	$horaFin = $datosFechaFin[1];
	
	$fechaFormatoFin = $diaFin."/".$mesFin."/".$anioFin." ".$horaFin.":00";


	echo $registro = insertarRegistroTrabajoManual($conexion, $proceso,$idEmpleado, $fechaFormatoInicio, $fechaFormatoFin, $cantidad,$observaciones, $impresoras, $tamanio, $tipo, $acabado, $gramaje, $origen,'NULL',$sinProceso_idProceso,$sinProceso_idCliente,$idSubConcepto2);

	$registroTrabajo2=verUltimoRegistroTrabajo($conexion, $idEmpleado);
	$idUltimoTrabajo2 = $registroTrabajo2[0]["id"];
					
	insertarUsuarioRegistroTrabajo($conexion,$idUltimoTrabajo2);


	

}

?>
