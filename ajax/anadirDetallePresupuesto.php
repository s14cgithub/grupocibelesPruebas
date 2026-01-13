<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="anadirDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$presupuesto = $_POST["numPresupuesto"];
	$tipoProceso = $_POST["tipoProceso"];
	$idDepartamento = $_POST["idDepartamento"];
	$proceso = $_POST["proceso"];	
	$descripcion = $_POST["descripcion"];
	$nota = $_POST["nota"];
	$unidad = $_POST["unidad"];
	$precio = $_POST["precio"];
	$orden = $_POST["orden"];
	$letra = $_POST["letra"];
		
	$descripcion = str_replace("'",'"',$descripcion);
	$nota = str_replace("'",'"',$nota);
	$notaAdmonProd = $_POST["notaProdAdmon"];
	$notaAdmonProd = str_replace("'",'"',$notaAdmonProd);
		

	if ($_POST["exentoIVA"]=="true")
		$exentoIVA = 1;
	else
		$exentoIVA = 0;




	$idMaterialPapel_tamano = $_POST["idMaterialPapel_tamano"];
	$idMaterialPapel_tipo =  $_POST["idMaterialPapel_tipo"];
	$idMaterialPapel_acabado =  $_POST["idMaterialPapel_acabado"];
	$idMaterialPapel_gramaje =  $_POST["idMaterialPapel_gramaje"];

	$idImpresoraDetalles =  $_POST["idImpresoraDetalles"];
	$numCaras =  $_POST["numCaras"];
	
	
	$idMaterialPapel_tamanoFinal =  $_POST["idMaterialPapel_tamanoFinal"];
	$peso =  $_POST["peso"];

	$idGFConcepto =  $_POST["idGFConcepto"];
	$idGFMetrosCuadrados =  $_POST["idGFMetrosCuadrados"];

	
		
	$idPapel = verIdPapel($conexion, $idMaterialPapel_tamano, $idMaterialPapel_tipo, $idMaterialPapel_acabado, $idMaterialPapel_gramaje);

	if (count($idPapel)<=0)
	{
		echo "Error: No existe el material seleccionado";
	}
	/*else if ($idPapel[0]["precio"]<=0 &&)
	{
		echo "Error: El precio del material introducido es 0. Primero hay que cambiar el precio en las tarifas del Papel";
	}*/
	else 
	{
		$numero = insertarDetallePresupuesto($conexion,$presupuesto,$tipoProceso,$proceso,$descripcion,$nota,$unidad,$precio,$orden,$idDepartamento,$notaAdmonProd,$exentoIVA,$idPapel[0]["id"],$idImpresoraDetalles,$numCaras,$idMaterialPapel_tamanoFinal,$peso,$idGFConcepto,$idGFMetrosCuadrados);
	
		if (substr($numero, 0, 5 ) === "Error")
		{
			echo $numero;
		}
		else
		{
			echo "Detalle Insertado";
			
			$datosNuevos = '';
			$resultado = cargarUnDetallePresupuesto($conexion,$numero);
			
			if (count($resultado)>0)
			{
				$datosNuevos = "Presupuesto: ".$resultado[0]["presupuesto"]."|Departamento: ".$resultado[0]["departamento"]."|Tipo Proceso: ".$resultado[0]["tipoProceso"]."|Proceso: ".$resultado[0]["proceso"]."|Descripcion: ".$resultado[0]["descripcion"]."|NotaAdmonProd: ".$resultado[0]["notaAdmonProd"]."|Nota: ".$resultado[0]["notaCibeles"]."|Orden: ".$resultado[0]["orden"]."|Unidades: ".$resultado[0]["unidades"]."|Unidades2: ".$resultado[0]["unidades2"]."|Precio: ".$resultado[0]["precio"];
			}
			
			
			//LOG
			$usuario = $_SESSION['usuario'];
			$descripcion = log_creacion;
			$tabla = presupuestoDetalle_tabla;
			$datosAntiguos = '';
			//$datosNuevos = '';
			$columna = "todas";
			$idRegistro = $numero;	
	
			if($letra!="")
			{
				$numPresupuesto = $presupuesto.$letra;
			}
			else
			{
				$numPresupuesto = $presupuesto;
			}
	
			echo insertarRegistro ($conexion, $usuario, $descripcion,$datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);
			
		}
	}
	
	
	
}

?>