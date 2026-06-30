<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="generacionTxtCorreosFranqueo")
{


	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	//$idProducto = $_POST["idProducto"];
	//$modo = $_POST["modo"];
	
	
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
	$sidi_numeroMaquina = "MV007468";
	$sidi_UAM = "2841594"; //oficina elegida
	
	$rutaCarpeta="../archivosDescargas/albaranesTXTcorreos/";
	$rutaCarpetaSession = "archivosDescargas/albaranesTXTcorreos/";

	
	
	//se borrar todo el contenido de la carpeta
	$files = glob($rutaCarpeta.'*'); //obtenemos todos los nombres de los ficheros	
	foreach($files as $file){
		if(is_file($file))
		unlink($file); //elimino el fichero
	}
	
	//unlink("../archivosDescargas/exportarAlbaranquesCorreosFranqueo.txt");
	
	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];


	eliminarFranqueoExportarCorreos($conn, $bbddSql);
	
	$campos = [
		'producto',
		'ambito_SIDI',
		'anadidos',
		'referencia',
		'id',
		'gramos_SIDI',
		'normalizado',
		'unidades',
		'codigoSidi',
		'idAnexo_SIDI',
		'idSIDI'
	];
	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();
	$modo = $filtros["modo"];
	$idProducto = $filtros["idProducto"];
	
	if ($modo==0)
	{//echo "entra en modo0";
		$datosTipos = mostrarDatosParaExportarAlbaranesCorreosFranqueo($conn, $bbddSql, $campos,$filtros);

		//echo json_encode($datosTipos);
		//exit;
	}
	else if ($modo==1)
	{
		$datosTipos = mostrarDatosParaExportarAlbaranesCorreosFranqueoPostF12($conn, $bbddSql, $campos,$filtros);
		
	}
	
	
	if (count($datosTipos["datos"])>0)
	{ //echo "encuentra algo";
		$nombreProducto = $datosTipos["datos"][0]["producto"];
		//echo ("\nentra2");
		//INICIO DEL RESUMEN
		
		/*$uno="0";
		$dos=$datosTipos["datos"][0]["idAnexo"];
		$tres=$datosTipos["datos"][0]["idProducto"];
		$orden="1";
		
		insertarDatosFranqueoExportar($conexion,$uno, $dos,$tres,$cuatro,$cinco,$seis,$siete,$referencia,$idUnico,$orden);*/
		
		//$referenciaAnterior="asdfjk38234";
		$primeraVez=true;
		//foreach ($datosTipos as $row) 
		$contador=0;
		$internacional="";
		$internacionalAnterior="";
		
		//$laReferenciaAnterior = "asdfkjk3238493asdfjaskdfj238492384";
		
		while ($contador<count($datosTipos["datos"]))
		{
			
			/*if ($laReferenciaAnterior != $datosTipos["datos"][$contador]["referencia"])
			{
				
				//cambiarValorTxtEnFranqueo($conexion,$datosTipos["datos"][$contador]["referencia"]);
				$laReferenciaAnterior = $datosTipos["datos"][$contador]["referencia"];
			}*/
			
			//cambiarValorTxtEnFranqueoTipo($conexion,$datosTipos["datos"][$contador]["id"]);


			if ($datosTipos["datos"][$contador]["ambito_SIDI"]=="11" || $datosTipos["datos"][$contador]["ambito_SIDI"]=="12" ||  $datosTipos["datos"][$contador]["ambito_SIDI"]=="13")
			{
				$internacional="1";//internacional
			}
			else
			{
				$internacional="0";//nacional
			}


			if ($nombreProducto=="LIBRO EXTRANJERO")
			{
				$internacional="1";
			}

			$clasificacion="";
			
			/*if ($datosTipos["datos"][$contador]["ambito_SIDI"]=="0002" || $datosTipos["datos"][$contador]["ambito_SIDI"]=="0003" ||  $datosTipos["datos"][$contador]["ambito_SIDI"]=="0001")
			{
				if ($datosTipos["datos"][$contador]["producto"]=="ORDINARIO")
				{
					$clasificacion="G2";//2
				}
				else if ($datosTipos["datos"][$contador]["producto"]=="CERTIFICADOS" || $datosTipos["datos"][$contador]["producto"]=="NOTIFICACIONES")
				{
					$clasificacion="G0";//3 VER CON MARTA - el valor 3 no existe
				}
				else
				{
					$clasificacion="G1";//1
				}
			}
			else
			{
				$clasificacion="G0";//0
			}*/

			$clasificacion="G0";

			/*if ($datosTipos["datos"][$contador]["ambito_SIDI"]=="0001" || $datosTipos["datos"][$contador]["ambito_SIDI"]=="1")
			{
				if ($datosTipos["datos"][$contador]["producto"]!="LIBRO")
				{
					$clasificacion="G1";
				}
				
			}*/
			
			
			
			
			
			
			
			if ($datosTipos["datos"][$contador]["anadidos"]!= null && $datosTipos["datos"][$contador]["anadidos"]!="null" && $datosTipos["datos"][$contador]["anadidos"]!="")//Son añadidos
			{
				$uno="4";
				$dos=$datosTipos["datos"][$contador]["anadidos"];
				$tres="";
				$cuatro="";
				$cinco="";
				$seis="";
				$siete="";
				$referencia=$datosTipos["datos"][$contador]["referencia"].$internacionalAnterior;
				$idUnico=$datosTipos["datos"][$contador]["id"];
				$orden="5";
				
			}
			else //No son añadidos
			{
				
				$uno="3";
				$dos=$datosTipos["datos"][$contador]["gramos_SIDI"];
				$tres=$datosTipos["datos"][$contador]["normalizado"];
				$cuatro="";
				$cinco=$datosTipos["datos"][$contador]["ambito_SIDI"];

				$numeroDigitos=0;
				if ($datosTipos["datos"][$contador]["producto"]=="ORDINARIO" || $datosTipos["datos"][$contador]["producto"]=="ORDINARIO EXTRANJERO") //SPU - DEPOSITOS: ORDINARIOS, ORDINARIO INTERNACIONAL, PAQUETE AZUL
				{
					$numeroDigitos = 8;

					while (strlen($cinco)<4)
					{
						$cinco = "0" . $cinco;
					}

				}
				else if ($datosTipos["datos"][$contador]["producto"]=="PUBLICORREO OPTIMO" || $datosTipos["datos"][$contador]["producto"]=="URGENTE" || $datosTipos["datos"][$contador]["producto"]=="PUBLICORREO PREMIUM" || $datosTipos["datos"][$contador]["producto"]=="LIBRO" || $datosTipos["datos"][$contador]["producto"]=="LIBRO EXTRANJERO" || $datosTipos["datos"][$contador]["producto"]=="PERIODICO" || $datosTipos["datos"][$contador]["producto"]=="PERIODICO EXTRANJERO" || $datosTipos["datos"][$contador]["producto"]=="" || $datosTipos["datos"][$contador]["producto"]==""  ) //NO SPU: CARTA URGENTE, CARTA URGENTE INTERNACIONAL, PUBLICORREO, LIBROS, PERIODICOS, PUBLICORREO OPTIMO, PUBLICORREO PREMIUM
				{
					$numeroDigitos = 5;
				}


				while (strlen($dos)<$numeroDigitos)
				{
					$dos = "0" . $dos;
				}

				$seis=$clasificacion;
				$siete=$datosTipos["datos"][$contador]["unidades"];
				$referencia=$datosTipos["datos"][$contador]["referencia"].$internacional;
				$idUnico=$datosTipos["datos"][$contador]["id"];
				$orden="4";
				
			}

			$datos2 = array(       
				'uno' => $uno,
				'dos' => $dos,
				'tres' => $tres,
				'cuatro' => $cuatro,
				'cinco' => $cinco,
				'seis' => $seis,
				'siete' => $siete,
				'referencia' => $referencia,
				'idUnico' => $idUnico,
				'orden' => $orden   
			); 

			insertarDatosFranqueoExportar($conn, $bbddSql, $datos2);

		
			$contador++;
			$internacionalAnterior = $internacional;
		}
		
		
		/*$ultimo = count($datosTipos)-1;
		$uno="-1";
		$dos=$datosTipos["datos"][$ultimo]["referencia"];
		$tres=$datosTipos["datos"][$ultimo]["idCliente"];
		$cuatro="DIMV007468";
		$cinco="";
		$seis="";
		$siete="";
		$referencia=$datosTipos["datos"][$ultimo]["referencia"].$internacional;
		$idUnico=99999999;
		$orden="99999999";
		insertarDatosFranqueoExportar($conexion,$uno, $dos,$tres,$cuatro,$cinco,$seis,$siete,$referencia,$idUnico,$orden);*/
		
		
		
		//se lee las referencias recien guardadas y se introducicen las cabeceras y las terminaciones de cada albaran
		
		$datos3 = [
			'referencia',
			'ot'
		];
		$joins3 = ['tabla2'];

		$filtros3 = array();
		$filtrosOperadores3 = array();

		$order3 = [
			['campo' => 'referencia', 'dir' => 'ASC'],
			['campo' => 'Idunico', 'dir' => 'ASC'],
			['campo' => 'orden', 'dir' => 'ASC']
		];

		$datosTipos2 = mostrarFranqueoExportarCorreos($conn, $bbddSql, $datos3, $joins3, $filtros3, $filtrosOperadores3, $order3);
				
		$referenciaAnterior = "asdfjaskdñfkljasdf";//pruebas
		$primeraVez = true;
		$contador =0;
		
		while ($contador<count($datosTipos2["datos"]))
		{
			//echo ("\nContador: ".$contador."; referencia: ".$datosTipos2["datos"][$contador]["referencia"]."; refenrecia anterior: ".$referenciaAnterior);
			if ($datosTipos2["datos"][$contador]["referencia"] != $referenciaAnterior)
			{	
				if($primeraVez==true)
				{					
					$primeraVez=false;
				}
				else
				{
					

					$datos4 = array();
					$datos4 = [
						'otSidi'
					];
					
					$joins4 = array();
					$joins4 = ['tabla2'];

					$filtros4 = array();
					
					$filtro4 = [
						'referencia' => substr($datosTipos2["datos"][$contador-1]["referencia"], 0, -1)
					];

					$filtrosOperadores4 = array();
					$order4 = array();

					$datosFranqueo = array();
					$datosFranqueo = cargarFranqueo($conn, $bbddSql, $datos4, $joins4, $filtros4, $filtrosOperadores4, $order4);

					
					
					//introducir cierre de albaran
					$uno="-1";
					$dos=$datosTipos2["datos"][$contador-1]["ot"];
					//$tres=$datosFranqueo[0]["idCliente"];
					$elCodigoSidi = $datosFranqueo[0]["codigoSidi"];
					if ($elCodigoSidi == null  || $elCodigoSidi == 'null'  || $elCodigoSidi == '')
					{
						$elCodigoSidi='';
					}
					$tres==$elCodigoSidi;
					//$tres = substr($tres,2);
					//$tres = "hola";
					$cuatro=$sidi_numeroMaquina;
					$cinco="";
					$seis="";
					$siete="";
					$referencia=$datosTipos2["datos"][$contador-1]["referencia"];
					$idUnico=99999999;
					$orden="99999999";

					$datos5 = array(       
						'uno' => $uno,
						'dos' => $dos,
						'tres' => $tres,
						'cuatro' => $cuatro,
						'cinco' => $cinco,
						'seis' => $seis,
						'siete' => $siete,
						'referencia' => $referencia,
						'idUnico' => $idUnico,
						'orden' => $orden   
					); 

					insertarDatosFranqueoExportar($conn, $bbddSql, $datos5);
				}

				$referenciaAnterior = $datosTipos2["datos"][$contador]["referencia"];


				
				$datos6 = array();
				$datos6 = [
					'otSidi'
				];
				
				$joins6 = array();
				$joins6 = ['tabla2'];

				$filtros6 = array();

				$filtro6 = [
					'referencia' => substr($datosTipos2["datos"][$contador]["referencia"], 0, -1)
				];

				$filtrosOperadores6 = array();
				$order6 = array();

				$datosFranqueo = array();
				//echo "\nrefe: ".substr($datosTipos2["datos"][$contador]["referencia"], 0, -1);
				$datosFranqueo = cargarFranqueo($conn, $bbddSql, $datos6, $joins6, $filtros6, $filtrosOperadores6, $order6);
				
				
				//introducir inicio de albaran
				$uno="1";
				$dos=$datosTipos2["datos"][$contador]["ot"];

				$elCodigoSidi = $datosTipos["datos"][$contador]["codigoSidi"]; 
				if ($elCodigoSidi == null  || $elCodigoSidi == 'null'  || $elCodigoSidi == '')
				{
					$elCodigoSidi='';
				}
				$tres=$elCodigoSidi; 
				//$tres = substr($tres,2);
				$cuatro=$sidi_numeroMaquina;
				$cinco=substr($datosTipos2["datos"][$contador]["referencia"],-1);//el ultimo digito de este campo de esta tabla, corresponde a si es nacional o no
				
				if (strlen($datosFranqueo["datos"][0]["otSidi"])<22)
				{
					$seis = $datosFranqueo["datos"][0]["otSidi"];
				}
				else if (substr($datosFranqueo["datos"][0]["otSidi"],0,2)=="OT") //METER EL CODIGO DE OT DE SIDI
				{
					$seis = substr($datosFranqueo["datos"][0]["otSidi"],0,22);
				}
				else
				{
					$seis = "";
				}
				
				
				$siete=$sidi_UAM; 
			
				$referencia=$datosTipos2["datos"][$contador]["referencia"];
				$idUnico=0;
				$orden="2";

				$datos7 = array(       
					'uno' => $uno,
					'dos' => $dos,
					'tres' => $tres,
					'cuatro' => $cuatro,
					'cinco' => $cinco,
					'seis' => $seis,
					'siete' => $siete,
					'referencia' => $referencia,
					'idUnico' => $idUnico,
					'orden' => $orden   
				); 

				insertarDatosFranqueoExportar($conn, $bbddSql, $datos7);
				//echo ("\nContador2: ".$contador."; referencia: ".$datosTipos2["datos"][$contador]["referencia"]."; refenrecia anterior: ".$referenciaAnterior);
			}
			$contador++;
		}
		
		$ultimo = count($datosTipos2["datos"])-1;

		$datos8 = array();
		$datos8 = [
			'codigoSidi'
		];
		
		$joins8 = array();
		$joins8 = ['tabla2'];

		$filtros8 = array();
		
		$filtro8 = [
			'referencia' => substr($datosTipos2["datos"][$ultimo]["referencia"], 0, -1)
		];

		$filtrosOperadores8 = array();
		$order8 = array();

		$datosFranqueo = array(); 
		$datosFranqueo = cargarFranqueo($conn, $bbddSql, $datos8, $joins8, $filtros8, $filtrosOperadores8, $order8);

		
		
		$uno="-1";
		$dos=($datosTipos2["datos"][$ultimo]["ot"]);
		//$tres=$datosFranqueo[0]["idCliente"];
		$elCodigoSidi = $datosFranqueo["datos"][0]["codigoSidi"];
		if ($elCodigoSidi == null  || $elCodigoSidi == 'null'  || $elCodigoSidi == '')
		{
			$elCodigoSidi='';
		}

		$tres==$elCodigoSidi;
		$cuatro=$sidi_numeroMaquina;
		$cinco="";
		$seis="";
		$siete="";
		$referencia=$datosTipos2["datos"][$ultimo]["referencia"];
		$idUnico=99999999;
		$orden="99999999";

		$datos9 = array(       
			'uno' => $uno,
			'dos' => $dos,
			'tres' => $tres,
			'cuatro' => $cuatro,
			'cinco' => $cinco,
			'seis' => $seis,
			'siete' => $siete,
			'referencia' => $referencia,
			'idUnico' => $idUnico,
			'orden' => $orden   
		); 

		insertarDatosFranqueoExportar($conn, $bbddSql, $datos9);
		
		
		//INICIO DEL RESUMEN - COMPROBADO		
		
		$cuatro="";
		$cinco="";
		$seis="";
		$siete="";
		$referencia="";
		$idUnico=0;
		
		$uno="0";
		$dos=substr($datosTipos["datos"][0]["idAnexo_SIDI"],1);
		$tres=$datosTipos["datos"][0]["idSIDI"];
		
		$orden="1";
		

		$datos10 = array(       
			'uno' => $uno,
			'dos' => $dos,
			'tres' => $tres,
			'cuatro' => $cuatro,
			'cinco' => $cinco,
			'seis' => $seis,
			'siete' => $siete,
			'referencia' => $referencia,
			'idUnico' => $idUnico,
			'orden' => $orden   
		); 

		insertarDatosFranqueoExportar($conn, $bbddSql, $datos10);		
		
		
		//leer tabla y generarTxt

		$datos11 = [
			'referencia',
			'ot',
			'uno',
			'dos',
			'tres',
			'cuatro',
			'cinco',
			'seis',
			'siete'
		];
		$joins11 = ['tabla2'];

		$filtros11 = array();
		$filtrosOperadores11 = array();

		$order11 = [
			['campo' => 'referencia', 'dir' => 'ASC'],
			['campo' => 'Idunico', 'dir' => 'ASC'],
			['campo' => 'orden', 'dir' => 'ASC']
		];

	
		$datosParaTxt = mostrarFranqueoExportarCorreos($conn, $bbddSql, $datos11, $joins11, $filtros11, $filtrosOperadores11, $order11);
			
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
			foreach ($datosParaTxt["datos"] as $row) 
			{
				if ($row["uno"]=="0")
				{
					$cabecera=$row["uno"].$delimitador.$row["dos"].$delimitador.$row["tres"];										
				}
				else if ($row["uno"]=="1")
				{
					$contadorFicheros++;
					$fp = fopen($rutaCarpeta.date('Y-m-d')."_".$nombreProducto."_".$contadorFicheros."_sidi.txt", "w");
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
					fwrite($fp,$row["siete"]);
					
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
			$fp = fopen($rutaCarpeta.date('Y-m-d')."_".$nombreProducto."_sidi.txt", "w");
			foreach ($datosParaTxt["datos"] as $row)
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
					fwrite($fp,$row["siete"]);
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
			
			//echo ($rutaCarpetaSession.date('Y-m-d')."_".$nombreProducto."_sidi.txt");
			$res["datos"] = $rutaCarpetaSession.date('Y-m-d')."_".$nombreProducto."_sidi.txt";
			$res["error"]="";
		}
		if ($contadorFicheros>0)
		{
			$zip = new ZipArchive();
			$zip->open($rutaCarpeta.date('Y-m-d')."_".$nombreProducto."_SIDI.zip",ZipArchive::CREATE);
			
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
			
			//echo ($rutaCarpetaSession.date('Y-m-d')."_".$nombreProducto."_SIDI.zip");
			
			$res["datos"] = $rutaCarpetaSession.date('Y-m-d')."_".$nombreProducto."_SIDI.zip";
			$res["error"]="";
			
		}		
	}
	else
	{
		eliminarFranqueoExportarCorreos($conn, $bbddSql);
		$res["error"] = "Error: No hay ningun registro para generar el TXT";		
	}
	
	
	
	
	$internacional=0;
	
	sqlsrv_close($conn);
	
	echo json_encode($res);
	
	
	
}


?>