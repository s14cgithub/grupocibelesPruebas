<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="copiarPresupuesto")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$primerPresupuesto = $_POST["primerPresupuesto"];//2202001
	$ultimoPresupuesto = $_POST["ultimoPresupuesto"];//2202070
	$ano = $_POST["ano"]; //22
	$mes = $_POST["mes"]; //03
	
	
	$secuencialInicio = substr($primerPresupuesto,4); //001
	$secuencialFinal = substr($ultimoPresupuesto,4); //070
	
	
	
	$numeroInicioViejo = intval($primerPresupuesto);//2202001
	$numeroFinalViejo = intval($ultimoPresupuesto); //2202070
	
	$numeroInicioNuevo = intval($ano.$mes.$secuencialInicio); //2203001
	$numeroFinalNuevo = intval($ano.$mes.$secuencialFinal);   //2203070
	
	
	$contadorNuevo=$numeroInicioNuevo; //2203001
	
	$Errores="Los siguientes presupuestos ya existen";
	
	/*while ($contadorNuevo<=$numeroFinalNuevo)//se comprueba que los presupuestos nuevos no existen
	{
		$resultado = verPresupuesto($conexion,$contadorNuevo);
		//echo ("\n".$contadorNuevo);
		if (count($resultado)>0)
		{
			$Errores = $Errores."\n".$contadorNuevo;
			
		}
		$contadorNuevo++;
	}
	
	if ($Errores!="Error: los siguientes presupuestos ya existen")
	{
		echo $Errores;
	}
	else*/
	{
		 
		$anioFecha = "20".$ano;	//2022
		$fechaInicio= "01-".$mes."-".$anioFecha;					
		$fechaFin =  date("t-m-Y", strtotime($fechaInicio));
		
		$fechaAceptacion = $fechaInicio;					
		$fechaCompromiso = $fechaFin;
		
		//LOG
		$usuario = $_SESSION['usuario'];
		$descripcion = log_creacion;
		$tabla = presupuesto_tabla;
		$datosAntiguos = '';
		$columna = presupuesto_Presupuesto;
		$idRegistro = 0;
		
		
		$contadorNuevo=$numeroInicioNuevo;	
		$contadorViejo = $numeroInicioViejo;
		while ($contadorNuevo<=$numeroFinalNuevo)
		{
			
			$resultado2 = verPresupuesto($conexion,$contadorNuevo);
			//echo ("\n".$contadorNuevo);
			if (count($resultado2)>0)
			{
				
					$Errores = $Errores."\n".$contadorNuevo;
			}
			else
			{
				echo copiarPresupuestoMensual($conexion, $contadorViejo, $contadorNuevo, $fechaInicio, $fechaAceptacion, $fechaFin, $fechaCompromiso);	
				copiarDetallePresupuesto($conexion,$contadorViejo,$contadorNuevo);


				

				
			}
			
			$contadorNuevo++;
			$contadorViejo++;			


			//LOG
			$datosNuevos = $contadorNuevo;		
			$presupuesto = $contadorNuevo;
			
			
			//echo insertarRegistro ($conexion, $usuario, $descripcion,$datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);
			
			
			
		}
		if ($Errores!="Los siguientes presupuestos ya existen")
		{
			echo $Errores;
		}

		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	
	
		
		
	
}


?>