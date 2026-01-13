<?php 

session_start(); 
$_SESSION['titulo']="INFORMES DE PRODUCCIÓN";

//$_SESSION['usuario']="";
$ruta="/";

require($ruta."comprobarSesion.php");

//require($ruta."Archivos Comunes/constantes.php");
require($ruta."Archivos Comunes/cabecera.php");


?>


<button type="button" class="btn btn-info" onClick="location.href = 'informeProd_horasEmpleados.php'">Horas Trabajadas en un día</button>
<!--<button type="button" class="btn btn-info" onClick="location.href = 'informeProd_ot.php'">OT</button>-->
<button type="button" class="btn btn-info" onClick="location.href = 'informeProd_costes_ot.php'">ver una OT</button>
<button type="button" class="btn btn-info" onClick="location.href = 'informeProd_ot_por_fecha.php'">OT por fecha</button>

<!--<button type="button" class="btn btn-info" onClick="location.href = 'informeProd_mediasProcesos.php'">Medias Procesos</button>-->


<span style="float: none; width: 100%"><br>&nbsp;</span>
<?php

echo ("</div>");
echo ("</html>");

?>