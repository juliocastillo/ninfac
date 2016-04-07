<?php
session_start();
extract($_GET);
require_once './conexion.php';
$db=new MySQL();
$db->consulta("UPDATE producto SET precio_costo='$precio_costo' WHERE id='$id'");