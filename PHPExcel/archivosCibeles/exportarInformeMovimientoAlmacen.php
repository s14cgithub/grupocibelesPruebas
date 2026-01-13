<?php

//require("../../../../comprobarSesion.php");

if(isset($_POST["exportarAccion"]) && $_POST["exportarAccion"]=="exportarExcel")
{
	$ruta = '../../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$condicion = $_POST["formExcelCondicion"];	
	$tipoExcel = $_POST["formExcelTipo"];
	 
	if ($tipoExcel=="Articulos")
	{
		$condicion = " where t3.cantidadTotal>0 and ". substr($condicion,6); 		
		$resultado = mostrarAlmacenMovimientosParaExcel($conexion,$condicion);
	}
	else if ($tipoExcel=="Ubicaciones")
	{
		$condicion = " where t1.cantidadTotal>0 and t3.cantidadTotal>0 and ". substr($condicion,6); 			
		$resultado = mostrarAlmacenMovimientosParaExcelubicaciones($conexion,$condicion);
	}
	else
	{
		$resultado = mostrarAlmacenMovimientos($conexion,$condicion);
	}
	
	//die;
	
	$nombreArchivo = 'MovimientosAlmacen_'.date('d-m-Y').'.xlsx';
	
	
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


	

$objPHPExcel->getActiveSheet()->getStyle("A1:L1")->getFont()->setBold(true);	



if ($tipoExcel=="Articulos")
{
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'id')
		->setCellValue('B1', 'CODIGO PRODUCTO')
		->setCellValue('C1', 'PRODUCTO')		
		->setCellValue('D1', 'DISPONIBILIDAD REAL');
	
	
		$contador=2;
	
		while($contador-2 < count($resultado))
		{
			
			//echo $contador-$noDisponible."aaaa<br>";
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$contador, $resultado[$contador-2]["id"])			
			->setCellValue('B'.$contador, $resultado[$contador-2]["codigo"])			
			->setCellValue('C'.$contador, $resultado[$contador-2]["nombreProducto"])			
			->setCellValue('D'.$contador, $resultado[$contador-2]["disponible"]);
	
			$contador++;
		}
}
else if ($tipoExcel =="Ubicaciones")
{
	// Add some data
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A1', 'id')
	->setCellValue('B1', 'CODIGO PRODUCTO')
	->setCellValue('C1', 'PRODUCTO')	
	->setCellValue('D1', 'HUECO')		
	->setCellValue('E1', 'DISPONIBILIDAD DESPUES DEL MOVIMIENTO')
	->setCellValue('F1', 'DISPONIBILIDAD REAL');


	$contador=2;

	while($contador-2 < count($resultado))
	{
		
		//echo $contador-$noDisponible."aaaa<br>";
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$contador, $resultado[$contador-2]["id"])			
		->setCellValue('B'.$contador, $resultado[$contador-2]["codigo"])			
		->setCellValue('C'.$contador, $resultado[$contador-2]["nombreProducto"])	
		->setCellValue('D'.$contador, $resultado[$contador-2]["hueco"])
		->setCellValue('E'.$contador, $resultado[$contador-2]["cantidadTotal"])
		->setCellValue('F'.$contador, $resultado[$contador-2]["disponible"]);

		$contador++;
	}
}
else
{
	// Add some data
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A1', 'id')
	->setCellValue('B1', 'FECHA')
	->setCellValue('C1', 'CLIENTE')
	->setCellValue('D1', 'CODIGO PRODUCTO')
	->setCellValue('E1', 'PRODUCTO')
	->setCellValue('F1', 'MODALIDAD')
	->setCellValue('G1', 'HUECO')		
	->setCellValue('H1', 'CANTIDAD')
	->setCellValue('I1', 'DISPONIBILIDAD DESPUES DEL MOVIMIENTO')
	->setCellValue('J1', 'DISPONIBILIDAD REAL')
	->setCellValue('K1', 'OT')
	->setCellValue('L1', 'OBSERVACIONES');


	$contador=2;
	while($contador-2 < count($resultado))
	{
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$contador, $resultado[$contador-2]["id"])
		->setCellValue('B'.$contador, $resultado[$contador-2]["fecha"])
		->setCellValue('C'.$contador, $resultado[$contador-2]["subcliente"])
		->setCellValue('D'.$contador, $resultado[$contador-2]["codigo"])			
		->setCellValue('E'.$contador, $resultado[$contador-2]["nombreProducto"])
		->setCellValue('F'.$contador, $resultado[$contador-2]["modalidad"])
		->setCellValue('G'.$contador, $resultado[$contador-2]["hueco"])
		->setCellValue('H'.$contador, $resultado[$contador-2]["cantidad"])
		->setCellValue('I'.$contador, $resultado[$contador-2]["cantidadTotal"])
		->setCellValue('J'.$contador, $resultado[$contador-2]["disponible"])
		->setCellValue('K'.$contador, $resultado[$contador-2]["ot"])
		->setCellValue('L'.$contador, $resultado[$contador-2]["observaciones"]);

		$contador++;
	}
}




	

	

				
	
	
	
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
	
	
	
	
	
		


