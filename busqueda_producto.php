<?
session_start();
$_SESSION['login'];
extract($_GET);
if ($_SESSION['login']!=true){
    echo "<script>window.location = './login.php'</script>";
} 
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Busqueda de productos - NINFAC</title>
<!--        estilos colores y formatos propio de la aplicacion-->
        <link rel="stylesheet" type="text/css" href="./css/profesional.css">        
        
        
    </head>
    <body style="overflow-y: scroll">
        <?php 
        require './conexion.php';
        require './model.php';
        $db=new MySQL();
        $model=new Model();
        extract($_GET);
        ?>
        <form name="buscarp" method="GET" action="">
            <table class="formTABLE" width="100%">
                <th colspan="2" style="background-color: orange">Busqueda de productos</th>
                <tr>
                    <td class="titleTD">
                        Nombre:
                    </td>
                    <td class="dataTD">
                        <input type="hidden" name="field" value="<?php echo $field; ?>">
                        <input type="hidden" name="form" value="<?php echo $form; ?>">
                        <input type="text" size="30" name="nombre" value="<?=$nombre ;?>">Pre:<input type="text" size="10" name="pre_fijo" value=""><br>
                    </td>
                <tr>
                    <td>
            <input type="submit" value="Buscar">
            </td>
            </table>
        </form>
        <?php
        if ($nombre){
            $result=$model->get_buscar_existencias($nombre);
            echo "<table border='1' class='resultTable'>";
            echo "<th>Corr</th><th>ID</th><th>Nombre</th><th>Existencias</th>";
            $i=1;
            while ($row = $db->fetch_array($result)){
                ?>
                <tr>
                    <td>
                        <?=$i++; ?>
                    </td>
                    <td>
                        <?php echo $row['id']?>
                    </td>
                    <td>
                        <?php
                        if ($row['saldo_actual']>0){
                             echo "<a href='javascript:capture_id(".$row['id'].")'>".$row['nombre_producto']."</a>";
                        }
                        else {
                            echo $row['nombre_producto'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $row['saldo_actual']?>
                    </td>                   
            <?php
            }
            echo "</table>";
        }
        ?>
    </body>
    <script language="JavaScript">
        function capture_id(id){
            opener.document.<?php echo $form; ?>.<?php echo $field; ?>.value= id;
            window.close();
        }
    </script>    
    
</html>        
