<?php

//require("../../../../comprobarSesion.php");

if(isset($_POST["exportarAccion"]) && $_POST["exportarAccion"]=="exportarExcel")
{
	$ruta = '../../';	
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	session_start(); 

	$idProducto = $_POST["exportarSidiExcel_idProducto"];
	$modo = $_POST["exportarSidiExcel_modo"];

	$sidi_codigoClienteCorreos = "81151061";
	$sidi_version = "2018v002";
	//$sidi_numeroContrato = "81000293";//sidi_pre
	$sidi_numeroContrato = "81000022";
	$sidi_numeroMaquina = "MV007468";
	//$sidi_oficinaElegida = "2812096"; //solo para pre
	$sidi_oficinaElegida = "2841594"; //solo para pro
	
	$sidi_codigoNuestroCliente = "";
	
	//$idProducto =3;
	//$modo = 0;

	$posicion="";
	

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$fechaActual = date('d-m-Y');

	if ($modo==0)
	{
		$campos = [
			'idSIDI',
			'codigoSidi',
			'importeSidi',
			'numSeguimiento',
			'nombre',
			'direccion',
			'poblacion',
			'cp',
			'ot',
			'otSidi',
			'numSeguimiento',
			'gramos_SIDI',
			'nombre_empresa',
			'direccionCliente',
			'localidadCliente',
			'provinciaCliente',
			'cpCliente'
		];

		$joins = [
			'tabla3',
			'tabla6',
			'tabla7',
			'tabla2',
			'tabla8',
			'tabla9'
		];

		$filtros = [
			'fecha' => $fechaActual,
			'comprobado' => 0,
			'idProductoPadre' => $idProducto
		];

		$filtrosOperadores = array();
		$group = array();
		$order = array();

		$datosTipos = cargarFranqueoTipos($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $group, $order);
		
	}
	else if ($modo == 1)
	{
		$campos = [
			'idSIDI',
			'codigoSidi',
			'importeSidi',
			'numSeguimiento',
			'nombre',
			'direccion',
			'poblacion',
			'cp',
			'ot',
			'otSidi',
			'numSeguimiento',
			'gramos_SIDI',
			'nombre_empresa',
			'direccionCliente',
			'localidadCliente',
			'provinciaCliente',
			'cpCliente'
		];

		$joins = [
			'tabla3',
			'tabla6',
			'tabla7',
			'tabla2',
			'tabla8'			
		];

		$filtros = [
			'fecha' => $fechaActual,
			'comprobado' => 1,
			'importado' => 1,
			'idProductoPadre' => $idProducto
		];

		$filtrosOperadores = array();
		$group = array();
		$order = array();

		$datosTipos = cargarFranqueoTipos($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $group, $order);
		
	}
	sqlsrv_close($conn);
	$nombreArchivo = 'Franqueo'.date('d-m-Y').'.xlsx';


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
$sheet = $objPHPExcel->getActiveSheet();

// Set document properties
$objPHPExcel->getProperties()->setCreator("")
							 ->setLastModifiedBy("")
							 ->setTitle("Office 2007 XLSX")
							 ->setSubject("Office 2007 XLSX")
							 ->setDescription("Office 2007 XLSX")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("result file");


	$sheet->setCellValue('A1', count($datosTipos["datos"]));//1 posicion
	for ($cont=0 ; $cont < count($datosTipos["datos"]) ; $cont++)
	{
		
		if ($cont==0 && count($datosTipos["datos"]) == 1)
		{
			$posicion="U";
		}
		else if ($cont==0 && count($datosTipos["datos"]) > 1)
		{
			$posicion = "C";
		}
		else if ($cont == count($datosTipos["datos"]) - 1 && count($datosTipos["datos"]) > 1)
		{
			$posicion = "F";
		}
		else
		{
			$posicion = "R";
		}
		
		$valorFormatoEntrega="0";
		
		if ($datosTipos["datos"][$cont]["anadidos2"] == "AcuseRecibo")
		{
			$valorFormatoEntrega="2";
		}
		else if ($datosTipos["datos"][$cont]["anadidos2"] == "PEE") //5años
		{
			$valorFormatoEntrega="3";
		}

		$contador = $cont + 1;
		//$sheet->setCellValue('A1', 'Hola Mundo');

		//$sheet->setCellValue('A1', "aaaa");//1 posicion
		
		
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$contador, $posicion)//1 posicion
			->setCellValue('B'.$contador, $sidi_version)//2 version
            ->setCellValue('C'.$contador, $datosTipos["datos"][$cont]["idSIDI"]) //3 codigo producto		
			->setCellValue('D'.$contador, "FM")//4 tipo de franqueo			
			->setCellValue('F'.$contador, $sidi_numeroContrato) //6 numero de contrato
			->setCellValue('G'.$contador, $datosTipos["datos"][$cont]["codigoSidi"])//7 codigo cliente
			->setCellValue('H'.$contador, $sidi_numeroMaquina)//8 numero de maquina
			->setCellValue('I'.$contador, $datosTipos["datos"][$cont]["importeSidi"] )//9 importe
			->setCellValue('J'.$contador, $datosTipos["datos"][$cont]["numSeguimiento"])//10 numero de seguimiento nuestro
			->setCellValue('N'.$contador, "1" )//14 total de bultos
			->setCellValue('O'.$contador, "1" )//15 numero de bultos
			->setCellValue('S'.$contador, $datosTipos["datos"][$cont]["nombre"])// 19 nombre del destinatario
			->setCellValue('Z'.$contador, $datosTipos["datos"][$cont]["direccion"] ) // 26  direccion
			->setCellValue('AH'.$contador, $datosTipos["datos"][$cont]["poblacion"])// 34 localidad
			->setCellValue('AJ'.$contador, $datosTipos["datos"][$cont]["cp"] )//36 codigo postal solo nacional y andorra; siempre 5 digitos
			->setCellValue('AL'.$contador, "ES")//38 pais. Obligatorio
			->setCellValue('AM'.$contador, $sidi_oficinaElegida )//39 Oficina Elegida
			->setCellValue('AQ'.$contador, "correos@grupocibeles.es")//43: email destinatario
			->setCellValue('AT'.$contador, $datosTipos["datos"][$cont]["ot"])// 46 referencia - nuestro OT
			->setCellValue('AU'.$contador, $datosTipos["datos"][$cont]["otSidi"]) // 47 referencia - OT de sidi
			->setCellValue('AV'.$contador, $datosTipos["datos"][$cont]["numSeguimiento"] )//48 referencias  - nuestro numero de seguimiento
			->setCellValue('AX'.$contador, $datosTipos["datos"][$cont]["gramos_SIDI"] ) // 50 peso
			->setCellValue('BO'.$contador, $valorFormatoEntrega)//67 - Formato de Prueba entrega
			->setCellValue('FJ'.$contador, $datosTipos["datos"][$cont]["nombre_empresa"])//166 - nombre del remite
			->setCellValue('FQ'.$contador, $datosTipos["datos"][$cont]["direccionCliente"])// 173  direccion remite
			->setCellValue('FX'.$contador, $datosTipos["datos"][$cont]["localidadCliente"])// 180  localidad remite
			->setCellValue('FY'.$contador, $datosTipos["datos"][$cont]["provinciaCliente"])// 181  provincia remite
			->setCellValue('FZ'.$contador, $datosTipos["datos"][$cont]["cpCliente"]) // 182  cp remite
			->setCellValue('GC'.$contador, "........" ) //185 telefono remite			
			->setCellValue('GG'.$contador,  "E");//189 fin

	}

	
	


	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Simple');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	while (ob_get_level()) {
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
	//$objWriter->save(__DIR__ . '/prueba.xlsx');
	
	exit;
}


?>
	
	
	
	
	
		


