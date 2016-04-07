<?php
session_start();
extract($_GET);
require_once './conexion.php';
require_once './llenarlistas.php';
$cbomun=new HtmlMunicipio();
$listamun=$cbomun->llenarlista($dep, $mun);
print "<select name='mun' id='mun'>";
print $listamun;
print "</select>";