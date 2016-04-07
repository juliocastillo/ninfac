<?
session_start();
$_SESSION['login'];
extract($_GET);
if ($_SESSION['login']!=true){
    echo "<script>window.location = './login.php'</script>";
} 
//archivos comunes *********************************
include_once ('./common.html');
include ("./conexion.php");
require_once('./menuprincipal.php');
//**************************************************
include ("./llenarlistas.php");
include ("./tools.php");
include ("./model.php");
include_once ('./common.html');
datevalidsp();
$model=new Model();
$db=new MySQL();
?>

<html>
    <head>
        <title>Ninfac</title>
        <!--creado por Julio Castillo, abril de 2013-->
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <link rel="stylesheet" href="" type="text/css">
       
    </head>
    <body class="PageBODY"><form action="" method="get" name="buscar" style="background-color: white">
    <center>
    <div style="border: 1px seagreen solid;  border-radius: 1px; width: 800px; position: relative;">
    <table style="width:100%">
        <tr>
        <td colspan="4" align="center" style="border: 1px gainsboro outset; background: gainsboro">
            <font class="FormHeaderFONT">Entradas de Producto terminado y devoluciones</font>
        </td>
        </tr>
        <tr >
            <td class="" align="right">
                <font class="ColumnFONT"><b>Fecha: desde </b></font>
            </td><td colspan="3" align="left" class="">
                <input type="text" name="finicio" value="<?php echo $finicio; ?>" id="finicio" size="10" maxlength="10" tabindex="6" onBlur="formatofecha(this.id, this.value); date_system_valid(this.id)" onkeyup="mascara(this,'/',patron,true)"/></input>
                <input type="button" value="..." id="finicio_btn" tabindex="7" ></input>
                <!--<font class="ColumnFONT"><b>hasta</b></font>-->
                Hasta <input type="text"  name="ffin" value="<?php echo $ffin; ?>" id="ffin" size="10" maxlength="10" tabindex="8" onBlur="formatofecha(this.id, this.value); date_system_valid(this.id)" onkeyup="mascara(this,'/',patron,true)" /></input>
                <input type="button" value="..." id="ffin_btn" tabindex="9" ></input>
            </td>
            </td>
        </tr>
            <td colspan="4" align="center" class="">
               <br><input type="submit" value="Buscar entradas de producto" tabindex="10" /></td>
        </tr>
    </table>
    </div>
</form>
<?        
if ($finicio && $ffin) {
    $consulta=$model->get_lista_entrada(datetosql($finicio),  datetosql($ffin));
    ?>
        <br>
<div align="center">
    <table class="FormTABLE" style="border: 1px gainsboro solid; border-collapse: collapse;">
        <td colspan="9"><a href="entrada_producto_terminado.php">Agregar entrada de producto</a></td>
        <tr><td style="border: 1px black solid; padding: 0px 1px 0px 1px">Corr</td>
            <td style="border: 1px black solid">Tipo de entrada</td>
            <td style="border: 1px black solid;" width="100px">No. Entrada</td>
            <td style="border: 1px black solid;" width="100px">Fecha</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Producto</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Lote</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Cantidad</td>
                <?php 
                $i=0;
                while ($row = $db->fetch_array($consulta)){
                    $i++;
                    ?>
                    <tr>
                       <td align="center" style="background-color: white;border: 1px gainsboro solid; padding: 3px"><font class="ColumnFONT"><?php echo $i; ?></td>
                       <td style="background-color: white; border: 1px gainsboro solid; font-family: arial; font-size: 15px">
                           <a href="entrada_producto_terminado.php?req=3&id=<?=$row['id']; ?>"><? echo $row['tipo_entrada']; ?></a></td>
                       <td style="background-color: white;border: 1px gainsboro solid;"><font class="ColumnFONT"><? echo $row['n_entrada']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT"><? echo $row['fecha_entrada']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT"><? echo $row['nombre_producto']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT"><? echo $row['lote']; ?></td>
                       <td align="right" style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT"><? echo $row['cantidad']; ?></td>
                        <?php
                }
                ?>

        </tr>
    </table>
</div>
<? } ?>
    
</body>
    <script language="Javascript">
    Calendar.setup({
        inputField : "finicio",
        trigger    : "finicio_btn",
        onSelect   : function() { this.hide() },
        showTime   : 12,
        weekNumbers: true,
        //dateFormat : "%Y-%m-%d %I:%M %p"
        dateFormat : "%d/%m/%Y",
        align      : ""
    });
    
    Calendar.setup({
        inputField : "ffin",
        trigger    : "ffin_btn",
        onSelect   : function() { this.hide() },
        showTime   : 12,
        weekNumbers: true,
        //dateFormat : "%Y-%m-%d %I:%M %p"
        dateFormat : "%d/%m/%Y",
        align      : ""
    });

        function popup(URL) {
            myWindow = window.open(URL, '" + "', 'scrollbars=yes, width=600, height=600, top = 50');
        }
    </script>

</html>
