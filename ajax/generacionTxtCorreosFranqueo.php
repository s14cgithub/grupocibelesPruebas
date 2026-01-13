

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
	
	
	$uno="";
	$dos="";
	$tres="";
	$cuatro="";
	$cinco="";
	$seis="";
	$siete="";
	$referencia="";
	$idUnico=0;
	$orden="";
	
	$rutaCarpeta="../archivosDescargas/albaranesTXTcorreos/";
	$rutaCarpetaSession = "archivosDescargas/albaranesTXTcorreos/";
	
	//se borrar todo el contenido de la carpeta
	$files = glob($rutaCarpeta.'_pec.txt'); //obtenemos todos los nombres de los ficheros	
	foreach($files as $file){
		if(is_file($file))
		unlink($file); //elimino el fichero
	}
	
	//unlink("../archivosDescargas/exportarAlbaranquesCorreosFranqueo.txt");
	
	borrarDatosFranqueoExportar($conexion);
	
	
	
	
	if ($modo==0)
	{
		$datosTipos = mostrarDatosParaExportarAlbaranesCorreosFranqueo($conexion,$idProducto);
	}
	else if ($modo==1)
	{
		$datosTipos = mostrarDatosParaExportarAlbaranesCorreosFranqueoPostF12($conexion,$idProducto);
	}
	
	if (count($datosTipos)>0)
	{
		$nombreProducto = $datosTipos[0]["producto"];
		//echo ("\nentra2");
		//INICIO DEL RESUMEN
		
		/*$uno="0";
		$dos=$datosTipos[0]["idAnexo"];
		$tres=$datosTipos[0]["idProducto"];
		$orden="1";
		
		insertarDatosFranqueoExportar($conexion,$uno, $dos,$tres,$cuatro,$cinco,$seis,$siete,$referencia,$idUnico,$orden);*/
		
		$referenciaAnterior="asdfjk38234";
		$primeraVez=true;
		//foreach ($datosTipos as $row) 
		$contador=0;
		$internacional="";
		$internacionalAnterior="";
		
		$laReferenciaAnterior = "asdfkjk3238493asdfjaskdfj238492384";
		
		while ($contador<count($datosTipos))
		{
			
			if ($laReferenciaAnterior != $datosTipos[$contador]["referencia"])
			{
				
				cambiarValorTxtEnFranqueo($conexion,$datosTipos[$contador]["referencia"]);
				$laReferenciaAnterior = $datosTipos[$contador]["referencia"];
			}
			
			cambiarValorTxtEnFranqueoTipo($conexion,$datosTipos[$contador]["id"]);
			
			
			if ($datosTipos[$contador]["ambito"]=="4" || $datosTipos[$contador]["ambito"]=="5" ||  $datosTipos[$contador]["ambito"]=="6" || $datosTipos[$contador]["ambito"]=="7" || $datosTipos[$contador]["ambito"]=="8" || $datosTipos[$contador]["ambito"]=="9" || $datosTipos[$contador]["ambito"]=="10" || $datosTipos[$contador]["ambito"]=="11" || $datosTipos[$contador]["ambito"]=="12" || $datosTipos[$contador]["ambito"]=="13" || $datosTipos[$contador]["ambito"]=="14" || $datosTipos[$contador]["ambito"]=="15")
			{
				$internacional="1";//internacional
			}
			else
			{
				$internacional="0";//nacional
			}
			
			
			
			$clasificacion="";
			
			if ($datosTipos[$contador]["ambito"]=="91" || $datosTipos[$contador]["ambito"]=="92" ||  $datosTipos[$contador]["ambito"]=="1")
			{
				if ($datosTipos[$contador]["producto"]=="ORDINARIO")
				{
					$clasificacion=2;
				}
				else if ($datosTipos[$contador]["producto"]=="CERTIFICADOS" || $datosTipos[$contador]["producto"]=="NOTIFICACIONES")
				{
					$clasificacion=3;
				}
				else
				{
					$clasificacion=1;
				}
			}
			else
			{
				$clasificacion=0;
			}
			
			
			
			
			
			
			
			
			
			
			
			
			/*$referencia2Actual=$datosTipos[$contador]["referencia"].$internacional;
			
			$referencia2Anterior= $referenciaAnterior.$internacional;
			
			//echo "\nActual: ".$referencia2Actual;
			//echo "\nAnterior: ".$referencia2Anterior;
			//echo "\n";
			
			if ($referencia2Actual != $referencia2Anterior)
			{
				if($primeraVez==true)
				{					
					$primeraVez=false;
				}
				else
				{
					//introducir cierre de albaran
					$uno="-1";
					$dos=$datosTipos[$contador-1]["referencia"];
					$tres=$datosTipos[$contador-1]["idCliente"];
					$cuatro="DIMV007468";
					$cinco="";
					$seis="";
					$siete="";
					$referencia=$datosTipos[$contador-1]["referencia"].$internacional;
					$idUnico=99999999;
					$orden="99999999";
					insertarDatosFranqueoExportar($conexion,$uno, $dos,$tres,$cuatro,$cinco,$seis,$siete,$referencia,$idUnico,$orden);
				}
				$referenciaAnterior = $datosTipos[$contador]["referencia"];
				
				//introducir inicio de albaran
				$uno="1";
				$dos=$datosTipos[$contador]["referencia"];
				$tres=$datosTipos[$contador]["idCliente"];
				$cuatro="DIMV007468";
				$cinco=$internacional;
				$seis=$datosTipos[$contador]["ot"];
				$siete="";
				$referencia=$datosTipos[$contador]["referencia"].$internacional;
				$idUnico=0;
				$orden="2";
				insertarDatosFranqueoExportar($conexion,$uno, $dos,$tres,$cuatro,$cinco,$seis,$siete,$referencia,$idUnico,$orden);
				
			}*/
			
			
			if ($datosTipos[$contador]["anadidos"]!= null && $datosTipos[$contador]["anadidos"]!="null" && $datosTipos[$contador]["anadidos"]!="")//Son añadidos
			{
				$uno="4";
				$dos=$datosTipos[$contador]["anadidos"];
				$tres="";
				$cuatro="";
				$cinco="";
				$seis="";
				$siete="";
				$referencia=$datosTipos[$contador]["referencia"].$internacionalAnterior;
				$idUnico=$datosTipos[$contador]["id"];
				$orden="5";
				
			}
			else //No son añadidos
			{
				
				$uno="3";
				$dos=$datosTipos[$contador]["gramos"];
				$tres=$datosTipos[$contador]["normalizado"];
				$cuatro="";
				$cinco=$datosTipos[$contador]["ambito"];
				$seis=$clasificacion;
				$siete=$datosTipos[$contador]["unidades"];
				$referencia=$datosTipos[$contador]["referencia"].$internacional;
				$idUnico=$datosTipos[$contador]["id"];
				$orden="4";
				
			}
		
			insertarDatosFranqueoExportar($conexion,$uno, $dos,$tres,$cuatro,$cinco,$seis,$siete,$referencia,$idUnico,$orden);
		
			$contador++;
			$internacionalAnterior = $internacional;
		}
		
		
		/*$ultimo = count($datosTipos)-1;
		$uno="-1";
		$dos=$datosTipos[$ultimo]["referencia"];
		$tres=$datosTipos[$ultimo]["idCliente"];
		$cuatro="DIMV007468";
		$cinco="";
		$seis="";
		$siete="";
		$referencia=$datosTipos[$ultimo]["referencia"].$internacional;
		$idUnico=99999999;
		$orden="99999999";
		insertarDatosFranqueoExportar($conexion,$uno, $dos,$tres,$cuatro,$cinco,$seis,$siete,$referencia,$idUnico,$orden);*/
		
		
		
		//se lee las referencias recien guardadas y se introducicen las cabeceras y las terminaciones de cada albaran
		$datosTipos2 = mostrarDatosAlbaranesFranqueo($conexion);
		$referenciaAnterior = "asdfjaskdñfkljasdf";//pruebas
		$primeraVez = true;
		$contador =0;
		while ($contador<count($datosTipos2))
		{
			//echo ("\nContador: ".$contador."; referencia: ".$datosTipos2[$contador]["referencia"]."; refenrecia anterior: ".$referenciaAnterior);
			if ($datosTipos2[$contador]["referencia"] != $referenciaAnterior)
			{	
				if($primeraVez==true)
				{					
					$primeraVez=false;
				}
				else
				{
					$datos3 = "";
					$datos3 = mostrarDatosFranqueoPorReferencia($conexion,substr($datosTipos2[$contador-1]["referencia"], 0, -1));
					
					//introducir cierre de albaran
					$uno="-1";
					$dos=substr($datosTipos2[$contador-1]["referencia"],0,-1);
					$tres=$datos3[0]["idCliente"];
					$cuatro="DIMV007468";
					$cinco="";
					$seis="";
					$siete="";
					$referencia=$datosTipos2[$contador-1]["referencia"];
					$idUnico=99999999;
					$orden="99999999";
					insertarDatosFranqueoExportar($conexion,$uno, $dos,$tres,$cuatro,$cinco,$seis,$siete,$referencia,$idUnico,$orden);
				}
				$referenciaAnterior = $datosTipos2[$contador]["referencia"];
				$datos3 = "";
				//echo "\nrefe: ".substr($datosTipos2[$contador]["referencia"], 0, -1);
				$datos3 = mostrarDatosFranqueoPorReferencia($conexion,substr($datosTipos2[$contador]["referencia"], 0, -1));
				
				//introducir inicio de albaran
				$uno="1";
				$dos=substr($datosTipos2[$contador]["referencia"],0,-1);
				$tres=$datosTipos[$contador]["idCliente"];
				$cuatro="DIMV007468";
				$cinco=substr($datosTipos2[$contador]["referencia"],-1);
				
				if (substr($datos3[0]["ot"],0,2)=="OT")
				{
					$seis = substr($datos3[0]["ot"],0,22);
				}
				else
				{
					$seis = "";
				}
				
				
				$siete="";
				$referencia=$datosTipos2[$contador]["referencia"];
				$idUnico=0;
				$orden="2";
				insertarDatosFranqueoExportar($conexion,$uno, $dos,$tres,$cuatro,$cinco,$seis,$siete,$referencia,$idUnico,$orden);
				//echo ("\nContador2: ".$contador."; referencia: ".$datosTipos2[$contador]["referencia"]."; refenrecia anterior: ".$referenciaAnterior);
			}
			$contador++;
		}
		
		$ultimo = count($datosTipos2)-1;
		$datos3 = "";
		$datos3 = mostrarDatosFranqueoPorReferencia($conexion,substr($datosTipos2[$ultimo]["referencia"], 0, -1));
		
		
		
		$uno="-1";
		$dos=substr($datosTipos2[$ultimo]["referencia"],0,-1);
		$tres=$datos3[0]["idCliente"];
		$cuatro="DIMV007468";
		$cinco="";
		$seis="";
		$siete="";
		$referencia=$datosTipos2[$ultimo]["referencia"];
		$idUnico=99999999;
		$orden="99999999";
		insertarDatosFranqueoExportar($conexion,$uno, $dos,$tres,$cuatro,$cinco,$seis,$siete,$referencia,$idUnico,$orden);
		
		//INICIO DEL RESUMEN
		
		
		
		$cuatro="";
		$cinco="";
		$seis="";
		$siete="";
		$referencia="";
		$idUnico=0;
		
		$uno="0";
		$dos=$datosTipos[0]["idAnexo"];
		$tres=$datosTipos[0]["idProducto"];
		$orden="1";
		
		insertarDatosFranqueoExportar($conexion,$uno, $dos,$tres,$cuatro,$cinco,$seis,$siete,$referencia,$idUnico,$orden);
		
		
		
		
		
		//leer tabla y generarTxt
		$datosParaTxt = mostrarDatosAlbaranesFranqueo($conexion);
		
		$contadorFicheros=0;
		
		//echo ($rutaCarpeta.date('Y-m-d')."_".$nombreProducto.".txt");
		//$fp = fopen("../archivosDescargas/exportarAlbaranquesCorreosFranqueo.txt", "w");

		$delimitador = "\t";
		
		$fp=null;
		//////////////////////////
		
		if ($idProducto==6 || $idProducto==8 || $idProducto==9 || $idProducto==10 || $idProducto==11 || $idProducto==12 )// se crear un fichero por cada registro
		{
			$cabecera="";
			$nuevoTxt=true;
			foreach ($datosParaTxt as $row) 
			{
				if ($row["uno"]=="0")
				{
					$cabecera=$row["uno"].$delimitador.$row["dos"].$delimitador.$row["tres"];										
				}
				else if ($row["uno"]=="1")
				{
					$contadorFicheros++;
					$fp = fopen($rutaCarpeta.date('Y-m-d')."_".$nombreProducto."_".$contadorFicheros."_pec.txt", "w");
					//echo ("prueba");
					$nuevoTxt=false;
					
					fwrite($fp,$cabecera);
					
					
					fwrite($fp,PHP_EOL);
					fwrite($fp,$row["uno"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["dos"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["tres"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["cuatro"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["cinco"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["seis"]);
					fwrite($fp,$delimitador);
				}
				else if ($row["uno"]=="2")
				{
					fwrite($fp,PHP_EOL);
					fwrite($fp,$row["uno"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["dos"]);				
				}
				else if ($row["uno"]=="3")
				{
					fwrite($fp,PHP_EOL);
					fwrite($fp,$row["uno"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["dos"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["tres"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["cuatro"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["cinco"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["seis"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["siete"]);
				}
				else if ($row["uno"]=="4")
				{
					fwrite($fp,PHP_EOL);
					fwrite($fp,$row["uno"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["dos"]);
					fwrite($fp,$delimitador);							
				}
				else if ($row["uno"]=="-1")
				{
					fwrite($fp,PHP_EOL);
					fwrite($fp,$row["uno"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["dos"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["tres"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["cuatro"]);		
					
					fclose($fp);
				}
			}
		}
		else
		{
			$fp = fopen($rutaCarpeta.date('Y-m-d')."_".$nombreProducto."_pec.txt", "w");
			foreach ($datosParaTxt as $row) 
			{

				if ($row["uno"]=="0")
				{
					//fwrite($fp,PHP_EOL);
					fwrite($fp,$row["uno"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["dos"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["tres"]);
				}
				else if ($row["uno"]=="1")
				{
					fwrite($fp,PHP_EOL);
					fwrite($fp,$row["uno"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["dos"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["tres"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["cuatro"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["cinco"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["seis"]);
					fwrite($fp,$delimitador);
				}
				else if ($row["uno"]=="2")
				{
					fwrite($fp,PHP_EOL);
					fwrite($fp,$row["uno"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["dos"]);				
				}
				else if ($row["uno"]=="3")
				{
					fwrite($fp,PHP_EOL);
					fwrite($fp,$row["uno"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["dos"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["tres"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["cuatro"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["cinco"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["seis"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["siete"]);
				}
				else if ($row["uno"]=="4")
				{
					fwrite($fp,PHP_EOL);
					fwrite($fp,$row["uno"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["dos"]);
					fwrite($fp,$delimitador);							
				}
				else if ($row["uno"]=="-1")
				{
					fwrite($fp,PHP_EOL);
					fwrite($fp,$row["uno"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["dos"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["tres"]);
					fwrite($fp,$delimitador);
					fwrite($fp,$row["cuatro"]);						
				}



			}


			fclose($fp);
			
			echo ($rutaCarpetaSession.date('Y-m-d')."_".$nombreProducto."_pec.txt");
		}
		if ($contadorFicheros>0)
		{
			$zip = new ZipArchive();
			$zip->open($rutaCarpeta.date('Y-m-d')."_".$nombreProducto."_PEC.zip",ZipArchive::CREATE);
			
			$files = glob($rutaCarpeta.'*'); //obtenemos todos los nombres de los ficheros	
			foreach($files as $file){
				if(is_file($file))
				{
					//substr($file, -3, 1);
					$nombreFichero = substr($file, strrpos($file, '/')+1);
					$zip->addFile($file,$nombreFichero);
					//echo ("\n".$nombreFichero);
				}
					
					
			}
			$zip->close();
			
			echo ($rutaCarpetaSession.date('Y-m-d')."_".$nombreProducto."_PEC.zip");
			//echo ("\n".$_SESSION["rutaTXTalbaran"]);
			
		}
		
		
		
		///////////////////////////
		
		
		
		
		
		
		
		
		
		
		
		
	}
	else
	{
		borrarDatosFranqueoExportar($conexion);
		echo ("Error: No hay ningun registro para generar el TXT");
	}
	
	
	
	
	
	$internacional=0;
	
	
	
	
	
	
	
	
}


?>