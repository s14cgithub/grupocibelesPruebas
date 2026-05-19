<?php  //NUEVA VERSION

session_start(); 
$_SESSION['titulo']="PRESUPUESTOS";

//$_SESSION['usuario']="";
$ruta="/";

require($ruta."comprobarSesion.php");

//require($ruta."Archivos Comunes/constantes.php");
require($ruta."Archivos Comunes/cabecera.php");


if ($_SESSION["permiso_presupuestos"] == 2)
{
	echo '<button type="button" class="btn btn-info" onClick="location.href = \'presupuestos_alta.php\'">Alta/Consulta</button>';
}
else if ($_SESSION["permiso_presupuestos"] == 1)
{
	echo '<button type="button" class="btn btn-info" onClick="location.href = \'presupuestos_alta.php\'">Consulta</button>';
}


?>




<button type="button" class="btn btn-info" onClick="location.href = 'presupuestos_listado.php'">Listado</button>





<span style="float: none; width: 100%"><br>&nbsp;</span>
<?php

echo ("</div>");
echo ("</html>");

?>