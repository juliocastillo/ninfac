<?php
session_start();
$_SESSION['login'];
extract($_GET);
if (isset($salir)){
    $_SESSION['login']=false;
} 

if ($_SESSION['login']==true){
    require_once('common.html');
    include ("./conexion.php");
    echo "<text style='font-size:10px;'>".$_SESSION['username']."</text>";
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
        
        
        
        
