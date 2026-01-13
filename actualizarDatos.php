<?php 

session_start(); 
$_SESSION['titulo']="Actualizacion de Datos";

//$_SESSION['usuario']="";
$ruta="/";

require($ruta."comprobarSesion.php");


require($ruta."Archivos Comunes/cabecera.php");






?>



<?php 
	if ($_SESSION["permiso_actualizarDatos"]==1||$_SESSION["permiso_actualizarDatos"]==2)
	{
		echo ('<table style="float: left">');
		echo '<th>Del Viejo al Nuevo</th>';
		
		echo ('<tr><td><button type="button" class="btn btn-info" onClick="actualizarDatosClientesAccessAsql()">Clientes</button></td></tr>');	
		//echo ('<tr><td><button type="button" class="btn btn-info" onClick="actualizarDatosPresupuestosAccessAsql()">Presupuestos</button></td></tr>');	
		
		echo ('</table>');		
		
		
		echo ('<table  style="margin-left: 50%">');	
		echo '<th>Del Nuevo Al Viejo</th>';				
		echo ('<tr><td><button type="button" class="btn btn-info" onClick="traspasarDatosFranqueoSqlAaccess()">Franqueo</button></td></tr>');
		echo ('<tr><td>&nbsp;</td></tr>');
		echo ('<tr><td>&nbsp;</td></tr>');
		echo ('<tr><td><button type="button" class="btn btn-info" onClick="restaurarF12()">restaurarF12</button></td></tr>');
		echo ('</table>');
		
		
		
	}
	

?>



<p id="error"></p>


<span style="float: none; width: 100%"><br>&nbsp;</span>
<?php

echo ("</div>");

echo ("</html>");

?>

