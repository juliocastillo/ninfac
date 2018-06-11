<?php
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
    <body class="PageBODY">
        <form action="" method="get" name="frm" style="background-color: white">
    <center>
    <div style="border: 1px seagreen solid;  border-radius: 1px; width: 800px; position: relative;">
    <div id='frm_errorloc' class='frm_strings' style="background-color:orange; border: 0px; text-align: center"></div>
    <table style="width:100%">
        <tr>
        <td colspan="4" align="center" style="border: 1px gainsboro outset; background: gainsboro">
            <font class="FormHeaderFONT">B&uacute;squeda de Facturaci&oacute;n</font>
        </td>
        </tr>
        <tr >
            <td class="" align="right">
                <font class="ColumnFONT"><b>N&uacute;mero de documento</b></font>
            </td><td colspan="3" align="left" class="">
                <input type="text" name="n_documento" value="<?php if(isset($n_documento)) echo $n_documento; ?>" id="n_documento" size="10" maxlength="10" tabindex="6" />
            </td>
            </td>
        </tr>
        <tr >
            <td class="" align="right">
                <font class="ColumnFONT"><b>Fecha: </b>desde</font>
            </td><td colspan="3" align="left" class="">
                <input type="text" name="finicio" value="<?php if(isset($finicio)) echo $finicio; ?>" id="finicio" size="10" maxlength="10" tabindex="6" onBlur="formatofecha(this.id, this.value); date_system_valid(this.id)" onkeyup="mascara(this,'/',patron,true)"/></input>
                <input type="button" value="..." id="finicio_btn" tabindex="7" ></input>
                <!--<font class="ColumnFONT"><b>hasta</b></font>-->
                Hasta <input type="text"  name="ffin" value="<?php if(isset($ffin)) echo $ffin; ?>" id="ffin" size="10" maxlength="10" tabindex="8" onBlur="formatofecha(this.id, this.value); date_system_valid(this.id)" onkeyup="mascara(this,'/',patron,true)" /></input>
                <input type="button" value="..." id="ffin_btn" tabindex="9" ></input>
            </td>
            </td>
        </tr>

        
            <td colspan="4" align="center" class="">
                <br><input type="submit" value="Buscar facturaci&oacute;n" tabindex="10" /></td>
        </tr>
    </table>
    </div>
</form>
<?php
if (isset($cambio_estado) && $cambio_estado=='I'){
	$model->cambiar_estado_facturacin($id,'I');
}
elseif (isset($cambio_estado) && $cambio_estado=='A'){
	$model->cambiar_estado_facturacin($id,'A');
}


if ((isset($finicio) && isset($ffin)) || isset($n_documento)) {
    if (isset($finicio)) $finicio = datetosql($finicio);
    if (isset($ffin)) $ffin = datetosql($ffin);
    $consulta=$model->get_lista_factura($finicio, $ffin, $n_documento);
    ?>
        <br>
<div align="center">
    <table class="FormTABLE" style="border: 1px gainsboro solid; border-collapse: collapse;">
        <td colspan="9"><a href="factura_ins.php">Agregar facturaci&oacute;n</a></td>
        <tr><td style="border: 1px black solid; padding: 0px 1px 0px 1px">Corr</td>
            <td style="border: 1px black solid">Tipo de documento</td>
            <td style="border: 1px black solid;" width="100px">N&uacute;mero Doc.</td>
            <td style="border: 1px black solid;" width="100px">Fecha Doc.</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Cliente</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Condici&oacute;n pago</td>
            <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Estado</td>
                <?php 
                $i=0;
                while ($row = $db->fetch_array($consulta)){
                    $i++;
                    ?>
                    <tr>
                       <td align="center" style="background-color: white;border: 1px gainsboro solid; padding: 3px"><font class="ColumnFONT"><?php echo $i; ?></td>
                       <td style="background-color: white; border: 1px gainsboro solid; font-family: arial; font-size: 15px">
                           <a href="factura_ins.php?req=3&id_documento=<?=$row['id']; ?>"><? echo $row['tipo_documento']; ?></a></td>
                       <td style="background-color: white;border: 1px gainsboro solid;"><font class="ColumnFONT"><? echo $row['n_documento']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT"><? echo $row['fecha_documento']; ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-size: 10px;"><? echo htmlentities($row['id_cliente']); ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT"><? echo htmlentities($row['condicion_pago']); ?></td>
                       <td style="background-color: white;border: 1px gainsboro solid;font-weight: normal;"><font class="ColumnFONT">
                            <?php
                            if ($row['estado']=='A') { ?>
                                    <a href="busqueda_factura.php?cambio_estado=I&id=<?php echo $row['id']; ?>"><?php echo $row['estado']; ?></a>
                                    <?php
                            }
                            else {
                                    ?>
                                    <a href="busqueda_factura.php?cambio_estado=A&id=<?php echo $row['id']; ?>"><?php echo $row['estado']; ?></a>
                                    <?php
                            }
                            ?>
                            </td>
						
                        <?php
                }
                ?>

        </tr>
    </table>
</div>
<?php } ?>
    
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

        var frmvalidator  = new Validator("frm");
        frmvalidator.EnableOnPageErrorDisplaySingleBox();
        frmvalidator.EnableMsgsTogether();
		
        frmvalidator.addValidation("finicio", "required", "Ingrese fecha desde");
        frmvalidator.addValidation("ffin", "required", "Ingrese fecha de fin");

</script>

</html>
