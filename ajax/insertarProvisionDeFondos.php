<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarPF")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];
	
	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();

	
	$presupuesto = $datos["presupuesto"];
	//echo "\nPresupuesto: ".$presupuesto ."\n";
	$idCliente = $datos["idCliente"];
	$tipo = $datos["tipo"];
	$importe = $datos["importe"];
	$clayma = $datos["clayma"];
	$concepto = $datos["concepto"];

	if ($idCliente==0) //NO SE HACE NADA
	{
		//mostrar mensaje "cliente no encontrado";
	}
	else
	{
		$contador = 0;
		
		if ($presupuesto=="correoDiario")
		{
			$contador="null";			
			
			/////////////////////////////////////
			$campos = ['proximoPresupuestoManual'];

			$joins=array();
			$filtros=array();

			$filtrosOperadores = array(
			array(
				'campo1' => 'presupuesto',
				'operador' => 'LIKE',
				'campo2' => '9'.date("y").'%'
				)
			);
			
			$group = array();
			$order=array();
			
			
			
			$numPF = cargarProvisionDeFondos($conn,$bbddSql, $campos, $joins, $filtros,$filtrosOperadores,$group, $order);

			if (count($numPF["datos"])>0)
			{
				if ($numPF["datos"][0]["proximoPresupuestoManual"]=="null" || $numPF["datos"][0]["proximoPresupuestoManual"]==null)
				{
					$presupuesto = "9".date("y")."0001";
				}
				else
				{
					$presupuesto = $numPF["datos"][0]["proximoPresupuestoManual"];
				}
			}
			else
			{
				$presupuesto = "9".date("y")."0001";
				
			}
			echo "Numero: ".$presupuesto."\n";
			/////////////////////////////////////
		}
		else 
		{

			$campos = ['contadorMax'];
			$joins=array();
			$filtros = ['presupuesto' => $presupuesto];
			$filtrosOperadores = array();					
			$order=array();
			$group = ['presupuesto'];
			//echo "entra";
			$contadorFondos = cargarProvisionDeFondos($conn,$bbddSql, $campos, $joins, $filtros,$filtrosOperadores,$group , $order);
			//echo "entra2";
			//echo json_encode($contadorFondos);
			//$datos =  verContadorPFporPresupuesto($conexion,$presupuesto);
			$contador = intval($contadorFondos["datos"][0]["contadorMax"]) + 1;
		}

		$datos2 = array(
				'presupuesto' => $presupuesto,
				'idCliente' => $idCliente,
				'importe' => $importe,
				'tipo' => $tipo,
				'contador' => $contador ,
				'clayma' => $clayma,
				'concepto' => $concepto
			);

	
		$resultado = insertarProvisionFondo($conn,$bbddSql, $datos2);
		echo json_encode($resultado);

		//arreglar esto, 
		// en presupuesto no se utiliza por que en presupuesto no aparece la opcion tipo 4, 
		// ¡¡SI HAY VARIOS REGISTRO CON ESE NUMERO DE PRESUPUESTO, SOLO SE MODIFICA EL PRIMERO QUE ENCUENTRE!!
		if ($tipo==4) 
		{
			$datosPresupuestoArreglo = mostrarProvisionDeFondos($conexion,$presupuesto);
			modificarPFpendientes($conexion,$datosPresupuestoArreglo[0]["id"],4,null,"Arreglos",$importe);
		}

		//////////////////////////////////////////


		//LOG

		$datos = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_creacion,
			'tabla' => provisionDeFondo_tabla ,
			'datosAntiguos' => '',
			'datosNuevos' => "idCliente: ".$idCliente."; Clayma: ".$clayma."; Importe: ". $importe."; Tipo: ".$tipo."; Contador: ". $contador,
			'columna' => 'Todas',
			'idRegistro' => 0 
		);

		insertarRegistro($conn,$bbddSql, $datos);	

	}


	sqlsrv_close($conn);
	
}

?>