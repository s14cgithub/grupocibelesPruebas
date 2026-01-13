

<?php

//require("../../../../comprobarSesion.php");

if(isset($_POST["exportarAccion"]) && $_POST["exportarAccion"]=="exportarExcel")
{
	$ruta = '../../';	
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$idProducto = $_POST["exportarSidiExcel_idProducto"];
	$modo = $_POST["exportarSidiExcel_modo"];

	$sidi_codigoClienteCorreos = "81151061";
	$sidi_version = "2018v002";
	//$sidi_numeroContrato = "81000293";//sidi_pre
	$sidi_numeroContrato = "81000022";
	$sidi_numeroMaquina = "MV007468";
	//$sidi_oficinaElegida = "2812096"; //solo para pre
	$sidi_oficinaElegida = "2841594"; //solo para pro
	
	$sidi_codigoNuestroCliente = "";

	
	
	
	$nombreArchivo = 'Clayma_'.date('d-m-Y').'.xlsx';
	
	header('Content-Type: application/vnd.openXMLformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$nombreArchivo.'"');
	header('Cache-Control: max-age=0');

	header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
	header('Content-Disposition: attachment;filename="'.$nombreArchivo.'"');

	include '../Classes/PHPExcel.php';
	$objPHPExcel = new PHPExcel();
	$sheet = $objPHPExcel->getActiveSheet();


	//$idProducto = 3;
	


	$posicion="";
	
	if ($modo==0)
	{
		$datosTipos = mostrarDatosParaExportarAlbaranesCorreosFranqueoSIDI($conexion,$idProducto);
	}
	else if ($modo==1)
	{
		$datosTipos = mostrarDatosParaExportarAlbaranesCorreosFranqueoPostF12SIDI($conexion,$idProducto);
	}
	$sheet->setCellValue('A1', count($datosTipos));//1 posicion
	for ($cont=0 ; $cont < count($datosTipos) ; $cont++)
	{
		
		if ($cont==0 && count($datosTipos)==1)
		{
			$posicion="U";
		}
		else if ($cont==0 && count($datosTipos)>1)
		{
			$posicion = "C";
		}
		else if ($cont == count($datosTipos) - 1 && count($datosTipos >1))
		{
			$posicion = "F";
		}
		else
		{
			$posicion = "R";
		}
		
		$valorFormatoEntrega="0";
		
		if ($datosTipos[$cont]["anadidos2"] == "AcuseRecibo")
		{
			$valorFormatoEntrega="2";
		}
		else if ($datosTipos[$cont]["anadidos2"] == "PEE") //5años
		{
			$valorFormatoEntrega="3";
		}

		$contador = $cont + 1;
		//$sheet->setCellValue('A1', 'Hola Mundo');

		//$sheet->setCellValue('A1', "aaaa");//1 posicion
		
		
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$contador, $posicion)//1 posicion
			->setCellValue('B'.$contador, $sidi_version)//2 version
            ->setCellValue('C'.$contador, $datosTipos[$cont]["idSIDI"]) //3 codigo producto		
			->setCellValue('D'.$contador, "FM")//4 tipo de franqueo			
			->setCellValue('F'.$contador, $sidi_numeroContrato) //6 numero de contrato
			->setCellValue('G'.$contador, $datosTipos[$cont]["codigoSidi"])//7 codigo cliente
			->setCellValue('H'.$contador, $sidi_numeroMaquina)//8 numero de maquina
			->setCellValue('I'.$contador, $datosTipos[$cont]["importeSidi"] )//9 importe
			->setCellValue('J'.$contador, $datosTipos[$cont]["numSeguimiento"])//10 numero de seguimiento nuestro
			->setCellValue('N'.$contador, "1" )//14 total de bultos
			->setCellValue('O'.$contador, "1" )//15 numero de bultos
			->setCellValue('S'.$contador, $datosTipos[$cont]["nombre"])// 19 nombre del destinatario
			->setCellValue('Z'.$contador, $datosTipos[$cont]["direccion"] ) // 26  direccion
			->setCellValue('AH'.$contador, $datosTipos[$cont]["poblacion"])// 34 localidad
			->setCellValue('AJ'.$contador, $datosTipos[$cont]["cp"] )//36 codigo postal solo nacional y andorra; siempre 5 digitos
			->setCellValue('AL'.$contador, "ES")//38 pais. Obligatorio
			->setCellValue('AM'.$contador, $sidi_oficinaElegida )//39 Oficina Elegida
			->setCellValue('AQ'.$contador, "correos@grupocibeles.es")//43: email destinatario
			->setCellValue('AT'.$contador, $datosTipos[$cont]["ot"])// 46 referencia - nuestro OT
			->setCellValue('AU'.$contador, $datosTipos[$cont]["otSidi"]) // 47 referencia - OT de sidi
			->setCellValue('AV'.$contador, $datosTipos[$cont]["numSeguimiento"] )//48 referencias  - nuestro numero de seguimiento
			->setCellValue('AX'.$contador, $datosTipos[$cont]["gramos_SIDI"] ) // 50 peso
			->setCellValue('BO'.$contador, $valorFormatoEntrega)//67 - Formato de Prueba entrega
			->setCellValue('FJ'.$contador, $datosTipos[$cont]["nombre_empresa"])//166 - nombre del remite
			->setCellValue('FQ'.$contador, $datosTipos[$cont]["direccionCliente"])// 173  direccion remite
			->setCellValue('FX'.$contador, $datosTipos[$cont]["localidadCliente"])// 180  localidad remite
			->setCellValue('FY'.$contador, $datosTipos[$cont]["provinciaCliente"])// 181  provincia remite
			->setCellValue('FZ'.$contador, $datosTipos[$cont]["cpCliente"]) // 182  cp remite
			->setCellValue('GC'.$contador, "........" ) //185 telefono remite			
			->setCellValue('GG'.$contador,  "E");//189 fin

	}






	

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_end_clean();
	$objWriter->save('php://output');
	exit;
	

}


?>
	
	
	
	
	
		


