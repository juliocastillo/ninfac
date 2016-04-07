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
include_once ('./common.html');
datevalidsp();
class Vista_form extends Model_form {
    public function __construct() {
    }

    public function get_form($args) {
        
        $diccionario = array(
            'form' => array(
            'action'=>$args[action],
            'FormTitle'=>$args[FormTitle],
            'id_documento' => $args[id_documento],
            'n_documento' => $args[n_documento],
            'fecha_documento' => $args[fecha_documento],
            'hecho_por' => $args[hecho_por],
			'comentario' => $args[comentario],
            'n_notaremi' => $args[n_notaremi],
            'fecha_notaremi' => $args[fecha_notaremi],
            'n_pedido' => $args[n_pedido],
            'venta_a_cta' => $args[venta_a_cta],     
            'cantidad' => $args[cantidad],
            'precio_unit' => $args[precio_unit],
            'ventas_no_sujetas' => $args[ventas_no_sujetas],
            'ventas_exentas' => $args[ventas_exentas],
            'ventas_gravadas' => $args[ventas_gravadas],    
            'tbl_detalle'=> $args[tbl_detalle]
            )
        );
        
        
        /*
         * cargar contenido de archivo
         * para hacer el parse
         */
        $tpl = file_get_contents($args[form]);
        
        /*
         * cargar listado de secciones de laboratorio
         */
        $cbo = new Htmltipo_documento();
        $lista = $cbo->llenarlista($args[tipo_documento]);
        $tpl = $this->set_var('tipo_documento', $lista, $tpl);

        $cbo = new Htmlcliente();
        $lista = $cbo->llenarlista($args[id_cliente]);
        $tpl = $this->set_var('id_cliente', $lista, $tpl);
        
        $cbo = new Htmlcondicion_pago();
        $lista = $cbo->llenarlista($args[condicion_pago]);
        $tpl = $this->set_var('condicion_pago', $lista, $tpl);
        
        $cbo = new Htmlproducto_existencia();
        $lista = $cbo->llenarlista($args[id_producto]);
        $tpl = $this->set_var('id_producto', $lista, $tpl);
        
        
        foreach ($diccionario[form] as $clave => $valor) {
            $tpl = $this->set_var($clave, $valor, $tpl);
        }
        print $tpl; //despliega la vista renderizada
    }

    public function set_var($htmlfield, $var, $tpl) {
        /*
         * asignar contenido a las variables en el html
         * solo hacer un reemplazo ya que las variables son únicas.
         */
        return str_replace('{' . $htmlfield . '}', $var, $tpl);
    }
}

class Model_form {
    public function __construct() {
        /*
         * controlador de conexion
         */
        require_once('./conexion.php');
    }
        
    function get_totales_documento($id_documento){
        $db=new MySQL();
        $sql = "SELECT 
                sum(fd.ventas_gravadas) AS ventas_gravadas,
                sum(fd.ventas_gravadas)*.13 AS iva,
               (sum(fd.ventas_gravadas)+sum(fd.ventas_gravadas)*.13) AS subtotal,
			   IF(sum(fd.ventas_gravadas)>=100,sum(fd.ventas_gravadas)*c.iva_retenido,0) AS iva_retenido,
			   (sum(fd.ventas_gravadas)+sum(fd.ventas_gravadas)*.13)-IF(sum(fd.ventas_gravadas)>=100,sum(fd.ventas_gravadas)*c.iva_retenido,0) AS venta_total
            FROM facturas_detalle fd
				LEFT JOIN facturacion f ON fd.id_documento = f.id
				LEFT JOIN clientes c ON f.id_cliente = c.id
            WHERE fd.id_documento='$id_documento'
            GROUP BY id_documento
            ";
        return $db->fetch_array($db->consulta($sql));
    }
    
    function actualizar_totales($id_documento){
        /*
         * actualizar nuevos saldos
         */
        $db = new MySQL();
        $totales = $this->get_totales_documento($id_documento);
        
        $sql = "UPDATE facturacion SET
            ventas_gravadas='$totales[ventas_gravadas]',
            iva='$totales[iva]',
            subtotal='$totales[subtotal]',
            iva_retenido='$totales[iva_retenido]', 
            venta_total='$totales[venta_total]'
        WHERE id='$id_documento'";
        $db->consulta($sql);
    }
    
    function insertar() {
        $db = new MySQL();
        $fecha_documento = datetosql($_POST[fecha_documento]);
        $fecha_notaremi = datetosql($_POST[fecha_notaremi]);
        $system_date=date("Y-m-d");
        $sql = "INSERT INTO facturacion 
            (
            tipo_documento,
            n_documento,
            fecha_documento,
            id_cliente, 
            n_notaremi, 
            fecha_notaremi, 
            condicion_pago, 
            venta_a_cta, 
            n_pedido, 
            hecho_por,
            comentario,
            date_add,
            user_add,
            estado
            ) 
            VALUE
            (
            '$_POST[tipo_documento]',
            '$_POST[n_documento]',
            '$fecha_documento',
            '$_POST[id_cliente]', 
            '$_POST[n_notaremi]', 
            '$fecha_notaremi', 
            '$_POST[condicion_pago]', 
            '$_POST[venta_a_cta]', 
            '$_POST[n_pedido]', 
            '$_POST[hecho_por]',
            '$_POST[comentario]',
            '$system_date',
            '$user_add',
            'A')";
        $db->consulta($sql);
        $id_documento = mysql_insert_id();
        
        $sql = "INSERT INTO facturas_detalle 
            (
            id_documento,
            id_producto, 
            cantidad, 
            precio_unit, 
            ventas_no_sujetas, 
            ventas_exentas, 
            ventas_gravadas,
            date_add,
            user_add,
            estado
            ) 
            VALUE
            (
            '$id_documento',
            '$_POST[id_producto]', 
            '$_POST[cantidad]', 
            '$_POST[precio_unit]', 
            '$_POST[ventas_no_sujetas]', 
            '$_POST[ventas_exentas]', 
            '$_POST[ventas_gravadas]',
            '$system_date',
            '$user_add',
            'A')";
        $db->consulta($sql);
        /*
         * actualizar nuevos saldos
         */
        $this->actualizar_totales($id_documento);
        
        return $id_documento;
    }
    
    function insertar_item($id_documento){
        $db = new MySQL();
        $sql = "INSERT INTO facturas_detalle 
            (
            id_documento,
            id_producto, 
            cantidad, 
            precio_unit, 
            ventas_no_sujetas, 
            ventas_exentas, 
            ventas_gravadas,
            date_add,
            user_add,
			estado
            ) 
            VALUE
            (
            '$id_documento',
            '$_POST[id_producto]', 
            '$_POST[cantidad]', 
            '$_POST[precio_unit]', 
            '$_POST[ventas_no_sujetas]', 
            '$_POST[ventas_exentas]', 
            '$_POST[ventas_gravadas]',
            '$system_date',
            '$user_add',
            'A')";
        $db->consulta($sql);
         /*
         * actualizar nuevos saldos
         */
        $this->actualizar_totales($id_documento);
        
    }

    
    

    function get_field_id($id){
        $db = new MySQL();
        $sql = "SELECT * FROM facturacion WHERE id='$id'";
        return $db->fetch_array($db->consulta($sql));
    }    
    
    function get_field_detalle_id($id){
        $db = new MySQL();
        $sql = "SELECT * FROM facturas_detalle WHERE id='$id'";
        return $db->fetch_array($db->consulta($sql));
    }
    
    
    
    function make_table($consulta,$id_documento){
        $db = new MySQL();
        
        $tblbody ="<table style='width: 900px; border-collapse:collapse' border='1'>";
        $tblbody .="<thead>D e t a l l e</thead>";
        $tblbody .="<tr>";
        $tblbody .="<td style='background-color: #FFCC99'>Corr</td>";
        $tblbody .="<td style='background-color: #FFCC99'>Cantidad</td>";
        $tblbody .="<td style='background-color: #FFCC99'>Producto</td>";
        $tblbody .="<td style='background-color: #FFCC99'>Precio Unitario</td>";
        $tblbody .="<td style='background-color: #FFCC99'>Ventas no sujetas</td>";
        $tblbody .="<td style='background-color: #FFCC99'>Ventas exentas</td>";
        $tblbody .="<td style='background-color: #FFCC99'>Ventas gravadas</td>";
        $tblbody .="<td style='background-color: #FFCC99' class='auto-style2'>&nbsp;</td>";
        $tblbody .="</tr>";
        $i=0;
        while ($row = $db->fetch_array($consulta)){
            $i++;
        $tblbody .= "<tr>".
            "<td>".$i."</td>".
            "<td style='text-align: center'>".$row['cantidad']."</td>".
            "<td>"."<a href='factura_ins.php?req=5&id_detalle=".$row['id']."&id_documento=".$id_documento."'>".$row['id_producto']."</a></td>".
            "<td style='text-align: right'>".$row['precio_unit']."</td>".
            "<td style='text-align: right'>".$row['ventas_no_sujetas']."</td>".    
            "<td style='text-align: right'>".$row['ventas_exentas']."</td>".
            "<td style='text-align: right'>".$row['ventas_gravadas']."</td>".
            "<td>"."<a href='factura_ins.php?req=7&id_detalle=".$row['id'].'&id_documento='.$id_documento."'>Eliminar</a>"."</td>".
            "</tr>";
        }
        $totales = $this->get_totales_documento($id_documento);
        $tblbody .="<tr><td><td><td><td><td><td>Sumas<td style='text-align: right'>".$totales['ventas_gravadas']."</td><td style='background-color: gray'></tr>";
        $tblbody .="<tr><td><td><td><td><td><td>IVA<td style='text-align: right'>".$totales['iva']."</td><td style='background-color: gray'></tr>";
        $tblbody .="<tr><td><td><td><td><td><td>Sub-total<td style='text-align: right'>".$totales['subtotal']."</td><td style='background-color: gray'></tr>";
        $tblbody .="<tr><td><td><td><td><td><td>(-) IVA Retenido<td style='text-align: right'>".$totales['iva_retenido']."</td><td style='background-color: gray'></tr>";
        $tblbody .="<tr><td><td><td><td><td><td>Venta total<td style='text-align: right'>".$totales['venta_total']."</td><td style='background-color: gray'></tr>";
        $tblbody .="</table>";
        return $tblbody;
    }
    
    function get_list_fields($id_documento) {
        $db = new MySQL();
        $sql = "SELECT 
                    fd.id,
                    fd.cantidad,
                    fd.precio_unit,
                    fd.ventas_no_sujetas,
                    fd.ventas_exentas,
                    fd.ventas_gravadas,
                    p.nombre AS id_producto
                FROM facturas_detalle fd
                LEFT JOIN producto p ON fd.id_producto = p.id
                WHERE id_documento='$id_documento'";
        return $db->consulta($sql);
    }
    
    function update_item($id, $id_documento){
        $fecha_documento = datetosql($_POST[fecha_documento]);
        $fecha_notaremi = datetosql($_POST[fecha_notaremi]);
        $system_date=date("Y-m-d");
        
//        actualizando documento factura o ccf
        $db = new MySQL();
        $sql = "UPDATE facturacion SET
            tipo_documento='$_POST[tipo_documento]',
            n_documento='$_POST[n_documento]',
            fecha_documento='$fecha_documento',
            id_cliente='$_POST[id_cliente]', 
            n_notaremi='$_POST[n_notaremi]', 
            fecha_notaremi='$fecha_notaremi', 
            condicion_pago='$_POST[condicion_pago]', 
            venta_a_cta='$_POST[venta_a_cta]', 
            n_pedido='$_POST[n_pedido]', 
            hecho_por='$_POST[hecho_por]',
            comentario='$_POST[comentario]',
            date_update='$system_date',
            user_update='$usrID'
        WHERE id='$id_documento'";
        $db->consulta($sql);
//        actualizar detalle        
        $db = new MySQL();
        $sql = "UPDATE facturas_detalle SET 
                    id_producto='$_POST[id_producto]', 
                    cantidad='$_POST[cantidad]', 
                    precio_unit='$_POST[precio_unit]', 
                    ventas_no_sujetas='$_POST[ventas_no_sujetas]', 
                    ventas_exentas='$_POST[ventas_exentas]', 
                    ventas_gravadas='$_POST[ventas_gravadas]',
                    date_update='$system_date',
                    user_update='$user_update'
                WHERE id='$id'";
        $db->consulta($sql);
        
         /*
         * actualizar nuevos saldos
         */
        $this->actualizar_totales($id_documento);
        
    }

    function delete_item($id,$id_documento){
        
//        borrando item de documento factura o ccf
        $db = new MySQL();
        $sql = "DELETE FROM facturas_detalle WHERE id='$id'";
        $db->consulta($sql);
        
         /*
         * actualizar nuevos saldos
         */
        $this->actualizar_totales($id_documento);
    }
    
    
}




extract($_GET);
extract($_POST);

if (!$req) {//ingresar nuevo registro desde cero
    $db = new MySQL();
    $vista = new Vista_form();
    $model = new Model_form();
    $args = array ( // parametro que se pasaran a la vista
            'form' => 'factura_ins.html',
            'action' => 'factura_ins.php?req=2',
            'FormTitle' => 'LABORATORIOS WÖHLER S.A. DE C.V.',
            'id_documento'=>$id_documento,
            'n_documento' => $n_documento,
            'fecha_documento' => $fecha_documento,
            'hecho_por' => $hecho_por,
			'comentario'=> $comentario,
            'fecha_nota_remision' => $fecha_nota_remision,
            'cantidad' => $cantidad,
            'precio_unit' => $precio_unit,
            'ventas_no_sujetas' => $ventas_no_sujetas,
            'ventas_exentas' => $ventas_exentas,
            'ventas_gravadas' => $ventas_gravadas,
            'tbl_detalle'=> ''
            );
    
    $vista->get_form($args);
} 
elseif ($req == 2) {//ingresar un nuevo registro
    $db = new MySQL();
    $model = new Model_form();
    $id_documento = $model->insertar();
    print "<script>window.location = 'factura_ins.php?req=3&id_documento=".$id_documento."'</script>";
}
elseif ($req == 3) {//mostrar para modificar registro
    $db = new MySQL();
    $vista = new Vista_form();
    $model = new Model_form();
    /*
     * declarar parametros para enviar a la vista
     */
    $rec = $model->get_field_id($id_documento);
    
    $consulta = $model->get_list_fields($id_documento);
    $tbl_detalle = $model->make_table($consulta,$id_documento);
    
    
    $args = array ( // parametro que se pasaran a la vista
            'form' => 'factura_ins.html',
            'action' => 'factura_ins.php?req=4&id_documento='.$id_documento,
            'FormTitle' => 'LABORATORIOS WÖHLER S.A. DE C.V.',
            'id_documento'=>$id_documento,
            'tipo_documento'=> $rec[tipo_documento],
            'n_documento'=> $rec[n_documento],
            'fecha_documento'=> datetosp($rec[fecha_documento]),
            'id_cliente'=> $rec[id_cliente], 
            'n_notaremi'=> $rec[n_notaremi], 
            'fecha_notaremi'=> datetosp($rec[fecha_notaremi]), 
            'condicion_pago'=> $rec[condicion_pago], 
            'venta_a_cta'=> $rec[venta_a_cta], 
            'n_pedido'=> $rec[n_pedido], 
            'hecho_por'=> $rec[hecho_por],
			'comentario'=> $rec[comentario],
			
            'tbl_detalle'=> $tbl_detalle
            );
    
    $vista->get_form($args);
}

elseif ($req == 4) {//ingresar un nuevo registro
    $db = new MySQL();
    $model = new Model_form();
    $model->insertar_item($id_documento);
    print "<script>window.location = 'factura_ins.php?req=3&id_documento=".$id_documento."'</script>";
}


elseif ($req == 5) {//mostrar para modificar registro
    $db = new MySQL();
    $vista = new Vista_form();
    $model = new Model_form();
    /*
     * declarar parametros para enviar a la vista
     */
    $rec = $model->get_field_id($id_documento);
    $rec_detalle = $model->get_field_detalle_id($id_detalle);
    
    $consulta = $model->get_list_fields($id_documento);
    $tbl_detalle = $model->make_table($consulta,$id_documento);
    
    
    $args = array ( // parametro que se pasaran a la vista
            'form' => 'factura_ins.html',
            'action' => 'factura_ins.php?req=6&id_documento='.$id_documento.'&id_detalle='.$id_detalle,
            'FormTitle' => 'LABORATORIOS WÖHLER S.A. DE C.V.',
            'id_documento'=>$id_documento,
            'tipo_documento'=> $rec[tipo_documento],
            'n_documento'=> $rec[n_documento],
            'fecha_documento'=> datetosp($rec[fecha_documento]),
            'id_cliente'=> $rec[id_cliente], 
            'n_notaremi'=> $rec[n_notaremi], 
            'fecha_notaremi'=> datetosp($rec[fecha_notaremi]), 
            'condicion_pago'=> $rec[condicion_pago], 
            'venta_a_cta'=> $rec[venta_a_cta], 
            'n_pedido'=> $rec[n_pedido], 
            'hecho_por'=> $rec[hecho_por],
			'comentario'=> $rec[comentario],
        
            'cantidad'=> $rec_detalle[cantidad], 
            'id_producto'=> $rec_detalle[id_producto],
            'precio_unit'=> $rec_detalle[precio_unit], 
            'ventas_no_sujetas'=> $rec_detalle[ventas_no_sujetas],
            'ventas_exentas'=> $rec_detalle[ventas_exentas], 
            'ventas_gravadas'=> $rec_detalle[ventas_gravadas],
            'tbl_detalle'=> $tbl_detalle
            );
    
    $vista->get_form($args);
}

elseif ($req == 6) {//ingresar un nuevo registro
    $db = new MySQL();
    $model = new Model_form();
    $model->update_item($id_detalle,$id_documento);
    print "<script>window.location = 'factura_ins.php?req=3&id_documento=".$id_documento."'</script>";
}

elseif ($req == 7) {//borrar un registro
    $db = new MySQL();
    $model = new Model_form();
    $model->delete_item($id_detalle,$id_documento);
    print "<script>window.location = 'factura_ins.php?req=3&id_documento=".$id_documento."'</script>";
}


?>

