<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="comprobarCodigo")
{ 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();

	$res = array('error' => '', 'datos' => array());

	$codigoBarras = $datos["codigoBarras"];
	$codigoBarras_id="";
	$codigoBarras_presupuesto="";
	
	$codigoBarras_longitud = strlen($codigoBarras);
	
	
	$posicion = strpos($codigoBarras,'-');
	
	if ($posicion===false)
	{
		$res['error'] = "Error1: No existe ese codigo: ".$codigoBarras;
	}
	else
	{
		
		$codigoBarras_id = substr($codigoBarras,0,$posicion);
		$codigoBarras_presupuesto = substr($codigoBarras,$posicion+1,$codigoBarras_longitud-$posicion);

		$conn1 = conectarSQL($conexion);
		$conn = $conn1['conn'];
		$bbddSql = $conn1['bbdd'];

		$camposTrabajo = ['cliente','campana','cantidadTrabajo','concepto','cantidadProceso','descripcion','notaCibeles','presupuestador','fechaCompromiso'];
		$joinsTrabajo = ['tabla15','tabla16'];
		$filtrosTrabajo = ['id' => $codigoBarras_id, 'presupuesto' => $codigoBarras_presupuesto];
		$resTrabajo = cargarDetallesPresupuesto($conn, $bbddSql, $camposTrabajo, $joinsTrabajo, $filtrosTrabajo, array(), array());
		$trabajo = $resTrabajo['datos'];
		//echo $trabajo[0]["cliente"];		
		

		if (!empty($resTrabajo['error']))
		{
			$res['error'] = "resTrabajo:".$resTrabajo['error'];
		}
		else if (count($trabajo)<=0)
		{
			$res['error'] = "Error2: No existe ese codigo: ".$codigoBarras;
		}
		else
		{	
			
			//mirar en registros horas si esta todo cerrado
			//is es true--> se sigue
			//si es false--> no hace nada
			$seguir = true;
			session_start();
			
			$idEmpleado = $_SESSION["idEmpleado"];
			
			//$registroTrabajo=verUltimoRegistroTrabajo($conexion, $idEmpleado);
			$camposReg = ['id','estado','codigoBarras'];
			$joinsReg = ['tabla_presupuestos','tabla_presupuestosDetalle','tabla_procesos'];
			$filtrosReg = ['idEmpleado' => $idEmpleado];
			$filtrosOperadoresReg = [['campo1' => 'estado', 'valor' => estadoCerrado, 'operador' => '!=']];
			$resReg = cargarRegistrosHoras($conn, $bbddSql, $camposReg, $joinsReg, $filtrosReg, $filtrosOperadoresReg, array());
			$registroTrabajo = $resReg['datos'];

			if (!empty($resReg['error']))
			{
				$res['error'] = "resReg: ".$resReg['error'];
				$seguir = false;
			}


			$iniciarTrabajo=false;			
			
			if (count($registroTrabajo)<=0)
			{   //echo "entra0";
				$iniciarTrabajo=true;
				//die ("Error1:".$idEmpleado);
			}
			else if ($registroTrabajo[0]["estado"]==estadoCerrado)
			{	//echo "entra1";
				//die ("Error2:".$idEmpleado);
				$iniciarTrabajo=true;
			}			 
			else if ($registroTrabajo[0]["codigoBarras"] != $codigoBarras)
			{ //echo "entra2";
				//die ("Error3:".$idEmpleado);
				$res['error'] = "Error3: primero hay que cerrar el proceso: ".$registroTrabajo[0]["codigoBarras"];
				$seguir = false;
			}
			
			
			if ($seguir == true)
			{	 //echo "entra3";		
				if ($iniciarTrabajo == true)
				{	//echo "entra4";											
					$fechaActual = date('d/m/Y H:i:s');

					
					/*DATOS ORIGINALES*/
					
					$resInsertarRegistros = insertarRegistroHoras($conn, $bbddSql, ['idEmpleado' => $idEmpleado, 'codigoBarras' => $codigoBarras, 'horaInicio' => $fechaActual, 'estado' => estadoAbierto, 'cantidad' => 0, 'observaciones' => '', 'modo' => modoPDA]);

					
					
					$resReg2 = cargarRegistrosHoras($conn, $bbddSql, ['id'], [], ['maxIdPorEmpleado' => $idEmpleado], [], array());
					$registroTrabajo2 = $resReg2['datos'];
					if (!empty($resReg2['error']))
					{
						$res['error'] = "resReg2:".$resReg2['error'];
					}
					$idUltimoTrabajo2 = (count($registroTrabajo2)>0) ? $registroTrabajo2[0]["id"] : 0;
									
					$resEmpleado = cargarEmpleados($conn, $bbddSql, ['nombre','apellidos'], ['id' => $idEmpleado], array(), array());
					if (count($resEmpleado['datos'])>0)
					{
						$nombreEmpleado = $resEmpleado['datos'][0]["nombre"]." ".$resEmpleado['datos'][0]["apellidos"];
						modificarRegistroHoras($conn, $bbddSql, ['nombreEmpleado' => $nombreEmpleado], ['id' => $idUltimoTrabajo2]);
					}

					
					/*FIN DATOS ORIGINALES*/

						
					$resMU = cargarRegistroHoras_multiusuario($conn, $bbddSql, ['idEmpleadoDistinct'], [], ['idUsuario' => $idEmpleado], []);
					$resultadoMultiUsuario = $resMU['datos'];

					

					$contadorMultiUsuario = 0;
					while ($contadorMultiUsuario<count($resultadoMultiUsuario))
					{ 
						//echo "entra5".count($resultadoMultiUsuario);
						
						$idEmpleadoMultiUsuario = $resultadoMultiUsuario[$contadorMultiUsuario]["idEmpleado"];
						insertarRegistroHoras($conn, $bbddSql, ['idEmpleado' => $idEmpleadoMultiUsuario, 'codigoBarras' => $codigoBarras, 'horaInicio' => $fechaActual, 'estado' => estadoAbierto, 'cantidad' => 0, 'observaciones' => '', 'modo' => modoPDA]);
						$resRegMulti = cargarRegistrosHoras($conn, $bbddSql, ['id'], [], ['maxIdPorEmpleado' => $idEmpleadoMultiUsuario], [], array());
						$registroTrabajoMultiUsuario = $resRegMulti['datos'];
						if (count($registroTrabajoMultiUsuario)>0)
						{
							$idUltimoTrabajoMultiUsuario = $registroTrabajoMultiUsuario[0]["id"];
							$resEmpleadoMulti = cargarEmpleados($conn, $bbddSql, ['nombre','apellidos'], ['id' => $idEmpleadoMultiUsuario], array(), array());
							if (count($resEmpleadoMulti['datos'])>0)
							{
								$nombreEmpleadoMulti = $resEmpleadoMulti['datos'][0]["nombre"]." ".$resEmpleadoMulti['datos'][0]["apellidos"];
								modificarRegistroHoras($conn, $bbddSql, ['nombreEmpleado' => $nombreEmpleadoMulti], ['id' => $idUltimoTrabajoMultiUsuario]);
							}
						}

						$contadorMultiUsuario++;
					}



					$res['datos'] = $trabajo;
				}
				else //se cierra el trabajo
				{


					$resSiAbierto = cargarRegistroHoras_multiusuario($conn, $bbddSql, ['empleadoInicio'], ['tabla_empleadoInicio'], ['idEmpleado' => $idEmpleado], []);
					$resultadoSiEstaAbierto = $resSiAbierto['datos'];
					if (count($resultadoSiEstaAbierto)>0)
					{
						$res['error'] = "Error: Tiene un proceso abierto. El proceso a sido abierto por ". $resultadoSiEstaAbierto[0]["empleadoInicio"]. ". El proceso tiene que ser cerrado por ". $resultadoSiEstaAbierto[0]["empleadoInicio"];
					}
					else
					{
						$resMUCierre = cargarRegistroHoras_multiusuario($conn, $bbddSql, ['idEmpleadoDistinct'], [], ['idUsuario' => $idEmpleado], []);
						$resultadoMultiUsuarioCierre = $resMUCierre['datos'];
						$cantidadEmpleados = count($resultadoMultiUsuarioCierre) + 1;
						
	
	
	
						/*******DATOS ORIGINALES */
						$idUltimoTrabajo = $registroTrabajo[0]["id"];
	
						$cantidad=$datos["cantidad"];
						if ($cantidad == "")
						{
							$cantidad =0;
						}
						else
						{
							$cantidad = $cantidad/$cantidadEmpleados;
	
						}
	
						
						$notas = $datos["notas"];
	
						$fechaActual = date('d/m/Y H:i:s');
	
						
						//insertarFinTrabajo($conexion,$codigoBarras,$idEmpleado,$fechaActual,"pda",$idUltimoTrabajo,$cantidad,$notas);
						
						modificarRegistroHoras($conn, $bbddSql, ['horaFin' => $fechaActual, 'modo' => 'pda', 'cantidad' => $cantidad, 'observaciones' => $notas, 'estado' => estadoCerrado], ['id' => $idUltimoTrabajo, 'idEmpleado' => $idEmpleado, 'codigoBarras' => $codigoBarras]);
	
	
	
						/********************FIN DE DATOS ORIGINALES***************************************************************** */
						
						$contadorMultiUsuario = 0;
						while ($contadorMultiUsuario<count($resultadoMultiUsuarioCierre))
						{ 
	
							$idEmpleadoMultiUsuario = $resultadoMultiUsuarioCierre[$contadorMultiUsuario]["idEmpleado"];
							$camposCierre = ['id'];
							$joinsCierre = ['tabla_presupuestos','tabla_presupuestosDetalle','tabla_procesos'];
							$filtrosCierre = ['idEmpleado' => $idEmpleadoMultiUsuario];
							$filtrosOperadoresCierre = [['campo1' => 'estado', 'valor' => estadoCerrado, 'operador' => '!=']];
							$resRegCierre = cargarRegistrosHoras($conn, $bbddSql, $camposCierre, $joinsCierre, $filtrosCierre, $filtrosOperadoresCierre, array());
							$registroTrabajoCierre = $resRegCierre['datos'];
							if (count($registroTrabajoCierre)>0)
							{
								$idUltimoTrabajoCierre = $registroTrabajoCierre[0]["id"];
								modificarRegistroHoras($conn, $bbddSql, ['horaFin' => $fechaActual, 'modo' => 'pda', 'cantidad' => $cantidad, 'observaciones' => $notas, 'estado' => estadoCerrado], ['id' => $idUltimoTrabajoCierre, 'idEmpleado' => $idEmpleadoMultiUsuario, 'codigoBarras' => $codigoBarras]);
							}
						
	
							$contadorMultiUsuario++;
						}
	
						eliminarRegistroHoras_multiusuario($conn, $bbddSql, ['idUsuario' => $idEmpleado]);
	
	
	
						//proceso cerrado: $res['datos'] queda vacio
					}






					
				}
			}
			
			//69106-1901006
			//68434-2001063
			
		}
		
		
		
	}
	
	
	
	/*$horarioFin = $registroTrabajo[0]["horarioFin"]->format('H:i:s');
					$horarioExtra = $registroTrabajo[0]["horarioExtra"];
					
					$horarioFin2 = strtotime ( '+'.$horarioExtra.' hour' , strtotime ( $horarioFin ) ) ;					
					$horarioFin2 = date ( 'H:i:s' , $horarioFin2 );
					
					$fechaActual = date('H:i:s');
					
					echo ("\n".$horarioFin2);
					
					/*if ($fechaActual<$horarioFin)//if estás fuera de tu horario
					{
						
						echo "Fuera de horario";
					}
					else
					{
						echo "no entra";
					}*/

	echo json_encode($res);
	
	//echo ("\nid: ".$codigoBarras_id);
	//echo ("\npresupuesto: ".$codigoBarras_presupuesto);
	
	
	/*$error = "";
		
	$login=comprobarCodigo($conexion,$codigoBarras);
		
		
				
		
	$numFilas = count($login);		
		
		
		if ($numFilas > 1)
		{			
			echo ("Error: Hay más de una persona con el mismo usuario.\nContactar con el administrador.");
		}
		else if ($numFilas<=0)
		{			
			echo ("Error: Datos incorrectos");
		}
		else if ($numFilas == 1)
		{
			
			$idUsuario = $login[0]["id"];
			$_SESSION["idEmpleado"] = $login[0]["idEmpleado"];
			//echo $idUsuario;			
			session_start(); 
			$_SESSION = array();
			session_unset();
			session_destroy();
			session_start();
			session_regenerate_id();			
			
			$_SESSION['usuario'] = $usuario;
			$_SESSION['idEmpleado'] = $usuario;
			
			//echo ("\nUsuario global: ".$_SESSION['usuario']);
			//echo ("\nUsuario: ".$usuario);
			
			//se guarda un registro de entrada
			
			$descripcion = "Iniciar sesion";
			insertarRegistro($conexion,$usuario, $descripcion);	
			
			$permisos = verPermisos($conexion, $idUsuario);
			
			//echo ("\nPermiso: ".$permisos[0]["clientes"]);
			
			if ($permisos[0]["pda"] == 1 )
			{
				echo ("pda");
			}
				
				
				
			$_SESSION["permiso_clientes"] = $permisos[0]["clientes"];
			
				
			
			
					
		}
		
		
		
		
	}
	else
	{
		echo $error;	
	}*/
	
	
	
	
	
	

		
		
		
}

?>
