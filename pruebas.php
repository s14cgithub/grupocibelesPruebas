
<?php 
$precioNetoTotal = 61.95;
$iva = round($precioNetoTotal * 0.21,2);
$precioTotal = round($precioNetoTotal * 1.21,2);
$provision=0;//??????????????

echo "<br>iva: ".$iva;
echo "<br>total: ".$precioTotal; 

?>


<!doctype html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

	  
	  <!--<link rel="stylesheet" href="css/estilos - copia.css" >-->
    <title>Hello, world!</title>
  </head>
  <body>
    
	  
	  <div class="container">
			<!--<div class="row">
				<div class="col-xs-2">
					<img src="imagenes/Logo Grupo Cibeles.jpg" width="auto">
				</div>
				<div class="col-xs-5">
					<h4>Gestión GrupoCibeles</h1>
				</div>
				<div class="col-xs-5">
					<h4>hola</h4>
				</div>
				
			</div>-->
		  <table class="table">
			<tr>
				<td><img src="imagenes/Logo Grupo Cibeles.jpg" width="150px"></td>
				<td><h1>Gestión GrupoCibeles</h1></td>
				<td><h1></h1></td>
			</tr>
			<tr><td colspan="3"></td></tr>
			<!--<tr><td>&nbsp;</td></tr>	-->		
			</table>
		  
		  
		  <table class="table">
			<tr>
				<td align="right">Usuario:</td>
				<td><input type="text" id="usuario" name="usuario"></input></td>
			</tr>

			<tr>
				<td align="right">Contraseña:</td>
				<td><input type="password" id="contrasena" name="contrasena"></input></td>
			</tr>

			<tr>
				<td></td>
				<td align="right"><input type="button" id="login" name="login" value="Acceder" onClick="comprobarLogin()"></td>

			</tr>

		</table>
		  
		  
		  
	</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

  
   
  </body>
</html>