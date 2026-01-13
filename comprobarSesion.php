<?php 

if ($_SESSION["usuario"]<>"")
{
}
else
{
	header('Location: index.php');	
}

?>