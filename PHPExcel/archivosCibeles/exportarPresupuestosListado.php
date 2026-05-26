<?php

//require("../../../../comprobarSesion.php");

if(isset($_POST["exportarAccion"]) && $_POST["exportarAccion"]=="exportarExcel")
{
	$ruta = '../../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	

	session_start(); 




	$fecha = "";
	$meses = $_SESSION["presupuestoListado_meses"];


	if ($meses>0)
	{
		$meses = $meses-1;
		$fechaActual = date('d-m-Y');
		$fechaInicio = date("01-m-Y",strtotime($fechaActual."- ".$meses." month")); 
		$fecha = " fecha >='".$fechaInicio."'";
	}

	$campos = array();
	$campos = [
		'cliente',
        'campana',
        'numeroFacturaCompleto',
		'numNoFactura',
        'clayma',
        'inicialComercial',
        'presupuesto',
        'fecha',
        'otBajada',
        'otAbierta',   
        'fechaAceptacion',
        'fechaCompromiso',
        'fechaTerminado',
        'activo',
        'nombreComercial',
        'telefonoComercial',
		'letra',
		'notaCibeles',
		'campana2',
		'fechaInicioReal',
		'cantidad',
		'cantidad2',
		'noSeFacturaObservaciones'
	];
	
	
	
	$filtros = array();
	$filtros["texto"] =  $_SESSION["presupuestoListado_texto"];
	$filtros["queBusca"] =  $_SESSION["presupuestoListado_queBusca"];
	$filtros["bajada"] =  $_SESSION["presupuestoListado_Bajada"];
	$filtros["abierta"] =  $_SESSION["presupuestoListado_Abierta"];
	$filtros["meses"] =  $_SESSION["presupuestoListado_meses"];
	$filtros["fechaAceptacion"] =  $_SESSION["presupuestoListado_fechaAceptacion"];
	$filtros["fecha"] =  $fecha;

	
	$order = array();
	$order[] = array(
		'campo' => $_SESSION["presupuestoListado_orden"],
		'dir' => $_SESSION["presupuestoListado_Desc"]
	);

	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	
	
	$resultado = cargarPresupuestosConNumFacturas($conn,$bbddSql, $campos, $filtros, $order);
	
	sqlsrv_close($conn);
	
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
			->setCellValue('Q1', 'FACTURA')			
			->setCellValue('R1', 'OT BAJADA')
			->setCellValue('S1', 'OT ABIERTA');
				
	

	
$contador=2;
while($contador-2 < count($resultado['datos']))
{
	//echo "numNoFactura: ".$resultado['datos'][$contador-2]["otAbierta"];//->format('d/m/Y');
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$contador, $resultado['datos'][$contador-2]["presupuesto"])
			->setCellValue('B'.$contador, $resultado['datos'][$contador-2]["letra"])
            ->setCellValue('C'.$contador, $resultado['datos'][$contador-2]["cliente"])
			->setCellValue('D'.$contador, $resultado['datos'][$contador-2]["notaCibeles"])
			->setCellValue('E'.$contador, $resultado['datos'][$contador-2]["campana"])
			->setCellValue('F'.$contador, $resultado['datos'][$contador-2]["campana2"])
			->setCellValue('G'.$contador, $resultado['datos'][$contador-2]["fecha"])
			->setCellValue('H'.$contador, $resultado['datos'][$contador-2]["fechaAceptacion"])
			->setCellValue('I'.$contador, $resultado['datos'][$contador-2]["fechaCompromiso"])
			->setCellValue('J'.$contador, $resultado['datos'][$contador-2]["fechaTerminado"])
			->setCellValue('K'.$contador, $resultado['datos'][$contador-2]["fechaInicioReal"])
			->setCellValue('L'.$contador, $resultado['datos'][$contador-2]["cantidad"])
			->setCellValue('M'.$contador, $resultado['datos'][$contador-2]["cantidad2"])
			->setCellValue('N'.$contador, $resultado['datos'][$contador-2]["clayma"])
			->setCellValue('O'.$contador, $resultado['datos'][$contador-2]["numNoFactura"])
			->setCellValue('P'.$contador, $resultado['datos'][$contador-2]["noSeFacturaObservaciones"])
			->setCellValue('Q'.$contador, $resultado['datos'][$contador-2]["numeroFacturaCompleto"])			
			->setCellValue('R'.$contador, $resultado['datos'][$contador-2]["otBajada"])
			->setCellValue('S'.$contador, $resultado['datos'][$contador-2]["otAbierta"]);
			
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

$nombreArchivo = 'Presupuestos'.date('d/m/Y').'.xlsx';
	
if (ob_get_length()) {
    ob_end_clean();
}

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
	
	
	
	
	
		


