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
datevalidsp();
$model=new Model();
$db=new MySQL();
extract($_GET);
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
    <div style="border: 1px seagreen solid;  border-radius: 1px; width: 600px; position: relative;">
    <table style="width:100%">
        <tr>
        <td colspan="2" align="center" style="border: 1px gainsboro outset; background: gainsboro">
            <font class="FormHeaderFONT">B&uacute;squeda de cuentas por cobrar</font>
        </td>
        </tr>
        <tr >
            <td width="300px" align="right">
                <font class="ColumnFONT"><b>N&uacute;mero de documento</b></font>
            </td>
            <td>
                <input type="text" name="n_documento" value="<?php echo $n_documento; ?>" id="n_documento" size="10" maxlength="10" tabindex="6" />
            </td>
            </td>
        </tr>
        <tr>
            <td width="300px" align="right">
                <font class="ColumnFONT"><b>Tipo documento </b></font>
            </td>
            <td>
                <select name="tipo_documento" id="tipo_documento">
                        <?php
                            $cbo = new Htmltipo_documento();
                            $list = $cbo->llenarlista($tipo_documento);
                            echo $list;
                        ?>
                </select>
            </td>
        </tr>

        
            <td colspan="2" align="center" class="">
                <br><input type="submit" value="Buscar facturaci&oacute;n" tabindex="10" /></td>
        </tr>
    </table>
    </div>
</form>


<?
if ($tipo_documento || $n_documento!="") {
    $consulta=$model->get_lista_factura('','', $n_documento,$tipo_documento);
    ?>
        <br>
<div align="center">
    <table width="400px" class="FormTABLE" style="border: 1px gainsboro solid; border-collapse: collapse;">
        <?php 
        while ($row = $db->fetch_array($consulta)){
            $id_documento = $row['id'];
        ?>
        <tr>
            <td width="150px" style="border: 1px black solid">Tipo de documento</td>
            <td style="border: 1px black solid;">
                           <? echo $row['tipo_documento']; ?></td>
            <tr>
            <td style="border: 1px black solid;" width="100px">N&uacute;mero Doc.</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px;"><font class="ColumnFONT"><? echo $row['n_documento']; ?></td>
            <tr>
            <td style="border: 1px black solid;" width="100px">Fecha Doc.</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px;font-weight: normal;"><font class="ColumnFONT"><? echo $row['fecha_documento']; ?></td>
            <tr>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Cliente</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px;font-size: 10px;"><? echo htmlentities($row['id_cliente']); ?></td>
            <tr>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Condici&oacute;n pago</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px;font-weight: normal;"><font class="ColumnFONT"><? echo htmlentities($row['condicion_pago']); ?></td>
            <tr>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Saldo actual</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px;font-weight: normal;"><font class="ColumnFONT">
                <?=$row['saldo_actual']?>
            </td>
            <tr>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Estado</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px;font-weight: normal;"><font class="ColumnFONT">
                <?=$row['estado']?>
            </td>
            <tr>
            <td colspan="2"><a href="javascript: void(0)" onclick="popup('cxc_ins.php?q=1&id_documento=<?=$row['id']; ?>')">Agregar pago</a></td>
            <?php
            }
            ?>

        </tr>
    </table>
</div>
<? }

if ($id_documento!="") {
    $consulta=$model->get_recibo_cobro($id_documento);
    ?>
        <br>
<div align="center">
    <table class="FormTABLE" style="border: 1px gainsboro solid; border-collapse: collapse;">
        <tr><td style="border: 1px black solid; padding: 0px 1px 0px 1px">Corr</td>
            <td style="border: 1px black solid">Recibo No.</td>
            <td style="border: 1px black solid;" width="100px">Fecha recibo</td>
            <td style="border: 1px black solid;" width="100px">Tipo de pago</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Monto</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Eliminar</td>
                <?php 
                $i=0;
                while ($row = $db->fetch_array($consulta)){
                    $i++;
                    ?>
                    <tr>
                       <td align="center" style="background-color: white;border: 1px gainsboro solid; padding: 3px"><font class="ColumnFONT"><?php echo $i; ?></td>
                       <td style="background-color: white; border: 1px gainsboro solid; font-family: arial; font-size: 15px">
                           <a href="" onclick="popup('cxc_ins.php?q=2&id_recibo=<?=$row['id']; ?>')"><?=$row['n_recibo']; ?></a></td>
                       <td style="background-color: white;border: 1px gainsboro solid;"><font class="ColumnFONT"><? echo $row['fecha_recibo']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT"><? echo $row['tipo_pago']; ?></td>
                       <td align="right" style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT"><? echo htmlentities($row['monto']); ?></td>
                       <td align="center" style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT">
                           <a href="" onclick="popup('cxc_ins.php?q=3&id_recibo=<?=$row['id']; ?>')"><img src="./images/b_drop.png"></a>
                       </td>
						
                        <?php
                }
                ?>

        </tr>
    </table>
</div>
<? }

?>
    
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
