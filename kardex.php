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
//require_once('./menuprincipal.php');
//**************************************************
include ("./llenarlistas.php");
include ("./tools.php");
include ("./model.php");
include_once ('./common.html');
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
    <body class="PageBODY">   
<?php        
if ($finicio) {
    $consulta=$model->get_lista_kardex($id_producto, datetosql($finicio), datetosql($ffin));
    ?>
        <br>
<div align="center">
    <?php
        $rec = $model->busqueda_producto_id($id_producto);
		echo 'INVENTARIO DE PRODUCTO TERMINADO: '.$rec['nombre'].' '.$rec['presentacion'].'<br>';
        echo 'EMPRESA: LABORATORIOS WOHLER S.A. DE .C.V. NIT: 0614-090282-003-7  NRC: 7142-0<br>';
		echo 'Articulo 142-A Codigo de comercio<br>';
        echo 'PERIODO: del '.$finicio.' al '.$ffin.'<br>';
    ?>
    <table class="FormTABLE" style="border: 1px gainsboro solid; border-collapse: collapse;">
        <tr><td rowspan="2" style="border: 1px black solid; padding: 0px 1px 0px 1px">Corr</td>
            <td rowspan="2" style="border: 1px black solid; padding: 0px 1px 0px 1px">Fecha de op.</td>
            <td colspan="2" style="border: 1px black solid; padding: 0px 1px 0px 1px">Tipo y No. de documento</td>
            <td rowspan="2" style="border: 1px black solid; padding: 0px 1px 0px 1px">Nombre Proveedor</td>
            <td colspan="2" style="border: 1px black solid; padding: 0px 1px 0px 1px">Entradas</td>
            <td colspan="2" style="border: 1px black solid; padding: 0px 1px 0px 1px">Salidas</td>
            <td colspan="2" style="border: 1px black solid; padding: 0px 1px 0px 1px">Saldo</td>

        <tr>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Tipo</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">No.Doc</td>            
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Unidades</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Precio</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Unidades</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Precio</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Unidades</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Precio</td>

        <tr>
           <td style="background-color: white;border: 1px gainsboro solid; padding: 3px"><font class="ColumnFONT"><?php echo 1; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;"><font class="ColumnFONT"><? echo $finicio; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;"><font class="ColumnFONT"><? echo 'Saldo anterior'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT"><? echo '---'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT"><? echo '---'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo '---'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo '---'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo '---'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo '---'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo $saldo; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo $rec['precio_costo'] ?></td>
            
                <?php 
                $i=1;
                while ($row = $db->fetch_array($consulta)){
                    $i++;
                    ?>
                    <tr>
                       <td style="background-color: white;border: 1px gainsboro solid; padding: 3px"><font class="ColumnFONT"><?php echo $i; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;"><font class="ColumnFONT"><? echo datetosp($row['fecha']); ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;"><font class="ColumnFONT"><? echo $row['tipo_documento']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT"><? echo $row['n_documento']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT"><? echo $row['proveedor']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo $row['cant_entrada']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo $row['precio_entrada']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo $row['cant_salida']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo $row['precio_salida']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo $saldo-$row['cant_salida']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo $row['monto']; ?></td>
                        <?php
                        $saldo = $saldo + $row['cant_entrada'] - $row['cant_salida'];
                        $entradas = $entradas +  $row['cant_entrada'];
                        $salidas = $salidas + $row['cant_salida'];
                }
                ?>
        <tr>
           <td style="background-color: white;border: 1px gainsboro solid; padding: 3px"><font class="ColumnFONT"><?php echo '---'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;"><font class="ColumnFONT"><? echo '---'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;"><font class="ColumnFONT"><? echo 'Totales'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo '---'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo '---'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo $entradas; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo '---'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo $salidas; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo '---'; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo $saldo; ?></td>
           <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;" align="right"><font class="ColumnFONT"><? echo number_format($saldo*$rec['precio_costo'],2); ?></td>
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
