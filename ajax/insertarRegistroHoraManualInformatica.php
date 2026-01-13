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


	

	/*$impresoras=$_POST["impresoras"];
	$tamanio=$_POST["tamanio"];
	$tipo=$_POST["tipo"];
	$acabado=$_POST["acabado"];
	$gramaje=$_POST["gramaje"];
	$origen=$_POST["origen"];
*/

	$impresoras=isset($_POST["impresoras"])?$_POST["impresoras"]:"NULL";
	$tamanio=isset($_POST["tamanio"])?$_POST["tamanio"]:"NULL";
	$tipo=isset($_POST["tipo"])?$_POST["tipo"]:"NULL";
	$acabado=isset($_POST["acabado"])?$_POST["acabado"]:"NULL";
	$gramaje=isset($_POST["gramaje"])?$_POST["gramaje"]:"NULL";
	$origen=isset($_POST["origen"])?$_POST["origen"]:"NULL";

	$numCaras=isset($_POST["numCaras"])?$_POST["numCaras"]:"NULL";

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


	echo $registro = insertarRegistroTrabajoManual($conexion, $proceso,$idEmpleado, $fechaFormatoInicio, $fechaFormatoFin, $cantidad,$observaciones, $impresoras, $tamanio, $tipo, $acabado, $gramaje, $origen,$numCaras,$sinProceso_idProceso,$sinProceso_idCliente);

	$registroTrabajo2=verUltimoRegistroTrabajo($conexion, $idEmpleado);
	$idUltimoTrabajo2 = $registroTrabajo2[0]["id"];
					
	insertarUsuarioRegistroTrabajo($conexion,$idUltimoTrabajo2);


	

}

?>
