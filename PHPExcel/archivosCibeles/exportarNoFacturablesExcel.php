<?php

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);

//require("../../../../comprobarSesion.php");

if(isset($_POST["exportarAccion"]) && $_POST["exportarAccion"]=="exportarExcel")
{
	$ruta = '../../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$filtros = isset($_POST["exportarFiltros"]) ? json_decode($_POST["exportarFiltros"], true) : array();
	$filtrosOperadores = isset($_POST["exportarFiltrosOperadores"]) ? json_decode($_POST["exportarFiltrosOperadores"], true) : array();
	$filtrosLike = isset($_POST["exportarFiltrosLike"]) ? json_decode($_POST["exportarFiltrosLike"], true) : array();
	$orden = isset($_POST["exportarOrden"]) ? $_POST["exportarOrden"] : '';
	$desc = isset($_POST["exportarDesc"]) ? $_POST["exportarDesc"] : 'false';

	$order = array(array('campo' => $orden, 'dir' => ($desc=="true" ? 'DESC' : 'ASC')));

	$camposPresupuesto = ['numNoFactura','presupuesto','cliente','campana','importePresupuesto','numNoFacturaFecha','noFacProcesado','noSeFacturaObservaciones'];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = cargarPresupuestos($conn, $bbddSql, $camposPresupuesto, ['tabla9'], $filtros, $filtrosOperadores, $order, $filtrosLike);

	sqlsrv_close($conn);

	$resultado = $res['datos'];

	$nombreArchivo = 'NoFacturables_'.date('d-m-Y').'.xlsx';	
	
	
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
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
	

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


// Add some data
$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nº NO FACTURABLE')
	 		->setCellValue('B1', 'PRESUPUESTO')
            ->setCellValue('C1', 'CLIENTE')
            ->setCellValue('D1', 'CAMPAÑA')
            ->setCellValue('E1', 'IMPORTE')
			->setCellValue('F1', 'FECHA')		
			->setCellValue('G1', 'PROCESADO')	
			->setCellValue('H1', 'OBSERVACIONES');

	
$contador=2;
while($contador-2 < count($resultado))
{
	$fechaNoFactura = ($resultado[$contador-2]["numNoFacturaFecha"]!=null) ? $resultado[$contador-2]["numNoFacturaFecha"]->format('d/m/Y') : '';

	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$contador, $resultado[$contador-2]["numNoFactura"])
			->setCellValue('B'.$contador, $resultado[$contador-2]["presupuesto"])
            ->setCellValue('C'.$contador, $resultado[$contador-2]["cliente"])			
			->setCellValue('D'.$contador, $resultado[$contador-2]["campana"])
			->setCellValue('E'.$contador, $resultado[$contador-2]["importePresupuesto"])
			->setCellValue('F'.$contador, $fechaNoFactura)
			->setCellValue('G'.$contador, $resultado[$contador-2]["noFacProcesado"])
			->setCellValue('H'.$contador, $resultado[$contador-2]["noSeFacturaObservaciones"]);
	
	$contador++;
}
	



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Hoja1');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);



//BORDER_THIN
//BORDER_MEDIUM

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:H'.($contador-1))->applyFromArray(array(
	'borders' => array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	)
));


	
	
// Redirect output to a client’s web browser (Excel2007)
ob_end_clean();
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




