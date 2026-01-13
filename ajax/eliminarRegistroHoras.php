<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarRegistro")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
			
	$idRegistro = isset($_POST["id"])?$_POST["id"]:'';
	
	$usuario = $_SESSION['usuario'];
	$tabla = tabla_registroHora;
	$descripcion = "eliminacion";
	$columna = "todas";
	$datosNuevos = "";
	
	$resultado = cargarRegistroHoraId($conexion,$idRegistro);
	
	$idEmpleado = $resultado[0][registroHora_columnaIdEmpleado];
	$empleado = $resultado[0][registroHora_columnaNombreEmpleado];
	$codigoBarras = $resultado[0][registroHora_columnaCodigoBarras];
	$horaInicio = $resultado[0][registroHora_columnaHoraInicio]->format('Y-m-d H:i:s');
	
	if ($resultado[0][registroHora_columnaHoraFin] == "" or $resultado[0][registroHora_columnaHoraFin] == null )
	{
		$horaFin = null;
	}
	else
	{
		$horaFin = $resultado[0][registroHora_columnaHoraFin]->format('Y-m-d H:i:s');
	}
		
	
	/*try
	{
		
		$horaFin = $resultado[0][registroHora_columnaHoraFin]->format('Y-m-d H:i:s');
	}
	catch (Exception $e) 
	{
		$horaFin = "";
	}*/
	
	$cantidad = $resultado[0][registroHora_columnaCantidad];
	$observaciones = $resultado[0][registroHora_columnaObservaciones];
	$estado = $resultado[0][registroHora_columnaEstado];
	$modo = $resultado[0][registroHora_columnaModo];
	
	
	
	$datosAntiguos = "idEmpleado: ".$idEmpleado."||"."empleado: ".$empleado."||"."codigoBarras: ".$codigoBarras."||"."horaInicio: ".$horaInicio."||"."horaFin: ".$horaFin."||"."cantidad: ".$cantidad."||"."nota: ".$observaciones."||"."estado: ".$estado."||"."modo: ".$modo;
	
	echo("\n".$datosAntiguos."\n");
	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		
		
	eliminarRegistroHoraId($conexion,$idRegistro);
	
	
	
	
	
	
	
	
			
	
}


?>
