<?
session_start();
$_SESSION['login'];
extract($_POST);
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        
    </head>
    <body>
        <form name="login" method="POST" action="" autocomplete="off">
        <div align="center">    
        <table>
            <thead><img src="./images/lw.jpg"></thead>
          <tr>
           <td rowspan="4"><img src="./images/Login_Key.jpg"></td>
           <td colspan="2">
               Ingrese su identificaci&oacute;n
           </td>
          </tr>
          <tr>
           <td>
               Identificaci&oacute;n
           </td>
           <td>
            <input type="text" name="usuario_login" id="usuario_login" value="" maxlength="50" size="20">
           </td>
          </tr>
          <tr>
           <td>
            <font>Palabra Clave</font>
           </td>
           <td>
            <input type="password" name="usuario_password" id="usuario_password" value="" maxlength="50" size="20">
           </td>
          </tr>
          <tr>
           <td colspan="2">
            <input type="submit" value="Conexión" style="float: right">
           </td>
          </tr>
        </table>        
        </div>
    </form>
        <?php
        include ("./conexion.php");
        $db = new MySQL();
        if (isset($usuario_login) && isset($usuario_password)){
            include ("./model.php");
            $model = new Model();
            $_SESSION['login'] = $model->login($usuario_login,$usuario_password);
            if($_SESSION['login'] == true){
                echo "<script>window.location = './index.php'</script>";
            }
        }
        ?>
    </body>
</html>