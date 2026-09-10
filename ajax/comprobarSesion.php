<?php

session_start();

$activa = isset($_SESSION["usuario"]) && $_SESSION["usuario"] != "";

echo json_encode(array('activa' => $activa));

?>
