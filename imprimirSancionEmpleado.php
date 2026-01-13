<?php 

session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccionSancion"])&&$_POST["imprimirAccionSancion"]=="imprimirSancion")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");		
	
	require($ruta."FPDF/fpdf.php");
	
	require($ruta."Archivos Comunes/cabeceraPieInforme.php");
	
	$nombreEmpleado = $_POST["empleado_sancion"];	
	$fechaInicio = $_POST["fechaInicio_sancion"];
	$fechaFin = $_POST["fechaFin_sancion"];
	$tiempoArealizar = $_POST["tiempoArealizar_sancion"];
	$tiempoTrabajado = $_POST["tiempoTrabajado_sancion"];
	$diferencia = $_POST["diferencia_sancion"];
	//echo $nombreEmpleado;

	
	/*$datosPresupuesto = verPresupuesto($conexion,$numPresupuesto);
	$datosDetalles = cargarDetallesPresupuesto($conexion,$numPresupuesto);*/


	
	
	$pdf = new PDF('P','mm','A4');

	//$pdf->SetXY(-10,-5);

	
	$pdf->AddPage();
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(false);	
	$pdf->SetFont('Arial','B',10);
	$altura=18;
	$margen=85;
	//$pdf->Text($margen,$altura,"ORDEN DE TRABAJO");


	$pdf->SetFont('Arial','',12);
	$altura=70;
	$margen=30;
	$pdf->Text($margen,$altura,$nombreEmpleado.":");
	
	$fechaInicio2=date_create($fechaInicio);
	//$fechaInicio2 = date('d/M/Y', $fechaInicio);

	if ($tiempoTrabajado=="0:0")
	{
		$tiempoTrabajado='0:00:00:00';
	}

	$valores_tiempoTrabajado = explode(':',$tiempoTrabajado);

	/*$texto = "Se le comunica que se ha detectado un error de fichaje el día ".date_format($fechaInicio2,"d/m/Y"). 
	". Siendo la suma de su jornada de ". $valores_tiempoTrabajado[1]." horas, ".$valores_tiempoTrabajado[2]." minutos y ".$valores_tiempoTrabajado[3]." segundos.".
	"\n\nSe recuerda la obligatoriedad de usar los medios a su alcance para la medición de la misma, siendo el único dato válido de la empresa para su control, los informes obtenidos del reloj fichador y de las PDA's facilitados para tal fin.".
	"\n\nEl inclumplimiento de su jornada laboral, o del uso de los dispositivos de medición, es constitutivo de falta, por lo que se le comunica mediante esta amonestación." ;
	*/

	$texto = "Se le comunica que se ha detectado un error de fichaje el día ".date_format($fechaInicio2,"d/m/Y"). 
	". Siendo la suma de su jornada de ". $tiempoTrabajado." horas.".
	"\n\nSe recuerda la obligatoriedad de usar los medios a su alcance para la medición de la misma, siendo el único dato válido de la empresa para su control, los informes obtenidos del reloj fichador y de las PDA's facilitados para tal fin.".
	"\n\nEl inclumplimiento de su jornada laboral, o del uso de los dispositivos de medición, es constitutivo de falta, por lo que se le comunica mediante esta amonestación." ;
	
	
	$pdf->SetFont('Arial','',12);
	$pdf->SetXY(30,80);
	$pdf->MultiCell(140,5,utf8_decode($texto),0,'L',false);
	

	

	$pdf->SetFont('Arial','',12);
	$altura=160;
	$margen=130;
	$pdf->Text($margen,$altura,"Getafe, ".date("d/m/Y"));
	



	$pdf->Output("I",$ruta."Presupuesto - ".date("dmy")." .pdf","UTF-8");

	
}
?>