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
        <title>Agregar recibo</title>
<!--        estilos colores y formatos propio de la aplicacion-->
        <link rel="stylesheet" type="text/css" href="./css/profesional.css">        
        
        
    </head>
    <body style="overflow-y: scroll">
        <?php
        //archivos comunes *********************************
        include_once ('./common.html');
        include ("./conexion.php");
        //**************************************************
        include ("./llenarlistas.php");
        include ("./tools.php");
        include ("./model.php");
        datevalidsp();
        $model=new Model();
        $db=new MySQL();
        extract($_GET);
        extract($_POST);

        if($q==1 && !isset($submit)){// si ya existe el recibo y se va a modificar
        } elseif($q==1 && isset($submit)){//esto es insertar un nuevo registro
            $model->set_recibo_cxc($id_documento, $id_vendedor, $n_recibo,  datetosql($fecha_recibo),
                $tipo_pago,$n_cheque,$banco,$monto);
            echo "<script>opener.location.reload();window.close()</script>";
        } elseif(($q==2 || $q==3) && !isset($submit)){ // esto es modificar registro           
            $row=$model->get_recibo_cobro_id($id_recibo);
            $id_documento = $row['id_documento'];
            $id_vendedor = $row['id_vendedor'];
            $n_recibo = $row['n_recibo'];
            $fecha_recibo = $row['fecha_recibo'];
            $tipo_pago = $row['tipo_pago'];
            $n_cheque = $row['n_cheque'];
            $banco = $row['banco'];
            $monto = $row['monto'];
        } elseif($q==2 && isset($submit)){ // esto es guardar registro modificado registro               
            $model->update_recibo_cxc($id_documento, $id_recibo, $id_vendedor, $n_recibo,  datetosql($fecha_recibo),
                $tipo_pago,$n_cheque,$banco,$monto);
            echo "<script>opener.location.reload();window.close()</script>";
        } elseif($q==3 && isset($submit)){ //esto es eliminar un registro
            $model->delete_recibo_cobro($id_documento,$id_recibo);
            echo "<script>opener.location.reload();window.close()</script>";
        }
        
        ?>
        <form name="frm" method="POST" action="">
            <div id='frm_errorloc' class='frm_strings' style="background-color:magenta; border-radius: 4px; border: 0px ; width:500px; text-align: center"></div>
            <input type="hidden" name="id_recibo" id="id_recibo" value="<?=$id_recibo ?>"> 
            <input type="hidden" name="q" id="q" value="<?=$q ?>">
            <input type="hidden" name="id_documento" id="id_documento" value="<?=$id_documento ?>">
            <table class="FormTABLE" width="400px" style="border: 1px gainsboro solid; border-collapse: collapse;">
                <thead>Recibo de cuentas por cobrar</thead>
                <tr>
                    <td width="150px" style="border: 1px black solid">Vendedor</td>
                    <td style="border: 1px black solid;">
                    <select name="id_vendedor" id="id_vendedor">
                        <option value="000">...Seleccione...</option>
                        <?php
                            $cbo = new Htmlvendedor();
                            $list = $cbo->llenarlista($id_vendedor);
                            echo $list;
                        ?>
                    </select>               
                    </td>
                    <tr>
                    <td style="border: 1px black solid;" width="100px">N&uacute;mero de recibo</td>
                    <td style="border: 1px black solid; padding: 0px 1px 0px 1px;"><font class="ColumnFONT">
                        <input name="n_recibo" id="n_recibo" value="<?=$n_recibo ?>" size="10" maxlength="10"> 
                    </td>
                    <tr>
                    <td style="border: 1px black solid;" width="100px">Fecha de recibo</td>
                    <td style="border: 1px black solid; padding: 0px 1px 0px 1px;font-weight: normal;"><font class="ColumnFONT">
                        <input type="text"  name="fecha_recibo" value="<?php echo $fecha_recibo; ?>" id="fecha_recibo" size="10" maxlength="10" onBlur="formatofecha(this.id, this.value); date_system_valid(this.id)" onkeyup="mascara(this,'/',patron,true)" /></input>
                        <input type="button" value="..." id="fecha_recibo_btn" tabindex="9" >
                    </td>
                    <tr>
                    <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Tipo de pago</td>
                    <td style="border: 1px black solid; padding: 0px 1px 0px 1px;font-size: 10px;">
                        <select name="tipo_pago" id="tipo_pago">
                        <?php
                            $cbo = new Htmltipo_pago();
                            $list = $cbo->llenarlista($tipo_pago);
                            echo $list;
                        ?>
                        </select>  
                    </td>
                    <tr>
                        <td style="border: 1px black solid; padding: 0px 1px 0px 1px">N&uacute;mero de cheque</td>
                    <td style="border: 1px black solid; padding: 0px 1px 0px 1px;font-weight: normal;">
                        <input name="n_cheque" id="n_cheque" value="<?=$n_cheque ?>" size="10" maxlength="10">
                    </td>
                    </td>
                    <tr>
                    <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Banco</td>
                    <td style="border: 1px black solid; padding: 0px 1px 0px 1px;font-weight: normal;">
                        <select name="banco" id="banco">
                            <option value="000">...Seleccione...</option>
                        <?php
                            $cbo = new Htmlbancos();
                            $list = $cbo->llenarlista($banco);
                            echo $list;
                        ?>4
                        </select>  

                    </td>
                    <tr>
                    <td style="border: 1px black solid; padding: 0px 1px 0px 1px">Monto</td>
                    <td style="border: 1px black solid; padding: 0px 1px 0px 1px;font-weight: normal;">
                        <input name="monto" id="monto" value="<?=$monto ?>" size="10" maxlength="10" style="text-align: right">
                    </td>
                    <tr>
                        <td colspan="2">
                            <? if($q=='1'){ ?>
                                <input type="submit" name="submit" value="Guardar">
                            <? } elseif($q=='2') { ?>
                                <input type="submit" name="submit" value="Modificar">
                            <? } elseif($q=='3') { ?>
                                <input type="submit" name="submit" value="Eliminar">
                                <?
                               }
                            ?>
                        </td>
                    </tr>
            </table>
        </form>
    </body>
<script language="JavaScript">
     Calendar.setup({
        inputField : "fecha_recibo",
        trigger    : "fecha_recibo_btn",
        onSelect   : function() { this.hide() },
        showTime   : 12,
        weekNumbers: true,
        //dateFormat : "%Y-%m-%d %I:%M %p"
        dateFormat : "%d/%m/%Y",
        align      : ""
    });
    
     function capture_id(){
            

            window.close();
     }
     
    var frmvalidator  = new Validator("frm");
    frmvalidator.EnableOnPageErrorDisplaySingleBox();
    frmvalidator.EnableMsgsTogether();

    frmvalidator.addValidation("id_vendedor", "dontselect=000", "Seleccione tipo de documento");
    frmvalidator.addValidation("n_recibo", "required", "Ingrese numero de recibo");
    frmvalidator.addValidation("n_recibo", "greaterthan=0", "Ingrese numero de recibo mayor a 0");

    frmvalidator.addValidation("fecha_recibo", "required", "Ingrese fecha de recibo");
    frmvalidator.addValidation('n_cheque','required','Ingrese numero de cheque',
            "VWZ_IsListItemSelected(document.forms['frm'].elements['tipo_pago'],2)");
    frmvalidator.addValidation('banco','dontselect=000','Ingrese banco',
            "VWZ_IsListItemSelected(document.forms['frm'].elements['tipo_pago'],2)");
    frmvalidator.addValidation('n_cheque','greaterthan=0','Ingrese numero de cheque mayor de 0',
            "VWZ_IsListItemSelected(document.forms['frm'].elements['tipo_pago'],2)");
//    frmvalidator.addValidation("id_cliente", "dontselect=000", "Seleccione cliente");
//    frmvalidator.addValidation("condicion_pago", "dontselect=000", "Seleccione condicion pago");
//
    frmvalidator.addValidation("monto", "required", "Ingrese monto");
    frmvalidator.addValidation("monto", "greaterthan=0", "Monto debe ser mayor de 0");
//    frmvalidator.addValidation("id_producto", "dontselect=000", "Seleccione producto");
//    frmvalidator.addValidation("precio_unit", "required", "Ingrese precio unitario");
//    //frmvalidator.addValidation("precio_unit", "greaterthan=0", "Ingrese precio unitario");//
//    mvalidator.addValidation("ventas_gravadas", "required", "Ingrese ventas gravadas");
//    mvalidator.addValidation("ventas_gravadas", "greaterthan=0", "Ventas gravadas no valido");
     
</script>    
    
</html>        
