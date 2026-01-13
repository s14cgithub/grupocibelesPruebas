<?php

//require("../../../../comprobarSesion.php");

if(isset($_POST["exportarAccion"]) && $_POST["exportarAccion"]=="exportarExcel")
{
	$ruta = '../../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$idCliente = $_POST["numeroClienteExcelFacTotal_form"];	
	$fechaInicio = $_POST["fechaInicioExcelFacTotal_form"];	
	$fechaFin = $_POST["fechaFinExcelFacTotal_form"];	
	
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
	
	if ($idCliente =="todos")
	{
		$condicion = " where t1.fecha >= '".$fechaInicio1."' and t1.fecha <='".$fechaFin1."'  order by fechaPago, formaPagoReal, numero ";
	}
	else
	{
		$condicion = " where t2.codigo_saldo= ".$idCliente. " and t1.fecha >= '".$fechaInicio1."' and t1.fecha <='".$fechaFin1."'  order by fechaPago, formaPagoReal, numero ";
	}
	
	 
	//$resultado=  mostrarFacturas($conexion,$condicion);
	$resultado=  mostrarFacturasTodosLosAnios($conexion,$condicion);
	
	
	
	$nombreArchivo = 'Facturas_'.date('d-m-Y').'.xlsx';
	
	
	
	
	
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
	
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'ORIGEN')
            ->setCellValue('B1', 'FACTURA')
	 		->setCellValue('C1', 'id Cliente')
            ->setCellValue('D1', 'CLIENTE')
            ->setCellValue('E1', 'FECHA')
            ->setCellValue('F1', 'NETO')
			->setCellValue('G1', 'IVA')		
			->setCellValue('H1', 'IMPORTE')
			->setCellValue('I1', 'aPagar')
			->setCellValue('J1', 'ABONO')
			->setCellValue('K1', 'FORMA PAGO')
			->setCellValue('L1', 'FECHA PAGO');

	
$contador=2;
while($contador-2 < count($resultado))
{
	$fechaPago="";
	if ($resultado[$contador-2]["formaPagoReal"]!="")
	{
		$fechaPago=$resultado[$contador-2]["fechaPago"];
	}
	else
	{
		$fechaPago="";
	}
		
		
	$objPHPExcel->setActiveSheetIndex(0)
		 	->setCellValue('A'.$contador, "MANIPULADO CIBELES")
            ->setCellValue('B'.$contador, $resultado[$contador-2]["numero"]."/".substr($resultado[$contador-2]["anioFactura"],2))
			->setCellValue('C'.$contador, $resultado[$contador-2]["codigo_saldo"])
            ->setCellValue('D'.$contador, $resultado[$contador-2]["cliente"])			
			->setCellValue('E'.$contador, $resultado[$contador-2]["fecha"])
			->setCellValue('F'.$contador, $resultado[$contador-2]["precioNeto"])
			->setCellValue('G'.$contador, $resultado[$contador-2]["iva"])
			->setCellValue('H'.$contador, $resultado[$contador-2]["precioTotal"])
			->setCellValue('I'.$contador, $resultado[$contador-2]["aPagar"])
			->setCellValue('J'.$contador, $resultado[$contador-2]["numAbono"]."/".substr($resultado[$contador-2]["anioAbono"],2))
			->setCellValue('K'.$contador, $resultado[$contador-2]["formaPagoReal"])
			->setCellValue('L'.$contador, $fechaPago);
			
	
	$contador++;
}
	
$resultado2=  mostrarFacturasClaymaTodosLosAnios($conexion,$condicion);
$contador2 = 2;
while($contador2-2 < count($resultado2))
{
	
	$fechaPago="";
	if ($resultado2[$contador2-2]["formaPagoReal"]!="")
	{
		$fechaPago=$resultado2[$contador2-2]["fechaPago"];		
	}
	else
	{
		$fechaPago="";
	}
	
	$objPHPExcel->setActiveSheetIndex(0)
		 	->setCellValue('A'.$contador, "MANIPULADO CLAYMA")
            ->setCellValue('B'.$contador, $resultado2[$contador2-2]["numero"]."/".substr($resultado[$contador-2]["anioFactura"],2))
			->setCellValue('C'.$contador, $resultado2[$contador2-2]["codigo_saldo"])
            ->setCellValue('D'.$contador, $resultado2[$contador2-2]["cliente"])			
			->setCellValue('E'.$contador, $resultado2[$contador2-2]["fecha"])
			->setCellValue('F'.$contador, $resultado2[$contador2-2]["precioNeto"])
			->setCellValue('G'.$contador, $resultado2[$contador2-2]["iva"])
			->setCellValue('H'.$contador, $resultado2[$contador2-2]["precioTotal"])
			->setCellValue('I'.$contador, $resultado2[$contador2-2]["aPagar"])
			->setCellValue('J'.$contador, $resultado2[$contador2-2]["numAbono"])
			->setCellValue('K'.$contador, $resultado2[$contador2-2]["formaPagoReal"])
			->setCellValue('L'.$contador, $fechaPago);
			
	
	$contador++;
	$contador2++;
}
	
	

$contador3 = 0;
while($contador3 < count($resultado))
{	
	$numeroAbono = $resultado[$contador3]["numAbono"];
	$anioAbono = $resultado[$contador3]["anioAbono"];
	$condicion2 = " where numero= ".$numeroAbono;
	if ($numeroAbono!="")
	{
		$resultado3 = mostrarAbonos($conexion, $condicion2, $anioAbono);
		
		
		$fechaPago="";
		if ($resultado3[0]["formaPagoReal"]!="")
		{
			$fechaPago=$resultado3[0]["fechaPago"];		
		}
		else
		{
			$fechaPago="";
		}
				
		
		$objPHPExcel->setActiveSheetIndex(0)
		 	->setCellValue('A'.$contador, "MANIPULADO ABONO CIBELES")			
            ->setCellValue('B'.$contador, $resultado3[0]["numero"]."/".substr($anioAbono,2))
			->setCellValue('C'.$contador, '')
            ->setCellValue('D'.$contador, $resultado3[0]["cliente"])
			->setCellValue('E'.$contador, $resultado3[0]["fecha"])
			->setCellValue('F'.$contador, $resultado3[0]["precioNeto"])
			->setCellValue('G'.$contador, $resultado3[0]["iva"])
			->setCellValue('H'.$contador, $resultado3[0]["precioTotal"])
			->setCellValue('I'.$contador, $resultado3[0]["aPagar"])
			->setCellValue('K'.$contador, $resultado3[0]["formaPagoReal"])
			->setCellValue('L'.$contador, $fechaPago);
		
		$contador++;
	}
	
	$contador3++;
}
	



	
$contador4 = 0;
while($contador4 < count($resultado2))
{
	
	$numeroAbono2 = $resultado2[$contador4]["numAbono"];
	$anioAbono2 = $resultado2[$contador4]["anioAbono"];
	
	$condicion4 = " where numero= ".$numeroAbono2;
	if ($numeroAbono2!="")
	{
		$resultado4 = mostrarAbonosClayma($conexion, $condicion4, $anioAbono2);
		
		$fechaPago="";
		if ($resultado4[0]["formaPagoReal"]!="")
		{
			$fechaPago=$resultado4[0]["fechaPago"];		
		}
		else
		{
			$fechaPago="";
		}
		
		
		$objPHPExcel->setActiveSheetIndex(0)
		 	->setCellValue('A'.$contador, "MANIPULADO ABONO CLAYMA")
            ->setCellValue('B'.$contador, $resultado4[0]["numero"]."/".substr($anioAbono2,2))
			->setCellValue('C'.$contador, '')
            ->setCellValue('D'.$contador, $resultado4[0]["cliente"])
			->setCellValue('E'.$contador, $resultado4[0]["fecha"])
			->setCellValue('F'.$contador, $resultado4[0]["precioNeto"])
			->setCellValue('G'.$contador, $resultado4[0]["iva"])
			->setCellValue('H'.$contador, $resultado4[0]["precioTotal"])
			->setCellValue('I'.$contador, $resultado4[0]["aPagar"])
			->setCellValue('K'.$contador, $resultado4[0]["formaPagoReal"])
			->setCellValue('L'.$contador, $fechaPago);
		
		$contador++;
	}
	
	$contador4++;
}
	
$objPHPExcel->getActiveSheet()->getStyle("E".$contador.":I".$contador)->getFont()->setBold(true);	
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$contador, 'TOTAL: ')
		 	->setCellValue('F'.$contador, '=SUM(F2:F'.($contador-1).')')
			->setCellValue('G'.$contador, '=SUM(G2:G'.($contador-1).')')
			->setCellValue('H'.$contador, '=SUM(H2:H'.($contador-1).')')
			->setCellValue('I'.$contador, '=SUM(I2:I'.($contador-1).')');
	

$contador++;
$contador++;
$contador++;
	

	
//facturas y abonos de correos

$objPHPExcel->getActiveSheet()->getStyle("A".$contador.":K".$contador)->getFont()->setBold(true);
	
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$contador, 'ORIGEN')
            ->setCellValue('B'.$contador, 'FACTURA')
	 		->setCellValue('C'.$contador, 'id Cliente')
            ->setCellValue('D'.$contador, 'CLIENTE')
            ->setCellValue('E'.$contador, 'FECHA')
            ->setCellValue('F'.$contador, 'NETO')
			->setCellValue('G'.$contador, 'IVA')		
			->setCellValue('H'.$contador, 'IMPORTE')
			->setCellValue('I'.$contador, 'aPagar')
			->setCellValue('J'.$contador, 'FORMA PAGO')
			->setCellValue('K'.$contador, 'FECHA PAGO');	
	
	

$contador++;
$inicioRegistroCorreos = $contador;
	
	
if ($idCliente =="todos")
{
	$condicion5 = " where  t1.fecha >= '".$fechaInicio1."' and t1.fecha <='".$fechaFin1."'    order by fechaPago, formaPago, numeroOficial";
}
else
{
	$condicion5 = " where codigo_saldo = " .$idCliente."  and t1.fecha >= '".$fechaInicio1."' and t1.fecha <='".$fechaFin1."'    order by fechaPago, formaPago, numeroOficial";
}	
	
	

$resultado5 = mostrarFacturasCorreosTodos($conexion, $condicion5);
$contador5 = 0;
while($contador5 < count($resultado5))
{		
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$contador, "CORREOS")
		->setCellValue('B'.$contador, $resultado5[$contador5]["numeroOficial"])
		->setCellValue('C'.$contador, $resultado5[$contador5]["codigo_saldo"])
		->setCellValue('D'.$contador, $resultado5[$contador5]["codigoCliente"]." - ".$resultado5[$contador5]["nombre_empresa"])
		->setCellValue('E'.$contador, $resultado5[$contador5]["fecha"])
		->setCellValue('F'.$contador, $resultado5[$contador5]["neto"])
		->setCellValue('G'.$contador, $resultado5[$contador5]["iva"])
		->setCellValue('H'.$contador, $resultado5[$contador5]["importe"])
		->setCellValue('I'.$contador, $resultado5[$contador5]["aPagar"])
		->setCellValue('J'.$contador, $resultado5[$contador5]["formaPago"])
		->setCellValue('K'.$contador, $resultado5[$contador5]["fechaPago"]);

	$contador++;
	$contador5++;
}
	
$objPHPExcel->getActiveSheet()->getStyle("E".$contador.":I".$contador)->getFont()->setBold(true);	
$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('E'.$contador, 'TOTAL: ')
		->setCellValue('F'.$contador, '=SUM(F'.$inicioRegistroCorreos.':F'.($contador-1).')')
		->setCellValue('G'.$contador, '=SUM(G'.$inicioRegistroCorreos.':G'.($contador-1).')')
		->setCellValue('H'.$contador, '=SUM(H'.$inicioRegistroCorreos.':H'.($contador-1).')')
		->setCellValue('I'.$contador, '=SUM(I'.$inicioRegistroCorreos.':I'.($contador-1).')');

	




	
	

				
	
	
	
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
	
	
	
	
	
		


