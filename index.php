<?php
session_start();
$_SESSION['login'];
extract($_GET);
if ($salir==1){
    $_SESSION['login']=false;
} 

if ($_SESSION['login']==true){
    require_once('common.html');
    include ("./conexion.php");
    require_once('menuprincipal.php');
} else {
    echo "<script>window.location = './login.php'</script>";
} 
?>


<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Ninfac</title>
        
    </head>
    <body>
    </body>    
</html>        
        
        
        
        
