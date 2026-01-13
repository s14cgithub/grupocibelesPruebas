<?php

//require("../../../../comprobarSesion.php");

if(isset($_POST["exportarAccion"]) && $_POST["exportarAccion"]=="exportarExcel")
{
	$ruta = '../../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	

	session_start(); 


	$texto = $_SESSION["presupuestoListado_texto"];
	$queBusca = $_SESSION["presupuestoListado_queBusca"];
	$bajada = $_SESSION["presupuestoListado_Bajada"];
	$abierta = $_SESSION["presupuestoListado_Abierta"];
	$meses = $_SESSION["presupuestoListado_meses"];
	$orden = $_SESSION["presupuestoListado_orden"];
	$desc = $_SESSION["presupuestoListado_Desc"];
	$fechaAceptacion = $_SESSION["presupuestoListado_fechaAceptacion"];

	$fecha = "";
	//$meses=0;


	if ($meses>0)
	{
		$meses = $meses-1;
		$fechaActual = date('d-m-Y');
		$fechaInicio = date("01-m-Y",strtotime($fechaActual."- ".$meses." month")); 
		$fecha = " fecha >='".$fechaInicio."'";
	}
	
	
		
	
	
	$resultado = mostrarPresupuestos1($conexion,$orden,$desc,$texto,$queBusca, $bajada, $abierta, $fecha,$fechaAceptacion);
	
	
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
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'PRESUPUESTO')
	 		->setCellValue('B1', 'LETRA')
            ->setCellValue('C1', 'CLIENTE')
            ->setCellValue('D1', 'notaCibeles')
            ->setCellValue('E1', 'CAMPAÑA')
			->setCellValue('F1', 'CAMPAÑA 2')	
			->setCellValue('G1', 'FECHA')	
			->setCellValue('H1', 'FECHA ACEPTACION')	
			->setCellValue('I1', 'FECHA COMPROMISO')	
			->setCellValue('J1', 'FECHA TERMINADO')	
			->setCellValue('K1', 'FECHA INICIO REAL')	
			->setCellValue('L1', 'CANTIDAD')	
			->setCellValue('M1', 'CANTIDAD 2')		
			->setCellValue('N1', 'CLAYMA')	
			->setCellValue('O1', 'NUMERO NO FACTURA')	
			->setCellValue('P1', 'NO SE FACTURA - OBSERVACIONES')	
			->setCellValue('Q1', 'NUMERO FACTURA')	
			->setCellValue('R1', 'AÑO FACTURA')	
			->setCellValue('S1', 'OT BAJADA')
			->setCellValue('T1', 'OT ABIERTA');
				
	

	
$contador=2;
while($contador-2 < count($resultado))
{
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$contador, $resultado[$contador-2]["presupuesto"])
			->setCellValue('B'.$contador, $resultado[$contador-2]["letra"])
            ->setCellValue('C'.$contador, $resultado[$contador-2]["cliente"])
			->setCellValue('D'.$contador, $resultado[$contador-2]["notaCibeles"])
			->setCellValue('E'.$contador, $resultado[$contador-2]["campana"])
			->setCellValue('F'.$contador, $resultado[$contador-2]["campana2"])
			->setCellValue('G'.$contador, $resultado[$contador-2]["fecha"])
			->setCellValue('H'.$contador, $resultado[$contador-2]["fechaAceptacion"])
			->setCellValue('I'.$contador, $resultado[$contador-2]["fechaCompromiso"])
			->setCellValue('J'.$contador, $resultado[$contador-2]["fechaTerminado"])
			->setCellValue('K'.$contador, $resultado[$contador-2]["fechaInicioReal"])
			->setCellValue('L'.$contador, $resultado[$contador-2]["cantidad"])
			->setCellValue('M'.$contador, $resultado[$contador-2]["cantidad2"])
			->setCellValue('N'.$contador, $resultado[$contador-2]["clayma"])
			->setCellValue('O'.$contador, $resultado[$contador-2]["numNoFactura"])
			->setCellValue('P'.$contador, $resultado[$contador-2]["noSeFacturaObservaciones"])
			->setCellValue('Q'.$contador, $resultado[$contador-2]["numFactura"])
			->setCellValue('R'.$contador, $resultado[$contador-2]["anioFactura"])
			->setCellValue('S'.$contador, $resultado[$contador-2]["otBajada"])
			->setCellValue('T'.$contador, $resultado[$contador-2]["otAbierta"]);
			
		
			
	
	$contador++;
}
	
// Miscellaneous glyphs, UTF-8
/*$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Miscellaneous glyphs')
            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');*/

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$nombreArchivo = 'Presupuestos'.date('d/m/Y').'.xls';
	
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
	
	
	
	
	
		


