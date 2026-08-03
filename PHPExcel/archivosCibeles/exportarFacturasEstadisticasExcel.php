<?php

ob_start();

//require("../../../../comprobarSesion.php");

if(isset($_POST["exportarAccion"]) && $_POST["exportarAccion"]=="exportarExcel")
{
	$ruta = '../../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$anio = $_POST["anioFacturaEstdExcel"];	
	$orden = $_POST["ordenFacturaEstdExcel"];
	
	$origen = $_POST["origenFacturaEstdExcel"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$camposOrdenPermitidos = array('nombre_empresa', 'codigo_saldo', 'franqueo', 'manipulado', 'mediaFranqueo', 'mediaManipulado', 'numFacturasCorreos', 'numFacManipulado');
	$ordenPartes = explode(' ', trim($orden));
	$campoOrden = in_array($ordenPartes[0], $camposOrdenPermitidos) ? $ordenPartes[0] : 'nombre_empresa';
	$dirOrden = (isset($ordenPartes[1]) && strtolower($ordenPartes[1]) == 'desc') ? 'DESC' : 'ASC';

	$filtrosOperadoresAnio = [
		['campo1' => 'fecha', 'valor' => $anio.'-01-01', 'operador' => '>='],
		['campo1' => 'fecha', 'valor' => $anio.'-12-31', 'operador' => '<=']
	];

	$stats = array();

	if ($origen=="Cibeles")
	{
		$resClientes = cargarClientes($conn, $bbddSql, ['codigo_saldo','nombre_empresa'], [], [['campo1'=>'codigo_saldo','campo2'=>'codigo','operador'=>'=']], []);
		$resFacturas = mostrarFacturacion($conn, $bbddSql, ['codigo_saldo','precioNeto'], ['tabla2'], [], $filtrosOperadoresAnio, []);
		$resCorreos = mostrarFacturacionCorreos($conn, $bbddSql, ['codigo_saldo','neto'], ['tabla2'], [], $filtrosOperadoresAnio, []);
	}
	else
	{
		$resClientes = cargarClientesClayma($conn, $bbddSql, ['codigo_saldo','nombre_empresa'], [], [['campo1'=>'codigo_saldo','campo2'=>'codigo','operador'=>'=']], []);
		$resFacturas = mostrarFacturacionClayma($conn, $bbddSql, ['codigo_saldo','precioNeto'], ['tabla2'], [], $filtrosOperadoresAnio, []);
		$resCorreos = array('datos' => array());
	}

	$nombresPorCliente = array();
	foreach ($resClientes['datos'] as $c)
	{
		$nombresPorCliente[$c['codigo_saldo']] = $c['nombre_empresa'];
	}

	foreach ($resFacturas['datos'] as $f)
	{
		$codigo = $f['codigo_saldo'];
		if (!isset($stats[$codigo]))
		{
			$stats[$codigo] = array('manipulado'=>0,'numFacManipulado'=>0,'franqueo'=>0,'numFacturasCorreos'=>0);
		}
		$stats[$codigo]['manipulado'] += $f['precioNeto'];
		$stats[$codigo]['numFacManipulado']++;
	}

	foreach ($resCorreos['datos'] as $f)
	{
		$codigo = $f['codigo_saldo'];
		if (!isset($stats[$codigo]))
		{
			$stats[$codigo] = array('manipulado'=>0,'numFacManipulado'=>0,'franqueo'=>0,'numFacturasCorreos'=>0);
		}
		$stats[$codigo]['franqueo'] += $f['neto'];
		$stats[$codigo]['numFacturasCorreos']++;
	}

	$resultado = array();
	foreach ($stats as $codigo => $s)
	{
		$resultado[] = array(
			'codigo_saldo' => $codigo,
			'nombre_empresa' => isset($nombresPorCliente[$codigo]) ? $nombresPorCliente[$codigo] : '',
			'manipulado' => $s['manipulado'],
			'numFacManipulado' => $s['numFacManipulado'],
			'franqueo' => $s['franqueo'],
			'numFacturasCorreos' => $s['numFacturasCorreos'],
			'mediaManipulado' => $s['numFacManipulado']>0 ? $s['manipulado']/$s['numFacManipulado'] : 0,
			'mediaFranqueo' => $s['numFacturasCorreos']>0 ? $s['franqueo']/$s['numFacturasCorreos'] : 0
		);
	}

	usort($resultado, function($a,$b) use ($campoOrden,$dirOrden) {
		if ($a[$campoOrden] == $b[$campoOrden]) return 0;
		$comparacion = ($a[$campoOrden] < $b[$campoOrden]) ? -1 : 1;
		return ($dirOrden == 'DESC') ? -$comparacion : $comparacion;
	});
	
	
	
	
	
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


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CODIGO')
	 		->setCellValue('B1', 'EMPRESA')
            ->setCellValue('C1', 'MANIPULADO')
            ->setCellValue('D1', 'FRANQUEO')
            ->setCellValue('E1', 'NUM FAC MANIPULADO')
			->setCellValue('F1', 'NUM FAC FRANQUEO')	
			->setCellValue('G1', 'MEDIA MANIPULADO')		
			->setCellValue('H1', 'MEDIA FRANQUEO');

	
$contador=2;
while($contador-2 < count($resultado))
{
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$contador, $resultado[$contador-2]["codigo_saldo"])
			->setCellValue('B'.$contador, $resultado[$contador-2]["nombre_empresa"])
            ->setCellValue('C'.$contador, $resultado[$contador-2]["manipulado"])
			->setCellValue('D'.$contador, $resultado[$contador-2]["franqueo"])
			->setCellValue('E'.$contador, $resultado[$contador-2]["numFacManipulado"])
			->setCellValue('F'.$contador, $resultado[$contador-2]["numFacturasCorreos"])
			->setCellValue('G'.$contador, $resultado[$contador-2]["mediaManipulado"])
			->setCellValue('H'.$contador, $resultado[$contador-2]["mediaFranqueo"]);
			
	
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

$nombreArchivo = 'Estadisticas'.date('d-m-Y').'.xlsx';

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
	
	
	
	
	
		


