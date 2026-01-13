<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarRegistro")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
			
	$idRegistro = isset($_POST["id"])?$_POST["id"]:'';
	$codigoBarras = isset($_POST["codigoBarras"])?$_POST["codigoBarras"]:'';
	$fechaInicio= isset($_POST["fechaInicio"])?$_POST["fechaInicio"]:'';
	$horaInicio= isset($_POST["horaInicio"])?$_POST["horaInicio"]:'';
	$fechaFin= isset($_POST["fechaFin"])?$_POST["fechaFin"]:'';
	$horaFin= isset($_POST["horaFin"])?$_POST["horaFin"]:'';

	
	if ($horaInicio == "informatica")
	{
		$datosFecha = explode("T",$fechaInicio);
		$fechaInicio = $datosFecha[0];
		$horaInicio = $datosFecha[1];

		$datosFecha = explode("T",$fechaFin);
		$fechaFin = $datosFecha[0];
		$horaFin = $datosFecha[1];
	}
	
	$fechaInicioCompleta = $fechaInicio." ".$horaInicio;
	$fechaFinCompleta = $fechaFin." ".$horaFin;
	
	
	
	
	
	$cantidad = isset($_POST["cantidad"])?$_POST["cantidad"]:'';
	$observaciones = isset($_POST["observaciones"])?$_POST["observaciones"]:'';
	$modificarModo = isset($_POST["modificarModo"])?false:true;



	$tipoProceso= isset($_POST["tipoProceso"])?$_POST["tipoProceso"]:'';
	$proceso= isset($_POST["proceso"])?$_POST["proceso"]:'';
	$cliente= isset($_POST["cliente"])?$_POST["cliente"]:'';

	$impresora= isset($_POST["impresora"])?$_POST["impresora"]:'';
	$tamanio= isset($_POST["tamanio"])?$_POST["tamanio"]:'';
	$tipo= isset($_POST["tipo"])?$_POST["tipo"]:'';
	$acabado= isset($_POST["acabado"])?$_POST["acabado"]:'';
	$gramaje= isset($_POST["gramaje"])?$_POST["gramaje"]:'';
	$origen= isset($_POST["origen"])?$_POST["origen"]:'';
	$numCaras= isset($_POST["numCaras"])?$_POST["numCaras"]:'';
	


	$idGFSubconcepto2= isset($_POST["idSubConcepto2"])?$_POST["idSubConcepto2"]:'';

	 
	
	
	$usuario = $_SESSION['usuario'];
	$tabla = tabla_registroHora;
	$descripcion = "modificacion";
	
	
	
	$resultado = cargarRegistroHoraId($conexion,$idRegistro);
	
	$modificar = false;
	
	if ($codigoBarras != "" && $resultado[0][registroHora_columnaCodigoBarras] != $codigoBarras) //cambiar codigo de barras
	{
		$modificar = true;		
		$datosAntiguos = $resultado[0][registroHora_columnaCodigoBarras];
		$datosNuevos = $codigoBarras;		
		$columna = registroHora_columnaCodigoBarras;		
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
		modificarRegistroHoraIdColumnaTexto($conexion,$idRegistro, $columna, $datosNuevos);
	}
	
	$fechaInicioAntiguo = $resultado[0][registroHora_columnaHoraInicio]->format('Y-m-d'); //2020-08-31
	$horaInicioAntiguo = $resultado[0][registroHora_columnaHoraInicio]->format('H:i:s');
	$fechaInicioCompletaAntiguo = $fechaInicioAntiguo." ".$horaInicioAntiguo;
	
	

	
	if ($fechaInicioCompleta != "" && $fechaInicioCompletaAntiguo != $fechaInicioCompleta)
	{	
	
		$modificar = true;		
		$datosAntiguos = $fechaInicioCompletaAntiguo;
		
		$datosNuevos = $fechaInicioCompleta;
		
		$columna = registroHora_columnaHoraInicio;		
		
		insertarRegistro($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');
		
		$datosNuevos =substr($datosNuevos,8,2)."-".substr($datosNuevos,5,2)."-".substr($datosNuevos,0,4)." ".$horaInicio;
		
		
		modificarRegistroHoraIdColumnaTexto($conexion,$idRegistro, $columna, $datosNuevos);
	}
	
	//echo("\naaaa\n");

	if ($resultado[0][registroHora_columnaHoraFin]=="" || $resultado[0][registroHora_columnaHoraFin]=="NULL" || $resultado[0][registroHora_columnaHoraFin]==NULL)
	{//echo "entra1";
		$fechaFinAntiguo = ""; //2020-08-31
		$horaFinAntiguo = "";
	}
	else
	{ //echo "entra2";
		$fechaFinAntiguo = $resultado[0][registroHora_columnaHoraFin]->format('Y-m-d'); //2020-08-31
		$horaFinAntiguo = $resultado[0][registroHora_columnaHoraFin]->format('H:i:s');
	}
	
	//echo("\naaaa\n");

	
	
	
	$fechaFinCompletaAntiguo = $fechaFinAntiguo." ".$horaFinAntiguo;
	
	if ($fechaFinCompleta != "" && $fechaFinCompletaAntiguo != $fechaFinCompleta)
	{		
		$modificar = true;		
		$datosAntiguos = $fechaFinCompletaAntiguo;
		
		$datosNuevos = $fechaFinCompleta;
		
		$columna = registroHora_columnaHoraFin;		
		
		insertarRegistro($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');
		
		$datosNuevos =substr($datosNuevos,8,2)."-".substr($datosNuevos,5,2)."-".substr($datosNuevos,0,4)." ".$horaFin;
		
		
		modificarRegistroHoraIdColumnaTexto($conexion,$idRegistro, $columna, $datosNuevos);
		
		
		
		$columna = registroHora_columnaEstado;
		$datosNuevos = 'cerrado';
		
		modificarRegistroHoraIdColumnaTexto($conexion,$idRegistro, $columna, $datosNuevos);
		
		
	}
	
	
	
	
	if ($modificar == true && $modificarModo==true)
	{
		$columna = registroHora_columnaModo;
		$datosNuevos=modoManual;
		modificarRegistroHoraIdColumnaTexto($conexion,$idRegistro, $columna, $datosNuevos);
	}
	
	
	
	
	
	if ($cantidad != "" && $resultado[0][registroHora_columnaCantidad] != $cantidad)
	{
		$modificar = true;		
		$datosAntiguos = $resultado[0][registroHora_columnaCantidad];		
		$datosNuevos = $cantidad;		
		$columna = registroHora_columnaCantidad;		
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
		modificarRegistroHoraIdColumnaNumero($conexion,$idRegistro, $columna, $datosNuevos);
	}
	
	if ($observaciones != "" && $resultado[0][registroHora_columnaObservaciones] != $observaciones)
	{
		$modificar = true;		
		$datosAntiguos = $resultado[0][registroHora_columnaObservaciones];
		$datosNuevos = $observaciones;		
		$columna = registroHora_columnaObservaciones;		
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
		modificarRegistroHoraIdColumnaTexto($conexion,$idRegistro, $columna, $datosNuevos);
	}


///////////////////////////////////////
/*
$tipoProceso= isset($_POST["tipoProceso"])?$_POST["tipoProceso"]:'';
$proceso= isset($_POST["proceso"])?$_POST["proceso"]:'';
$cliente= isset($_POST["cliente"])?$_POST["cliente"]:'';
*/
	if ($proceso != "" && $resultado[0][registroHora_columnasinProceso_idConcepto] != $proceso)
	{
		$modificar = true;		
		$datosAntiguos = $resultado[0][registroHora_columnasinProceso_idConcepto];		
		$datosNuevos = $proceso;		
		$columna = registroHora_columnasinProceso_idConcepto;		
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
		modificarRegistroHoraIdColumnaNumero($conexion,$idRegistro, $columna, $datosNuevos);
	}

	if ($cliente != "" && $resultado[0][registroHora_columnasinProceso_idCliente] != $cliente)
	{
		$modificar = true;		
		$datosAntiguos = $resultado[0][registroHora_columnasinProceso_idCliente];		
		$datosNuevos = $cliente;		
		$columna = registroHora_columnasinProceso_idCliente;		
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
		modificarRegistroHoraIdColumnaNumero($conexion,$idRegistro, $columna, $datosNuevos);
	}

	




///////////////////////////////////
	if ($impresora != "" && $resultado[0][registroHora_columnaIdImpresoras] != $impresora)
	{
		$modificar = true;		
		$datosAntiguos = $resultado[0][registroHora_columnaIdImpresoras];		
		$datosNuevos = $impresora;		
		$columna = registroHora_columnaIdImpresoras;		
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
		modificarRegistroHoraIdColumnaNumero($conexion,$idRegistro, $columna, $datosNuevos);
	}

	if ($tamanio != "" && $resultado[0][registroHora_columnaIdPapelTamano] != $tamanio)
	{
		$modificar = true;		
		$datosAntiguos = $resultado[0][registroHora_columnaIdPapelTamano];		
		$datosNuevos = $tamanio;		
		$columna = registroHora_columnaIdPapelTamano;		
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
		modificarRegistroHoraIdColumnaNumero($conexion,$idRegistro, $columna, $datosNuevos);
	}

	if ($tipo != "" && $resultado[0][registroHora_columnaIdPapelTipo] != $tipo)
	{
		$modificar = true;		
		$datosAntiguos = $resultado[0][registroHora_columnaIdPapelTipo];		
		$datosNuevos = $tipo;		
		$columna = registroHora_columnaIdPapelTipo;		
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
		modificarRegistroHoraIdColumnaNumero($conexion,$idRegistro, $columna, $datosNuevos);
	}

	if ($acabado != "" && $resultado[0][registroHora_columnaIdPapelAcabado] != $acabado)
	{
		$modificar = true;		
		$datosAntiguos = $resultado[0][registroHora_columnaIdPapelAcabado];		
		$datosNuevos = $acabado;		
		$columna = registroHora_columnaIdPapelAcabado;		
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
		modificarRegistroHoraIdColumnaNumero($conexion,$idRegistro, $columna, $datosNuevos);
	}

	if ($gramaje != "" && $resultado[0][registroHora_columnaIdPapelGramaje] != $gramaje)
	{
		$modificar = true;		
		$datosAntiguos = $resultado[0][registroHora_columnaIdPapelGramaje];		
		$datosNuevos = $gramaje;		
		$columna = registroHora_columnaIdPapelGramaje;		
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
		modificarRegistroHoraIdColumnaNumero($conexion,$idRegistro, $columna, $datosNuevos);
	}

	if ($origen != "" && $resultado[0][registroHora_columnaIdPapelOrigen] != $origen)
	{
		$modificar = true;		
		$datosAntiguos = $resultado[0][registroHora_columnaIdPapelOrigen];		
		$datosNuevos = $origen;		
		$columna = registroHora_columnaIdPapelOrigen;		
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
		modificarRegistroHoraIdColumnaNumero($conexion,$idRegistro, $columna, $datosNuevos);
	}

	
	if ($numCaras != "" && $resultado[0][registroHora_columnaNumerosCaras] != $numCaras)
	{
		$modificar = true;		
		$datosAntiguos = $resultado[0][registroHora_columnaNumerosCaras];		
		$datosNuevos = $numCaras;		
		$columna = registroHora_columnaNumerosCaras;		
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
		modificarRegistroHoraIdColumnaNumero($conexion,$idRegistro, $columna, $datosNuevos);
	}

	if ($idGFSubconcepto2 != "" && $resultado[0][registroHora_columnaIdGFSubconjunto2] != $idGFSubconcepto2)
	{
		$modificar = true;		
		$datosAntiguos = $resultado[0][registroHora_columnaIdGFSubconjunto2];		
		$datosNuevos = $idGFSubconcepto2;		
		$columna = registroHora_columnaIdGFSubconjunto2;		
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
		modificarRegistroHoraIdColumnaNumero($conexion,$idRegistro, $columna, $datosNuevos);
	}
			
	
}


?>
