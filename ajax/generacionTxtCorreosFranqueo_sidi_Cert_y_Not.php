

<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="generacionTxtCorreosFranqueo")
{
	
	
	
	
	//echo ("\nentra");
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$idProducto = $_POST["idProducto"];
	$modo = $_POST["modo"];
	
	
	
	/////////////////////////
	
	$sidi_codigoClienteCorreos = "81151061";
	$sidi_version = "2018v002";
	//$sidi_numeroContrato = "81000293";//sidi_pre
	$sidi_numeroContrato = "81000022";
	$sidi_numeroMaquina = "MV007468";
	//$sidi_oficinaElegida = "2812096"; //solo para pre
	$sidi_oficinaElegida = "2841594"; //solo para pro
	
	$sidi_codigoNuestroCliente = "";
	
	$anioActual = date('Y');
	$mesActual = date('m');
	$diaActual = date('d');
	
	$horaActual = date ('h');
	$minutosActual = date('i');
	$segundosActual = date('s');
	
	$tabulador = "\t";
	
	
	//C:\xampp\htdocs\gestionGrupocibelesPreproduccion\archivosDescargas\importacionCertificados
	$rutaCarpeta="../archivosDescargas/importacionCertificados/";
	$rutaCarpetaSession = "archivosDescargas/importacionCertificados/";
	
	//se borrar todo el contenido de la carpeta
	/*$files = glob($rutaCarpeta.'*'); //obtenemos todos los nombres de los ficheros	
	foreach($files as $file){
		if(is_file($file))
		unlink($file); //elimino el fichero
	}*/
	
	$nombreFichero = "FD".$sidi_codigoClienteCorreos.$anioActual.$mesActual.$diaActual.$horaActual.$minutosActual.$segundosActual.".txt";
	$fp = fopen($rutaCarpeta.$nombreFichero, "w");
	
	
	$posicion="";
	
	if ($modo==0)
	{
		$datosTipos = mostrarDatosParaExportarAlbaranesCorreosFranqueoSIDI($conexion,$idProducto);
	}
	else if ($modo==1)
	{
		$datosTipos = mostrarDatosParaExportarAlbaranesCorreosFranqueoPostF12SIDI($conexion,$idProducto);
	}
	
	for ($cont=0 ; $cont < count($datosTipos) ; $cont++)
	{
		$peso="";
		$linea = "";
		
		if ($cont==0 && count($datosTipos)==1)
		{
			$posicion="U";
		}
		else if ($cont==0 && count($datosTipos)>1)
		{
			$posicion = "C";
		}
		else if ($cont == count($datosTipos) - 1 && count($datosTipos >1))
		{
			$posicion = "F";
		}
		else
		{
			$posicion = "R";
		}
		
		$linea=$posicion;
		
		
		$linea = $linea . $tabulador . $sidi_version . $tabulador . $datosTipos[$cont]["idSIDI"] . $tabulador; //1, 2, 3
		
		$linea = $linea . "FM" . $tabulador; //4;  
		$linea = $linea . $tabulador;//5
		
		
		
			
		$linea = $linea . $sidi_numeroContrato . $tabulador . $datosTipos[$cont]["codigoSidi"] . $tabulador;//6 y 7
		
		$linea = $linea . $sidi_numeroMaquina . $tabulador; // 8
		
		$linea = $linea . $datosTipos[$cont]["importeSidi"] . $tabulador; // 9
		
		$linea = $linea . $datosTipos[$cont]["numSeguimiento"] . $tabulador; // 10
		
		$linea = $linea . $tabulador . $tabulador . $tabulador; // 11, 12, 13
		
		$linea = $linea . "1" . $tabulador . "1" . $tabulador; //14 y 15   total de bultos y numero de bulto
		
		$linea = $linea . $tabulador . $tabulador . $tabulador; //16, 17, 18
		
		$linea = $linea . $datosTipos[$cont]["nombre"] . $tabulador; // 19 nombre del destinatario
		
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador; //del 20 al 25
		
		$linea = $linea . $datosTipos[$cont]["direccion"] . $tabulador; // 26  direccion
		
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador; // del 27 al 33
		
		$linea = $linea . $datosTipos[$cont]["poblacion"] . $tabulador; // 34 localidad
		
		$linea = $linea . $tabulador; // 35
		
		$linea = $linea . $datosTipos[$cont]["cp"] . $tabulador; //36 codigo postal solo nacional y andorra; siempre 5 digitos
		
		$linea = $linea . $tabulador;//37 

		$linea = $linea . "ES" . $tabulador;//38 pais. Obligatorio
		
		$linea = $linea . $sidi_oficinaElegida .  $tabulador; //39 
			
		//$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador; // del 40 al 45
		
		$linea = $linea . $tabulador . $tabulador;// del 40 al 41
		
		
		
		$linea = $linea . $tabulador . "correos@grupocibeles.es". $tabulador . $tabulador; //42 - 44;   42: telefono destinatario; 43: email destinatario
		
		$linea = $linea . $tabulador;// 45
		
		
		
		
		$linea = $linea . $datosTipos[$cont]["ot"] . $tabulador; // 46 referencia - nuestro OT

		$linea = $linea . $datosTipos[$cont]["otSidi"] . $tabulador; // 47 referencia - OT de sidi
		
		$linea = $linea . $tabulador . $tabulador; //del 48 al 49
		
		$linea = $linea . $datosTipos[$cont]["gramos"] . $tabulador; // 50 peso
		
		$linea = $linea . $tabulador . $tabulador  . $tabulador  . $tabulador  . $tabulador  . $tabulador ; // 51 al 56 
		


		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador ; // 57 al 66 

		
		$valorFormatoEntrega="0";
		
		if ($datosTipos[$cont]["anadidos2"] == "AcuseRecibo")
		{
			$valorFormatoEntrega="2";
		}
		else if ($datosTipos[$cont]["anadidos2"] == "PEE") //5años
		{
			$valorFormatoEntrega="3";
		}
		
		$linea = $linea . $valorFormatoEntrega . $tabulador; //67 - Formato de Prueba entrega
		
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador;
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador;
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador;
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador;
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador;
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador;
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador;
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador;
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador;
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador; //68 al 165
		
		
		$linea = $linea . $datosTipos[$cont]["nombreEmpresa"] . $tabulador;//166 - nombre del remite
		
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador; // 167 al 172		
		
		$linea = $linea . $datosTipos[$cont]["direccion"] . $tabulador; // 173  direccion remite
		
		$linea = $linea . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador . $tabulador; //174 as 179
		
		$linea = $linea . $datosTipos[$cont]["localidad"] . $tabulador; // 180  localidad remite
		$linea = $linea . $datosTipos[$cont]["provincia"] . $tabulador; // 181  provincia remite
		$linea = $linea . $datosTipos[$cont]["codigo_postal"] . $tabulador; // 182  cp remite
		
		$linea = $linea . $tabulador . $tabulador; //183 al 184
			
		$linea = $linea . "" . $tabulador; //185 telefono remite
		
		$linea = $linea . "" . $tabulador; //186 email remite
		
		$linea = $linea . $tabulador . $tabulador; //187 al 188
		
		$linea = $linea . "E";
		
		
			 
			 
		fwrite($fp,$linea);
		fwrite($fp,PHP_EOL);
		
		
	}
	fclose($fp);
	
	
	
	
			
	echo ($rutaCarpetaSession.$nombreFichero);
		
		
		
	
	/*else
	{
		borrarDatosFranqueoExportar($conexion);
		echo ("Error: No hay ningun registro para generar el TXT");
	}*/	
	
	
	
	
	
}


?>