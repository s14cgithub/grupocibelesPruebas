<?php
require_once __DIR__.("/../Verifactu/wortice/lanzarFactura.php");
require_once __DIR__.("/../Verifactu/wortice/lanzarFacturaClayma.php");
require_once __DIR__.("/funciones2.php");

function abrirConexionInsertUpdate($datosBBDD)
{
    $connectionInfo = array(
        "Database" => $datosBBDD->bbddBBDD,
        "UID"      => $datosBBDD->bbddUser,
        "PWD"      => $datosBBDD->bbddPass,
        "CharacterSet" => "UTF-8"
    );

    $conn_sis = sqlsrv_connect($datosBBDD->dbhost, $connectionInfo);

    if (!$conn_sis) {
        return array(
            "ok"      => false,
            "mensaje" => "Error de conexión: " . print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    // Intentar iniciar la transacción
    if (!sqlsrv_begin_transaction($conn_sis)) {
        return array(
            "ok"      => false,
            "mensaje" => "No se pudo iniciar transacción: " . print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    // Si todo va bien, devolvemos la conexión activa
    return array(
        "ok"      => true,
        "mensaje" => "Conexión establecida y transacción iniciada",
        "data"    => $conn_sis
    );
}

function diaDeLaSemanaActual() 
{
	$diaTexto="";
	if (date("N")=="1")
	{
		$diaTexto = "Lunes";
	}
	else if (date("N")=="2")
	{
		$diaTexto = "Martes";
	}
	else if (date("N")=="3")
	{
		$diaTexto = "Miercoles";
	}
	else if (date("N")=="4")
	{
		$diaTexto = "Jueves";
	}
	else if (date("N")=="5")
	{
		$diaTexto = "Viernes";
	}
	else if (date("N")=="6")
	{
		$diaTexto = "Sabado";
	}
	
	else if (date("N")=="7")
	{
		$diaTexto = "Domingo";
	}
	return $diaTexto;
}

function mesActual()
{
	$mesTexto="";
	if (date("n")=="1")
	{		
		$mesTexto = "Enero";
	}
	else if (date("n")=="2")
	{		
		$mesTexto = "Febrero";
	}
	else if (date("n")=="3")
	{		
		$mesTexto = "Marzo";
	}
	else if (date("n")=="4")
	{		
		$mesTexto = "Abril";
	}
	else if (date("n")=="5")
	{		
		$mesTexto = "Mayo";
	}
	else if (date("n")=="6")
	{		
		$mesTexto = "Junio";
	}
	else if (date("n")=="7")
	{		
		$mesTexto = "Julio";
	}
	else if (date("n")=="8")
	{		
		$mesTexto = "Agosto";
	}
	else if (date("n")=="9")
	{		
		$mesTexto = "Septiembre";
	}
	else if (date("n")=="10")
	{		
		$mesTexto = "Octubre";
	}
	else if (date("n")=="11")
	{		
		$mesTexto = "Noviembre";
	}
	else if (date("n")=="12")
	{		
		$mesTexto = "Diciembre";
	}
	
	return $mesTexto;
}

function reemplazarSimbolos($texto)
{
	
	$resultado = $texto;

	$resultado = str_replace('€',EURO,$resultado);
	$resultado = str_replace('ñ',ene,$resultado);
	$resultado = str_replace('Ñ',ene_may,$resultado);
	$resultado = str_replace('á',a_acento,$resultado);
	$resultado = str_replace('é',e_acento,$resultado);
	$resultado = str_replace('í',i_acento,$resultado);
	$resultado = str_replace('ó',o_acento,$resultado);
	$resultado = str_replace('ú',u_acento,$resultado);
	$resultado = str_replace('Á',a_acento_may,$resultado);
	$resultado = str_replace('É',e_acento_may,$resultado);
	$resultado = str_replace('Í',i_acento_may,$resultado);
	$resultado = str_replace('Ó',o_acento_may,$resultado);
	$resultado = str_replace('Ú',u_acento_may,$resultado);

	$resultado = str_replace('º',signo_grado,$resultado);
	$resultado = str_replace('ª',signo_ordinal,$resultado);
	$resultado = str_replace('%',signo_tantoPorciento,$resultado);
	$resultado = str_replace('…',signo_tresPuntos,$resultado);
	
	$resultado = str_replace('|',lineaVertical,$resultado);
	$resultado = str_replace('·',puntoMedio,$resultado);
	$resultado = str_replace('¬',sinSigno,$resultado);
	$resultado = str_replace('¡',exclamacionAbierta,$resultado);
	$resultado = str_replace('¿',interrogacionAbierta,$resultado);
	
	$resultado = str_replace('Ç',CcedillaMayuscula,$resultado);
	$resultado = str_replace('ç',CcedillaMinuscula,$resultado);
	$resultado = str_replace('¨',CcedillaMinuscula,$resultado);
	
	$resultado = str_replace('´',acento,$resultado);
	$resultado = str_replace('`',acentoGrave,$resultado);
	$resultado = str_replace('²',superindice2,$resultado);

	$resultado = str_replace('–','-',$resultado);

	$resultado = str_replace('“','"',$resultado);
	$resultado = str_replace('”','"',$resultado);
	
	
	return $resultado;
}

//LOGIN
//include("constantes.php");

function comprobarLogin($datosBBDD, $usuario1, $contrasena1)
{
	//global $conn_sis;
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	/*	
	if ($conn_sis)
	{		
			echo "conexion existosa";
	} 
	else
	{
			echo "conexion fallida.\nNo se ha podido conectar al servidor";
	} 	
	*/
	$consulta="select * from [".$datosBBDD->bbddBBDD."].[dbo].[login] where usuario='".$usuario1."' and contrasena='".$contrasena1."' and activo=1;";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	
	/*if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}*/

	sqlsrv_close($conn_sis);		
	
	return $result;
	//return $resultado;
	
}

function cambiarLogin($datosBBDD, $usuario1, $contrasena1)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[login] set contrasena='".$contrasena1."' where usuario='".$usuario1."';";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
		$mensaje = "Error: no se ha podido actualizar la contraseña'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = $consulta;
	}
	
	return $mensaje;	
}

function insertarRegistro($datosBBDD, $usuario1, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto,$clayma=0)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[log] (usuario, descripcion, datosAntiguos, datosNuevos, tabla, columna, idRegistro, presupuesto, clayma) values ('".$usuario1."','".$descripcion."','".$datosAntiguos."','".$datosNuevos."','".$tabla."','".$columna."',".$idRegistro.",'".$presupuesto."',".$clayma.")";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	/*if( $resultado === false) 
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}*/
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el log'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;

	//return $resultado;
}

function verPermisos ($datosBBDD, $idusuario1)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from permisos where id_usuario = ". $idusuario1;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;
}


//CLIENTES

function cargarClientes($datosBBDD,$condicion="")
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$condiciones = $condicion;
	
	//$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[clientes] ".$condiciones ." order by nombre_empresa";
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[clientes] ".$condiciones;  //el order debe estar dentro de la variable $condiciones
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered")); 
	//$resultado = sqlsrv_query($conn_sis, $consulta);

	
	
	if( $resultado === false ) 
	{
     	die ("Error:\n".$resultado."\n".$consulta."\n");		
	}
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))	
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}




function cargarClientesClayma($datosBBDD,$condicion="")
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$condiciones = $condicion;
	
	//$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] ".$condiciones ." order by nombre_empresa";
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] ".$condiciones;
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))	
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function cargarSubClientes($datosBBDD,$condicion="")
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$condiciones = $condicion;
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[clientes] ".$condiciones ." order by subCliente";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;
	
}

function cargarSubClientes2($datosBBDD,$campos,$condicion="")
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$condiciones = $condicion;
	
	$consulta = "select ".$campos . " from [".$datosBBDD->bbddBBDD."].[dbo].[clientes] ".$condiciones ." order by subCliente";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;
	
}


function mirarDatosEmpresaPorCodigo($datosBBDD,$codigo)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[clientes] where codigo = ".$codigo;
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;
}



function cargarObservacionesClientes($datosBBDD,$idCliente)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*, t2.nombre + ' ' + t2.apellidos as nombreCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesObservaciones] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t2
  on t1.idEmpleado = t2.id where t1.idCliente = ".$idCliente." order by t1.id desc";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function cargarObservacionesClientesClayma($datosBBDD,$idCliente)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*, t2.nombre + ' ' + t2.apellidos as nombreCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesObservacionesClayma] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t2
  on t1.idEmpleado = t2.id where t1.idCliente = ".$idCliente." order by t1.fecha desc, t1.id";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}



function insertarObservacionCliente ($datosBBDD,$idCliente,$asunto,$texto,$idEmpleado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[clientesObservaciones] (asunto, idEmpleado,observacion, idCliente) values ('".$asunto."',".$idEmpleado.",'".$texto."',".$idCliente.")";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	/*if( $resultado === false)
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}*/
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha insertar la observacion..\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Observacion Insertada");
	}
	
	return $mensaje;	
	//return $resultado;
}

function insertarObservacionClienteClayma($datosBBDD,$idCliente,$asunto,$texto,$idEmpleado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[clientesObservacionesClayma] (asunto, idEmpleado,observacion, idCliente) values ('".$asunto."',".$idEmpleado.",'".$texto."',".$idCliente.")";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	/*if( $resultado === false)
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}*/
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha insertar la observacion..\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Observacion Insertada");
	}
	
	return $mensaje;	
	//return $resultado;
}


function cargarClientesContactos($datosBBDD,$idCliente)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*, t2.sexo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesContactos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[sexo] as t2
  on t2.id = t1.idSexo
  where t1.idCliente = ".$idCliente." order by t1.id";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function cargarClientesContactosClayma($datosBBDD,$idCliente)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*, t2.sexo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesContactosClayma] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[sexo] as t2
  on t2.id = t1.idSexo
  where t1.idCliente = ".$idCliente." order by t1.id";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}



function cargarClientesDireccionRutas($datosBBDD,$idCliente,$condicion)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *  FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesDirecRutas] ".$condicion;
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function cargarClientesDireccionRutasClayma($datosBBDD,$idCliente,$condicion)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *  FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesDirecRutasClayma] ".$condicion;
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function insertarContactoCliente($datosBBDD,$idCliente,$idSexo,$nombre,$apellidos,$departamento,$cargo,$telefono,$movil,$email,$comentario)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[clientesContactos] (idCliente, idSexo,nombre, apellidos, departamento, cargo, telefono, movil, email, comentario) values (".$idCliente.",".$idSexo.",'".$nombre."','".$apellidos."','".$departamento."','".$cargo."','".$telefono."','".$movil."','".$email."','".$comentario."')";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	/*if( $resultado === false)
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}*/
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar el contacto..\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Contacto Insertado");
	}
	
	return $mensaje;	
	//return $resultado;
}

function insertarContactoClienteClayma($datosBBDD,$idCliente,$idSexo,$nombre,$apellidos,$departamento,$cargo,$telefono,$movil,$email,$comentario)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[clientesContactosClayma] (idCliente, idSexo,nombre, apellidos, departamento, cargo, telefono, movil, email, comentario) values (".$idCliente.",".$idSexo.",'".$nombre."','".$apellidos."','".$departamento."','".$cargo."','".$telefono."','".$movil."','".$email."','".$comentario."')";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	/*if( $resultado === false)
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}*/
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar el contacto..\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Contacto Insertado");
	}
	
	return $mensaje;	
	//return $resultado;
}


function insertarDireccionRutaCliente($datosBBDD,$idCliente,$att,$nombre,$direccion,$cp,$poblacion,$provincia,$pais,$activo)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[clientesDirecRutas] (idCliente, att,nombre, direccion, cp, poblacion, provincia, pais,activo) 
	values (".$idCliente.",'".$att."','".$nombre."','".$direccion."','".$cp."','".$poblacion."','".$provincia."','".$pais."',".$activo.")";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar la direccion de la ruta..\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Direccion de la Ruta Insertado");
	}
	
	return $mensaje;		
}

function insertarDireccionRutaClienteClayma($datosBBDD,$idCliente,$att,$nombre,$direccion,$cp,$poblacion,$provincia,$pais,$activo)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[clientesDirecRutasClayma] (idCliente, att,nombre, direccion, cp, poblacion, provincia, pais,activo) 
	values (".$idCliente.",'".$att."','".$nombre."','".$direccion."','".$cp."','".$poblacion."','".$provincia."','".$pais."',".$activo.")";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar la direccion de la ruta..\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Direccion de la Ruta Insertado");
	}
	
	return $mensaje;		
}


function modificarCliente($datosBBDD,$codigoCliente,$direccion,$localidad,$provincia,$cp,$comercial,$diasApagar,$formaPago,$email,$numCuenta,$cuotaRecogida,$periodo,$prodNoBon,$otrosConceptos,$importeOtrosConceptos,$provFondos,$cobroUnitarioEnvio,$envio_Att,$envio_Nombre,$envio_Direccion,$envio_cp,$envio_poblacion,$envio_provincia,$envio_pais,$correoDiario,$activo, $pfFijaImporte,$domiciliado,$nuestraCuenta,$sinIva,$retener,$pedidoCliente,$vencimiento,$preFactura,$noAplicarPF,$retencion,$codigoSIDI,$pais,$codigoPais)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[clientes] set direccion='".$direccion."', localidad='".$localidad."', provincia='".$provincia."', [codigo_postal]='".$cp."', 
	idComercial=".$comercial.", [idDiasDePago]=".$diasApagar.", idFormaPago = ".$formaPago.", email = '".$email."', fac_cuotaRecogida = ".$cuotaRecogida.", fac_idPeriodo = ".$periodo."
	, fac_porCientoNoBonificable = ".$prodNoBon.", fac_otrosConceptosfijos = '".$otrosConceptos."', fac_importeFijoOtrosConcepto = ".$importeOtrosConceptos.", fac_idProvisionFondos = ".$provFondos."
	, fac_cobroUnitarioEnvio = ".$cobroUnitarioEnvio.", envio_att = '".$envio_Att."', envio_nombre = '".$envio_Nombre."', envio_domicilio = '".$envio_Direccion."', envio_cp = '".$envio_cp."'
	, envio_poblacion = '".$envio_poblacion."', envio_provincia = '".$envio_provincia."', envio_pais = '".$envio_pais."', numCuentaBanco='".$numCuenta."', correoDiario=".$correoDiario."
	, activo=".$activo.", fac_pfFijaImporte = ". $pfFijaImporte.", domiciliada=".$domiciliado.", nuestraCuenta = '".$nuestraCuenta."', sinIva=".$sinIva.", retener=".$retener."
	, pedidoCliente='".$pedidoCliente."', vencimiento='".$vencimiento."', prefactura=".$preFactura.", noAplicarPF=".$noAplicarPF.", retencion=".$retencion.", codigoSidi=".$codigoSIDI.", pais = '".$pais."', codigoPais = '".$codigoPais."' where codigo = ".$codigoCliente;
	
	
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Cliente Modificado");
	}
	
	return $mensaje;
}


function modificarClienteClayma($datosBBDD,$codigoCliente,$direccion,$localidad,$provincia,$cp,$comercial,$diasApagar,$formaPago,$email,$numCuenta,$cuotaRecogida,$periodo,$prodNoBon,$otrosConceptos,$importeOtrosConceptos,$provFondos,$cobroUnitarioEnvio,$envio_Att,$envio_Nombre,$envio_Direccion,$envio_cp,$envio_poblacion,$envio_provincia,$envio_pais,$correoDiario,$activo, $pfFijaImporte,$domiciliado,$nuestraCuenta,$sinIva,$retener, $pedidoCliente,$vencimiento,$preFactura,$noAplicarPF,$retencion,$pais,$codigoPais)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] set direccion='".$direccion."', localidad='".$localidad."', provincia='".$provincia."', [codigo_postal]='".$cp."'
	, idComercial=".$comercial.", [idDiasDePago]=".$diasApagar.", idFormaPago = ".$formaPago.", email = '".$email."', fac_cuotaRecogida = ".$cuotaRecogida.", fac_idPeriodo = ".$periodo."
	, fac_porCientoNoBonificable = ".$prodNoBon.", fac_otrosConceptosfijos = '".$otrosConceptos."', fac_importeFijoOtrosConcepto = ".$importeOtrosConceptos.", fac_idProvisionFondos = ".$provFondos."
	, fac_cobroUnitarioEnvio = ".$cobroUnitarioEnvio.", envio_att = '".$envio_Att."', envio_nombre = '".$envio_Nombre."', envio_domicilio = '".$envio_Direccion."', envio_cp = '".$envio_cp."'
	, envio_poblacion = '".$envio_poblacion."', envio_provincia = '".$envio_provincia."', envio_pais = '".$envio_pais."', numCuentaBanco='".$numCuenta."', correoDiario=".$correoDiario." 
	, activo=".$activo.", fac_pfFijaImporte = ". $pfFijaImporte.", domiciliada=".$domiciliado.", nuestraCuenta = '".$nuestraCuenta."', sinIva=".$sinIva.", retener=".$retener.", pedidoCliente='".$pedidoCliente."'
	, vencimiento='".$vencimiento."', prefactura=".$preFactura.", noAplicarPF=".$noAplicarPF.", retencion=".$retencion." , pais = '".$pais."', codigoPais = '".$codigoPais."'
	where codigo = ".$codigoCliente;
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Cliente Modificado");
	}
	
	return $mensaje;
	
}


 
function modificarClienteContacto($datosBBDD,$id, $sexo, $nombre, $apellidos, $departamento, $cargo, $telefono, $movil, $email, $comentario)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[clientesContactos] 
	set idSexo='".$sexo."'
	, nombre='".$nombre."'
	, apellidos='".$apellidos."'
	, departamento='".$departamento."'
	, cargo='".$cargo."'
	, telefono='".$telefono."'
	, movil = '".$movil."'
	, email = '".$email."'
	, comentario = '".$comentario."'
	
	where id = ".$id;
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Contacto Modificado");
	}
	
	return $mensaje;
	
}
function modificarClienteContactoClayma($datosBBDD,$id, $sexo, $nombre, $apellidos, $departamento, $cargo, $telefono, $movil, $email, $comentario)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[clientesContactosClayma] 
	set idSexo='".$sexo."'
	, nombre='".$nombre."'
	, apellidos='".$apellidos."'
	, departamento='".$departamento."'
	, cargo='".$cargo."'
	, telefono='".$telefono."'
	, movil = '".$movil."'
	, email = '".$email."'
	, comentario = '".$comentario."'
	
	where id = ".$id;
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Contacto Modificado");
	}
	
	return $mensaje;
	
}



 
function eliminarClienteContacto($datosBBDD,$id)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesContactos] where id = ".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el contacto'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}

function eliminarClienteContactoClayma($datosBBDD,$id)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesContactosClayma] where id = ".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el contacto'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}





function modificarClienteDireccionRuta($datosBBDD,$id,$att,$nombre,$direccion,$cp,$poblacion,$provincia,$pais,$activo)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[clientesDirecRutas] 
	set att='".$att."'
	, nombre='".$nombre."'
	, direccion='".$direccion."'
	, cp='".$cp."'
	, poblacion='".$poblacion."'
	, provincia='".$provincia."'
	, pais = '".$pais."'
	, activo = ".$activo."
	
	
	where id = ".$id;
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Direccion de Ruta Modificado");
	}
	
	return $mensaje;	
}

function modificarClienteDireccionRutaClayma($datosBBDD,$id,$att,$nombre,$direccion,$cp,$poblacion,$provincia,$pais,$activo)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[clientesDirecRutasClayma] 
	set att='".$att."'
	, nombre='".$nombre."'
	, direccion='".$direccion."'
	, cp='".$cp."'
	, poblacion='".$poblacion."'
	, provincia='".$provincia."'
	, pais = '".$pais."'
	, activo = ".$activo."
	
	
	where id = ".$id;
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Direccion de Ruta Modificado");
	}
	
	return $mensaje;	
}


function cambiarActivosDireccionesRuta($datosBBDD,$id,$idCliente)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[clientesDirecRutas] set activo = 0 where idCliente = ".$idCliente." and id!=".$id;
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido desactivar las direcciones.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Direccion Activada");
	}
	
	return $mensaje;	
}

function cambiarActivosDireccionesRutaClayma($datosBBDD,$id,$idCliente)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[clientesDirecRutasClayma] set activo = 0 where idCliente = ".$idCliente." and id!=".$id;
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido desactivar las direcciones.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Direccion Activada");
	}
	
	return $mensaje;	
}


function eliminarClienteDireccionRuta($datosBBDD,$id)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesDirecRutas] where id = ".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar la direccion de la ruta'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}

function eliminarClienteDireccionRutaClayma($datosBBDD,$id)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesDirecRutasClayma] where id = ".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar la direccion de la ruta'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}



function modificarClienteAutorizado($datosBBDD,$codigo,$valor) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[clientes]
	set idAutorizacionFranqueo = ".$valor."
	where codigo = ".$codigo;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Cliente Modificado");
	}
	
	return $mensaje;
}


function cargarListadoDireccionesRutasCliente($datosBBDD,$condicion)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select * from (
SELECT codigo as 'id', codigo as 'idCliente', subcliente as 'nombre', direccion, codigo_postal, localidad, provincia, '' as pais, 1 as orden, 1 as 'activo'   FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientes]
union
  SELECT id, [idCliente],[nombre],[direccion],[cp],[poblacion],[provincia],[pais], 2 as orden, activo FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesDirecRutas] 
 ) as tabla ". $condicion;
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


//CLIENTES - FIN

//PDA

function verSiHayProcesoAbiertoPorEmpleado($datosBBDD,$idEmpleado) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*, t2.cliente, t2.campana as 'campana', t2.cantidad as 'cantidad trabajo', t2.comercial, t2.[fechaCompromiso]
,t4.proceso as concepto, t3.unidades as 'cantidad proceso', t3.descripcion, t3.notaCibeles, t3.presupuesto
, t5.nombre as presupuestador, t7.nombre as comercial
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
on SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) = t2.presupuesto
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t3
on SUBSTRING(t1.codigoBarras,0,CHARINDEX('-', t1.codigoBarras)) = t3.id
inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t4
on t4.id = t3.idConcepto
left join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t5
on t5.id = t2.idComercial
left join (SELECT nombre_empresa, idComercial , 0 as clayma
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientes]
  union 
SELECT nombre_empresa,idComercial, 1 as clayma
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma]) as t6
on t6.nombre_empresa = t2.cliente and t6.clayma = t2.clayma
left join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t7 
on t7.id = t6.idComercial
 where t1.idEmpleado = ".$idEmpleado." and t1.estado != 'cerrado'";	
 	//echo $consulta;
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;
}

function verUltimoRegistroTrabajo($datosBBDD,$idEmpleado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	//$consulta = "select codigoBarras, iniciofin from registroHoras where id = (select max(id) from registroHoras) and idEmpleado = ".$idEmpleado;
	
	$consulta = "select t1.id as id, t1.codigoBarras, t1.estado, t2.horarioInicio, t2.horarioFin, t1.horaInicio, t1.horaFin
	
	from [".$datosBBDD->bbddBBDD."].[dbo].registroHoras as t1
	
	INNER JOIN [".$datosBBDD->bbddBBDD."].[dbo].empleados as t2 
	on t1.idEmpleado= t2.id
	
	where t1.id = (select max(t3.id) from [".$datosBBDD->bbddBBDD."].[dbo].registroHoras as t3 where t3.idEmpleado=".$idEmpleado.") and t1.idEmpleado = ".$idEmpleado;	
	 	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	//echo $consulta;
	sqlsrv_close($conn_sis);
	return $result;
}


function comprobarCodigo($datosBBDD, $id, $presupuesto)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select t1.cliente, t1.campana as 'campana', t1.cantidad as 'cantidad trabajo', t1.comercial, t1.[fechaCompromiso],
	t2.concepto, t2.unidades as 'cantidad proceso', t2.descripcion, t2.notaCibeles, t2.presupuesto
	from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]  as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t2
	on t1.presupuesto = t2.presupuesto	
	
	where t2.id=".$id." and t2.presupuesto='".$presupuesto."'";	
 	//echo $consulta;
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;
	
}

function insertarRegistroTrabajoInicio($datosBBDD,$codigoBarras,$idEmpleado, $fecha,$modo,$cantidad=0, $observaciones='')
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] (idEmpleado, codigoBarras, horaInicio, estado, cantidad, observaciones,modo) values (".$idEmpleado.",'".$codigoBarras."','".$fecha."','".estadoAbierto."',".$cantidad.",'".$observaciones."','".$modo."')";
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido abrir el proceso'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Franqueo modificado";
	}
	
	return $mensaje;	
}


                                                      
function insertarRegistroTrabajoCompleto($datosBBDD,$codigoBarras,$idEmpleado, $fechaInicio,$fechaFin,$modo,$estado,$cantidad=0, $observaciones='')
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] (idEmpleado, codigoBarras, horaInicio, horaFin, estado, cantidad, observaciones,modo) values (".$idEmpleado.",'".$codigoBarras."','".$fechaInicio."','".$fechaFin."','".$estado."',".$cantidad.",'".$observaciones."','".$modo."')";
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido registrar el proceso'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Registro Insertado";
	}
	
	return $mensaje;
	
	
}


function insertarUsuarioRegistroTrabajo($datosBBDD,$idProceso)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update t1 set t1.nombreEmpleado = t2.nombre + ' ' + t2.apellidos 
	from registroHoras as t1 
	inner join empleados as t2
	on t1.idEmpleado = t2.id where t1.id = ".$idProceso;	
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;	
}

         
function insertarFinTrabajo($datosBBDD,$codigoBarras,$idEmpleado, $fecha, $modo,$id,$cantidad,$notas)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	
	$consulta = "update registroHoras set horaFin = '".$fecha."', modo='".$modo."', cantidad=".$cantidad.", observaciones='".$notas."', estado = '".estadoCerrado."' where id=".$id." and idEmpleado = ".$idEmpleado." and codigoBarras = '".$codigoBarras."'";	
 	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;	
}


function verMultiUsuarioPorIdUsuario($datosBBDD, $idUsuario) //idUsuario: es quien a iniciado sesion y quien añade a otros empleados
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT distinct(idUsuario), idEmpleado
  		FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras_multiusuario]
  		WHERE idUsuario = ".$idUsuario;	
		
 	//echo $consulta;
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array(); 
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;
	
}

function verMultiUsuarioPorIdEmpleado($datosBBDD, $idEmpleado) // idEmpleado: es quien ha sido añadido por otro empleado
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "		SELECT t1.*, CONCAT(t2.nombre,' ', t2.apellidos) as empleadoInicio
		FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras_multiusuario] as t1
		inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t2
		on t1.idUsuario = t2.id
  		WHERE t1.idEmpleado =".$idEmpleado;	
		
 	//echo $consulta;
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array(); 
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;
	
}


function insertarRegistroTrabajoManual($datosBBDD, $proceso,$idEmpleado, $fechaFormatoInicio, $fechaFormatoFin, $cantidad,$observaciones, $impresoras, $tamanio, $tipo, $acabado, $gramaje, $origen,$numCaras,$sinProceso_idProceso,$sinProceso_idCliente,$idSubconjunto2="NULL")
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] (idEmpleado, codigoBarras, horaInicio, horaFin, estado, cantidad, observaciones,modo, idImpresoras,idPapelTamano, idPapelTipo,idPapelAcabado,idPapelGramaje, idPapelOrigen,sinProceso_idConcepto,sinProceso_idCliente,idGFSubconjunto2,impresionNumeroCaras) 
	
	values (".$idEmpleado.",'".$proceso."','".$fechaFormatoInicio."','".$fechaFormatoFin."','".estadoCerrado."',".$cantidad.",'".$observaciones."','".modoManual."',".$impresoras.",".$tamanio.",".$tipo.",".$acabado.",".$gramaje.",".$origen.",".$sinProceso_idProceso.",".$sinProceso_idCliente.",".$idSubconjunto2.",".$numCaras.")";
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false) 
	{
     	$mensaje = "Error: no se ha podido registrar el proceso'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Registro Insertado";
	}
	
	return $mensaje;	
}




function insertarProceso_RegistroHora($datosBBDD, $idRegistroHora, $proceso)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] set codigoBarras = '".$proceso."' where id = ".$idRegistroHora ;	
	
	//echo $consulta;

	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Proceso Insertado");
	}
	
	return $mensaje;	
}

//PDA - FIN

//pda gestion

function cargarAutomaticosPda($datosBBDD)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select * from registroHoras where modo='".modoAutomatico."'";	
 	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}


function cargarRegistroHoraId($datosBBDD,$id)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select * from registroHoras where id=".$id;	
 	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}


function modificarRegistroHoraIdColumnaTexto($datosBBDD,$idRegistro,$columna,$datosNuevos)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update registroHoras set [".$columna."] = '".$datosNuevos."' where id=".$idRegistro;
 	
	//echo $consulta;

 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	

	sqlsrv_close($conn_sis);
	
	return $resultado;	
}


function modificarRegistroHoraIdColumnaNumero($datosBBDD,$idRegistro,$columna,$datosNuevos)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update registroHoras set [".$columna."] = ".$datosNuevos." where id=".$idRegistro;
 	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	

	sqlsrv_close($conn_sis);
	
	return $resultado;	
}

function modificarRegistroHoraSinProceso($datosBBDD,$idRegistro,$idProceso,$idCliente)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] set [sinProceso_idConcepto] = ".$idProceso.", [sinProceso_idCliente] = ".$idCliente." where id=".$idRegistro; 
 	
	//echo $consulta;
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	

	sqlsrv_close($conn_sis);
	
	return $resultado;	
}


function cargarRegistrosHora($datosBBDD,$otBuscar,$empleadoAbuscar,$fechaInicio,$fechaFin,$orden,$desc, $masde10horas="false")	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$condicion = "";
	
	
	if ($otBuscar != "" )
	{
		$condicion = "where ".registroHora_columnaCodigoBarras." like '%-".$otBuscar."'";
	}
	
	if ($empleadoAbuscar!="" and $condicion=="")
	{
		$condicion = "where ".registroHora_columnaIdEmpleado. " = ".$empleadoAbuscar;
	}
	else if ($empleadoAbuscar!="")
	{
		$condicion = $condicion." and ".registroHora_columnaIdEmpleado. " = ".$empleadoAbuscar;
	}
	
	$fechaInicio1;
	if ($fechaInicio!="")//la fecha viene con formato 'yyyy-mm-dd' y hay que convertirlo en 'dd-mm-yyy'
	{
		$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	}
	
	$fechaFin1;
	if ($fechaFin!="")//la fecha viene con formato 'yyyy-mm-dd' y hay que convertirlo en 'dd-mm-yyy'
	{
		$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
	}
	
	if ($fechaInicio!="" and $fechaFin!="" and $condicion=="") 
	{
		$condicion = "where ".registroHora_columnaHoraInicio. " >= '".$fechaInicio1."' and ".registroHora_columnaHoraInicio. " < '".date("d-m-Y",strtotime($fechaFin1."+ 1 days"))."'";
	}
	else if ($fechaInicio!="" and $fechaFin!="")
	{
		$condicion = $condicion." and ".registroHora_columnaHoraInicio. " >= '".$fechaInicio1."' and ".registroHora_columnaHoraInicio. " < '".date("d-m-Y",strtotime($fechaFin1."+ 1 days"))."'";
	}
	else if ($fechaInicio!="" and $condicion=="") 
	{		
		$condicion = "where ".registroHora_columnaHoraInicio. " >= '".$fechaInicio1."'";
	}
	else if ($fechaInicio!="" )
	{
		$condicion  = $condicion." and ".registroHora_columnaHoraInicio. " >= '".$fechaInicio1."'";
	}


	if ($masde10horas=="true" and $condicion=="")
	{
		$condicion = "where  (estado!= 'abierto' and DATEDIFF (HOUR, horaInicio , horaFin )>10)";
	}
	else if ($masde10horas=="true")
	{
		$condicion = $condicion." and  (estado!= 'abierto' and DATEDIFF (HOUR, horaInicio , horaFin )>10)";
	}
	
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] ".$condicion." order by ".$orden. " ".$desc;
	
	//echo ($consulta); 	
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}


function cargarRegistrosHoraInformatica($datosBBDD,$condicion)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	
	$consulta = "SELECT t1.*, t8.id as idGFConcepto, t7.id as idGFSubconcepto1,t5.idTipoProceso as 'sinProceso_idTipoProceso',  t3.tipoProceso, t4.proceso, t2.descripcion
, t9.impresoras, t10.tamano, t11.tipo, t12.acabado, t13.gramaje, t14.origen
FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
left join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t2
on t2.id = SUBSTRING(t1.codigoBarras,1,len(t1.codigoBarras)-8)

left join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t3
on t3.id = t2.idTipo

left join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t4
on t4.id = t2.idConcepto

left join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t5
on t5.id = t1.sinProceso_idConcepto

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_subconcepto2] as t6
on t6.id = t1.idGFSubconjunto2

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_subconcepto1] as t7
on t6.idSubconcepto1 = t7.id

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_concepto] as t8
on t7.idConcepto = t8.id

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_impresoras] as t9
on t1.idImpresoras = t9.id

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanio] as t10
on t1.idPapelTamano = t10.id

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTipo] as t11
on t1.idPapelTipo = t11.id

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelAcabado] as t12
on t1.idPapelAcabado = t12.id

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelGramaje] as t13
on t1.idPapelGramaje = t13.id

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelOrigen] as t14
on t1.idPapelOrigen = t14.id


".$condicion;



	
	//$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] ".$condicion;
	
	//echo ($consulta); 	
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}

function cargarRegistrosHoras($datosBBDD,$condicion)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	
	$consulta = "SELECT t1.*,t5.id as idDepartamento,t5.departamento,t4.tipoProceso,t3.proceso, t2.nombre, t6.subcliente
FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t2
on t2.id = t1.idEmpleado
inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t3
on t3.id = t1.sinProceso_idConcepto
inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t4
on t3.idTipoProceso = t4.id
inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosDepartamento] as t5
on t5.id = t3.idDepartamento
left join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t6
on t6.codigo = t1.sinProceso_idCliente

".$condicion;
	
	//echo ($consulta); 	
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}

function cargarProcesosEspecificos($datosBBDD,$ot,$idDepartamento)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	
	$consulta = "SELECT t1.*, t2.proceso, t3.tipoProceso, t4.departamento
FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t2
on t2.id = t1.idConcepto
inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t3
on t3.id = t1.idTipo
inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosDepartamento] as t4
on t4.id = t1.idDepartamento

where presupuesto = '".$ot."' and t1.idDepartamento = ".$idDepartamento;
	
	//echo ($consulta); 	
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}





////////////
function cargarRegistrosHoraSumatorio($datosBBDD,$empleadoAbuscar,$fechaAbuscar)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$condicionFecha = "";
	
	$fechaAbuscar = date('d-m-Y');
	$fechaPosterior = date("d-m-Y",strtotime($fechaAbuscar."+ 1 days"));
	
	//echo ("la fecha es: ".$fechaAbuscar);
	//echo ("el id es: ".$empleadoAbuscar);
	$newDate = $fechaAbuscar;
	//$newDate = date("d-m-Y", strtotime($fechaAbuscar));

	//$condicionFecha = registroHora_columnaHoraInicio. " >= '".$newDate."' and ".registroHora_columnaHoraInicio. " < '".date("d-m-Y",strtotime($newDate."+ 1 days"))."'";
	$condicionFecha = registroHora_columnaHoraInicio. " >= '".$fechaAbuscar."' and ".registroHora_columnaHoraInicio. " < '".$fechaPosterior."'";
	
		
	$consulta = "SELECT  [id]    
      ,[codigoBarras]
      ,[nombreEmpleado]
      ,[horaInicio]
      ,[horaFin]
      ,[cantidad]
      ,[observaciones]
      ,[estado]

	  ,isnull(RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':' 
+ RIGHT('0'+ cast((sum(datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':'
 + RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2),0) as horas

 ,(select (RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':' 
+ RIGHT('0'+ cast((sum(datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':' 
 + RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2)) as horasTotal
 
 FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
    where idEmpleado=".$empleadoAbuscar." and ".$condicionFecha." ) as horasTotal
 


  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
    where idEmpleado=".$empleadoAbuscar." and ".$condicionFecha."
	group by [id]
    
      ,[codigoBarras]
      ,[nombreEmpleado]
      ,[horaInicio]
      ,[horaFin]
      ,[cantidad]
      ,[observaciones]
      ,[estado]
";
		
	//echo ($consulta); 	
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}


function eliminarRegistroHoraId($datosBBDD,$idRegistro)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "delete from registroHoras where id=".$idRegistro;	
 	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
		
	sqlsrv_close($conn_sis);
	
	//return $resultado;
}

function cargarEmpleadosPDA($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select t1.nombre, t1.apellidos, t2.idEmpleado from empleados as t1
	inner join [login] as t2 on t1.id = t2.idEmpleado 
	inner join permisos as t3 on t2.id=t3.id_usuario
	where t3.pda = 1 or t3.[pda_registrosHorasManuales]=2
	
	order by t1.nombre, t1.apellidos";	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


//pda gestion - FIN
//INFORMES
function cargarHorasTotalEmpleados($datosBBDD, $fechaABuscar, $fechaABuscarFin,$diasHabiles)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$newDate = date("d-m-Y", strtotime($fechaABuscar));
	$newDateFin = date("d-m-Y", strtotime($fechaABuscarFin."+ 1 days"));
	
	$consulta = "SELECT concat(t1.nombre,' ',t1.apellidos) as nombreEmpleado, t1.id as idEmpleado

, (max((datepart(HOUR,t1.horasLaborales)))) * ".$diasHabiles." as horasARealizar1
,cast ((case when  sum(datediff(second, t4.horaInicio, t4.horaFin)) is null then '0' else sum(datediff(second, t4.horaInicio, t4.horaFin))  end /3600.000) as decimal(6,2))   as horasRealizadas1
,((max((datepart(HOUR,t1.horasLaborales)))) * ".$diasHabiles.") - (cast ((case when  sum(datediff(second, t4.horaInicio, t4.horaFin)) is null then '0' else sum(datediff(second, t4.horaInicio, t4.horaFin))  end /3600.000) as decimal(6,2))) as diferencia1
	
	
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[login] as t2
	on t1.id = t2.idEmpleado
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[permisos] as t3
	on t3.id_usuario = t2.id
  left join (select * from [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] where horaInicio >= '".$newDate."' and horaFin < '".$newDateFin."')  as t4
	on t4.idEmpleado = t1.id
	
	where t2.activo >0 and (t3.pda!=0 or t3.[pda_registrosHorasManuales]!=0 )
 
  group by concat(t1.nombre,' ',t1.apellidos), t1.id
   order by concat(t1.nombre,' ',t1.apellidos), t1.id";

	//echo $consulta;


	/*$consulta = "SELECT concat(t3.nombre,' ', t3.apellidos) as nombreEmpleado,  t4.idEmpleado

, (max((datepart(HOUR,t3.horasLaborales)))) * ".$diasHabiles." as horasARealizar1
,cast ((case when  sum(datediff(second, t4.horaInicio, t4.horaFin)) is null then '0' else sum(datediff(second, t4.horaInicio, t4.horaFin))  end /3600.000) as decimal(6,2))   as horasRealizadas1
,((max((datepart(HOUR,t3.horasLaborales)))) * ".$diasHabiles.") - (cast ((case when  sum(datediff(second, t4.horaInicio, t4.horaFin)) is null then '0' else sum(datediff(second, t4.horaInicio, t4.horaFin))  end /3600.000) as decimal(6,2))) as diferencia1
	


	, (max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales)))) * ".$diasHabiles." as segundosArealizar
		,(max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales)))) * ".$diasHabiles." / 86400 as diasArealizar
		,convert(varchar,DATEADD(s,(max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales)))) * 1,0),108) as horasArealizar
		
		,case when  sum(datediff(second, t4.horaInicio, t4.horaFin)) is null then '0' else sum(datediff(second, t4.horaInicio, t4.horaFin))  end   as segundosTrabajados
		, case when sum(datediff(second, t4.horaInicio, t4.horaFin)) / 86400 is null then '0' else sum(datediff(second, t4.horaInicio, t4.horaFin)) / 86400 end as diasTrabajados
		, case when convert(varchar,DATEADD(s,sum(datediff(second, t4.horaInicio, t4.horaFin)),0),108) is null then '0' else convert(varchar,DATEADD(s,sum(datediff(second, t4.horaInicio, t4.horaFin)),0),108) end as horasTrabajados
		  
	
		,case when (sum(datediff(second, t4.horaInicio, t4.horaFin)))	
	 -  ((max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales)))) * ".$diasHabiles.") is null then '0'
	 else (sum(datediff(second, t4.horaInicio, t4.horaFin)))	
	 -  ((max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales)))) * ".$diasHabiles.") end as segundosDiferencia


	 ,case when((sum(datediff(second, t4.horaInicio, t4.horaFin)))	
	 -  ((max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales)))) * ".$diasHabiles.")) / 86400 is null then '0'
	 else ((sum(datediff(second, t4.horaInicio, t4.horaFin)))	
	 -  ((max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales)))) * ".$diasHabiles.")) / 86400 end as diasDiferencia	


	 , case when convert(varchar,DATEADD(s,
	 ((sum(datediff(second, t4.horaInicio, t4.horaFin)))	
	  -  ((max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales))))
	   * ".$diasHabiles.")),0),108) is null then '0'
	   else convert(varchar,DATEADD(s,
	 ((sum(datediff(second, t4.horaInicio, t4.horaFin)))	
	  -  ((max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales))))
	   * ".$diasHabiles.")),0),108) end as horasDiferenciaP
	
	   ,case when CONVERT(varchar, DATEADD(s, 
	   ((max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales)))) * ".$diasHabiles.")
	   -
	   (sum(datediff(second, t4.horaInicio, t4.horaFin)))	
	   
	   , 0), 108) is null then '0'
	   else CONVERT(varchar, DATEADD(s, 
	   ((max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales)))) * ".$diasHabiles.")
	   -
	   (sum(datediff(second, t4.horaInicio, t4.horaFin)))	
	   
	   , 0), 108) end as horasDiferenciaN
	
	
			,case when (sum(datediff(second, t4.horaInicio, t4.horaFin)))	
			-  ((max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales)))) * ".$diasHabiles.") is null then '0' 
			when (sum(datediff(second, t4.horaInicio, t4.horaFin)))	
		 -  ((max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales)))) * ".$diasHabiles.") >0 
			 
			 then 
				convert(varchar,DATEADD(s,
		((sum(datediff(second, t4.horaInicio, t4.horaFin)))	
		 -  ((max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales))))
		  * ".$diasHabiles.")),0),108)
			 else 
				CONVERT(varchar, DATEADD(s, 
			((max((datepart(HOUR,t3.horasLaborales) * 3600)) + max((datepart(MINUTE,t3.horasLaborales) *60)) +  max((datepart(SECOND,t3.horasLaborales)))) * ".$diasHabiles.")
			-
			(sum(datediff(second, t4.horaInicio, t4.horaFin)))	
			
			, 0), 108)
			 end as diferencia
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[permisos] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[login] as t2
	on t1.id_usuario = t2.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t3
	on t3.id = t2.idEmpleado
	 left join (select * from [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] where horaInicio >= '".$newDate."' and horaFin < '".$newDateFin."') as t4
	 on t4.idEmpleado = t3.id 
	
	  --where (t1.pda!=0 or t1.[pda_registrosHorasManuales]!=0 ) and t2.activo = 1
	  
	
	   group by t3.nombre, t3.apellidos, t4.idEmpleado
	   
	 order by t3.nombre, t3.apellidos, t4.idEmpleado  "; */
	
	
	

	/*$consulta = "SELECT t1.idEmpleado, t1.nombreEmpleado
	, (max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles." as segundosArealizar
	,(max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles." / 86400 as diasArealizar
	,convert(varchar,DATEADD(s,(max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles.",0),108) as horasArealizar
	
     ,sum(datediff(second, t1.horaInicio, t1.horaFin)) as segundosTrabajados
	 , sum(datediff(second, t1.horaInicio, t1.horaFin)) / 86400 as diasTrabajados
	 ,convert(varchar,DATEADD(s,sum(datediff(second, t1.horaInicio, t1.horaFin)),0),108) as horasTrabajados
	  

	,(sum(datediff(second, t1.horaInicio, t1.horaFin)))	
	 -  ((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles.") as segundosDiferencia
	   
	, ((sum(datediff(second, t1.horaInicio, t1.horaFin)))	
	 -  ((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles.")) / 86400 as diasDiferencia
	  
	,convert(varchar,DATEADD(s,
	((sum(datediff(second, t1.horaInicio, t1.horaFin)))	
	 -  ((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales))))
	  * ".$diasHabiles.")),0),108) as horasDiferenciaP,

		CONVERT(varchar, DATEADD(s, 
		((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles.")
		-
		(sum(datediff(second, t1.horaInicio, t1.horaFin)))	
		
		, 0), 108) as horasDiferenciaN,


		case when (sum(datediff(second, t1.horaInicio, t1.horaFin)))	
	 -  ((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles.") >0 
		 
		 then 
			convert(varchar,DATEADD(s,
	((sum(datediff(second, t1.horaInicio, t1.horaFin)))	
	 -  ((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales))))
	  * ".$diasHabiles.")),0),108)
		 else 
			CONVERT(varchar, DATEADD(s, 
		((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles.")
		-
		(sum(datediff(second, t1.horaInicio, t1.horaFin)))	
		
		, 0), 108)
		 end as diferencia




  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t2
	on t1.idEmpleado = t2.id 


  where t1.horaInicio >= '".$newDate."' and t1.horaFin < '".$newDateFin."'
 
  group by t1.idEmpleado, t1.nombreEmpleado
	";*/
	

	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarHorasTotalEmpleadosTotal($datosBBDD, $fechaABuscar, $fechaABuscarFin,$diasHabiles)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$newDate = date("d-m-Y", strtotime($fechaABuscar));
	$newDateFin = date("d-m-Y", strtotime($fechaABuscarFin."+ 1 days"));
	
	$consulta = "select 
	sum(horasARealizar1) as horasARealizar1
	,sum(horasRealizadas1) as horasRealizadas1
	,sum(diferencia1) as diferencia1
from (
	
	
	SELECT concat(t1.nombre,' ',t1.apellidos) as nombreEmpleado, t1.id as idEmpleado

, (max((datepart(HOUR,t1.horasLaborales)))) * ".$diasHabiles." as horasARealizar1
,cast ((case when  sum(datediff(second, t4.horaInicio, t4.horaFin)) is null then '0' else sum(datediff(second, t4.horaInicio, t4.horaFin))  end /3600.000) as decimal(6,2))   as horasRealizadas1
,((max((datepart(HOUR,t1.horasLaborales)))) * ".$diasHabiles.") - (cast ((case when  sum(datediff(second, t4.horaInicio, t4.horaFin)) is null then '0' else sum(datediff(second, t4.horaInicio, t4.horaFin))  end /3600.000) as decimal(6,2))) as diferencia1
	
	
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[login] as t2
	on t1.id = t2.idEmpleado
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[permisos] as t3
	on t3.id_usuario = t2.id
  left join (select * from [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] where horaInicio >= '".$newDate."' and horaFin < '".$newDateFin."')  as t4
	on t4.idEmpleado = t1.id
	
	where t2.activo >0 and (t3.pda!=0 or t3.[pda_registrosHorasManuales]!=0 )
 
  group by concat(t1.nombre,' ',t1.apellidos), t1.id
   ) as t";

   //echo $consulta;
	
	/*
	$consulta = "
	select 
	sum(horasARealizar1) as horasARealizar1
	,sum(horasRealizadas1) as horasRealizadas1
	,sum(diferencia1) as diferencia1
	
	
	
	,sum(segundosArealizar) as  segundosArealizar
,sum(segundosTrabajados) as segundosTrabajados
,sum(segundosDiferencia) as segundosDiferencia


,sum(segundosArealizar)/86400 as diasArealizar
,convert(varchar,DATEADD(s,sum(segundosArealizar),0),108) as horasArealizar

,sum(segundosTrabajados)/86400  as diasTrabajados
,convert(varchar,DATEADD(s,sum(segundosTrabajados),0),108) as horasTrabajados



,sum(segundosDiferencia)/86400  as diasDiferencia

,case when ( (sum(segundosTrabajados)	- sum(segundosArealizar)) > 0)
		then 
			convert(varchar,DATEADD(s,sum(segundosTrabajados)	- sum(segundosArealizar),0),108)
		 else 
			convert(varchar,DATEADD(s,sum(segundosArealizar) - sum(segundosTrabajados),0),108)
		 end as diferencia


from (
	
	
	
	SELECT t1.idEmpleado, t1.nombreEmpleado



, (max((datepart(HOUR,t2.horasLaborales)))) * ".$diasHabiles." as horasARealizar1
,cast ((case when  sum(datediff(second, t1.horaInicio, t1.horaFin)) is null then '0' else sum(datediff(second, t1.horaInicio, t1.horaFin))  end /3600.000) as decimal(6,2))   as horasRealizadas1
,((max((datepart(HOUR,t2.horasLaborales)))) * ".$diasHabiles.") - (cast ((case when  sum(datediff(second, t1.horaInicio, t1.horaFin)) is null then '0' else sum(datediff(second, t1.horaInicio, t1.horaFin))  end /3600.000) as decimal(6,2))) as diferencia1
	

	, (max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles." as segundosArealizar
	,(max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles." / 86400 as diasArealizar
	,convert(varchar,DATEADD(s,(max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles.",0),108) as horasArealizar
	
     ,sum(datediff(second, t1.horaInicio, t1.horaFin)) as segundosTrabajados
	 , sum(datediff(second, t1.horaInicio, t1.horaFin)) / 86400 as diasTrabajados
	 ,convert(varchar,DATEADD(s,sum(datediff(second, t1.horaInicio, t1.horaFin)),0),108) as horasTrabajados
	  

	,(sum(datediff(second, t1.horaInicio, t1.horaFin)))	
	 -  ((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles.") as segundosDiferencia
	   
	, ((sum(datediff(second, t1.horaInicio, t1.horaFin)))	
	 -  ((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles.")) / 86400 as diasDiferencia
	  
	,convert(varchar,DATEADD(s,
	((sum(datediff(second, t1.horaInicio, t1.horaFin)))	
	 -  ((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales))))
	  * ".$diasHabiles.")),0),108) as horasDiferenciaP,

		CONVERT(varchar, DATEADD(s, 
		((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles.")
		-
		(sum(datediff(second, t1.horaInicio, t1.horaFin)))	
		
		, 0), 108) as horasDiferenciaN,


		case when (sum(datediff(second, t1.horaInicio, t1.horaFin)))	
	 -  ((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles.") >0 
		 
		 then 
			convert(varchar,DATEADD(s,
	((sum(datediff(second, t1.horaInicio, t1.horaFin)))	
	 -  ((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales))))
	  * ".$diasHabiles.")),0),108)
		 else 
			CONVERT(varchar, DATEADD(s, 
		((max((datepart(HOUR,t2.horasLaborales) * 3600)) + max((datepart(MINUTE,t2.horasLaborales) *60)) +  max((datepart(SECOND,t2.horasLaborales)))) * ".$diasHabiles.")
		-
		(sum(datediff(second, t1.horaInicio, t1.horaFin)))	
		
		, 0), 108)
		 end as diferencia

  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t2
	on t1.idEmpleado = t2.id 


  where t1.horaInicio >= '".$newDate."' and t1.horaFin < '".$newDateFin."'
 
  group by t1.idEmpleado, t1.nombreEmpleado
   ) as t";

*/



 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarDatosInformeOt($datosBBDD, $otABuscar)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	/*$consulta = "SELECT t1.codigoBarras,t2.concepto,t3.cliente,t3.campana,
	sum(isnull(t1.cantidad,0)) as cantidad,
	(RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':'
	+ RIGHT('0'+ cast((sum(datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':'
	+ RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2)) as horas
	, sum(datediff(second, t1.horaInicio, t1.horaFin)) as segundos
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t2
	on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar)
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t3
	on t2.presupuesto = t3.presupuesto
	where t1.codigoBarras like '%-".$otABuscar."'
	group by t1.codigoBarras,t2.concepto, t3.cliente, t3.campana
	order by t2.concepto";*/
	
	$consulta = "SELECT t1.codigoBarras,CONCAT(t6.departamento,'/',t5.tipoProceso, '/', t4.proceso) as concepto,t3.cliente,t3.campana, 
	sum(isnull(t1.cantidad,0)) as cantidad,
	(RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':'
	+ RIGHT('0'+ cast((sum(datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':'
	+ RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2)) as horas
	, sum(datediff(second, t1.horaInicio, t1.horaFin)) as segundos



	,(SELECT 	sum(isnull(t4.cantidad,0)) as total
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t4
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t5
	on t4.[codigoBarras] = cast(t5.id as nvarchar) + '-' + cast(t5.presupuesto as nvarchar)
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t6
	on t5.presupuesto = t6.presupuesto
	where t4.codigoBarras like '%-".$otABuscar."') as cantidadTotal
	, (SELECT 
	(RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':'
	+ RIGHT('0'+ cast((sum(datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':'
	+ RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2)) as horas
	
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t2
	on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar)
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t3
	on t2.presupuesto = t3.presupuesto	where t1.codigoBarras like '%-".$otABuscar."') as horasTotal



	FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t2
	on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar)
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t3
	on t2.presupuesto = t3.presupuesto
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t4
	on t2.idConcepto = t4.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t5
	on t4.idTipoProceso = t5.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosDepartamento] as t6
	on t4.idDepartamento = t6.id

	

	where t1.codigoBarras like '%-".$otABuscar."'
	group by t1.codigoBarras,t6.departamento,t5.tipoProceso,t4.proceso, t3.cliente, t3.campana
	order by t6.departamento,t5.tipoProceso,t4.proceso";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarDatosInformeOtTotal($datosBBDD, $otABuscar)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT t3.cliente,t3.campana,
	sum(isnull(t1.cantidad,0)) as cantidad,
	(RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':'
	+ RIGHT('0'+ cast((sum(datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':'
	+ RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2)) as horas
	, sum(datediff(second, t1.horaInicio, t1.horaFin)) as segundos
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t2
	on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar)
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t3
	on t2.presupuesto = t3.presupuesto
	where t1.codigoBarras like '%-".$otABuscar."'
	group by t3.cliente, t3.campana";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarDatosInformeMediaProceso($datosBBDD, $anioAbuscar)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	

	$consulta = "SELECT 

CONCAT(t6.departamento,'/',t5.tipoProceso, '/', t4.proceso) as concepto
,sum(isnull(t2.cantidad,0)) as cantidad2

,sum(datediff(second, t2.horaInicio, t2.horaFin)) as tiempo

, (sum(cast(isnull(t2.cantidad,0) as bigint))*3600) / sum(datediff(second, t2.horaInicio, t2.horaFin)) as media

  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].registroHoras as t2
  on t2.[codigoBarras] = cast(t1.id as nvarchar) + '-' + cast(t1.presupuesto as nvarchar)

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t4
	on t1.idConcepto = t4.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t5
	on t4.idTipoProceso = t5.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosDepartamento] as t6
	on t4.idDepartamento = t6.id

  where YEAR(t2.horaInicio) = ".$anioAbuscar."

  group by t1.concepto,t6.departamento,t5.tipoProceso, t4.proceso
  order by t1.concepto,t6.departamento,t5.tipoProceso, t4.proceso";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarHorasDetallesEmpleado($datosBBDD, $fechaABuscar, $fechaABuscarFin,$nombreEmpleado) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$newDate = date("d-m-Y", strtotime($fechaABuscar));
	$newDateFin = date("d-m-Y", strtotime($fechaABuscarFin));
		
	$consulta = "SELECT t2.idConcepto,t1.[id]
	  ,t1.[nombreEmpleado]
      ,t1.[codigoBarras]
      , CONCAT(t6.departamento,'/',t5.tipoProceso, '/', t4.proceso) as concepto
      ,t1.[horaInicio]
      ,t1.[horaFin]     
	  
	  , t1.cantidad as cantidad

      ,t1.[observaciones]
      ,t1.[estado]
      ,t1.[modo]
	  ,(RIGHT('0'+ cast((datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':' 
+ RIGHT('0'+ cast(((datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':' 
 + RIGHT('0'+ cast((datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2)) as 'horas'
,cast ((case when  (datediff(second, t1.horaInicio, t1.horaFin)) is null then '0' else (datediff(second, t1.horaInicio, t1.horaFin))  end /3600.000) as decimal(6,2)) as 'horasRealizadas1'

--, cast((nullif((t2.precio*case when t2.unidades2 is null then t2.unidades else t2.unidades2 end),0)  /  case when t7.cantidad2 is null then t7.cantidad else t7.cantidad2 end  as decimal(6,3)) as  'precio/unidad'
,  cast (case when (case when t7.cantidad2 is null then t7.cantidad else t7.cantidad2 end)=0 
       then 0 
	   else      (
					(t2.precio * case when t2.unidades2 is null then t2.unidades else t2.unidades2 end )     
					/  
					case when t7.cantidad2 is null then t7.cantidad else t7.cantidad2 end    
					)   
		end as decimal(6,3)) as  'precio/unidad'

  , cast((3600.000 * t1.cantidad) / cast (datediff(second, t1.horaInicio, t1.horaFin)  as decimal(16,3)  ) as decimal(16,3)) as 'unidad/hora'
 --, CAST (ROUND( (datediff(second, t1.horaInicio, t1.horaFin)/3600.000 / nullif(t1.cantidad,0))-IsNull(nullif(t2.precio,0),0) , 3) as decimal(6,3)) as 'precio/unidad'
--,CAST (ROUND(  (datediff(second, t1.horaInicio, t1.horaFin)/3600.000 / nullif(t1.cantidad,0)) * 100/nullif(t2.precio,0) , 3) as decimal(6,3)) as porcentaje
 
 , t8.[unidad/hora] as 'unidad/horaTotalProceso'
 ,case when t8.[unidad/hora]<=0 then 0 else    cast((cast((3600.000 * t1.cantidad) / cast (datediff(second, t1.horaInicio, t1.horaFin)  as decimal(16,3)  ) as decimal(16,3))) * 100 / t8.[unidad/hora] as decimal(16,3)) end as porcentaje

  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t2
  on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar) 

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t3
  on t1.idEmpleado =t3.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t4
	on t2.idConcepto = t4.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t5
	on t4.idTipoProceso = t5.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosDepartamento] as t6
	on t4.idDepartamento = t6.id


inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t7
	on t7.presupuesto = t2.presupuesto


left join (

select idConcepto, ot, sum(cantidad) as cantidad, sum(segundos) as segundos
,case when sum(segundos)<=0 then 0 else cast(3600.000 * sum(cantidad) / cast (sum(segundos)  as decimal(16,3)  ) as decimal(16,3)) end as 'unidad/hora'
from (
select t1.nombreEmpleado,t2.idConcepto, SUBSTRING(t1.codigoBarras, CHARINDEX ( '-' , t1.codigoBarras)+1,7) as ot
, sum(t1.cantidad) as cantidad
, cast (sum(datediff(second, t1.horaInicio, t1.horaFin)) as decimal(16,3) ) as segundos
, case when cast (sum(datediff(second, t1.horaInicio, t1.horaFin))  as decimal(16,3)  )<=0 then 0 else  cast(3600.000 * sum(t1.cantidad) / cast (sum(datediff(second, t1.horaInicio, t1.horaFin))  as decimal(16,3)  ) as decimal(16,3)) end as 'unidad/hora'

FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t2
on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar) 

group by t1.nombreEmpleado, t2.idConcepto,SUBSTRING(t1.codigoBarras, CHARINDEX ( '-' , t1.codigoBarras)+1,7)

) as tabla group by idConcepto, ot

) as t8
on t8.idConcepto = t2.idConcepto and SUBSTRING(t1.codigoBarras, CHARINDEX ( '-' , t1.codigoBarras)+1,7) =t8.ot
  
  where 

  t1.nombreEmpleado = '".$nombreEmpleado."'
  and t1.horaInicio >= '".$newDate."' and t1.horaFin < '".date("d-m-Y",strtotime($newDateFin."+ 1 days"))."'
  order by horaInicio, concepto";	
  

  //echo $consulta;

 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function cargarDatosInformeOtDetalle($datosBBDD, $codigoBarrasABuscar)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	/*$consulta = "SELECT t1.nombreEmpleado,t2.concepto,t1.codigoBarras,
 sum(isnull(t1.cantidad,0)) as cantidad,
(RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':' 
+ RIGHT('0'+ cast((sum(datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':' 
 + RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2)) as horas
 , sum(datediff(second, t1.horaInicio, t1.horaFin)) as segundos
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t2
  on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar)
  where t1.codigoBarras ='".$codigoBarrasABuscar."'
  group by t1.nombreEmpleado, t2.concepto,t1.codigoBarras";*/
	
	
	$consulta = "SELECT t1.nombreEmpleado,t2.concepto,t1.codigoBarras,
 sum(isnull(t1.cantidad,0)) as cantidad,
(RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':' 
+ RIGHT('0'+ cast((sum(datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':' 
 + RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2)) as horas
 , sum(datediff(second, t1.horaInicio, t1.horaFin)) as segundos,
 (SELECT sum(isnull(t1.cantidad,0))
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t2
  on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar)
  where t1.codigoBarras ='".$codigoBarrasABuscar."'
  group by t1.codigoBarras
 ) as cantidadTotal,
 ( SELECT (RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':' 
+ RIGHT('0'+ cast((sum(datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':' 
 + RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2)) as horas
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t2
  on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar)
  where t1.codigoBarras ='".$codigoBarrasABuscar."'
  group by t1.codigoBarras
 ) as horasTotal

  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t2
  on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar)
  where t1.codigoBarras ='".$codigoBarrasABuscar."'
  group by t1.nombreEmpleado, t2.concepto,t1.codigoBarras";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}



function cargarDatosInformeOtCostes($datosBBDD, $ot) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);

	
	$consulta = "SELECT  t12.cliente, t12.campana, t2.departamento, t3.tipoProceso, t4.proceso
,convert(varchar,DATEADD(dd, 0, DATEDIFF(dd, 0, t5.horaInicio)),23) as fechaInicio
,t5.nombreEmpleado, sum(t5.cantidad) as cantidad

,case when convert(varchar,DATEADD(s,sum(datediff(second, horaInicio, horaFin)),0),108) is null then '0' else convert(varchar,DATEADD(s,sum(datediff(second, horaInicio, horaFin)),0),108) end as horasTrabajados 
,sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) as segundosTrabajados
,cast(case when t5.precioHora is null then 0.000 else t5.precioHora end * case when sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) is null then 0.000 else sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) end / 3600.000 as decimal(16,3))  as 'costeHora'
,cast(isnull(sum(t5.cantidad) /(sum(datediff(SECOND, t5.horaInicio, t5.horaFin))/60.00),0)*60 as decimal(6,0)) as media
FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t12
  on t12.presupuesto = t1.presupuesto

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosDepartamento] as t2
  on t1.idDepartamento = t2.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t3
  on t1.idTipo = t3.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t4
  on t1.idConcepto = t4.id
  left join (
  select t1.*, t2.precioHora
   from [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t2
  on t1.idEmpleado = t2.id) as t5
  on CONCAT(t1.id,'-',t1.presupuesto) = t5.codigoBarras

  where t1.presupuesto = '".$ot."' and t4.mostrarEnInforme = 1

  group by t12.cliente, t12.campana,t2.departamento, t3.tipoProceso, t4.proceso, t5.nombreEmpleado
  ,convert(varchar,DATEADD(dd, 0, DATEDIFF(dd, 0, t5.horaInicio)),23), t5.precioHora

  order by  t2.departamento, t3.tipoProceso, t4.proceso,convert(varchar,DATEADD(dd, 0, DATEDIFF(dd, 0, t5.horaInicio)),23) desc,  t5.nombreEmpleado

 
  ";

	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarDatosInformeOtCostesTotales($datosBBDD, $ot)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);


	$anio = substr( $ot,0,2);
	$anio = $anio + 2000;

	$consulta = "select  ISNULL(sum(tabla.cantidad),0) as cantidad, ISNULL(sum(tabla.segundosTrabajados),0) as segundosTrabajados, sum(tabla.costeHora) as costeHora, tabla.fechaTerminado, tabla.fechaCompromiso, tabla.fechaInicioReal, datediff(day, tabla.fechaCompromiso, tabla.fechaTerminado) as satisfaccion, datediff(day, tabla.fechaInicioReal, tabla.fechaTerminado) as tiempoRealizacion 
, tabla.comprasTerceros, tabla.importeFactura, tabla.importeFacturaClayma, ISNULL(sum(tabla.costePapel),0) as costePapel, ISNULL(sum(tabla.costeClick),0) as costeClick, tabla.cantidadPresupuesto, ISNULL(sum(tabla.precioPapel_Presupuesto),0) as precioPapel_Presupuesto, ISNULL(sum(tabla.precioClick_Presupuesto),0) as precioClick_Presupuesto
, tabla.tantoPorCientoTransporte, max(tabla.pesoGramos) as pesoGramos
from (
SELECT  t6.cliente, t6.campana, t2.departamento, t3.tipoProceso, t4.proceso
,convert(varchar,DATEADD(dd, 0, DATEDIFF(dd, 0, t5.horaInicio)),105) as fechaInicio
,t5.nombreEmpleado, sum(t5.cantidad) as cantidad

,case when convert(varchar,DATEADD(s,sum(datediff(second, horaInicio, horaFin)),0),108) is null then '0' else convert(varchar,DATEADD(s,sum(datediff(second, horaInicio, horaFin)),0),108) end as horasTrabajados 
,sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) as segundosTrabajados
,cast(case when t5.precioHora is null then 0.000 else t5.precioHora end * case when sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) is null then 0.000 else sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) end / 3600.000 as decimal(16,3))  as 'costeHora'
, t6.fechaTerminado, t6.fechaCompromiso, t6.fechaInicioReal

, case when t9.comprasTerceros is null then 0 else t9.comprasTerceros end  as comprasTerceros 
, case when t7.precioNeto is null then 0 else  t7.precioNeto end as importeFactura
, case when t8.precioNeto is null then 0 else  t8.precioNeto end as importeFacturaClayma

, sum(t10.papel) as costePapel
, sum(t11.click) as costeClick

, case when t6.cantidad2 is null or t6.cantidad2 = '' then t6.cantidad else t6.cantidad2 end as cantidadPresupuesto

, case when t1.unidades2>0 then sum(t12.precio * t1.unidades2) else  sum(t12.precio*t1.unidades) end  as precioPapel_Presupuesto
, case when t1.unidades2>0 then sum(t13.precioClick * t1.impresionNumeroCaras * t1.unidades2) else  sum(t13.precioClick * t1.impresionNumeroCaras * t1.unidades) end  as precioClick_Presupuesto
, t14.tantoPorCientoTransporte, max(t1.pesoGramos) as pesoGramos
/*
,case when t6.clayma = 1
		then case when t8.precioNeto is null then 0 else  t8.precioNeto end - case when t9.comprasTerceros is null then 0 else t9.comprasTerceros end - (case when t5.precioHora is null then 0.000 else t5.precioHora end * case when sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) is null then 0.000 else sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) end / 60.000)
		else case when t7.precioNeto is null then 0 else  t7.precioNeto end - case when t9.comprasTerceros is null then 0 else t9.comprasTerceros end - (case when t5.precioHora is null then 0.000 else t5.precioHora end * case when sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) is null then 0.000 else sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) end / 60.000)
 end as beneficios
 */

FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t6
  on t6.presupuesto = t1.presupuesto

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosDepartamento] as t2
  on t1.idDepartamento = t2.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t3
  on t1.idTipo = t3.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t4
  on t1.idConcepto = t4.id
  left join (
  select t1.*, t2.precioHora
   from [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t2
  on t1.idEmpleado = t2.id) as t5
  on CONCAT(t1.id,'-',t1.presupuesto) = t5.codigoBarras

  left join [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anio."] as t7
  on t6.presupuesto = t7.presupuesto
    left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anio."] as t8
  on t6.presupuesto = t8.presupuesto

  left join (SELECT sum(t7.total) as comprasTerceros, t8.presupuesto
FROM [".$datosBBDD->bbddBBDD."].[dbo].[comprasTercerosDetalles] as t7
inner join [".$datosBBDD->bbddBBDD."].[dbo].[compraTerceros] as t8
on t7.pedido = t8.pedido
group by t8.presupuesto
) as t9
  on t9.presupuesto = t6.presupuesto


   left join (
  SELECT t1.id, t2.precio * t1.cantidad as papel
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas_papel] as t2
on t1.idPapelTamano = t2.idTamanio and t1.idPapelTipo = t2.idTipo and t1.idPapelAcabado = t2.idAcabado and t1.idPapelGramaje = t2.idGramaje
where  t2.precio * t1.cantidad   is not null and t1.idPapelOrigen = 1
  ) as t10
  on t10.id = t5.id

   left join (
  
  SELECT t3.id as idRegistroHora,t1.id as idImpresora, t2.precioClick, t3.cantidad, t2.precioClick * t3.cantidad * ISNULL(t3.impresionNumeroCaras,1) as click
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[L_impresoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[L_impresorasTipo] as t2
  on t1.tipoImpresora = t2.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras]  as  t3
  on t3.idImpresoras = t1.id
  ) as t11
  on t11.idRegistroHora = t5.id

  left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas_papel] as t12
	on t1.idMaterialPapel = t12.id

  left join [".$datosBBDD->bbddBBDD."].[dbo].[L_impresorasTipo]  as t13
   on t1.idTipoImpresora = t13.id

     left join [".$datosBBDD->bbddBBDD."].[dbo].[datosGenericosFranqueo] as t14
  on t14.anio = '".$anio."'

  where t1.presupuesto = '".$ot."'

  group by t6.cliente, t6.campana,t2.departamento, t3.tipoProceso, t4.proceso, t5.nombreEmpleado, t6.fechaTerminado
  ,convert(varchar,DATEADD(dd, 0, DATEDIFF(dd, 0, t5.horaInicio)),105)  , t5.precioHora,t6.fechaTerminado, t9.comprasTerceros, t7.precioNeto, t8.precioNeto,t6.clayma
  , case when t6.cantidad2 is null or t6.cantidad2 = '' then t6.cantidad else t6.cantidad2 end, t6.fechaCompromiso, t6.fechaInicioReal
  , t1.unidades2, t14.tantoPorCientoTransporte

  ) as tabla
  
  group by tabla.cliente, tabla.campana, tabla.fechaTerminado--, tabla.departamento, tabla.tipoProceso, tabla.proceso , tabla.nombreEmpleado --(para desglosar los costes por empleado)
  , tabla.comprasTerceros, tabla.importeFactura, tabla.importeFacturaClayma, tabla.cantidadPresupuesto, tabla.tantoPorCientoTransporte, tabla.fechaCompromiso, tabla.fechaInicioReal";
	//echo $consulta;

	/*
	$consulta = "select  sum(tabla.cantidad) as cantidad, sum(tabla.segundosTrabajados) as segundosTrabajados, sum(tabla.costeHora) as costeHora, tabla.fechaTerminado
, tabla.comprasTerceros, tabla.importeFactura, tabla.importeFacturaClayma, sum(tabla.costePapel) as costePapel, sum(tabla.costeClick) as costeClick, tabla.cantidadPresupuesto
from (
SELECT  t6.cliente, t6.campana, t2.departamento, t3.tipoProceso, t4.proceso
,convert(varchar,DATEADD(dd, 0, DATEDIFF(dd, 0, t5.horaInicio)),105) as fechaInicio
,t5.nombreEmpleado, sum(t5.cantidad) as cantidad

,case when convert(varchar,DATEADD(s,sum(datediff(second, horaInicio, horaFin)),0),108) is null then '0' else convert(varchar,DATEADD(s,sum(datediff(second, horaInicio, horaFin)),0),108) end as horasTrabajados 
,sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) as segundosTrabajados
,cast(case when t5.precioHora is null then 0.000 else t5.precioHora end * case when sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) is null then 0.000 else sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) end / 3600.000 as decimal(16,3))  as 'costeHora'
, t6.fechaTerminado

, case when t9.comprasTerceros is null then 0 else t9.comprasTerceros end  as comprasTerceros 
, case when t7.precioNeto is null then 0 else  t7.precioNeto end as importeFactura
, case when t8.precioNeto is null then 0 else  t8.precioNeto end as importeFacturaClayma

, sum(t10.papel) as costePapel
, sum(t11.click) as costeClick

, case when t6.cantidad2 is null or t6.cantidad2 = '' then t6.cantidad else t6.cantidad2 end as cantidadPresupuesto


--,case when t6.clayma = 1
--		then case when t8.precioNeto is null then 0 else  t8.precioNeto end - case when t9.comprasTerceros is null then 0 else t9.comprasTerceros end - (case when t5.precioHora is null then 0.000 else t5.precioHora end * case when sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) is null then 0.000 else sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) end / 60.000)
--		else case when t7.precioNeto is null then 0 else  t7.precioNeto end - case when t9.comprasTerceros is null then 0 else t9.comprasTerceros end - (case when t5.precioHora is null then 0.000 else t5.precioHora end * case when sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) is null then 0.000 else sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) end / 60.000)
 --end as beneficios


FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t6
  on t6.presupuesto = t1.presupuesto

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosDepartamento] as t2
  on t1.idDepartamento = t2.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t3
  on t1.idTipo = t3.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t4
  on t1.idConcepto = t4.id
  left join (
  select t1.*, t2.precioHora
   from [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t2
  on t1.idEmpleado = t2.id) as t5
  on CONCAT(t1.id,'-',t1.presupuesto) = t5.codigoBarras

  left join [".$datosBBDD->bbddBBDD."].[dbo].[facturas2024] as t7
  on t6.presupuesto = t7.presupuesto
    left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma2024] as t8
  on t6.presupuesto = t8.presupuesto

  left join (SELECT sum(t7.total) as comprasTerceros, t8.presupuesto
FROM [".$datosBBDD->bbddBBDD."].[dbo].[comprasTercerosDetalles] as t7
inner join [".$datosBBDD->bbddBBDD."].[dbo].[compraTerceros] as t8
on t7.pedido = t8.pedido
group by t8.presupuesto
) as t9
  on t9.presupuesto = t6.presupuesto


   left join (
  SELECT t1.id, t2.precio * t1.cantidad as papel
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas_papel] as t2
on t1.idPapelTamano = t2.idTamanio and t1.idPapelTipo = t2.idTipo and t1.idPapelAcabado = t2.idAcabado and t1.idPapelGramaje = t2.idGramaje
where  t2.precio * t1.cantidad   is not null
  ) as t10
  on t10.id = t5.id

   left join (
  
  SELECT t3.id as idRegistroHora,t1.id as idImpresora, t2.precioClick, t3.cantidad, t2.precioClick * t3.cantidad as click
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[L_impresoras] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[L_impresorasTipo] as t2
  on t1.tipoImpresora = t2.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras]  as  t3
  on t3.idImpresoras = t1.id
  ) as t11
  on t11.idRegistroHora = t5.id

  where t1.presupuesto = '".$ot."'

  group by t6.cliente, t6.campana,t2.departamento, t3.tipoProceso, t4.proceso, t5.nombreEmpleado, t6.fechaTerminado
  ,convert(varchar,DATEADD(dd, 0, DATEDIFF(dd, 0, t5.horaInicio)),105)  , t5.precioHora,t6.fechaTerminado, t9.comprasTerceros, t7.precioNeto, t8.precioNeto,t6.clayma, case when t6.cantidad2 is null or t6.cantidad2 = '' then t6.cantidad else t6.cantidad2 end

  ) as tabla 
  group by tabla.cliente, tabla.campana, tabla.fechaTerminado--, tabla.departamento, tabla.tipoProceso, tabla.proceso , tabla.nombreEmpleado --(para desglosar los costes por empleado)
  , tabla.comprasTerceros, tabla.importeFactura, tabla.importeFacturaClayma, tabla.cantidadPresupuesto";
*/
	
 	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


///////////////////////////////
function cargarDetallesInformeOtCostesTotales($datosBBDD, $ot)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);

	$consulta = "SELECT t1.id as idRegistroHoras, t1.nombreEmpleado, t3.impresoras, t9.tipoImpresora, t9.precioClick
 , t4.tamano, t5.tipo, t6.acabado, t7.gramaje, t8.origen, t2.precio as precioMaterial, t1.cantidad as cantidadEmpleado
 , ISNULL(t1.impresionNumeroCaras,1) as numeroCaras, t2.precio * t1.cantidad * ISNULL(t1.impresionNumeroCaras,1) as costePapel
 , t9.precioClick * t1.cantidad  * ISNULL(t1.impresionNumeroCaras,1) as costeClick
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1
  left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas_papel] as t2
on t1.idPapelTamano = t2.idTamanio and t1.idPapelTipo = t2.idTipo and t1.idPapelAcabado = t2.idAcabado and t1.idPapelGramaje = t2.idGramaje

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_impresoras] as t3
on t1.idImpresoras = t3.id

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanio] as t4
on t1.idPapelTamano = t4.id

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTipo] as t5
on t1.idPapelTipo = t5.id

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelAcabado] as t6
on t1.idPapelAcabado = t6.id

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelGramaje] as t7
on t1.idPapelGramaje = t7.id

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelOrigen] as t8
on t1.idPapelOrigen = t8.id

left join [".$datosBBDD->bbddBBDD."].[dbo].[L_impresorasTipo] as t9
on t9.id = t3.tipoImpresora

where SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) = '".$ot."' and t1.idPapelOrigen = 1";
	//echo consulta

	
	
 	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarDetallesPresupustoInformeOtCostesTotales($datosBBDD, $ot) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);

	$consulta = "SELECT t1.id,t3.nombre, t6.departamento, t7.tipoProceso, t8.proceso, t9.tamano, t10.tipo, t11.acabado, t12.gramaje, t5.tipoImpresora, t1.unidades, t1.unidades2
	,case when t1.unidades2>0 then t1.unidades2 else  t1.unidades end  as unidadesParaUtilizar
	, t1.impresionNumeroCaras, t4.precio as precioMaterial, t5.precioClick
,case when t1.unidades2>0 then t4.precio * t1.unidades2 else  t4.precio*t1.unidades end  as costePapel
,case when t1.unidades2>0 then t5.precioClick * t1.impresionNumeroCaras * t1.unidades2 else  t5.precioClick * t1.impresionNumeroCaras * t1.unidades end  as costeClick

FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
on t1.presupuesto = t2.presupuesto
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t3
on t2.idComercial = t3.id
left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas_papel] as t4
on t1.idMaterialPapel = t4.id
left join [".$datosBBDD->bbddBBDD."].[dbo].[L_impresorasTipo] as t5
on t1.idTipoImpresora = t5.id
left join [".$datosBBDD->bbddBBDD."].[dbo].[procesosDepartamento] as t6
on t1.idDepartamento = t6.id
left join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t7
on t1.idTipo = t7.id
left join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t8
on t1.idConcepto = t8.id
left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanio] as t9
on t4.idTamanio = t9.id
left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTipo] as t10
on t4.idTipo = t10.id
left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelAcabado] as t11
on t4.idAcabado = t11.id
left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelGramaje] as t12
on t4.idGramaje = t12.id

where t2.presupuesto = '".$ot."' and case when t1.unidades2>0 then t1.unidades2 else t1.unidades end > 0 and t8.mostrarEnInforme = 1

order by departamento, tipoProceso, proceso, tipoImpresora";
	//echo consulta

	
	
 	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function cargarOtPorFechas($datosBBDD, $fechaInicio, $fechaFin) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);

	$numeroDiaInicio =  date("d", strtotime($fechaInicio));
	$numeroMesInicio =  date("m", strtotime($fechaInicio));
	$numeroAnioInicio=  date("y", strtotime($fechaInicio));

	$fechaInicio1 = $numeroDiaInicio . '-' . $numeroMesInicio . '-'  . $numeroAnioInicio;

	$numeroDiaFin =  date("d", strtotime($fechaFin));
	$numeroMesFin =  date("m", strtotime($fechaFin));
	$numeroAnioFin =  date("Y", strtotime($fechaFin));

	$fechaFin1 = $numeroDiaFin . '-' . $numeroMesFin . '-'  . $numeroAnioFin;





	$consulta = "SELECT  SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) as presupuesto
, sum(datediff(SECOND, t1.horaInicio, t1.horaFin))/60 as minutosTrabajados
 ,sum(datediff(SECOND, t1.horaInicio, t1.horaFin))/60/60 as horasTrabajados
 , case when t2.finalizado='FINALIZADO' then t2.finalizado  else 'EN  PROCESO' end as 'finalizado'
 ,t3.cantidad, t3.cantidad2, sum(t1.cantidad)  as  cantidadRealizada
 ,case when t3.cantidad2=0 or sum(t1.cantidad)=0  then 0 else   CAST(SUM(t1.cantidad) AS BIGINT)*100/t3.cantidad2 end as tantoPorcientoRealizado
 
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1

  left join (select tabla.*, 'FINALIZADO' as finalizado from (SELECT t1.*, t2.inicial
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
  on t1.idComercial = t2.id
  where t1.fechaTerminado <= GETDATE()
   or t1.numNoFactura is not null
  or t1.presupuesto  in  (select presupuesto from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestosFacturadosTodosLosAnios] 
 ) ) as tabla) as t2
 on SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) = t2.presupuesto

 left join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t3
 on SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) = t3.presupuesto



   where t1.horaInicio >='".$fechaInicio1."' and t1.horaFin <  dateadd(day,1,'".$fechaFin1."')


   group by SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) , t2.finalizado,t3.cantidad, t3.cantidad2


   order by finalizado, 1";
	//echo $consulta;

	
	
 	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}




function cargarOtPorFechasTotal($datosBBDD, $fechaInicio, $fechaFin) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);

	$numeroDiaInicio =  date("d", strtotime($fechaInicio));
	$numeroMesInicio =  date("m", strtotime($fechaInicio));
	$numeroAnioInicio=  date("y", strtotime($fechaInicio));

	$fechaInicio1 = $numeroDiaInicio . '-' . $numeroMesInicio . '-'  . $numeroAnioInicio;

	$numeroDiaFin =  date("d", strtotime($fechaFin));
	$numeroMesFin =  date("m", strtotime($fechaFin));
	$numeroAnioFin =  date("Y", strtotime($fechaFin));

	$fechaFin1 = $numeroDiaFin . '-' . $numeroMesFin . '-'  . $numeroAnioFin;





	$consulta = "select sum(minutosTrabajados) as minutosTrabajados, sum(horasTrabajados) as horasTrabajados, sum(cantidad) as cantidad, sum(cantidad2) as cantidad2, sum(cantidadRealizada) as cantidadRealizada
,case when sum(cantidad2)=0 then 0 when sum(cantidad)=0 then 0 else   sum(cantidadRealizada)*100/sum(cantidad2) end as tantoPorcientoRealizado 
from (

SELECT  SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) as presupuesto
, sum(datediff(SECOND, t1.horaInicio, t1.horaFin))/60 as minutosTrabajados
 ,sum(datediff(SECOND, t1.horaInicio, t1.horaFin))/60/60 as horasTrabajados
 , case when t2.finalizado='FINALIZADO' then t2.finalizado  else 'EN  PROCESO' end as 'finalizado',t3.cantidad, t3.cantidad2, sum(t1.cantidad)  as  cantidadRealizada
 , case when t3.cantidad2=0 then 0 else   sum(t1.cantidad)*100/t3.cantidad2 end as tantoPorcientoRealizado
 
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras] as t1

  left join (select tabla.*, 'FINALIZADO' as finalizado from (SELECT t1.*, t2.inicial
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
  on t1.idComercial = t2.id
  where t1.fechaTerminado <= GETDATE()
   or t1.numNoFactura is not null
  or t1.presupuesto  in  (select presupuesto from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestosFacturadosTodosLosAnios] 
 ) ) as tabla) as t2
 on SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) = t2.presupuesto

 left join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t3
 on SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) = t3.presupuesto



	where t1.horaInicio >='".$fechaInicio1."' and t1.horaFin <  dateadd(day,1,'".$fechaFin1."')


   group by SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) , t2.finalizado,t3.cantidad, t3.cantidad2


   ) as tabla";
	//echo $consulta;

	
	
 	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

//////////////////////////////

function cargarImpresoras($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[L_impresoras] as t1
 	order by impresoras";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function cargarTamaniosPapel($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanio] as t1
 	order by tamano";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function modificarTamanioPapel($datosBBDD, $id,$valor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanio] set tamano = '".$valor."' where id = ".$id;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el tamaño.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Tamaño modificado";
	}
	
	return $mensaje;	
}

function cargarTiposPapel($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTipo] as t1
 	order by tipo";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function modificarTipoPapel($datosBBDD, $id,$valor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTipo] set tipo = '".$valor."' where id = ".$id;	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el tipo.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Tipo modificado";
	}
	
	return $mensaje;	
}



function cargarAcabadoPapel($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[L_papelAcabado] as t1
 	order by acabado";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function modificarAcabadoPapel($datosBBDD, $id,$valor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[L_papelAcabado] set acabado = '".$valor."' where id = ".$id;	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el acabado.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Acabado modificado";
	}
	
	return $mensaje;	
}

function cargarGramajePapel($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[L_papelGramaje] as t1
 	order by gramaje";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function modificarGramajePapel($datosBBDD, $id,$valor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[L_papelGramaje] set gramaje = '".$valor."' where id = ".$id;	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el gramaje.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Gramaje modificado";
	}
	
	return $mensaje;	
}



function cargarOrigenPapel($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[L_papelOrigen] as t1
 	order by origen";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}



function cargarConceptosGF($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_concepto] as t1
 	order by nombreConcepto";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}



function cargarSubConceptos1GF($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_subconcepto1] as t1 
  ".$condicion." 
 	order by nombreSubconcepto";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarSubConceptos2GF($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_subconcepto2] as t1
  ".$condicion." 
 	order by nombreSubconcepto2";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}
//INFORMES - FIN

function cargarComerciales($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t1
 	order by nombre";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function cargarPaises($datosBBDD,$condicion="")
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *  FROM [".$datosBBDD->bbddBBDD."].[dbo].[paises] ".$condicion." order by nombreComun";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function cargarPresupuestadores($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	$consulta = "SELECT *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t1
 	order by nombre";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function verProximoNumPresupuesto($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	//$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestoNum]";
	$consulta = "SELECT  t1.* FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestoNum] as t1

inner join (select max(id) as id2 FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestoNum] where secuencial >99

) as t2
   
on t1.id = t2.id2";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


	
	
function actualizarNumPresupuesto($datosBBDD, $anioActual, $mesActual, $nuevoSecuencial)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	//$consulta = "update  [".$datosBBDD->bbddBBDD."].[dbo].[presupuestoNum] set anio = '".$anioActual."', mes = '".$mesActual."', secuencial=".$nuevoSecuencial;
	
	
	/*$anioActual="21";
	$mesActual="01";
	$nuevoSecuencial=109;*/
		
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[presupuestoNum] (anio,mes, secuencial) values ('".$anioActual."', '".$mesActual."',".$nuevoSecuencial.")";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{	
		//sqlsrv_close($conn_sis);
		//echo("Error: No se ha podido guardar\nIntentar otra vez");
		return ("Error");
		//die( print_r( sqlsrv_errors(), true) );
	}
	
	sqlsrv_close($conn_sis);
	
	return;
}

function cargarFormasDePago($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t1
 	order by concepto";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarFormasDePagoComprasATerceros($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[formaDePagoCompraTerceros] as t1
 	order by concepto";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function insertarPresupuesto($datosBBDD,$presupuestoNuevo, $comercial, $detallada, $cliente, $campania, $cantidad, $pedCliente, $direccion, $cp, $poblacion, $formaPago,$persona,$mostrarTotalPresupuesto,$mostrarTotalFranqueo,$mostrarTotalFranqueoImporte,$notaCibeles,$otBajada, $origenValor,$observaciones2,$trabajoIniciado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaActual = "";
	
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] ([presupuesto],[cliente],[persona],[direccion],[poblacion],[cp],notaCibeles,[idFormaPago],[campana],[cantidad],[idComercial],[pedcli],fechaAceptacion,fechaCompromiso,fechaTerminado,[factura],[detallada],[idVisualizarTotalFranqueo],[idVisualizarTotalPresu],[importeFranqueo],otBajada,[campana2],[cantidad2], clayma,[observaciones2],trabajoIniciado) values ('".$presupuestoNuevo."','".$cliente."','".$persona."','".$direccion."','".$poblacion."','".$cp."','".$notaCibeles."',".$formaPago.",'".$campania."',".$cantidad.",".$comercial.",'".$pedCliente."',null,null,null,'',".$detallada.",".$mostrarTotalFranqueo.",".$mostrarTotalPresupuesto.",'".$mostrarTotalFranqueoImporte."',".$otBajada.",'".$campania."',".$cantidad.",".$origenValor.", '".$observaciones2."',".$trabajoIniciado.")";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	/*while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}*/
	
	sqlsrv_close($conn_sis);
	
	return;
}



function insertarPresupuestoMensual($datosBBDD,$numMensual, $comercial, $detallada, $cliente, $campania, $cantidad, $pedCliente, $direccion, $cp, $poblacion, $formaPago,$persona,$mostrarTotalPresupuesto,$mostrarTotalFranqueo,$mostrarTotalFranqueoImporte,$notaPresupuesto,$otBajada,$otAbierta, $fechaInicio, $fechaAceptacion, $fechaFin,$fechaCompromiso,$clayma=0,$observaciones2='')
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaActual = "";
	
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] ([presupuesto],[cliente],[persona],[direccion],[poblacion],[cp],notaCibeles,[idFormaPago],[campana],[cantidad],[idComercial],[pedcli],[factura],[detallada],[idVisualizarTotalFranqueo],[idVisualizarTotalPresu],[importeFranqueo],otBajada,[campana2],[cantidad2],otAbierta,fechaInicioReal,fechaAceptacion, fechaTerminado, fechaCompromiso,clayma,[observaciones2]) values ('".$numMensual."','".$cliente."','".$persona."','".$direccion."','".$poblacion."','".$cp."','',".$formaPago.",'".$campania."',".$cantidad.",".$comercial.",'".$pedCliente."','',".$detallada.",".$mostrarTotalFranqueo.",".$mostrarTotalPresupuesto.",'".$mostrarTotalFranqueoImporte."',".$otBajada.",'".$campania."',".$cantidad.",".$otAbierta.",'".$fechaInicio."','".$fechaAceptacion."','".$fechaFin."','".$fechaCompromiso."',".$clayma.", '".$observaciones2."')";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	/*while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}*/
	
	sqlsrv_close($conn_sis);
	
	return;
}


function verDatosClientePorSubcliente($datosBBDD,$subcliente)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientes] where subcliente = '".$subcliente."'";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verDatosClientePorSubclienteClayma($datosBBDD,$subcliente)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] where subcliente = '".$subcliente."'";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function cargarTipoDeProceso($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] order by orden";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarProcesoBBDD($datosBBDD,$tipoProceso,$departamento)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[procesos] where idTipoProceso=".$tipoProceso." and idDepartamento=".$departamento." order by proceso";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarDepartamentoProcesoBBDD($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[procesosDepartamento] order by departamento";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	

}


function cargarProcesoARecibir($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[procesos_ARecibir] order by proceso";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function cargarProcesosMaterialesAFabricar($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[procesos_AFabricar] order by proceso";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarProcesosManipulados($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[procesos_Manipulados] order by proceso";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function cargarProcesosDistribucion($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[procesos_Distribucion] order by proceso";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function cargarProcesosAlmacenamiento($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[procesos_Almacenamiento] order by proceso";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarProcesosTratamientoSobrante($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[procesos_Sobrante] order by proceso";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}



function modificarPresupuesto($datosBBDD, $presupuesto, $comercial, $detallada, $cliente, $campania, $cantidad, $pedCliente, $direccion, $cp, $poblacion, $formaPago, $persona,$mostrarTotalPresupuesto,$mostrarTotalFranqueo,$mostrarTotalFranqueoImporte,$notaPresupuesto, $origenValor, $campaniaObservacion,$trabajoIniciado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [presupuestos] set 
	cliente = '".$cliente."', 
	persona = '".$persona."', 
	direccion = '".$direccion."', 
	poblacion = '".$poblacion."', 
	cp = '".$cp."', 
	idFormaPago = ".$formaPago.", 
	idComercial = ".$comercial.", 
	detallada = ".$detallada.", 
	campana = '".$campania."', 
	cantidad = ".$cantidad.",	
	pedcli = '".$pedCliente."',	
	notaCibeles = '".$notaPresupuesto."',	
	idVisualizarTotalPresu = ".$mostrarTotalPresupuesto.",
	idVisualizarTotalFranqueo = ".$mostrarTotalFranqueo.",
	importeFranqueo = ".$mostrarTotalFranqueoImporte.",
	clayma = ".$origenValor.", 
	campana2 = '".$campania."',
	cantidad2 = ".$cantidad.",
	campanaObservacion = '".$campaniaObservacion."',
	trabajoIniciado = ".$trabajoIniciado."
	
	
	where presupuesto = '".$presupuesto."'";	
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;	
}


function insertarDetallePresupuesto ($datosBBDD, $presupuesto,$tipoProceso,$proceso,$descripcion,$nota,$unidad,$precio,$orden,$idDepartamento,$notaAdmonProd,$exentoIVA,$idPapel,$idImpresoraDetalles,$numCaras,$idMaterialPapel_tamanoFinal,$peso,$idGFConcepto,$idGFMetrosCuadrados)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] (presupuesto, idConcepto, idTipo, unidades, precio, descripcion, notaCibeles, orden, idDepartamento, unidades2,notaAdmonProd,exentoIVA,idMaterialPapel,idTipoImpresora,impresionNumeroCaras,idPapelTamanioFinal,pesoGramos,idGFConcepto,idGFMetrosCuadrados) values ('".$presupuesto."',".$proceso.",".$tipoProceso.",".$unidad.",".$precio.",'".$descripcion."','".$nota."',".$orden.",".$idDepartamento.",".$unidad.",'".$notaAdmonProd."',".$exentoIVA.",".$idPapel.",".$idImpresoraDetalles.",".$numCaras.",".$idMaterialPapel_tamanoFinal.",".$peso.",".$idGFConcepto.",".$idGFMetrosCuadrados.")";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	if( $resultado === false)
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}
	//sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar.\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Detalle Insertado");
		
		
		$consulta = "SELECT SCOPE_IDENTITY() AS 'numeroDetalle'";
		
		$stmt  = sqlsrv_query($conn_sis, $consulta);
		
		$mensaje="";

		if( sqlsrv_fetch($stmt)  === false ) 
		{
			$mensaje = "Error: no se ha podido ver la id del detalle'.\n".$stmt ."\n".$consulta."\n";
			$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		}
		else
		{

			$mensaje = sqlsrv_get_field($stmt , 0);
			//$mensaje = $mensaje."|||Usuario insertado";

		}
		sqlsrv_close($conn_sis);		
			
	}
	
	return $mensaje;	
	//return $resultado;
}


function modificarDetallePresupuesto ($datosBBDD, $idDetalle,$proceso,$descripcion,$nota,$unidad,$precio,$orden,$notaAdmonProd,$exentoIVA,$peso)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle]
	set idConcepto = ".$proceso.", descripcion='".$descripcion."', notaCibeles='".$nota."', unidades=".$unidad.", precio=".$precio.", orden=".$orden.", unidades2 = ".$unidad.", notaAdmonProd = '".$notaAdmonProd."', exentoIVA = ".$exentoIVA.", pesoGramos = ".$peso."
	 where id = ".$idDetalle;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Detalle Modificado");
	}
	
	return $mensaje;

	//return $resultado;
}


function modificarDetallePresupuesto2 ($datosBBDD, $idDetalle,$nota,$unidad2,$notaAdmonProd)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle]
	set notaCibeles='".$nota."', unidades2=".$unidad2.", notaAdmonProd='".$notaAdmonProd."'
	where id = ".$idDetalle;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Detalle Modificado");
	}
	
	return $mensaje;
	
	//return $resultado;
}


function eliminarDetallePresupuesto ($datosBBDD, $idDetalle)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle]	 
	where id = ".$idDetalle;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el detalle'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;
	
}

function cargarDetallesPresupuesto($datosBBDD,$numPresupuesto,$orden="ninguno")
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select t1.*, t3.proceso, t2.tipoProceso, t4.departamento, t5.precio as precioMaterialPapel, t6.tamano, t7.tipo, t8.acabado, t9.gramaje
, t10.tipoImpresora, t10.precioClick, t11.tamano as tamanoFinal, t12.id as fgIdConcepto, t12.[nombreSubconcepto2] as gfConcepto ,t12.[coste] as gfCoste
, t13.[nombreSubconcepto] as gfMaterial, t14.[nombreConcepto] as gfTipoProceso
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].procesosTipos as t2
	on t1.idTipo = t2.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].procesos as t3
	on t1.idConcepto = t3.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].procesosDepartamento as t4
	on t1.idDepartamento = t4.id
	left join [".$datosBBDD->bbddBBDD."].[dbo].tarifas_papel as t5
	on t1.idMaterialPapel = t5.id
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanio] as t6
	on t6.id = t5.idTamanio
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTipo] as t7
	on t7.id = t5.idTipo
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelAcabado] as t8
	on t8.id = t5.idAcabado
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelGramaje] as t9
	on t9.id = t5.idGramaje
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_impresorasTipo] as t10
	on t10.id = t1.idTipoImpresora
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanio] as t11
	on t11.id = t1.idPapelTamanioFinal
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_subconcepto2] as t12
	on t12.id = t1.idGFConcepto
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_subconcepto1] as t13
	on t12.idSubconcepto1 = t13.id
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_concepto] as t14
	on t13.idConcepto = t14.id
	
	where t1.presupuesto = '".$numPresupuesto."'";
	
	if ($orden=="ninguno")
	{
		$consulta = $consulta . " order by t2.orden, t1.idTipo, t1.orden, t1.id";
	}
	else
	{
		$consulta = $consulta . " order by t4.departamento, t2.orden, t1.idTipo, t1.orden, t1.id";
	}
	
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarUnDetallePresupuesto($datosBBDD,$idDetalle)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select t1.*, t3.proceso, t2.tipoProceso, t4.departamento
FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1	
	inner join [".$datosBBDD->bbddBBDD."].[dbo].procesosTipos as t2
	on t1.idTipo = t2.id	
	inner join [".$datosBBDD->bbddBBDD."].[dbo].procesos as t3
	on t1.idConcepto = t3.id	
	inner join [".$datosBBDD->bbddBBDD."].[dbo].procesosDepartamento as t4
	on t1.idDepartamento = t4.id	
	where t1.id = ".$idDetalle."
	order by t2.orden, t1.idTipo, t1.orden, t1.id";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function crearNuevoProcesoPresupuesto($datosBBDD,$idTipo,$proceso,$descripcion,$idDepartamento)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[procesos] (idTipoProceso, proceso, descripcion,idDepartamento) values (".$idTipo.",'".$proceso."','".$descripcion."',".$idDepartamento.")";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	
	
	sqlsrv_close($conn_sis);
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido crear el proceso.\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Proceso Creado");
	}
	
	return $mensaje;	
	//return $resultado;
}


function verPresupuesto($datosBBDD,$numPresupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	/*$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]
	
	where presupuesto = '".$numPresupuesto."'";*/
	
	
	$consulta = "SELECT t1.*, t2.nombre as nombreComercial, t2.telefono as telefonoComercial,t2.inicial as inicialComercial, t3.concepto as textoFormaPago, t4.tipoIva as ivaFranqueo, t5.numero as numFactura, t6.numero as numFactura2, t5.fecha as fechaFac, t6.fecha as fechaFacClay
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
  on t2.id = t1.idComercial

	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t1.idFormaPago
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[totalFranqueoTipos] as t4
  on t4.id = t1.idVisualizarTotalFranqueo
  
  left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t5
  on t5.presupuesto = t1.presupuesto
  
   left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t6
  on t6.presupuesto = t1.presupuesto

  where t1.presupuesto = '".$numPresupuesto."'";
	
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}



function verSiOtEsDeClayma($datosBBDD,$numPresupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT clayma FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]
  where presupuesto = '".$numPresupuesto."'";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verPresupuestoParaOt($datosBBDD,$numPresupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*, t2.nombre as nombreComercial, t2.telefono as telefonoComercial,t2.inicial as inicialComercial, t3.concepto as textoFormaPago, t4.tipoIva as ivaFranqueo, t5.nombre_empresa, t5.nombre_franqueo, t5.codigo, t5.codigoSidi
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
  on t2.id = t1.idComercial

	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t1.idFormaPago
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[totalFranqueoTipos] as t4
  on t4.id = t1.idVisualizarTotalFranqueo

  inner join [".$datosBBDD->bbddBBDD."].[dbo].clientes as t5
  on t5.subcliente = t1.cliente

  where t1.presupuesto = '".$numPresupuesto."'";
	
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verPresupuestoParaOtClayma($datosBBDD,$numPresupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*, t2.nombre as nombreComercial, t2.telefono as telefonoComercial,t2.inicial as inicialComercial, t3.concepto as textoFormaPago, t4.tipoIva as ivaFranqueo, t5.nombre_empresa, t5.nombre_franqueo, t5.codigo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
  on t2.id = t1.idComercial

	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t1.idFormaPago
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[totalFranqueoTipos] as t4
  on t4.id = t1.idVisualizarTotalFranqueo

  inner join [".$datosBBDD->bbddBBDD."].[dbo].clientesClayma as t5
  on t5.subcliente = t1.cliente

  where t1.presupuesto = '".$numPresupuesto."'";
	
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function copiarDetallePresupuesto($datosBBDD,$viejoPresupuesto,$nuevoPresupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] (presupuesto, unidades, precio, descripcion, notaCibeles, orden, idConcepto, idTipo,idDepartamento, notaAdmonProd,exentoIVA)
 (select '".$nuevoPresupuesto."', unidades, precio, descripcion, notaCibeles, orden, idConcepto, idTipo,idDepartamento, notaAdmonProd, exentoIVA
   FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] where presupuesto='".$viejoPresupuesto."')";
	
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}


function cargarLetraNumPresupuesto($datosBBDD,$numPresupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select letra from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] where presupuesto='".$numPresupuesto."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function modificarLetraPresupuesto($datosBBDD,$nuevaLetra,$numPresupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] 
	set letra = '".$nuevaLetra."' where presupuesto= '".$numPresupuesto."'";
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}


function crearNuevaFormaPago($datosBBDD,$formaPago)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] (concepto) values ('".$formaPago."')";
	
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}





//function mostrarPresupuestos($datosBBDD,$orden,$otBuscar,$empleadoAbuscar,$fechaAbuscar)	

function mostrarPresupuestos1($datosBBDD,$orden,$desc,$texto,$queBusca, $bajada, $abierta,$fecha,$fechaAceptacion)	//hay que adaptar el codigo para que utilice la funcion 'mostrarPresupuestos2'. La unica diferencia es el tratamiento de $condicion
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$condicion = " ";
	if ($texto!="")
	{
		//$condicion = " where ". $queBusca."='".$texto."' ";
		//$condicion = " where ". $queBusca." like '%".$texto."%' and tabla.codigo_saldo = tabla.codigo ";
		$condicion = " where ". $queBusca." like '%".$texto."%'";
	}	
	
	
	
	if ($bajada=="false" && $abierta=="false")
	{
		
	}
	else
	{
		$valor = 0;
		
		if ($bajada=="true")
		{
			$valor = 1;
		}
		
		if ($condicion == " ")
		{
			$condicion = " where ";
		}
		else 
		{
			$condicion = $condicion . " and ";
		}
		
		$condicion =  $condicion . "otBajada = ".$valor;
		
		
		$valor = 0;
		
		if ($abierta=="true")
		{
			$valor = 1;
		}
		
		if ($condicion == " ")
		{
			$condicion = " where ";
		}
		else 
		{
			$condicion = $condicion . " and ";
		}
		
		$condicion =  $condicion . "otAbierta = ".$valor;
		
		
		
		
	}
	
	if ($fecha!="")
		{
			if ($condicion == " ")
			{
				$condicion = " where ";
			}
			else 
			{
				$condicion = $condicion . " and ";
			}
			$condicion =  $condicion . $fecha;
			//$condicion =  $condicion . "fechaAceptacion='".$fecha."'";
		}

		if ($fechaAceptacion!="")
		{
			if ($condicion == " ")
			{
				$condicion = " where ";
			}
			else 
			{
				$condicion = $condicion . " and ";
			}
			$fechaSinHora = date("d-m-Y", strtotime($fechaAceptacion));
			$condicion =  $condicion . "fechaAceptacionRegistro>='".$fechaSinHora."' and otAbierta!=1";
			//$condicion =  $condicion . "fechaAceptacion='13-03-2024'";
		}
	
	//echo $condicion;
	//top(5171)
	$consulta = "select * from (
	

SELECT t1.*, t2.nombre as nombreComercial, t2.telefono as telefonoComercial,t2.inicial as inicialComercial, t3.concepto as textoFormaPago
, t4.tipoIva as ivaFranqueo, ISNULL(t6.numero,t8.factura) as  'numFactura', t6.anioFactura as 'anioFactura'
, t5.nuestraCuenta, t5.codigo_saldo, 'Cibeles' as origen, t5.activo, t5.codigo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
  on t2.id = t1.idComercial

	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t1.idFormaPago
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[totalFranqueoTipos] as t4
  on t4.id = t1.idVisualizarTotalFranqueo 
    left join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t5
  on t5.subcliente = t1.cliente
     left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t6 
  on t6.presupuesto = t1.presupuesto
	left join (select distinct(t7.presupuesto), t7.factura, t7.anioFactura from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTodosLosAnios] as t7 where t7.presupuesto != 'null') as t8
	on t8.presupuesto = t1.presupuesto 

  where t1.clayma=0

   union

  SELECT t1.*, t2.nombre as nombreComercial, t2.telefono as telefonoComercial,t2.inicial as inicialComercial, t3.concepto as textoFormaPago
, t4.tipoIva as ivaFranqueo, ISNULL(t6.numero,t8.factura) as  'numFactura', t6.anioFactura
, t5.nuestraCuenta, t5.codigo_saldo, 'Clayma' as origen, t5.activo, t5.codigo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
  on t2.id = t1.idComercial

	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t1.idFormaPago
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[totalFranqueoTipos] as t4
  on t4.id = t1.idVisualizarTotalFranqueo 
    left join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t5
  on t5.subcliente = t1.cliente
     left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t6
  on t6.presupuesto = t1.presupuesto
  left join (select distinct(t7.presupuesto), t7.factura, t7.anioFactura from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClaymaTodosLosAnios] as t7 where t7.presupuesto != 'null') as t8
	on t8.presupuesto = t1.presupuesto 


  where t1.clayma=1) as tabla 
  
  ".$condicion. " order by ".$orden . " " . $desc;
	
	 	
	//echo $consulta;
 	//$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array());	

	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}


function mostrarPresupuestos2($datosBBDD,$condicion,$modo=0) //modo: se hace por rendimiento: 0: todos los campos; 1:presupuesto,codigo_saldo,clayma,campana2,idFormaPago,cantidad2 	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	

	if ($modo==0)
	{
$consulta = "select * from (
	

SELECT t1.*, t2.nombre as nombreComercial, t2.telefono as telefonoComercial,t2.inicial as inicialComercial, t3.concepto as textoFormaPago
, t4.tipoIva as ivaFranqueo, ISNULL(t6.numero,t8.factura) as  'numFactura', ISNULL(t6.anioFactura,t8.anioFactura) as  'anioFactura' 
, t5.nuestraCuenta, t5.codigo_saldo, 'Cibeles' as origen, t5.activo, ISNULL(t9.importePresupuesto,0) as importePresupuesto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
  on t2.id = t1.idComercial

	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t1.idFormaPago
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[totalFranqueoTipos] as t4
  on t4.id = t1.idVisualizarTotalFranqueo 
    left join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t5
  on t5.subcliente = t1.cliente
     left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t6
  on t6.presupuesto = t1.presupuesto
	left join (select distinct(t7.presupuesto), t7.factura, t7.anioFactura from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTodosLosAnios] as t7 where t7.presupuesto != 'null') as t8
	on t8.presupuesto = t1.presupuesto
	left join (SELECT ISNULL(sum(ROUND(precio*unidades,2)),0) as importePresupuesto, presupuesto FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] group by presupuesto ) as t9
	on t9.presupuesto=t1.presupuesto

  where t1.clayma=0

   union

  SELECT t1.*, t2.nombre as nombreComercial, t2.telefono as telefonoComercial,t2.inicial as inicialComercial, t3.concepto as textoFormaPago
, t4.tipoIva as ivaFranqueo, ISNULL(t6.numero,t8.factura) as  'numFactura', ISNULL(t6.anioFactura,t8.anioFactura) as  'anioFactura' 
, t5.nuestraCuenta, t5.codigo_saldo, 'Clayma' as origen, t5.activo, ISNULL(t9.importePresupuesto,0) as importePresupuesto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
  on t2.id = t1.idComercial

	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t1.idFormaPago
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[totalFranqueoTipos] as t4
  on t4.id = t1.idVisualizarTotalFranqueo 
    left join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t5
  on t5.subcliente = t1.cliente
     left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t6
  on t6.presupuesto = t1.presupuesto
  left join (select distinct(t7.presupuesto), t7.factura, t7.anioFactura from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClaymaTodosLosAnios] as t7 where t7.presupuesto != 'null') as t8
	on t8.presupuesto = t1.presupuesto 
	left join (SELECT ISNULL(sum(ROUND(precio*unidades,2)),0) as importePresupuesto, presupuesto FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] group by presupuesto ) as t9
	on t9.presupuesto=t1.presupuesto

  where t1.clayma=1) as tabla ".$condicion;
	}
	else if ($modo==1)
	{
		$consulta="select * from (
	
		select t1.presupuesto, t5.codigo_saldo, t1.clayma, t1.campana2, t1.idFormaPago, t1.cantidad2


		FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1

		inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
		on t2.id = t1.idComercial

			inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
		on t3.id = t1.idFormaPago
		
		inner join [".$datosBBDD->bbddBBDD."].[dbo].[totalFranqueoTipos] as t4
		on t4.id = t1.idVisualizarTotalFranqueo 
			left join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t5
		on t5.subcliente = t1.cliente
			left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t6
		on t6.presupuesto = t1.presupuesto
			left join (select distinct(t7.presupuesto), t7.factura, t7.anioFactura from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTodosLosAnios] as t7 where t7.presupuesto != 'null') as t8
			on t8.presupuesto = t1.presupuesto
			left join (SELECT ISNULL(sum(ROUND(precio*unidades,2)),0) as importePresupuesto, presupuesto FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] group by presupuesto ) as t9
			on t9.presupuesto=t1.presupuesto

		where t1.clayma=0

		union
		select t1.presupuesto, t5.codigo_saldo, t1.clayma, t1.campana2, t1.idFormaPago, t1.cantidad2		
		FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1

		inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
		on t2.id = t1.idComercial

			inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
		on t3.id = t1.idFormaPago
		
		inner join [".$datosBBDD->bbddBBDD."].[dbo].[totalFranqueoTipos] as t4
		on t4.id = t1.idVisualizarTotalFranqueo 
			left join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t5
		on t5.subcliente = t1.cliente
			left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t6
		on t6.presupuesto = t1.presupuesto
		left join (select distinct(t7.presupuesto), t7.factura, t7.anioFactura from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClaymaTodosLosAnios] as t7 where t7.presupuesto != 'null') as t8
			on t8.presupuesto = t1.presupuesto 
			left join (SELECT ISNULL(sum(ROUND(precio*unidades,2)),0) as importePresupuesto, presupuesto FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] group by presupuesto ) as t9
			on t9.presupuesto=t1.presupuesto

		where t1.clayma=1) as tabla

		".$condicion;
	}
	
	
	
 	
	//echo $consulta;
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}



function mostrarPresupuestos($datosBBDD,$orden,$desc,$texto,$queBusca)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$condicion = " ";
	if ($texto!="")
	{
		$condicion = " where ". $queBusca."='".$texto."' ";
	}	
	
	
	$consulta = "SELECT t1.*, t2.nombre as nombreComercial, t2.telefono as telefonoComercial,t2.inicial as inicialComercial, t3.concepto as textoFormaPago, t4.tipoIva as ivaFranqueo, t5.nuestraCuenta, t5.codigo_saldo, t5.idFormaPago as idFormaPagoCliente, t5.prefactura
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
  on t2.id = t1.idComercial

	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t1.idFormaPago
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[totalFranqueoTipos] as t4
  on t4.id = t1.idVisualizarTotalFranqueo 
    inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t5
  on t5.subcliente = t1.cliente
  
  ".$condicion. " order by ".$orden . " " . $desc;
 	
	//echo $consulta;
 	//$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	 $resultado = sqlsrv_query($conn_sis, $consulta,array(),array());	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))	
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}


function mostrarPresupuestos3($datosBBDD,$condicion)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	
	
	
	$consulta = "SELECT *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]
  
  ".$condicion;
 	
	//echo $consulta;
 	//$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	 $resultado = sqlsrv_query($conn_sis, $consulta,array(),array());	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))	
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}


function mostrarPresupuestosClayma($datosBBDD,$orden,$desc,$texto,$queBusca)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$condicion = " ";
	if ($texto!="")
	{
		$condicion = " where ". $queBusca."='".$texto."' ";
	}	
	
	
	$consulta = "SELECT t1.*, t2.nombre as nombreComercial, t2.telefono as telefonoComercial,t2.inicial as inicialComercial, t3.concepto as textoFormaPago, t4.tipoIva as ivaFranqueo, t5.nuestraCuenta, t5.codigo_saldo, t5.idFormaPago as idFormaPagoCliente, t5.prefactura
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
  on t2.id = t1.idComercial

	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t1.idFormaPago
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[totalFranqueoTipos] as t4
  on t4.id = t1.idVisualizarTotalFranqueo 
    inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t5
  on t5.subcliente = t1.cliente
  
  ".$condicion. " order by ".$orden . " " . $desc;
 	
	//echo $consulta;
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}



function modificarPresupuesto2($datosBBDD,$presupuesto,$otBajada,$otAbierta,$fechaTerminado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [presupuestos] set 
	[otBajada] = ".$otBajada.", 
	[otAbierta] = ".$otAbierta.", ";
	
	if ($fechaTerminado == null)
	{
		$consulta = $consulta."[fechaTerminado] = null";
	}
	else
	{
		$consulta = $consulta."[fechaTerminado] = '".$fechaTerminado."' ";
	}
	
	
	$consulta = $consulta."
	
	where presupuesto = '".$presupuesto."'";	
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;	
}


function modificarPresupuesto3($datosBBDD,$presupuesto,$fechaAceptacion,$fechaCompromiso)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]
	set ";
	
		
	if ($fechaAceptacion == null)
	{
		$consulta = $consulta."[fechaAceptacion] = null";
	}
	else
	{
		$consulta = $consulta."[fechaAceptacion] = '".$fechaAceptacion."' ";
	}
	$consulta = $consulta.",";
	if ($fechaCompromiso == null)
	{
		$consulta = $consulta."[fechaCompromiso] = null";
	}
	else
	{
		$consulta = $consulta."[fechaCompromiso] = '".$fechaCompromiso."' ";
	}
	

	if ($fechaAceptacion!=null && $fechaCompromiso!=null)
	{
		$consulta = $consulta.",";		
		$consulta = $consulta."[fechaAceptacionRegistro] = GETDATE() ";
	}
	
	



	$consulta = $consulta." 

 
 	where presupuesto = '".$presupuesto."'";	
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;	
}

/*function mostrarListadoPresupuestosActivos($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT presupuesto, letra, cliente
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]
  where presupuesto not in (
SELECT presupuesto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas])

  and fechaAceptacion != '' and fechaAceptacion is not null";	
	
 	echo $consulta;
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}*/

function mostrarDatosPresupuesto($datosBBDD,$numPresu)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t2.codigo_saldo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].clientes as t2
  on t1.cliente = t2.nombre_empresa
  where t1.presupuesto = '".$numPresu."'";	
	
 	//echo $consulta;
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}



function modificarPresupuestoValorPdfGenerado($datosBBDD,$presupuesto,$pdfGenerado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [presupuestos] set 
	pdfGenerado = ".$pdfGenerado. " where presupuesto = '".$presupuesto."'";	
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el valor del campo 'pdf generado'.\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;	
}

function mostrarOt($datosBBDD,$condicion)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	/*$condicion = " ";
	if ($texto!="")
	{
		$condicion = " where ". $queBusca."='".$texto."' ";
	}*/
	
		
	
	$consulta = "select t1.*, CONCAT(t2.numero,'/',t2.anioFactura) as facturaCibeles, CONCAT(t3.numero,'/',t3.anioFactura) as facturaClayma from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] t1
left join  [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t2
on t1.presupuesto = t2.presupuesto
left join  [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t3
on t1.presupuesto = t3.presupuesto

  where t1.otBajada = 1 and t1.otAbierta = 1 and" .$condicion;	
 	
	//echo $consulta;
 	//$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	 $resultado = sqlsrv_query($conn_sis, $consulta,array(),array());
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}


function modificarOtProduccion($datosBBDD,$presupuesto,$campana,$cantidad,$fechaInicio,$fechaTerminado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [presupuestos] set 
	campana2 = '".$campana. "',  
	cantidad2 = ".$cantidad.", 
	fechaInicioReal = '".$fechaInicio."', 
	fechaTerminado = '".$fechaTerminado."'
	
	where presupuesto = '".$presupuesto."'";	
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";

	if( $resultado === false ) 
	{
	   	$mensaje = "Error: no se ha podido modificar la Ot'.\n".$resultado."\n";
		$mensaje = $mensaje .("\n".$consulta."\n");
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Ot Modificado");
	}
	
	return $mensaje;	
}

function verDepartamentosProcesos($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	/*$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]
	
	where presupuesto = '".$numPresupuesto."'";*/
	
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[procesosDepartamento]";
	
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	//while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarDetallesPresupuestoDepartamento($datosBBDD,$numPresupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select distinct(t4.departamento) FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].procesosTipos as t2
	on t1.idTipo = t2.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].procesos as t3
	on t1.idConcepto = t3.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].procesosDepartamento as t4
	on t1.idDepartamento = t4.id
	
	where t1.presupuesto = '".$numPresupuesto."'";
	
	$consulta = $consulta . " order by t4.departamento";
	
		
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado,SQLSRV_FETCH_NUMERIC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}



function cargarTiposProvisionesFondos($datosBBDD,$condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] ".$condicion." order by id";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	
}


/*function insertarProvisionFondo($datosBBDD,$presupuesto,$importe,$tipo)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] (presupuesto, importe, tipo) values ('".$presupuesto."',".$importe.",".$tipo.")";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);
	
	
	$mensaje="";
	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar la provision de fondo.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Provision de Fondo Insertado");
	}
	
	return $mensaje;	
	
	
	//return $resultado;
}*/

function insertarProvisionFondo($datosBBDD,$presupuesto,$idCliente,$importe,$tipo,$contador,$clayma,$concepto)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] (presupuesto, idCliente,importe, tipo,contador,clayma,concepto) values ('".$presupuesto."','".$idCliente."',".$importe.",".$tipo.",".$contador.",".$clayma.",'".$concepto."')";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar la provision de fondo.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Provision de Fondo Insertado");
	}
	
	return $mensaje;

	//return $resultado;
}



function mostrarProvisionDeFondos($datosBBDD,$numPresupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select t1.*, t2.tipo as tipoTexto FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t2
on t2.id=t1.tipo

 where t1.presupuesto='".$numPresupuesto."'";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;		
}


function mostrarProvisionDeFondosPorId($datosBBDD,$id)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] where id='".$id."'";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;		
}


function mostrarProvisionDeFondosPendientes($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	$consulta = "select tablaTodo.* from (
		select tabla1.* from (
		
		SELECT t1.*, t3.nombre_franqueo, t2.campana, t3.codigo, t4.tipo as tipoTexto
		  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
		
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
		  on t1.presupuesto=t2.presupuesto
		
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
		  on t2.cliente=t3.subcliente
		  
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t4
		  on t4.id = t1.tipo
		
		  where cobrada = 1  and formaPago = '' and t1.clayma = 0
		
		  union
		
		
		  SELECT t1.*, t2.nombre_franqueo, '', t2.codigo, t3.tipo 
		  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
		  on t1.idCliente = t2.codigo
		   inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t3
		  on t3.id = t1.tipo
		
		  where cobrada = 1  and formaPago = ''  and t1.clayma = 0 and t1.id not in(SELECT t1.id
		  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
		
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
		  on t1.presupuesto=t2.presupuesto
		
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
		  on t2.cliente=t3.subcliente
		  
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t4
		  on t4.id = t1.tipo
		
		  where cobrada = 1  and formaPago = '' and t1.clayma = 0)
		  ) as tabla1
		
		  union 
		
		
		
		  select tabla2.* from (
		
		SELECT t1.*, t3.nombre_franqueo, t2.campana, t3.codigo, t4.tipo as tipoTexto
		  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
		
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
		  on t1.presupuesto=t2.presupuesto
		
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t3
		  on t2.cliente=t3.subcliente
		  
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t4
		  on t4.id = t1.tipo
		
		  where cobrada = 1  and formaPago = '' and t1.clayma = 1
		  union
		  SELECT t1.*, t2.nombre_franqueo, '', t2.codigo, t3.tipo 
		  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
		  on t1.idCliente = t2.codigo
		   inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t3
		  on t3.id = t1.tipo
		
		  where cobrada = 1  and formaPago = ''  and t1.clayma = 1 and t1.id not in(SELECT t1.id
		  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
		
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
		  on t1.presupuesto=t2.presupuesto
		
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t3
		  on t2.cliente=t3.subcliente
		  
		  inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t4
		  on t4.id = t1.tipo
		
		  where cobrada = 1 and formaPago = '' and t1.clayma = 1)
		  ) as tabla2
		
		  ) as tablaTodo ".$condicion;
	
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;		
}

function mostrarProvisionDeFondos2($datosBBDD,$numPresupuesto,$numPresupuestoContador=1)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//echo "<br>".substr($numPresupuesto, 0,1)."<br>";
	if (substr($numPresupuesto, 0,1)=="9")
	{//echo "entra";
		$consulta="select t3.nombre_empresa as 'subcliente', t3.direccion,t3.codigo_postal, t3.localidad, t3.provincia, t3.nif as nif_subcliente,t1.importe, t1.fechaCreacion, t1.concepto as campana 
FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1 

inner join [".$datosBBDD->bbddBBDD."].[dbo].clientes as t3 
on t1.idCliente = t3.codigo_saldo 
where t1.presupuesto='".$numPresupuesto."'  and t3.codigo_saldo = t3.codigo";
	
	}
	else
	{//echo "entra2";
		/*$consulta = "select t3.subcliente, t3.direccion,t3.codigo_postal, t3.localidad, t3.provincia, t3.nif_subcliente, t2.campana,t1.importe, t1.fechaCreacion
FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
on t1.presupuesto=t2.presupuesto
inner join [".$datosBBDD->bbddBBDD."].[dbo].clientes as t3
on t2.cliente = t3.subcliente
where t1.presupuesto='".$numPresupuesto."' and t1.contador = ".$numPresupuestoContador;*/
		$consulta = "select t3.nombre_empresa as 'subcliente', t3.direccion,t3.codigo_postal, t3.localidad, t3.provincia, t3.nif as 'nif_subcliente', t2.campana,t1.importe, t1.fechaCreacion
FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
on t1.presupuesto=t2.presupuesto
inner join [".$datosBBDD->bbddBBDD."].[dbo].clientes as t3
on t2.cliente = t3.nombre_empresa
where t1.presupuesto='".$numPresupuesto."' and t1.contador = ".$numPresupuestoContador;
	
	}
	
	//echo "numPresupuesto: ".$numPresupuesto;
	//echo "\n<br>contador: ". $numPresupuestoContador;
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	
}

function mostrarProvisionDeFondos2Clayma($datosBBDD,$numPresupuesto,$numPresupuestoContador)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select t3.subcliente, t3.direccion,t3.codigo_postal, t3.localidad, t3.provincia, t3.nif_subcliente, t2.campana,t1.importe, t1.fechaCreacion
FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
on t1.presupuesto=t2.presupuesto
inner join [".$datosBBDD->bbddBBDD."].[dbo].clientesClayma as t3
on t2.cliente = t3.subcliente
where t1.presupuesto='".$numPresupuesto."' and t1.contador = ".$numPresupuestoContador;




if (substr($numPresupuesto, 0,1)=="9")
	{//echo "entra";
		$consulta = "select t3.subcliente, t3.direccion,t3.codigo_postal, t3.localidad, t3.provincia, t3.nif_subcliente, '' as campana, t1.importe, t1.fechaCreacion
FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1

inner join [".$datosBBDD->bbddBBDD."].[dbo].clientesClayma as t3
on t1.idCliente = t3.codigo_saldo
where t1.presupuesto='".$numPresupuesto."'  and t3.codigo_saldo = t3.codigo";
	
	}
	else
	{//echo "entra2";
		/*$consulta = "select t3.subcliente, t3.direccion,t3.codigo_postal, t3.localidad, t3.provincia, t3.nif_subcliente, t2.campana,t1.importe, t1.fechaCreacion
FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
on t1.presupuesto=t2.presupuesto
inner join [".$datosBBDD->bbddBBDD."].[dbo].clientes as t3
on t2.cliente = t3.subcliente
where t1.presupuesto='".$numPresupuesto."' and t1.contador = ".$numPresupuestoContador;*/
		$consulta = "select t3.subcliente, t3.direccion,t3.codigo_postal, t3.localidad, t3.provincia, t3.nif_subcliente, t2.campana,t1.importe, t1.fechaCreacion
FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
on t1.presupuesto=t2.presupuesto
inner join [".$datosBBDD->bbddBBDD."].[dbo].clientesClayma as t3
on t2.cliente = t3.subcliente
where t1.presupuesto='".$numPresupuesto."' and t1.contador = ".$numPresupuestoContador;
	
	}




	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
		
	return $result;	
}

function verUltimoNumeroPresupuesto($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT max(presupuesto) as ultimoNumero FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	
}


function cargarTiposCobrada($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipoCobrada]";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}



function modificarPFpendientes($datosBBDD,$idPF,$cobrada,$fecha,$formaPago,$importe)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	
	if ($fecha==""||$fecha==null)
	{
		$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] set cobrada=".$cobrada.", fechaCobro=null, formaPago='".$formaPago."', importe=".$importe." where id=".$idPF;
	}
	else
	{
		$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] set cobrada=".$cobrada.", fechaCobro='".$fecha."', formaPago='".$formaPago."', importe=".$importe." where id=".$idPF;
	}	
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la provision de fondo'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}



function insertarMovimientoPF ($datosBBDD,$codigoCliente,$fecha,$formaPago,$importe,$presupuesto,$fechaCuadre,$informacionCuadre,$saldoPostPF=0,$clayma=0, $anio=0)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	if ($fechaCuadre=="")
	{
		$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[provisionDeFondo_movimientos] (codigoCliente, fecha, formaPago, importe,presupuesto, fechaCuadre, informacionCuadre, saldoPostPF, clayma, anioFactura) values (".$codigoCliente.",'".$fecha."','".$formaPago."',".$importe.",'".$presupuesto."',null,'".$informacionCuadre."',".$saldoPostPF.",".$clayma.", ".$anio.")";
	}
	else//MIRAR ESTO ¿cuando se mete la fecha de factura?
	{
		//$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[provisionDeFondo_movimientos] (codigoCliente, fecha, formaPago, importe,presupuesto, fechaCuadre, informacionCuadre, aPagarFacturaCibeles, numFactura, clayma) values (".$codigoCliente.",'".$fecha."','".$formaPago."',".$importe.",'".$presupuesto."','".$fechaCuadre."','".$informacionCuadre."')";
		$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[provisionDeFondo_movimientos] (codigoCliente, fecha, formaPago, importe,presupuesto, fechaCuadre, informacionCuadre, saldoPostPF, clayma) values (".$codigoCliente.",'".$fecha."','".$formaPago."',".$importe.",'".$presupuesto."','".$fechaCuadre."','".$informacionCuadre."',".$saldoPostPF.",".$clayma.")";
	}
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	/*if( $resultado === false) 
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}*/
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar el movimiento de la provision de fondos'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Error: ".$consulta."Presupuesto Modificado";
	}
	
	return $mensaje;		
	
	//return $resultado;
}

function provisionDeFondo_verSumatorioPorPresupuesto($datosBBDD,$clayma,$numeroFacOriginal,$anioFacOriginal,$datosOrigenFactura)
{

	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	/*
	$consulta = "SELECT isnull(sum(importe)*-1,0) as importeTotal  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] 
	where  tipo = 3 and cobrada = 2 and clayma=".$clayma."
 and numFacturaAplicada = '".$numeroFacOriginal."' and numFacturaAplicadaAnio = '".$anioFacOriginal."'";
*/

$consulta = "SELECT isnull(sum(importe)*-1,0) as importeTotal  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] 
	where  tipo = 3 and cobrada = 2 and clayma=".$clayma." and facCompletaAplicada = '".$datosOrigenFactura."'";




	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;

}


function verProvisionDeFondo($datosBBDD,$condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] " . $condicion;

	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;

}

function anularProvisionFondoAplicadaAFactura ($datosBBDD,$facCompleto) 
{	
	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo]  
	set  numFacturaAplicada = NULL, numFacturaAplicadaAnio=NULL , facCompletaAplicada = NULL
	where facCompletaAplicada = '".$facCompleto."'";
	//echo "Error: ".$consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	

	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el saldo del cliente'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;
	

}
 	

function modificarDatosPFenCliente($datosBBDD,$codigoCliente,$fecha,$importe)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[clientes] set importePF=isnull(importePF,0) + (".$importe."), fechaCobroPF='".$fecha."' where codigo=".$codigoCliente;
	//echo "Error: ".$consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	

	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el saldo del cliente'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;
	
}

function modificarDatosPFenClienteClayma($datosBBDD,$codigoCliente,$fecha,$importe)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] set importePF=isnull(importePF,0) + (".$importe."), fechaCobroPF='".$fecha."' where codigo=".$codigoCliente;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el saldo del cliente'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}


function mostrarProvisionDeFondosTodos($datosBBDD, $condicion="")
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from (
SELECT t1.*, t3.nombre_franqueo, t2.campana, t3.codigo, t4.cobrada as cobradaNombre, t5.tipo as tipoNombre, t3.subcliente, t3.nombre_empresa, t3.codigo_saldo, t3.noAplicarPF
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
  on t1.presupuesto=t2.presupuesto

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t2.cliente=t3.subcliente

   inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipoCobrada] as t4
  on t1.cobrada=t4.id
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t5
  on t5.id = t1.tipo 
  where t2.clayma=0


  union
  
SELECT t1.*, t3.nombre_franqueo, t2.campana, t3.codigo, t4.cobrada as cobradaNombre, t5.tipo as tipoNombre, t3.subcliente, t3.nombre_empresa, t3.codigo_saldo, t3.noAplicarPF
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
  on t1.presupuesto=t2.presupuesto

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t3
  on t2.cliente=t3.subcliente

   inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipoCobrada] as t4
  on t1.cobrada=t4.id
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t5
  on t5.id = t1.tipo 
  where t2.clayma=1
  
  
   union 

  SELECT t1.*, t3.nombre_franqueo, t1.concepto, t3.codigo, t4.cobrada as cobradaNombre, t5.tipo as tipoNombre, t3.subcliente, t3.nombre_empresa, t3.codigo_saldo, t3.noAplicarPF
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
   
   
    inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente=t3.codigo
   
 
    inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipoCobrada] as t4
  on t1.cobrada=t4.id
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t5
  on t5.id = t1.tipo 

  where t1.presupuesto like '9%'
  
  ) as tabla
  
 

  ".$condicion;
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
		
	return $result;	
	
}

function mostrarProvisionDeFondosTodos_Sumatorio($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select sum(importe) as importe from (
SELECT t1.*, t3.nombre_franqueo, t2.campana, t3.codigo, t4.cobrada as cobradaNombre, t5.tipo as tipoNombre, t3.subcliente, t3.nombre_empresa, t3.codigo_saldo, t3.noAplicarPF
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
  on t1.presupuesto=t2.presupuesto

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t2.cliente=t3.subcliente

   inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipoCobrada] as t4
  on t1.cobrada=t4.id
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t5
  on t5.id = t1.tipo 
  where t2.clayma=0


  union
  
SELECT t1.*, t3.nombre_franqueo, t2.campana, t3.codigo, t4.cobrada as cobradaNombre, t5.tipo as tipoNombre, t3.subcliente, t3.nombre_empresa, t3.codigo_saldo, t3.noAplicarPF
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
  on t1.presupuesto=t2.presupuesto

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t3
  on t2.cliente=t3.subcliente

   inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipoCobrada] as t4
  on t1.cobrada=t4.id
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t5
  on t5.id = t1.tipo 
  where t2.clayma=1
  
  
   union 

  SELECT t1.*, t3.nombre_franqueo, t1.concepto, t3.codigo, t4.cobrada as cobradaNombre, t5.tipo as tipoNombre, t3.subcliente, t3.nombre_empresa, t3.codigo_saldo, t3.noAplicarPF
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
   
   
    inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente=t3.codigo
   
 
    inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipoCobrada] as t4
  on t1.cobrada=t4.id
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t5
  on t5.id = t1.tipo 

  where t1.presupuesto like '9%'
  
  ) as tabla
  
 

  ".$condicion;
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
		
	return $result;	
}


function eliminarProvisionFondos($datosBBDD,$idRegistro)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] where id=".$idRegistro;	
 	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
		
	sqlsrv_close($conn_sis);
	
	//return $resultado;
}

	
function mostrarFacturasSinEmitir($datosBBDD, $clayma,$condicion)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select tabla.* from (SELECT t1.*, t2.inicial
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t2
  on t1.idComercial = t2.id
  where t1.fechaTerminado  is not null
   and clayma = ".$clayma." and t1.numNoFactura is null
  and t1.presupuesto not in  (select presupuesto from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestosFacturadosTodosLosAnios]
 ) ) as tabla ".$condicion;
	
 	
	//echo $consulta;
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
	
}

function mostrarFacturasSinEmitirMensuales($datosBBDD,$condicion)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	/*$consulta = "SELECT t1.*
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[facturasMensualesPendientes] as t2
  on t1.numero = t2.numeroFacturaOriginal ".$condicion;*/
	
	$consulta = "   select t1.* FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t1
   inner join [".$datosBBDD->bbddBBDD."].[dbo].[facturasMensualesPendientes] as t2
   on t1.numero = t2.numeroFacturaOriginal and t1.anioFactura = t2.anio ".$condicion;;
	
 	
	//echo $consulta;
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}


function mostrarFacturasTodosLosAnios($datosBBDD,$condicion)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta = "SELECT t1.*, t2.codigo_saldo, 0 as origen, t3.numero as numAbono, t3.anioAbono
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t1
inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.cliente = t2.nombre_empresa 
left join [".$datosBBDD->bbddBBDD."].[dbo].[abonosTodosLosAnios] as t3
on t3.factura = t1.numero and t3.anioFactura = t1.anioFactura ".$condicion;
	
	//echo ($consulta);
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	return $result;	
}

function mostrarFacturasClaymaTodosLosAnios($datosBBDD,$condicion)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta = "SELECT t1.*, t2.codigo_saldo, 0 as origen, t3.numero as numAbono, t3.anioAbono
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t1
inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
on t1.cliente = t2.nombre_empresa 
left join [".$datosBBDD->bbddBBDD."].[dbo].[abonosClaymaTodosLosAnios] as t3
on t3.factura = t1.numero and t3.anioFactura = t1.anioFactura ".$condicion;
	
	//echo ($consulta);
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function mostrarFacturas($datosBBDD,$condicion,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta = "SELECT t1.*, t2.codigo_saldo, 0 as origen, t3.numero as numAbono, t3.anioAbono, t4.numeroFacturaCompleto as facRec, t4.anioFacRec
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] as t1
inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[abonosTodosLosAnios] as t3
on t3.factura = t1.numero and t3.anioFactura = ".$anioSeleccionado."
left join [".$datosBBDD->bbddBBDD."].[dbo].[facRecTodosLosAnios] as t4
on t4.origenFactura = t1.numeroFacturaCompleto

".$condicion;


	//echo ($consulta);
	
	//$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	$resultado = sqlsrv_query($conn_sis, $consulta);
	
	if ($resultado === false) {
		echo "<pre>Error SQLSRV:\n".print_r(sqlsrv_errors(), true)."</pre>";
		echo "<pre>Consulta:\n$consulta</pre>";
		sqlsrv_close($conn_sis);
		return array(); // o false
	}


	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
	
}

function mostrarFacturasClayma($datosBBDD,$condicion,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	/*$consulta = "
SELECT t1.*, t2.codigo_saldo, 1 as origen
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma] as t1
inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
on t1.cliente = t2.nombre_empresa " .$condicion;*/
	
	$consulta = "SELECT t1.*, t2.codigo_saldo, 1 as origen, t3.numero as numAbono, t3.anioAbono, t4.numeroFacturaCompleto as facRec, t4.anioFacRec
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] as t1
inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[abonosClaymaTodosLosAnios] as t3
on t3.factura = t1.numero and t3.anioFactura = ".$anioSeleccionado."
left join [".$datosBBDD->bbddBBDD."].[dbo].[facRecClaymaTodosLosAnios] as t4
on t4.origenFactura = t1.numeroFacturaCompleto
 ".$condicion;
	
	
	//echo ($consulta);
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function mostrarAbonosClayma($datosBBDD,$condicion,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM  [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anioSeleccionado."] " .$condicion;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	//echo ($consulta);
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function mostrarAbonos($datosBBDD,$condicion,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM  [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anioSeleccionado."] " .$condicion;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	//echo ($consulta);
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	
	return $result;	
}

function mostrarFactRectificativas($datosBBDD,$condicion,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta = "SELECT t1.*, t2.codigo_saldo, 0 as origen, '' as numAbono, ''as anioAbono, t4.numeroFacturaCompleto as facRec, t4.anioFacRec
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferencias".$anioSeleccionado."] as t1
inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[facRecTodosLosAnios] as t4
on t4.origenFactura = t1.numeroFacturaCompleto

".$condicion;


	//echo ($consulta);
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta);
	
	if ($resultado === false) {
		echo "<pre>Error SQLSRV:\n".print_r(sqlsrv_errors(), true)."</pre>";
		echo "<pre>Consulta:\n$consulta</pre>";
		sqlsrv_close($conn_sis);
		return array(); // o false
	}


	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
	
}

function mostrarFactRectificativasClayma($datosBBDD,$condicion,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta = "SELECT t1.*, t2.codigo_saldo, 1 as origen, '' as numAbono, ''as anioAbono, t4.numeroFacturaCompleto as facRec, t4.anioFacRec
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferenciasClayma".$anioSeleccionado."] as t1
inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[facRecClaymaTodosLosAnios] as t4
on t4.origenFactura = t1.numeroFacturaCompleto

".$condicion;


	//echo ($consulta);
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta);
	
	if ($resultado === false) {
		echo "<pre>Error SQLSRV:\n".print_r(sqlsrv_errors(), true)."</pre>";
		echo "<pre>Consulta:\n$consulta</pre>";
		sqlsrv_close($conn_sis);
		return array(); // o false
	}


	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
	
}



function mostrarFactRectificativasSustitutiva($datosBBDD,$condicion,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta = "SELECT t1.*, t2.codigo_saldo, 0 as origen, '' as numAbono, ''as anioAbono, t4.numeroFacturaCompleto as facRec, t4.anioFacRec
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucion".$anioSeleccionado."] as t1
inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[facRecTodosLosAnios] as t4
on t4.origenFactura = t1.numeroFacturaCompleto

".$condicion;


	//echo ($consulta);
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta);
	
	if ($resultado === false) {
		echo "<pre>Error SQLSRV:\n".print_r(sqlsrv_errors(), true)."</pre>";
		echo "<pre>Consulta:\n$consulta</pre>";
		sqlsrv_close($conn_sis);
		return array(); // o false
	}


	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
	
}

function mostrarFactRectificativasSustitutivaClayma($datosBBDD,$condicion,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta = "SELECT t1.*, t2.codigo_saldo, 1 as origen, '' as numAbono, ''as anioAbono, t4.numeroFacturaCompleto as facRec, t4.anioFacRec
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucionClayma".$anioSeleccionado."] as t1
inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[facRecClaymaTodosLosAnios] as t4
on t4.origenFactura = t1.numeroFacturaCompleto

".$condicion;


	//echo ($consulta);
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta);
	
	if ($resultado === false) {
		echo "<pre>Error SQLSRV:\n".print_r(sqlsrv_errors(), true)."</pre>";
		echo "<pre>Consulta:\n$consulta</pre>";
		sqlsrv_close($conn_sis);
		return array(); // o false
	}


	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
	
}

/*function borrarFacturaTemporal($datosBBDD,$numPresupuesto)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[facturasTemporal] where presupuesto = '".$numPresupuesto."'";
	
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar la tabla temporal de las facturas'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;
	
}*/


/*function insertarPresupuestoATemporal($datosBBDD,$numPresupuesto)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasTemporal] (presupuesto, cliente, otroConcepto,inicialComercial, cantidad, pedido, idFormaPago, detallada)
 select t1.presupuesto, t1.cliente, t1.campana2, t2.inicial, t1.cantidad2, t1.pedcli, t1.idFormaPago, t1.detallada 

 from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t2
 on t1.idComercial = t2.id
 where t1.presupuesto = '".$numPresupuesto."'";
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido copiar los datos del presupupuesto a la tabla temporal de facturas'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;
	
}*/



/*function mostrarFacturasTemporal($datosBBDD,$numPresupuesto)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[facturasTemporal] where presupuesto = '".$numPresupuesto."'";
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	
	
}*/



function borrarFacturaDetallesTemporal($datosBBDD,$numPresupuesto,$usuario)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal] where presupuesto = '".$numPresupuesto."' and idEmpleado =  ".$usuario;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar la tabla temporal de las facturas'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}


function borrarFacturaDetallesTemporalPorIdEmpleado($datosBBDD,$usuario)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal] where  idEmpleado =  ".$usuario;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar la tabla temporal de las facturas'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}



function insertarPresupuestoDetallesATemporal($datosBBDD,$numPresupuesto,$usuario)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	/*$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal] ([presupuesto],[concepto],[descripcion],[notaCibeles],[unidades],[precio], total,[ordenTipo],[orden])
SELECT t1.[presupuesto], t2.proceso, t1.descripcion, t1.notaCibeles, t1.unidades2, t1.precio, round(t1.unidades2*t1.precio,2) as total,t3.orden,t1.orden     
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t2
  on t1.idConcepto = t2.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t3
  on t1.idTipo = t3.id
 where t1.presupuesto = '".$numPresupuesto."'";	*/
	
	
	$consulta ="insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal] (idEmpleado, [presupuesto],[concepto],[descripcion],[notaCibeles],[unidades],[precio], total,[ordenTipo],[orden],[idTipoProceso],exentoIVA)
	
	SELECT '".$usuario."', t1.[presupuesto], t2.proceso, t1.descripcion, t1.notaCibeles, 

case when t1.unidades2 is NULL then t1.unidades
	else t1.unidades2
	end unidades2

, t1.precio, round((case when t1.unidades2 is NULL then t1.unidades
	else t1.unidades2
	end)*t1.precio,2) as total,t3.orden,t1.orden, t1.idTipo,exentoIVA    
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t2
  on t1.idConcepto = t2.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t3
  on t1.idTipo = t3.id
 where t1.presupuesto = '".$numPresupuesto."'  and t1.id not in ( 
	select id from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] where presupuesto = '".$numPresupuesto."' and  precio=0 and (idTipo=7 or idTipo=9 or idTipo=1 or idTipo=2))";	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido copiar los datos de los detalles del presupupuesto a la tabla temporal de facturas'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "";
	}
	
	return $mensaje;	
}

function insertarFacturasDetallesATemporal($datosBBDD,$numPresupuesto,$usuario,$numeroFactura,$anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta ="insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal] (idEmpleado, [presupuesto],[concepto],[descripcion],[notaCibeles],[unidades],[precio], total,[ordenTipo],[orden])
	
	SELECT '".$usuario."', '".$numPresupuesto."', concepto, descripcion, notaCibeles, unidades, precio, total, ordenTipo, orden 
	from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."]
 where factura = ".$numeroFactura;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido copiar los datos de los detalles del presupupuesto a la tabla temporal de facturas'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "";
	}
	
	return $mensaje;
	
}


function mostrarFacturasDetallesTemporal($datosBBDD,$numPresupuesto,$usuario)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal] where presupuesto = '".$numPresupuesto."' and idEmpleado= ".$usuario." order by ordenTipo, orden";
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;		
}

function mostrarFacRecDetallesTemporal($datosBBDD,$facturaOriginal,$clayma,$usuario)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesTemporal] where facturaOriginal = '".$facturaOriginal."' and idUsuario= ".$usuario." and clayma= ".$clayma." order by ordenTipo, orden";
	//echo $consulta;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;		
}

function modificarDetallePreFactura ($datosBBDD, $idDetalle,$proceso,$descripcion,$nota,$unidad,$precio,$total,$exentoIVA)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal]
	set concepto = '".$proceso."', descripcion='".$descripcion."', notaCibeles='".$nota."', unidades=".$unidad.", precio=".$precio.", total=".$total.", exentoIVA=".$exentoIVA."
	where id = ".$idDetalle;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Detalle de la Prefactura Modificado");
	}
	
	return $mensaje;	
}

function modificarDetalleRecTemporal ($datosBBDD, $idDetalle,$proceso,$descripcion,$nota,$unidad,$precio,$total,$exentoIVA)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesTemporal]
	set concepto = '".$proceso."', descripcion='".$descripcion."', notaCibeles='".$nota."', unidades=".$unidad.", precio=".$precio.", total=".$total.", exentoIVA=".$exentoIVA."
	where id = ".$idDetalle;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Detalle de la Prefactura Modificado");
	}
	
	return $mensaje;	
}
function cargarUnDetalleRecTemporal($datosBBDD,$idDetalle)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesTemporal] where id = '".$idDetalle."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function cargarUnDetallePrefactura($datosBBDD,$idDetalle)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal] where id = '".$idDetalle."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function eliminarDetallePreFactura ($datosBBDD, $idDetalle)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal]	 
	where id = ".$idDetalle;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	if( $resultado === false)
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}
	sqlsrv_close($conn_sis);
	//return $resultado;
}

function eliminarDetalleRecTemporal ($datosBBDD, $idDetalle)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesTemporal] 
	where id = ".$idDetalle;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	if( $resultado === false)
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}
	sqlsrv_close($conn_sis);
	//return $resultado;
}

function insertarDetallePrefactura ($datosBBDD, $presupuesto,$proceso,$descripcion,$nota,$unidad,$precio,$usuario,$exentoIVA)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal] ([presupuesto],[concepto],[descripcion],[notaCibeles],[unidades],[precio], total,[ordenTipo],[orden],idEmpleado,exentoIVA) values ('".$presupuesto."','".$proceso."','".$descripcion."','".$nota."',".$unidad.",".$precio.",".round($precio*$unidad,2).",1000,1000,".$usuario.",".$exentoIVA.")";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	/*if( $resultado === false)
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}*/
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar..\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Detalle Insertado");
	}
	
	return $mensaje;	
	//return $resultado;
}

function insertarDetalleRecTemporal ($datosBBDD,$facturaOriginal,$proceso,$descripcion,$nota,$unidad,$precio,$usuario,$exentoIVA,$clayma)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesTemporal] 
	([facturaOriginal],[concepto],[descripcion],[notaCibeles],[unidades],[precio], total,[ordenTipo],[orden],idUsuario,exentoIVA,clayma) 
	values ('".$facturaOriginal."','".$proceso."','".$descripcion."','".$nota."',".$unidad.",".$precio.",".round($precio*$unidad,2).",1000,1000,".$usuario.",".$exentoIVA.",".$clayma.")";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	/*if( $resultado === false)
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}*/
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	
	/*if( $resultado === false) 
	{		
		echo( print_r( sqlsrv_errors(), true) );
	}*/

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar..\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Detalle Insertado");
	}
	
	return $mensaje;	
	//return $resultado;
}




function insertarFactura($datosBBDD, $presupuesto , $cliente, $codigoCliente, $fecha, $pedidoCliente, $cantidad, $formaPago,$numCuenta, $campana, $detallada, $neto, $iva, $irpf, $total, $provision, $aPagar, $inicial,$anioSeleccionado,$combinadoSumatorio, $prefactura=0)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] (presupuesto, cliente, descripcion, fecha, inicialComercial, precioNeto, iva, irpf, precioTotal,provision, aPagar, cantidad, pedido, formaPago, detallada, numCuentaBanco,combinadoSumatorio,prefactura) values ('".$presupuesto."','".$cliente."','".$campana."','".$fecha."','".$inicial."',".$neto.",".$iva.",".$irpf.",".$total.",".$provision.",".$aPagar.",".$cantidad.",'".$pedidoCliente."','".$formaPago."',".$detallada.",'".$numCuenta."',".$combinadoSumatorio.",".$prefactura.")";
	$anioDosDigitos = $anioSeleccionado - 2000;
	
	$consulta = "
INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."]
(numero,numeroFacturaCompleto, presupuesto, cliente, idCodigoCliente, descripcion, fecha, inicialComercial, precioNeto, iva, irpf, precioTotal, provision, aPagar, cantidad, pedido, formaPago, detallada, numCuentaBanco, combinadoSumatorio, prefactura)
VALUES (
  (SELECT ISNULL(MAX(numero),0) + 1 FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."]),
  CONCAT('FAC ',(SELECT ISNULL(MAX(numero),0) + 1 FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."]),'/".$anioDosDigitos."'),
  '".$presupuesto."',
  '".$cliente."',
  ".$codigoCliente.",
  '".$campana."',
  '".$fecha."',
  '".$inicial."',
  ".$neto.",
  ".$iva.",
  ".$irpf.",
  ".$total.",
  ".$provision.",
  ".$aPagar.",
  ".$cantidad.",
  '".$pedidoCliente."',
  '".$formaPago."',
  ".$detallada.",
  '".$numCuenta."',
  ".$combinadoSumatorio.",
  ".$prefactura."
)";

	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Factura Grabada");
	}
	
	return $mensaje;	
}

function insertarFactura2($datosBBDD, $presupuesto , $cliente, $fecha, $pedidoCliente, $cantidad, $formaPago,$numCuenta, $campana, $detallada, $neto, $iva, $total, $provision, $aPagar, $inicial,$anioSeleccionado,$combinadoSumatorio=0)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] (presupuesto, cliente, descripcion, fecha, inicialComercial, precioNeto, iva, precioTotal,provision, aPagar, cantidad, pedido, formaPago, detallada, numCuentaBanco,combinadoSumatorio) values ('".$presupuesto."','".$cliente."','".$campana."','".$fecha."','".$inicial."',".$neto.",".$iva.",".$total.",".$provision.",".$aPagar.",".$cantidad.",'".$pedidoCliente."','".$formaPago."',".$detallada.",'".$numCuenta."',".$combinadoSumatorio.")";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	//sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$consulta = "SELECT SCOPE_IDENTITY() AS 'numeroFactura'";
		/*$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

		$result = array();

		while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
		{
			$result[] = $valor;
		}

		sqlsrv_close($conn_sis);
		return $result;	*/
		$stmt  = sqlsrv_query($conn_sis, $consulta);
		
		$mensaje="";

		if( sqlsrv_fetch($stmt)  === false ) 
		{
			$mensaje = "Error: no se ha podido ver la id del usuario insertado'.\n".$stmt ."\n".$consulta."\n";
			$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		}
		else
		{

			$mensaje = sqlsrv_get_field($stmt , 0);
			//$mensaje = $mensaje."|||Usuario insertado";

		}			
	}
	
	return $mensaje;	
}


function insertarFacturaClayma($datosBBDD, $presupuesto , $cliente, $codigoCliente, $fecha, $pedidoCliente, $cantidad, $formaPago,$numCuenta, $campana, $detallada, $neto, $iva, $irpf, $total, $provision, $aPagar, $inicial,$anioSeleccionado,$combinadoSumatorio,$prefactura=0)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] (presupuesto, cliente, descripcion, fecha, inicialComercial, precioNeto, iva, irpf, precioTotal,provision, aPagar, cantidad, pedido, formaPago, detallada, numCuentaBanco,combinadoSumatorio,prefactura) values ('".$presupuesto."','".$cliente."','".$campana."','".$fecha."','".$inicial."',".$neto.",".$iva.",".$irpf.",".$total.",".$provision.",".$aPagar.",".$cantidad.",'".$pedidoCliente."','".$formaPago."',".$detallada.",'".$numCuenta."',".$combinadoSumatorio.",".$prefactura.")";
	$anioDosDigitos = $anioSeleccionado - 2000;
	
	$consulta = "
INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."]
(numero,numeroFacturaCompleto, presupuesto, cliente, idCodigoCliente, descripcion, fecha, inicialComercial, precioNeto, iva, irpf, precioTotal, provision, aPagar, cantidad, pedido, formaPago, detallada, numCuentaBanco, combinadoSumatorio, prefactura)
VALUES (
  (SELECT ISNULL(MAX(numero),0) + 1 FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."]),
 CONCAT('FAC ',(SELECT ISNULL(MAX(numero),0) + 1 FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."]),'/".$anioDosDigitos."'), 
  '".$presupuesto."',
  '".$cliente."',
   ".$codigoCliente.",
  '".$campana."',
  '".$fecha."',
  '".$inicial."',
  ".$neto.",
  ".$iva.",
  ".$irpf.",
  ".$total.",
  ".$provision.",
  ".$aPagar.",
  ".$cantidad.",
  '".$pedidoCliente."',
  '".$formaPago."',
  ".$detallada.",
  '".$numCuenta."',
  ".$combinadoSumatorio.",
  ".$prefactura."
)";
	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Factura Grabada");
	}
	
	return $mensaje;	
}

function insertarFacturaRec($datosBBDD,$motivo, $facturaOriginal, $cliente, $codigoCliente, $fecha, $pedidoCliente, $cantidad, $formaPago,$numCuenta, $campana, $detallada, $neto, $iva, $irpf, $total, $provision, $aPagar, $anioSeleccionado,$combinadoSumatorio)
{
	$connectionInfo = array(
        "Database" => $datosBBDD->bbddBBDD,
        "UID"      => $datosBBDD->bbddUser,
        "PWD"      => $datosBBDD->bbddPass,
        "CharacterSet" => "UTF-8"
    );

    $conn_sis = sqlsrv_connect($datosBBDD->dbhost, $connectionInfo);

    if (!$conn_sis) {
        return array(
            "ok"      => false,
            "mensaje" => "Error de conexión: " . print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    // Intentar iniciar la transacción
    if (!sqlsrv_begin_transaction($conn_sis)) {
        return array(
            "ok"      => false,
            "mensaje" => "No se pudo iniciar transacción: " . print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    // Si todo va bien, devolvemos la conexión activa
	/*
    return array(
        "ok"      => true,
        "mensaje" => "Conexión establecida y transacción iniciada",
        "data"    => $conn_sis
    );
	*/
	// 2) Calcular el nuevo número con bloqueos (serializable sobre el rango)
    $sqlSel = "SELECT ISNULL(MAX(numero),0) + 1 AS n FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferencias".$anioSeleccionado."] WITH (UPDLOCK, HOLDLOCK)";
   
	$stmtSel = sqlsrv_query($conn_sis, $sqlSel);
    if ($stmtSel === false) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));
		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error SELECT MAX(numero): ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    $row = sqlsrv_fetch_array($stmtSel, SQLSRV_FETCH_ASSOC);

	if (!$row || !isset($row['n'])) {
        sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "No se obtuvo un número válido en SELECT MAX(numero)",
            "data"    => null
        );
    }

	$serieFactura = "RECT";
	$anioDosDigitos = $anioSeleccionado - 2000;
    $numero = (int)$row['n'];
    $numeroCompleto = $serieFactura.' '.$numero.'/'.$anioDosDigitos;

	
	$consulta = "
INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferencias".$anioSeleccionado."]
(numero,serieFactura,numeroFacturaCompleto, origenFactura, motivo, cliente, idCodigoCliente, descripcion, fecha, precioNeto, iva, irpf, precioTotal, provision, aPagar, cantidad, pedido, formaPago, detallada, numCuentaBanco, combinadoSumatorio)
VALUES (
  ".$numero.",
  '".$serieFactura."',
  '".$numeroCompleto."',
  '".$facturaOriginal."',
  '".$motivo."',
  '".$cliente."',
  ".$codigoCliente.",
  '".$campana."',
  '".$fecha."',
  ".$neto.",
  ".$iva.",
  ".$irpf.",
  ".$total.",
  ".$provision.",
  ".$aPagar.",
  ".$cantidad.",
  '".$pedidoCliente."',
  '".$formaPago."',
  ".$detallada.",
  '".$numCuenta."',
  ".$combinadoSumatorio."

)";

	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if ($resultado === false) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));

		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error en INSERT: ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }
	

	if (!sqlsrv_commit($conn_sis)) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));
		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error en COMMIT: ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

	sqlsrv_close($conn_sis);
	

	// Devuelve el número reservado/insertado (y lo que se necesite)
    //return array(array('numero' => $numero, 'numeroFacturaCompleto' => $numeroCompleto));

	return array(
        "ok"      => true,
        "mensaje" => "Factura insertada correctamente con número $numeroCompleto",
        "data"    => array(
						array(
							'numero' => $numero, 
							'numeroFacturaCompleto' => $numeroCompleto,
							'anio' => $anioSeleccionado
							)
						)

    			);
}


function insertarFacturaRecClayma($datosBBDD,$motivo, $facturaOriginal, $cliente, $codigoCliente, $fecha, $pedidoCliente, $cantidad, $formaPago,$numCuenta, $campana, $detallada, $neto, $iva, $irpf, $total, $provision, $aPagar, $anioSeleccionado,$combinadoSumatorio)
{
	$connectionInfo = array(
        "Database" => $datosBBDD->bbddBBDD,
        "UID"      => $datosBBDD->bbddUser,
        "PWD"      => $datosBBDD->bbddPass,
        "CharacterSet" => "UTF-8"
    );

    $conn_sis = sqlsrv_connect($datosBBDD->dbhost, $connectionInfo);

    if (!$conn_sis) {
        return array(
            "ok"      => false,
            "mensaje" => "Error de conexión: " . print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    // Intentar iniciar la transacción
    if (!sqlsrv_begin_transaction($conn_sis)) {
        return array(
            "ok"      => false,
            "mensaje" => "No se pudo iniciar transacción: " . print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    // Si todo va bien, devolvemos la conexión activa
	/*
    return array(
        "ok"      => true,
        "mensaje" => "Conexión establecida y transacción iniciada",
        "data"    => $conn_sis
    );
	*/
	// 2) Calcular el nuevo número con bloqueos (serializable sobre el rango)
    $sqlSel = "SELECT ISNULL(MAX(numero),0) + 1 AS n FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferenciasClayma".$anioSeleccionado."] WITH (UPDLOCK, HOLDLOCK)";
   
	$stmtSel = sqlsrv_query($conn_sis, $sqlSel);
    if ($stmtSel === false) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));
		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error SELECT MAX(numero): ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    $row = sqlsrv_fetch_array($stmtSel, SQLSRV_FETCH_ASSOC);

	if (!$row || !isset($row['n'])) {
        sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "No se obtuvo un número válido en SELECT MAX(numero)",
            "data"    => null
        );
    }

	$serieFactura = "RECT";
	$anioDosDigitos = $anioSeleccionado - 2000;
    $numero = (int)$row['n'];
    $numeroCompleto = $serieFactura.' '.$numero.'/'.$anioDosDigitos;

	
	$consulta = "
INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferenciasClayma".$anioSeleccionado."]
(numero,serieFactura,numeroFacturaCompleto, origenFactura, motivo, cliente, idCodigoCliente, descripcion, fecha, precioNeto, iva, irpf, precioTotal, provision, aPagar, cantidad, pedido, formaPago, detallada, numCuentaBanco, combinadoSumatorio)
VALUES (
  ".$numero.",
  '".$serieFactura."',
  '".$numeroCompleto."',
  '".$facturaOriginal."',
  '".$motivo."',
  '".$cliente."',
  ".$codigoCliente.",
  '".$campana."',
  '".$fecha."',
  ".$neto.",
  ".$iva.",
  ".$irpf.",
  ".$total.",
  ".$provision.",
  ".$aPagar.",
  ".$cantidad.",
  '".$pedidoCliente."',
  '".$formaPago."',
  ".$detallada.",
  '".$numCuenta."',
  ".$combinadoSumatorio."

)";

	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if ($resultado === false) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));

		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error en INSERT: ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }
	

	if (!sqlsrv_commit($conn_sis)) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));
		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error en COMMIT: ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

	sqlsrv_close($conn_sis);
	

	// Devuelve el número reservado/insertado (y lo que se necesite)
    //return array(array('numero' => $numero, 'numeroFacturaCompleto' => $numeroCompleto));

	return array(
        "ok"      => true,
        "mensaje" => "Factura insertada correctamente con número $numeroCompleto",
        "data"    => array(
						array(
							'numero' => $numero, 
							'numeroFacturaCompleto' => $numeroCompleto,
							'anio' => $anioSeleccionado
							)
						)

    			);
}

function insertarFacturaRecSustClayma($datosBBDD,$motivo, $facturaOriginal, $fechaFacOriginal, $cliente, $codigoCliente, $fecha, $pedidoCliente, $cantidad, $formaPago,$numCuenta, $campana, $detallada, $neto, $iva, $irpf, $total, $provision, $aPagar, $anioSeleccionado,$combinadoSumatorio)
{
	$connectionInfo = array(
        "Database" => $datosBBDD->bbddBBDD,
        "UID"      => $datosBBDD->bbddUser,
        "PWD"      => $datosBBDD->bbddPass,
        "CharacterSet" => "UTF-8"
    );

    $conn_sis = sqlsrv_connect($datosBBDD->dbhost, $connectionInfo);

    if (!$conn_sis) {
        return array(
            "ok"      => false,
            "mensaje" => "Error de conexión: " . print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    // Intentar iniciar la transacción
    if (!sqlsrv_begin_transaction($conn_sis)) {
        return array(
            "ok"      => false,
            "mensaje" => "No se pudo iniciar transacción: " . print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    // Si todo va bien, devolvemos la conexión activa
	/*
    return array(
        "ok"      => true,
        "mensaje" => "Conexión establecida y transacción iniciada",
        "data"    => $conn_sis
    );
	*/
	// 2) Calcular el nuevo número con bloqueos (serializable sobre el rango)
    $sqlSel = "SELECT ISNULL(MAX(numero),0) + 1 AS n FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucionClayma".$anioSeleccionado."] WITH (UPDLOCK, HOLDLOCK)";
   
	$stmtSel = sqlsrv_query($conn_sis, $sqlSel);
    if ($stmtSel === false) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));
		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error SELECT MAX(numero): ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    $row = sqlsrv_fetch_array($stmtSel, SQLSRV_FETCH_ASSOC);

	if (!$row || !isset($row['n'])) {
        sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "No se obtuvo un número válido en SELECT MAX(numero)",
            "data"    => null
        );
    }

	$serieFactura = "SUST";
	$anioDosDigitos = $anioSeleccionado - 2000;
    $numero = (int)$row['n'];
    $numeroCompleto = $serieFactura.' '.$numero.'/'.$anioDosDigitos;

	
	$consulta = "
INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucionClayma".$anioSeleccionado."]
(numero,serieFactura,numeroFacturaCompleto, origenFactura, fechaFacOrigenFactura, motivo, cliente, idCodigoCliente, descripcion, fecha, precioNeto, iva, irpf, precioTotal, provision, aPagar, cantidad, pedido, formaPago, detallada, numCuentaBanco, combinadoSumatorio)
VALUES (
  ".$numero.",
  '".$serieFactura."',
  '".$numeroCompleto."',
  '".$facturaOriginal."',
  '".$fechaFacOriginal."',
  '".$motivo."',
  '".$cliente."',
  ".$codigoCliente.",
  '".$campana."',
  '".$fecha."',
  ".$neto.",
  ".$iva.",
  ".$irpf.",
  ".$total.",
  ".$provision.",
  ".$aPagar.",
  ".$cantidad.",
  '".$pedidoCliente."',
  '".$formaPago."',
  ".$detallada.",
  '".$numCuenta."',
  ".$combinadoSumatorio."

)";

	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if ($resultado === false) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));

		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error en INSERT: ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }
	

	if (!sqlsrv_commit($conn_sis)) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));
		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error en COMMIT: ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

	sqlsrv_close($conn_sis);
	

	// Devuelve el número reservado/insertado (y lo que se necesite)
    //return array(array('numero' => $numero, 'numeroFacturaCompleto' => $numeroCompleto));

	return array(
        "ok"      => true,
        "mensaje" => "Factura insertada correctamente con número $numeroCompleto",
        "data"    => array(
						array(
							'numero' => $numero, 
							'numeroFacturaCompleto' => $numeroCompleto,
							'anio' => $anioSeleccionado
							)
						)

    			);
}

function insertarFacturaRecSust($datosBBDD,$motivo, $facturaOriginal, $fechaFacOrigenFactura, $cliente, $codigoCliente, $fecha, $pedidoCliente, $cantidad, $formaPago,$numCuenta, $campana, $detallada, $neto, $iva, $irpf, $total, $provision, $aPagar, $anioSeleccionado,$combinadoSumatorio)
{
	$connectionInfo = array(
        "Database" => $datosBBDD->bbddBBDD,
        "UID"      => $datosBBDD->bbddUser,
        "PWD"      => $datosBBDD->bbddPass,
        "CharacterSet" => "UTF-8"
    );

    $conn_sis = sqlsrv_connect($datosBBDD->dbhost, $connectionInfo);

    if (!$conn_sis) {
        return array(
            "ok"      => false,
            "mensaje" => "Error de conexión: " . print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    // Intentar iniciar la transacción
    if (!sqlsrv_begin_transaction($conn_sis)) {
        return array(
            "ok"      => false,
            "mensaje" => "No se pudo iniciar transacción: " . print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    // Si todo va bien, devolvemos la conexión activa
	/*
    return array(
        "ok"      => true,
        "mensaje" => "Conexión establecida y transacción iniciada",
        "data"    => $conn_sis
    );
	*/
	// 2) Calcular el nuevo número con bloqueos (serializable sobre el rango)
    $sqlSel = "SELECT ISNULL(MAX(numero),0) + 1 AS n FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucion".$anioSeleccionado."] WITH (UPDLOCK, HOLDLOCK)";
   //echo $sqlSel;
	$stmtSel = sqlsrv_query($conn_sis, $sqlSel);
    if ($stmtSel === false) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));
		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error SELECT MAX(numero): ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    $row = sqlsrv_fetch_array($stmtSel, SQLSRV_FETCH_ASSOC);

	if (!$row || !isset($row['n'])) {
        sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "No se obtuvo un número válido en SELECT MAX(numero)",
            "data"    => null
        );
    }

	$serieFactura = "SUST";
	$anioDosDigitos = $anioSeleccionado - 2000;
    $numero = (int)$row['n'];
    $numeroCompleto = $serieFactura.' '.$numero.'/'.$anioDosDigitos;

	
	$consulta = "
INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucion".$anioSeleccionado."]
(numero,serieFactura,numeroFacturaCompleto, origenFactura, fechaFacOrigenFactura, motivo, cliente, idCodigoCliente, descripcion, fecha, precioNeto, iva, irpf, precioTotal, provision, aPagar, cantidad, pedido, formaPago, detallada, numCuentaBanco, combinadoSumatorio)
VALUES (
  ".$numero.",
  '".$serieFactura."',
  '".$numeroCompleto."',
  '".$facturaOriginal."',
  '".$fechaFacOrigenFactura."',
  '".$motivo."',
  '".$cliente."',
  ".$codigoCliente.",
  '".$campana."',
  '".$fecha."',
  ".$neto.",
  ".$iva.",
  ".$irpf.",
  ".$total.",
  ".$provision.",
  ".$aPagar.",
  ".$cantidad.",
  '".$pedidoCliente."',
  '".$formaPago."',
  ".$detallada.",
  '".$numCuenta."',
  ".$combinadoSumatorio."

)";

	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if ($resultado === false) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));

		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error en INSERT: ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }
	

	if (!sqlsrv_commit($conn_sis)) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));
		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error en COMMIT: ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

	sqlsrv_close($conn_sis);
	

	// Devuelve el número reservado/insertado (y lo que se necesite)
    //return array(array('numero' => $numero, 'numeroFacturaCompleto' => $numeroCompleto));

	return array(
        "ok"      => true,
        "mensaje" => "Factura insertada correctamente con número $numeroCompleto",
        "data"    => array(
						array(
							'numero' => $numero, 
							'numeroFacturaCompleto' => $numeroCompleto,
							'anio' => $anioSeleccionado
							)
						)

    			);
}

function verProvisionDeFondoPorPresupuesto($datosBBDD, $presupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] where presupuesto = '".$presupuesto."' and cobrada = 2  and (numFacturaAplicada is null or numFacturaAplicada ='')   and tipo=3";
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] where presupuesto = '".$presupuesto."' and cobrada = 2  and (facCompletaAplicada is null or facCompletaAplicada ='')   and tipo=3";
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function verProvisionDeFondoPorPresupuesto2($datosBBDD, $presupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] where presupuesto like '".$presupuesto."%' and cobrada = 2  and (numFacturaAplicada is null or numFacturaAplicada ='')   and tipo=3";
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] where presupuesto like '".$presupuesto."%' and cobrada = 2  and (facCompletaAplicada is null or facCompletaAplicada ='')   and tipo=3";
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function verProvisionDeFondoPorPresupuestoTodo($datosBBDD, $presupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select ISNULL(sum(importe),0) as importeTotal from [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] where  tipo=3 and cobrada = 2 and presupuesto = '".$presupuesto."'";
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function modificarFactura_facturaRec($datosBBDD,$facturaOriginal,$anioFacturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioFacturaOriginal."] 
	set facRecDiferencia = 1 	
	where numeroFacturaCompleto= '".$facturaOriginal."'";
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}

function modificarFacturaRec_facturaRec($datosBBDD,$facturaOriginal,$anioFacturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferencias".$anioFacturaOriginal."] 
	set facRecDiferencia = 1 	
	where numeroFacturaCompleto= '".$facturaOriginal."'";
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}


function modificarFacturaRecSust_facturaRec($datosBBDD,$facturaOriginal,$anioFacturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucion".$anioFacturaOriginal."] 
	set facRecDiferencia = 1 	
	where numeroFacturaCompleto= '".$facturaOriginal."'";
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}

function modificarFactura_facturaRecClayma($datosBBDD,$facturaOriginal,$anioFacturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioFacturaOriginal."] 
	set facRecDiferencia = 1 	
	where numeroFacturaCompleto= '".$facturaOriginal."'";
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}



function modificarFacturaRec_facturaRecClayma($datosBBDD,$facturaOriginal,$anioFacturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferenciasClayma".$anioFacturaOriginal."] 
	set facRecDiferencia = 1 	
	where numeroFacturaCompleto= '".$facturaOriginal."'";
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}

function modificarFacturaRecSust_facturaRecClayma($datosBBDD,$facturaOriginal,$anioFacturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucionClayma".$anioFacturaOriginal."] 
	set facRecDiferencia = 1 	
	where numeroFacturaCompleto= '".$facturaOriginal."'";
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}

function modificarFactura_facturaRecSustClayma($datosBBDD,$facturaOriginal,$anioFacturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioFacturaOriginal."] 
	set facRecSustitucion = 1 	
	where numeroFacturaCompleto= '".$facturaOriginal."'";
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}
         
function modificarFacturaRec_facturaRecSustClayma($datosBBDD,$facturaOriginal,$anioFacturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferenciasClayma".$anioFacturaOriginal."] 
	set facRecSustitucion = 1 	
	where numeroFacturaCompleto= '".$facturaOriginal."'";
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}


function modificarFacturaRecSust_facturaReSustClayma($datosBBDD,$facturaOriginal,$anioFacturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucionClayma".$anioFacturaOriginal."] 
	set facRecSustitucion = 1 	
	where numeroFacturaCompleto= '".$facturaOriginal."'";
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}



function modificarFactura_facturaRecSust($datosBBDD,$facturaOriginal,$anioFacturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioFacturaOriginal."] 
	set facRecSustitucion = 1 	
	where numeroFacturaCompleto= '".$facturaOriginal."'";
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}

function modificarFacturaRec_facturaRecSust($datosBBDD,$facturaOriginal,$anioFacturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferencias".$anioFacturaOriginal."] 
	set facRecSustitucion = 1 	
	where numeroFacturaCompleto= '".$facturaOriginal."'";
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}

function modificarFacturaRecSust_facturaRecSust($datosBBDD,$facturaOriginal,$anioFacturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucion".$anioFacturaOriginal."] 
	set facRecSustitucion = 1 	
	where numeroFacturaCompleto= '".$facturaOriginal."'";
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}

function borrarDetallesTemporalesFacRec($datosBBDD,$idEmpleado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesTemporal] where idUsuario =  ".$idEmpleado;
	
	//echo $consulta;
 	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}

function modificarPreciosFacturaMasivo($datosBBDD, $numeroDeFactura, $anioSeleccionado, $totalNetoIVAincluido, $iva,$irpf,$total,$aPagar,$provision)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] 
	set precioNeto = ".$totalNetoIVAincluido.", iva = ".$iva.", irpf=".$irpf.", precioTotal=".$total.", provision=".$provision.", aPagar=".$aPagar."
	where numero= ".$numeroDeFactura;
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}


function modificarPreciosFacturaClaymaMasivo($datosBBDD, $numeroDeFactura, $anioSeleccionado, $totalNetoIVAincluido, $iva,$irpf,$total,$aPagar,$provision)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] 
	set precioNeto = ".$totalNetoIVAincluido.", iva = ".$iva.", irpf=".$irpf.", precioTotal=".$total.", provision=".$provision.", aPagar=".$aPagar."
	where numero= ".$numeroDeFactura;
		
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}


	
function mostrarNumFacturaPorPresupuesto($datosBBDD, $presupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] where presupuesto = '".$presupuesto."'";
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function mostrarNumFacturaPorPresupuestoClayma($datosBBDD, $presupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] where presupuesto = '".$presupuesto."'";
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function copiarTemporalPreFacturaAFacturaDetalle($datosBBDD, $numFactura,$presupuesto,$usuario,$anioSeleccionado,$combinacion=0,$campana="")
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	if ($combinacion==0)
	{
		$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."] (factura, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden,exentoIVA)

		SELECT ".$numFactura." as numFactura, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden,isnull(exentoIva,0)
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal]
  where presupuesto = '".$presupuesto."' and idEmpleado=".$usuario; //añadir condicion usuario
	}
	else if ($combinacion==1)
	{		
		$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."] (factura, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden,exentoIVA, presupuesto, campana)

		SELECT ".$numFactura." as numFactura, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden,isnull(exentoIva,0), '".$presupuesto."', '".$campana."'
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal]
  where presupuesto = '".$presupuesto."' and idEmpleado=".$usuario;
	}
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("");
	}
	
	return $mensaje;	
}


function copiarTemporalPreFacturaAFacturaDetalleClayma($datosBBDD, $numFactura,$presupuesto,$usuario,$anioSeleccionado,$combinacion=0,$campana="")
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	if ($combinacion==0)
	{
		$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClayma".$anioSeleccionado."] (factura, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden,exentoIVA)

		SELECT ".$numFactura." as numFactura, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden,exentoIVA
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal]
  where presupuesto = '".$presupuesto."' and idEmpleado=".$usuario;
	}
	else if ($combinacion==1)
	{		
		$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClayma".$anioSeleccionado."] (factura, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden,exentoIVA, presupuesto, campana)

		SELECT ".$numFactura." as numFactura, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden,exentoIVA, '".$presupuesto."', '".$campana."'
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal]
  where presupuesto = '".$presupuesto."' and idEmpleado=".$usuario;
	}
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("");
	}
	
	return $mensaje;	
}	
	
	

function copiarTemporalRecAFacturaDetalleRec($datosBBDD,$facturaOriginal,$idEmpleado,$anioSeleccionado,$numeroFacturaRec)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetalles".$anioSeleccionado."] (numeroFacturaCompleto, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden, exentoIVA, idUsuario) 
select '".$numeroFacturaRec."', concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden, exentoIVA, idUsuario 
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesTemporal]
where facturaOriginal = '".$facturaOriginal."' and idUsuario = ".$idEmpleado." and clayma = 0";	
	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar la factura rectificativa'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("");
	}
	
	return $mensaje;	
}

function copiarTemporalRecAFacturaDetalleRecSust($datosBBDD,$facturaOriginal,$idEmpleado,$anioSeleccionado,$numeroFacturaRec)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustDetalles".$anioSeleccionado."] (numeroFacturaCompleto, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden, exentoIVA, idUsuario) 
select '".$numeroFacturaRec."', concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden, exentoIVA, idUsuario 
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesTemporal]
where facturaOriginal = '".$facturaOriginal."' and idUsuario = ".$idEmpleado." and clayma = 0";	
	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar la factura rectificativa sustitutiva'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("");
	}
	
	return $mensaje;	
}


function copiarTemporalDetallesRec($datosBBDD,$idEmpleado,$anioSeleccionado,$soloNumFactura,$facturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesTemporal] 
	(facturaOriginal, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden, presupuesto, campana, exentoIVA, idUsuario, clayma) 
select '".$facturaOriginal."', concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden, presupuesto, campana, exentoIVA, ".$idEmpleado.",0
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."]
where factura = '".$soloNumFactura."'";	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar los detalles de la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("");
	}
	
	return $mensaje;	
}

function copiarTemporalDetallesRecClayma($datosBBDD,$idEmpleado,$anioSeleccionado,$soloNumFactura,$facturaOriginal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesTemporal] 
	(facturaOriginal, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden, presupuesto, campana, exentoIVA, idUsuario, clayma) 
select '".$facturaOriginal."', concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden, presupuesto, campana, exentoIVA, ".$idEmpleado.",1
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClayma".$anioSeleccionado."]
where factura = '".$soloNumFactura."'";	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar los detalles de la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("");
	}
	
	return $mensaje;	
}

function copiarTemporalRecAFacturaDetalleRecClayma($datosBBDD,$facturaOriginal,$idEmpleado,$anioSeleccionado,$numeroFacturaRec)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesClayma".$anioSeleccionado."] (numeroFacturaCompleto, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden, exentoIVA, idUsuario) 
select '".$numeroFacturaRec."', concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden, exentoIVA, idUsuario 
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesTemporal]
where facturaOriginal = '".$facturaOriginal."' and idUsuario = ".$idEmpleado." and clayma = 1";	
	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar la factura rectificativa'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("");
	}
	
	return $mensaje;	
}

function copiarTemporalRecAFacturaDetalleRecSustClayma($datosBBDD,$facturaOriginal,$idEmpleado,$anioSeleccionado,$numeroFacturaRec)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustDetallesClayma".$anioSeleccionado."] (numeroFacturaCompleto, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden, exentoIVA, idUsuario) 
select '".$numeroFacturaRec."', concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden, exentoIVA, idUsuario 
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesTemporal]
where facturaOriginal = '".$facturaOriginal."' and idUsuario = ".$idEmpleado." and clayma = 1";	
	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar los detalles rectificativa sustitutiva'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("");
	}
	
	return $mensaje;	
}
	

function eliminarDetallePreFacturaPorPresupuesto ($datosBBDD, $numPresupuesto)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal]	 
	where presupuesto = '".$numPresupuesto."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar los detalles de la factura en la tabla temporal'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Detalle eliminada en la tabla temporal");
	}
	
	return $mensaje;	
}


function copiarPresupuestoDetalleAAFacturaDetalle($datosBBDD, $numFactura,$presupuesto,$anioSeleccionado) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."]
           (factura
           ,concepto
           ,unidades
           ,precio
           ,total
           ,descripcion
           ,notaCibeles
           ,ordenTipo
           ,orden           
           ,exentoIVA)
		SELECT ".$numFactura." as factura
		, t2.proceso as concepto
		, case when t1.unidades2>0 then t1.unidades2 else t1.unidades end as unidades
		, t1.precio as precio
		, t1.precio* (case when t1.unidades2>0 then t1.unidades2 else t1.unidades end) as total
		, t1.descripcion
		, t1.notaCibeles
		, t3.orden as ordenTipo
		, t1.orden
		, t1.exentoIVA

		FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
		inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t2
		on t1.idConcepto = t2.id
		inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t3
		on t3.id = t1.idTipo
		where t1.presupuesto = '".$presupuesto."' and t1.id not in ( 
	select id from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] where presupuesto = '".$numPresupuesto."' and  precio=0 and (idTipo=7 or idTipo=9 or idTipo=1 or idTipo=2))
		order by t1.id ";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar los detalles la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("");
	}
	
	return $mensaje;	
}

function copiarPresupuestoDetalleAAFacturaDetalleClayma($datosBBDD, $numFactura,$presupuesto,$anioSeleccionado) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClayma".$anioSeleccionado."]
           (factura
           ,concepto
           ,unidades
           ,precio
           ,total
           ,descripcion
           ,notaCibeles
           ,ordenTipo
           ,orden           
           ,exentoIVA)
		SELECT ".$numFactura." as factura
		, t2.proceso as concepto
		, case when t1.unidades2>0 then t1.unidades2 else t1.unidades end as unidades
		, t1.precio as precio
		, t1.precio* (case when t1.unidades2>0 then t1.unidades2 else t1.unidades end) as total
		, t1.descripcion
		, t1.notaCibeles
		, t3.orden as ordenTipo
		, t1.orden
		, t1.exentoIVA

		FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
		inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t2
		on t1.idConcepto = t2.id
		inner join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t3
		on t3.id = t1.idTipo
		where t1.presupuesto = '".$presupuesto."'
		order by t1.id ";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar los detalles la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("");
	}
	
	return $mensaje;	
}

function modificarProvisionNumFactura ($datosBBDD, $idProvision,$numFactura) //prefactura mensual
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo]
	set numFacturaAplicada = $numFactura
	where id = ".$idProvision."";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el valor de numFacturaAplicada'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Numero de factura modificada en la provision de fondos");
	}
	
	return $mensaje;	
}

function modificarProvisionNumFacturaPorPresupuesto($datosBBDD,$numPresupuesto,$numFactura,$anioSeleccionado,$numeroFacturaCompleto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	/*
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo]
	set numFacturaAplicada = ".$numFactura.", numFacturaAplicadaAnio=".$anioSeleccionado."	
	 where presupuesto = '".$numPresupuesto."' and cobrada = 2  and (numFacturaAplicada is null or numFacturaAplicada ='')   and tipo=3";
	*/

	$consulta ="update [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo]
	set facCompletaAplicada = '".$numeroFacturaCompleto."'	
	 where presupuesto = '".$numPresupuesto."' and cobrada = 2  and (facCompletaAplicada is null or facCompletaAplicada ='')   and tipo=3";
	


	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el valor de numFacturaAplicada 2'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Numero de factura modificada en la provision de fondos");
	}
	
	return $mensaje;
}



function modificarProvisionNumFactura2 ($datosBBDD, $numFactura,$numPresupuesto,$numeroFacturaCompleto)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	/*
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo]
	set numFacturaAplicada = $numFactura
	where presupuesto like '".$numPresupuesto."%'";
	*/

	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo]
	set facCompletaAplicada = '".$numeroFacturaCompleto."'
	where presupuesto like '".$numPresupuesto."%'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el valor de numFacturaAplicada'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Numero de factura modificada en la provision de fondos");
	}
	
	return $mensaje;	
}

function verFacturaRectificativaSustitucion($datosBBDD,$numeroFacturaRec,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select case when irpf < 0 or irpf > 0 then precioNeto + iva else precioTotal end as precioTotalSinIrpf ,t1.*, t2.*
	, t1.numCuentaBanco as cuentaDelBanco, t3.codigo as codigoPais1, t3.nombreComun as nombrePais
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucion".$anioSeleccionado."] as t1
  inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t2.nombre_empresa = t1.cliente 
  left join [".$datosBBDD->bbddBBDD."].[dbo].[paises] as t3
  on t3.id = t2.pais  
  where  t2.codigo = t2.codigo_saldo and t1.numeroFacturaCompleto = '".$numeroFacturaRec."'";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaRectificativaSustitucionClayma($datosBBDD,$numeroFacturaRec,$anioSeleccionado) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select case when irpf < 0 or irpf > 0 then precioNeto + iva else precioTotal end as precioTotalSinIrpf, t1.*, t2.*
	, t1.numCuentaBanco as cuentaDelBanco, t3.codigo as codigoPais1, t3.nombreComun as nombrePais
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucionClayma".$anioSeleccionado."] as t1
  inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
  on t2.nombre_empresa = t1.cliente 
  left join [".$datosBBDD->bbddBBDD."].[dbo].[paises] as t3
  on t3.id = t2.pais  
  where  t2.codigo = t2.codigo_saldo and t1.numeroFacturaCompleto = '".$numeroFacturaRec."'";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaRectificativa($datosBBDD,$numeroFacturaRec,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select case when irpf < 0 or irpf > 0 then precioNeto + iva else precioTotal end as precioTotalSinIrpf, t1.*, t2.*
	, t1.numCuentaBanco as cuentaDelBanco, t3.codigo as codigoPais1, t3.nombreComun as nombrePais
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferencias".$anioSeleccionado."] as t1
  inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t2.nombre_empresa = t1.cliente 
  left join [".$datosBBDD->bbddBBDD."].[dbo].[paises] as t3
  on t3.id = t2.pais 
  where  t2.codigo = t2.codigo_saldo and t1.numeroFacturaCompleto = '".$numeroFacturaRec."'";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaRectificativaClayma($datosBBDD,$numeroFacturaRec,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$numeroFacturaRec = trim($numeroFacturaRec);
	//$numeroFacturaRec = str_replace(["\xC2\xA0", "\n", "\r", "\t"], ' ', $numeroFacturaRec);
	//$numeroFacturaRec = preg_replace('/\s+/', ' ', $numeroFacturaRec); // deja solo un espacio normal

	$consulta = "select case when irpf < 0 or irpf > 0 then precioNeto + iva else precioTotal end as precioTotalSinIrpf, t1.*, t2.*
	, t1.numCuentaBanco as cuentaDelBanco, t3.codigo as codigoPais1, t3.nombreComun as nombrePais
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferenciasClayma".$anioSeleccionado."] as t1
  inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
  on t2.nombre_empresa = t1.cliente 
  left join [".$datosBBDD->bbddBBDD."].[dbo].[paises] as t3
  on t3.id = t2.pais 
  where  t2.codigo = t2.codigo_saldo and t1.numeroFacturaCompleto = '".$numeroFacturaRec."'";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	 //$resultado = sqlsrv_query($conn_sis, $consulta,array(),array()); 
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	//$result = array();
	$result = [];
	//echo "<br>hey1";
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{//echo "<br>hey2";
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFactura($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select t1.prefactura as laprefactura, case when irpf < 0 or irpf > 0 then precioNeto + iva else precioTotal end as precioTotalSinIrpf, t1.*, t2.*
	, t1.numCuentaBanco as cuentaDelBanco, t3.codigo as codigoPais1, t3.nombreComun as nombrePais
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] as t1
  inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t2.nombre_empresa = t1.cliente 
  left join [".$datosBBDD->bbddBBDD."].[dbo].[paises] as t3
  on t3.id = t2.pais 
 
  where  t2.codigo = t2.codigo_saldo and t1.numero = ".$numFactura;	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFactura_numeroCompleto($datosBBDD,$numeroCompleto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$datosNumFactura=explode('/',$numeroCompleto);
	$anioSeleccionado = 2000+ $datosNumFactura[1];	

	$consulta = "select t1.*, t2.*, t1.numCuentaBanco as cuentaDelBanco
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] as t1
  inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t2.nombre_empresa = t1.cliente where  t2.codigo = t2.codigo_saldo and t1.numeroFacturaCompleto = '".$numeroCompleto."'";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFactura_numeroCompletoClayma($datosBBDD,$numeroCompleto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	

	$datosNumFactura=explode('/',$numeroCompleto);
	$anioSeleccionado = 2000+ $datosNumFactura[1];
	

	$consulta = "select t1.*, t2.*, t1.numCuentaBanco as cuentaDelBanco
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] as t1
  inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
  on t2.nombre_empresa = t1.cliente where  t2.codigo = t2.codigo_saldo and t1.numeroFacturaCompleto = '".$numeroCompleto."'";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaClayma($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select t1.prefactura as laprefactura, case when irpf < 0 or irpf > 0 then precioNeto + iva else precioTotal end as precioTotalSinIrpf, t1.*, t2.*
	, t1.numCuentaBanco as cuentaDelBanco, t3.codigo as codigoPais1, t3.nombreComun as nombrePais
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] as t1
  inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
  on t2.nombre_empresa = t1.cliente 
  left join [".$datosBBDD->bbddBBDD."].[dbo].[paises] as t3
  on t3.id = t2.pais  
  where t2.codigo = t2.codigo_saldo and t1.numero = ".$numFactura;	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaDetalle($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."] where factura = ".$numFactura. " order by presupuesto, ordenTipo, orden";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}
function verFacturaRecDetalle($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetalles".$anioSeleccionado."] where numeroFacturaCompleto = '".$numFactura. "' order by id";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaRecSustDetalle($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustDetalles".$anioSeleccionado."] where numeroFacturaCompleto = '".$numFactura. "' order by id";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaRecSustDetalleClayma($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustDetallesClayma".$anioSeleccionado."] where numeroFacturaCompleto = '".$numFactura. "' order by id";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaRecDetalleClayma($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesClayma".$anioSeleccionado."] where numeroFacturaCompleto = '".$numFactura. "' order by id";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaRecDetalleTotalesPorIVA($datosBBDD,$numeroFacturaRec,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT sum(total) as base, isnull(exentoIva,0) as exentoIva FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetalles".$anioSeleccionado."] 
	where numeroFacturaCompleto = '".$numeroFacturaRec."'
group by isnull(exentoIva,0)";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaRecSustDetalleTotalesPorIVA($datosBBDD,$numeroFacturaRec,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT sum(total) as base, isnull(exentoIva,0) as exentoIva FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustDetalles".$anioSeleccionado."] 
	where numeroFacturaCompleto = '".$numeroFacturaRec."'
group by isnull(exentoIva,0)";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaRecSustDetalleTotalesPorIVAClayma($datosBBDD,$numeroFacturaRec,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT sum(total) as base, isnull(exentoIva,0) as exentoIva FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustDetallesClayma".$anioSeleccionado."] 
	where numeroFacturaCompleto = '".$numeroFacturaRec."'
group by isnull(exentoIva,0)";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaRecDetalleTotalesPorIVAClayma($datosBBDD,$numeroFacturaRec,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT sum(total) as base, isnull(exentoIva,0) as exentoIva FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDetallesClayma".$anioSeleccionado."] 
	where numeroFacturaCompleto = '".$numeroFacturaRec."'
group by isnull(exentoIva,0)";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function verFacturaDetalleTotalesPorIVA($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT sum(total) as base, isnull(exentoIva,0) as exentoIva FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."] 
	where factura = ".$numFactura." 
group by isnull(exentoIva,0)";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaDetalleTotalesPorIVAClayma($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT sum(total) as base, isnull(exentoIva,0) as exentoIva FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClayma".$anioSeleccionado."] 
	where factura = ".$numFactura." 
group by isnull(exentoIva,0)";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}




function guardarVerifactuErrores($datosBBDD,$numFactura,$anioSeleccionado,$message, $code, $requestId)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] 
	set verifactu_message='".$message."', verifactu_code='".$code."', verifactu_idSolicitud='".$requestId."' 
	where numero=".$numFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar los errores de verifactu'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}
function guardarVerifactuErroresRec($datosBBDD,$numeroFacturaCompleto,$anioSeleccionado,$message, $code, $requestId)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferencias".$anioSeleccionado."] 
	set verifactu_message='".$message."', verifactu_code='".$code."', verifactu_idSolicitud='".$requestId."' 
	where numeroFacturaCompleto='".$numeroFacturaCompleto."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar los errores de verifactu'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}

function guardarVerifactuErroresRecSust($datosBBDD,$numeroFacturaCompleto,$anioSeleccionado,$message, $code, $requestId)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucion".$anioSeleccionado."] 
	set verifactu_message='".$message."', verifactu_code='".$code."', verifactu_idSolicitud='".$requestId."' 
	where numeroFacturaCompleto='".$numeroFacturaCompleto."'";
	
	//echo $consulta;

	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar los errores de verifactu'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}
function guardarVerifactuErroresRecSustClayma($datosBBDD,$numeroFacturaCompleto,$anioSeleccionado,$message, $code, $requestId)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucionClayma".$anioSeleccionado."] 
	set verifactu_message='".$message."', verifactu_code='".$code."', verifactu_idSolicitud='".$requestId."' 
	where numeroFacturaCompleto='".$numeroFacturaCompleto."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar los errores de verifactu'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}
function guardarVerifactuErroresRecClayma($datosBBDD,$numeroFacturaCompleto,$anioSeleccionado,$message, $code, $requestId)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferenciasClayma".$anioSeleccionado."] 
	set verifactu_message='".$message."', verifactu_code='".$code."', verifactu_idSolicitud='".$requestId."' 
	where numeroFacturaCompleto='".$numeroFacturaCompleto."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar los errores de verifactu'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}

function guardarVerifactuErroresClayma($datosBBDD,$numFactura,$anioSeleccionado,$message, $code, $requestId)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] 
	set verifactu_message='".$message."', verifactu_code='".$code."', verifactu_idSolicitud='".$requestId."' 
	where numero=".$numFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar los errores de verifactu'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}


function guardarVerifactuRespuesta($datosBBDD,$numFactura,$anioSeleccionado,$qr_code, $issuerIrsId, $issuedTime,$number,$hash,$verifactuUrl, $queueId, $requestId)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] 
	set verifactu_qrcode='".$qr_code."'
	, verifactu_idSolicitud='".$requestId."'
	, verifactu_nifExpedidor='".$issuerIrsId."'
	, verifactu_fechaExpedicion='".$issuedTime."'
	, verifactu_numFactura='".$number."'
	, verifactu_hast='".$hash."'
	, verifactu_url='".$verifactuUrl."'
	, verifactu_queueId='".$queueId."'	
	, verifactu_message=NULL
	
	where numero=".$numFactura;
	
	//echo $consulta;

	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar las respuestas del verifactu'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	

}
function guardarVerifactuRespuestaRec($datosBBDD,$numeroFacturaCompleto,$anioSeleccionado,$qr_code, $issuerIrsId, $issuedTime,$number,$hash,$verifactuUrl, $queueId, $requestId)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferencias".$anioSeleccionado."] 
	set verifactu_qrcode='".$qr_code."'
	, verifactu_idSolicitud='".$requestId."'
	, verifactu_nifExpedidor='".$issuerIrsId."'
	, verifactu_fechaExpedicion='".$issuedTime."'
	, verifactu_numFactura='".$number."'
	, verifactu_hast='".$hash."'
	, verifactu_url='".$verifactuUrl."'
	, verifactu_queueId='".$queueId."'	
	
	where numeroFacturaCompleto='".$numeroFacturaCompleto."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar las respuestas del verifactu'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	

}

function guardarVerifactuRespuestaRecSust($datosBBDD,$numeroFacturaCompleto,$anioSeleccionado,$qr_code, $issuerIrsId, $issuedTime,$number,$hash,$verifactuUrl, $queueId, $requestId)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucion".$anioSeleccionado."] 
	set verifactu_qrcode='".$qr_code."'
	, verifactu_idSolicitud='".$requestId."'
	, verifactu_nifExpedidor='".$issuerIrsId."'
	, verifactu_fechaExpedicion='".$issuedTime."'
	, verifactu_numFactura='".$number."'
	, verifactu_hast='".$hash."'
	, verifactu_url='".$verifactuUrl."'
	, verifactu_queueId='".$queueId."'	
	
	where numeroFacturaCompleto='".$numeroFacturaCompleto."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar las respuestas del verifactu'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	

}

function guardarVerifactuRespuestaRecSustClayma($datosBBDD,$numeroFacturaCompleto,$anioSeleccionado,$qr_code, $issuerIrsId, $issuedTime,$number,$hash,$verifactuUrl, $queueId, $requestId)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucionClayma".$anioSeleccionado."] 
	set verifactu_qrcode='".$qr_code."'
	, verifactu_idSolicitud='".$requestId."'
	, verifactu_nifExpedidor='".$issuerIrsId."'
	, verifactu_fechaExpedicion='".$issuedTime."'
	, verifactu_numFactura='".$number."'
	, verifactu_hast='".$hash."'
	, verifactu_url='".$verifactuUrl."'
	, verifactu_queueId='".$queueId."'	
	
	where numeroFacturaCompleto='".$numeroFacturaCompleto."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar las respuestas del verifactu'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	

}

function guardarVerifactuRespuestaRecClayma($datosBBDD,$numeroFacturaCompleto,$anioSeleccionado,$qr_code, $issuerIrsId, $issuedTime,$number,$hash,$verifactuUrl, $queueId, $requestId)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferenciasClayma".$anioSeleccionado."] 
	set verifactu_qrcode='".$qr_code."'
	, verifactu_idSolicitud='".$requestId."'
	, verifactu_nifExpedidor='".$issuerIrsId."'
	, verifactu_fechaExpedicion='".$issuedTime."'
	, verifactu_numFactura='".$number."'
	, verifactu_hast='".$hash."'
	, verifactu_url='".$verifactuUrl."'
	, verifactu_queueId='".$queueId."'	
	
	where numeroFacturaCompleto='".$numeroFacturaCompleto."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar las respuestas del verifactu'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	

}

function guardarVerifactuRespuestaClayma($datosBBDD,$numFactura,$anioSeleccionado,$qr_code, $issuerIrsId, $issuedTime,$number,$hash,$verifactuUrl, $queueId, $requestId)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] 
	set verifactu_qrcode='".$qr_code."'
	, verifactu_idSolicitud='".$requestId."'
	, verifactu_nifExpedidor='".$issuerIrsId."'
	, verifactu_fechaExpedicion='".$issuedTime."'
	, verifactu_numFactura='".$number."'
	, verifactu_hast='".$hash."'
	, verifactu_url='".$verifactuUrl."'
	, verifactu_queueId='".$queueId."'	
	
	where numero=".$numFactura;
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar las respuestas del verifactu'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	

}



function verFacturaDetalleClayma($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClayma".$anioSeleccionado."] where factura = ".$numFactura. " order by ordenTipo, orden";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaDetallePresupuesto($datosBBDD,$numFactura,$numPresupuesto,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."] where factura = ".$numFactura. " and presupuesto = '".$numPresupuesto."' order by ordenTipo, orden";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if( $resultado === false ) 
	{
     	die ("Error:\n".$resultado."\n".$consulta."\n");		
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaDetallePresupuestoClayma($datosBBDD,$numFactura,$numPresupuesto,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClayma".$anioSeleccionado."] where factura = ".$numFactura. " and presupuesto = '".$numPresupuesto."' order by ordenTipo, orden";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function verFacturaDetallePresupuesto2($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."] where factura = ".$numFactura. " order by ordenTipo, orden";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if( $resultado === false ) 
	{
     	die ("Error:\n".$resultado."\n".$consulta."\n");		
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaDetallePresupuestoClayma2($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClayma".$anioSeleccionado."] where factura = ".$numFactura. " order by ordenTipo, orden";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaDetalleSumatorio($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT concepto, sum(unidades) as unidades, precio, sum(total) as total, descripcion, exentoIVA  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."] where factura = '".$numFactura."'
group by concepto, descripcion, precio,exentoIVA 
order by concepto";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verFacturaDetalleSumatorioClayma($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT concepto, sum(unidades) as unidades, precio, sum(total) as total, descripcion FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClayma".$anioSeleccionado."] where factura = '".$numFactura."'
group by concepto, descripcion, precio
order by concepto";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function verDetalleFacturaTemporalSumatorio($datosBBDD,$usuario)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT concepto, sum(unidades) as unidades, precio, sum(total) as total, descripcion,[exentoIVA] FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal] where idEmpleado = '".$usuario."'
group by concepto, descripcion, precio,[exentoIVA]
order by concepto";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verAbono($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select t1.*, t2.*, t1.numCuentaBanco as cuentaDelBanco
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anioSeleccionado."] as t1
  inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t2.subcliente = t1.cliente where t1.numero = ".$numFactura;	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verAbonoDetalle($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonoDetalles".$anioSeleccionado."] where factura = ".$numFactura. " order by ordenTipo, orden";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verAbonoClayma($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select t1.*, t2.*, t1.numCuentaBanco as cuentaDelBanco
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anioSeleccionado."] as t1
  inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
  on t2.subcliente = t1.cliente where t1.numero = ".$numFactura;	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verAbonoDetalleClayma($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonoDetallesClayma".$anioSeleccionado."] where factura = ".$numFactura. " order by ordenTipo, orden";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


/*function mostrarListadoFacturasPendientes($datosBBDD,$condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[facturas] where formaPagoReal = '' or formaPagoReal is null or formaPagoReal = 'null' order by numero desc";
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[facturas] ".$condicion;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	//echo $consulta;
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	
	return $result;
}*/

/*function mostrarListadoFacturasPendientesClayma($datosBBDD,$condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma] ".$condicion;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	
	return $result;
}*/



function modificarFacturasPendientes($datosBBDD, $numFactura, $formaPago, $anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] set formaPagoReal='".$formaPago."', fechaPago=GETDATE() where numero=".$numFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}

function modificarFacturasPendientesClayma($datosBBDD, $numFactura, $formaPago, $anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] set formaPagoReal='".$formaPago."', fechaPago=GETDATE() where numero=".$numFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}

function modificarRecDiferenciasPendientes($datosBBDD, $numFactura, $formaPago, $anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferencias".$anioSeleccionado."] set formaPagoReal='".$formaPago."', fechaPago=GETDATE() where numero=".$numFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}

function modificarRecDiferenciasPendientesClayma($datosBBDD, $numFactura, $formaPago, $anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecDiferenciasClayma".$anioSeleccionado."] set formaPagoReal='".$formaPago."', fechaPago=GETDATE() where numero=".$numFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}

function modificarRecSustitucionPendientes($datosBBDD, $numFactura, $formaPago, $anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucion".$anioSeleccionado."] set formaPagoReal='".$formaPago."', fechaPago=GETDATE() where numero=".$numFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}

function modificarRecSustitucionPendientesClayma($datosBBDD, $numFactura, $formaPago, $anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasRecSustitucionClayma".$anioSeleccionado."] set formaPagoReal='".$formaPago."', fechaPago=GETDATE() where numero=".$numFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}


function modificarAbonosPendientes($datosBBDD, $abono, $formaPago, $anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anioSeleccionado."] set formaPagoReal='".$formaPago."', fechaPago=GETDATE() where numero=".$abono;
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	

	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}

function modificarAbonosPendientesClayma($datosBBDD, $abono, $formaPago, $anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anioSeleccionado."] set formaPagoReal='".$formaPago."', fechaPago=GETDATE() where numero=".$abono;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}

/*function insertarMovimientoFacturaCibeles($datosBBDD, $numFactura)
{
	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[provisionDeFondo_movimientos] (codigoCliente, presupuesto, fecha, aPagarFacturaCibeles, numFactura,formaPago)

  SELECT t2.codigo,t1.presupuesto,GETDATE(), t1.aPagar, t1.numero, t1.formaPagoReal
from [".$datosBBDD->bbddBBDD."].[dbo].[facturas] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.cliente = t2.subcliente and t2.codigo_saldo = t2.codigo
where numero = ".$numFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	//echo $consulta;
	
	sqlsrv_close($conn_sis);
		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar el movimiento de la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Movimiento de la factura grabada");
	}
	
	return $mensaje;	
	
	
	
}*/

/*function insertarMovimientoAbonoCibeles($datosBBDD, $numAbono, $clayma)
{
	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[provisionDeFondo_movimientos] (codigoCliente, fecha, aPagarFacturaCibeles, abono,formaPago, clayma)

  SELECT t2.codigo,GETDATE(), t1.aPagar, t1.numero, t1.formaPagoReal, ".$clayma."
from [".$datosBBDD->bbddBBDD."].[dbo].[abono] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.cliente = t2.subcliente
where numero = ".$numAbono;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	
	sqlsrv_close($conn_sis);
		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar el movimiento del abono'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Movimiento del abono grabado");
	}
	
	return $mensaje;	
	
	
	
}*/


function grabarFacturaCorreos($datosBBDD, $numeroOficial,$fecha,$codigoCliente,$campana,$neto,$iva,$importe,$anticipo,$aPagar)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] (numeroOficial, fecha, codigoCliente, campana, neto,iva,importe,anticipo,aPagar)

	values ('".$numeroOficial."','".$fecha."','".$codigoCliente."','".$campana."',".$neto.",".$iva.",".$importe.",".$anticipo.",".$aPagar.")";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido grabar la factura de correos a  cibeles'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Grabado");
	}
	
	return $mensaje;
}


function verSiExisteNumeroOficialFacturaCorreos($datosBBDD, $numeroOficial)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] where numeroOficial = '".$numeroOficial."'";
	//echo $consulta; 
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
	
}



function borrarNumeroOficialFacturaCorreos($datosBBDD,$numeroOficial)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] where numeroOficial = '".$numeroOficial."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar la factura de correos'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}

function mostrarFacturasCorreosTodos($datosBBDD,$condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT t1.*, t2.nombre_franqueo, t2.nombre_empresa, t2.codigo_saldo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.codigoCliente = t2.codigo ".$condicion;
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
		
	return $result;		
}

function mostrarFacturaCorreosId($datosBBDD,$id)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] where id='".$id."'";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;		
}

function eliminarFacturaCorreos($datosBBDD,$idRegistro)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] where id=".$idRegistro;	
 	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
		
	sqlsrv_close($conn_sis);
	
	//return $resultado;
}

function mostrarFacturasCorreosPendientes($datosBBDD,$condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT t1.*, t2.nombre_franqueo, t2.codigo_saldo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.codigoCliente = t2.codigo

   ".$condicion;
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;
}


function modificarFacturasCorreosPendientes($datosBBDD,$factura,$formaPago)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] set formaPago='".$formaPago."', fechaPago=GETDATE() where numeroOficial='".$factura."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	

	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar la forma de pago'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Factura de correos modificada";
	}
	
	return $mensaje;	
}


function verLibroContabilidad($datosBBDD,$fechaIncio, $fechaFin)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaIncio1 = date("d-m-Y", strtotime($fechaIncio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));	
	
	$consulta = "
SELECT t1.numero,'' as serie, t2.codigo, t1.cliente, t1.fecha, t1.aPagar, t1.precioNeto, t1.iva, t1.precioTotal, '' as abono
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t2.subcliente = t1.cliente
  where t1.fecha>='".$fechaIncio1."' and t1.fecha<='".$fechaFin1."'
  order by t1.numero
  ";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	$consulta = "
SELECT t1.numero,'-AB' as serie, t2.codigo, t1.cliente, t1.fecha, t1.aPagar, t1.precioNeto, t1.iva, t1.precioTotal,  CONCAT(t1.factura,'/', (t1.anioFactura-2000)) as abono
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t2.subcliente = t1.cliente
  where t1.fecha>='".$fechaIncio1."' and t1.fecha<='".$fechaFin1."'
  order by t1.numero
  ";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
		
	return $result;	
}

function verFacturasDomiciliadasSinPagar($datosBBDD,$fechaIncio, $fechaFin)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaIncio1 = date("d-m-Y", strtotime($fechaIncio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
	
	
	$consulta = "select tabla.*, 'nombreFranqueoReal' = CASE tabla.franqueoCliente WHEN '' THEN tabla.nombre_empresa ELSE tabla.franqueoCliente END, tabla.fecha from 
(
SELECT  case when numeroFacturaCompleto is NULL or numeroFacturaCompleto = NULL or numeroFacturaCompleto = '' then CONCAT(t1.numero, '/', t1.anioFactura-2000, ' MA') else numeroFacturaCompleto end as 'factura', t2.codigo_saldo, t2.nombre_empresa, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe', t1.provision as 'anticipo', t1.aPagar, '' as codigoSubcliente, '' as franqueoCliente, t2.vencimiento, t1.fecha, t1.presupuesto 
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
 
    where t2.domiciliada = 1 and (formaPagoReal is null or formaPagoReal='') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."'

union 

	SELECT numeroFacturaCompleto  as 'factura', t2.codigo_saldo, t2.nombre_empresa, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe', t1.provision as 'anticipo'
	, t1.aPagar, '' as codigoSubcliente	, '' as franqueoCliente, t2.vencimiento, t1.fecha, t1.origenFactura as presupuesto
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[facRecTodosLosAnios] as t1 
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2 
	on t1.cliente = t2.nombre_empresa 
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3 
	on t2.idFormaPago = t3.id 
	where t2.domiciliada = 1 and (formaPagoReal is null or formaPagoReal='') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."' 
 
 union
 
 SELECT CONCAT(t1.numero, '/', t1.anioAbono-2000, ' AB') as 'factura', t2.codigo_saldo, t2.nombre_empresa, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe', t1.provision as 'anticipo', t1.aPagar, '' as codigoSubcliente, '' as franqueoCliente, t2.vencimiento, t1.fecha, '' as presupuesto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
 
    where t2.domiciliada = 1 and (formaPagoReal is null or formaPagoReal='') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."'
 

 union
  

  SELECT t1.numeroOficial as 'factura', t2.codigo_saldo, t2.nombre_empresa, t3.concepto, t1.neto, t1.iva, t1.importe,t1.anticipo, t1.aPagar, t1.codigoCliente, t4.nombre_franqueo as franqueoCliente, t2.vencimiento, t1.fecha, '' as presupuesto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.codigoCliente = t2.codigo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t4
  on t2.codigo_saldo = t4.codigo_saldo and t4.codigo_saldo = t4.codigo


  where  t2.domiciliada = 1 and (t1.formaPago is null or t1.formaPago='') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."'
  ) as tabla
    order by nombre_empresa, tabla.fecha, factura";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	
}

function verFacturasDomiciliadasSinPagarPorCliente($datosBBDD,$fechaIncio, $fechaFin, $idCliente)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaIncio1 = date("d-m-Y", strtotime($fechaIncio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));	
	
	/*$consulta = "
SELECT t1.*, t2.codigo_saldo, t3.concepto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
  where t2.domiciliada = 1 and t1.abono=0 and (formaPagoReal is null or formaPagoReal='') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."'
  order by cliente, numero
  ";*/
	
	$consulta = "select tabla.*, 'nombreFranqueoReal' = CASE tabla.franqueoCliente WHEN '' THEN tabla.nombre_empresa ELSE tabla.franqueoCliente END, tabla.fecha from 
(
SELECT CAST(t1.numero as varchar(50))+' (Cib)' as 'factura', t2.codigo_saldo, t2.nombre_empresa, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe', t1.provision as 'anticipo', t1.aPagar, '' as codigoSubcliente, '' as franqueoCliente, t2.vencimiento, t1.fecha, t1.presupuesto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
 
    where t2.domiciliada = 1 and (formaPagoReal is null or formaPagoReal='') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."' and t2.codigo_saldo = ".$idCliente."
 
union 

SELECT numeroFacturaCompleto as 'factura', t2.codigo_saldo, t2.nombre_empresa, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe', t1.provision as 'anticipo', t1.aPagar, '' as codigoSubcliente, '' as franqueoCliente, t2.vencimiento, t1.fecha, t1.origenFactura as presupuesto 
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facRecTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
 
    where t2.domiciliada = 1 and (formaPagoReal is null or formaPagoReal='') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."' and t2.codigo_saldo = ".$idCliente."
 

 union
 
 SELECT CAST(t1.numero as varchar(50))+' (Cib - AB)' as 'abono', t2.codigo_saldo, t2.nombre_empresa, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe', t1.provision as 'anticipo', t1.aPagar, '' as codigoSubcliente, '' as franqueoCliente, t2.vencimiento, t1.fecha, '' as presupuesto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
 
    where t2.domiciliada = 1 and (formaPagoReal is null or formaPagoReal='') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."' and t2.codigo_saldo = ".$idCliente."
 
 
	/* marian dice que no debe aparecer clayma. A parte el codigo esta mal porque el idCliente no puede ser el mismo para clayma
 union 
 
 SELECT CAST(t1.numero as varchar(50))+' (Cla)' as 'factura', t2.codigo_saldo, t2.nombre_empresa, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe', t1.provision as 'anticipo', t1.aPagar, '' as codigoSubcliente, '' as franqueoCliente, t2.vencimiento, t1.fecha, t1.presupuesto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
  on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
 
    where t2.domiciliada = 1 and (formaPagoReal is null or formaPagoReal='') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."' and t2.codigo_saldo = ".$idCliente."
 
 union
 
 SELECT CAST(t1.numero as varchar(50))+' (Cla - AB)' as 'abono', t2.codigo_saldo, t2.nombre_empresa, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe', t1.provision as 'anticipo', t1.aPagar, '' as codigoSubcliente, '' as franqueoCliente, t2.vencimiento, t1.fecha, '' as presupuesto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosClaymaTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
  on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
 
    where t2.domiciliada = 1 and (formaPagoReal is null or formaPagoReal='') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."' and t2.codigo_saldo = ".$idCliente."
 */
 
 union 
  

  SELECT t1.numeroOficial as 'factura', t2.codigo_saldo, t2.nombre_empresa, t3.concepto, t1.neto, t1.iva, t1.importe,t1.anticipo, t1.aPagar, t1.codigoCliente, t4.nombre_franqueo as franqueoCliente, t2.vencimiento, t1.fecha, '' as presupuesto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.codigoCliente = t2.codigo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t4
  on t2.codigo_saldo = t4.codigo_saldo and t4.codigo_saldo = t4.codigo


  where  t2.domiciliada = 1 and (t1.formaPago is null or t1.formaPago='') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."'  and t2.codigo_saldo = ".$idCliente."
  ) as tabla
    order by nombre_empresa, tabla.fecha, factura";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	
}

function verFacturasAcompensarSinPagar($datosBBDD,$fechaIncio, $fechaFin)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaIncio1 = date("d-m-Y", strtotime($fechaIncio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));	
	
	/*
	$consulta = "
select tabla.*, 'nombreFranqueoReal' = CASE tabla.franqueoCliente WHEN '' THEN tabla.nombre_franqueo ELSE tabla.franqueoCliente END from 
(
SELECT  case when numeroFacturaCompleto is NULL or numeroFacturaCompleto = NULL or numeroFacturaCompleto = '' then CONCAT(t1.numero, '/', t1.anioFactura-2000, ' MA') else numeroFacturaCompleto end as 'factura', t2.codigo_saldo, t2.nombre_franqueo, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe', t1.provision as 'anticipo', t1.aPagar, '' as codigoSubcliente, '' as franqueoCliente, t2.[importePF], t1.fecha
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
  where (t2.idFormaPago = 6 or t2.idFormaPago=13 ) and  (t1.abono=0 or t1.abono is null) and (t1.formaPagoReal is null or t1.formaPagoReal = '') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."'
 
union 

SELECT  numeroFacturaCompleto as 'factura', t2.codigo_saldo, t2.nombre_franqueo, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe', t1.provision as 'anticipo', t1.aPagar, '' as codigoSubcliente, '' as franqueoCliente, t2.[importePF], t1.fecha
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facRecTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
  where (t2.idFormaPago = 6 or t2.idFormaPago=13 ) and  (t1.abono=0 or t1.abono is null) and (t1.formaPagoReal is null or t1.formaPagoReal = '') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."'


 //(t2.idFormaPago = 6 or t2.idFormaPago=13 ) and 
  union

  SELECT t1.numeroOficial as 'factura', t2.codigo_saldo, t2.nombre_franqueo, t3.concepto, t1.neto, t1.iva, t1.importe,t1.anticipo, t1.aPagar, t1.codigoCliente, t4.nombre_franqueo as franqueoCliente, t2.[importePF], t1.fecha
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.codigoCliente = t2.codigo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t4
  on t2.codigo_saldo = t4.codigo_saldo and t4.codigo_saldo = t4.codigo


  where (t2.idFormaPago = 6 or t2.idFormaPago=13 ) and  (t1.formaPago is null or t1.formaPago = '') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."'
  ) as tabla
  order by nombreFranqueoReal, factura
  ";
  */
  $consulta="select tabla.*, 'nombreFranqueoReal' = CASE tabla.franqueoCliente WHEN '' THEN tabla.nombre_franqueo ELSE tabla.franqueoCliente END 
from ( 
	SELECT case when numeroFacturaCompleto is NULL or numeroFacturaCompleto = NULL or numeroFacturaCompleto = '' then CONCAT(t1.numero, '/', t1.anioFactura-2000, ' MA') else numeroFacturaCompleto end as 'factura'
	, t2.codigo_saldo, t2.nombre_franqueo, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe', t1.provision as 'anticipo', t1.aPagar, '' as codigoSubcliente
	, '' as franqueoCliente, t2.[importePF], t1.fecha FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t1 
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2 
	on t1.cliente = t2.nombre_empresa 
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3 
	on t2.idFormaPago = t3.id 
	where (t2.idFormaPago = 6 or t2.idFormaPago=13 ) and (t1.formaPagoReal is null or t1.formaPagoReal = '') 
	and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."' 

	union 

	SELECT numeroFacturaCompleto as 'factura'
	, t2.codigo_saldo, t2.nombre_franqueo, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe', t1.provision as 'anticipo', t1.aPagar, '' as codigoSubcliente
	, '' as franqueoCliente, t2.[importePF], t1.fecha FROM [".$datosBBDD->bbddBBDD."].[dbo].[facRecTodosLosAnios] as t1 
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2 
	on t1.cliente = t2.nombre_empresa 
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3 
	on t2.idFormaPago = t3.id 
	where (t2.idFormaPago = 6 or t2.idFormaPago=13 ) and (t1.formaPagoReal is null or t1.formaPagoReal = '') 
	and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."' 
	union

	SELECT CONCAT(t1.numero, '/', t1.anioAbono-2000, ' AB') as 'factura'
	, t2.codigo_saldo, t2.nombre_franqueo, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe'
	, t1.provision as 'anticipo', t1.aPagar, '' as codigoSubcliente
	, '' as franqueoCliente, t2.[importePF], t1.fecha FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosTodosLosAnios] as t1 
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2 
	on t1.cliente = t2.nombre_empresa 
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3 
	on t2.idFormaPago = t3.id 
	where (t2.idFormaPago = 6 or t2.idFormaPago=13 ) and (t1.formaPagoReal is null or t1.formaPagoReal = '') 
	and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."' 	
	
	
	union 
	
	SELECT t1.numeroOficial as 'factura', t2.codigo_saldo, t2.nombre_franqueo, t3.concepto, t1.neto, t1.iva, t1.importe,t1.anticipo, t1.aPagar, t1.codigoCliente
	, t4.nombre_franqueo as franqueoCliente, t2.[importePF], t1.fecha 
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1 
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2 
	on t1.codigoCliente = t2.codigo 
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3 
	on t2.idFormaPago = t3.id 
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t4 
	on t2.codigo_saldo = t4.codigo_saldo and t4.codigo_saldo = t4.codigo 
	where (t2.idFormaPago = 6 or t2.idFormaPago=13 ) and (t1.formaPago is null or t1.formaPago = '') and t1.fecha >= '".$fechaIncio1."' and t1.fecha <='".$fechaFin1."' ) as tabla 

order by nombreFranqueoReal, factura";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
		
	return $result;	
}

	
/*function mostrarFacturaPorFechas($datosBBDD,$fechaIncio, $fechaFin)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT t1.*, t2.codigo, t2.nif_subcliente
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.subcliente
  where t1.fecha>='".$fechaIncio."' and t1.fecha<='".$fechaFin."'";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
		
	return $result;	
}*/

function mostrarFacturaYAbonosPorFechas($datosBBDD,$fechaIncio, $fechaFin) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("Y-m-d", strtotime($fechaIncio));
	$fechaFin1 = date("Y-m-d", strtotime($fechaFin));	
	
	$consulta = "SELECT t1.[numero]
      ,t1.[cliente]
      ,t1.[descripcion]
      ,t1.[presupuesto]
      ,t1.[origenFactura]
      ,t1.[fecha]
      ,t1.[inicialComercial]
      ,t1.[precioNeto]
      ,t1.[iva]
      ,t1.[precioTotal]
      ,t1.[provision]
      ,t1.[aPagar]
      ,t1.[cantidad]
      ,t1.[pedido]
      ,t1.[formaPago]
      ,t1.[detallada]
      ,t1.[formaPagoReal]
      ,t1.[fechaPago]
      ,t1.[cd]
      ,t1.[fechaInicio]
      ,t1.[fechaFin]
      ,t1.[importeFranqueo]
      ,t1.[numCuentaBanco]
      ,t1.[abono]
      ,t1.[combinadoSumatorio]
      ,t1.[observaciones]
      ,t1.[observacionesInternas]
      ,t1.[anioFactura] as anioRegistro
	  , t2.codigo
	  , t2.nif_subcliente
	  ,t1.numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.subcliente
  where t1.fecha>='".$fechaInicio1."' and t1.fecha<='".$fechaFin1."' and codigo=codigo_saldo



 union

  SELECT t1.[numero]
      ,t1.[cliente]
      ,t1.[descripcion]
      ,t1.[factura] as presupuesto
	  ,'abono'
      ,t1.[fecha]
      ,t1.[inicialComercial]
      ,t1.[precioNeto]
      ,t1.[iva]
      ,t1.[precioTotal]
      ,t1.[provision]
      ,t1.[aPagar]
      ,t1.[cantidad]
      ,t1.[pedido]
      ,t1.[formaPago]
      ,t1.[detallada]
      ,t1.[formaPagoReal]
      ,t1.[fechaPago]
      ,t1.[cd]
      ,t1.[fechaInicio]
      ,t1.[fechaFin]
      ,t1.[importeFranqueo]
      ,t1.[numCuentaBanco]
       ,'' ,'' ,'','', t1.anioAbono as anioRegistro ,t2.codigo , t2.nif_subcliente  
	   , '' as numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.subcliente
  where t1.fecha>='".$fechaInicio1."' and t1.fecha<='".$fechaFin1."' and codigo=codigo_saldo

   union 
  
  SELECT t1.[numero]
      ,t1.[cliente]
      ,t1.[descripcion]
      , '' as presupuesto
      ,t1.[origenFactura]
      ,t1.[fecha]
      , '' as inicialComerical
      ,t1.[precioNeto]
      ,t1.[iva]
      ,t1.[precioTotal]
      ,t1.[provision]
      ,t1.[aPagar]
      ,t1.[cantidad]
      ,t1.[pedido]
      ,t1.[formaPago]
      ,t1.[detallada]
      ,t1.[formaPagoReal]
      ,t1.[fechaPago]
      ,t1.[cd]
      ,t1.[fechaInicio]
      ,t1.[fechaFin]
      ,t1.[importeFranqueo]
      ,t1.[numCuentaBanco]
      , '' as abono
      ,t1.[combinadoSumatorio]
      ,t1.[observaciones]
      ,t1.[observacionesInternas]
      ,t1.[anioFacRec] as anioRegistro
	  , t2.codigo
	  , t2.nif_subcliente
	  ,t1.numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facRecTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.subcliente
  where t1.fecha>='".$fechaInicio1."' and t1.fecha<='".$fechaFin1."' and codigo=codigo_saldo

    order by numero";
	
 	
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	
}

function mostrarFacturaYAbonosPorFechasClayma($datosBBDD,$fechaIncio, $fechaFin) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaIncio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));	
	
	/*$consulta = "SELECT t1.*, t2.codigo, t2.nif_subcliente
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
  on t1.cliente = t2.subcliente
  where t1.fecha>='".$fechaInicio1."' and t1.fecha<='".$fechaFin1."' and codigo=codigo_saldo



 union

  SELECT t1.[numero]
      ,t1.[cliente]
      ,t1.[descripcion]
      ,t1.[factura] as presupuesto
	  ,'abono'
      ,t1.[fecha]
      ,t1.[inicialComercial]
      ,t1.[precioNeto]
      ,t1.[iva]
      ,t1.[precioTotal]
      ,t1.[provision]
      ,t1.[aPagar]
      ,t1.[cantidad]
      ,t1.[pedido]
      ,t1.[formaPago]
      ,t1.[detallada]
      ,t1.[formaPagoReal]
      ,t1.[fechaPago]
      ,t1.[cd]
      ,t1.[fechaInicio]
      ,t1.[fechaFin]
      ,t1.[importeFranqueo]
      ,t1.[numCuentaBanco]
      ,'' ,'' ,'','' ,t2.codigo ,''
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
  on t1.cliente = t2.subcliente
  where t1.fecha>='".$fechaInicio1."' and t1.fecha<='".$fechaFin1."' and codigo=codigo_saldo

    order by numero";*/
	
	
	$consulta = "SELECT t1.[numero]
      ,t1.[cliente]
      ,t1.[descripcion]
      ,t1.[presupuesto]
      ,t1.[origenFactura]
      ,t1.[fecha]
      ,t1.[inicialComercial]
      ,t1.[precioNeto]
      ,t1.[iva]
      ,t1.[precioTotal]
      ,t1.[provision]
      ,t1.[aPagar]
      ,t1.[cantidad]
      ,t1.[pedido]
      ,t1.[formaPago]
      ,t1.[detallada]
      ,t1.[formaPagoReal]
      ,t1.[fechaPago]
      ,t1.[cd]
      ,t1.[fechaInicio]
      ,t1.[fechaFin]
      ,t1.[importeFranqueo]
      ,t1.[numCuentaBanco]
      ,t1.[abono]
      ,t1.[combinadoSumatorio]
      ,t1.[observaciones]
      ,t1.[observacionesInternas]
      ,t1.[anioFactura] as anioRegistro
	  , t2.codigo
	  , t2.nif_subcliente
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
  on t1.cliente = t2.subcliente
  where t1.fecha>='".$fechaInicio1."' and t1.fecha<='".$fechaFin1."' and codigo=codigo_saldo



 union

  SELECT t1.[numero]
      ,t1.[cliente]
      ,t1.[descripcion]
      ,t1.[factura] as presupuesto
	  ,'abono'
      ,t1.[fecha]
      ,t1.[inicialComercial]
      ,t1.[precioNeto]
      ,t1.[iva]
      ,t1.[precioTotal]
      ,t1.[provision]
      ,t1.[aPagar]
      ,t1.[cantidad]
      ,t1.[pedido]
      ,t1.[formaPago]
      ,t1.[detallada]
      ,t1.[formaPagoReal]
      ,t1.[fechaPago]
      ,t1.[cd]
      ,t1.[fechaInicio]
      ,t1.[fechaFin]
      ,t1.[importeFranqueo]
      ,t1.[numCuentaBanco]
      ,'' ,'' ,'','', t1.anioAbono as anioRegistro ,t2.codigo ,''
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosClaymaTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
  on t1.cliente = t2.subcliente
  where t1.fecha>='".$fechaInicio1."' and t1.fecha<='".$fechaFin1."' and codigo=codigo_saldo

    order by numero";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	
}


function cargarDiasApagar($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[diasApagar]";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	
}



function cargarPeriodosFacturacion($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesFacPeriodos]";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	
}


function cargarFacturasProvisionFondo($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesFacPF]";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	
}




function verExistenciaCliente($datosBBDD,$campo,$valor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientes] where ".$campo." ='".$valor."'";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	
}

function verExistenciaClienteClayma($datosBBDD,$campo,$valor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] where ".$campo." ='".$valor."'";	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);	
		
	return $result;	
}

function crearClienteNuevo($datosBBDD,$codigoSaldo,$subCliente,$nombreEmpresa,$nif,$nifSubCliente,$direccion,$localidad,$provincia,$cp,$nombreFranqueo,$comercial,$diasApagar,$formaPago,$EmailFactura,$cuotaRecogida,$periodoFacturacion,$prodNoBon,$otrosConceptos,$importeFijoOtrosConceptos,$provisionFondos,$cobroUnitarioEnvio,$envAtt,$envNombre,$envDireccion,$envCp,$envPoblacion,$envProvincia,$envPais,$numCuenta,$correoDiario,$activo,$pfFijaImporte,$domiciliado, $nuestraCuenta,$sinIva,$retener,$pedidoCliente,$vencimiento,$prefactura,$noAplicarPF,$retencion,$pais,$codigoPais)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$valorCodigoSaldo="";
	if ($codigoSaldo==0||$codigoSaldo=="0")
	{
		$valorCodigoSaldo="max(codigo)+1";
	}
	else
	{
		$valorCodigoSaldo=$codigoSaldo;
	}	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[clientes] 
	(codigo, codigo_saldo, nombre_empresa,nombre_franqueo, subcliente, nif,nif_subcliente, 
direccion, localidad, provincia, codigo_postal, idComercial,idDiasDePago, idFormaPago, email,
fac_cuotaRecogida,fac_idPeriodo,fac_porCientoNoBonificable,fac_otrosConceptosFijos,fac_importeFijoOtrosConcepto,fac_idProvisionFondos, 
fac_cobroUnitarioEnvio, [envio_att],[envio_nombre],[envio_domicilio],[envio_cp],[envio_poblacion],[envio_provincia],[envio_pais],
numCuentaBanco,correoDiario,activo,fac_pfFijaImporte, domiciliada, nuestraCuenta, sinIva, retener, pedidoCliente, vencimiento, 
prefactura,noAplicarPF, retencion, pais, codigoPais) 
select max(codigo)+1, ".$valorCodigoSaldo." , '".$nombreEmpresa."', '".$nombreFranqueo."', '".$subCliente."', '".$nif."',
 '".$nifSubCliente."', '".$direccion."', '".$localidad."', '".$provincia."', '".$cp."', ".$comercial.", ".$diasApagar.",
  ".$formaPago.", '".$EmailFactura."', ".$cuotaRecogida.", ".$periodoFacturacion.", ".$prodNoBon.", '".$otrosConceptos."',
   ".$importeFijoOtrosConceptos.", ".$provisionFondos.", ".$cobroUnitarioEnvio.", '".$envAtt."', '".$envNombre."', '".$envDireccion."',
    '".$envCp."', '".$envPoblacion."', '".$envProvincia."', '".$envPais."', '".$numCuenta."', ".$correoDiario.", ".$activo.",
	".$pfFijaImporte.", ".$domiciliado.", '".$nuestraCuenta."', ".$sinIva.", ".$retener.", '".$pedidoCliente."', '".$vencimiento."',
	 ".$prefactura.", ".$noAplicarPF.", ".$retencion." , '".$pais."', '".$codigoPais."' 
	 FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientes] ";
 	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	
	//sqlsrv_close($conn_sis);
		

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar el cliente'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$consulta = "select max(codigo) as 'codigo' FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientes]";
		$resultado2 = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
		if( $resultado2 === false) 
		{		
			die(print_r( sqlsrv_errors(), true) );
		}
		$result = array();

		while($valor = sqlsrv_fetch_array($resultado2, SQLSRV_FETCH_ASSOC))
		{
			$result[] = $valor;
		}

		sqlsrv_close($conn_sis);


		return $result;	
	}
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	

	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false) 
	{
     	$mensaje = "Error: no se ha podido insertar el cliente'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Factura de correos modificada";
	}
	
	return $mensaje;
}


function crearClienteNuevoClayma($datosBBDD,$codigoSaldo,$subCliente,$nombreEmpresa,$nif,$nifSubCliente,$direccion,$localidad,$provincia,$cp,$nombreFranqueo,$comercial,$diasApagar,$formaPago,$EmailFactura,$cuotaRecogida,$periodoFacturacion,$prodNoBon,$otrosConceptos,$importeFijoOtrosConceptos,$provisionFondos,$cobroUnitarioEnvio,$envAtt,$envNombre,$envDireccion,$envCp,$envPoblacion,$envProvincia,$envPais,$numCuenta,$correoDiario,$activo,$pfFijaImporte,$domiciliado, $nuestraCuenta,$sinIva,$retener,$pedidoCliente,$vencimiento,$prefactura,$noAplicarPF,$retencion,$pais,$codigoPais)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$valorCodigoSaldo="";
	if ($codigoSaldo==0||$codigoSaldo=="0")
	{
		$valorCodigoSaldo="max(codigo)+1";
	}
	else
	{
		$valorCodigoSaldo=$codigoSaldo;
	}	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] 
	(codigo, codigo_saldo, nombre_empresa,nombre_franqueo, subcliente, nif,nif_subcliente, 
direccion, localidad, provincia, codigo_postal, idComercial,idDiasDePago, idFormaPago, email,
fac_cuotaRecogida,fac_idPeriodo,fac_porCientoNoBonificable,fac_otrosConceptosFijos,fac_importeFijoOtrosConcepto,fac_idProvisionFondos, 
fac_cobroUnitarioEnvio, [envio_att],[envio_nombre],[envio_domicilio],[envio_cp],[envio_poblacion],[envio_provincia],[envio_pais],
numCuentaBanco,correoDiario,activo,fac_pfFijaImporte, domiciliada, nuestraCuenta, sinIva, retener, pedidoCliente, vencimiento, 
prefactura,noAplicarPF, retencion, pais, codigoPais) 
select max(codigo)+1, ".$valorCodigoSaldo." , '".$nombreEmpresa."', '".$nombreFranqueo."', '".$subCliente."', '".$nif."',
 '".$nifSubCliente."', '".$direccion."', '".$localidad."', '".$provincia."', '".$cp."', ".$comercial.", ".$diasApagar.",
  ".$formaPago.", '".$EmailFactura."', ".$cuotaRecogida.", ".$periodoFacturacion.", ".$prodNoBon.", '".$otrosConceptos."',
   ".$importeFijoOtrosConceptos.", ".$provisionFondos.", ".$cobroUnitarioEnvio.", '".$envAtt."', '".$envNombre."', 
   '".$envDireccion."', '".$envCp."', '".$envPoblacion."', '".$envProvincia."', '".$envPais."', '".$numCuenta."',
    ".$correoDiario.", ".$activo.",".$pfFijaImporte.", ".$domiciliado.", '".$nuestraCuenta."', ".$sinIva.", ".$retener.",
	 '".$pedidoCliente."', '".$vencimiento."',".$prefactura.", ".$noAplicarPF.", ".$retencion." , '".$pais."', '".$codigoPais."'  
	 FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] ";	
 	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
		
	//sqlsrv_close($conn_sis);
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar el cliente'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$consulta = "select max(codigo) as 'codigo' FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma]";
		$resultado2 = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
		if( $resultado2 === false) 
		{		
			die(print_r( sqlsrv_errors(), true) );
		}
		$result = array();

		while($valor = sqlsrv_fetch_array($resultado2, SQLSRV_FETCH_ASSOC))
		{
			$result[] = $valor;
		}

		sqlsrv_close($conn_sis);


		return $result;	
	}
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	

	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false) 
	{
     	$mensaje = "Error: no se ha podido insertar el cliente'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Factura de correos modificada";
	}
	
	return $mensaje;
}


function cargarListadoNombreFranqueo($datosBBDD,$condicion)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
		
	$consulta="select codigo,nombre_franqueo from [".$datosBBDD->bbddBBDD."].[dbo].[clientes] ".$condicion;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);		
	
	return $result;	
}


function cargarCertificadoProductos($datosBBDD)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="select id,producto from [".$datosBBDD->bbddBBDD."].[dbo].[certificadoProductos] order by producto";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function guardarCertificado($datosBBDD,$idCliente,$unidad,$idProducto,$fecha)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1 = date("d-m-Y", strtotime($fecha));
	
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[certificadoEspeciales] where idCliente = ".$idCliente." and idProducto = ".$idProducto;
				
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	$row_count = sqlsrv_num_rows( $resultado );
	
	$importeUnitario=0;
	
	if ($row_count>0)//espciales
	{
		$valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
		$importeUnitario = $valor["importeUnitario"];
	}
	else//no especiales
	{
		$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[certificadoProductos] where id = ".$idProducto;
		$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
		$valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
		$importeUnitario = $valor["importeUnitario"];
		
	}

	
	//sqlsrv_close($conn_sis);		
	
	//return $result;
	
	
	sqlsrv_free_stmt($resultado);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[CertificadosGrabados] (idCliente, unidades, idProducto,importeUnitario, fecha) values (".$idCliente.",".$unidad.",".$idProducto.",".$importeUnitario.", '".$fecha1."')";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar el certificado'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Certificado Guardado");
	}
	
	return $mensaje;	
}


function cargarListadoEmpleado($datosBBDD)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="select id, nombre, apellidos from [".$datosBBDD->bbddBBDD."].[dbo].[empleados] order by nombre, apellidos";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);		
	
	return $result;	
}


function cargarListadoTipoAlabaran($datosBBDD)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="select * from [".$datosBBDD->bbddBBDD."].[dbo].[albaranTipo] order by tipo";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);		
	
	return $result;	
}


function guardarAlbaran($datosBBDD,$idEmpleado,$idTipoAlbaran,$idCliente,$fecha,$cantidad,$importe,$descripcion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$newDateFin = date("d-m-Y", strtotime($fecha));
	$numeroDia =  date("d", strtotime($fecha));
	$numeroMes =  date("m", strtotime($fecha));
	$numeroAnio=  date("y", strtotime($fecha));
	
	$fechaSinHora = date("d-m-Y", strtotime($fecha));
	$fechaConHora = date("d-m-Y h:i:s", strtotime($fecha));

	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[albaranes] (idEmpleado, idTipo, idCliente, fecha, cantidad, importe, descripcion,numeroReciboUnDia, numeroRecibo) 

	SELECT ".$idEmpleado.", ".$idTipoAlbaran.", ".$idCliente.",'".$fechaConHora."', ".$cantidad.", ".$importe.", '".$descripcion."', isnull(max(numeroReciboUnDia),0)+1, CONCAT('".$numeroMes.$numeroDia."',isnull(max(numeroReciboUnDia),0)+1,'/".$numeroAnio."')
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[albaranes]
  where fecha >= '".$fechaSinHora."' and fecha< '".date("d-m-Y",strtotime($newDateFin."+ 1 days"))."'";	
	
		
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);	
	
	$mensaje="";
	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar el Albarán'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Albaran Creado");
	}
	
	return $mensaje;	
}


function cargarAlbaranes($datosBBDD,$condicion="")
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$condiciones = $condicion;
	
	$consulta = "select t1.*, t2.nombre_franqueo, t3.nombre + ' ' + t3.apellidos as nombre, t4.tipo
from [".$datosBBDD->bbddBBDD."].[dbo].[albaranes] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.idCliente = t2.codigo
inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t3 
on t3.id = t1.idEmpleado
inner join [".$datosBBDD->bbddBBDD."].[dbo].[albaranTipo] as t4
on t4.id=t1.idTipo ".$condiciones;
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function cargarlistadoTarifasProductos($datosBBDD)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
		
	$consulta = "select id, producto from [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] order by producto";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function rellenarCamposGrabacionFranqueo($datosBBDD, $producto)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT titulo FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] where idProductoPadre = '".$producto."' order by orden";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;
	
}

function rellenarCamposGrabacionFranqueo2($datosBBDD, $producto,$anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select  t1.gramos, t2.clave, t1.tipos, t1.precioNeto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t1 
  INNER JOIN [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t2 
  on t1.idTarifasProducto = t2.id 
  where t2.idProductoPadre='".$producto."' 
  order by  t1.gramos, t2.orden
";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function verImporteProductoFranqueo ($datosBBDD, $tipo, $cantidad, $anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*, t2.titulo, (precioNeto + iva) * ".$cantidad." as importe, precioNeto * ".$cantidad." as importeSinIva 
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t1
   inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t2
   on t1.idTarifasProducto = t2.id  
 
  where tipos='".$tipo."'
";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function verImporteProductoFranqueoAcuseRecibo ($datosBBDD, $tipo, $cantidad,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*,  (precioNeto + iva) * ".$cantidad." as importe, precioNeto * ".$cantidad." as importeSinIva 
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t1
   where t1.id= (SELECT idAcuseRecibo FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] where tipos='".$tipo."')

";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function verImporteProductoFranqueoPEE ($datosBBDD, $tipo, $cantidad,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*,  (precioNeto + iva) * ".$cantidad." as importe, precioNeto * ".$cantidad." as importeSinIva 
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t1
   where t1.id= (SELECT idPEE FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] where tipos='".$tipo."')

";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function mostraTarifaPorTipo ($datosBBDD,$idTipo,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	$consulta = "SELECT precioNeto, iva, precioNeto + iva as total 
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."]

  where id = ".$idTipo;
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function verOrdenTarifasProducto ($datosBBDD,$idProducto, $titulo)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta = "SELECT orden 
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos]
  where idProductoPadre=".$idProducto." and titulo = '".$titulo."'";
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function guardarFacturaEspecial($datosBBDD,$idCliente,$ordenTrabajo,$fecha,$concepto,$unidades,$importe)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspeciales] (idCliente, ordenTrabajo, fechaFacturacion,concepto, unidades, precioUnitario) values (".$idCliente.",'".$ordenTrabajo."','".$fecha."','".$concepto."',".$unidades.",".$importe.")";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar la factura especial'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Factura Especial Guardada");
	}
	
	return $mensaje;	
}


function cargarCertificados($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaActual = date('d-m-Y');
	$fechaAnterior = date("d-m-Y",strtotime($fechaActual."- 2 month")); 
	$mes = date ("m",strtotime($fechaAnterior));
	$anio = date ("Y",strtotime($fechaAnterior));
	$laFecha = "01-".$mes."-".$anio;	
	
	$consulta = "SELECT t1.*, t2.producto, t3.nombre_franqueo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[CertificadosGrabados] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[certificadoProductos] as t2
  on t1.idProducto = t2.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idcliente = t3.codigo

  where t1.fecha >='".$laFecha."'

  order by t1.id desc";
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}



function eliminarRegistroCertificado($datosBBDD, $id)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[CertificadosGrabados] where id=".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el certificado'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;

}



function cargarFacturasEspeciales($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*, t2.nombre_franqueo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspeciales] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.idCliente = t2.codigo
   order by t1.id desc";	
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function eliminarRegistroEspecial($datosBBDD, $id)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspeciales] where id=".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el registro'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;
	
}


function modificarRegistroEspecial($datosBBDD, $id,$concepto,$unidades,$precioUnitario)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);		
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspeciales] set 
	concepto='".$concepto."' 
	,unidades=".$unidades."
	,precioUnitario=".$precioUnitario."
	where id=".$id;		
		
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el registro'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Registro Actualizado";
	}
	
	return $mensaje;
	
}

function insertarCertYrut($datosBBDD,$fechaFac,$primerDia,$ultimoDia)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspecialesTemporal] (idCliente, ordenTrabajo, fechaFacturacion, concepto, unidades, 
	precioUnitario,fechaInicio, fechaFin) 
	SELECT idCliente, 'CD','".$fechaFac."', 'Gestion Certificados y Paquetes' as descripcion, '1' as unidades, sum(unidades * importeUnitario) as precio, '".$primerDia."' as fechaInicio, '".$ultimoDia."' as fechaFin
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[CertificadosGrabados]
  where fecha>= '".$primerDia."' and fecha <= '".$ultimoDia."'

  group by idCliente
	
	";
	//echo "\n".$consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido pasar los certificados'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$conn_sis2=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		/*$consulta2="insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspecialesTemporal] (idCliente, ordenTrabajo, fechaFacturacion, concepto, unidades, 
	precioUnitario,fechaInicio, fechaFin) 
	SELECT idCliente, 'CD','".$fechaFac."', 'Recogidas - entregas especiales' as descripcion, '1' as unidades, sum(cantidad * importe) as precio, '".$primerDia."' as fechaInicio, '".$ultimoDia."' as fechaFin
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[albaranes]
  where fecha>= '".$primerDia."' and fecha <= '".$ultimoDia."'

  group by idCliente";*/
		
		$consulta2="insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspecialesTemporal] (idCliente, ordenTrabajo, fechaFacturacion, concepto, unidades, 
	precioUnitario,fechaInicio, fechaFin) 
	SELECT idCliente, 'RECOGIDAS','".$fechaFac."', 'Recogidas - entregas especiales' as descripcion, sum(cantidad) as unidades, importe as precio, '".$primerDia."' as fechaInicio, '".$ultimoDia."' as fechaFin
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[albaranes]
  where fecha>= '".$primerDia."' and fecha <= '".$ultimoDia."'
  group by idCliente, descripcion, importe";
		
		$resultado2 = sqlsrv_query($conn_sis2, $consulta2,array(),array("Scrollable"=>"buffered"));
	

		sqlsrv_close($conn_sis2);		

		$mensaje="";	

		if( $resultado2 === false ) 
		{
			$mensaje = "Error: no se ha podido pasar los datos de las rutas'.\n".$resultado2."\n".$consulta2."\n";
			$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		}
		else
		{
			$conn_sis3=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
			$consulta3="insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspecialesTemporal] (idCliente, ordenTrabajo, fechaFacturacion, concepto, unidades, 
		precioUnitario,fechaInicio, fechaFin) 
		SELECT idCliente, ordenTrabajo,'".$fechaFac."', concepto as descripcion, unidades, precioUnitario as precio, '".$primerDia."' as fechaInicio, '".$ultimoDia."' as fechaFin
	  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspeciales]
	  where fechaFacturacion>= '".$primerDia."' and fechaFacturacion <= '".$ultimoDia."'

	  group by idCliente, ordenTrabajo, concepto, unidades, precioUnitario";
			$resultado3 = sqlsrv_query($conn_sis3, $consulta3,array(),array("Scrollable"=>"buffered"));


			sqlsrv_close($conn_sis3);		

			$mensaje="";	

			if( $resultado3 === false ) 
			{
				$mensaje = "Error: no se ha podido pasar los datos especiales'.\n".$resultado3."\n".$consulta3."\n";
				$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
			}
			else
			{

			}
		}
	}

	return $mensaje;
}


function eliminarFacturasEspeciales($datosBBDD)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspecialesTemporal]";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar los registros de las facturas especiales'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;
	
}

function cargarFacturasEspecialesTemporal($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*, t2.nombre_franqueo FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspecialesTemporal]  as t1
     inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.idCliente = t2.codigo
   order by t2.nombre_empresa, id";	
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function recogerDatosFacturasMensuales($datosBBDD,$fechaInicio,$fechaFin,$fechaFac)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$anioSeleccionado = date('Y', strtotime($fechaFac));
	
	$consulta = "
	SELECT t1.*,t5.concepto as formaPago, t2.importe, t3.envios,t4.conceptos FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t1
	left join /* para ver el importe de franqueo*/
	(
		select t1.idCliente, sum(t1.importe) as importe from  [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1 
		inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
		on  t1.tipo = t2.tipos
		inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3
		on t2.idTarifasProducto = t3.id
		where (t3.clave='B' or t3.clave='G' or t3.clave='D' or t3.clave='H' or t3.clave='NOTP') and (t1.fecha>='".$fechaInicio."' and t1.fecha<='".$fechaFin."' and (t1.ot not like 'OT%')) and t1.comprobado=1
		group by t1.idCliente
	) as t2
	on t1.codigo = t2.idCliente

	left join /* para ver el numero de envios de franqueo*/
	(
		select t1.idCliente, sum(unidades) as envios from  [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1 
		
		where  (t1.fecha>='".$fechaInicio."' and t1.fecha<='".$fechaFin."' and (t1.ot not like 'OT%')) and t1.comprobado=1
		group by t1.idCliente
	) as t3
	on t1.codigo = t3.idCliente

	left join /* se mira si tiene conceptos*/
	(
		select idCliente, 'tieneConceptos' as conceptos 
		from [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspecialesTemporal]
		where (UPPER([ordenTrabajo]) ='CD' or UPPER([ordenTrabajo]) ='BUROFAX' or UPPER([ordenTrabajo]) ='RECOGIDAS' or UPPER([ordenTrabajo]) ='CROTALES' or UPPER([ordenTrabajo]) ='FACTURAS' or UPPER([ordenTrabajo]) ='OTROS CONCEPTOS') and fechaFacturacion>='".$fechaInicio."' and fechaFacturacion<='".$fechaFin."'
		group by idCliente
	) as t4
	on t1.codigo = t4.idCliente

	left join [".$datosBBDD->bbddBBDD."].[dbo].[formaDepago] as t5
	on t5.id = t1.idFormaPago


	where t1.fac_idPeriodo=1  and t1.correoDiario=1  and (t2.importe>0 or t3.envios>0 or t4.conceptos != '' or t1.fac_cuotaRecogida>0) and t1.activo = 1 
	order by nombre_empresa 
	
	";	
	//echo $consulta;
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function recogerDatosFacturasMensualesDetalles($datosBBDD,$codigoCliente)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	/*$consulta = "
		select idCliente, concepto, sum(unidades) as unidades, sum(unidades)*precioUnitario as importe, precioUnitario 
		from [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspecialesTemporal]
		where (UPPER([ordenTrabajo]) ='CD' or UPPER([ordenTrabajo]) ='BUROFAX') and idCliente = ".$codigoCliente."
		group by idCliente, fechaFacturacion, concepto, precioUnitario
	";*/
	
	$consulta = "
	select idCliente, concepto, 1 as unidades,sum(unidades*precioUnitario) as precioUnitario, sum(unidades*precioUnitario) as importe
		from [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspecialesTemporal]
		where (UPPER([ordenTrabajo]) ='CD' or UPPER([ordenTrabajo]) ='BUROFAX') and idCliente = ".$codigoCliente."
		group by idCliente, fechaFacturacion, concepto
union
	select idCliente, concepto, unidades,precioUnitario, (unidades*precioUnitario) as importe
		from [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspecialesTemporal]
		where (UPPER([ordenTrabajo]) ='CROTALES' or UPPER([ordenTrabajo]) ='FACTURAS' or UPPER([ordenTrabajo]) ='RECOGIDAS' or UPPER([ordenTrabajo]) ='OTROS CONCEPTOS') and idCliente = ".$codigoCliente
		
		
		;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function insertarFacturasMensuales($datosBBDD,$nombreCliente,$codigoSaldoCliente,$descripcion,$presupuesto,$fecha,$inicialComercial,$precioNetoTotal,$iva,$precioTotal,$provision,$aPagar,$cantidadFranqueo,$pedido,$formaPago,$detallada,$formaPagoReal,$fechaPago,$cd,$fechaInicio, $fechaFin,$precioFranqueo,$nuestraCuenta,$anioSeleccionado,$prefactura)
{
	$connectionInfo = array(
        "Database" => $datosBBDD->bbddBBDD,
        "UID"      => $datosBBDD->bbddUser,
        "PWD"      => $datosBBDD->bbddPass,
        "CharacterSet" => "UTF-8"
    );

    $conn_sis = sqlsrv_connect($datosBBDD->dbhost, $connectionInfo);

    if (!$conn_sis) {
        return array(
            "ok"      => false,
            "mensaje" => "Error de conexión: " . print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    // Intentar iniciar la transacción
    if (!sqlsrv_begin_transaction($conn_sis)) {
        return array(
            "ok"      => false,
            "mensaje" => "No se pudo iniciar transacción: " . print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

	/*
    // Si todo va bien, devolvemos la conexión activa
    return array(
        "ok"      => true,
        "mensaje" => "Conexión establecida y transacción iniciada",
        "data"    => $conn_sis
    );
	*/

	// 2) Calcular el nuevo número con bloqueos (serializable sobre el rango)
    $sqlSel = "SELECT ISNULL(MAX(numero),0) + 1 AS n FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] WITH (UPDLOCK, HOLDLOCK)";
   
	$stmtSel = sqlsrv_query($conn_sis, $sqlSel);
    if ($stmtSel === false) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));
		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error SELECT MAX(numero): ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

    $row = sqlsrv_fetch_array($stmtSel, SQLSRV_FETCH_ASSOC);

	if (!$row || !isset($row['n'])) {
        sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "No se obtuvo un número válido en SELECT MAX(numero)",
            "data"    => null
        );
    }


    $numero = (int)$row['n'];

	$anioDosDigitos = $anioSeleccionado - 2000;
    $numeroCompleto = 'FAC '.$numero.'/'.$anioDosDigitos; 







	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] 
	(numero,numeroFacturaCompleto,cliente, idCodigoCliente, descripcion, presupuesto, fecha, inicialComercial, precioNeto, iva, precioTotal, provision, aPagar, cantidad, pedido
	, formaPago, detallada, formaPagoReal, fechaPago, cd, fechaInicio, fechaFin, importeFranqueo, numCuentaBanco,prefactura)
	
	values (
	".$numero.",
	'".$numeroCompleto."',
	'".$nombreCliente."',
	'".$codigoSaldoCliente."',
	'".$descripcion."',
	'".$presupuesto."',
	'".$fecha."',
	'".$inicialComercial."',
	".$precioNetoTotal.",
	".$iva.",
	".$precioTotal.",
	".$provision.",
	".$aPagar.",
	".$cantidadFranqueo.",
	'".$pedido."',
	'".$formaPago."',
	".$detallada.",
	'".$formaPagoReal."',
	NULL,
	".$cd.",
	'".$fechaInicio."',
	'".$fechaFin."',
	".$precioFranqueo.", 
	'".$nuestraCuenta."'
	,".$prefactura."
	)";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

	if ($resultado === false) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));

		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error en INSERT: ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }
	

	if (!sqlsrv_commit($conn_sis)) {
        //sqlsrv_rollback($conn_sis);
        //die(print_r(sqlsrv_errors(), true));
		sqlsrv_rollback($conn_sis);
        sqlsrv_close($conn_sis);
        return array(
            "ok"      => false,
            "mensaje" => "Error en COMMIT: ".print_r(sqlsrv_errors(), true),
            "data"    => null
        );
    }

	sqlsrv_close($conn_sis);
	

	// Devuelve el número reservado/insertado (y lo que se necesite)
    //return array(array('numero' => $numero, 'numeroFacturaCompleto' => $numeroCompleto));

	return array(
        "ok"      => true,
        "mensaje" => "Factura insertada correctamente con número $numeroCompleto",
        "data"    => array(
						array(
							'numero' => $numero, 
							'numeroFacturaCompleto' => $numeroCompleto
							)
						)

    			);
	
}


function insertarFacturasDetallesManuales($datosBBDD, $numeroFactura, $concepto, $unidades, $precioUnitario, $total1, $descripcion, $nota, $ordenTipo, $orden,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."] (factura, concepto, unidades, precio, total, descripcion, notaCibeles, ordenTipo, orden)
	values (".$numeroFactura.",'".$concepto."',".$unidades.",".$precioUnitario.",".$total1.",'".$descripcion."','".$nota."',".$ordenTipo.",".$orden.")
	";  
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido insertar los detalles de la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}


function insertarFacturaMensualPorCopia($datosBBDD, $numeroFacturaAcopiar,$anioSeleccionadoAcopiar,$fecha,$fechaInicio, $fechaFin,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] 
	([cliente]
			  ,[descripcion]
			  ,[presupuesto]
			  ,[origenFactura]
			  ,[fecha]
			  ,[inicialComercial]
			  ,[precioNeto]
			  ,[iva]
			  ,[precioTotal]
			  ,[provision]
			  ,[aPagar]
			  ,[cantidad]
			  ,[pedido]
			  ,[formaPago]
			  ,[detallada]
			  ,[cd]
			  ,[fechaInicio]
			  ,[fechaFin]
			  ,[importeFranqueo]
			  ,[numCuentaBanco]			  
			  ,[combinadoSumatorio]
			  ,[observaciones]
			  ,[observacionesInternas]			  
			  ,[prefactura])
   
   select  [cliente]
			  ,[descripcion]
			  ,[presupuesto]
			  ,[origenFactura]
			  ,'".$fecha."'
			  ,[inicialComercial]
			  ,[precioNeto]
			  ,[iva]
			  ,[precioTotal]
			  ,[provision]
			  ,[aPagar]
			  ,[cantidad]
			  ,[pedido]
			  ,[formaPago]
			  ,[detallada]			  
			  ,[cd]
			  ,'".$fechaInicio."'
			  ,'".$fechaFin."'
			  ,[importeFranqueo]
			  ,[numCuentaBanco]			  
			  ,[combinadoSumatorio]
			  ,[observaciones]
			  ,[observacionesInternas]			  
			  ,1 from [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionadoAcopiar."] where numero=".$numeroFacturaAcopiar;
  
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

	if( $resultado === false ) 
	{
		$mensaje = "Error: no se ha podido crear la Factura Mensual por Copia.'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));			
	}
	else
	{
		$consulta = "SELECT SCOPE_IDENTITY() AS 'numeroFactura'";
		$stmt  = sqlsrv_query($conn_sis, $consulta);
		
		$mensaje="";

		if( sqlsrv_fetch($stmt)  === false ) 
		{
			$mensaje = "Error: no se ha podido ver la id del usuario insertado'.\n".$stmt ."\n".$consulta."\n";
			$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		}
		else
		{
			$mensaje = sqlsrv_get_field($stmt , 0);
			//$mensaje = $mensaje."|||Usuario insertado";
		}
	}	
	sqlsrv_close($conn_sis);
	return $mensaje;	
}

function insertarFacturaMensualDetallesPorCopia($datosBBDD, $numFacturaCopiar, $numFacturaNueva,$anioSeleccionado,$anioSeleccionadoAcopiar)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."] (
      [factura]
      ,[concepto]
      ,[unidades]
      ,[precio]
      ,[total]
      ,[descripcion]
      ,[notaCibeles]
      ,[ordenTipo]
      ,[orden])
  
  select '".$numFacturaNueva."'
      ,[concepto]
      ,[unidades]
      ,[precio] as precio
      ,[total] as total
      ,[descripcion]
      ,[notaCibeles]
      ,[ordenTipo]
      ,[orden] from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionadoAcopiar."] where factura = ".$numFacturaCopiar;
  
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido duplicar los detalles de la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}


/*function verRangoFacturasPorNumeroYCdSinRetener($datosBBDD,$numFacturaInicio, $numFacturaFin)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT t1.*, t2.*, t1.numCuentaBanco as cuentaDelBanco
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.subcliente
	
	where t1.numero>=".$numFacturaInicio." and t1.numero<=".$numFacturaFin." and t1.cd = 1 and retener=0";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	
	return $result;
}*/

/*function verRangoFacturasPorNumeroYCdRetenidas($datosBBDD,$numFacturaInicio, $numFacturaFin)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT t1.*, t2.*
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.cliente = t2.subcliente
	
	where t1.numero>=".$numFacturaInicio." and t1.numero<=".$numFacturaFin." and t1.cd = 1 and retener=1";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	
	return $result;
}*/


function duplicarFactura_Abono($datosBBDD,$numeroFactura,$fecha,$anioSeleccionado,$anioSeleccionado2)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anioSeleccionado2."] (cliente, descripcion, factura, anioFactura, fecha, inicialComercial, precioNeto, iva, irpf, precioTotal, provision, aPagar, cantidad, pedido, formaPago, detallada, formaPagoReal, fechaPago, cd, fechaInicio, fechaFin, importeFranqueo, numcuentaBanco) 

SELECT [cliente]
      ,descripcion
      ,".$numeroFactura."
	  ,".$anioSeleccionado."
      ,'".$fecha."'
      ,[inicialComercial]
      ,[precioNeto]*-1 as precioNeto
      ,[iva]*-1 as iva
	  ,[irpf]*-1 as irpf
      ,[precioTotal]*-1 as precioTotal
      ,[provision]*-1 as provision
      ,[aPagar]*-1 as aPagar
      ,[cantidad]
      ,[pedido]
      ,[formaPago]
      ,[detallada]
      ,[formaPagoReal]
      ,[fechaPago]
      ,[cd]
      ,[fechaInicio]
      ,[fechaFin]
      ,[importeFranqueo]
      ,[numCuentaBanco]
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."]
  where numero = ".$numeroFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido crear el Rect.'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		return $mensaje;
	}
	else
	{
		$consulta = "SELECT SCOPE_IDENTITY() AS 'numeroAbono', '".$anioSeleccionado2."' as anioAbono";
		$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

		$result = array();

		while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
		{
			$result[] = $valor;
		}

		sqlsrv_close($conn_sis);
		return $result;		
	}
}

function duplicarFacturaDetalles_Abono($datosBBDD, $numFacturaCopiar, $numAbonoNuevo,$anioSeleccionado,$anioSeleccionado2)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[abonoDetalles".$anioSeleccionado2."] (
      [factura]
      ,[concepto]
      ,[unidades]
      ,[precio]
      ,[total]
      ,[descripcion]
      ,[notaCibeles]
      ,[ordenTipo]
      ,[orden])
  
  select '".$numAbonoNuevo."'
      ,[concepto]
      ,[unidades]
      ,[precio]*-1 as precio
      ,[total]*-1 as total
      ,[descripcion]
      ,[notaCibeles]
      ,[ordenTipo]
      ,[orden] from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."] where factura = ".$numFacturaCopiar;
  
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido duplicar los detalles de la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}


function duplicarFactura_AbonoClayma($datosBBDD,$numeroFactura,$fecha,$anioSeleccionado,$anioSeleccionado2)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$anio2digitos = date("Y", strtotime($anioSeleccionado));
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anioSeleccionado2."] (cliente, descripcion, factura, anioFactura, fecha, inicialComercial, precioNeto, iva, precioTotal, provision, aPagar, cantidad, pedido, formaPago, detallada, formaPagoReal, fechaPago, cd, fechaInicio, fechaFin, importeFranqueo, numcuentaBanco) 

SELECT [cliente]
      ,descripcion
      , '".$numeroFactura."'
	  , $anio2digitos
      ,'".$fecha."'
      ,[inicialComercial]
      ,[precioNeto]*-1 as precioNeto
      ,[iva]*-1 as iva
      ,[precioTotal]*-1 as precioTotal
      ,[provision]*-1 as provision
      ,[aPagar]*-1 as aPagar
      ,[cantidad]
      ,[pedido]
      ,[formaPago]
      ,[detallada]
      ,[formaPagoReal]
      ,[fechaPago]
      ,[cd]
      ,[fechaInicio]
      ,[fechaFin]
      ,[importeFranqueo]
      ,[numCuentaBanco]
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."]
  where numero = ".$numeroFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido crear el Rect.'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		return $mensaje;
	}
	else
	{
		$consulta = "SELECT SCOPE_IDENTITY() AS 'numeroAbono', '".$anioSeleccionado2."' as anioAbono";
		$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

		$result = array();

		while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
		{
			$result[] = $valor;
		}

		sqlsrv_close($conn_sis);
		return $result;		
	}
}

function duplicarFacturaDetalles_AbonoClayma($datosBBDD, $numFacturaCopiar, $numAbonoNuevo,$anioSeleccionado,$anioSeleccionado2)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[abonoDetallesClayma".$anioSeleccionado2."] (
      [factura]
      ,[concepto]
      ,[unidades]
      ,[precio]
      ,[total]
      ,[descripcion]
      ,[notaCibeles]
      ,[ordenTipo]
      ,[orden])
  
  select '".$numAbonoNuevo."'
      ,[concepto]
      ,[unidades]
      ,[precio]*-1 as precio
      ,[total]*-1 as total
      ,[descripcion]
      ,[notaCibeles]
      ,[ordenTipo]
      ,[orden] from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClayma".$anioSeleccionado."] where factura = ".$numFacturaCopiar;
  
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido duplicar los detalles de la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}


//////////////////////
function facturaAfacturaMensualPendiente($datosBBDD,$numeroFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	     
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[facturasMensualesPendientes]
           ([numeroFacturaOriginal], anio)
		   select numero, ".$anioSeleccionado." from  [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] where [origenFactura] = 'mensual' and numero = ".$numeroFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido crear el Rect.'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		return $mensaje;
	}
	else
	{
			
	}
}

function cambiarValorOrigenFactura($datosBBDD,$numeroFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
     	
	$consulta = "  update
   [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."]
   set [origenFactura] = 'mensual'
   where numero = ".$numeroFactura ." and (presupuesto = 'mensual' or presupuesto like 'Factura Original%')";
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido crear el Rect.'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		return $mensaje;
	}
	else
	{
			
	}
}

function cambiarValorOrigenFacturaClayma($datosBBDD,$numeroFactura, $anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "  update
   [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."]
   set [origenFactura] = 'mensual'
   where numero = ".$numeroFactura ." and (presupuesto = 'mensual' or presupuesto like 'Factura Original%')";
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido crear el Rect.'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		return $mensaje;
	}
	else
	{
			
	}
}

function facturaAfacturaMensualPendienteClayma($datosBBDD,$numeroFactura, $anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "  INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[facturasMensualesPendientesClayma]
           ([numeroFacturaOriginal], anio)
		   select numero, ".$anioSeleccionado." from  [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] where [origenFactura] = 'mensual' and numero = ".$numeroFactura;
     
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido crear el Rect.'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		return $mensaje;
	}
	else
	{
			
	}
}




function borrarNumPresupuestoEnFactura($datosBBDD, $numFactura,$anioSeleccionado) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update
   [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."]
   set presupuesto = presupuesto + '_V'
   where numero = ".$numFactura;
  
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido borrar el numero de presupuesto en la factura original'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}

function borrarNumPresupuestoEnFacturaClayma($datosBBDD, $numFactura, $anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update
   [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."]
   set presupuesto = presupuesto + '_V'
   where numero = ".$numFactura;
  
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido borrar el numero de presupeusto en la factura original'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}


function cambiarValorAbonoEnFactura($datosBBDD, $numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update
   [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."]
   set abono = 1
   where numero = ".$numFactura;
  
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido modificar el valor del campo 'Abono' de la tabla Factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}

function cambiarValorAbonoEnFacturaClayma($datosBBDD, $numFactura, $anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."]
   set abono = 1
   where numero = ".$numFactura;
  
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido modificar el valor del campo 'Abono' de la tabla Factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}

function verContadorPFporPresupuesto($datosBBDD,$presupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
		
	$consulta = "SELECT   presupuesto, isnull(max(contador),0) as contador
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo]
  where presupuesto = '".$presupuesto."'
  group by presupuesto";	
	 	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	//echo $consulta;
	sqlsrv_close($conn_sis);
	return $result;
}

function verNumeroPFcorreoDiario($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	
	$consulta = "SELECT max(presupuesto) as ultimoNumero, max(presupuesto)+1 as proximoNumero
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] 
  where presupuesto like '9".date("y")."%'";  
	 	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	//echo $consulta;
	sqlsrv_close($conn_sis);
	return $result;
}


function traspasarPresupuestosAccessAsql($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	/*if ($conn_sis)
	{		
			echo "conexion existosa";
	} 
	else
	{
			echo "conexion fallida.\nNo se ha podido conectar al servidor";
	} */
	
	$consulta = "SELECT * FROM [LOCAL]...[tiemposOt]";
	         
	//$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar la tabla de presupuesto'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		return $mensaje;
	}
	if (1==0)
	//else
	{
		
		$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] 
( PRESUPUESTO, CLIENTE, PERSONA, DIRECCION, Poblacion, CP, PAGO, notaCibeles, CANTIDAD, Fecha, PEDCLI, fechaAceptacion, fechaCompromiso, fechaTerminado, FACTURA, detallada )
 select presupuesto, cliente, persona, direccion, poblacion, cp, pago, [nota cibeles], cantidad, fecha, pedcli, [fe-aceptado], [fe-compromiso], [fe-terminado], factura, detallada  from 
OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Produccion\Compartido\sergio\borrar.mdb';'Admin';'', 'select * from [maestro presupuestos]')";
		
	
		
		//$consulta = iconv("UTF-8", "ISO-8859-1", $consulta);
		//$consulta = mb_convert_encoding($consulta, 'ISO-8859-15', 'UTF-8');
		$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

		if( $resultado === false ) 
		{
			$mensaje = "Error2'.\nResultado: ".$resultado."\n".$consulta."\n";
			$mensaje = $mensaje.(print_r(sqlsrv_errors(), true));
			return $mensaje;
		}
		
		else 
		{
			$consulta = "update t1
			set t1.idFormaPago = 1
			from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
			inner join 
			(select *  from 
			OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
			on t1.presupuesto = t2.presupuesto
			where t2.[forma de pago] = 'Al contado'";
			
			//$consulta = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $consulta);
			$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

			if( $resultado === false ) 
			{
				$mensaje = "Error3'.\n".$resultado."\n".$consulta."\n";
				$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
				return $mensaje;
			}
			else
			{
				$consulta = "update t1
				set t1.idFormaPago = 2
				from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
				inner join 
				(select *  from 
				OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
				on t1.presupuesto = t2.presupuesto
				where t2.[forma de pago] = 'Del Franqueo se realizará una Provisón de Fondos y el resto a 30 días F.F.'";


				$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

				if( $resultado === false ) 
				{
					$mensaje = "Error4'.\n".$resultado."\n".$consulta."\n";
					$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
					return $mensaje;
				}
				else
				{
					$consulta = "update t1
					set t1.idFormaPago = 3
					from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
					inner join 
					(select *  from 
					OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
					on t1.presupuesto = t2.presupuesto
					where t2.[forma de pago] = 'Transferencia a  30 días  F.F.'";


					$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

					if( $resultado === false ) 
					{
						$mensaje = "Error5'.\n".$resultado."\n".$consulta."\n";
						$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
						return $mensaje;
					}
					else
					{
						$consulta = "update t1
						set t1.idFormaPago = 4
						from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
						inner join 
						(select *  from 
						OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
						on t1.presupuesto = t2.presupuesto
						where t2.[forma de pago] = 'Transferencia a 60 días F.F.'";


						$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

						if( $resultado === false ) 
						{
							$mensaje = "Error6'.\n".$resultado."\n".$consulta."\n";
							$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
							return $mensaje;
						}
						else
						{
							$consulta = "update t1
							set t1.idFormaPago = 5
							from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
							inner join 
							(select *  from 
							OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
							on t1.presupuesto = t2.presupuesto
							where t2.[forma de pago] = 'Recibo domiciliado'";


							$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

							if( $resultado === false ) 
							{
								$mensaje = "Error7'.\n".$resultado."\n".$consulta."\n";
								$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
								return $mensaje;
							}
							else
							{
								$consulta = "update t1
								set t1.idFormaPago = 6
								from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
								inner join 
								(select *  from 
								OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
								on t1.presupuesto = t2.presupuesto
								where t2.[forma de pago] = '50% inicio de campaña - 50% fin de campaña'";


								$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

								if( $resultado === false ) 
								{
									$mensaje = "Error8'.\n".$resultado."\n".$consulta."\n";
									$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
									return $mensaje;
								}
								else
								{
									$consulta = "update t1
									set t1.idFormaPago = 7
									from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
									inner join 
									(select *  from 
									OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
									on t1.presupuesto = t2.presupuesto
									where t2.[forma de pago] = 'Compensacion factura contra insercion de anuncio'";


									$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

									if( $resultado === false ) 
									{
										$mensaje = "Error9'.\n".$resultado."\n".$consulta."\n";
										$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
										return $mensaje;
									}
									else
									{
										$consulta = "update t1
										set t1.idFormaPago = 8
										from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
										inner join 
										(select *  from 
										OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
										on t1.presupuesto = t2.presupuesto
										where t2.[forma de pago] = 'Primer trabajo solicitado: Al contado. Al inicio de los trabajos se realizará transferencia'";


										$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

										if( $resultado === false ) 
										{
											$mensaje = "Error10'.\n".$resultado."\n".$consulta."\n";
											$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
											return $mensaje;
										}
										else
										{
											$consulta = "update t1
											set t1.idFormaPago = 9
											from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
											inner join 
											(select *  from 
											OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
											on t1.presupuesto = t2.presupuesto
											where t2.[forma de pago] = 'Al contado. A  la recepción  del material'";


											$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

											if( $resultado === false ) 
											{
												$mensaje = "Error11'.\n".$resultado."\n".$consulta."\n";
												$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
												return $mensaje;
											}
											else
											{
												$consulta = "update t1
												set t1.idFormaPago = 10
												from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
												inner join 
												(select *  from 
												OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
												on t1.presupuesto = t2.presupuesto
												where t2.[forma de pago] = 'Transferencia inmediata'";


												$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

												if( $resultado === false ) 
												{
													$mensaje = "Error12'.\n".$resultado."\n".$consulta."\n";
													$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
													return $mensaje;
												}
												else
												{
													$consulta = "update t1
													set t1.idFormaPago = 11
													from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
													inner join 
													(select *  from 
													OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
													on t1.presupuesto = t2.presupuesto
													where t2.[forma de pago] = '50% a la aceptación del presupuesto - 50% a 30 días F.F.'";


													$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

													if( $resultado === false ) 
													{
														$mensaje = "Error13'.\n".$resultado."\n".$consulta."\n";
														$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
														return $mensaje;
													}
													else
													{
														$consulta = "update t1
														set t1.idFormaPago = 12
														from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
														inner join 
														(select *  from 
														OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
														on t1.presupuesto = t2.presupuesto
														where t2.[forma de pago] = '50% a la aceptación del presupuesto - 50% a la entrega de materiales'";


														$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

														if( $resultado === false ) 
														{
															$mensaje = "Error14'.\n".$resultado."\n".$consulta."\n";
															$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
															return $mensaje;
														}
														else
														{
															$consulta = "update t1
															set t1.idFormaPago = 13
															from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
															inner join 
															(select *  from 
															OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
															on t1.presupuesto = t2.presupuesto
															where t2.[forma de pago] = '50% a 30 d. f.f. y 50% a 60 d. f.f.'";


															$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

															if( $resultado === false ) 
															{
																$mensaje = "Error15'.\n".$resultado."\n".$consulta."\n";
																$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																return $mensaje;
															}
															else
															{
																$consulta = "update t1
																set t1.idFormaPago = 14
																from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																inner join 
																(select *  from 
																OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																on t1.presupuesto = t2.presupuesto
																where t2.[forma de pago] = 'Talón Bancario a 60 d. f.f.'";


																$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																if( $resultado === false ) 
																{
																	$mensaje = "Error16'.\n".$resultado."\n".$consulta."\n";
																	$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																	return $mensaje;
																}
																else
																{
																	$consulta = "update t1
																	set t1.idFormaPago = 15
																	from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																	inner join 
																	(select *  from 
																	OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																	on t1.presupuesto = t2.presupuesto
																	where t2.[forma de pago] = 'Del Franqueo se realizará una Provisón de Fondos y el resto a 60 días F.F.'";


																	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																	if( $resultado === false ) 
																	{
																		$mensaje = "Error17'.\n".$resultado."\n".$consulta."\n";
																		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																		return $mensaje;
																	}
																	else
																	{
																		$consulta = "update t1
																		set t1.idFormaPago = 16
																		from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																		inner join 
																		(select *  from 
																		OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																		on t1.presupuesto = t2.presupuesto
																		where t2.[forma de pago] = 'Talón Bancario a 30 d. f.f.'";


																		$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																		if( $resultado === false ) 
																		{
																			$mensaje = "Error18'.\n".$resultado."\n".$consulta."\n";
																			$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																			return $mensaje;
																		}
																		else
																		{
																			$consulta = "update t1
																			set t1.idFormaPago = 17
																			from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																			inner join 
																			(select *  from 
																			OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																			on t1.presupuesto = t2.presupuesto
																			where t2.[forma de pago] = 'ANTICIPADO'";


																			$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																			if( $resultado === false ) 
																			{
																				$mensaje = "Error19'.\n".$resultado."\n".$consulta."\n";
																				$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																				return $mensaje;
																			}
																			else
																			{
																				$consulta = "update t1
																				set t1.idFormaPago = 18
																				from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																				inner join 
																				(select *  from 
																				OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																				on t1.presupuesto = t2.presupuesto
																				where t2.[forma de pago] = 'Transferencia'";


																				$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																				if( $resultado === false ) 
																				{
																					$mensaje = "Error20'.\n".$resultado."\n".$consulta."\n";
																					$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																					return $mensaje;
																				}
																				else
																				{
																					////parte de comercial//////
																					$consulta = "update t1
																					set t1.idComercial = 1
																					from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																					inner join 
																					(select *  from 
																					OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																					on t1.presupuesto = t2.presupuesto
																					where t2.Comercial = 'Raúl'";


																					$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																					if( $resultado === false ) 
																					{
																						$mensaje = "Error21'.\n".$resultado."\n".$consulta."\n";
																						$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																						return $mensaje;
																					}
																					else
																					{
																						
																						$consulta = "update t1
																						set t1.idComercial = 2
																						from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																						inner join 
																						(select *  from 
																						OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																						on t1.presupuesto = t2.presupuesto
																						where t2.Comercial = 'Rocío'";


																						$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																						if( $resultado === false ) 
																						{
																							$mensaje = "Error22'.\n".$resultado."\n".$consulta."\n";
																							$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																							return $mensaje;
																						}
																						else
																						{
																							$consulta = "update t1
																							set t1.idComercial = 3
																							from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																							inner join 
																							(select *  from 
																							OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																							on t1.presupuesto = t2.presupuesto
																							where t2.Comercial = 'Alfonso - 637 701 133'";


																							$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																							if( $resultado === false ) 
																							{
																								$mensaje = "Error23'.\n".$resultado."\n".$consulta."\n";
																								$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																								return $mensaje;
																							}
																							else
																							{
																								$consulta = "update t1
																								set t1.idComercial = 4
																								from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																								inner join 
																								(select *  from 
																								OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																								on t1.presupuesto = t2.presupuesto
																								where t2.Comercial = 'Carlos Matesanz - 699 195 388'";

																								$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																								if( $resultado === false ) 
																								{
																									$mensaje = "Error24'.\n".$resultado."\n".$consulta."\n";
																									$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																									return $mensaje;
																								}
																								else
																								{
																									$consulta = "update t1
																									set t1.idComercial = 6
																									from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																									inner join 
																									(select *  from 
																									OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																									on t1.presupuesto = t2.presupuesto
																									where t2.Comercial = 'Ángel Rodriguez - 660 947 344'";

																									$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																									if( $resultado === false ) 
																									{
																										$mensaje = "Error25'.\n".$resultado."\n".$consulta."\n";
																										$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																										return $mensaje;
																									}
																									else
																									{
																										$consulta = "update t1
																										set t1.idComercial = 7
																										from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																										inner join 
																										(select *  from 
																										OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																										on t1.presupuesto = t2.presupuesto
																										where t2.Comercial = 'Cristina'";

																										$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																										if( $resultado === false ) 
																										{
																											$mensaje = "Error26'.\n".$resultado."\n".$consulta."\n";
																											$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																											return $mensaje;
																										}
																										else
																										{
																											$consulta = "update t1
																											set t1.idComercial = 8
																											from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																											inner join 
																											(select *  from 
																											OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																											on t1.presupuesto = t2.presupuesto
																											where t2.Comercial = 'Silvia - 91 684 37 37'";

																											$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																											if( $resultado === false ) 
																											{
																												$mensaje = "Error27'.\n".$resultado."\n".$consulta."\n";
																												$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																												return $mensaje;
																											}
																											else
																											{
																												$consulta = "update t1
																												set t1.idComercial = 9
																												from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																												inner join 
																												(select *  from 
																												OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																												on t1.presupuesto = t2.presupuesto
																												where t2.Comercial = 'Mirela - 91 684 37 37'";

																												$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																												if( $resultado === false ) 
																												{
																													$mensaje = "Error28'.\n".$resultado."\n".$consulta."\n";
																													$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																													return $mensaje;
																												}
																												else
																												{
																													$consulta = "update t1
																													set t1.idComercial = 10
																													from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																													inner join 
																													(select *  from 
																													OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																													on t1.presupuesto = t2.presupuesto
																													where t2.Comercial = 'Alejandro - 629 351 536'";

																													$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																													if( $resultado === false ) 
																													{
																														$mensaje = "Error29'.\n".$resultado."\n".$consulta."\n";
																														$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																														return $mensaje;
																													}
																													else
																													{
																														$consulta = "update t1
																														set t1.idComercial = 11
																														from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																														inner join 
																														(select *  from 
																														OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																														on t1.presupuesto = t2.presupuesto
																														where t2.Comercial = 'Thierry - 607 27 15 91'";

																														$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																														if( $resultado === false ) 
																														{
																															$mensaje = "Error30'.\n".$resultado."\n".$consulta."\n";
																															$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																															return $mensaje;
																														}
																														else
																														{
																															$consulta = "update t1
																															set t1.idComercial = 12
																															from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																															inner join 
																															(select *  from 
																															OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																															on t1.presupuesto = t2.presupuesto
																															where t2.Comercial = 'Alejandra - 682 691 545'";

																															$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																															if( $resultado === false ) 
																															{
																																$mensaje = "Error31'.\n".$resultado."\n".$consulta."\n";
																																$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																																return $mensaje;
																															}
																															else
																															{
																																$consulta = "update t1
																																set t1.idComercial = 13
																																from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																																inner join 
																																(select *  from 
																																OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [maestro presupuestos]')) as t2
																																on t1.presupuesto = t2.presupuesto
																																where t2.Comercial = 'Benja'";

																																$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																																if( $resultado === false ) 
																																{
																																	$mensaje = "Error32'.\n".$resultado."\n".$consulta."\n";
																																	$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																																	return $mensaje;
																																}
																																else
																																{
																																	$consulta = "update t1
																																	set t1.otBajada = t2.[ot abierta]
																																	, t1.otAbierta = t2.realizado
																																	from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
																																	inner join 
																																	(select *  from 
																																	OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [presupuestos]')) as t2
																																	on t1.presupuesto = t2.presu";

																																	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																																	if( $resultado === false ) 
																																	{
																																		$mensaje = "Error33'.\n".$resultado."\n".$consulta."\n";
																																		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																																		return $mensaje;
																																	}
																																	else
																																	{
																																		////////detalles/////////
																																		$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle]";

																																		$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																																		if( $resultado === false ) 
																																		{
																																			$mensaje = "Error34'.\n".$resultado."\n".$consulta."\n";
																																			$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																																			return $mensaje;
																																		}
																																		else
																																		{
																																			$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] (id, PRESUPUESTO, CONCEPTO, GRUPO, UNIDADES, PRECIO, DESCRIPCION, notaCibeles )
																																			select id, presupuesto, concepto, grupo, unidades, precio, descripcion, [nota cibeles]  
																																			from OPENROWSET('Microsoft.ACE.OLEDB.12.0','Z:\Comercial\Compartido\2021\PRESUPUESTOS 2021.mdb';'Admin';'', 'select * from [MAESTRO PRESUPUESTOS DETALLE]')";

																																			$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));

																																			if( $resultado === false ) 
																																			{
																																				$mensaje = "Error35'.\n".$resultado."\n".$consulta."\n";
																																				$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
																																				return $mensaje;
																																			}
																																			else
																																			{

																																			}

																																		}

																																	}

																																}

																															}

																														}

																													}

																												}

																											}

																										}

																									}

																								}

																							}
																						}
																					}
																				}
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}			
		}
	}
	
	
}


		
function copiarPresupuestoMensual ($datosBBDD, $contadorViejo, $contadorNuevo, $fechaInicio, $fechaAceptacion, $fechaFin, $fechaCompromiso)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]
           ([presupuesto]
           ,[letra]
           ,[cliente]
           ,[persona]
           ,[direccion]
           ,[poblacion]
           ,[cp]
           ,[pago]
           ,[notaCibeles]
           ,[forma de pago]
           ,[campana]
           ,[cantidad]           
           ,[comercial]
           ,[pedcli]
           ,[fechaAceptacion]
           ,[fechaCompromiso]
           ,[fechaTerminado]
           ,[factura]
           ,[detallada]
           ,[idComercial]
           ,[idFormaPago]
           ,[idVisualizarTotalPresu]
           ,[idVisualizarTotalFranqueo]
           ,[importeFranqueo]
           ,[otBajada]
           ,[otAbierta]
           ,[fechaInicioReal]
           ,[noRetrasar]
           ,[campana2]
           ,[cantidad2]
           ,[pdfGenerado]
		   ,[observaciones2]
		   ,[clayma])
     
	 SELECT '".$contadorNuevo."'
      ,''
      ,[cliente]
      ,[persona]
      ,[direccion]
      ,[poblacion]
      ,[cp]
      ,[pago]
      ,[notaCibeles]
      ,[forma de pago]
      ,[campana]
      ,[cantidad]     
      ,[comercial]
      ,[pedcli]
      ,'".$fechaAceptacion."'
      ,'".$fechaCompromiso."'
      ,'".$fechaFin."'
      ,[factura]
      ,[detallada]
      ,[idComercial]
      ,[idFormaPago]
      ,[idVisualizarTotalPresu]
      ,[idVisualizarTotalFranqueo]
      ,[importeFranqueo]
      ,[otBajada]
      ,[otAbierta]
      ,'".$fechaInicio."'
      ,[noRetrasar]
      ,[campana2]
      ,[cantidad2]
      ,0
	  ,[observaciones2]
	  ,[clayma]
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]
 where presupuesto = '".$contadorViejo."'";
	//echo "\n".$consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido crear el presupuesto: ".$contadorNuevo."\n";
		//$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;	
}


	
function cargarRutasPlantilla ($datosBBDD,$condicion)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT  [id]
      ,[idCliente]
      ,[lunesRuta]
      ,[lunesHora]
      ,[martesRuta]
      ,[martesHora]
      ,[miercolesRuta]
      ,[miercolesHora]
      ,[juevesRuta]
      ,[juevesHora]
      ,[viernesRuta]
      ,[viernesHora]
      ,[incidencia]
      ,[contacto]
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasPlantilla] ". $condicion;
	
	/*if ($condicion!="")
	{
		$consulta .= " where ".$condicion;
	}*/
	
	//echo "\n".$consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}



function modificarRutasPlantilla ($datosBBDD,$id,$idCliente,$Lruta,$Lhora,$Mruta,$Mhora,$Xruta,$Xhora,$Jruta,$Jhora,$Vruta,$Vhora,$contacto,$incidencia)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	if ($Lhora==null)
	{
		$Lhora_valor = "null";
	}
	else
	{
		$Lhora_valor = "'".$Lhora."'";
	}
	
	if ($Mhora==null)
	{
		$Mhora_valor = "null";
	}
	else
	{
		$Mhora_valor = "'".$Mhora."'";
	}
	
	if ($Xhora==null)
	{
		$Xhora_valor = "null";
	}
	else
	{
		$Xhora_valor = "'".$Xhora."'";
	}
	
	if ($Jhora==null)
	{
		$Jhora_valor = "null";
	}
	else
	{
		$Jhora_valor = "'".$Jhora."'";
	}
	if ($Vhora==null)
	{
		$Vhora_valor = "null";
	}
	else
	{
		$Vhora_valor = "'".$Vhora."'";
	}	
	
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[rutasPlantilla] 
	SET [idCliente] = ".$idCliente."
      ,[lunesRuta] = '".$Lruta."'
      ,[lunesHora] = ".$Lhora_valor."
      ,[martesRuta] = '".$Mruta."'
      ,[martesHora] = ".$Mhora_valor."
      ,[miercolesRuta] = '".$Xruta."'
      ,[miercolesHora] = ".$Xhora_valor."
      ,[juevesRuta] = '".$Jruta."'
      ,[juevesHora] = ".$Jhora_valor."
      ,[viernesRuta] = '".$Vruta."'
      ,[viernesHora] = ".$Vhora_valor."
      ,[incidencia] = '".$incidencia."'
      ,[contacto] = '".$contacto."'
	
	where id=".$id;
	//return ($consulta);
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el registro de la ruta'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = $consulta;
	}
	
	return $mensaje;	
}


function eliminarRutasPlantillaPorId($datosBBDD,$id)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[rutasPlantilla] where id = ".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el registro'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}


function insertarRutasPlantillaPorId($datosBBDD,$idCliente,$LR,$LH,$MR,$MH,$XR,$XH,$JR,$JH,$VR,$VH,$contacto,$incidencia)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//echo ("martes hora: ".$MH."\n");
	
	
	if ($LH=="null")
	{
		$Lhora_valor = "null";
	}
	else
	{
		$Lhora_valor = "'".$LH."'";
	}
	
	if ($MH=="null")
	{
		$Mhora_valor = "null";
	}
	else
	{
		$Mhora_valor = "'".$MH."'";
	}
	
	if ($XH=="null")
	{
		$Xhora_valor = "null";
	}
	else
	{
		$Xhora_valor = "'".$XH."'";
	}
	
	if ($JH=="null")
	{
		$Jhora_valor = "null";
	}
	else
	{
		$Jhora_valor = "'".$JH."'";
	}
	if ($VH=="null")
	{
		$Vhora_valor = "null";
	}
	else
	{
		$Vhora_valor = "'".$VH."'";
	}
	
	
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[rutasPlantilla]
           ([idCliente]
           ,[lunesRuta]
           ,[lunesHora]
           ,[martesRuta]
           ,[martesHora]
           ,[miercolesRuta]
           ,[miercolesHora]
           ,[juevesRuta]
           ,[juevesHora]
           ,[viernesRuta]
           ,[viernesHora]
           ,[incidencia]
           ,[contacto])
     VALUES
           (".$idCliente."
           ,'".$LR."'
           ,".$Lhora_valor."
           ,'".$MR."'
           ,".$Mhora_valor."
           ,'".$XR."'
           ,".$Xhora_valor."
           ,'".$JR."'
           ,".$Jhora_valor."
           ,'".$VR."'
           ,".$Vhora_valor."
           ,'".$incidencia."'
		   ,'".$contacto."'
          )";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se podido insertar la ruta'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Ruta Insertada\n";
	}
	$mensaje .= $consulta;
	
	return $mensaje;	
}


function cargarRutasParaVincular($datosBBDD)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="select distinct(ruta) as ruta from (

SELECT distinct(lunesRuta) as ruta FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasPlantilla]
union
SELECT distinct(martesRuta) as ruta FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasPlantilla]
union
SELECT distinct(miercolesRuta) as ruta FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasPlantilla]
union
SELECT distinct(juevesRuta) as ruta FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasPlantilla]
union
SELECT distinct(viernesRuta) as ruta FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasPlantilla]
) as todo";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);		
	
	return $result;	
}



function verSiExisteVinculacion($datosBBDD,$idEmpleado,$ruta)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[rutasVinculaciones] where idConductor = ".$idEmpleado." or ruta = '".$ruta."'";
				
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	$row_count = sqlsrv_num_rows( $resultado );
	
	$importeUnitario=0;
	
	if ($row_count>0)//la vinculacion existe
	{
		return true;
	}
	else
	{	
		return false;
	}
}

function insertarVinculacionRutaConductor($datosBBDD,$idEmpleado,$ruta)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[rutasVinculaciones] (idConductor, ruta)
  	
  	values (".$idEmpleado.",'".$ruta."')";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar la vinculacion conductor-ruta.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Vinculacion Guardada");
	}
	
	return $mensaje;	
}

function cargarRutasVinculadasConductores($datosBBDD)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="SELECT t1.id, t1.[idConductor], (t2.nombre + ' ' +  t2.apellidos) as nombreCompleto, t1.[ruta]
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasVinculaciones] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t2
  on t1.idConductor = t2.id
  order by t2.nombre, t2.apellidos";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);		
	
	return $result;	
}


function eliminarVinculacionRutaConductor ($datosBBDD, $id)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[rutasVinculaciones]	 
	where id = ".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	if( $resultado === false)
	{	
		echo ("Error: \n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}
	sqlsrv_close($conn_sis);
	//return $resultado;
}

function cargarRutasAdicionales ($datosBBDD,$condicion)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT  *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasAdicionales]" . $condicion;
	
	/*if ($condicion!="")
	{
		$consulta .= " where ".$condicion;
	}*/
	
	//echo "\n".$consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}

function insertarRutasAdicionales($datosBBDD,$idCliente,$hora, $ruta,$contacto,$incidencia,$fecha)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//echo ("martes hora: ".$MH."\n");
	
	
	/*if ($LH=="null")
	{
		$Lhora_valor = "null";
	}
	else
	{
		$Lhora_valor = "'".$LH."'";
	}
	
	if ($MH=="null")
	{
		$Mhora_valor = "null";
	}
	else
	{
		$Mhora_valor = "'".$MH."'";
	}
	
	if ($XH=="null")
	{
		$Xhora_valor = "null";
	}
	else
	{
		$Xhora_valor = "'".$XH."'";
	}
	
	if ($JH=="null")
	{
		$Jhora_valor = "null";
	}
	else
	{
		$Jhora_valor = "'".$JH."'";
	}
	if ($VH=="null")
	{
		$Vhora_valor = "null";
	}
	else
	{
		$Vhora_valor = "'".$VH."'";
	}*/
	
	
	
	
	/*$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[rutasAdicionales]
           ([idCliente]
           ,[lunesRuta]
           ,[lunesHora]
           ,[martesRuta]
           ,[martesHora]
           ,[miercolesRuta]
           ,[miercolesHora]
           ,[juevesRuta]
           ,[juevesHora]
           ,[viernesRuta]
           ,[viernesHora]
           ,[incidencia]
           ,[contacto]
		   ,[fecha])
     VALUES
           (".$idCliente."
           ,'".$LR."'
           ,".$Lhora_valor."
           ,'".$MR."'
           ,".$Mhora_valor."
           ,'".$XR."'
           ,".$Xhora_valor."
           ,'".$JR."'
           ,".$Jhora_valor."
           ,'".$VR."'
           ,".$Vhora_valor."
           ,'".$incidencia."'
		   ,'".$contacto."'
		   ,'".$fecha."'
          )";*/
	
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[rutasAdicionales]
           ([idCliente]
           ,[ruta]
           ,[hora]           
           ,[incidencia]
           ,[contacto]
		   ,[fecha])
     VALUES
           (".$idCliente."
           ,'".$ruta."'
           ,'".$hora."'         
           ,'".$incidencia."'
		   ,'".$contacto."'
		   ,'".$fecha."'
          )";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se podido insertar la ruta'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Ruta Insertada\n";
	}
	$mensaje .= $consulta;
	
	return $mensaje;	
}

function modificarRutasAdicional ($datosBBDD,$id,$idCliente,$hora,$ruta,$contacto,$incidencia,$fecha)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	/*if ($Lhora==null)
	{
		$Lhora_valor = "null";
	}
	else
	{
		$Lhora_valor = "'".$Lhora."'";
	}
	
	if ($Mhora==null)
	{
		$Mhora_valor = "null";
	}
	else
	{
		$Mhora_valor = "'".$Mhora."'";
	}
	
	if ($Xhora==null)
	{
		$Xhora_valor = "null";
	}
	else
	{
		$Xhora_valor = "'".$Xhora."'";
	}
	
	if ($Jhora==null)
	{
		$Jhora_valor = "null";
	}
	else
	{
		$Jhora_valor = "'".$Jhora."'";
	}
	if ($Vhora==null)
	{
		$Vhora_valor = "null";
	}
	else
	{
		$Vhora_valor = "'".$Vhora."'";
	}
	
	
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[rutasAdicionales] 
	SET [idCliente] = ".$idCliente."
      ,[lunesRuta] = '".$Lruta."'
      ,[lunesHora] = ".$Lhora_valor."
      ,[martesRuta] = '".$Mruta."'
      ,[martesHora] = ".$Mhora_valor."
      ,[miercolesRuta] = '".$Xruta."'
      ,[miercolesHora] = ".$Xhora_valor."
      ,[juevesRuta] = '".$Jruta."'
      ,[juevesHora] = ".$Jhora_valor."
      ,[viernesRuta] = '".$Vruta."'
      ,[viernesHora] = ".$Vhora_valor."
      ,[incidencia] = '".$incidencia."'
      ,[contacto] = '".$contacto."'
	  ,[fecha] = '".$fecha."'
	
	where id=".$id;*/
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[rutasAdicionales] 
	SET [idCliente] = ".$idCliente."
      ,[hora] = '".$hora."'
      ,[ruta] = '".$ruta."'      
      ,[incidencia] = '".$incidencia."'
      ,[contacto] = '".$contacto."'
	  ,[fecha] = '".$fecha."'
	
	where id=".$id;
	//return ($consulta);
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el registro de la ruta'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = $consulta;
	}
	
	return $mensaje;	
}

function eliminarRutasAdicionalPorId($datosBBDD,$id)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[rutasAdicionales] where id = ".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el registro'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}



function cargarLasRutasDelDia($datosBBDD,$ruta)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select t2.subcliente, t2.nombre_franqueo,t1.* from (

select idCliente,
	case
		when DATENAME(weekday, GETDATE())='Lunes' then lunesRuta
		when DATENAME(weekday, GETDATE())='Martes' then martesRuta
		when DATENAME(weekday, GETDATE())='Miércoles' then miercolesRuta
		when DATENAME(weekday, GETDATE())='Jueves' then juevesRuta
		when DATENAME(weekday, GETDATE())='Viernes' then viernesRuta
	end as ruta,
	case
		when DATENAME(weekday, GETDATE())='Lunes' then lunesHora
		when DATENAME(weekday, GETDATE())='Martes' then martesHora
		when DATENAME(weekday, GETDATE())='Miércoles' then miercolesHora
		when DATENAME(weekday, GETDATE())='Jueves' then juevesHora
		when DATENAME(weekday, GETDATE())='Viernes' then viernesHora
	end as hora
	, incidencia, contacto, DATENAME(weekday, GETDATE()) as dia
from [".$datosBBDD->bbddBBDD."].[dbo].[rutasPlantilla]

UNION ALL
SELECT  [idCliente] ,[ruta],[hora],[incidencia],[contacto], 'adicional' as dia 
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasAdicionales]
 where fecha = FORMAT (getdate(), 'yyyy-MM-dd')

 ) as t1
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
 on t1.idCliente = t2.codigo

 where t1.ruta = '".$ruta."'

 order by t1.hora";
	
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	//while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function verRutaPorIdEmpleado($datosBBDD,$idEmpleado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasVinculaciones] where idConductor = ".$idEmpleado;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();	
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function guardarHistoricoRuta ($datosBBDD,$idCliente,$firmaValor,$idEmpleado,$horaRuta)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "  insert into [".$datosBBDD->bbddBBDD."].[dbo].[rutasHistorico] ([idCliente],[ruta],[hora],[incidencia],[contacto],firma,idEmpleado)  
 
 select t1.*,'".$firmaValor."', ".$idEmpleado." from (

select idCliente,
	case
		when DATENAME(weekday, GETDATE())='Lunes' then lunesRuta
		when DATENAME(weekday, GETDATE())='Martes' then martesRuta
		when DATENAME(weekday, GETDATE())='Miércoles' then miercolesRuta
		when DATENAME(weekday, GETDATE())='Jueves' then juevesRuta
		when DATENAME(weekday, GETDATE())='Viernes' then viernesRuta
	end as ruta,
	case
		when DATENAME(weekday, GETDATE())='Lunes' then lunesHora
		when DATENAME(weekday, GETDATE())='Martes' then martesHora
		when DATENAME(weekday, GETDATE())='Miércoles' then miercolesHora
		when DATENAME(weekday, GETDATE())='Jueves' then juevesHora
		when DATENAME(weekday, GETDATE())='Viernes' then viernesHora
	end as hora
	, incidencia, contacto
from [".$datosBBDD->bbddBBDD."].[dbo].[rutasPlantilla]

UNION ALL
SELECT  [idCliente] ,[ruta],[hora],[incidencia],[contacto]
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasAdicionales]
 where fecha = FORMAT (getdate(), 'yyyy-MM-dd')

 ) as t1
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
 on t1.idCliente = t2.codigo

 inner join [".$datosBBDD->bbddBBDD."].[dbo].[rutasVinculaciones] as t3
 on t3.ruta = t1.ruta

 where t1.idCliente = ".$idCliente." and t3.idConductor = ".$idEmpleado."  and t1.hora='".$horaRuta."'

 order by t1.hora";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	/*if( $resultado === false) 
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}*/

	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualiar el log'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;	
	
	//return $resultado;
}


function guardarHistoricoRutaNombre ($datosBBDD,$idCliente,$nombre, $dni,$idEmpleado,$horaRuta)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "  insert into [".$datosBBDD->bbddBBDD."].[dbo].[rutasHistorico] ([idCliente],[ruta],[hora],[incidencia],[contacto],nombrePersona, dniPersona,idEmpleado)  
 
 select t1.*,'".$nombre."', '".$dni."', '".$idEmpleado."' from (

select idCliente,
	case
		when DATENAME(weekday, GETDATE())='Lunes' then lunesRuta
		when DATENAME(weekday, GETDATE())='Martes' then martesRuta
		when DATENAME(weekday, GETDATE())='Miércoles' then miercolesRuta
		when DATENAME(weekday, GETDATE())='Jueves' then juevesRuta
		when DATENAME(weekday, GETDATE())='Viernes' then viernesRuta
	end as ruta,
	case
		when DATENAME(weekday, GETDATE())='Lunes' then lunesHora
		when DATENAME(weekday, GETDATE())='Martes' then martesHora
		when DATENAME(weekday, GETDATE())='Miércoles' then miercolesHora
		when DATENAME(weekday, GETDATE())='Jueves' then juevesHora
		when DATENAME(weekday, GETDATE())='Viernes' then viernesHora
	end as hora
	, incidencia, contacto
from [".$datosBBDD->bbddBBDD."].[dbo].[rutasPlantilla]

UNION ALL
SELECT  [idCliente] ,[ruta],[hora],[incidencia],[contacto]
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasAdicionales]
 where fecha = FORMAT (getdate(), 'yyyy-MM-dd')

 ) as t1
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
 on t1.idCliente = t2.codigo

 inner join [".$datosBBDD->bbddBBDD."].[dbo].[rutasVinculaciones] as t3
 on t3.ruta = t1.ruta

 where t1.idCliente = ".$idCliente." and t3.idConductor = ".$idEmpleado."  and t1.hora='".$horaRuta."'

 order by t1.hora";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	/*if( $resultado === false) 
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}*/

	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualiar el log'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Nombre y Dni Guardado");
	}
	
	return $mensaje;	
	
	//return $resultado;
}

function cargarDatosClienteRuta($datosBBDD,$idCliente, $idConductor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select t1.*,t2.subCliente, t2.direccion, t2.localidad from (

select idCliente,
	case
		when DATENAME(weekday, GETDATE())='Lunes' then lunesRuta
		when DATENAME(weekday, GETDATE())='Martes' then martesRuta
		when DATENAME(weekday, GETDATE())='Miércoles' then miercolesRuta
		when DATENAME(weekday, GETDATE())='Jueves' then juevesRuta
		when DATENAME(weekday, GETDATE())='Viernes' then viernesRuta
	end as ruta,
	case
		when DATENAME(weekday, GETDATE())='Lunes' then lunesHora
		when DATENAME(weekday, GETDATE())='Martes' then martesHora
		when DATENAME(weekday, GETDATE())='Miércoles' then miercolesHora
		when DATENAME(weekday, GETDATE())='Jueves' then juevesHora
		when DATENAME(weekday, GETDATE())='Viernes' then viernesHora
	end as hora
	, incidencia, contacto
from [".$datosBBDD->bbddBBDD."].[dbo].[rutasPlantilla]

UNION ALL
SELECT  [idCliente] ,[ruta],[hora],[incidencia],[contacto]
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasAdicionales]
 where fecha = FORMAT (getdate(), 'yyyy-MM-dd')

 ) as t1
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
 on t1.idCliente = t2.codigo

 inner join [".$datosBBDD->bbddBBDD."].[dbo].[rutasVinculaciones] as t3
 on t3.ruta = t1.ruta


 where t1.idCliente = ".$idCliente." and t3.idConductor = ".$idConductor." 

 order by t1.hora";
	
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	//while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function cargarPersonasRutasPorCliente($datosBBDD,$idCliente)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT distinct(t1.nombrePersona), t1.dniPersona
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasHistorico] as t1
  where t1.idCliente = ".$idCliente. " and t1.nombrePersona is not null";
	
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	//while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function cargarDniRuta($datosBBDD,$nombre)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT distinct(t1.dniPersona) as dni
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasHistorico] as t1
  where t1.nombrePersona ='".$nombre."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	//while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function verSiExisteRuta($datosBBDD,$idCliente,$idEmpleado,$horaRuta)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT id, fecha, hora
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasHistorico]
  where idCliente = ".$idCliente." and fecha = FORMAT (getdate(), 'yyyy-MM-dd') and idEmpleado = ".$idEmpleado."  and hora='".$horaRuta."'";
	
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	//while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function modificarHistoricoRutaFirma($datosBBDD,$id,$firmaValor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[rutasHistorico]
  set firma = '".$firmaValor."' where id = ".$id;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	//while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}
function modificarHistoricoRutaNombreDni($datosBBDD,$id,$nombre,$dni)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[rutasHistorico]
  set nombrePersona = '".$nombre."',  dniPersona = '".$dni."' where id = ".$id;
	
	 	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el registro de la ruta'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Nombre y dni Modificado";
	}
	
	return $mensaje;
}


function verSiTieneNombreYdniRuta($datosBBDD,$idCliente,$horaRuta)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
		
	$consulta = "SELECT id
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasHistorico]
  where idCliente = ".$idCliente." and fecha = FORMAT (getdate(), 'yyyy-MM-dd') and nombrePersona<>'' and hora='".$horaRuta."'";
	
	 	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el registro de la ruta'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		return $mensaje;
	}
	else
	{	
		$row_count = sqlsrv_num_rows( $resultado );
		sqlsrv_close($conn_sis);


		if ($row_count>0)
		{
			return  true;
		}
		else
		{
			return false;
		}
	}	
}


function cargarValorNombreYdniRuta($datosBBDD,$idCliente,$horaRuta)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
		
	$consulta = "SELECT nombrePersona, dniPersona
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasHistorico]
  where idCliente = ".$idCliente." and fecha = FORMAT (getdate(), 'yyyy-MM-dd') and nombrePersona<>'' and hora='".$horaRuta."'";
		
 	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	//while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}


	
function cargarRutasHistorico($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
		
	$consulta = "SELECT t1.*, t2.subcliente, t3.nombre, t3.apellidos  FROM [".$datosBBDD->bbddBBDD."].[dbo].[rutasHistorico] as t1
inner join [".$datosBBDD->bbddBBDD."].dbo.clientes as t2
on t1.idCliente = t2.codigo
inner join  [".$datosBBDD->bbddBBDD."].dbo.empleados as t3
on t1.idEmpleado = t3.id
order by t1.fecha desc, t1.hora desc, t2.subcliente";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();	
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
	
}

function cargarEmpleados($datosBBDD,$idEmpleado,$precioHora,$horasLaborales,$orden,$desc)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$condicion = "";	
	
	if ($idEmpleado != "" )
	{
		$condicion = "where ".empleado_id." = ".$idEmpleado;
	}
	
	if ($precioHora!="" and $condicion=="")
	{
		$condicion = "where ".empleado_precioHora. " = ".$precioHora;
	}
	else if ($precioHora!="")
	{
		$condicion = $condicion." and ".empleado_precioHora. " = '".$precioHora;
	}	
	
	if ($horasLaborales!="" and $condicion=="")
	{
		$condicion = "where ".empleado_horasLaborales. " = ".$$horasLaborales;
	}
	else if ($horasLaborales!="")
	{
		$condicion = $condicion." and ".empleado_horasLaborales. " = '".$$horasLaborales;
	}	
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[empleados] ".$condicion." order by ".$orden. " ".$desc;
	
	//echo ($consulta); 	
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}


function modificarRegistroEmpleado($datosBBDD, $idEmpleado,$nombre,$apellidos,$precioHora,$horasLaborales)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[empleados]
	set nombre='".$nombre."'
	, apellidos = '".$apellidos."'
	, precioHora = ".$precioHora."
	, horasLaborales = '".$horasLaborales."'
	where id=".$idEmpleado;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar los datos del empleado'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Los datos del empleado con id: ".$idEmpleado. " (".$nombre." ".$apellidos.") han sido modificados.";
	}
	
	return $mensaje;	
}

	
function eliminarRegistroEmpleado($datosBBDD, $idEmpleado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="delete [".$datosBBDD->bbddBBDD."].[dbo].[empleados] where id=".$idEmpleado;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el empleado'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "El empleado con id: ".$idEmpleado. " ha sido eliminado";
	}
	
	return $mensaje;	
}



	
function insertarRegistroEmpleado ($datosBBDD,$nombre,$apellidos,$precioHora,$horasLaborales)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[empleados]
           ([nombre]
           ,[apellidos]           
           ,[precioHora]
           ,[horasLaborales])
     VALUES
           ('".$nombre."'
           ,'".$apellidos."'      
           ,".$precioHora."
           ,'".$horasLaborales."')"; 
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar el empleado'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Empleado insertado");
	}
	
	return $mensaje;	
	
	//return $resultado;
}


function cargarLogin($datosBBDD,$idEmpleado,$usuario)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$condicion = "";	
	
	if ($idEmpleado != "" )
	{
		$condicion = "where ".login_idEmpleado." = ".$idEmpleado;
	}
	
	if ($usuario!="" and $condicion=="")
	{
		$condicion = "where ".login_usuario. " = '".$usuario."'";
	}
	
	//$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[login] ".$condicion." order by ".$orden. " ".$desc;
	$consulta = "select id, idEmpleado, usuario from [".$datosBBDD->bbddBBDD."].[dbo].[login] ".$condicion. "order by usuario";
	
	//echo ($consulta); 	
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}


function modificarRegistroLogin($datosBBDD,$id,$idEmpleado,$usuario,$contrasena)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[login]
	set idEmpleado=".$idEmpleado."
	, usuario = '".$usuario."'
	, contrasena = CASE 
		WHEN '".$contrasena."' = ''
		THEN contrasena
		ELSE '".$contrasena."'
		END
	where id=".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	

	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar los datos del usuario'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Los datos del usuario con id: ".$id. " han sido modificados.";
	}
	
	return $mensaje;	
}


function eliminarRegistroUsuario($datosBBDD, $id)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="delete [".$datosBBDD->bbddBBDD."].[dbo].[login] where id=".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el empleado'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "El usuario con id: ".$id. " ha sido eliminado";
	}
	
	return $mensaje;	
}


function insertarRegistroUsuario ($datosBBDD,$idEmpleado,$usuarioNuevo,$contrasenaNuevo)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[login]
           ([idEmpleado]
           ,[usuario]     
           ,[contrasena])
     VALUES
           (".$idEmpleado."
           ,'".$usuarioNuevo."'      
           ,'".$contrasenaNuevo."')"; 
	
	
	
	$resultado  = sqlsrv_query($conn_sis, $consulta);
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar el usuario'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$consulta = "SELECT SCOPE_IDENTITY() AS [SCOPE_IDENTITY]";	
		
		$stmt  = sqlsrv_query($conn_sis, $consulta);
		
		$mensaje="";

		if( sqlsrv_fetch($stmt)  === false ) 
		{
			$mensaje = "Error: no se ha podido ver la id del usuario insertado'.\n".$stmt ."\n".$consulta."\n";
			$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		}
		else
		{

			$mensaje = sqlsrv_get_field($stmt , 0);
			$mensaje = $mensaje."|||Usuario insertado";

		}
	}
	
	sqlsrv_close($conn_sis);
	
	return $mensaje;	
	
	//return $resultado;
}

function modificarPermisos($datosBBDD,$idLogin,$pms_administracion,$pms_pda,$pms_pda_gestion,$pms_pdaAdjunto,$pms_informesProduccion,$pms_presupuestos,$pms_nuevoProcesoPresu,$pms_cambiarFechaCompromisoPresu,$pms_cambiarFechaAceptacionPresu,$pms_otBajada,$pms_otAbierta,$pms_otTerminada,$pms_otBajadaAutomatico,$pms_prodOt,$pms_admContabilidad,$pms_prodGrabarFranqueo,$pms_prodFranqueoF12,$pms_actualizarDatos,$pms_presupuestoMensual,$pms_rutas,$pms_pdaConductor, $pms_admFacturacion)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="UPDATE [".$datosBBDD->bbddBBDD."].[dbo].[permisos]
   SET  
      [administracion] = ".$pms_administracion."
      ,[pda] = '".$pms_pda."'
      ,[pdaGestion] = ".$pms_pda_gestion."
      ,[informesProduccion] = ".$pms_informesProduccion."
      ,[pdaAdjunto] = ".$pms_pdaAdjunto."
      ,[presupuestos] = ".$pms_presupuestos."
      ,[nuevoProcesoPresu] = ".$pms_nuevoProcesoPresu."
      ,[cambiarFechaCompromisoPresu] = ".$pms_cambiarFechaCompromisoPresu."
      ,[cambiarFechaAceptacionPresu] = ".$pms_cambiarFechaAceptacionPresu."
      ,[presuOtBajada] = ".$pms_otBajada."
      ,[presuOtAbierta] = ".$pms_otAbierta."
      ,[presuOtTerminada] = ".$pms_otTerminada."
      ,[otBajadaAutomatico] = ".$pms_otBajadaAutomatico."
      ,[ot] = ".$pms_prodOt."
      ,[admContabilidad] = ".$pms_admContabilidad."
	  ,[admFacturacion] = ".$pms_admFacturacion."
      ,[grabarFranqueo] = ".$pms_prodGrabarFranqueo."
      ,[franqueoF12] = ".$pms_prodFranqueoF12."
      ,[actualizarDatos] = ".$pms_actualizarDatos."
      ,[presupuestoMensual] = ".$pms_presupuestoMensual."
      ,[rutas] = ".$pms_rutas."
      ,[pdaConductor] = '".$pms_pdaConductor."'
     
 WHERE [id_usuario] = ".$idLogin;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar los permisos'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Permisos Modificados";
	}
	
	return $mensaje;	
}


function crearNuevoPermiso ($datosBBDD, $idLogin)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[permisos] (id_usuario) values (".$idLogin.")";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	/*if( $resultado === false) 
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}*/
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido crear los permisos'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Permisos Creados");
	}
	
	return $mensaje;

	//return $resultado;
}


function borrarPermisosUsuario($datosBBDD,$id)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[permisos] where id_usuario = ".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el registro'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;
}



function verSiExisteUsuario($datosBBDD,$usuario)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[login] where usuario = '".$usuario."'";
				
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	$row_count = sqlsrv_num_rows($resultado);
	sqlsrv_close($conn_sis);
	
	$mensaje = "111111111";
	
	if ($row_count>0)
	{		
		$mensaje = "true";
	}
	else
	{
		$mensaje = "false";
		
	}
	
	
	return $mensaje;	
}

function eliminarAlbaran($datosBBDD,$id)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[albaranes] 
where id = '".$id."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido eliminar el Albarán'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Albarán Eliminado");
	}
	
	return $mensaje;	
}



function mostrarUltimaFechaAbono ($datosBBDD, $anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select max(fecha) as fecha from  [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anioSeleccionado."]";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function mostrarUltimaFechaFacturasCibeles ($datosBBDD, $anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select max(fecha) as fecha from  [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."]";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function mostrarUltimaFechaFacturasCibelesClayma ($datosBBDD, $anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select max(fecha) as fecha from  [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."]";	
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function mostrarPresupuestosCombinados ($datosBBDD,$idEmpleado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT distinct(presupuesto)
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal]
  where idEmpleado = ".$idEmpleado;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function verNumPresupuestosCombinados ($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT distinct(t1.presupuesto), t2.campana FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
on t1.presupuesto = t2.presupuesto
 where t1.factura =".$numFactura." order by t1.presupuesto";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verNumPresupuestosCombinadosClayma ($datosBBDD,$numFactura,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT distinct(t1.presupuesto), t2.campana FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClayma".$anioSeleccionado."] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
on t1.presupuesto = t2.presupuesto
 where t1.factura =".$numFactura." order by t1.presupuesto";	
		
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function verNumPresupuestosCombinadosTemporal ($datosBBDD,$usuario)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT distinct(t1.presupuesto), t2.descripcion as campana FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[facturasTemporal] as t2
on t1.presupuesto = t2.presupuesto
 where t1.idEmpleado = ".$usuario." order by t1.presupuesto";
	
	//echo $consulta;
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if( $resultado === false ) 
	{
     	die ("Error:\n".$resultado."\n".$consulta."\n");		
	}
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function mostrarFacturaPorNumero($datosBBDD,$numFactura,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] where numero = ".$numFactura." order by numero desc";
	
	//echo $consulta;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function mostrarFacturaPorNumeroClayma($datosBBDD,$numFactura,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] where numero = ".$numFactura." order by numero desc";
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}



function eliminarFacturaMensualPendiente($datosBBDD, $numFactura)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[facturasMensualesPendientes] where numeroFacturaOriginal = ".$numFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar la tabla temporal de las facturas'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}


function modificarObservacionFactura($datosBBDD,$numeroFactura,$observacion,$anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="UPDATE [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."]
   SET  
      [observaciones] = '".$observacion."'
     
 WHERE [numero] = ".$numeroFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar la observacion de la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Observacion Modificada";
	}
	
	return $mensaje;	
}	

function modificarObservacionFacturaClayma($datosBBDD,$numeroFactura,$observacion,$anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="UPDATE [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."]
   SET  
      [observaciones] = '".$observacion."'
     
 WHERE [numero] = ".$numeroFactura;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar la observacion de la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Observacion Modificada";
	}
	
	return $mensaje;	
}	


function modificarObservacionAbono($datosBBDD,$numAbono,$observacion,$observacionInterna,$anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="UPDATE [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anioSeleccionado."]
   SET  
      [observaciones] = '".$observacion."', [observacionesInternas] = '".$observacionInterna."'
     
 WHERE [numero] = ".$numAbono;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar la observacion de la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Observacion Modificada";
	}
	
	return $mensaje;	
}

function modificarObservacionAbonoClayma($datosBBDD,$numAbono,$observacion,$observacionInterna,$anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="UPDATE [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anioSeleccionado."]
   SET  
      [observaciones] = '".$observacion."', [observacionesInternas] = '".$observacionInterna."'
     
 WHERE [numero] = ".$numAbono;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar la observacion de la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Observacion Modificada";
	}
	
	return $mensaje;	
}	


function modificarObservacionPrespuesto($datosBBDD,$numeroPresupuesto,$observacion)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="UPDATE [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]
   SET  
      [observaciones2] = '".$observacion."'
     
 WHERE [presupuesto] = ".$numeroPresupuesto;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar la observacion del presupuesto'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Observacion Modificada";
	}
	
	return $mensaje;	
}


function verCertificadosGrabados($datosBBDD,$idCliente,$fechaInicio,$fechaFin)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
	
	if ($idCliente==0)
	{
		$consulta = "SELECT t2.subcliente, t1.idProducto, sum(unidades) as unidades, t1.importeUnitario, sum(t1.unidades*t1.importeUnitario) as total, t3.producto, t1.fecha  
FROM [".$datosBBDD->bbddBBDD."].[dbo].[CertificadosGrabados] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t2.codigo = t1.idCliente
inner join [".$datosBBDD->bbddBBDD."].[dbo].[certificadoProductos] as t3
on t3.id = t1.idProducto
where  fecha >= '".$fechaInicio1."' and fecha <= '".$fechaFin1."'
group by t1.idCliente, t2.subcliente, t1.idProducto, t1.importeUnitario, t3.producto, t1.fecha 
order by t1.idCliente, t1.fecha, t3.producto";
	}
	else
	{
		$consulta = "SELECT t2.subcliente, t1.idProducto, sum(unidades) as unidades, t1.importeUnitario, sum(t1.unidades*t1.importeUnitario) as total, t3.producto, t1.fecha  
FROM [".$datosBBDD->bbddBBDD."].[dbo].[CertificadosGrabados] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t2.codigo = t1.idCliente
inner join [".$datosBBDD->bbddBBDD."].[dbo].[certificadoProductos] as t3
on t3.id = t1.idProducto
where t1.idCliente = ". $idCliente ." and fecha >= '".$fechaInicio1."' and fecha <= '".$fechaFin1."'
group by t2.subcliente, t1.idProducto, t1.importeUnitario, t3.producto, t1.fecha 
order by t1.fecha, t3.producto";
	}
	
	//echo $consulta;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function verHistoricoSaldo($datosBBDD,$fecha,$idCliente)	
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	//$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
		
	$consulta = "SELECT saldoPostPF
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionDeFondo_movimientos]
  where id in (select max(id) FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionDeFondo_movimientos] where fecha <= '".$fecha."' and codigoCliente = ".$idCliente.")
  ";
	
  //echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	//echo $consulta;
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	return $result;
}


function modificarImporteEnClienteFac($datosBBDD,$idCliente,$importe)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[clientes]
  set fac_pfFijaImporte = ".$importe."
  where codigo = ".$idCliente;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el importe Fijo del cliente'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = $consulta;
	}
	
	return $mensaje;	
}

function modificarImporteEnClienteFacClayma($datosBBDD,$idCliente,$importe)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);		
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma]
  set fac_pfFijaImporte = ".$importe."
  where codigo = ".$idCliente;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el importe Fijo del cliente'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = $consulta;
	}
	
	return $mensaje;	
}


function mostrarListadoFacturasPendientesTotal($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select t1.*, tabla1.aPagar as totalCliente from (

select 'MANIPULADOS' as origen, CONVERT(varchar(250),t1.numero) as factura, t1.cliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.presupuesto as descripcion, t1.fecha, t1.precioTotal as importe, t1.aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, numeroFacturaCompleto
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t1
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
 on t1.cliente = t2.nombre_empresa
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
where formaPagoReal = '' or formaPagoReal is null or formaPagoReal = 'null' 

union

select 'MANIPULADOS' as origen, CONVERT(varchar(250),t1.numero) as factura, t1.cliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.presupuesto as descripcion, t1.fecha, t1.precioTotal as importe, t1.aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CLAYMA' as origen2, numeroFacturaCompleto
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t1
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
 on t1.cliente = t2.nombre_empresa
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
where formaPagoReal = '' or formaPagoReal is null or formaPagoReal = 'null' 

union

SELECT 'CORREOS' as origen, t1.numeroOficial as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, campana as descripcion, fecha, importe, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CORREOS' as origen2, '' as numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.codigoCliente = t2.codigo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
  where (formaPago is null or formaPago='') and (fechaPago is null or fechaPago='')
  
  
 union

SELECT 'ABONO' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, '' as numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null' 
  
  union 
  
 SELECT 'ABONO' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CLAYMA' as origen2, '' as numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosClaymaTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
	on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null'

	union 
	SELECT 'REC DIFERENCIAS' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2 , numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facRecTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where (t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null') and t1.serieFactura = 'RECT'

 union 
  
 SELECT 'REC SUSTITUCION' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facRecTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where (t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null') and t1.serieFactura = 'SUST'

	union 
	SELECT 'REC DIFERENCIAS' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facRecClaymaTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
	on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where (t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null') and t1.serieFactura = 'RECT'

 union 
  
 SELECT 'REC SUSTITUCION' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facRecClaymaTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
	on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where (t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null') and t1.serieFactura = 'SUST'
  
  ) as t1 

  left join (
  select cliente, sum(aPagar) as aPagar from (

select 'MANIPULADOS' as origen, CONVERT(varchar(250),t1.numero) as factura, t1.cliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.presupuesto as descripcion, t1.fecha, t1.precioTotal as importe, t1.aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, numeroFacturaCompleto
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t1
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
 on t1.cliente = t2.nombre_empresa
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
where formaPagoReal = '' or formaPagoReal is null or formaPagoReal = 'null' 

union

select 'MANIPULADOS' as origen, CONVERT(varchar(250),t1.numero) as factura, t1.cliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.presupuesto as descripcion, t1.fecha, t1.precioTotal as importe, t1.aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CLAYMA' as origen2, numeroFacturaCompleto
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t1
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
 on t1.cliente = t2.nombre_empresa
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
where formaPagoReal = '' or formaPagoReal is null or formaPagoReal = 'null' 

union

SELECT 'CORREOS' as origen, t1.numeroOficial as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, campana as descripcion, fecha, importe, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CORREOS' as origen2, '' as numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.codigoCliente = t2.codigo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
  where (formaPago is null or formaPago='') and (fechaPago is null or fechaPago='')
  
  
 union

SELECT 'ABONO' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, '' as numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null' 
  
  union 
  
 SELECT 'ABONO' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CLAYMA' as origen2, '' as numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosClaymaTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
	on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null'
  
	union 
	SELECT 'REC DIFERENCIAS' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facRecTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where (t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null') and t1.serieFactura = 'RECT'

 union 
  
 SELECT 'REC SUSTITUCION' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facRecTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where (t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null') and t1.serieFactura = 'SUST'

union 
	SELECT 'REC DIFERENCIAS' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facRecClaymaTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
	on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where (t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null') and t1.serieFactura = 'RECT'

 union 
  
 SELECT 'REC SUSTITUCION' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, numeroFacturaCompleto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facRecClaymaTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
	on t1.cliente = t2.nombre_empresa
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where (t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null') and t1.serieFactura = 'SUST'

  ) as t1  
  group by cliente
  ) as tabla1
  on tabla1.cliente = t1.cliente

  

  ". $condicion;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function mostrarListadoFacturasPendientesTotal_Sumatorio($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select  sum(aPagar) as aPagar, sum(importe) as importe from (

select 'MANIPULADOS' as origen, CONVERT(varchar(250),t1.numero) as factura, t1.cliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.presupuesto as descripcion, t1.fecha, t1.precioTotal as importe, t1.aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t1
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
 on t1.cliente = t2.subcliente
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
where formaPagoReal = '' or formaPagoReal is null or formaPagoReal = 'null' 

union

select 'MANIPULADOS' as origen, CONVERT(varchar(250),t1.numero) as factura, t1.cliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.presupuesto as descripcion, t1.fecha, t1.precioTotal as importe, t1.aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CLAYMA' as origen2
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t1
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
 on t1.cliente = t2.subcliente
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
where formaPagoReal = '' or formaPagoReal is null or formaPagoReal = 'null' 

union

SELECT 'CORREOS' as origen, t1.numeroOficial as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, campana as descripcion, fecha, importe, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CORREOS' as origen2
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.codigoCliente = t2.codigo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
  where formaPago is null or formaPago=''
  
  
  union

SELECT 'ABONO' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.cliente = t2.subcliente
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null' 
  
  union 
  
 SELECT 'ABONO' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CLAYMA' as origen2
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosClaymaTodosLosAnios] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
	on t1.cliente = t2.subcliente
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago
    where t1.formaPagoReal = '' or t1.formaPagoReal is null or t1.formaPagoReal = 'null'
  
  
  ) as t1  

  ". $condicion;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}


function mostrarListadoFacturas_Sumatorio($datosBBDD, $condicion, $anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select  sum(aPagar) as aPagar, sum(importe) as importe, sum(precioNeto) as precioNeto  from (

select 'MANIPULADOS' as origen, CONVERT(varchar(250),t1.numero) as factura, t1.cliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.presupuesto as descripcion, t1.fecha, t1.precioTotal as importe, t1.aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, t1.precioNeto
from [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] as t1
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
 on t1.cliente = t2.nombre_empresa
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago ".$condicion. "

union

SELECT 'ABONO' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, t1.precioNeto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.cliente = t2.subcliente
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago ".$condicion. "
  
  ) as t1 

  ";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function mostrarListadoFacturasClayma_Sumatorio($datosBBDD, $condicion, $anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select  sum(aPagar) as aPagar, sum(importe) as importe, sum(precioNeto) as precioNeto from (

select 'MANIPULADOS' as origen, CONVERT(varchar(250),t1.numero) as factura, t1.cliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.presupuesto as descripcion, t1.fecha, t1.precioTotal as importe, t1.aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, t1.precioNeto
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] as t1
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
 on t1.cliente = t2.nombre_empresa
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago ".$condicion. "

union

SELECT 'ABONO' as origen,  CONVERT(varchar(250),t1.numero) as factura, t2.subcliente as cliente, t2.codigo as idCliente, t2.codigo_saldo, t1.descripcion as descripcion, fecha, preciototal, aPagar, t2.domiciliada, t3.concepto as formaPago, t2.importePF as saldo, 'CIBELES' as origen2, t1.precioNeto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
	on t1.cliente = t2.subcliente
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
  on t3.id = t2.idFormaPago ".$condicion. "
  
  ) as t1 

  ";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}



/////////////////////////////SOLO TEMPORALMENTE/////////////////////////////////


function modificarTemporalTraspasoFactura($datosBBDD, $numFacturaSql)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[temporal_traspaso]
	set temporal1 = ".$numFacturaSql;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualiar el log'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;	
}


function verNumeroFacturaTemporal($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[temporal_traspaso]";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function insertarTemporalProvisionTraspaso($datosBBDD, $numPresu)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[temporal_traspaso_provision] 
insert into  [".$datosBBDD->bbddBBDD."].[dbo].[temporal_traspaso_provision] 
(

 [nombre_franqueo]
      ,[FechaCreacion]
      ,[presupuesto]
      ,[campana]
      ,[Orden de trabajo]
      ,[importe]
      ,[tipo]
      ,[cobrada]
      ,[fechaCobro]
      ,[formaPago])



SELECT t2.nombre_franqueo, t1.FechaCreacion, t1.presupuesto, t3.campana, 'OT '+t1.presupuesto as 'Order de trabajo', t1.importe*100, t4.tipo, 'Sí' as cobrada, t1.fechaCobro, t1.formaPago
  

  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].clientes as t2
  on t1.idCliente = t2.codigo

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t3
  on t1.presupuesto = t3.presupuesto

  inner join [".$datosBBDD->bbddBBDD."].[dbo].provisionesDeFondo_tipos as t4
  on t1.tipo = t4.id

  where t1.presupuesto = '".$numPresu."'";
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualiar el log'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;	
}



function modificarPresupuesto_clienteClayma($datosBBDD,$numPresupuesto, $nombreCliente, $direccion, $poblacion, $cp, $clayma1)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]
	set cliente = '".$nombreCliente."', direccion='".$direccion."', poblacion='".$poblacion."', cp='".$cp."', clayma=".$clayma1."
	where presupuesto = '".$numPresupuesto."'";
		
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualiar el cliente en la tabla de presupuestos'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;	
}



function modificarPFClienteClayma($datosBBDD,$numPresupuesto,$idCliente, $clayma1)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo]
	set idCliente = ".$idCliente.", clayma=".$clayma1."
	where presupuesto = '".$numPresupuesto."'";
	
		
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualiar el cliente en la tabla de Provisiones de Fondos'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;	
}



function insertarPrefacturaCabeceraTemporal($datosBBDD, $idCliente, $clayma, $usuario, $pedido, $cantidad, $formaPago, $campana, $detallada, $Neto, $iva, $total, $provisionTotal, $aPagar,$presupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[facturasTemporal]  ([idCliente],[usuario],[clayma],[pedido],[cantidad],[formaPago],[descripcion],[detallada],[precioNeto],[iva],[precioTotal],[provision],[aPagar],[presupuesto]) values (".$idCliente.",".$usuario.",".$clayma.",'".$pedido."',".$cantidad.",'".$formaPago."','".$campana."',".$detallada.",".$Neto.",".$iva.",".$total.",".$provisionTotal.",".$aPagar.",'".$presupuesto."')";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se podido guardar la cabecera'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Cabecera guardada para la combinacion");
	}
	
	return $mensaje;	
	
	//return $resultado;
}

function borrarPrefacturaCabeceraTemporal($datosBBDD, $usuario)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[facturasTemporal] where usuario=".$usuario;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar los datos antiguos de la cabecera'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Cabecera borrada");
	}
	
	return $mensaje;	
	
	//return $resultado;
}
function borrarPrefacturaCabeceraTemporal2($datosBBDD, $usuario,$presupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[facturasTemporal] where usuario=".$usuario." and presupuesto = '".$presupuesto."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar los datos antiguos de la cabecera'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Cabecera borrada");
	}
	
	return $mensaje;	
	
	//return $resultado;
}

function borrarPrefacturaCabeceraTemporal3($datosBBDD,$presupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[facturasTemporal] where presupuesto = '".$presupuesto."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar los datos antiguos de la cabecera'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Cabecera borrada");
	}
	
	return $mensaje;	
	
	//return $resultado;
}


function verCombinacionesSiMismoCliente($datosBBDD,$usuario)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT distinct(t3.codigo_saldo) as clientes, t3.nombre_empresa   FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal] as t1 
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
on t1.presupuesto = t2.presupuesto
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
on t2.cliente = t3.nombre_empresa
where t1.idEmpleado=".$usuario;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	
	return $result;
}

function verCombinacionesSiMismoClienteClayma($datosBBDD,$usuario)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT distinct(t3.codigo_saldo) as clientes, t3.nombre_empresa   FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesTemporal] as t1 
inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
on t1.presupuesto = t2.presupuesto
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t3
on t2.cliente = t3.nombre_empresa
where t1.idEmpleado=".$usuario;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	
	return $result;
}


function verSiHayDatosCombinacionPrefactura($datosBBDD,$usuario) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasTemporal] as t1 where t1.usuario=".$usuario;
	$consulta = "SELECT t1.*, t2.concepto as formaPagoTexto FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasTemporal] as t1 
inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t2
on t1.formaPago = t2.id
where t1.usuario=".$usuario;
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	if( $resultado === false ) 
	{
     	die ("Error:\n".$resultado."\n".$consulta."\n");		
	}	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function verSiHayDatosCombinacionPrefactura2($datosBBDD,$numPresupuesto,$usuario)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasTemporal] as t1 where t1.usuario=".$usuario." and t1.presupuesto = '".$numPresupuesto."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	
	return $result;
}


function modificarFacturaTemporal($datosBBDD, $idEmpleado, $numPresupuesto, $clayma, $idCliente, $pedido, $cantidad, $idFormaPago, $campana, $detallada, $neto, $iva, $total, $provision, $aPagar,$irpf)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasTemporal]
	set idCliente = ".$idCliente.
	", clayma=".$clayma.
	", pedido='".$pedido."'".
	", cantidad=".$cantidad.
	", formaPago=".$idFormaPago.
	", descripcion='".$campana."'".
	", detallada=".$detallada.
	", precioNeto=".$neto.
	", iva=".$iva.
	", irpf=".$irpf.
	", precioTotal=".$total.
	", provision=".$provision.
	", aPagar=".$aPagar.
	
		
		
		
		
	" where presupuesto = '".$numPresupuesto."' and usuario = ".$idEmpleado;
		
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
		
	$mensaje="";
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar los datos de la factura temporal'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;	
}

	
function sumatorioPreciosFacturasTemporal($datosBBDD,$usuario, $idCliente)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT sum(precioNeto) as neto, sum(iva) as iva, sum(precioNeto) * 0.21 as iva2, sum(precioTotal) as total, (sum(precioNeto)*0.21) + sum(precioNeto) as total2
	, sum(provision) as provision, sum(aPagar) as aPagar, sum(precioNeto) + sum(iva) as totalSinIrpf, sum(irpf) as irpf
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasTemporal]
where idCliente = ".$idCliente." and usuario = ".$usuario;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if( $resultado === false ) 
	{
     	die ("Error:\n".$resultado."\n".$consulta."\n");		
	}	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	return $result;
}

function verPedidoClientePresupuesto($datosBBDD, $numPresupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT pedcli FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] where presupuesto = '".$numPresupuesto."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	return $result;
}

function modificarPresupuestoPedidoNotasCliente($datosBBDD,$presupuesto,$pedido,$notas)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
		
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]
	set pedcli='".$pedido."', notaCibeles='".$notas."' where presupuesto = '".$presupuesto."'";
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Pedido del cliente Modificado");
	}
	
	return $mensaje;	
}


function verCodigoSaldoPorReferenciaFranqueo($datosBBDD,$referencia,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$anioActual = date('Y');
	
	$consulta = "select t1.codigo_saldo, sum(t2.importe) as importe, t2.comprobado  from [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t2
on t1.codigo = t2.idCliente
 where t2.referencia = '".$referencia."'
 group by t1.codigo_saldo, t2.comprobado";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function verCodigoSaldoPorNombreSubCliente($datosBBDD,$nombreSubcliente)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select codigo_saldo from [".$datosBBDD->bbddBBDD."].[dbo].[clientes] where subcliente = '".$nombreSubcliente."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function verCodigoSaldoPorNombreCliente($datosBBDD,$nombreCliente)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select codigo_saldo from [".$datosBBDD->bbddBBDD."].[dbo].[clientes] where nombre_empresa = '".$nombreCliente."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function verCodigoSaldoPorNombreClienteClayma($datosBBDD,$nombreCliente)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select codigo_saldo from [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] where nombre_empresa = '".$nombreCliente."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}


function modificarNumNoFactura($datosBBDD,$presupuesto, $observacion)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
		
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]
set numNoFactura = (SELECT isnull(max(numNoFactura)+1,1) as numeroNoFactura from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]), noSeFacturaObservaciones = '".$observacion."', numNoFacturaFecha=GETDATE()
where presupuesto = '".$presupuesto."'";
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{		
	}
	
	return $mensaje;	
}

function verUltimoNumeroNoFactura($datosBBDD)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	
	$consulta = "SELECT max(numNoFactura) as numeroNoFactura from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos]";
	
 	//echo ($consulta);
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}


function mostrarListadoFacturasPendienteAnterioresCibeles($datosBBDD, $anio)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select t1.*, '".$anio."' as anio, 'CIBELES' as origen from [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anio."] as t1 where t1.pagada=0 order by numero";
	 	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
		
	if( $resultado === false ) 
	{
    	die( print_r( sqlsrv_errors(), true));
	}	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))	
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}
function mostrarListadoFacturasPendientesAnterioresCibelesCD($datosBBDD, $anio)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select t1.*, '".$anio."' as anio, 'CIBELES - CD' as origen from [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos".$anio."] as t1 where t1.pagada=0 order by numero";
	 	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
		
	if( $resultado === false ) 
	{
    	die( print_r( sqlsrv_errors(), true));
	}	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))	
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function mostrarListadoFacturasPendientesAnterioresClayma($datosBBDD, $anio)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta = "select t1.*, '".$anio."' as anio, 'CLAYMA' as origen from [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anio."] as t1 where t1.pagada=0 order by numero";
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
		
	if( $resultado === false ) 
	{
    	die( print_r( sqlsrv_errors(), true));
	}	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))	
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;		
}

function modificarFacturasPendientesAnteriores($datosBBDD, $numFactura, $formaPago, $anio)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anio."] set formaPago='".$formaPago."', fechaPago=GETDATE(), pagada=1 where numero='".$numFactura."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	

	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}

function modificarFacturasPendientesAnterioresCD($datosBBDD, $numFactura, $formaPago, $anio)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos".$anio."] set formaPago='".$formaPago."', fechaPago=GETDATE(), pagada=1 where numero='".$numFactura."'";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	

	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}




function modificarFacturasPendientesAnterioresClayma($datosBBDD, $numFactura, $formaPago, $anio)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anio."] set formaPago='".$formaPago."', fechaPago=GETDATE(), pagada=1 where numero='".$numFactura."'";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	

	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;	
}

function verFacturasEspecialesInforme($datosBBDD,$idCliente,$fechaInicio,$fechaFin)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
	
	if ($idCliente==0)
	{
		$consulta = "select t2.subcliente, t1.concepto as producto, sum(unidades) as unidades, t1.precioUnitario as importeUnitario, sum(t1.unidades*t1.precioUnitario) as total, t1.fechaFacturacion as fecha
from [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspeciales] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t2.codigo = t1.idCliente
where  t1.fechaFacturacion  >= '".$fechaInicio1."' and t1.fechaFacturacion  <= '".$fechaFin1."'
group by t1.idCliente, t2.subcliente, t1.concepto, t1.precioUnitario, t1.fechaFacturacion 
order by t1.idCliente, t1.fechaFacturacion, t1.concepto";
	}
	else
	{
		$consulta = "SELECT t2.subcliente, t1.concepto as producto, sum(unidades) as unidades, t1.precioUnitario as importeUnitario, sum(t1.unidades*t1.precioUnitario) as total, t1.fechaFacturacion as fecha
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasEspeciales] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t2.codigo = t1.idCliente

where t1.idCliente = ".$idCliente." and fechaFacturacion >= '".$fechaInicio1."' and t1.fechaFacturacion  <= '".$fechaFin1."'
group by t2.subcliente, t1.concepto, t1.precioUnitario, t1.fechaFacturacion 
order by t1.fechaFacturacion, t1.concepto";
	}
	
	//echo $consulta;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	return $result;	
}

function verEstadoFacturacionFinMes($datosBBDD)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturarFechaActual]";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	return $result;	
}

function verEstadoFacturacionFinMesClayma($datosBBDD)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturarFechaActualClayma]";
		
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	return $result;	
}


function cambiarDatosFacturarFechaActual($datosBBDD, $activado, $fecha)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturarFechaActual] set [activado]=".$activado.", [fechaImprimir]='".$fecha."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el estado de la fecha fin de facturacion'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = $consulta;
	}
	
	return $mensaje;	
}

function cambiarDatosFacturarFechaActualClayma($datosBBDD, $activado, $fecha)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturarFechaActualClayma] set [activado]=".$activado.", [fechaImprimir]='".$fecha."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el estado de la fecha fin de facturacion'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = $consulta;
	}
	
	return $mensaje;
	
}

function eliminarPrefacturaMensual($datosBBDD,$numFactura,$anioSeleccionado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[facturasMensualesPendientes] where numeroFacturaOriginal = ".$numFactura." and anio=".$anioSeleccionado;
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar la prefactura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;
	
}

/*function verPresupuestoDelAbono($datosBBDD,$numAbono)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT isnull(t2.presupuesto,'') as presupuesto,t3.codigo_saldo, t1.*  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abono] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].clientes as t3
on t1.cliente = t3.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[facturas] as t2
on t1.factura = t2.numero
where t1.numero = ".$numAbono;
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	
	return $result;
	
}*/


function anularProvisionFondosDesdeComercial($datosBBDD,$idPF,$usuario)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] set cobrada = 4, borradaComercial='".$usuario."' where id = ".$idPF;
		
	//echo "Error: ".$consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido anular la provision de fondos'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Provision de fondo Modificada";
	}
	
	return $mensaje;
	
}

function modificarPresupuestoAnularNoFac($datosBBDD,$numNoFactura)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] set [numNoFactura] = null, noSeFacturaObservaciones=''
	 where numNoFactura= '".$numNoFactura."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido realizar la anulacion'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = $consulta;
	}
	
	return $mensaje;
}



function modificarObservacionNoFac($datosBBDD,$numNoFactura,$observacion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] set [noSeFacturaObservaciones] = '".$observacion."'
	 where numNoFactura= '".$numNoFactura."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la Observaciones'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = "Observacion";
	}
	
	return $mensaje;
}


function modificarNoFacturableProcesado($datosBBDD,$presupuesto,$procesado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] set [noFacProcesado] = ".$procesado."
	 where presupuesto= '".$presupuesto."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido cambiar el valor de 'Procesado'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = $consulta;
	}
	
	return $mensaje;
}
	


function verFacturasDomiciliadasEntreFechas($datosBBDD,$fechaInicio,$fechaFin)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 =  date("Y-m-d", strtotime($fechaInicio));
	$fechaFin1 =  date("Y-m-d", strtotime($fechaFin));
	
	$consulta = "SELECT t1.*  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].clientes as t2
on t1.cliente = t2.nombre_empresa and t2.codigo_saldo = t2.codigo
where t2.domiciliada=1 and fecha>='".$fechaInicio1."' and fecha <= '".$fechaFin1."'  and  (t1.formaPagoReal is null or t1.formaPagoReal= '')";
		
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function verAbonosDomiciliadasEntreFechas($datosBBDD,$fechaInicio,$fechaFin)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 =  date("Y-m-d", strtotime($fechaInicio));
	$fechaFin1 =  date("Y-m-d", strtotime($fechaFin));
	
		
	$consulta = "SELECT t1.*  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonosTodosLosAnios] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].clientes as t2
on t1.cliente = t2.nombre_empresa and t2.codigo_saldo = t2.codigo
where t2.domiciliada=1 and fecha>='".$fechaInicio1."' and fecha <= '".$fechaFin1."'  and  (t1.formaPagoReal is null or t1.formaPagoReal= '')";
	
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function verFacturasCorreosDomiciliadasEntreFechas($datosBBDD,$fechaInicio,$fechaFin)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 =  date("Y-m-d", strtotime($fechaInicio));
	$fechaFin1 =  date("Y-m-d", strtotime($fechaFin));
		
	$consulta = "SELECT t1.*, t2.codigo_saldo  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].clientes as t2
on t1.codigoCliente = t2.codigo 
where t2.domiciliada=1 and t1.fecha>='".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and  (t1.formaPago is null or t1.formaPago= '')";
	
	  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

	
function eliminarPreFacturasCorreos ($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] where numeroOficial like '19%' or numeroOficial like '20%' or numeroOficial like '21%' or numeroOficial like '22%'";
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] where numeroOficial like 'A%'";
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar las preFacturas'.\n".$consulta."\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje="Albaranes de Correos Borradas";
	}
	
	return $mensaje;
}

function cargarProveedores($datosBBDD,$condicion="")
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$condiciones = $condicion;
	
	//$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[clientes] ".$condiciones ." order by nombre_empresa";
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[proveedores] ".$condiciones;  //el order debe estar dentro de la variable $condiciones
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if( $resultado === false ) 
	{
     	die ("Error:\n".$resultado."\n".$consulta."\n");		
	}
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))	
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;
	
}

function cargarProveedorContactos($datosBBDD,$condicion)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
			
	$consulta = "SELECT t1.*, t2.sexo  FROM [".$datosBBDD->bbddBBDD."].[dbo].[proveedoresContactos] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[sexo] as t2
on t1.idSexo = t2.id ". $condicion;
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;
	
}



function modificarContactoProveedor($datosBBDD,$idContacto,$idSexo,$nombre,$apellidos,$departamento,$cargo,$telefono,$movil,$email,$comentario)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "UPDATE [".$datosBBDD->bbddBBDD."].[dbo].[proveedoresContactos]
   SET [idSexo] = ".$idSexo."
      ,[nombre] = '".$nombre."'
      ,[apellidos] = '".$apellidos."'
      ,[departamento] = '".$departamento."'
      ,[cargo] = '".$cargo."'
      ,[telefono] = '".$telefono."'
      ,[movil] = '".$movil."'
      ,[email] = '".$email."'
      ,[comentario] = '".$comentario."'
 WHERE id = ".$idContacto;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el contacto del proveedor.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Contacto Modificado";
	}
	
	return $mensaje;
}

function insertarContactoProveedor($datosBBDD,$idProveedor,$idSexo,$nombre,$apellidos,$departamento,$cargo,$telefono,$movil,$email,$comentario)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[proveedoresContactos] (idCliente, idSexo,nombre, apellidos, departamento, cargo, telefono, movil, email, comentario) values (".$idProveedor.",".$idSexo.",'".$nombre."','".$apellidos."','".$departamento."','".$cargo."','".$telefono."','".$movil."','".$email."','".$comentario."')";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar el contacto..\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Contacto Insertado");
	}
	
	return $mensaje;	
}



function eliminarContactoProveedor($datosBBDD,$idContacto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[proveedoresContactos] where id =" .$idContacto ;
		
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el contacto'.\n".$consulta."\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje="Contacto Borrado";
	}
	
	return $mensaje;
}


function modificarProveedor($datosBBDD,$idProveedor,$nombre,$nif,$servicio,$direccion,$localidad,$provincia,$cp,$precioComparado,$fechaAlta,$homologado1,$motivoDeshomologado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	//$fecha1 = date("d-m-Y", strtotime($fechaAlta));
	
	$consulta = "UPDATE [".$datosBBDD->bbddBBDD."].[dbo].[proveedores]
   SET [proveedor] = '".$nombre."'
      ,[servicio] = '".$servicio."'
      ,[direccion] = '".$direccion."'
      ,[localidad] = '".$localidad."'
      ,[provincia] = '".$provincia."'
      ,[cp] = '".$cp."'
      ,[nif] = '".$nif."'        
      ,[homologado] = ".$homologado1."
      ,[deshomologado] = '".$motivoDeshomologado."'
      ,[precioComparado] = '".$precioComparado."' 
 WHERE id = ".$idProveedor;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el proveedor.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Proveedor Modificado";
	}
	
	return $mensaje;
}


function insertarProveedor($datosBBDD,$nombre,$nif,$servicio,$direccion,$localidad,$provincia,$cp,$precioComparado,$telefono)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[proveedores]
           ([proveedor]
           ,[servicio]
           ,[direccion]
           ,[localidad]
           ,[provincia]
           ,[cp]
           ,[nif]
           ,[telefono]
           ,[fax]
           ,[fechaAlta]
           ,[homologado]
           ,[deshomologado]
           ,[precioComparado])
     VALUES
           ('".$nombre."'
           ,'".$servicio."'
           ,'".$direccion."'
           ,'".$localidad."'
           ,'".$provincia."'
           ,'".$cp."'
           ,'".$nif."'
           ,'".$telefono."'
           ,''
           ,GETDATE()
           ,1
           ,''
           ,'".$precioComparado."')";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//sqlsrv_close($conn_sis);
	
	$mensaje="";

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar el contacto..\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Contacto Insertado");
		$consulta = "SELECT SCOPE_IDENTITY() AS 'idProveedor'";
		
		$stmt  = sqlsrv_query($conn_sis, $consulta);
		
		$mensaje="";

		if( sqlsrv_fetch($stmt)  === false ) 
		{
			$mensaje = "Error: no se ha podido ver la id del proveedor'.\n".$stmt ."\n".$consulta."\n";
			$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		}
		else
		{

			$mensaje = sqlsrv_get_field($stmt , 0);
			//$mensaje = $mensaje."|||Usuario insertado";

		}
		sqlsrv_close($conn_sis);
	}	
	
	return $mensaje;
}

function mostrarSoloPresupuestos($datosBBDD,$condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT *
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] " . $condicion;
	
 	
	//echo $consulta;
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;	
}


function cargarTodoUnCliente($datosBBDD,$idCliente, $anio)
{
	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select tabla.*, tabla2.nombre_empresa from (

SELECT 'Facturas' as origen, 'cibeles' as origen2, CAST(t1.numero as varchar(10)) as numero, t1.cliente, t1.fecha, t1.precioNeto, t1.iva, t1.aPagar, t2.numero as numAbono, t3.codigo_saldo, t1.presupuesto 
   FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anio."] as t1
   inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
   on t1.cliente = t3.nombre_empresa
   left join   [".$datosBBDD->bbddBBDD."].[dbo].[abonosTodosLosAnios]  as t2
   on t1.numero = t2.factura and t2.anioFactura = ".$anio."
union
SELECT 'Abono' as origen, 'cibeles' as origen2, CAST(t1.numero as varchar(10)) as numero, t1.cliente, t1.fecha, t1.precioNeto, t1.iva, t1.aPagar, t1.factura as numFactura, t3.codigo_saldo, '' as presupuesto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anio."] as t1
   inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
   on t1.cliente = t3.nombre_empresa
  left join  [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t2
  on t1.factura = t2.numero and t1.anioFactura = t2.anioFactura
  
  union 

  SELECT 'Franqueo' as origen, 'cibeles' as origen2, numeroOficial as numero, t2.nombre_empresa, fecha, neto as precioNeto,iva, aPagar, null as numeroFactura, t2.codigo_saldo, campana as presupuesto
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].clientes as t2
  on t1.codigoCliente = t2.codigo 
  where t1.fecha >= '01-01-".$anio."' and t1.fecha <='31-12-".$anio."' and  t2.codigo_saldo = ".$idCliente."
 
  ) as tabla
   left join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as tabla2
  on tabla.codigo_saldo = tabla2.codigo

  where fecha >= '01-01-".$anio."' and fecha <='31-12-".$anio."' and tabla.codigo_saldo = ".$idCliente." 
  order by origen desc, origen2, len(numero),numero";
  
 
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function cargarTodoUnClienteClayma($datosBBDD,$idCliente, $anio)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select tabla.*, tabla2.nombre_empresa from (

SELECT 'Facturas' as origen, 'clayma' as origen2, t1.numero, t1.cliente, t1.fecha, t1.precioNeto, t1.iva, t1.aPagar, t2.numero as numAbono, t3.codigo_saldo, t1.presupuesto 
   FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anio."] as t1
     inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t3
   on t1.cliente = t3.nombre_empresa
   left join   [".$datosBBDD->bbddBBDD."].[dbo].[abonosClaymaTodosLosAnios]  as t2
   on t1.numero = t2.factura and t2.anioFactura = ".$anio."

union

   SELECT 'Abono' as origen, 'clayma' as origen2, t1.numero, t1.cliente, t1.fecha, t1.precioNeto, t1.iva, t1.aPagar, t1.factura as numFactura, t3.codigo_saldo, '' as presupuesto 
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anio."] as t1
   inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t3
   on t1.cliente = t3.nombre_empresa
  left join  [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t2
  on t1.factura = t2.numero and t1.anioFactura = t2.anioFactura


  ) as tabla
  
  left join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as tabla2
  on tabla.codigo_saldo = tabla2.codigo

  where fecha >= '01-01-".$anio."' and fecha <='31-12-".$anio."' and tabla.codigo_saldo = ".$idCliente." 
  order by origen desc, origen2, len(numero),numero";
  
 
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function mostrarAlmacenMovimientos($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT t1.*, t2.subcliente, t3.nombre as nombreProducto, t3.codigo, t3.cantidadTotal as disponible, t4.modalidad, t5.hueco, t6.idAlbaran
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Movimientos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t2.codigo = t1.idSubCliente
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Producto] as t3
  on t1.idProducto = t3.id
   inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Modalidad] as t4
  on t1.idModalidad = t4.id
   inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Huecos] as t5
  on t1.idHueco = t5.id
  left join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_AlbaranesDetalles] as t6
  on t1.id = t6.idMovimiento

  ". $condicion;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function mostrarAlmacenMovimientosParaExcel($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT t1.*, t2.subcliente, t3.nombre as nombreProducto, t3.codigo, t3.cantidadTotal as disponible, t4.modalidad, t5.hueco, t6.idAlbaran
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Movimientos] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t2.codigo = t1.idSubCliente
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Producto] as t3
	on t1.idProducto = t3.id
	 inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Modalidad] as t4
	on t1.idModalidad = t4.id
	 inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Huecos] as t5
	on t1.idHueco = t5.id
	inner join (select max(tMax.id) as id, tMax.idProducto from [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Movimientos] as tMax  group by tMax.idProducto ) as t7
	on t1.id = t7.id 
	left join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_AlbaranesDetalles] as t6
	on t1.id = t6.idMovimiento
  ". $condicion;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function mostrarAlmacenMovimientosParaExcelubicaciones($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT t1.*, t2.subcliente, t3.nombre as nombreProducto, t3.codigo, t3.cantidadTotal as disponible, t4.modalidad, t5.hueco, t6.idAlbaran
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Movimientos] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t2.codigo = t1.idSubCliente
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Producto] as t3
	on t1.idProducto = t3.id
	 inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Modalidad] as t4
	on t1.idModalidad = t4.id
	 inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Huecos] as t5
	on t1.idHueco = t5.id
	inner join (select max(tMax.id) as id, tMax.idProducto, tmax.idHueco from [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Movimientos] as tMax  group by tMax.idProducto, tMax.idHueco ) as t7
	on t1.id = t7.id 
	left join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_AlbaranesDetalles] as t6
	on t1.id = t6.idMovimiento
  ". $condicion;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function mostrarAlmacenProveedores($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Proveedor] ".$condicion;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function mostrarAlmacenHuecos($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Huecos] ".$condicion;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function mostrarAlmacenProductos($datosBBDD,$condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT t1.*, t2.subcliente FROM [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Producto] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.idSubCliente = t2.codigo".$condicion;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}



function insertarAlmacenProveedor($datosBBDD,$proveedor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Proveedor] (nombre)
  	
  	values ('".$proveedor."')";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar el nombre del proveedor.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Proveedor Guardado");
	}
	
	return $mensaje;	
}


function modificarAlmacenProveedor($datosBBDD,$id,$proveedor)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Proveedor] set [nombre]='".$proveedor."' where id=".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el nombre del proveedor'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Proveedor Actualizado";
	}
	
	return $mensaje;	
}

function modificarAlmacenHuecos($datosBBDD,$id,$hueco)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Huecos] set [hueco]='".$hueco."' where id=".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el nombre del hueco'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Hueco Actualizado";
	}
	
	return $mensaje;	
}

function insertarAlmacenProducto($datosBBDD,$idCliente, $producto, $codigo)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Producto] (nombre,idSubCliente,codigo, cantidadTotal)
  	
  	values ('".$producto."',".$idCliente.", '".$codigo."',0)";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar el nombre del producto.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Producto Guardado");
	}
	
	return $mensaje;	
}

function insertarAlmacenHueco($datosBBDD,$hueco)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "INSERT INTO [dbo].[almacen_Huecos] ([hueco]) VALUES ('".$hueco."')";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar el hueco.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Hueco Guardado");
	}
	
	return $mensaje;	
}


function cargarModalidadAlmacen($datosBBDD,$condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Modalidad] ".$condicion;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}


function cargarAlmacenesAlmacen($datosBBDD,$condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Almacenes] ".$condicion;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}


function insertarMovimientoAlmacen($datosBBDD, $almacen, $fecha, $cliente, $producto, $modalidad, $hueco, $cantidad, $observacion, $ot, $cantidadTotal)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1 = date("d-m-Y", strtotime($fecha));
	
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Movimientos]
           ([idSubCliente]
           ,[idProducto]
           ,[fecha]
           ,[idModalidad]
           ,[idHueco]
           ,[cantidad]
           ,[observaciones]
           ,[ot]
           ,[idAlmacen]
		   ,[cantidadTotal])
     VALUES
           (".$cliente."
           ,".$producto."
           ,'".$fecha1."'
           ,".$modalidad."
           ,".$hueco."
           ,".$cantidad."
           ,'".$observacion."'
           ,'".$ot."'
           ,".$almacen."
		   ,$cantidadTotal)";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar el movimiento.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Movimiento Guardado");
	}
	
	return $mensaje;	
}

function actualizarCantidadTotalAmacen($datosBBDD,$idProducto, $cantidad)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Producto] set [cantidadTotal]=[cantidadTotal]+".$cantidad." where id=".$idProducto;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el nombre del hueco'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "";
	}
	
	return $mensaje;	
}

function modificarProductoAlmacen($datosBBDD,$idProducto, $nombre, $codigo)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta=" update [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Producto] set nombre ='".$nombre."' , codigo='".$codigo."' where id = " .$idProducto;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el producto'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		//$mensaje = "Error: ". $consulta;
	}
	else
	{
		$mensaje = "Producto Modificado";
	}
	
	return $mensaje;	
}

function mostrarHuecosUtilizadosSalida($datosBBDD,$idProducto, $idSubCliente)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT distinct(t5.hueco), t5.id
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Movimientos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t2.codigo = t1.idSubCliente
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Producto] as t3
  on t1.idProducto = t3.id
   inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Modalidad] as t4
  on t1.idModalidad = t4.id
   inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Huecos] as t5
  on t1.idHueco = t5.id


  where t1.idProducto = ".$idProducto." and t1.idSubCliente = ".$idSubCliente." and t1.cantidadTotal>0 
  
  and  t1.id in (SELECT max(id)  FROM [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Movimientos]where idHueco = t1.idHueco and idProducto = t1.idProducto and idSubCliente = t1.idSubCliente)
  order by hueco";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function mostrarUltimoAlbaranAlmacen($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT id as 'ultimoId', secuencial, fecha FROM [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Albaranes] 
  where id in (select max(id) from [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Albaranes])";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

	
function insertarAlbaranAlmacen($datosBBDD, $numeroAlbaran, $secuencial, $fechaActual, $observaciones, $empresaEnvio, $direccionEnvio, $cpEnvio, $localidadEnvio, $provinciaEnvio)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1 = date("d-m-Y", strtotime($fechaActual));
	
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Albaranes]
           ([id]          
           ,[secuencial]
           ,[observaciones]
           ,[fecha]
		   ,[envioNombreEmpresa]
           ,[envioDireccion]
           ,[envioCp]
           ,[envioLocalidad]
           ,[envioProvincia])
     VALUES
           (".$numeroAlbaran."          
           ,'".$secuencial."'
           ,'".$observaciones."'
           ,'".$fecha1."'
		   ,'".$empresaEnvio."'
		   ,'".$direccionEnvio."'
		   ,'".$cpEnvio."'
		   ,'".$localidadEnvio."'
		   ,'".$provinciaEnvio."')";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar el albaran.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Albaran Guardado");
	}
	
	return $mensaje;	
}

function insertarAlbaranDetalle($datosBBDD, $numeroAlbaran, $idMovimiento)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta = "

INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[almacen_AlbaranesDetalles]
           ([idAlbaran]
           ,[idMovimiento])
     VALUES
           (".$numeroAlbaran."
           ,".$idMovimiento.")


";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);
		
	$mensaje="";
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar el albaran.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = (" -");
	}
	
	return $mensaje;	
}

function modificarValorAlbaranEnMovimientos($datosBBDD,$idMovimiento)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Movimientos] set albaran=1 where id=".$idMovimiento;
		
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el albaran en los movimientos'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "";
	}
	
	return $mensaje;	
}


function verAlbaranAlmacen($datosBBDD, $numAlbaran)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select t2.id as albaran, t2.observaciones,  sum(t3.cantidad) as cantidad, t4.nombre_empresa, t4.codigo_saldo, t4.direccion, t4.codigo_postal, t4.localidad, t4.provincia, t5.nombre, t5.codigo, t6.modalidad, t7.almacen, t2.envioNombreEmpresa, t2.[envioDireccion], t2.[envioCp], t2.[envioLocalidad], t2.[envioProvincia]
	from [".$datosBBDD->bbddBBDD."].[dbo].[almacen_AlbaranesDetalles] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Albaranes] as t2
	on t1.idAlbaran = t2.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Movimientos] as t3
	on t1.idMovimiento = t3.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t4
	on t4.codigo = t3.idSubCliente
	 inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Producto] as t5
	on t5.id = t3.idProducto
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Modalidad] as t6
	on t6.id = t3.idModalidad
	 inner join [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Almacenes] as t7
	on t7.id = t3.idAlmacen
	where t2.id = ".$numAlbaran."
	group by t2.id, t2.observaciones, t4.nombre_empresa, t4.codigo_saldo, t4.direccion, t4.codigo_postal, t4.localidad, t4.provincia, t5.nombre, t5.codigo, t6.modalidad, t7.almacen, t2.envioNombreEmpresa, t2.[envioDireccion], t2.[envioCp], t2.[envioLocalidad], t2.[envioProvincia]
	
	order by codigo";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function verDatosDeEnvioPorIdMovimientoAlmacen($datosBBDD, $idMovimiento)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="SELECT t1.*, t2.nombre_empresa, t2.direccion, t2.codigo_postal, t2.localidad, t2.provincia, t2.envio_nombre, t2.envio_domicilio, t2.envio_cp, t2.envio_poblacion, t2.envio_provincia
FROM [".$datosBBDD->bbddBBDD."].[dbo].[almacen_Movimientos] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
on t1.idSubCliente = t3.codigo
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t3.codigo_saldo = t2.codigo
where t1.id=".$idMovimiento;
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	
	return $result;
}

function cargarAnios($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select name, substring(name,9,len(name)-8) as anio  from [".$datosBBDD->bbddBBDD."].sys.tables 
where name like 'facturas20%'  and len(name)=12  and  substring(name,9,len(name)-8) >=2022";

	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	return $result;	
}

function mostrarListadoSaldosSumatorio($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT sum(importePF) as saldoTotal, sum(fac_pfFijaImporte) as importeFijoTotal FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientes] ".$condicion;

	//echo $consulta;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function mostrarListadoSaldosSumatorioClayma($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT sum(importePF) as saldoTotal FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] ".$condicion;

	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
	
	return $result;	
}

function verSumatorioMovimientosSaldo($datosBBDD,$fechaInicio, $fechaFin,$idCliente)	//con ajuste de saldo
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("Y-m-d", strtotime($fechaInicio));
	$fechaFin1 = date("Y-m-d", strtotime($fechaFin));
		
	$consulta = "SELECT sum(importe) as sumaMovimientos
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionDeFondo_movimientos]
  where codigoCliente = ".$idCliente." and fecha>= '".$fechaInicio1."' and fecha <='".$fechaFin1."' and informacionCuadre = 'Ajuste de Saldo' 
  ";	
	// echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	//echo $consulta;
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function verSumatorioMovimientosSaldoSinFranqueo($datosBBDD,$fechaInicio, $fechaFin,$idCliente)	
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("Y-m-d", strtotime($fechaInicio));
	$fechaFin1 = date("Y-m-d", strtotime($fechaFin));	
	
	$consulta = "SELECT sum(importe) as sumaMovimientosSinFranqueo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionDeFondo_movimientos]
  where codigoCliente = ".$idCliente." and fecha>= '".$fechaInicio1."' and fecha <='".$fechaFin1."'  and presupuesto != '' and formaPago != ''
  ";	
	 
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	//echo $consulta;
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function verMovimientosSaldoSinFranqueoUnCliente($datosBBDD,$fechaInicio, $fechaFin,$idCliente)	
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));	
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionDeFondo_movimientos]
  where codigoCliente = ".$idCliente." and fecha>= '".$fechaInicio1."' and fecha <='".$fechaFin1."'  and presupuesto != '' order by id 
  ";	
	 
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	//echo $consulta;
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function verEstadisticasFacturasPorAnio($datosBBDD, $anio, $orden)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select t9.codigo_saldo, t9.nombre_empresa, isnull(t9.factura,0) + isnull(t9.abono,0) as manipulado, isnull(numFactura,0) + isnull(numAbono,0) as numFacManipulado
, isnull(franqueo,0) as franqueo, isnull(numFacturasCorreos,0) as numFacturasCorreos
, isnull((isnull(t9.factura,0) + isnull(t9.abono,0))/nullif(isnull(numFactura,0),0),0) as mediaManipulado
, isnull(isnull(t9.franqueo,0)/nullif(isnull(numFacturasCorreos,0),0),0) as mediaFranqueo
from (
SELECT t1.codigo_saldo, t1.nombre_empresa
,(select sum(t2.precioNeto) from [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anio."] as t2 where t1.nombre_empresa = t2.cliente group by t2.cliente) as factura
,(select sum(t3.precioNeto) from [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anio."] as t3 where t1.nombre_empresa = t3.cliente group by t3.cliente ) as abono
,(select count(t7.numero) as numFactura from [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anio."] as t7 where t1.nombre_empresa = t7.cliente group by t7.cliente) as numFactura
,(select count(t8.numero) as numAbono  from [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anio."] as t8 where t1.nombre_empresa = t8.cliente group by t8.cliente ) as numAbono
, t6.neto as franqueo, t6.numFacturas as numFacturasCorreos

FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t1

left join (SELECT t5.codigo_saldo, isnull(sum(t4.neto),0) as neto, count(t4.codigoCliente) as numFacturas  
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t4

inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t5
on t4.codigoCliente = t5.codigo
where t4.fecha >= '01-01-".$anio."' and t4.fecha <= '31-12-".$anio."'
group by t5.codigo_saldo) as t6
on t6.codigo_saldo = t1.codigo_saldo
 
 where t1.codigo=t1.codigo_saldo

 ) as t9
 where (t9.numFactura > 0 or t9.numAbono > 0 or t9.numFacturasCorreos>0) 
 order by ".$orden;

	 
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	//echo $consulta;
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function verEstadisticasFacturasPorAnioClayma($datosBBDD, $anio, $orden)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	$consulta = "select t9.codigo_saldo, t9.nombre_empresa, isnull(t9.factura,0) + isnull(t9.abono,0) as manipulado, isnull(numFactura,0) + isnull(numAbono,0) as numFacManipulado

, isnull((isnull(t9.factura,0) + isnull(t9.abono,0))/nullif(isnull(numFactura,0),0),0) as mediaManipulado
, 0 as 'numFacturasCorreos', 0 as 'mediaFranqueo', 0 as 'franqueo'

from (
SELECT t1.codigo_saldo, t1.nombre_empresa
,(select sum(t2.precioNeto) from [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anio."] as t2 where t1.nombre_empresa = t2.cliente group by t2.cliente) as factura
,(select sum(t3.precioNeto) from [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anio."] as t3 where t1.nombre_empresa = t3.cliente group by t3.cliente ) as abono
,(select count(t7.numero) as numFactura from [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anio."] as t7 where t1.nombre_empresa = t7.cliente group by t7.cliente) as numFactura
,(select count(t8.numero) as numAbono  from [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anio."] as t8 where t1.nombre_empresa = t8.cliente group by t8.cliente ) as numAbono


FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t1


 
 where t1.codigo=t1.codigo_saldo

 ) as t9
 where (t9.numFactura > 0 or t9.numAbono > 0 )  
 order by ".$orden;	
	
	 
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	//echo $consulta;
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	
	return $result;
}


function mostrarFacturasAgenteComercial($datosBBDD,$condicion,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	$consulta = "select * from (

SELECT t3.nombre, t1.cliente, t1.numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'factura' as tipo, t1.liquidado  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.cliente = t2.nombre_empresa
inner join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
on t2.idComercial = t3.id


union


SELECT t3.nombre, t1.cliente, t1.numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'abono' as tipo, t1.liquidado  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anioSeleccionado."] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.cliente = t2.nombre_empresa
inner join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
on t2.idComercial = t3.id

) as t1 ".$condicion;
	
	/*$consulta = "SELECT t1.*, t2.codigo_saldo, 0 as origen, t3.numero as numAbono, t3.anioAbono
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] as t1
inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[abonosTodosLosAnios] as t3
on t3.factura = t1.numero and t3.anioFactura = ".$anioSeleccionado." ".$condicion;*/
	
	//echo ($consulta);
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function mostrarFacturasAgenteComercialClayma($datosBBDD,$condicion,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from (

SELECT t3.nombre, t1.cliente, t1.numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'factura' as tipo, t1.liquidado  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
on t1.cliente = t2.nombre_empresa
inner join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
on t2.idComercial = t3.id


union


SELECT t3.nombre, t1.cliente, t1.numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'abono' as tipo, t1.liquidado  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anioSeleccionado."] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
on t1.cliente = t2.nombre_empresa
inner join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
on t2.idComercial = t3.id

) as t1 ".$condicion;	
	
	//echo ($consulta);
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}


function mostrarFacturasAgenteComercialClaymaYcibeles($datosBBDD,$condicion,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta = "select * from (
	
	
	SELECT isnull(t3.nombre,'_NINGUNO') as nombre, t1.cliente, t1.numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'factura' as tipo, t1.liquidado  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] as t1
left join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
on t2.idComercial = t3.id


union


SELECT isnull(t3.nombre,'_NINGUNO') as nombre, t1.cliente, t1.numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'abono' as tipo, t1.liquidado  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anioSeleccionado."] as t1
left join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
on t2.idComercial = t3.id

union

SELECT isnull(t3.nombre,'_NINGUNO') as nombre, t1.cliente, t1.numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'facturaClayma' as tipo, t1.liquidado  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] as t1
left join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
on t2.idComercial = t3.id


union


SELECT isnull(t3.nombre,'_NINGUNO') as nombre, t1.cliente, t1.numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'abonoClayma' as tipo, t1.liquidado  FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anioSeleccionado."] as t1
left join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
on t2.idComercial = t3.id

) as t1 ".$condicion;
	
	//echo ($consulta);
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}


function modificarFactura_liquidado($datosBBDD, $numFactura,$anioSeleccionado, $liquidado) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."]
   set liquidado = ".$liquidado."  where numero = ".$numFactura;
  
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido modificar el campo Liquidado.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}

function modificarFacturaClayma_liquidado($datosBBDD, $numFactura,$anioSeleccionado, $liquidado) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."]
   set liquidado = ".$liquidado."  where numero = ".$numFactura;
  	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido modificar el campo Liquidado.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}

function modificarAbono_liquidado($datosBBDD, $numFactura,$anioSeleccionado, $liquidado) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anioSeleccionado."]
   set liquidado = ".$liquidado."  where numero = ".$numFactura;
  
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido modificar el campo Liquidado.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}

function modificarAbonoClayma_liquidado($datosBBDD, $numFactura,$anioSeleccionado, $liquidado) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anioSeleccionado."]
   set liquidado = ".$liquidado."  where numero = ".$numFactura;
  	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido modificar el campo Liquidado.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;
	
}

function verSiDestinoEsD1($datosBBDD, $codigoPostal)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="select * from [".$datosBBDD->bbddBBDD."].[dbo].[capitalesYadministraciones] where cp='".$codigoPostal."'";
			
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
	
}

function verDatosTarifa ($datosBBDD,$idTarifaProducto, $peso, $fecha)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1=  date("d-m-Y", strtotime($fecha));
	$anioSeleccionado = date("Y", strtotime($fecha));
	
	$consulta = "SELECT t1.*, t2.titulo 
from [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t1 
inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t2
on t1.idTarifasProducto = t2.id
where t1.idTarifasProducto = ".$idTarifaProducto." and t1.gramos=" .$peso;
	
	//$consulta = "SELECT * from [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] where idTarifasProducto = ".$idTarifaProducto." and gramos=" .$peso;
  	
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function cambiarDireccionadoPresupuesto($datosBBDD, $numPresupuesto) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta=" update t1
			set t1.idFormaPago = t3.id, t1.direccion = t2.direccion, t1.poblacion = t2.localidad, t1.cp = t2.codigo_postal
			from [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t1 
			inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
			  on t1.cliente = t2.nombre_empresa
			inner join [".$datosBBDD->bbddBBDD."].[dbo].[formaDePago] as t3
			  on t3.id = t2.idFormaPago
			  where t1.presupuesto = '".$numPresupuesto."'";
  
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido modificar los datos de la direccion.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;
	
}

function modificarValorPrefactura($datosBBDD, $numFactura,$valor,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anioSeleccionado."] set prefactura = ".$valor." where numero = ".$numFactura;
  	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido modificar el valor de la prefactura.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}

function modificarValorPrefacturaClayma($datosBBDD, $numFactura,$valor,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anioSeleccionado."] set prefactura = ".$valor." where numero = ".$numFactura;
  	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: No se ha podido modificar el valor de la prefactura.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}

function insertarFacturasDetalles($datosBBDD,$numFactura, $concepto, $unidades, $precioUnitario, $descripcion, $nota, $ordenTipo, $orden,$anioSeleccionado,$exentoIVA)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);

$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."] ([factura],[concepto],[unidades],[precio],[total],[descripcion],[notaCibeles],[ordenTipo],[orden],[exentoIVA]) VALUES (".$numFactura.",'".$concepto."',".$unidades.",".$precioUnitario.",".round($precioUnitario*$unidades,2).",'".$descripcion."','".$nota."',".$ordenTipo.",".$orden.",".$exentoIVA.")";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar el detalle de la prefactura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = $consulta;
	}
	
	return $mensaje;	
}

function cargarUnDetalleFactura($datosBBDD,$idDetalle,$anioSeleccionado,$clayma)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	if ($clayma==true)
	{
		$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetallesClayma".$anioSeleccionado."] where id = '".$idDetalle."'";
	}
	else
	{
		$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$anioSeleccionado."] where id = '".$idDetalle."'";
	}	

	//echo $consulta;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function modificarDetalleFactura ($datosBBDD, $idDetalle,$proceso,$descripcion,$nota,$unidad,$precio,$total,$anioSeleccionado,$clayma)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$clayma1="";
	if ($clayma==true)
	{
		$clayma1="Clayma";
	}
	
	$consulta="update t1 set  t1.concepto = '".$proceso."', t1.descripcion='".$descripcion."', t1.notaCibeles='".$nota."', t1.unidades=".$unidad.", t1.precio=".$precio.", t1.total=".$total."
	from [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$clayma1.$anioSeleccionado."] as t1 
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$clayma1.$anioSeleccionado."] as t2
	on t1.factura = t2.numero where t1.id = ".$idDetalle." and t2.prefactura=1;";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Detalle de la Factura (prefactura) Modificado");
	}
	
	return $mensaje;	
}

function eliminarDetalleFactura ($datosBBDD, $idDetalle,$anioSeleccionado, $clayma)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$clayma1="";
	if ($clayma==true)
	{
		$clayma1="Clayma";
	}	
	
	$consulta = "DELETE t1
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasDetalles".$clayma1.$anioSeleccionado."] t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$clayma1.$anioSeleccionado."] t2 
  on t1.factura = t2.numero 
	where t1.id = ".$idDetalle." and t2.prefactura = 1";
	//echo ("\n".$consulta."\n");
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	if( $resultado === false)
	{	
		echo ("\n".$consulta."\n");
		die( print_r( sqlsrv_errors(), true) );
	}
	sqlsrv_close($conn_sis);
	//return $resultado;
}


function modificarFactura($datosBBDD,$numFactura,$anioSeleccionado,$clayma,$pedido,$cantidad,$formaPago,$campana,$detallada,$neto,$iva,$irpf,$total,$aPagar)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$clayma1="";
	if ($clayma==true)
	{
		$clayma1="Clayma";
	}
		
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$clayma1.$anioSeleccionado."]
	set pedido='".$pedido."'".
	", cantidad=".$cantidad.
	", formaPago='".$formaPago."'".
	", descripcion='".$campana."'".
	", detallada=".$detallada.
	", precioNeto=".$neto.
	", iva=".$iva.
	", irpf=".$irpf.
	", precioTotal=".$total.
	", aPagar=".$aPagar.
		
		
	" where numero = '".$numFactura."' and prefactura = 1";
		
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
		
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar los datos de la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	//$mensaje = $consulta;
	return $mensaje;	
}



function convertirPrefacturaAFactura($datosBBDD,$numFactura,$anioSeleccionado,$clayma)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$clayma1="";
	if ($clayma==true)
	{
		$clayma1="Clayma";
	}	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$clayma1.$anioSeleccionado."]
	set prefactura =0 where numero = '".$numFactura."' and prefactura = 1";
		
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
		
	$mensaje="";
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar los datos de la factura'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	$mensaje = $consulta;
	return $mensaje;	
}

;
function cargarObservacionesClientesCompleto($datosBBDD,$condicion,$clayma)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
		
	if ($clayma===true || $clayma==="true" )
	{		
		$consulta = "SELECT t1.*, t2.* FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesObservacionesClayma] as t2
on t1.codigo = t2.idCliente
 ".$condicion;
	}
	else
	{
		
		$consulta = "SELECT t1.*, t2.* FROM [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesObservaciones] as t2
on t1.codigo = t2.idCliente
 ".$condicion;
	}
		
	//echo $consulta;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


//FRANQUEO
function cargarListadoFranqueoTipos($datosBBDD,$condicion, $anioSeleccionado) //se puede añadir cualquier campo en la consulta
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta = "SELECT t1.id, t1.fecha, t1.idCliente, t2.nombre_franqueo, t5.producto, t4.titulo, t1.ot, t1.unidades, t1.importe, t1.referencia, t1.txt, t1.comprobado,t4.idProductoPadre
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."]  as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes]  as t2
	on t2.codigo = t1.idCliente
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."]  as t3
	on t1.tipo = t3.tipos
	left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos]  as t4
	on t3.idTarifasProducto = t4.id
	left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre]  as t5
	on t4.idProductoPadre = t5.id ".$condicion;
	
	//echo $consulta;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}

function cargarTotalFranqueoIVA($datosBBDD)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[totalFranqueoTipos] order by tipoIva";
	//$consulta = "select subcliente from clientes order by subcliente";
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function insertarGrabacionFranqueoTipos($datosBBDD,$idCliente, $ot, $otSidi="", $fecha, $tipo, $cantidad, $importe, $referencia, $importeSinIva, $anioSeleccionado, $numSeguimiento="", $importado=0, $nombre="", $direccion="", $poblacion="", $cp="")
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1 = date("d-m-Y", strtotime($fecha));	
		
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] (idCliente, ot, otSidi, fecha, tipo, unidades, importe, referencia, importeSinIva, numSeguimiento, importado, nombre, direccion, poblacion, cp)
  
  	values (".$idCliente.",'".$ot."','".$otSidi."','".$fecha."','".$tipo."',".$cantidad.",".$importe.",'".$referencia."',".$importeSinIva.",'".$numSeguimiento."',".$importado.",'".$nombre."','".$direccion."','".$poblacion."','".$cp."')";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
		
	$mensaje="";
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar el registro de franqueo en franqueoTipo'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Registro Guardado");
	}
	
	return $mensaje;	
}

function insertarGrabacionFranqueoTiposEspeciales($datosBBDD,$idCliente, $ot, $fecha, $tipo, $totalEnvios, $importe,$gramos)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1 = date("d-m-Y", strtotime($fecha));	
	$anioActual = date('Y');
		
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] (idCliente, ot, fecha, tipo, unidades, importe, gramos,referencia,importeSinIva)
  
  	values (".$idCliente.",'".$ot."','".$fecha."','".$tipo."',".$totalEnvios.",".$importe.",".$gramos.",'',".$importe."/1.21)";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
		
	$mensaje="";
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar el registro de franqueo en franqueoTipo'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Registro Guardado");
	}
	
	return $mensaje;	
}

function mostrarHistoricoFranqueo($datosBBDD,$idEmpleado,$idProducto,$permisoF12,$fecha)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$anioSeleccionado =   date("Y", strtotime($fecha));
	
	if ($permisoF12==1 || $permisoF12==2)
	{
				
		$fecha1=  date("d-m-Y", strtotime($fecha));
		
		$consulta = "SELECT t1.*, t2.nombre_franqueo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioSeleccionado."] as t1

   inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.idCliente = t2.codigo where producto=".$idProducto." and fecha='".$fecha1."'  order by fecha desc, id desc";
		//echo $consulta;
	}
	else
	{
	
		$idDelEmpleado = "";
		if (strlen($idEmpleado)==1)
		{
			$idDelEmpleado = "0".$idEmpleado;
		}
		else if (strlen($idEmpleado)==2)
		{
			$idDelEmpleado =$idEmpleado;
		}
		else
		{
			die("Error: el id del empleado debe tener un maximo de 2 digitos");//en este caso habria que quitar un digito al secuencial y dárselo al idEmpleado
		}


		
		$consulta = "SELECT t1.*, t2.nombre_franqueo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioSeleccionado."] as t1

   inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.idCliente = t2.codigo
   where SUBSTRING(t1.referencia,11, 2) = '".$idDelEmpleado."' and t1.producto=".$idProducto." and (t1.comprobado=0 or t1.comprobado is null) order by t1.id desc";
	}
	
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function mostrarDatosFranqueoPorReferencia($datosBBDD,$referencia)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$anioActual = date('Y');
	
	$consulta = "select t1.*, t2.codigoSidi FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioActual."] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.idCliente = t2.codigo 
	where t1.referencia='".$referencia."'";


	

		
	//echo "\n".$consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}
	
function eliminarRegistroFranqueo($datosBBDD,$id,$referencia,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	//$anioActual = date('Y');
		
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioSeleccionado."] where id=".$id." and referencia='".$referencia."'";	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
		
	$mensaje="";
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el registro'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Registro Eliminado");
	}
	
	return $mensaje;	
}

function eliminarRegistrosFranqueoTipos($datosBBDD,$referencia,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	

	$anioActual = date('Y');
		
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] where referencia='".$referencia."'";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
		
	$mensaje="";
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar la referencia'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Referencia Eliminada");
	}
	
	return $mensaje;	
}

function eliminarRegistrosFranqueoTiposPorId($datosBBDD,$id)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$anioActual = date('Y');
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] where id=".$id;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
		
	$mensaje="";
	
	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar la referencia'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Id Eliminada");
	}
	
	return $mensaje;	
}

function cargarDatosFranqueoTipoPorReferencia ($datosBBDD,$referencia,$anio)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
		
	$consulta = "select t1.*, t2.precioNeto + t2.iva as tarifa, t2.id as idTarifa FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anio."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anio."] as t2
  on t1.tipo = t2.tipos where t1.referencia='".$referencia."'";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function cargarTiposFranqueoPorProducto ($datosBBDD,$idProductoPadre)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$anioActual = date('Y');
	
	$consulta = "SELECT t1.id,t2.destino, t1.gramos, t1.tipos, t2.orden, t2.titulo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioActual."] as t1

INNER  join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t2
on t1.idTarifasProducto = t2.id

INNER join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t3
on t2.idproductoPadre = t3.id

where t3.id = ".$idProductoPadre."



UNION 

select id,'','',tipos,9,'' from [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioActual."] where idTarifasProducto is null

order by 5,6";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}	

function modificarFranqueoTipoPorId($datosBBDD,$id,$tipo,$unidades,$importe,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta=" update [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."]
  set tipo= '".$tipo."'
  , unidades= ".$unidades."
  , importe=".$importe."
   where id =".$id;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar la contraseña'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Franqueo modificado";
	}
	
	return $mensaje;
}


function verDatosFranqueoTipoPorReferencia($datosBBDD,$referencia,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$anioActual = date('Y');
	
	$consulta = "select t1.*, t3.titulo, t2.gramos, t2.descripcion from [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
on t1.tipo = t2.tipos 
left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3
on t2.idTarifasProducto = t3.id
where t1.referencia = '".$referencia."' order by t3.orden, t2.gramos";
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

        
function modificarFranqueoTipoPorReferencia($datosBBDD,$referencia,$idCliente,$fecha,$ot,$otSidi,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$anioActual = date('Y');
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."]
  set ot = '".$ot."'
  ,otSidi = '".$otSidi."'
  ,fecha = '".$fecha."'
  ,idCliente= ".$idCliente." 
   where referencia ='".$referencia."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar la contraseña'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Franqueo modificado";
	}
	
	return $mensaje;
}

function modificarFranqueoPorReferencia($datosBBDD,$referencia,$idCliente,$ot,$otSidi,$fecha,$importeTotal,$enviosTotal,$detalle,$anadidos,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$fecha1=  date("d-m-Y", strtotime($fecha));
	//$anioActual = date('Y');	
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioSeleccionado."]
  set fecha = '".$fecha1."'
  , idCliente= ".$idCliente." 
  , ot= '".$ot."' 
  , otSidi= '".$otSidi."'
  , importe= ".$importeTotal." 
  , envios= ".$enviosTotal." 
  , detalle= '".$detalle."'
  , anadidos = '".$anadidos."'
  
  
   where referencia ='".$referencia."'";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo "\n".$consulta;
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar la contraseña'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Franqueo modificado";
	}
	
	return $mensaje;
}


function borrarDatosFranqueoExportar($datosBBDD)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete from [".$datosBBDD->bbddBBDD."].[dbo].[franqueoExportarCorreos]";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar la tabla temporal de los albaranes de franqueo'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		
	}
	
	return $mensaje;	
}


function cambiarValorTxtEnFranqueo($datosBBDD, $referencia)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$anioActual = date('Y');
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioActual."] 
	set txt=1 
	
	where referencia='".$referencia."'";
			
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el valor del txt de la referencia: '".$referencia."\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Registro Actualizado";
	}
	
	return $mensaje;
}


function cambiarValorTxtEnFranqueoTipo($datosBBDD, $id)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$anioActual = date('Y');
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] 
	set txt=1 
	
	where id = ".$id;
		
	//echo $consulta;	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el valor del txt de la id: '".$id."\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Registro Actualizado2";
	}
	
	return $mensaje;
}


function mostrarDatosParaExportarAlbaranesCorreosFranqueo ($datosBBDD, $idProducto)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$hoy = getdate();
	//$fechaActual = date("d-m-Y", strtotime($hoy));
	$fechaActual = date('Y-m-d'); 
	$anioActual = date('Y');	

	//$fechaActual = '2025-04-30';
	
	$consulta = "SELECT t1.*, t3.idProductoPadre,t4.producto, t4.idAnexo, t4.idProducto,t2.gramos,t2.normalizado, t2.ambito, t2.anadidos
	, t2.ambito_SIDI, t2.gramos_SIDI, t4.idAnexo_SIDI, t4.idSIDI, t5.codigoSidi, t5.codigoSidiPre, cast(t1.importe*100 as int) as importeSidi, t6.anadidos as anadidos2, t5.nombre_empresa, t5.direccion, t5.localidad, t5.provincia, t5.codigo_postal
FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] as t1 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioActual."] as t2 
on t1.tipo = t2.tipos 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3 
on t3.id = t2.idTarifasProducto 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
on t4.id = t3.idProductoPadre  
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t5
on t5.codigo = t1.idCliente
left join [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioActual."] as t6
on t6.referencia = t1.referencia

where t1.fecha = '".$fechaActual."' and t1.comprobado = 0  and t1.txt = 0
  and t3.idProductoPadre = ".$idProducto."

 


UNION 


SELECT  t1.*, t3.idProductoPadre,t4.producto, t4.idAnexo, t4.idProducto,t2.gramos,t2.normalizado, t2.ambito, t2.anadidos
, t2.ambito_SIDI, t2.gramos_SIDI, t4.idAnexo_SIDI, t4.idSIDI, t5.codigoSidi, t5.codigoSidiPre, cast(t1.importe*100 as int) as importeSidi, t6.anadidos as anadidos2, t5.nombre_empresa, t5.direccion, t5.localidad, t5.provincia, t5.codigo_postal
FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] as t1 

left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioActual."] as t2 
on t1.tipo = t2.tipos 

left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3 
on t3.id = t2.idTarifasProducto 

left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
on t4.id = t3.idProductoPadre 
left join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t5
on t5.codigo = t1.idCliente 
 left join [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioActual."] as t6
on t6.referencia = t1.referencia

where t1.fecha = '".$fechaActual."' and t1.comprobado = 0 
  and t3.idProductoPadre is null
  and t1.referencia in (SELECT t1.referencia
FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] as t1 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioActual."] as t2 
on t1.tipo = t2.tipos 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3 
on t3.id = t2.idTarifasProducto 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
on t4.id = t3.idProductoPadre  
 

where t1.fecha = '".$fechaActual."' and t1.comprobado = 0  and t1.txt = 0
  and t3.idProductoPadre = ".$idProducto.")


order by t1.referencia, t1.id";	
  
	//echo "\n".$consulta."\n";
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function mostrarDatosParaExportarAlbaranesCorreosFranqueoPostF12 ($datosBBDD, $idProducto)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$hoy = getdate();
	//$fechaActual = date("d-m-Y", strtotime($hoy));
	$fechaActual = date('Y-m-d');
	$anioActual = date('Y');

	//$fechaActual = "25-11-2024";
	
	$consulta = "SELECT t1.*, t3.idProductoPadre,t4.producto, t4.idAnexo, t4.idProducto,t2.gramos,t2.normalizado, t2.ambito, t2.anadidos
	, t2.ambito_SIDI, t2.gramos_SIDI, t4.idAnexo_SIDI, t4.idSIDI, t5.codigoSidi, t5.codigoSidiPre, cast(t1.importe*100 as int) as importeSidi, t6.anadidos as anadidos2, t5.nombre_empresa, t5.direccion, t5.localidad, t5.provincia, t5.codigo_postal
FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] as t1 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioActual."] as t2 
on t1.tipo = t2.tipos 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3 
on t3.id = t2.idTarifasProducto 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
on t4.id = t3.idProductoPadre

inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t5
on t5.codigo = t1.idCliente 
 left join [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioActual."] as t6
on t6.referencia = t1.referencia

where t1.fecha = '".$fechaActual."' and t1.comprobado = 1
  and t3.idProductoPadre = ".$idProducto."

 


UNION 


SELECT  t1.*, t3.idProductoPadre,t4.producto, t4.idAnexo, t4.idProducto,t2.gramos,t2.normalizado, t2.ambito, t2.anadidos
, t2.ambito_SIDI, t2.gramos_SIDI, t4.idAnexo_SIDI, t4.idSIDI, t5.codigoSidi, t5.codigoSidiPre, cast(t1.importe*100 as int) as importeSidi, t6.anadidos as anadidos2, t5.nombre_empresa, t5.direccion, t5.localidad, t5.provincia, t5.codigo_postal
FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] as t1 

left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioActual."] as t2 
on t1.tipo = t2.tipos 

left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3 
on t3.id = t2.idTarifasProducto 

left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
on t4.id = t3.idProductoPadre 
left join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t5
on t5.codigo = t1.idCliente 
 left join [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioActual."] as t6
on t6.referencia = t1.referencia
 

where t1.fecha = '".$fechaActual."' and t1.comprobado = 1 
  and t3.idProductoPadre is null
  and t1.referencia in (SELECT t1.referencia
FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] as t1 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioActual."] as t2 
on t1.tipo = t2.tipos 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3 
on t3.id = t2.idTarifasProducto 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
on t4.id = t3.idProductoPadre  


where t1.fecha = '".$fechaActual."' and t1.comprobado = 1
  and t3.idProductoPadre = ".$idProducto.")


order by t1.referencia, t1.id";	
	
//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function insertarDatosFranqueoExportar ($datosBBDD, $uno, $dos,$tres,$cuatro,$cinco,$seis,$siete,$referencia,$idUnico,$orden)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[franqueoExportarCorreos] (uno, dos, tres, cuatro, cinco, seis, siete, referencia, idUnico, orden) values ('".$uno."','".$dos."','".$tres."','".$cuatro."','".$cinco."','".$seis."','".$siete."','".$referencia."',".$idUnico.",'".$orden."')";
	//echo "\n".$consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar la tabla Exportar'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;	
}

function mostrarDatosAlbaranesFranqueo ($datosBBDD)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	


	$anioActual = date('Y');

	$consulta = "SELECT t1.*, t2.ot, t2.otSidi
from [".$datosBBDD->bbddBBDD."].[dbo].[franqueoExportarCorreos] as t1
left join [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioActual."] as t2
on SUBSTRING(t1.referencia,1,20) = t2.referencia 
order by t1.referencia, t1.Idunico, t1.orden";
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function mostrarInformeFranqueoResumen ($datosBBDD,$idProducto, $fecha)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1=  date("d-m-Y", strtotime($fecha));
	$anioSeleccionado =  date("Y", strtotime($fecha));
	
	$consulta = "SELECT t2.nombre_franqueo as cliente, t3.producto, count(t1.idCliente) as albaranes, sum(t1.envios) as envios, sum(t1.importe) as importe, (  SELECT  sum(importe) as importe
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioSeleccionado."]
  where producto = ".$idProducto." and fecha = '".$fecha1."') as total
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioSeleccionado."] as t1
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes]  as t2
  on t1.idCliente = t2.codigo
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre]  as t3
  on t1.producto = t3.id
  
  where t1.producto = ".$idProducto." and t1.fecha = '".$fecha1."'

  group by t2.nombre_franqueo, t3.producto

  order by 1";
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function mostrarInformeFranqueoPorProductoYfecha ($datosBBDD,$idProducto, $fecha)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1=  date("d-m-Y", strtotime($fecha));
	$anioSeleccionado = date('Y', strtotime($fecha));
	
	/*$consulta = "SELECT t1.*, t2.producto as nombreProducto, t3.nombre_franqueo as nombre, t4.numAlbaranes, t4.importeTotal, t4.enviosTotal
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueo] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t2
  on t1.producto = t2.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente = t3.codigo

  inner join (  SELECT t1.idCliente, count(idCliente) as numAlbaranes, sum(importe) as importeTotal, sum(envios) as enviosTotal
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueo] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t2
  on t1.producto = t2.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente = t3.codigo
  where t1.fecha='".$fecha1."' and  t1.producto = ".$idProducto."
  group by  t1.idCliente
  ) as t4

  on t1.idCliente = t4.idCliente


  where t1.fecha='".$fecha1."' and  t1.producto = ".$idProducto."
  order by t1.idCliente";*/
	
	$consulta="SELECT t1.* , t2.producto as nombreProducto, t3.nombre_franqueo as nombre, 1 as numAlbaranes, t1.importe as importeTotal, t1.envios as enviosTotal
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioSeleccionado."] as t1
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t2
  on t1.producto = t2.id
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente = t3.codigo
 where t1.fecha='".$fecha1."' and  t1.producto =  ".$idProducto." order by t3.nombre_franqueo, t1.idCliente";
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function mostrarInformeFranqueoTotales ($datosBBDD,$idProducto, $fecha)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1 = date("d-m-Y", strtotime($fecha));
	$anioSeleccionado = date("Y", strtotime($fecha));
	
	$consulta = "SELECT  count(idCliente) as numAlbaranes, sum(importe) as importeTotal, sum(envios) as enviosTotal
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioSeleccionado."] as t1

  where t1.fecha='".$fecha1."' and  t1.producto = ".$idProducto;
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}



function mostrarInformeFranqueoPorProductoYfechaResumen ($datosBBDD,$idProducto, $fecha)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1=  date("d-m-Y", strtotime($fecha));
	$anioSeleccionado = date("Y", strtotime($fecha));
	
	$consulta = "SELECT  (t1.[tipo])
      ,sum(t1.[unidades]) as unidades
      ,sum(t1.[importe]) as importe         
      ,t2.[gramos] as gramos
	  ,t3.titulo
	  ,t4.producto

  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1

  inner join  [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t1.tipo = t2.tipos

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3
  on t2.[idTarifasProducto] = t3.id

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
  on t3.idProductoPadre = t4.id

  where fecha = '".$fecha1."'
  and t3.[idProductoPadre]=".$idProducto."
  

  group by t4.producto, t1.tipo, t3.orden,t2.[gramos],t3.titulo

  order by t4.producto, t3.orden";
  //and comprobado = 1
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function mostrarHistoricoFranqueoEnviosEspeciales($datosBBDD)
{
	$anioActual = date('Y');
	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select * FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] where tipo='1'  order by id desc";
		
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function ejecutarF12($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$fecha = date('d-m-Y');
	$anioActual = date('Y');
		
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioActual."] set comprobado = 1 where comprobado = 0";
		
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido pasar los datos a cibeles'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$conn_sis2=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		//$consulta2="update [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos] set comprobado = 1 where comprobado = 0 and fecha='".$fecha."' ";
		$consulta2="update [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] set comprobado = 1 where comprobado = 0";
		$resultado2 = sqlsrv_query($conn_sis2, $consulta2,array(),array("Scrollable"=>"buffered"));
	

		sqlsrv_close($conn_sis2);		

		$mensaje="";	

		if( $resultado2 === false ) 
		{
			$mensaje = "Error: no se ha podido pasar los datos a cibeles'.\n".$resultado2."\n".$consulta2."\n";
			$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		}
		else
		{
			
		}
	}
	
	return $mensaje;
}

function ejecutarF12PorReferencia($datosBBDD, $referencia,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$hoy = getdate();
	//$fecha=  date("d-m-Y", strtotime($hoy));
	$fecha = date('d-m-Y');
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioSeleccionado."] set comprobado = 1 where comprobado = 0 and referencia='".$referencia."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido pasar los datos a cibeles'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$conn_sis2=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		$consulta2="update [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] set comprobado = 1 where comprobado = 0 and referencia='".$referencia."'";
		$resultado2 = sqlsrv_query($conn_sis2, $consulta2,array(),array("Scrollable"=>"buffered"));
	
		//echo "Error: ". $consulta2;
		sqlsrv_close($conn_sis2);		

		$mensaje="";	

		if( $resultado2 === false ) 
		{
			$mensaje = "Error: no se ha podido pasar los datos a cibeles'.\n".$resultado2."\n".$consulta2."\n";
			$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		}
		else
		{
			
		}
	}
	
	return $mensaje;
}

function verConsumoFranqueoGrabadosPorClienteYfechas($datosBBDD,$idCliente,$fechaInicio,$fechaFin,$groupFechas="",$groupFechas1="",$ordenPorCodigoSaldo="", $ot="")	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("Y-m-d", strtotime($fechaInicio));
	$fechaFin1 = date("Y-m-d", strtotime($fechaFin));
	
	$anioSeleccionado = date("Y", strtotime($fechaInicio));	
	
	
	if ($groupFechas!="")
	{
		$groupFechas = 't1.fecha,';
	}
	if ($groupFechas1!="")
	{
		$groupFechas = '';
		$groupFechas1 = 't1.fecha,';
	}
	
	if ($idCliente=="todos" && $ordenPorCodigoSaldo==1)
	{
		$consulta = "SELECT ".$groupFechas.$groupFechas1." t3.nombre_empresa as nombreEmpresa, t1.ot, t2.descripcion, t2.gramos, sum(t1.importe)/sum(t1.unidades) as unitario, sum(t1.unidades) as unidades, sum(t1.importe) as importeTotal, sum(t1.importeSinIva) as importeTotalSinIva, sum(t1.[importeSinIva])/sum(t1.unidades) as unitarioSinIva, case sum(t1.importe) when  sum(t1.importeSinIva) then sum(t1.importe) else sum(t1.importe)/1.21 end as 'importeTotalSinIva2', t3.codigo_saldo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t2.tipos = t1.tipo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente = t3.codigo
  
  where t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t1.comprobado=1 and t1.ot like '%".$ot."%'
  
  group by  t3.nombre_empresa, ".$groupFechas." t1.ot, ".$groupFechas1." t2.descripcion, t2.gramos, t3.codigo_saldo
    order by t3.codigo_saldo, t3.nombre_empresa,  ".$groupFechas." t1.ot, ".$groupFechas1." t2.descripcion, t2.gramos
  ";
	}
	else if ($idCliente=="todos")
	{
		$consulta = "SELECT ".$groupFechas.$groupFechas1." t3.nombre_empresa as nombreEmpresa, t1.ot, t2.descripcion, t2.gramos, sum(t1.importe)/sum(t1.unidades) as unitario, sum(t1.unidades) as unidades, sum(t1.importe) as importeTotal, sum(t1.importeSinIva) as importeTotalSinIva, sum(t1.[importeSinIva])/sum(t1.unidades) as unitarioSinIva, case sum(t1.importe) when  sum(t1.importeSinIva) then sum(t1.importe) else sum(t1.importe)/1.21 end as 'importeTotalSinIva2', t3.codigo_saldo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t2.tipos = t1.tipo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente = t3.codigo
  
  where t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t1.comprobado=1 and t1.ot like '%".$ot."%'
  
  group by  t3.nombre_empresa, ".$groupFechas." t1.ot, ".$groupFechas1." t2.descripcion, t2.gramos, t3.codigo_saldo
    order by t3.nombre_empresa,  ".$groupFechas." t1.ot, ".$groupFechas1." t2.descripcion, t2.gramos
  ";
	}
	else
	{
		$consulta = "SELECT ".$groupFechas.$groupFechas1." t3.nombre_empresa as nombreEmpresa, t1.ot, t2.descripcion, t2.gramos, sum(t1.importe)/sum(t1.unidades) as unitario, sum(t1.unidades) as unidades, sum(t1.importe) as importeTotal, sum(t1.importeSinIva) as importeTotalSinIva, sum(t1.[importeSinIva])/sum(t1.unidades) as unitarioSinIva, case sum(t1.importe) when  sum(t1.importeSinIva) then sum(t1.importe) else sum(t1.importe)/1.21 end as 'importeTotalSinIva2', t3.codigo_saldo
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t2.tipos = t1.tipo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente = t3.codigo
  
  where t3.codigo_saldo = ".$idCliente." and t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."'  and t1.comprobado=1 and t1.ot like '%".$ot."%'
  
 group by  t3.nombre_empresa, ".$groupFechas." t1.ot, ".$groupFechas1." t2.descripcion, t2.gramos, t3.codigo_saldo
    order by t3.nombre_empresa,  ".$groupFechas." t1.ot, ".$groupFechas1." t2.descripcion, t2.gramos
  ";
	}	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function verConsumoFranqueoExtensionResumen($datosBBDD,$idCliente,$fechaInicio,$fechaFin)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
	
	$anioSeleccionado = date("Y", strtotime($fechaInicio));	
	
	if ($idCliente=="todos")
	{
		$consulta = "SELECT t3.nombre_empresa as nombreEmpresa, t1.ot, sum(t1.importe)/sum(t1.unidades) as unitario, sum(t1.unidades) as unidades, sum(t1.importe) as importeTotal, sum(t1.importeSinIva) as importeTotalSinIva, sum(t1.[importeSinIva])/sum(t1.unidades) as unitarioSinIva, case sum(t1.importe) when  sum(t1.importeSinIva) then sum(t1.importe) else sum(t1.importe)/1.21 end as 'importeTotalSinIva2'
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t2.tipos = t1.tipo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente = t3.codigo
  
  where t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t1.comprobado=1
  
  group by  t3.nombre_empresa, t1.ot
    order by t3.nombre_empresa, t1.ot
  ";
	}
	else
	{
		$consulta = "SELECT t3.nombre_empresa as nombreEmpresa, t1.ot, sum(t1.importe)/sum(t1.unidades) as unitario, sum(t1.unidades) as unidades, sum(t1.importe) as importeTotal, sum(t1.importeSinIva) as importeTotalSinIva, sum(t1.[importeSinIva])/sum(t1.unidades) as unitarioSinIva, case sum(t1.importe) when  sum(t1.importeSinIva) then sum(t1.importe) else sum(t1.importe)/1.21 end as 'importeTotalSinIva2'
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t2.tipos = t1.tipo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente = t3.codigo
  
  where t3.codigo_saldo = ".$idCliente." and t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."'  and t1.comprobado=1
  
 group by t3.nombre_empresa, t1.ot
    order by t3.nombre_empresa, t1.ot
  ";
	}	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function verConsumoFranqueoGrabadosPorClienteYfechasExtension($datosBBDD,$idCliente,$fechaInicio,$fechaFin,$extension,$ot)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
	$anioSeleccionado = date("Y", strtotime($fechaInicio));
	
	$consulta = "SELECT t3.nombre_empresa as nombreEmpresa, t1.ot, t2.descripcion, t2.gramos, sum(t1.importe)/sum(t1.unidades) as unitario, sum(t1.unidades) as unidades, sum(t1.importe) as importeTotal, sum(t1.importeSinIva)/sum(t1.unidades) as unitarioSinIva, sum(t1.importeSinIva) as importeTotalSinIva, case sum(t1.importe) when  sum(t1.importeSinIva) then sum(t1.importe) else sum(t1.importe)/1.21 end as 'importeTotalSinIva2'
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t2.tipos = t1.tipo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente = t3.codigo
  
  where t1.idCliente = ".$idCliente." and t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and ot like '%".$extension."%' and ot like '%".$ot."%' and t1.comprobado=1
  
  group by t3.nombre_empresa, t1.ot, t2.descripcion, t2.gramos
    order by t3.nombre_empresa, t1.ot, t2.descripcion, t2.gramos
  ";	
	
	/*$consulta = "SELECT t3.nombre_empresa as nombreEmpresa, t1.ot, t2.descripcion, t2.gramos, sum(t1.importe)/sum(t1.unidades) as unitario, sum(t1.unidades) as unidades, sum(t1.importe) as importeTotal, sum(t1.importeSinIva)/sum(t1.unidades) as unitarioSinIva, sum(t1.importeSinIva) as importeTotalSinIva, case sum(t1.importe) when sum(t1.importeSinIva) then sum(t1.importe) else sum(t1.importe)/1.21 end as 'importeTotalSinIva2' 
FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos] as t1 
inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas] as t2 on t2.tipos = t1.tipo 
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3 on t1.idCliente = t3.codigo 
where t3.codigo=2581 and t1.fecha >= '01-09-2022' and t1.fecha <= '30-09-2022' and ot like '%%' and ot like '%%' and t1.comprobado=1 
group by t3.nombre_empresa, t1.ot, t2.descripcion, t2.gramos 

order by t3.nombre_empresa, t1.ot, t2.descripcion, t2.gramos";*/
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function verConsumoFranqueoGrabadosPorSubClienteYfechas($datosBBDD,$idCliente,$fechaInicio,$fechaFin)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
	$anioSeleccionado = date("Y", strtotime($fechaInicio));
	
	if ($idCliente=="todos")
	{
		$consulta = "SELECT t3.nombre_empresa, t3.subcliente, t1.ot, t2.descripcion, t2.gramos, sum(t1.importe)/sum(t1.unidades) as unitario, sum(t1.unidades) as unidades, sum(t1.importe) as importeTotal, sum(t1.importeSinIva)/sum(t1.unidades) as unitarioSinIva, sum(t1.importeSinIva) as importeTotalSinIva
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t2.tipos = t1.tipo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente = t3.codigo
  
  where t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t1.comprobado=1
  
  group by t3.subcliente, t1.ot, t2.descripcion, t2.gramos,t3.nombre_empresa
    order by t3.subcliente, t1.ot, t2.descripcion, t2.gramos
  ";
	}
	else
	{
		$consulta = "SELECT t3.nombre_empresa, t3.subcliente, t1.ot, t2.descripcion, t2.gramos, sum(t1.importe)/sum(t1.unidades) as unitario, sum(t1.unidades) as unidades, sum(t1.importe) as importeTotal, sum(t1.importeSinIva)/sum(t1.unidades) as unitarioSinIva, sum(t1.importeSinIva) as importeTotalSinIva
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t2.tipos = t1.tipo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente = t3.codigo
  
  where t1.idCliente = ".$idCliente." and t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."'  and t1.comprobado=1
  
  group by t3.subcliente, t1.ot, t2.descripcion, t2.gramos,t3.nombre_empresa
    order by t3.subcliente, t1.ot, t2.descripcion, t2.gramos
  ";
	}	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function verConsumoFranqueoGrabadosPorOT($datosBBDD,$OT, $groupFecha,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$groupFecha1 = '';
	if ($groupFecha!="")
	{
		$groupFecha1 = 't1.fecha,';
	}
	 
	//$anioSeleccionado = substr($OT, 0,2) + 2000;
	
	$consulta = "SELECT ".$groupFecha1." t3.subcliente, t1.ot, t2.descripcion, t2.gramos, case when sum(t1.unidades)<=0 then 0 else sum(t1.importe)/sum(t1.unidades) end as unitario, sum(t1.unidades) as unidades, sum(t1.importe) as importeTotal, case when sum(t1.unidades)>=0 then 0 else  sum(t1.importeSinIva)/sum(t1.unidades) end as unitarioSinIva, sum(t1.importeSinIva) as importeTotalSinIva
  , t4.campana2
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t2.tipos = t1.tipo
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente = t3.codigo

  left join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t4
  on t4.presupuesto = substring(t1.ot,4,7) 
  
  where ot like '%".$OT."%' and comprobado=1
  
  group by ".$groupFecha1." t3.subcliente, t1.ot, t2.descripcion, t2.gramos, t4.campana2
    
	
	
  ";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function verImporteFranqueoPorFechasYcliente($datosBBDD,$idCliente,$fechaInicio,$fechaFin,$extension,$ot,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));	
	
	$consulta = "SELECT isnull(sum(importeSinIva),0) as importe  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."]
where comprobado = 1 
and fecha >= '".$fechaInicio1 ."'
and fecha <= '".$fechaFin1."'
and idCliente = ".$idCliente."
and ot like '%".$extension."%'

  ";
	//and ot like '%".$ot."%'
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function verImportesTotalesFranqueo($datosBBDD)	
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha = date("d-m-Y");	
	
	$anioActual = date('Y');	
	
	$consulta = "select idCliente, sum(importe) as importeTotal, t2.codigo_saldo as idCodigoSaldo 
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."]  as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.idCliente = t2.codigo
  	where t1.comprobado = 0
     group by t1.idCliente, t2.codigo_saldo
  order by t1.idCliente";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function verImportesTotalesFranqueoPorReferencia($datosBBDD,$referencia,$anioSeleccionado)	
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha = date("d-m-Y");	
	
	$consulta = "select idCliente, sum(importe) as importeTotal, t2.codigo_saldo as idCodigoSaldo 
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."]  as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.idCliente = t2.codigo
  	where t1.referencia = '".$referencia."'
     group by t1.idCliente, t2.codigo_saldo
  order by t1.idCliente";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

/*function verSumatorioPrecioFranqueoCibeles($datosBBDD, $idCliente,$fechaInicio,$fechaFin)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
	

	
	
	$consulta = "SELECT sum(t1.importe) as importe, sum(t1.importeSinIva) as importeSinIva, t2.subcliente
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.idCliente = t2.codigo
  where t1.idCliente = ".$idCliente." and t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t1.comprobado=1 
  group by t2.subcliente";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);
		
	
	return $result;
}*/

function verConsumoPorProductosFranqueo($datosBBDD,$fechaInicio, $fechaFin)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
	$anioSeleccionado = date("Y", strtotime($fechaInicio));	
	
	$consulta = "
	select * from (
	SELECT t4.producto, sum(t1.unidades) as 'unidades', sum(importe) as 'importe', sum(t1.unidades*t2.precioNeto) as sinIva, sum(t1.unidades*(t2.precioNeto+iva)) as conIva, t4.retribucionCorreos
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t1.tipo=t2.tipos
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3
  on t2.idTarifasProducto = t3.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
  on t3.idProductoPadre = t4.id

  where t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t1.comprobado=1 

  group by t4.producto, t4.retribucionCorreos
  
  union 

  SELECT ' Acuses', sum(t1.unidades) as 'unidades', sum(importe) as 'importe', sum(t1.unidades*t2.precioNeto) as sinIva, sum(t1.unidades*(t2.precioNeto+iva)) as conIva, 'CARTA' as retribucionCorreos
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t1.tipo=t2.tipos 

  where  t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t2.idTarifasProducto is null  and t2.tipos!='1'
	

	union 

  SELECT ' Envios Especiales', sum(t1.unidades) as 'unidades', sum(importe) as 'importe', sum(importe/1.21) as sinIva, sum(importe) as conIva, 'ENVIOS ESPECIALES' as retribucionCorreos
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t1.tipo=t2.tipos 

  where  t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t2.idTarifasProducto is null  and t2.tipos='1'
  )
as tabla order by retribucionCorreos, producto
  ";
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function verConsumoPorProductosFranqueo2 ($datosBBDD,$fechaInicio, $fechaFin)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("Y-m-d", strtotime($fechaInicio));
	$fechaFin1 = date("Y-m-d", strtotime($fechaFin));
	$anioSeleccionado = date("Y", strtotime($fechaInicio));	
	
	
	/*$consulta = "select 
  t4.producto2, t3.destino, sum(t1.unidades) as 'unidades', 
   sum(((t8.precioNetoReal*t1.unidades))-(isnull(t5.descuentoTantoPorCiento,0)*t8.precioNetoReal*t1.unidades/100)) as sinIva  
  , sum( (((t8.precioIvaReal)*t1.unidades))-(isnull(t5.descuentoTantoPorCiento,0)*(t1.unidades*(t8.precioIvaReal))/100)) as conIva
  
  ,t4.retribucionCorreos  
  , t7.sumaUnidades, t3.orden
 
  from [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
 on t1.tipo=t2.tipos
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3
  on t2.idTarifasProducto = t3.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
  on t3.idProductoPadre = t4.id
  
  inner join (SELECT tipos, case when iva>0 then (precioNeto+iva)/1.21
	else precioNeto end as precioNetoReal, precioNeto + iva as precioIvaReal     
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."]) as t8
  on t8.tipos = t2.tipos
  left join [".$datosBBDD->bbddBBDD."].[dbo].[franqueoDescuentoCorreos] as t5
  on t5.idTarifasProducto = t3.id   and t5.idTarifasProductoPadre = t4.id and t1.idCliente = t5.idCliente
   


 left join (
 select t4.producto2, sum(unidades) as sumaUnidades from [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
 on t1.tipo=t2.tipos
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3
  on t2.idTarifasProducto = t3.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
  on t3.idProductoPadre = t4.id
   where t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t1.comprobado=1
   group by t4.producto2
 ) as t7
 on t7.producto2 = t4.producto2

   where t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t1.comprobado=1    

   	group by  t4.retribucionCorreos, t4.producto2, t3.destino, t7.sumaUnidades, t3.orden

	union 

	  SELECT ' Acuses' as producto,t2.destinoAcuses as destino, sum(t1.unidades) as 'unidades', sum(t1.unidades*t4.precioNetoReal) as sinIva, sum(t1.unidades*(t4.precioIvaReal)) as conIva, 'CARTA' as retribucionCorreos
  ,t3.unidades as sumatorioUnidades, t2.destinoAcusesOrden as orden
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t1.tipo=t2.tipos
  inner join (SELECT tipos, case when iva>0 then (precioNeto+iva)/1.21
	else precioNeto end as precioNetoReal, precioNeto + iva as precioIvaReal     
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."]) as t4
  on t4.tipos = t2.tipos

  left join (
 SELECT 'acuses' as tipo, sum(t1.unidades) as 'unidades'

  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t1.tipo=t2.tipos
  where  t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t2.idTarifasProducto is null  and t2.tipos!='1' and t1.comprobado=1
  ) as t3
  on t3.tipo = 'acuses'

  where  t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t2.idTarifasProducto is null  and t2.tipos!='1' and t1.comprobado=1 
group by t2.destinoAcuses, t3.unidades,t2.destinoAcusesOrden

union 

SELECT ' Envios Especiales', 'NINGUNO', sum(t1.unidades) as 'unidades', sum(importe/1.21) as sinIva, sum(importe) as conIva, 'ENVIOS ESPECIALES' as retribucionCorreos, sum(t1.unidades) as 'sumaUnidades'
  , 1 as orden
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t1.tipo=t2.tipos 

  where  t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t2.idTarifasProducto is null  and t2.tipos='1' and t1.comprobado=1


  order by retribucionCorreos, producto2, orden";*/
	
	
	$consulta = "select 
  t4.producto2, sum(t1.unidades) as 'unidades', 
   sum(((t8.precioNetoReal*t1.unidades))-(isnull(t5.descuentoTantoPorCiento,0)*t8.precioNetoReal*t1.unidades/100)) as sinIva  
  , sum( (((t8.precioIvaReal)*t1.unidades))-(isnull(t5.descuentoTantoPorCiento,0)*(t1.unidades*(t8.precioIvaReal))/100)) as conIva
  
  ,t4.retribucionCorreos  
  , t7.sumaUnidades, t3.ordenInforme as orden
 
  from [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
 on t1.tipo=t2.tipos
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3
  on t2.idTarifasProducto = t3.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
  on t3.idProductoPadre = t4.id
  
  inner join (SELECT tipos, case when iva>0 then (precioNeto+iva)/1.21
	else precioNeto end as precioNetoReal, precioNeto + iva as precioIvaReal     
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."]) as t8
  on t8.tipos = t2.tipos
  left join [".$datosBBDD->bbddBBDD."].[dbo].[franqueoDescuentoCorreos] as t5
  on t5.idTarifasProducto = t3.id   and t5.idTarifasProductoPadre = t4.id and t1.idCliente = t5.idCliente
   


 left join (
 select t4.producto2, sum(unidades) as sumaUnidades from [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
 on t1.tipo=t2.tipos
 inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3
  on t2.idTarifasProducto = t3.id
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
  on t3.idProductoPadre = t4.id
   where t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t1.comprobado=1
   group by t4.producto2
 ) as t7
 on t7.producto2 = t4.producto2

   where t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t1.comprobado=1    

   	group by  t4.retribucionCorreos, t4.producto2, t7.sumaUnidades, t3.ordenInforme 

	union 

	  SELECT ' Acuses' as producto, sum(t1.unidades) as 'unidades', sum(t1.unidades*t4.precioNetoReal) as sinIva, sum(t1.unidades*(t4.precioIvaReal)) as conIva, 'CARTA' as retribucionCorreos
  ,t3.unidades as sumatorioUnidades, t2.destinoAcusesOrden as orden
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t1.tipo=t2.tipos
  inner join (SELECT tipos, case when iva>0 then (precioNeto+iva)/1.21
	else precioNeto end as precioNetoReal, precioNeto + iva as precioIvaReal     
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."]) as t4
  on t4.tipos = t2.tipos

  left join (
 SELECT 'acuses' as tipo, sum(t1.unidades) as 'unidades'

  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t1.tipo=t2.tipos
  where  t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t2.idTarifasProducto is null  and t2.tipos!='1' and t2.descripcion like '%acuse%' and t1.comprobado=1
  ) as t3
  on t3.tipo = 'acuses'

  where  t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t2.idTarifasProducto is null  and t2.tipos!='1' and t2.descripcion like '%acuse%' and t1.comprobado=1 
group by t2.destinoAcuses, t3.unidades,t2.destinoAcusesOrden

union 

	  SELECT ' PEE' as producto, sum(t1.unidades) as 'unidades', sum(t1.unidades*t4.precioNetoReal) as sinIva, sum(t1.unidades*(t4.precioIvaReal)) as conIva, 'CARTA' as retribucionCorreos
  ,t3.unidades as sumatorioUnidades, t2.destinoAcusesOrden as orden
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t1.tipo=t2.tipos
  inner join (SELECT tipos, case when iva>0 then (precioNeto+iva)/1.21
	else precioNeto end as precioNetoReal, precioNeto + iva as precioIvaReal     
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."]) as t4
  on t4.tipos = t2.tipos

  left join (
 SELECT 'PEE' as tipo, sum(t1.unidades) as 'unidades'

  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t1.tipo=t2.tipos
  where  t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t2.idTarifasProducto is null  and t2.tipos!='1' and t2.descripcion like '%PEE%' and t1.comprobado=1
  ) as t3
  on t3.tipo = 'PEE'

  where  t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t2.idTarifasProducto is null  and t2.tipos!='1' and t2.descripcion like '%PEE%' and t1.comprobado=1 
group by t2.destinoAcuses, t3.unidades,t2.destinoAcusesOrden


union 

SELECT ' Envios Especiales', sum(t1.unidades) as 'unidades', sum(importe/1.21) as sinIva, sum(importe) as conIva, 'ENVIOS ESPECIALES' as retribucionCorreos, sum(t1.unidades) as 'sumaUnidades'
  , 1 as orden
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2
  on t1.tipo=t2.tipos 

  where  t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t2.idTarifasProducto is null  and t2.tipos='1' and t1.comprobado=1


  order by retribucionCorreos, producto2, orden";
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function verImporteFacturasCorreosPorFechaYcliente ($datosBBDD,$fechaInicio, $fechaFin,$idCliente)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
	
	$consulta = "SELECT sum(importe) as importe
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos]
  where codigoCliente = ".$idCliente." and fecha >= '".$fechaInicio1."' and fecha <= '".$fechaFin1."'";
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}
function verDatosFacturasCorreosPorFechaYCliente2 ($datosBBDD,$fechaInicio, $fechaFin)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("Y-m-d", strtotime($fechaInicio));
	$fechaFin1 = date("Y-m-d", strtotime($fechaFin));	
	
	$consulta = " SELECT t1.codigoCliente, sum(t1.importe) as 'importe' , t2.subcliente
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].clientes as t2
  on t1.codigoCliente = t2.codigo
  where  t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' 
    group by t1.codigoCliente, t2.subcliente
   order by t1.codigoCliente";
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function verDatosFranqueoPorFechaYCliente2 ($datosBBDD,$fechaInicio, $fechaFin)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));	
	$anioSeleccionado = date('Y', strtotime($fechaInicio));
	
	$consulta = "  select t1.idCliente, sum(t1.importe) as 'importe' , t2.subcliente
	from [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1
	 inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.idCliente = t2.codigo
  where  t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t1.comprobado = 1
  group by t1.idCliente, t2.subcliente
  order by t1.idCliente";
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

/*function verImporteFranqueoPorFechaYCliente ($datosBBDD,$fechaInicio, $fechaFin,$idCliente)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
	
	$consulta = "select sum(importe) from [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos]
  where idCliente = ".$idCliente." and fecha >= '".$fechaInicio1."' and fecha <= '".$fechaFin1."'  and comprobado = 1";

  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}*/

function verDatosFranqueoPorRefenciaEid($datosBBDD,$referencia,$id,$anio)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	$consulta = "SELECT *  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anio."]
  where referencia = '".$referencia."' and id ='".$id."'";
		
	//echo $consulta;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}

function verBonificacionesFranqueo($datosBBDD,$fechaInicio,$fechaFin,$codigoCliente)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fechaInicio1 =  date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 =  date("d-m-Y", strtotime($fechaFin));
	
	$anioSeleccionado =  date("Y", strtotime($fechaInicio));
	
	$consulta = "select * from
(select t3.nombre_empresa, t3.subcliente, t3.codigo_saldo, t3.codigo, t2.descripcion, t4.descuentoPorCiento, 

sum(ROUND((t2.precioNeto),2)*(unidades)) as importe, sum(ROUND((t2.precioNeto),2)*(unidades)) * t4.descuentoPorCiento/100 as bonificacion, t2.gramos, t2.precioNeto, sum(t1.unidades) as unidades


FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1 
inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2 
on t1.tipo = t2.tipos 
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3 
on t3.codigo = t1.idCliente 
inner join [".$datosBBDD->bbddBBDD."].[dbo].[descuentosFranqueo".$anioSeleccionado."] as t4 
on t4.descripcion = t2.descripcion 
where t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t3.codigo_saldo = ".$codigoCliente." and t2.descripcion  like 'ACUSE%' and t1.comprobado=1 


GROUP BY t3.nombre_empresa, t3.subcliente, t3.codigo_saldo, t3.codigo, t2.descripcion, t4.descuentoPorCiento,  t2.gramos, t2.precioNeto 

union

select t3.nombre_empresa, t3.subcliente, t3.codigo_saldo, t3.codigo, t2.descripcion, t4.descuentoPorCiento, 
sum(ROUND((t2.precioNeto),2)*(unidades)) as importe, sum(ROUND((t2.precioNeto),2)*(unidades)) * t4.descuentoPorCiento/100 as bonificacion, t2.gramos, t2.precioNeto, sum(t1.unidades) as unidades
FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioSeleccionado."] as t1 
inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioSeleccionado."] as t2 
on t1.tipo = t2.tipos 
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3 
on t3.codigo = t1.idCliente 
inner join [".$datosBBDD->bbddBBDD."].[dbo].[descuentosFranqueo".$anioSeleccionado."] as t4 
on t4.descripcion = t2.descripcion 
where t1.fecha >= '".$fechaInicio1."' and t1.fecha <= '".$fechaFin1."' and t3.codigo_saldo = ".$codigoCliente." and t2.descripcion NOT like 'ACUSE%'  and t1.comprobado=1 


GROUP BY t3.nombre_empresa, t3.subcliente, t3.codigo_saldo, t3.codigo, t2.descripcion, t4.descuentoPorCiento ,  t2.gramos, t2.precioNeto


) as tabla

order by tabla.descripcion, tabla.nombre_empresa, tabla.subcliente, tabla.codigo_saldo, tabla.codigo, tabla.gramos";
	
  
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function cargarDatosFranqueoTipoPorId ($datosBBDD,$id)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$anioActual = date('Y');
	
	$consulta = "SELECT t1.*, t2.codigo_saldo, t2.importePF
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] as t1
  inner join  [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
  on t1.idCliente = t2.codigo

  where t1.id = ".$id;  
 
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function cargarDatosGenericosFranqueo($datosBBDD,$condicion="")
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "SELECT * FROM [".$datosBBDD->bbddBBDD."].[dbo].[datosGenericosFranqueo] ".$condicion;
	
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if( $resultado === false ) 
	{
     	die ("Error:\n".$resultado."\n".$consulta."\n");		
	}
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))	
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function verDiasFranqueados ($datosBBDD,$condicion, $anio)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$anioActual = date('Y');
	
	$consulta = "SELECT count(distinct(fecha)) as diasFranqueados FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anio."] ".$condicion;  
 
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function borrarDatosFranqueoImportacionCertificados ($datosBBDD, $idEmpleado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTiposImportacionTemporal] where idEmpleado =" .$idEmpleado ;
		
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar los datos'.\n".$consulta."\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje="Datos Borrados";
	}
	
	return $mensaje;
}

function mostrarDatosParaExportarAlbaranesCorreosFranqueoSIDI ($datosBBDD, $idProducto)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$hoy = getdate();
	//$fechaActual = date("d-m-Y", strtotime($hoy));
	$fechaActual = date('d-m-Y'); 
	$anioActual = date('Y');
	
	$consulta = "SELECT t1.id, t1.idCliente, t1.ot, t1.fecha, t1.tipo, t1.unidades, t1.importe, t1.referencia, t1.comprobado, t1.txt, t1.importeSinIva, t1.numSeguimiento, t1.importado, t1.nombre, t1.direccion,
  t1.poblacion, t1.cp
  , t3.idProductoPadre,t4.producto, t4.idAnexo, t4.idProducto,t2.gramos as peso,t2.normalizado, t2.ambito, t2.anadidos
	, t2.ambito_SIDI, t2.gramos_SIDI, t4.idAnexo_SIDI, t4.idSIDI, substring(t5.codigoSidi,3,8) as codigoSidi, t6.anadidos as anadidos2, cast(t1.importe*100 as int) as importeSidi, 
	t5.nombre_empresa, t5.direccion as direccionCliente, t5.localidad as localidadCliente, t5.provincia as provinciaCliente, t5.codigo_postal as cpCliente
	,substring(t5.codigoSidiPre,3,8) as codigoSidiPre, t6.otSidi, t7.otSidi as otSidiPresupuesto
FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] as t1 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioActual."] as t2 
on t1.tipo = t2.tipos 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3 
on t3.id = t2.idTarifasProducto 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
on t4.id = t3.idProductoPadre  

inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t5
on t5.codigo = t1.idCliente

left join [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioActual."] as t6
on t6.referencia = t1.referencia

left join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t7
on t1.ot  like '%'+t7.presupuesto+'%' and t1.ot!=''
 

where t1.fecha = '".$fechaActual."' and t1.comprobado = 0  
  and t3.idProductoPadre = ".$idProducto;
  
	//echo "\n".$consulta."\n";
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function mostrarDatosParaExportarAlbaranesCorreosFranqueoPostF12SIDI ($datosBBDD, $idProducto)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	//$hoy = getdate();
	//$fechaActual = date("d-m-Y", strtotime($hoy))
	$fechaActual = date('d-m-Y');
	$anioActual = date('Y');

	//$fechaActual = '25-11-2024';
	
	$consulta = "SELECT t1.id, t1.idCliente, t1.ot, t1.fecha, t1.tipo, t1.unidades, t1.importe, t1.referencia, t1.comprobado, t1.txt, t1.importeSinIva, t1.numSeguimiento, t1.importado, t1.nombre, t1.direccion,
  t1.poblacion, t1.cp
  , t3.idProductoPadre,t4.producto, t4.idAnexo, t4.idProducto,t2.gramos as peso,t2.normalizado, t2.ambito, t2.anadidos
	, t2.ambito_SIDI, t2.gramos_SIDI, t4.idAnexo_SIDI, t4.idSIDI, substring(t5.codigoSidi,3,8) as codigoSidi, t6.anadidos as anadidos2, cast(t1.importe*100 as int) as importeSidi, 
	t5.subcliente, t5.direccion as direccionCliente, t5.localidad as localidadCliente, t5.provincia as provinciaCliente, t5.codigo_postal as cpCliente
	,substring(t5.codigoSidiPre,3,8) as codigoSidiPre
FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoTipos".$anioActual."] as t1 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas".$anioActual."] as t2 
on t1.tipo = t2.tipos 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductos] as t3 
on t3.id = t2.idTarifasProducto 

inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
on t4.id = t3.idProductoPadre  

inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t5
on t5.codigo = t1.idCliente

left join [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioActual."] as t6
on t6.referencia = t1.referencia
 

where t1.fecha = '".$fechaActual."' and t1.comprobado = 1 and t1.importado = 1
  and t3.idProductoPadre = ".$idProducto;
	
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function insertarGrabacionFranqueo($datosBBDD,$fecha,$idCliente,$ot,$otSidi="",$importe,$envios,$idProducto,$detalle,$idEmpleado,$anadidos,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1 = date("d-m-Y", strtotime($fecha));
	$fecha2 = date("d/m/Y", strtotime($fecha));
	
	$idDelEmpleado = "";
	if (strlen($idEmpleado)==1)
	{
		$idDelEmpleado = "0".$idEmpleado;
	}
	else if (strlen($idEmpleado)==2)
	{
		$idDelEmpleado =$idEmpleado;
	}
	else
	{
		die("Error: el id del empleado debe tener un maximo de 2 digitos");//en este caso habria que quitar un digito al secuencial y dárselo al idEmpleado
	}	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioSeleccionado."] (referencia, fecha, idCliente, ot, otSidi, importe, envios, producto, detalle, anadidos)
  
  select  CONCAT('".$fecha2."','".$idDelEmpleado."',CASE 
  WHEN LEN(isnull(max(id),0)+1)=1 THEN CONCAT('0000000',isnull(max(id),0)+1)
  WHEN LEN(isnull(max(id),0)+1)=2 THEN CONCAT('000000',isnull(max(id),0)+1)
  WHEN LEN(isnull(max(id),0)+1)=3 THEN CONCAT('00000',isnull(max(id),0)+1)
  WHEN LEN(isnull(max(id),0)+1)=4 THEN CONCAT('0000',isnull(max(id),0)+1)
  WHEN LEN(isnull(max(id),0)+1)=5 THEN CONCAT('000',isnull(max(id),0)+1)
  WHEN LEN(isnull(max(id),0)+1)=6 THEN CONCAT('00',isnull(max(id),0)+1)
  WHEN LEN(isnull(max(id),0)+1)=7 THEN CONCAT('0',isnull(max(id),0)+1)
	ELSE 'ERROR'END) 
	,'".$fecha1."', ".$idCliente.",'".$ot."','".$otSidi."',".$importe.",".$envios.",".$idProducto.",'".$detalle."','".$anadidos."' from [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioSeleccionado."]";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar el registro de franqueo'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Registro Guardado");
	}
	
	return $mensaje;	
}

function verUltimaReferenciaPorUsuario ($datosBBDD, $idEmpleado,$anioSeleccionado)	
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$idDelEmpleado = "";
	if (strlen($idEmpleado)==1)
	{
		$idDelEmpleado = "0".$idEmpleado;
	}
	else if (strlen($idEmpleado)==2)
	{
		$idDelEmpleado =$idEmpleado;
	}
	else
	{
		die("Error: el id del empleado debe tener un maximo de 2 digitos");//en este caso habria que quitar un digito al secuencial y dárselo al idEmpleado
	}
	
	
	$consulta = "select referencia from [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioSeleccionado."] 
where id=(select max(id) FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueo".$anioSeleccionado."] where SUBSTRING(referencia,11, 2) = '".$idDelEmpleado."')

";
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

//FRANQUEO - FIN

//FRANQUEO PAGADO

function mostrarHistoricoFranqueoPagado($datosBBDD,$anioSeleccionado, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	//$anioSeleccionado =   date("Y", strtotime($fecha));
	
	//$fecha1=  date("d-m-Y", strtotime($fecha));
	
		
	$consulta = "SELECT t1.*, t2.nombre_empresa, t2.nombre_franqueo, CONCAT(t3.nombre,' ', t3.apellidos) as nombreEmpleado, t4.producto
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueoPagado".$anioSeleccionado."] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2 
	on t1.idCliente = t2.codigo
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t3
	on t1.idEmpleado = t3.id
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[tarifasProductoPadre] as t4
	on t1.idProductoPadre = t4.id ".$condicion;

	
	
	
	
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function insertarGrabacionFranqueoPagado($datosBBDD,$fecha,$idCliente,$idProducto,$envios,$ot,$tipoDetalle,$idEmpleado,$anioSeleccionado)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1 = date("d-m-Y", strtotime($fecha));
	$fecha2 = date("d/m/Y", strtotime($fecha));
	
	$idDelEmpleado = "";
	if (strlen($idEmpleado)==1)
	{
		$idDelEmpleado = "0".$idEmpleado;
	}
	else if (strlen($idEmpleado)==2)
	{
		$idDelEmpleado =$idEmpleado;
	}
	else
	{
		die("Error: el id del empleado debe tener un maximo de 2 digitos");//en este caso habria que quitar un digito al secuencial y dárselo al idEmpleado
	}	
	
	$consulta = "INSERT INTO [dbo].[franqueoPagado".$anioSeleccionado."]
           ([idCliente]
           ,[idProductoPadre]
           ,[fecha]
           ,[unidades]
           ,[ot]
           ,[tipoCert_Not]
           ,[idEmpleado])
     VALUES
           (".$idCliente."
           ,".$idProducto."
           ,'".$fecha1."'
           ,".$envios."
           ,'".$ot."'
           ,'".$tipoDetalle."'
           ,'".$idEmpleado."')";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar el registro de franqueo'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Registro Guardado");
	}
	
	return $mensaje;	
}


function modificarFranqueoPagado($datosBBDD,$anioSeleccionado,$id,$ot,$envios,$detalle)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	
	$consulta = "update [dbo].[franqueoPagado".$anioSeleccionado."] set unidades = ".$envios.", ot='".$ot."', [tipoCert_Not]='".$detalle."'  where id=".$id;
          
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el registro'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Registro Modificado");
	}
	
	return $mensaje;	
}

function eliminarFranqueoPagado($datosBBDD,$anioSeleccionado,$id)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
		
	
	$consulta = "delete [dbo].[franqueoPagado".$anioSeleccionado."] where id=".$id;
          
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido eliminar el registro de franqueo'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Registro Eliminado");
	}
	
	return $mensaje;	
}



//FRANQUEO PAGADO - FIN 

//COMPRAS A TERCEROS

function copiarDetallesCompras($datosBBDD,$numeroPedidoNuevo,$numeroPedioCopiar)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "insert into [".$datosBBDD->bbddBBDD."].[dbo].[comprasTercerosDetalles] (pedido, descripcion, cantidad, precioUnidad, precioVenta, total, margen)
	select '".$numeroPedidoNuevo."', descripcion, cantidad, precioUnidad, precioVenta, total, margen 
	  FROM [".$datosBBDD->bbddBBDD."].[dbo].[comprasTercerosDetalles] where pedido = '".$numeroPedioCopiar."'";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	
	sqlsrv_close($conn_sis);
	
	return $resultado;
}

function cargarComprasTercerosAntiguos($datosBBDD,$condicion="")
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$datos = explode("order by",$condicion);
	$condicion1 = $datos[0];
	$orderBy = "";
	if (count($datos)>1)
	{
		$orderBy = "order by ".$datos[1];
	}
	
	//$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[clientes] ".$condiciones ." order by nombre_empresa";
	$consulta = "SELECT t1.*, t2.cliente as nombreCliente, t3.proveedor as nombreProveedor, sum(t4.total) as importe
	, t2.fecha as fechaPresupuesto, t5.fechaPago as fechaFactura, t5.numero as numeroFactura, t8.fechaPago as fechaFacturaClayma, t8.numero as numeroFacturaClayma, t6.nombre as comercialNombre, t6.inicial as comercialInicial, t7.concepto as formaPago
	, t2.clayma as clayma, t5.anioFactura, t8.anioFactura as anioFacturaClayma
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[compraTercerosAntiguo] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].presupuestos as t2
  on t1.presupuesto = t2.presupuesto
  inner join [".$datosBBDD->bbddBBDD."].[dbo].proveedores as t3
  on t1.idProveedor = t3.id
  left join [".$datosBBDD->bbddBBDD."].[dbo].[comprasTercerosDetallesAntiguo] as t4
  on t1.pedido = t4.pedido 
  left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t5
  on t1.presupuesto = t5.presupuesto
  left join [".$datosBBDD->bbddBBDD."].dbo.comerciales as t6
  on t1.idComercial = t6.id
  left join  [".$datosBBDD->bbddBBDD."].[dbo].[formaDePagoCompraTerceros] as t7
  on t1.idFormaPago = t7.id
  left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t8
  on t1.presupuesto = t8.presupuesto
  " .$condicion1. "
   group by t1.pedido, t1.idComercial, t1.presupuesto, t1.fecha, t1.idProveedor, t1.contactoProveedor, t1.fechaEntrega, t1.idFormaPago, t1.idCliente, t1.contactoCliente
  , t1.fechaFacturaCompra, t1.numeroFacturaCompra, t1.pedidoAntiguo, t2.cliente, t3.proveedor, t2.fecha, t5.fechaPago, t5.numero, t6.nombre, t6.inicial, t7.concepto, t1.pdfGenerado
  ,t2.clayma, t8.fechaPago, t8.numero, t5.anioFactura, t8.anioFactura  " .$orderBy;  //el order debe estar dentro de la variable $condiciones
	
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if( $resultado === false ) 
	{
     	die ("Error:\n".$resultado."\n".$consulta."\n");		
	}
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))	
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function insertarComprarTercero($datosBBDD,$idComercial,$presupuesto,$idProveedor,$contactoProveedor,$formaPago,$anual)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[compraTerceros]
           ([idComercial]
           ,[presupuesto]
           ,[fecha]
           ,[idProveedor]
           ,[contactoProveedor]  
		   ,[idFormapago]
		   ,[anual]
           )
     VALUES
           (".$idComercial."
           ,'".$presupuesto."'
           ,GETDATE()
           ,".$idProveedor."
           ,'".$contactoProveedor."'
		   ,".$formaPago."
		   ,".$anual."
           )";
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//sqlsrv_close($conn_sis);
	
	$mensaje="";

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido insertar el pedido..\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Contacto Insertado");
		$consulta = "SELECT SCOPE_IDENTITY() AS 'idCompra'";
		
		$stmt  = sqlsrv_query($conn_sis, $consulta);
		
		$mensaje="";

		if( sqlsrv_fetch($stmt)  === false ) 
		{
			$mensaje = "Error: no se ha podido ver la id del pedido'.\n".$stmt ."\n".$consulta."\n";
			$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
		}
		else
		{

			$mensaje = sqlsrv_get_field($stmt , 0);
			//$mensaje = $mensaje."|||Usuario insertado";
		}
		sqlsrv_close($conn_sis);
	}	
	
	return $mensaje;
}


function cargarComprasTerceros($datosBBDD,$condicion="")
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$datos = explode("order by",$condicion);
	$condicion1 = $datos[0];
	$orderBy = "";
	if (count($datos)>1)
	{
		$orderBy = "order by ".$datos[1];
	}	
	
	//$consulta = "select * from [".$datosBBDD->bbddBBDD."].[dbo].[clientes] ".$condiciones ." order by nombre_empresa";
	$consulta = "SELECT t1.*, t2.cliente as nombreCliente, t3.proveedor as nombreProveedor, sum(t4.total) as importe
	, t2.fecha as fechaPresupuesto, t5.fechaPago as fechaFactura, t5.numero as numeroFactura, t8.fechaPago as fechaFacturaClayma, t8.numero as numeroFacturaClayma, t6.nombre as comercialNombre, t6.inicial as comercialInicial, t7.concepto as formaPago
	, t2.clayma as clayma, t5.anioFactura, t8.anioFactura as anioFacturaClayma, t1.anual
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[compraTerceros] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].presupuestos as t2
  on t1.presupuesto = t2.presupuesto
  inner join [".$datosBBDD->bbddBBDD."].[dbo].proveedores as t3
  on t1.idProveedor = t3.id
  left join [".$datosBBDD->bbddBBDD."].[dbo].[comprasTercerosDetalles] as t4
  on t1.pedido = t4.pedido 
  left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t5
  on t1.presupuesto = t5.presupuesto
  left join [".$datosBBDD->bbddBBDD."].dbo.comerciales as t6
  on t1.idComercial = t6.id
  left join  [".$datosBBDD->bbddBBDD."].[dbo].[formaDePagoCompraTerceros] as t7
  on t1.idFormaPago = t7.id
  left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t8
  on t1.presupuesto = t8.presupuesto
  " .$condicion1. "
   group by t1.pedido, t1.idComercial, t1.presupuesto, t1.fecha, t1.idProveedor, t1.contactoProveedor, t1.fechaEntrega, t1.idFormaPago, t1.idCliente, t1.contactoCliente
  , t1.fechaFacturaCompra, t1.numeroFacturaCompra, t1.pedidoAntiguo,t1.observacionesInternas, t2.cliente, t3.proveedor, t2.fecha, t5.fechaPago, t5.numero, t6.nombre, t6.inicial, t7.concepto, t1.pdfGenerado
  ,t2.clayma, t8.fechaPago, t8.numero, t5.anioFactura, t8.anioFactura, t1.anual  " .$orderBy;  //el order debe estar dentro de la variable $condiciones
	
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if( $resultado === false ) 
	{
     	die ("Error:\n".$resultado."\n".$consulta."\n");		
	}
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))	
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}

function cargarComprasTercerosConceptos($datosBBDD,$condicion="")
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$datos = explode("order by",$condicion);
	$condicion1 = $datos[0];
	$orderBy = "";
	if (count($datos)>1)
	{
		$orderBy = "order by ".$datos[1];
	}	
	
	$consulta = "SELECT t1.pedido, t3.proveedor as nombreProveedor, t4.descripcion, t4.total, t1.numeroFacturaCompra, t1.fechaFacturaCompra, t1.fecha, t1.presupuesto, t5.fechaPago as fechaFactura, t5.numero as numeroFactura, t8.fechaPago as fechaFacturaClayma, t8.numero as numeroFacturaClayma, t2.clayma
	, t5.anioFactura, t8.anioFactura as anioFacturaClayma

  FROM [".$datosBBDD->bbddBBDD."].[dbo].[compraTerceros] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].presupuestos as t2
  on t1.presupuesto = t2.presupuesto
  inner join [".$datosBBDD->bbddBBDD."].[dbo].proveedores as t3
  on t1.idProveedor = t3.id
  left join [".$datosBBDD->bbddBBDD."].[dbo].[comprasTercerosDetalles] as t4
  on t1.pedido = t4.pedido 
  left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasTodosLosAnios] as t5
  on t1.presupuesto = t5.presupuesto
  left join [".$datosBBDD->bbddBBDD."].dbo.comerciales as t6
  on t1.idComercial = t6.id
  left join  [".$datosBBDD->bbddBBDD."].[dbo].[formaDePagoCompraTerceros] as t7
  on t1.idFormaPago = t7.id 
  left join [".$datosBBDD->bbddBBDD."].[dbo].[facturasClaymaTodosLosAnios] as t8
  on t1.presupuesto = t8.presupuesto
  " .$condicion1. " " .$orderBy; 
	
	//echo $consulta;
 
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	if( $resultado === false ) 
	{
     	die ("Error:\n".$resultado."\n".$consulta."\n");		
	}
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))	
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	return $result;	
}


function modificarComprasTerceros($datosBBDD,$id,$nombreProveedor,$contactoP,$contactoC,$comercial,$presupuesto,$fechaEntrega,$formaPago,$numFacCompra,$fechaFacCompra,$observacionInterna)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$fechaEntrega1="NULL";
	if ($fechaEntrega!="")
	{		
		$fechaEntrega1 = date("d-m-Y", strtotime($fechaEntrega));
	}
	
	$fechaFacCompra1="NULL";
	if ($fechaFacCompra!="")
	{
		$fechaFacCompra1 = date("d-m-Y", strtotime($fechaFacCompra));
	}
	
	$consulta = "UPDATE [".$datosBBDD->bbddBBDD."].[dbo].[compraTerceros]
   SET [idComercial] = ".$comercial."
      ,[presupuesto] = '".$presupuesto."'      
      ,[idProveedor] = ".$nombreProveedor."
      ,[contactoProveedor] = '".$contactoP."'
	  ,[observacionesInternas] = '".$observacionInterna."'";
	
	if ($fechaEntrega1=="NULL")
	{
		$consulta = $consulta . ",[fechaEntrega] = ".$fechaEntrega1." ";
	}
	else
	{
		$consulta = $consulta . ",[fechaEntrega] = '".$fechaEntrega1."' "; 
	}
	
	if ($fechaFacCompra1=="NULL")
	{
		$consulta = $consulta . ",[fechaFacturaCompra] = ".$fechaFacCompra1." ";
	}
	else
	{
		$consulta = $consulta . ",[fechaFacturaCompra] = '".$fechaFacCompra1."' "; 
	}
	
	$consulta = $consulta . "  ,[idFormaPago] = ".$formaPago."      
      ,[contactoCliente] = '".$contactoC."' 
     
      ,[numeroFacturaCompra] =  '".$numFacCompra."'      
 WHERE pedido = " . $id;	
	

	//echo $consulta;

	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la compra.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Comprar a Tercero modificada";
	}
	
	return $mensaje;
}

function insertarDetalleCompraTercero ($datosBBDD,$idPedido,$descripcion,$cantidad,$precioUnitario,$precioVenta,$precioTotal,$margen)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "

INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[comprasTercerosDetalles]
           ([pedido]
           ,[descripcion]
           ,[cantidad]
           ,[precioUnidad]
           ,[precioVenta]
           ,[total]
           ,[margen])
     VALUES
           (".$idPedido."
           ,'".$descripcion."'
           ,".$cantidad."
           ,".$precioUnitario."
           ,".$precioVenta."
           ,".$precioTotal."
           ,".$margen.")


";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar el albaran.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = (" -");
	}
	
	return $mensaje;	
}


function mostrarComprarTerceroDetalles($datosBBDD,$idPedido)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta=" select * from [".$datosBBDD->bbddBBDD."].[dbo].[comprasTercerosDetalles] 
	where pedido = ".$idPedido."
 
 	order by id asc";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function modificarDetalleCompraTercero($datosBBDD, $idDetalle, $descripcion, $cantidad, $precioUnitario, $precioTotal, $precioVenta, $margen)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);		
	
	$consulta="update [".$datosBBDD->bbddBBDD."].[dbo].[comprasTercerosDetalles] set descripcion='".$descripcion."', cantidad=".$cantidad.", precioUnidad=".$precioUnitario.", precioVenta=".$precioVenta.", total=".$precioTotal.", margen=".$margen." where id=".$idDetalle;
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido actualizar el detalle en la compra a Ternceros'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "";
	}
	
	return $mensaje;	
}

function eliminarDetalleComprarTercero ($datosBBDD, $idDetalle)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[comprasTercerosDetalles] where id =" .$idDetalle ;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el detalle'.\n".$consulta."\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje="Detalle Borrado";
	}
	
	return $mensaje;
}

function modificarComprasTercerosPdfImpreso($datosBBDD,$numPedido)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "UPDATE [".$datosBBDD->bbddBBDD."].[dbo].[compraTerceros]
   SET [pdfGenerado] = 1 WHERE pedido = " . $numPedido;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el valor del pdfGenerado.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "pdfGenerado Cambiado";
	}
	
	return $mensaje;
}

function insertarFacturaEnCompraTerceros($datosBBDD,$idPedido,$numeroFactura,$fechaFactura)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1 = date("d-m-Y", strtotime($fechaFactura));	
	
	$consulta = "UPDATE [".$datosBBDD->bbddBBDD."].[dbo].[compraTerceros]
   SET [fechaFacturaCompra] = '".$fecha1."'
      ,[numeroFacturaCompra] = '".$numeroFactura."'  
 WHERE pedido=".$idPedido;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el valor del pdfGenerado.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "pdfGenerado Cambiado";
	}
	
	return $mensaje;	
}

//COMPRAS A TERCEROS - FIN





function cambiar_clienteAutorizadosUndia($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$fecha1 = date("d-m-Y", strtotime($fechaFactura));	
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[clientes]
	set idAutorizacionFranqueo=1
	where idAutorizacionFranqueo=3";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el valor de la autorizacion de los clientes.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "";
	}
	
	return $mensaje;	
}


function verIdPapel($datosBBDD, $idMaterialPapel_tamano, $idMaterialPapel_tipo, $idMaterialPapel_acabado, $idMaterialPapel_gramaje)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="SELECT id, precio FROM [".$datosBBDD->bbddBBDD."].[dbo].[tarifas_papel] where idTamanio = ".$idMaterialPapel_tamano." and idTipo = ".$idMaterialPapel_tipo." and idAcabado = ".$idMaterialPapel_acabado." and idGramaje = ".$idMaterialPapel_gramaje;
 
 	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}



function mostrarTarifasPapel($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="select t1.id, t2.tamano, t3.tipo, t4.acabado, t5.gramaje, t1.precio
from [".$datosBBDD->bbddBBDD."].[dbo].tarifas_papel as t1	
inner join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanio] as t2
on t2.id = t1.idTamanio
inner join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTipo] as t3
on t3.id = t1.idTipo
inner join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelAcabado] as t4
on t4.id = t1.idAcabado
inner join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelGramaje] as t5
on t5.id = t1.idGramaje
where t2.tamano != '_Ninguno'
order by t2.tamano, t3.tipo, t4.acabado, t5.gramaje, t1.precio";
 
 	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}



function modificarTarifaPapel($datosBBDD, $idTarifaPapel, $precio)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[tarifas_papel] set precio = ".$precio." where id = ".$idTarifaPapel;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la Tarifa.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "";
	}
	
	return $mensaje;	
}




function insertarTarifaPapel ($datosBBDD, $idMaterialPapel_tamano, $idMaterialPapel_tipo, $idMaterialPapel_acabado, $idMaterialPapel_gramaje,$precio)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "

	INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[tarifas_papel]
           ([idTamanio]
           ,[idTipo]
           ,[idAcabado]
           ,[idGramaje]
           ,[precio])
     VALUES
    		(".$idMaterialPapel_tamano."
        	,".$idMaterialPapel_tipo."
        	,".$idMaterialPapel_acabado."
       		,".$idMaterialPapel_gramaje."
        	,".$precio.")";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido guardar el albaran.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = (" -");
	}
	
	return $mensaje;	
}


function mostrarTarifasTipoImpresora($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="select * from [".$datosBBDD->bbddBBDD."].[dbo].[L_impresorasTipo] order by tipoImpresora";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}


function modificarTarifaTipoImpresora($datosBBDD, $idTipoImpresora, $precio)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[L_impresorasTipo] set precioClick = ".$precio." where id = ".$idTipoImpresora;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la Tarifa.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "";
	}
	
	return $mensaje;	
}



function mostrarPresupuestosDetalles($datosBBDD, $condicion) 
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);

	$consulta = "SELECT t1.*,t3.nombre, t6.departamento, t7.tipoProceso, t8.proceso, t9.id as idTamanio, t9.tamano,t10.id as idTipoPapel, t10.tipo, t11.acabado, t12.gramaje, t5.tipoImpresora
	,case when t1.unidades2>0 then t1.unidades2 else  t1.unidades end  as unidadesParaUtilizar
	, t1.impresionNumeroCaras, t4.precio as precioMaterial, t5.precioClick
	,case when t1.unidades2>0 then t4.precio * t1.unidades2 else  t4.precio*t1.unidades end  as costePapel
	,case when t1.unidades2>0 then t5.precioClick * t1.impresionNumeroCaras * t1.unidades2 else  t5.precioClick * t1.impresionNumeroCaras * t1.unidades end  as costeClick

	FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
	on t1.presupuesto = t2.presupuesto
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestadores] as t3
	on t2.idComercial = t3.id
	left join [".$datosBBDD->bbddBBDD."].[dbo].[tarifas_papel] as t4
	on t1.idMaterialPapel = t4.id
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_impresorasTipo] as t5
	on t1.idTipoImpresora = t5.id
	left join [".$datosBBDD->bbddBBDD."].[dbo].[procesosDepartamento] as t6
	on t1.idDepartamento = t6.id
	left join [".$datosBBDD->bbddBBDD."].[dbo].[procesosTipos] as t7
	on t1.idTipo = t7.id
	left join [".$datosBBDD->bbddBBDD."].[dbo].[procesos] as t8
	on t1.idConcepto = t8.id
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanio] as t9
	on t4.idTamanio = t9.id
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTipo] as t10
	on t4.idTipo = t10.id
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelAcabado] as t11
	on t4.idAcabado = t11.id
	left join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelGramaje] as t12
	on t4.idGramaje = t12.id
	";

	$consulta = $consulta. $condicion;

	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;

}


function eliminarTamaniosPapel($datosBBDD,$id)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanio] where id = ".$id;
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el tamaño'.\n".$consulta."\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje="Tamaño Borrado";
	}
	
	return $mensaje;
}

function eliminarTiposPapel($datosBBDD,$id)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTipo] where id = ".$id;
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el tipo'.\n".$consulta."\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje="Tipo Borrado";
	}
	
	return $mensaje;
}


function eliminarAcabadosPapel($datosBBDD,$id)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[L_papelAcabado] where id = ".$id;
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el acabado'.\n".$consulta."\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje="Acabado Borrado";
	}
	
	return $mensaje;
}


function eliminarGramajesPapel($datosBBDD,$id)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[L_papelGramaje] where id = ".$id;
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el gramaje'.\n".$consulta."\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje="Gramaje Borrado";
	}
	
	return $mensaje;
}



function insertarTamanioPapel($datosBBDD,$valor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = " INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanio] ([tamano]) VALUES ('".$valor."')";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
		$mensaje = "Error: no se ha podido guardar el tamaño.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Tamaño Insertado");
	}
	
	return $mensaje;	
}

function insertarTipoPapel($datosBBDD,$valor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = " INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTipo] ([tipo]) VALUES ('".$valor."')";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
		$mensaje = "Error: no se ha podido guardar el tipo.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Tipo Insertado");
	}
	
	return $mensaje;	
}

function insertarAcabadoPapel($datosBBDD,$valor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = " INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[L_papelAcabado] ([acabado]) VALUES ('".$valor."')";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
		$mensaje = "Error: no se ha podido guardar el acabado.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Acabado Insertado");
	}
	
	return $mensaje;	
}

function insertarGramajePapel($datosBBDD,$valor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = " INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[L_papelGramaje] ([gramaje]) VALUES ('".$valor."')";	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
		$mensaje = "Error: no se ha podido guardar el gramaje.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Gramaje Insertado");
	}
	
	return $mensaje;	
}

function cargarTamaniosConversorPapel($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*, t2.tamano as tamanioInicio, t3.tamano as tamanioFinal
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanioConversor] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanio] as t2
  on t1.idTamanioInicio = t2.id
    inner join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanio] as t3
  on t1.idTamanioFinal = t3.id

  order by tamanioInicio, tamanioFinal";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}



function insertarTamanioConversor($datosBBDD, $idTamanioInicio, $idTamanioFinal, $valorTamanioConversor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = " INSERT INTO [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanioConversor] 
			 ([idTamanioInicio]
           ,[idTamanioFinal]
           ,[valor])
		   VALUES
           (".$idTamanioInicio."
           ,".$idTamanioFinal."
            ,".$valorTamanioConversor.")";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
		$mensaje = "Error: no se ha podido guardar el conversor.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("Conversor Insertado");
	}
	
	return $mensaje;	
}


function modificarTamanioConversor($datosBBDD,$id,$idTamanioInicio,$idTamanioFinal,$valorTamanioConversor)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanioConversor] set idTamanioInicio = ".$idTamanioInicio.", idTamanioFinal = ".$idTamanioFinal.", valor = ".$valorTamanioConversor." where id = ".$id;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar el conversor.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "Conversor Modificado";
	}
	
	return $mensaje;	
}


function eliminarTamanioConversor($datosBBDD,$id)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "delete [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanioConversor] where id = ".$id;
	
 	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido borrar el conversor'.\n".$consulta."\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje="Conversor Borrado";
	}
	
	return $mensaje;
}



function mostrarValorConversorTamanio($datosBBDD, $presupuesto)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "select max(t15.valor) as valorConversor
FROM [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos detalle] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[L_papelTamanioConversor] as t15
  on t15.idTamanioInicio = t1.idTipoImpresora and t15.idTamanioFinal = t1.idPapelTamanioFinal
  where t1.presupuesto = '".$presupuesto."'";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}


function insertarMultiUsuario($datosBBDD, $idUsuario, $idMultiEmpleado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "  insert into [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras_multiusuario]  
		([idUsuario]
           ,[proceso]
           ,[idEmpleado])
		   VALUES
           ( ".$idUsuario."
           ,''
           ,".$idMultiEmpleado.")";
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido añadir el Empleado'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;

	//return $resultado;
}

function cargarMultiUsuario($datosBBDD, $idUsuario)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = "SELECT t1.*,CONCAT(t2.nombre, ' ', t2.apellidos) as nombreEmpleado
				FROM [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras_multiusuario] as t1
				inner join [".$datosBBDD->bbddBBDD."].[dbo].[empleados] as t2
				on t1.idEmpleado = t2.id				
 				where t1.idUsuario = ".$idUsuario;
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}



function quitarMultiUsuario($datosBBDD, $idUsuario, $idMultiEmpleado)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "  delete from [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras_multiusuario]  where idUsuario=".$idUsuario. " and idEmpleado=".$idMultiEmpleado;
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido quitar el Empleado'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;

	//return $resultado;
}

function quitarMultiUsuarioPorIdUsuario($datosBBDD, $idUsuario)
{	
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "  delete from [".$datosBBDD->bbddBBDD."].[dbo].[registroHoras_multiusuario]  where idUsuario=".$idUsuario;
	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
	
	//echo ("\n".$consulta."\n");
	
	sqlsrv_close($conn_sis);	
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido quitar el Empleado'.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		//$mensaje = ("Presupuesto Modificado");
	}
	
	return $mensaje;

	//return $resultado;
}



function verIdClientePorOtSidi($datosBBDD, $otSidi)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);	
	
	$consulta = " SELECT top 1 t1.otSidi, ot, count(t1.ot) as numRegistros,t1.idCliente, t2.codigo_saldo  
FROM [".$datosBBDD->bbddBBDD."].[dbo].[franqueo2025] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.idCliente = t2.codigo


 where t1.otSidi = '".$otSidi."'
 group by t1.otSidi,t1.ot, t1.idCliente, t2.codigo_saldo 
 order by count(t1.ot) desc";
	
 	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	if( $resultado === false) 
	{		
		die( print_r( sqlsrv_errors(), true) );
	}
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}
	
	sqlsrv_close($conn_sis);
	
	return $result;
}



function mostrarFacturasAbonosCorreosClayma ($datosBBDD,$condicion,$anio)
{

	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	
	$consulta = "select * from (

SELECT t1.cliente, CAST(t1.numero AS VARCHAR) as numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'factura' as tipo  
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anio."] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
on t2.idComercial = t3.id


union


SELECT t1.cliente, CAST(t1.numero AS VARCHAR) as numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'abono' as tipo  
FROM [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anio."] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
on t2.idComercial = t3.id

union

SELECT t1.cliente, CAST(t1.numero AS VARCHAR) as numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'facturaClayma' as tipo  
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anio."] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
on t2.idComercial = t3.id


union


SELECT t1.cliente, CAST(t1.numero AS VARCHAR) as numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'abonoClayma' as tipo  
FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anio."] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
on t1.cliente = t2.nombre_empresa
left join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
on t2.idComercial = t3.id

union 

SELECT  t2.nombre_empresa, t1.numeroOficial, t1.fecha, t1.neto, t1.formaPago, t1.fechaPago, 'facturaCorreos' as tipo 
FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1
inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
on t1.codigoCliente = t2.codigo

) as t1 ".$condicion;
	
	
	
	//echo ($consulta);
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;	
}



function mostrarListadoFacturas_SumatorioTodo($datosBBDD, $condicion, $anio)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select sum(precioNeto) as precioNeto from (

	SELECT t1.cliente, CAST(t1.numero AS VARCHAR) as numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'factura' as tipo  
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturas".$anio."] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.cliente = t2.nombre_empresa
	left join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
	on t2.idComercial = t3.id
	
	
	union
	
	
	SELECT t1.cliente, CAST(t1.numero AS VARCHAR) as numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'abono' as tipo  
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[abono".$anio."] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.cliente = t2.nombre_empresa
	left join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
	on t2.idComercial = t3.id
	
	union
	
	SELECT t1.cliente, CAST(t1.numero AS VARCHAR) as numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'facturaClayma' as tipo  
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasClayma".$anio."] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
	on t1.cliente = t2.nombre_empresa
	left join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
	on t2.idComercial = t3.id
	
	
	union
	
	
	SELECT t1.cliente, CAST(t1.numero AS VARCHAR) as numero, t1.fecha, t1.precioNeto, t1.formaPagoReal, t1.fechaPago, 'abonoClayma' as tipo  
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[abonoClayma".$anio."] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t2
	on t1.cliente = t2.nombre_empresa
	left join [".$datosBBDD->bbddBBDD."].[dbo].[comerciales] as t3
	on t2.idComercial = t3.id
	
	union 
	
	SELECT  t2.nombre_empresa, t1.numeroOficial, t1.fecha, t1.neto, t1.formaPago, t1.fechaPago, 'facturaCorreos' as tipo 
	FROM [".$datosBBDD->bbddBBDD."].[dbo].[facturasCorreos] as t1
	inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t2
	on t1.codigoCliente = t2.codigo
	
	) as t1 ".$condicion;


	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function guardarOtSidiDesdeExcel($datosBBDD, $ot, $otSidi)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] set otSidi = '".$otSidi."'  where presupuesto =  '".$ot."'";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
		
	sqlsrv_close($conn_sis);
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar.\n".$resultado."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = ("");
	}
	
	return $mensaje;	
}



function registrosProvisionesFondo($datosBBDD, $condicion)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "select * from (
SELECT t1.*, t3.nombre_franqueo, t2.campana, t3.codigo, t4.cobrada as cobradaNombre, t5.tipo as tipoNombre, t3.subcliente, t3.nombre_empresa, t3.codigo_saldo, t3.noAplicarPF
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
  on t1.presupuesto=t2.presupuesto

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t2.cliente=t3.subcliente

   inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipoCobrada] as t4
  on t1.cobrada=t4.id
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t5
  on t5.id = t1.tipo 
  where t2.clayma=0


  union
  
SELECT t1.*, t3.nombre_franqueo, t2.campana, t3.codigo, t4.cobrada as cobradaNombre, t5.tipo as tipoNombre, t3.subcliente, t3.nombre_empresa, t3.codigo_saldo, t3.noAplicarPF
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[presupuestos] as t2
  on t1.presupuesto=t2.presupuesto

  inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientesClayma] as t3
  on t2.cliente=t3.subcliente

   inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipoCobrada] as t4
  on t1.cobrada=t4.id
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t5
  on t5.id = t1.tipo 
  where t2.clayma=1 
  
  
   union 

  SELECT t1.*, t3.nombre_franqueo, t1.concepto, t3.codigo, t4.cobrada as cobradaNombre, t5.tipo as tipoNombre, t3.subcliente, t3.nombre_empresa, t3.codigo_saldo, t3.noAplicarPF
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo] as t1
   
   
    inner join [".$datosBBDD->bbddBBDD."].[dbo].[clientes] as t3
  on t1.idCliente=t3.codigo
   
 
    inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipoCobrada] as t4
  on t1.cobrada=t4.id
  
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[provisionesDeFondo_tipos] as t5
  on t5.id = t1.tipo 

  where t1.presupuesto like '9%' 
  
  ) as tabla ".$condicion;
 


	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}



function cargarGFConcepto($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="select * from [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_concepto] order by nombreConcepto";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function cargarGFSubConcepto1($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="select * from [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_subconcepto1] order by nombreSubconcepto";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function cargarGFSubConcepto2($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="select * from [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_subconcepto2] order by nombreSubconcepto2";
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}


function mostrarTarifasGranFormato($datosBBDD)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta="SELECT t3.id, t1.nombreConcepto, t2.nombreSubconcepto, t3.nombreSubconcepto2, t3.coste
  FROM [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_concepto] as t1
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_subconcepto1] as t2
  on t1.id = t2.idConcepto
  inner join [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_subconcepto2] as t3
  on t2.id = t3.idSubconcepto1

  order by t1.nombreConcepto, t2.nombreSubconcepto, t3.nombreSubconcepto2";
 
 	
	
	//echo $consulta;
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));	
	
	$result = array();
	
	while($valor = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
	{
		$result[] = $valor;
	}

	sqlsrv_close($conn_sis);		
	
	return $result;
}

function modificarTarifaGranFormato($datosBBDD, $idGranFormato, $precio)
{
	$connectionInfo = array("Database"=>$datosBBDD->bbddBBDD, "UID"=>$datosBBDD->bbddUser, "PWD"=>$datosBBDD->bbddPass, "CharacterSet"=>"UTF-8");	
	$conn_sis=sqlsrv_connect($datosBBDD->dbhost,$connectionInfo);
	
	$consulta = "update [".$datosBBDD->bbddBBDD."].[dbo].[L_gf_subconcepto2] set coste = ".$precio." where id = ".$idGranFormato;	
	
	$resultado = sqlsrv_query($conn_sis, $consulta,array(),array("Scrollable"=>"buffered"));
 	sqlsrv_close($conn_sis);		
	
	$mensaje="";	

	if( $resultado === false ) 
	{
     	$mensaje = "Error: no se ha podido modificar la Tarifa.\n".$resultado."\n".$consulta."\n";
		$mensaje = $mensaje . (print_r(sqlsrv_errors(), true));
	}
	else
	{
		$mensaje = "";
	}
	
	return $mensaje;	
}








?>