<?php

ob_start();

if(isset($_POST["exportarAccion"]) && $_POST["exportarAccion"]=="exportarExcel")
{
	$ruta = '../../';

	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idCliente = $_POST["numeroClienteExcelFacTotal_form"];
	$fechaInicio = $_POST["fechaInicioExcelFacTotal_form"];
	$fechaFin = $_POST["fechaFinExcelFacTotal_form"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$filtros = array();
	if ($idCliente != "todos")
	{
		$filtros['codigo_saldo'] = $idCliente;
	}

	$filtrosOperadores = [
		['campo1' => 'fecha', 'valor' => $fechaInicio, 'operador' => '>='],
		['campo1' => 'fecha', 'valor' => $fechaFin, 'operador' => '<=']
	];

	$order = [
		['campo' => 'fechaPago', 'dir' => 'ASC'],
		['campo' => 'formaPagoReal', 'dir' => 'ASC'],
		['campo' => 'numero', 'dir' => 'ASC']
	];

	$campos = ['numeroFacturaCompleto','codigo_saldo','cliente','fecha','precioNeto','iva','precioTotal','aPagar','origenFactura','formaPagoReal','fechaPago'];

	$resultadoConsulta = mostrarFacturacion($conn, $bbddSql, $campos, ['tabla2'], $filtros, $filtrosOperadores, $order);
	
	
	
	$resultado = $resultadoConsulta['datos'];

	$resultadoConsulta2 = mostrarFacturacionClayma($conn, $bbddSql, $campos, ['tabla2'], $filtros, $filtrosOperadores, $order);
	$resultado2 = $resultadoConsulta2['datos'];

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
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
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
			->setCellValue('J1', 'ORIGEN')
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

	$origenCol = (substr($resultado[$contador-2]["numeroFacturaCompleto"],0,3)=="FAC") ? "" : $resultado[$contador-2]["origenFactura"];


	$objPHPExcel->setActiveSheetIndex(0)
		 	->setCellValue('A'.$contador, "MANIPULADO CIBELES")
            ->setCellValue('B'.$contador, $resultado[$contador-2]["numeroFacturaCompleto"])
			->setCellValue('C'.$contador, $resultado[$contador-2]["codigo_saldo"])
            ->setCellValue('D'.$contador, $resultado[$contador-2]["cliente"])
			->setCellValue('E'.$contador, $resultado[$contador-2]["fecha"])
			->setCellValue('F'.$contador, $resultado[$contador-2]["precioNeto"])
			->setCellValue('G'.$contador, $resultado[$contador-2]["iva"])
			->setCellValue('H'.$contador, $resultado[$contador-2]["precioTotal"])
			->setCellValue('I'.$contador, $resultado[$contador-2]["aPagar"])
			->setCellValue('J'.$contador, $origenCol)
			->setCellValue('K'.$contador, $resultado[$contador-2]["formaPagoReal"])
			->setCellValue('L'.$contador, $fechaPago);


	$contador++;
}

$resultado2Total = count($resultado2);
$contador2 = 0;
while($contador2 < $resultado2Total)
{

	$fechaPago="";
	if ($resultado2[$contador2]["formaPagoReal"]!="")
	{
		$fechaPago=$resultado2[$contador2]["fechaPago"];
	}
	else
	{
		$fechaPago="";
	}

	$origenCol = (substr($resultado2[$contador2]["numeroFacturaCompleto"],0,3)=="FAC") ? "" : $resultado2[$contador2]["origenFactura"];

	$objPHPExcel->setActiveSheetIndex(0)
		 	->setCellValue('A'.$contador, "MANIPULADO CLAYMA")
            ->setCellValue('B'.$contador, $resultado2[$contador2]["numeroFacturaCompleto"])
			->setCellValue('C'.$contador, $resultado2[$contador2]["codigo_saldo"])
            ->setCellValue('D'.$contador, $resultado2[$contador2]["cliente"])
			->setCellValue('E'.$contador, $resultado2[$contador2]["fecha"])
			->setCellValue('F'.$contador, $resultado2[$contador2]["precioNeto"])
			->setCellValue('G'.$contador, $resultado2[$contador2]["iva"])
			->setCellValue('H'.$contador, $resultado2[$contador2]["precioTotal"])
			->setCellValue('I'.$contador, $resultado2[$contador2]["aPagar"])
			->setCellValue('J'.$contador, $origenCol)
			->setCellValue('K'.$contador, $resultado2[$contador2]["formaPagoReal"])
			->setCellValue('L'.$contador, $fechaPago);


	$contador++;
	$contador2++;
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



//facturas de correos

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

$filtrosCorreos = array();
if ($idCliente != "todos")
{
	$filtrosCorreos['codigo_saldo'] = $idCliente;
}

$filtrosOperadoresCorreos = [
	['campo1' => 'fecha', 'valor' => $fechaInicio, 'operador' => '>='],
	['campo1' => 'fecha', 'valor' => $fechaFin, 'operador' => '<=']
];

$resultadoConsulta5 = mostrarFacturacionCorreos($conn, $bbddSql, ['numeroOficial','codigo_saldo','codigoCliente','nombre_empresa','fecha','neto','iva','importe','aPagar','formaPago','fechaPago'], ['tabla2'], $filtrosCorreos, $filtrosOperadoresCorreos, []);
$resultado5 = $resultadoConsulta5['datos'];

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



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


ob_end_clean();

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
