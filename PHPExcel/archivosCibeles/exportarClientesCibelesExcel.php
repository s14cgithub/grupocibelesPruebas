<?php

//require("../../../../comprobarSesion.php");

if(isset($_POST["exportarAccion"]) && $_POST["exportarAccion"]=="exportarExcel")
{
	$ruta = '../../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	parse_str($_POST["exportarCondiciones"], $datos);
	
	$clayma = isset($_POST["exportarClayma"]) ? $_POST["exportarClayma"] : 0;
	$campos = isset($datos["campos"]) ? json_decode($datos["campos"], true) : array();
	$filtros = isset($datos["filtros"]) ? json_decode($datos["filtros"], true) : array();
	$filtrosOperadores = isset($datos["filtrosOperadores"]) ? json_decode($datos["filtrosOperadores"], true) : array();
	$filtrosLike = isset($datos["filtrosLike"]) ? json_decode($datos["filtrosLike"], true) : array();
	$joins = isset($datos["joins"]) ? json_decode($datos["joins"], true) : array();
	$order = isset($datos["order"]) ? json_decode($datos["order"], true) : array();


	$campos[] = "importePF";
	$campos[] = "fac_pfFijaImporte";



	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	
	
	if ($clayma == 1 || $clayma == "1" )
	{
		$resultado = cargarClientesClayma($conn,$bbddSql, $campos, $filtros,$filtrosOperadores, $order,$joins,$filtrosLike);
		$nombreArchivo = 'ClientesClayma_'.date('d-m-Y').'.xlsx';
	}
	else
	{
		$resultado = cargarClientes($conn,$bbddSql, $campos, $filtros,$filtrosOperadores, $order,$joins,$filtrosLike);
		$nombreArchivo = 'ClientesCibeles_'.date('d-m-Y').'.xlsx';
	}
	
	//echo $cargarClientes['sql'];
	sqlsrv_close($conn);
	
	
	//echo json_encode($resultado);
	//echo mostrarOt($conexion,$condicion);
	
	
	
	
	//echo $resultado;
	
	
	
	
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
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);


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


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CODIGO')
	 		->setCellValue('B1', 'CLIENTE')
            ->setCellValue('C1', 'FRANQUEO')
            ->setCellValue('D1', 'DIRECCION')
            ->setCellValue('E1', 'CP')
			->setCellValue('F1', 'LOCALIDAD')
	->setCellValue('G1', 'SALDO')
	->setCellValue('H1', 'PF-FIJO')
	;
			

	
$contador=2;
while($contador-2 < count($resultado["datos"]))
{
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$contador, $resultado["datos"][$contador-2]["codigo"])
			->setCellValue('B'.$contador, $resultado["datos"][$contador-2]["nombre_empresa"])
            ->setCellValue('C'.$contador, $resultado["datos"][$contador-2]["nombre_franqueo"])			
			->setCellValue('D'.$contador, $resultado["datos"][$contador-2]["direccion"])
			->setCellValue('E'.$contador, $resultado["datos"][$contador-2]["codigo_postal"])
			->setCellValue('F'.$contador, $resultado["datos"][$contador-2]["localidad"])
			->setCellValue('G'.$contador, $resultado["datos"][$contador-2]["importePF"])
			->setCellValue('H'.$contador, $resultado["datos"][$contador-2]["fac_pfFijaImporte"])
			;
			
	
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
	
	
	
	
	
		


