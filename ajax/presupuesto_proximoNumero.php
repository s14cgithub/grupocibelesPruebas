<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="proximoNumero")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	$resultado = verProximoNumPresupuesto($conexion);
	
	$anioActual = date("y");
	$mesActual = date("m");
	
	$secuencialActual = "";
	$secuenciaNueva = 0;
	$secuenciaNuevaString = "";
	
	
	$presupuestoNuevo = "";
	
	
	//echo ("\Numero de registros: ".count($resultado));
	
	$anioGuardado = "";
	$mesGuardado = "";
	
	
	if (count($resultado)<=0)
	{		
		//siempre debe de haber un registro
		//die("Error: la tabla 'presupuestoNum' no tiene ningun registro");
		
		$ultimoNumero = verUltimoNumeroPresupuesto($conexion);
		
		$anioGuardado = substr($ultimoNumero[0]["ultimoNumero"],0,2);
		$mesGuardado = substr($ultimoNumero[0]["ultimoNumero"],2,2);
		$secuencialActual = substr($ultimoNumero[0]["ultimoNumero"],4);
		//echo("Error:".$secuencialActual."\nultimonuermo:".$ultimoNumero[0]["ultimoNumero"]."\n");
		
		
		
		
	}	
	else
	{
		
		$anioGuardado = $resultado[0]["anio"];
		$mesGuardado = $resultado[0]["mes"];
		$secuencialActual = $resultado[0]["secuencial"];
		//echo("Error1:".$secuencialActual);
	}
	
	//echo ("\nAnioGuardado: ".$anioGuardado);
	//echo ("\nAnioActual: ".$anioActual);
	
	if ($anioActual != $anioGuardado or $mesActual != $mesGuardado)
	{
		$secuencialActual = "100";
	}
		
	
	//rocio: 000 al 100 - manualmente
	//comerciales: 101 al infinito - automatico	
	
	$secuenciaNueva = intval($secuencialActual);
	$secuenciaNueva++;
	
	$mensaje = actualizarNumPresupuesto($conexion, $anioActual, $mesActual, $secuenciaNueva);
	$pos = strpos($mensaje, "Error");

	if ($pos === false) 
	{
		$secuenciaNuevaString = $secuenciaNueva;
	
		while (strlen($secuenciaNuevaString)<3)
		{
			$secuenciaNuevaString = "0".$secuenciaNuevaString;
		}


		$presupuestoNuevo = $anioActual.$mesActual.$secuenciaNuevaString;
		echo ($presupuestoNuevo);	
		
		
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
		$origen = $_POST["origen"];
		$trabajoIniciado = $_POST["trabajoIniciado"];
		//$observaciones2 = $_POST["observaciones2"];
		$observaciones2 = isset($_POST["observaciones2"])?$_POST["observaciones2"]:'';
		
		$origenValor = 0;
		if ($origen=="true")
		{
			$origenValor = 1;
		}

		$trabajoIniciadoValor = 0;
		if ($trabajoIniciado=="true")
		{
			$trabajoIniciadoValor = 1;
		}
				
		//añadir las observaciones
		insertarPresupuesto($conexion,$presupuestoNuevo, $comercial, $detallada, $cliente, $campania, $cantidad, $pedCliente, $direccion, $cp, $poblacion, $formaPago,$persona,$mostrarTotalPresupuesto,$mostrarTotalFranqueo,$mostrarTotalFranqueoImporte,$notaPresupuesto,$otBajada, $origenValor,$observaciones2,$trabajoIniciadoValor);
		
		
		//LOG
			$usuario = $_SESSION['usuario'];
			$descripcion = log_creacion;
			$tabla = presupuesto_tabla;
			$datosAntiguos = '';
			$datosNuevos = $presupuestoNuevo;	
			$columna = presupuesto_Presupuesto;
			$idRegistro = 0;			

			echo insertarRegistro ($conexion, $usuario, $descripcion,$datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuestoNuevo);	
		                                
		
		
		
	} 
	else 
	{
		echo "Error: No se ha podido crear el presupuesto\nIntentar otra vez";		
	}
	
}


?>