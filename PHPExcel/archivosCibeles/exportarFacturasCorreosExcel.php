<?php

//require("../../../../comprobarSesion.php");

if(isset($_POST["exportarAccion"]) && $_POST["exportarAccion"]=="exportarExcel")
{
	$ruta = '../../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$nombreCliente = $_POST["exportarCliente"];	
	$fechaInicio = $_POST["exportarFechaInicio"];	
	$fechaFin = $_POST["exportarFechaFin"];	
	$orden = $_POST["exportarOrdenarPor"];	
	$desc = $_POST["exportarDesc"];
	
	
	$condicion = "";
	
	
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
		$condicion = " where t2.nombre_empresa='".$nombreCliente2."'";
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
	}
	
	
	

	
	
	
	$resultado=  mostrarFacturasCorreosTodos($conexion,$condicion);
	
	
	
	
	
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
            ->setCellValue('A1', 'FACTURA')
	 		->setCellValue('B1', 'id Cliente')
            ->setCellValue('C1', 'CLIENTE')
            ->setCellValue('D1', 'FECHA')
            //->setCellValue('E1', 'NETO')
			//->setCellValue('F1', 'IVA')			
		
			->setCellValue('E1', 'IMPORTE');

	
$contador=2;
while($contador-2 < count($resultado))
{
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$contador, $resultado[$contador-2]["numeroOficial"])
			->setCellValue('B'.$contador, $resultado[$contador-2]["codigo_saldo"])
            ->setCellValue('C'.$contador, $resultado[$contador-2]["nombre_empresa"])
			->setCellValue('D'.$contador, $resultado[$contador-2]["fecha"])
			//->setCellValue('E'.$contador, $resultado[$contador-2]["precioNeto"])
			//->setCellValue('F'.$contador, $resultado[$contador-2]["iva"])
			->setCellValue('E'.$contador, $resultado[$contador-2]["importe"]);
			
	
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

$nombreArchivo = 'Correos-'.date('d/m/Y').'.xlsx';
	
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
	
	
	
	
	
		


