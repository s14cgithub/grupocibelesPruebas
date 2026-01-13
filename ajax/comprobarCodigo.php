<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="comprobarCodigo")
{ 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$codigoBarras = $_POST["valor"];
	$codigoBarras_id="";
	$codigoBarras_presupuesto="";
	
	$codigoBarras_longitud = strlen($codigoBarras);
	
	
	$posicion = strpos($codigoBarras,'-');
	
	if ($posicion===false)
	{
		echo ("Error1: No existe ese codigo: ".$codigoBarras);
	}
	else
	{
		
		$codigoBarras_id = substr($codigoBarras,0,$posicion);
		$codigoBarras_presupuesto = substr($codigoBarras,$posicion+1,$codigoBarras_longitud-$posicion);
		
		$trabajo=comprobarCodigo($conexion,$codigoBarras_id, $codigoBarras_presupuesto);
		//echo $trabajo[0]["cliente"];
		
		if (count($trabajo)<=0)
		{
			echo ("Error2: No existe ese codigo: ".$codigoBarras);
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
			$registroTrabajo = verSiHayProcesoAbiertoPorEmpleado($conexion, $idEmpleado);		
			
			
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
				echo ("Error3: primero hay que cerrar el proceso: ".$registroTrabajo[0]["codigoBarras"]);		
				$seguir = false;
			}
			
			
			if ($seguir == true)
			{	 //echo "entra3";		
				if ($iniciarTrabajo == true)
				{	//echo "entra4";											
					$fechaActual = date('d/m/Y H:i:s');

					
					/*DATOS ORIGINALES*/
					
					insertarRegistroTrabajoInicio($conexion,$codigoBarras,$idEmpleado,$fechaActual,modoPDA);

					
					$registroTrabajo2=verUltimoRegistroTrabajo($conexion, $idEmpleado);
					$idUltimoTrabajo2 = $registroTrabajo2[0]["id"];
					//echo $idUltimoTrabajo2;
					insertarUsuarioRegistroTrabajo($conexion,$idUltimoTrabajo2);


					/*FIN DATOS ORIGINALES*/

						
					$resultadoMultiUsuario = verMultiUsuarioPorIdUsuario($conexion, $idEmpleado);

					$contadorMultiUsuario = 0;
					while ($contadorMultiUsuario<count($resultadoMultiUsuario))
					{ 
						//echo "entra5".count($resultadoMultiUsuario);
						
						$idEmpleadoMultiUsuario = $resultadoMultiUsuario[$contadorMultiUsuario]["idEmpleado"];
						insertarRegistroTrabajoInicio($conexion,$codigoBarras,$idEmpleadoMultiUsuario,$fechaActual,modoPDA);
						$registroTrabajoMultiUsuario=verUltimoRegistroTrabajo($conexion, $idEmpleadoMultiUsuario);
						$idUltimoTrabajoMultiUsuario = $registroTrabajoMultiUsuario[0]["id"];
						insertarUsuarioRegistroTrabajo($conexion,$idUltimoTrabajoMultiUsuario);

						$contadorMultiUsuario++;
					}



					echo json_encode($trabajo);
				}
				else //se cierra el trabajo
				{


					$resultadoSiEstaAbierto = verMultiUsuarioPorIdEmpleado($conexion, $idEmpleado);
					if (count($resultadoSiEstaAbierto)>0)
					{
						echo "Error: Tiene un proceso abierto. El proceso a sido abierto por ". $resultadoSiEstaAbierto[0]["empleadoInicio"]. ". El proceso tiene que ser cerrado por ". $resultadoSiEstaAbierto[0]["empleadoInicio"];
					}
					else
					{
						$resultadoMultiUsuarioCierre =  verMultiUsuarioPorIdUsuario($conexion, $idEmpleado);
						$cantidadEmpleados = count($resultadoMultiUsuarioCierre) + 1;
						
	
	
	
						/*******DATOS ORIGINALES */
						$idUltimoTrabajo = $registroTrabajo[0]["id"];
	
						$cantidad=$_POST["cantidad"];
						if ($cantidad == "")
						{
							$cantidad =0;
						}
						else
						{
							$cantidad = $cantidad/$cantidadEmpleados;
	
						}
	
						
						$notas = $_POST["notas"];;
	
						$iniciarTrabajo=true;									
						$fechaActual = date('d/m/Y H:i:s');
	
						
						//insertarFinTrabajo($conexion,$codigoBarras,$idEmpleado,$fechaActual,"pda",$idUltimoTrabajo,$cantidad,$notas);
						
						insertarFinTrabajo($conexion,$codigoBarras,$idEmpleado,$fechaActual,"pda",$idUltimoTrabajo,$cantidad,$notas);
	
	
	
						/********************FIN DE DATOS ORIGINALES***************************************************************** */
						
						$contadorMultiUsuario = 0;
						while ($contadorMultiUsuario<count($resultadoMultiUsuarioCierre))
						{ 
	
							$idEmpleadoMultiUsuario = $resultadoMultiUsuarioCierre[$contadorMultiUsuario]["idEmpleado"];
							$registroTrabajoCierre = verSiHayProcesoAbiertoPorEmpleado($conexion, $idEmpleadoMultiUsuario);
							$idUltimoTrabajoCierre = $registroTrabajoCierre[0]["id"];
							insertarFinTrabajo($conexion,$codigoBarras,$idEmpleadoMultiUsuario,$fechaActual,"pda",$idUltimoTrabajoCierre,$cantidad,$notas);
						
	
							$contadorMultiUsuario++;
						}
	
						quitarMultiUsuarioPorIdUsuario($conexion, $idEmpleado);
	
	
	
						echo json_encode("");
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
