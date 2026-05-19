

<?php 

/*
session_unset();
session_start();
*/

/////////////

session_start();
session_unset();
session_destroy();
session_start();

$_SESSION['titulo']="GESTIÓN GRUPOCIBELES";
$_SESSION['usuario']="";

$ruta="/";
//require($ruta."Archivos Comunes/constantes.php");
require($ruta."Archivos Comunes/cabecera.php");

?>


<table class="table sinBorde">
	<tr>
		<td align="right" >Usuario:</td>
		<td><input type="text" id="usuario" name="usuario" onKeyPress="verSiEsIntro_Login_usuario(event)"></input></td>
	</tr>

	<tr>
		<td align="right">Contraseña:</td>
		<td><input type="password" id="contrasena" name="contrasena" onKeyPress="verSiEsIntro_Login_contrasena(event)"></input></td>
	</tr>

	<tr>
		<td></td>
		<td align="right"><input type="button" id="login" name="login" value="Acceder" class="btn btn-info" onClick="comprobarLogin()"></td>

	</tr>

</table>





<?php

echo ("</div></div></div>");


echo ('<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>');
echo ("</body>");
echo ("</html>");




?>

<script  src="js/js_Index.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<!--<script type="text/javascript"> 
			alert("La resolución de tu pantalla es: " + screen.width + " x " + screen.height) ;
		</script>-->