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

	$condicion = $_POST["exportarACCondiciones"];
	$anioSeleccionado = $_POST["exportarACAnioSeleccionado"];


	
	
	
	$resultado = mostrarFacturasAbonosCorreosClayma($conexion,$condicion,$anioSeleccionado);
	$nombreArchivo = 'facturasAbonosCorreos_'.date('d-m-Y').'.xlsx';
	

	
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

$contadorInicio = 2;

$objPHPExcel->getActiveSheet()->getStyle("A".$contadorInicio.":G".$contadorInicio)->getFont()->setBold( true );
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$contadorInicio, 'CLIENTE')
	 		->setCellValue('B'.$contadorInicio, 'NUMERO FACTURA')
            ->setCellValue('C'.$contadorInicio, 'TIPO')
            ->setCellValue('D'.$contadorInicio, 'FECHA')
            ->setCellValue('E'.$contadorInicio, 'FECHA DE PAGO')
			->setCellValue('F'.$contadorInicio, 'FORMA DE PAGO')
			->setCellValue('G'.$contadorInicio, 'PRECIO NETO');
	
//$contador=2;
$contadorRegistro=0;
$contadorCeldas=$contadorInicio+1;
$primeraVez=true;
	
$comercialActual = "asjdkfPffaasj";
	
$inicioSumatorio=$contadorCeldas;
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
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$contadorCeldas, $resultado[$contadorRegistro]["cliente"])
			->setCellValue('B'.$contadorCeldas, $resultado[$contadorRegistro]["numero"])
            ->setCellValue('C'.$contadorCeldas, $resultado[$contadorRegistro]["tipo"])			
			->setCellValue('D'.$contadorCeldas, $resultado[$contadorRegistro]["fecha"])		
			->setCellValue('E'.$contadorCeldas, $resultado[$contadorRegistro]["fechaPago"])
			->setCellValue('F'.$contadorCeldas, $resultado[$contadorRegistro]["formaPagoReal"])			
			->setCellValue('G'.$contadorCeldas, $resultado[$contadorRegistro]["precioNeto"]);
			
		
	
	$objPHPExcel->getActiveSheet()->getStyle('G'.$contadorCeldas)->getNumberFormat()->setFormatCode('#,##0.00_-€');
	
	
	
	$contadorRegistro++;
	$contadorCeldas++;
}
	
	$contadorRegistro+3;
	$contadorCeldas2=$contadorCeldas;
	$contadorCeldas--;
	
	$objPHPExcel->getActiveSheet()->getCell('G'.$contadorCeldas2)->setValue('=SUM(G'.$inicioSumatorio.':G'.$contadorCeldas.')');
	$objPHPExcel->getActiveSheet()->getStyle('G'.$contadorCeldas2)->getNumberFormat()->setFormatCode('#,##0.00_-€');


	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$inicioSumatorio.':G'.$contadorCeldas)->applyFromArray($BStyle);
	
	$objPHPExcel->getActiveSheet()->getCell('G1')->setValue('=SUM(G'.$inicioSumatorio.':G'.$contadorCeldas.')');
	$objPHPExcel->getActiveSheet()->getStyle('G1')->getNumberFormat()->setFormatCode('#,##0.00_-€');
	
	
	
	//$objPHPExcel->getActiveSheet()->getStyle($column.$style)->getNumberFormat()->setFormatCode('#');
	
	
	
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	
	
	
	
	
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