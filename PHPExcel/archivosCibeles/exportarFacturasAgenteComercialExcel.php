<?php

//require("../../../../comprobarSesion.php");

if(isset($_POST["exportarACAccion"]) && $_POST["exportarACAccion"]=="exportarExcel")
{
	
	$ruta = '../../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	/*$nombreCliente = $_POST["exportarCliente"];	
	$fechaInicio = $_POST["exportarFechaInicio"];	
	$fechaFin = $_POST["exportarFechaFin"];	
	$orden = $_POST["exportarOrdenarPor"];	
	$desc = $_POST["exportarDesc"];*/
	$clayma = $_POST["exportarACClayma"];
	$condicion = $_POST["exportarACCondiciones"];
	$anioSeleccionado = $_POST["exportarACAnioSeleccionado"];
	
	
	
	/*$condicion = "";
	
	
	$nombreCliente2="";
	
	$datosNombre = explode(' - ',$nombreCliente);
	
	$contador=0;
	
	while ($contador<count($datosNombre)-1)
	{
		if ($nombreCliente2=="")
		{
			$nombreCliente2 = $datosNombre[$contador];
		}
		else
		{
			$nombreCliente2 = $nombreCliente2." - ".$datosNombre[$contador];
		}
		
		$contador++;
	}
	
	//echo ($nombreCliente2);
	
	if ($nombreCliente!="Todos")
	{
		$condicion = " where t1.cliente='".$nombreCliente2."'";
	}
	if ($fechaInicio!="")
	{
		if ($condicion=="")
		{
			$condicion = " where t1.fecha >= '".$fechaInicio."'";
		}
		else
		{
			$condicion = $condicion." and t1.fecha >= '".$fechaInicio."'";
		}
	}
	
	if ($fechaFin!="")
	{
		if ($condicion=="")
		{
			$condicion = " where t1.fecha <= '".$fechaFin."'";
		}
		else
		{
			$condicion = $condicion." and t1.fecha <= '".$fechaFin."'";
		}
	}
	
	
	
	
	$condicion = $condicion." order by t1.".$orden;
	
	if ($desc=="true")
	{
		$condicion = $condicion." desc";
	}*/
	
	

	
	//echo $clayma;
	/*if ($clayma=="true")
	{
		$resultado=  mostrarFacturasClayma($conexion,$condicion,$anioSeleccionado);
		$nombreArchivo = 'Clayma_'.date('d-m-Y').'.xlsx';
	}
	else
	{
		$resultado=  mostrarFacturas($conexion,$condicion,$anioSeleccionado);

		$nombreArchivo = 'Cibeles_'.date('d-m-Y').'.xlsx';
	}*/
	
	$resultado = mostrarFacturasAgenteComercialClaymaYcibeles($conexion,$condicion,$anioSeleccionado);
	$nombreArchivo = 'AgenteComercial_'.date('d-m-Y').'.xlsx';
	

	
	//echo count($resultado);
	
	
	
	
	
	
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
	//echo $condicion;

	

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("")
							 ->setLastModifiedBy("")
							 ->setTitle("Office 2007 XLSX")
							 ->setSubject("Office 2007 XLSX")
							 ->setDescription("Office 2007 XLSX")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("result file");


$objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold( true );
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NOMBRE')
	 		->setCellValue('B1', 'CLIENTE')
            ->setCellValue('C1', 'NUMERO FACTURA')
            ->setCellValue('D1', 'FECHA')
            ->setCellValue('E1', 'NETO')
			->setCellValue('F1', 'FORMA DE PAGO')		
			->setCellValue('G1', 'FECHA PAGO')
			->setCellValue('H1', 'TIPO')
			->setCellValue('I1', 'LIQUIDADO');

	
//$contador=2;
$contadorRegistro=0;
$contadorCeldas=2;
$primeraVez=true;
	
$comercialActual = "asjdkfPffaasj";
	
$inicioSumatorio=2;
$finSumatorio=0;
	
	
$BStyle = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
	
	
while($contadorRegistro < count($resultado))
{
	if ($primeraVez)
	{
		$primeraVez = false;
		$comercialActual = $resultado[$contadorRegistro]["nombre"];
	}
	else if ($comercialActual!=$resultado[$contadorRegistro]["nombre"])
	{
		$comercialActual = $resultado[$contadorRegistro]["nombre"];
		
		$finSumatorio=$contadorCeldas-1;
		
			
		
		
		
		
		$objPHPExcel->getActiveSheet()->getCell('E'.$contadorCeldas)->setValue('=SUM(E'.$inicioSumatorio.':E'.$finSumatorio.')');
		$objPHPExcel->getActiveSheet()->getStyle('E'.$contadorCeldas)->getNumberFormat()
			->setFormatCode('#,##0.00_-€');	
		
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$inicioSumatorio.':I'.$finSumatorio)->applyFromArray($BStyle);
		
		$contadorCeldas+=3;
		
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$contadorCeldas.':I'.$contadorCeldas)->getFont()->setBold( true );
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$contadorCeldas, 'NOMBRE')
	 		->setCellValue('B'.$contadorCeldas, 'CLIENTE')
            ->setCellValue('C'.$contadorCeldas, 'NUMERO FACTURA')
            ->setCellValue('D'.$contadorCeldas, 'FECHA')
            ->setCellValue('E'.$contadorCeldas, 'NETO')
			->setCellValue('F'.$contadorCeldas, 'FORMA DE PAGO')		
			->setCellValue('G'.$contadorCeldas, 'FECHA PAGO')
			->setCellValue('H'.$contadorCeldas, 'TIPO')
			->setCellValue('I'.$contadorCeldas, 'LIQUIDADO');
		
		$contadorCeldas++;
		$inicioSumatorio=$contadorCeldas;
		
		
	}
	
	$fecha = "";
	if ($resultado[$contadorRegistro]["formaPagoReal"]!='')
	{
		$fecha = $resultado[$contadorRegistro]["fechaPago"];
	}
	
	
	
	
	
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$contadorCeldas, $resultado[$contadorRegistro]["nombre"])
			->setCellValue('B'.$contadorCeldas, $resultado[$contadorRegistro]["cliente"])
            ->setCellValue('C'.$contadorCeldas, $resultado[$contadorRegistro]["numero"])			
			->setCellValue('D'.$contadorCeldas, $resultado[$contadorRegistro]["fecha"])
			//->setCellValue('D'.$contadorCeldas, $resultado[$contadorRegistro]["fecha"])
			
			//->setCellValue('D'.$contadorCeldas, PHPExcel_Shared_Date::PHPToExcel( date("d/m/Y", strtotime($resultado[$contadorRegistro]["fecha"])) ))
		
			->setCellValue('E'.$contadorCeldas, $resultado[$contadorRegistro]["precioNeto"])
			->setCellValue('F'.$contadorCeldas, $resultado[$contadorRegistro]["formaPagoReal"])
			->setCellValue('G'.$contadorCeldas, $fecha)
			->setCellValue('H'.$contadorCeldas, $resultado[$contadorRegistro]["tipo"])
			->setCellValue('I'.$contadorCeldas, $resultado[$contadorRegistro]["liquidado"]);
		
	
	$objPHPExcel->getActiveSheet()->getStyle('E'.$contadorCeldas)->getNumberFormat()
			->setFormatCode('#,##0.00_-€');
	
	
	
	$contadorRegistro++;
	$contadorCeldas++;
}
	
	$contadorRegistro+3;
	$contadorCeldas2=$contadorCeldas;
	$contadorCeldas--;
	
	$objPHPExcel->getActiveSheet()->getCell('E'.$contadorCeldas2)->setValue('=SUM(E'.$inicioSumatorio.':E'.$contadorCeldas.')');
	$objPHPExcel->getActiveSheet()->getStyle('E'.$contadorCeldas2)->getNumberFormat()
			->setFormatCode('#,##0.00_-€');
	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$inicioSumatorio.':I'.$contadorCeldas)->applyFromArray($BStyle);
	
	
	
	
	//$objPHPExcel->getActiveSheet()->getStyle($column.$style)->getNumberFormat()->setFormatCode('#');
	
	
	
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	
	
	
	
// Miscellaneous glyphs, UTF-8
/*$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Miscellaneous glyphs')
            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');*/

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


	
	
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$nombreArchivo.'"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
}