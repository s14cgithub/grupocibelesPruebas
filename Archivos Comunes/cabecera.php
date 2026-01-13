<?php 
include('/constantes.php');	
include("/codigoInclude.php");
?>

<!doctype html>
<html>
<head>
	

	<!-------------------------------------------------->
	<!--<meta http-equiv="Expires" content="0">
 
	<meta http-equiv="Last-Modified" content="0">
 
	<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0>
	<meta http-equiv="Cache-Control" content="post-check=0, pre-check=0", false">
 
	<meta http-equiv="Pragma" content="no-cache">-->
	
	
	
	<!-------------------------------------------------->
	
	
	
	<meta name="language" content="Spanish">
	<meta http-equiv="Content-Language" content="es">
	<meta charset="utf-8">
	<!--<link rel="stylesheet" type="text/css" href="css/reboot.css"/>-->
	
	
	
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	
	

    <!-- Bootstrap CSS -->
   
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	
	
	<link rel="icon" type="image/png" href="imagenes/apple-touch-icon.png"/>
    <link rel="apple-touch-icon" href="imagenes/apple-touch-icon.png" />
	
	
	
	
	<!--------->
	
	<!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>-->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	
	<!--------->
	
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
	
	
	<!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
	
	
	
	
	<link rel="stylesheet" type="text/css" href="css/estilos.css"/>
	<!--<script  src="js/scripts.js" type="text/javascript" language="JavaScript" charset="UTF-8"></script>-->
	
	
	<!--<script src="https://kit.fontawesome.com/41bcea2ae3.js" crossorigin="anonymous"></script>-->

	<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js" integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous"></script>


	
	
</head>
	
<body>
	
	<?php 
		$url =  $_SERVER['REQUEST_URI'];
		$url_separado = explode("/",$url);
		$grabarFranqueo = true;
					
		if ($url_separado[count($url_separado)-1] != 'franqueoGrabacion.php')
		{
			$grabarFranqueo = false;
		}
					
		
		if ($grabarFranqueo==false)
		{
			echo ' <div class="container">';
		}
		else
		{
			echo ' <div class="containerFranqueo">';
		}
	
					
	?>
	
	
    	 
    <!--<div class="container">-->
		<!--<div class="row">
		<div class="col-md-12">-->
		<table class="table d-print-none">
			
			
			<tr class="miH1">
				<td><img src="imagenes/grupoCibeles_blanco.png" onClick="pantallaCompleta()"></td>
				<td style="vertical-align: middle"><h1 style="color: white"><?php echo $_SESSION['titulo']; ?></h1></td>
				<!--<td><h1><?php //echo $_SESSION['usuario']; ?></h1></td>-->
				<td align="right">
				
				<?php 					
					
					
					$paginaActual = substr($_SERVER["REQUEST_URI"],  strripos($_SERVER["REQUEST_URI"],'/')+1);					
					
					if ($paginaActual=="admEmisionFacturasPendientesMensuales.php")
					{
						echo ('<button type="button" class="btn btn-secondary" id="cabeceraBotonAtras" aria-haspopup="true" aria-expanded="false"  onclick="location.href = \'admFacturacion.php\';">ir Atras </button>');
					}
					//else if ($paginaActual=="admEmisionFacturasPendientes.php"||$paginaActual=="admEmisionFacturasPendientes.php?clayma=0"||$paginaActual=="admEmisionFacturasPendientes.php?clayma=1")
					else if (strpos($paginaActual,"admEmisionFacturasPendientes.php")!==false)
					{
						echo ('<button type="button" class="btn btn-secondary" id="cabeceraBotonAtras" aria-haspopup="true" aria-expanded="false"  onclick="location.href = \'admFacturacion.php\';">ir Atras </button>');
					}
					else if ($paginaActual=="admFacturacion.php")
					{
						echo ('<button type="button" class="btn btn-secondary" id="cabeceraBotonAtras" aria-haspopup="true" aria-expanded="false"  onclick="location.href = \'principal.php\';">ir Atras </button>');
					}
					else if ($paginaActual=="administracion.php")
					{
						echo ('<button type="button" class="btn btn-secondary" id="cabeceraBotonAtras" aria-haspopup="true" aria-expanded="false"  onclick="location.href = \'principal.php\';">ir Atras </button>');
					}
					else if ($paginaActual=="principal.php")
					{
						
					}
					else if ($paginaActual=="pda_gestion.php")
					{
						echo ('<button type="button" class="btn btn-secondary" id="cabeceraBotonAtras" aria-haspopup="true" aria-expanded="false"  onclick="location.href = \'principal.php\';">ir Atras </button>');
					}
					else if ($paginaActual=="presupuestos.php")
					{
						echo ('<button type="button" class="btn btn-secondary" id="cabeceraBotonAtras" aria-haspopup="true" aria-expanded="false"  onclick="location.href = \'principal.php\';">ir Atras </button>');
					}
					else if ($paginaActual=="presupuestos_alta.php")
					{
						echo ('<button type="button" class="btn btn-secondary" id="cabeceraBotonAtras" aria-haspopup="true" aria-expanded="false"  onclick="location.href = \'presupuestos.php\';">ir Atras </button>');
					}
					else if ($paginaActual=="presupuestos_listado.php")
					{
						echo ('<button type="button" class="btn btn-secondary" id="cabeceraBotonAtras" aria-haspopup="true" aria-expanded="false"  onclick="location.href = \'presupuestos.php\';">ir Atras </button>');
					}
					else if ($paginaActual=="ot.php")
					{
						echo ('<button type="button" class="btn btn-secondary" id="cabeceraBotonAtras" aria-haspopup="true" aria-expanded="false"  onclick="location.href = \'principal.php\';">ir Atras </button>');
					}
					else if ($paginaActual=="prefactura.php")
					{
						//echo ('<button type="button" class="btn btn-secondary" id="cabeceraBotonAtras" aria-haspopup="true" aria-expanded="false"  onclick="location.href = \'admEmisionFacturasPendientes.php\';">ir Atras </button>');
					}
					else if ($grabarFranqueo==false)
					{
						echo ('<button type="button" class="btn btn-secondary" id="cabeceraBotonAtras" aria-haspopup="true" aria-expanded="false"  onclick="history.back(-1);">ir Atras </button>');
					}
					else if (strpos($paginaActual,'clientesClayma.php')!==false ||strpos($paginaActual,'clientes.php')!==false)
					{
						echo ('<button type="button" class="btn btn-secondary" id="cabeceraBotonAtras" aria-haspopup="true" aria-expanded="false"  onclick="location.href = \'admFacturacion.php\';">ir Atras </button>');
					}
					
					//echo $paginaActual;
					
					?>
				
				<!---------------------PRUEBAS MAPA------------------------------------------------------------------->
				
					<!--<div class="btn-group">
						<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Mapa
					  	</button>
						
						
						
 
						
						
				  		<div class="dropdown-menu" style="margin-right: 100px;">
							<a class="dropdown-item" href="./principal.php" id="menu_prinpipal">Ir a Principal</a>							
							<a class="dropdown-item" href="#"><span  data-toggle="modal" data-target="#cambiarContrasenia" data-whatever="@mdo">Cambiar Contraseña</span></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="./index.php">Cerrar Sesion</a>
				  		</div>
					</div>-->
					
					
					<!---------------------------------------------------------------------------------------->
					
					<div class="btn-group">
						<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php echo $_SESSION['usuario']; ?>
					  	</button>
				  		<div class="dropdown-menu" style="margin-right: 100px;">
							<a class="dropdown-item" href="./principal.php" id="menu_prinpipal">Ir a Principal</a>
							<!--<a class="dropdown-item" href="#" onclick="history.back(-1);" id="menu_irAtras">Ir Atrás</a>-->
							<a class="dropdown-item" href="#"><span  data-toggle="modal" data-target="#cambiarContrasenia" data-whatever="@mdo">Cambiar Contraseña</span></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="./index.php">Cerrar Sesion</a>
				  		</div>
					</div>
				
				
			</td>
				
				
				<!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#cambiarContrasenia" data-whatever="@mdo"  style="height: =5px;line-height: 5px;" onClick="pasarDatosPresupuesto(\''+datos[contador]["presupuesto"]+'\',\''+fechaAceptacion+'\',\''+fechaCompromiso+'\')">OT</button>-->
				
				
				
				
			</tr>
			
			<tr><td colspan="3"></td></tr>
		</table>
		
		
		<!--<div class="row miH1">
			<div class="col-sm"><img src="imagenes/Logo Grupo Cibeles.jpg" width="75%"></div>
			<div class="col-sm"><?php //echo $_SESSION['titulo']; ?></div>
			<div class="col-sm"><?php //echo $_SESSION['usuario']; ?></div>
  		</div>-->
		
		
		<div class="modal fade" id="cambiarContrasenia"  tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="ModalLabel">CAMBIAR CONTRASEÑA:</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body">
				<form>                 
				  <div class="form-group">
					<label class="col-form-label">Contraseña Actual:</label>
					<input type="password" class="form-control" id="passActual">
				  </div>
				  <div class="form-group">
					<label class="col-form-label">Contraseña Nueva:</label>
					<input type="password" class="form-control" id="passNueva">
				  </div>	
				<div class="form-group">
					<label class="col-form-label">Repetir contraseña Nueva:</label>
					<input type="password" class="form-control" id="passNuevaRepe">
				  </div>
				</form>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" data-dismiss="" onClick="cambiarContrasenia()">Cambiar Contraseña</button>
			  </div>
			</div>
		  </div>
		</div> 
		
	
		
          
		<!------------------------------------------------------------------------->


<!--<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>-->



<!------------------------------------------------------------------->
		
		
    	
		
		
		
		<!--<div class="table">
			
			<div>
				
				<div class="tablaCelda alignCentro"><img src="imagenes/Logo Grupo Cibeles.jpg" width="150px" onClick="pantallaCompleta()"></div>
				<div class="tablaCelda alignCentro"><h1><?php //echo $_SESSION['titulo']; ?></h1></div>
				<div class="tablaCelda alignCentro"><h1><?php //echo $_SESSION['usuario']; ?></h1></div>
			
			</div><!-- class="tablaFila" -->
			
			<!--<div class="caption"><hr></div>-->
			
		<!--</div> <!-- class="tabla" -->
		
		
		
					




		