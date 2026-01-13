<?php 

session_start(); 
$_SESSION['titulo']="INFORME POR OT";
$ruta="/";
require($ruta."comprobarSesion.php");
require($ruta."Archivos Comunes/cabecera.php");
?>

	

<table class="d-none d-print-block" style="width: 100%">
			
			
	<tr style="width: 100%">
		<td><img src="imagenes/Logo Grupo Cibeles.jpg"></td>
		<td align="center" style="width: 100%"><h2>Informe por OT</h2></td>
	</tr>
	<tr>
		<td align="center" colspan="2"><h2 id="InformeOt"></h2></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><p>Informe realizado el día: <?php echo date("d/m/Y"). " a las " .date("H:i:s"); ?> </p></td>
	</tr>

	<tr><td colspan="2"></td></tr>
</table>



<form align="center"  class="d-print-none" >
		OT: <input class="tamanio7" type="text" id="buscarOt" name="buscarOt" value=""></input>
		<input type="button" class="btn btn-info" value="Buscar" onClick="cargarInformePorOt()"></input>
		<button type="button" class="btn btn-info" onclick="javascript:window.print()">Imprimir</button>
		<button type="button" class="btn btn-info" onclick="cargarDatosImagenes()" data-toggle="modal" data-target="#exampleModal">Fotos</button>



</form>



<br>	
	

<table class="table" id="resultadoInforme" align="center" style="text-align: center"></table>
	
<table><tr><td><br></td></tr></table>


<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>-->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">       
		  		<span style="text-align: center" align="center">OT: <span id="numOtImagen"></span></span>
      		</div>
      		<div class="modal-body">
		 
				<div class="row">

  				<!-- Grid column -->
			 		<div class="col-md-12 d-flex justify-content-center mb-5">

						<button type="button" class="btn btn-outline-black waves-effect filter" data-rel="all">Todos</button>
						<button type="button" class="btn btn-outline-black waves-effect filter" data-rel="arranque">Arraque</button>
						<button type="button" class="btn btn-outline-black waves-effect filter" data-rel="calidad">Calidad</button>
						<button type="button" class="btn btn-outline-black waves-effect filter" data-rel="incidencia">Incidencia</button>
			  		</div>
 				 <!-- Grid column -->

				</div>
				<div class="gallery" id="gallery"></div>

		  
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
       
      		</div>
    	</div>
  </div>
</div>


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
<script  src="js/js_informeProd_ot.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script language="javascript">
	
	//document.getElementById("buscarOt").value='<?php //echo $fechaAbuscar?>';
	let params = new URLSearchParams(window.location.search);
	var parametro=params.get("presupuesto");
	
	if (parametro!=null)
	{	
		//alert(params.get("presupuesto"));
		document.getElementById("buscarOt").value = params.get("presupuesto");
		cargarInformePorOt();
		
	}
	
	
	window.onbeforeprint = (event) => {
		
		if (document.getElementById("buscarOt").value== "")
		{
			alert("Introducir una OT");
		}
		
	};
	
	
	
$(function() {
var selectedClass = "";
$(".filter").click(function(){
selectedClass = $(this).attr("data-rel");
$("#gallery").fadeTo(100, 0.1);
$("#gallery div").not("."+selectedClass).fadeOut().removeClass('animation');
setTimeout(function() {
$("."+selectedClass).fadeIn().addClass('animation');
$("#gallery").fadeTo(300, 1);
}, 300);
});
});
	

	

	
	

	//alert("La resolución de tu pantalla es: " + screen.width + " x " + screen.height) ;

</script>

<!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>-->


<!--<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>-->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>


    

<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>-->

<!-- 2012120  -->

<style>
.gallery {
-webkit-column-count: 3;
-moz-column-count: 3;
column-count: 3;
-webkit-column-width: 33%;
-moz-column-width: 33%;
column-width: 33%; }
.gallery .pics {
-webkit-transition: all 350ms ease;
transition: all 350ms ease; }
.gallery .animation {
-webkit-transform: scale(1);
-ms-transform: scale(1);
transform: scale(1); }

@media (max-width: 450px) {
.gallery {
-webkit-column-count: 1;
-moz-column-count: 1;
column-count: 1;
-webkit-column-width: 100%;
-moz-column-width: 100%;
column-width: 100%;
}
}

@media (max-width: 400px) {
.btn.filter {
padding-left: 1.1rem;
padding-right: 1.1rem;
}
}
</style>