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
extract($_GET);
datevalidsp();
$model=new Model();
$db=new MySQL();
?>
<head>
    <title>Ninfac</title>
        <!--creado por Julio Castillo, abril de 2013-->
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <link rel="stylesheet" href="" type="text/css">
    <script type="text/javascript">
            function popup(URL) {
            myWindow = window.open(URL, '" + "', 'scrollbars=yes, width=600, height=600, top = 50');
        }
    </script>
        
     
<form name="frm" post="GET">
    <div style="border: 1px seagreen solid;  border-radius: 1px; width: 800px; position: relative;">
    <table border="0">
        <tbody>
            <tr>
                <td>Seleccione vendedor</td>
            </tr>
            <tr>
                <td>
                    <select name="id_vendedor" id="id_vendedor">
                        <option value="000">...Seleccione...</option>
                        <?php
                            $cbo = new Htmlvendedor();
                            $list = $cbo->llenarlista($id_vendedor);
                            echo $list;
                        ?>
                    </select><input type="submit" name="submit" value="Actualizar">
                </td>
            </tr>
            <tr>
            <tr>
                <td>Seleccionar Cliente</td>
            </tr>
            <tr>
                <td>
                <select name="id_cliente" id="id_cliente">
                    <option value="000">...Seleccione...</option>
                    <?php
                            $cbo = new Htmlcliente();
                            $list = $cbo->llenarlista();
                            echo $list;
                    ?>
                </select>
                    <input onclick="popup('busqueda_cliente.php?field=id_cliente&amp;form=frm');" tabindex="" type="button" value="..." /></td>
            </tr>
            
            <tr>
                <td>
                    <input type="submit" name="submit" value="Guardar">
                </td>
            </tr>
        </tbody>
    </table>
    </div>
</form>
<?
    if (isset($id_cliente) && $id_cliente!='000' && $submit=="Guardar"){
        $model->set_vendedor_cliente($id_vendedor, $id_cliente);
    }
    
    if ($submit=="Eliminar"){
        $model->delete_vendedor_cliente($id);
    }
?>
<?
    $consulta = $model->get_lista_vendedor_cliente($id_vendedor);
?>
<table border="1">
    <thead>
        <tr>
            <th>Ruta del vendedor</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?
        while ($row = $db->fetch_array($consulta)){
        ?>
        <tr>
            <td><?=htmlentities($row['nombre_cliente_comercial']); ?></td>
            <th><a href="asig_clientes.php?id_vendedor=<?=$id_vendedor;?>&submit=Eliminar&id=<?=$row['id'];?>">Eliminar</th>
        </tr>
        <?
        }
        ?>
    </tbody>
</table>
