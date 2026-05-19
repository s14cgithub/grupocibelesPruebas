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
	
	$errores="";

		 
	$anioFecha = "20".$ano;	//2022
	$fechaInicio= "01-".$mes."-".$anioFecha;					
	$fechaFin =  date("t-m-Y", strtotime($fechaInicio));
	
	$fechaAceptacion = $fechaInicio;					
	$fechaCompromiso = $fechaFin;




	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = [
		'presupuesto'
	];
	$joins = array();	
	$filtrosOperadores = array();
	$order = array();
	
	$resultado = array();
	$resultado2 = array();
	
	$contadorNuevo=$numeroInicioNuevo;	
	$contadorViejo = $numeroInicioViejo;

	if($secuencialInicio<=100 && $secuencialFinal<=100)
	{
		while ($contadorNuevo<=$numeroFinalNuevo)
		{
			$filtros = [
			'presupuesto' => $contadorNuevo				
			];
			$res = cargarPresupuestos($conn,$bbddSql, $campos, $joins, $filtros,$filtrosOperadores, $order);		
			
			//$resultado['prueba1'] = count($res['datos']);
			//echo json_encode($res['datos']);
			
			if (count($res['datos'])>0)
			{
					$errores = $errores."\n".$contadorNuevo;
			}
			else
			{	
				$resultado =  insertarPresupuestoMensualCopia($conn,$bbddSql, strval($contadorViejo), strval($contadorNuevo), $fechaInicio, $fechaAceptacion, $fechaFin, $fechaCompromiso);	
				$resultado2 = insertarDetallePresupuesto_Select($conn,$bbddSql, strval($contadorViejo), strval($contadorNuevo));
			}
			
			$contadorNuevo++;
			$contadorViejo++;			


			//LOG
			$datosNuevos = $contadorNuevo;		
			$presupuesto = $contadorNuevo;
			
			$datos = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_creacion,
			'tabla' => presupuesto_tabla ,
			'datosAntiguos' => '',
			'datosNuevos' => $datosNuevos,
			'columna' => presupuesto_Presupuesto,
			'idRegistro' => 0 
		);

			insertarRegistro($conn,$bbddSql, $datos);
			
			
		}
		if ($errores!='')
		{
			$errores = "Los siguientes presupuesto ya existen.\n".$errores;
			$resultado['error'] = $errores;
			$resultado['ok'] = false;
		}		
	}
	else
	{
		$resultado['error'] = "el presupuesto no puede ser mayor de 100";
		$resultado['ok'] = false;		
	}

	sqlsrv_close($conn);

	echo json_encode(array(
    'resultado' => $resultado,
    'resultado2' => $resultado2
));
	
}

?>