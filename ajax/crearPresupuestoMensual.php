<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="crearPresupuesto")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	$numMensual = $_POST["numMensual"];
	$anioActual = date("y");
	
	
	$anio = substr($numMensual,0,2);
	$mes = substr($numMensual,2,2);
	$secuencial = substr($numMensual,4);
	
	//rocio: 000 al 100 - manualmente
	//comerciales: 101 al infinito - automatico	
	
	if (intval($secuencial)>=0 && intval($secuencial)<=100)
	{
		
		if (intval($mes)>0 && intval($mes<=12))
		{
			if (intval($anio)>=$anioActual)
			{
		
				$comercial = $_POST["comercial"];
				$detallada = $_POST["detallada"];
				$cliente = $_POST["cliente"];
				$campania = $_POST["campania"];
				$cantidad = $_POST["cantidad"];
				$pedCliente = $_POST["pedCliente"];
				$direccion = $_POST["direccion"];
				$cp = $_POST["cp"];
				$poblacion = $_POST["poblacion"];
				$formaPago = $_POST["formaPago"];
				$persona = $_POST["persona"];
				$mostrarTotalPresupuesto = $_POST["mtp"];
				$mostrarTotalFranqueo = $_POST["mtf"];
				$mostrarTotalFranqueoImporte = $_POST["mtfImporte"];
				$notaPresupuesto = $_POST["nota"];
				$otBajada = $_POST["otBajada"];
				
				$observaciones2 = isset($_POST["observaciones2"])?$_POST["observaciones2"]:'';
				
				$clayma = $_POST["clayma"];
				
				$clayma1=0;
				if ($clayma=="true")
				{
					$clayma1=1;
				}
				


				$resultado = verPresupuesto($conexion,$numMensual);
				//echo "\nNumero: ".count($resultado)."\n";
				if (count($resultado)>0)
				{
					echo ("Error: El presupuesto ". $numMensual." ya existe");
				}
				else
				{
					$anio = "20".$anio;
					
	
					$fechaInicio= "01-".$mes."-".$anio;					
					$fechaFin =  date("t-m-Y", strtotime($fechaInicio));
					
					echo ($numMensual);
					
					$otAbierta=1;
  					$fechaAceptacion = $fechaInicio;
					
					$fechaCompromiso = $fechaFin;
					
					
					insertarPresupuestoMensual($conexion,$numMensual, $comercial, $detallada, $cliente, $campania, $cantidad, $pedCliente, $direccion, $cp, $poblacion, $formaPago,$persona,$mostrarTotalPresupuesto,$mostrarTotalFranqueo,$mostrarTotalFranqueoImporte,$notaPresupuesto,$otBajada,$otAbierta, $fechaInicio, $fechaAceptacion, $fechaFin,$fechaCompromiso,$clayma1,$observaciones2);
					
					
					
					//LOG
					$usuario = $_SESSION['usuario'];
					$descripcion = log_creacion;
					$tabla = presupuesto_tabla;
					$datosAntiguos = '';
					$datosNuevos = $numMensual;	
					$columna = presupuesto_Presupuesto;
					$idRegistro = 0;
					$presupuesto = $numMensual;



					echo insertarRegistro ($conexion, $usuario, $descripcion,$datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto,$clayma1);
					
					
				}
			}
			else
			{
				echo ("Error: El año debe ser igual o superior al año actual");
			}
		}
		else
		{
			echo ("Error: Mes fuera de rango");
		}
		
		
		
		
	}
	else 
	{
		echo ("Error: Secuencial fuera de rango");
	}
	
}


?>