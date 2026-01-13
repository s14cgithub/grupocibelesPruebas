<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarPF")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$presupuesto = $_POST["presupuesto"];
	$idCliente = isset($_POST["idCliente"])?$_POST["idCliente"]:0;
	$importe = $_POST["importe"];
	$tipo = $_POST["tipo"];
	$clayma = $_POST["clayma"];	
	$concepto = isset($_POST["concepto"]) ? $_POST["concepto"] : '';
	
	
	
	$clayma1 = 0;
	if ($clayma=="true")
	{
		$clayma1 = 1;
	}
	
	
	
	$contador = 0;
	
	if ($idCliente==0)
	{
		$informacion = mostrarDatosPresupuesto($conexion,$presupuesto);
		$idCliente = $informacion[0]["codigo_saldo"];
	}
	
	
	
	if ($presupuesto=="correoDiario")
	{
		$contador="null";
		$numPF = verNumeroPFcorreoDiario($conexion);
		if (count($numPF)>0)
		{
			if ($numPF[0]["proximoNumero"]=="null" || $numPF[0]["proximoNumero"]==null)
			{
				$presupuesto = "9".date("y")."0001";
			}
			else
			{
				$presupuesto = $numPF[0]["proximoNumero"];
			}
		}
		else
		{
			$presupuesto = "9".date("y")."0001";
			
		}
		echo "Numero: ".$presupuesto."\n";
	}
	else 
	{
		$datos =  verContadorPFporPresupuesto($conexion,$presupuesto);	
		$contador = intval($datos[0]["contador"]) + 1;
	}
	
	
	
	
	echo insertarProvisionFondo($conexion,$presupuesto,$idCliente,$importe,$tipo,$contador,$clayma1,$concepto);
	
	if ($tipo ==4)
	{
		$datosPresupuestoArreglo =  mostrarProvisionDeFondos($conexion,$presupuesto);
		modificarPFpendientes($conexion,$datosPresupuestoArreglo[0]["id"],4,null,"Arreglos",$importe);
		 	
	}
	
	
	//LOG
	$usuario = $_SESSION['usuario'];
	$descripcion = log_creacion;
	$tabla = provisionDeFondo_tabla;
	$datosAntiguos = '';
	$datosNuevos = "idCliente: ".$idCliente."; Clayma: ".$clayma."; Importe: ". $importe."; Tipo: ".$tipo."; Contador: ". $contador;	
	$columna = 'Todas';
	$idRegistro = 0;	



	echo insertarRegistro ($conexion, $usuario, $descripcion,$datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);
	
	
	/*$descripcion1 = "creacion";
	$usuario = $_SESSION['usuario'];	
	$datosAntiguos="";	
	$tabla = provisionDeFondo_tabla;
	$idRegistro=$presupuesto;
	
	$columna = provisionDeFondo_importe;
	$datosNuevos = $importe ;
		
	echo insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro);	
	
	$columna = provisionDeFondo_tipo;
	$datosNuevos = $tipo ;
		
	echo insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro);*/
	
}

?>