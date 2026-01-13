<?php 

session_start(); 
$_SESSION['titulo']="MATERIALES";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	
	
	?>

<ul class="nav nav-tabs">
	<li class="nav-item">
		<a id="pestania_tamanos" class="nav-link active" aria-current="page" href="#" onclick="activar_Tamanio();">Tamaños</a>
	</li>
	<li class="nav-item">
		<a id="pestania_conversionTamano"  class="nav-link" aria-current="page" href="#" onclick="activar_ConversionTamano();">Conversion Tamaño Real</a>
	</li>
	<li class="nav-item">
		<a id="pestania_Tipos"  class="nav-link" aria-current="page" href="#" onclick="activar_Tipos();">Tipos</a>
	</li>
	<li class="nav-item">
		<a id="pestania_Acabados"  class="nav-link" aria-current="page" href="#" onclick="activar_Acabados();">Acabados</a>
	</li>
	<li class="nav-item">
		<a id="pestania_Gramaje"  class="nav-link" aria-current="page" href="#" onclick="activar_Gramaje();">Gramaje</a>
	</li>
</ul>	
<!-- <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Dropdown</a>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="#">Action</a></li>
      <li><a class="dropdown-item" href="#">Another action</a></li>
      <li><a class="dropdown-item" href="#">Something else here</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="#">Separated link</a></li>
    </ul>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Link</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
  </li>
</ul>

-->





<br><br>

	<table align="center" class="tabla">	
	<tbody id="listado" name="listado"></tbody>
	


</table>

<br>

	<?php
	
	
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->







<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<?php




echo ("</div>");
echo ("</body>");
echo ("</html>");

?>

<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_materiales.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script> 

<script language="javascript">

	
	activar_Tamanio() ;
	document.getElementById("button-up").addEventListener("click", scrollUp);
	
</script>