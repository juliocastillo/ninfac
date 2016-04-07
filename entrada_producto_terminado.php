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
            'action'=>$args['action'],
            'FormTitle'=>$args['FormTitle'],
            'n_entrada' => $args['n_entrada'],
            'fecha_entrada' => $args['fecha_entrada'],
            'lote' => $args['lote'],
            'cantidad' => $args['cantidad'],
            'precio_unit' => $args['precio_unit'],
            'comentario' => $args['comentario'],
            'ID' => $args['id'],
            'tbl_detalle'=> $args[tbl_detalle]
            )
        );
        
        
        /*
         * cargar contenido de archivo
         * para hacer el parse
         */
        $tpl = file_get_contents($args['form']);
        
        /*
         * cargar listado de secciones de laboratorio
         */
        $cbo = new Htmltipo_entrada();
        $lista = $cbo->llenarlista($args['tipo_entrada']);
        $tpl = $this->set_var('tipo_entrada', $lista, $tpl);

        $cbo = new Htmlproducto();
        $lista = $cbo->llenarlista($args['id_producto']);
        $tpl = $this->set_var('id_producto', $lista, $tpl);
        
        
        foreach ($diccionario['form'] as $clave => $valor) {
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
    
    
    function insertar() {
        $db = new MySQL();
        $fecha_entrada = datetosql($_POST[fecha_entrada]);
        $system_date=date("Y-m-d");
        $sql = "INSERT INTO entrada_producto_terminado 
            (
            tipo_entrada,
            n_entrada,
            fecha_entrada, 
            id_producto, 
            lote, 
            cantidad,
            precio_unit,
            comentario,
            date_add,
            user_add,
            estado
            ) 
            VALUE
            (
            '$_POST[tipo_entrada]',
            '$_POST[n_entrada]',
            '$fecha_entrada', 
            '$_POST[id_producto]', 
            '$_POST[lote]', 
            '$_POST[cantidad]', 
            '$_POST[precio_unit]',
            '$_POST[comentario]',
            '$system_date',
            '$user_add',
            'A')
            ON DUPLICATE KEY UPDATE cantidad='$_POST[cantidad]', precio_unit='$_POST[precio_unit]', comentario='$_POST[comentario]'";
        $db->consulta($sql);
    }
    function actualizar($id) {
        $db = new MySQL();
        $fecha_entrada = datetosql($_POST[fecha_entrada]);
        $system_date=date("Y-m-d");
        $sql = "UPDATE entrada_producto_terminado SET
            tipo_entrada='$_POST[tipo_entrada]',
            n_entrada='$_POST[n_entrada]',
            fecha_entrada='$fecha_entrada',
            id_producto='$_POST[id_producto]',
            lote='$_POST[lote]',  
            cantidad='$_POST[cantidad]',
            precio_unit='$_POST[precio_unit]',
            comentario='$_POST[comentario]',
            date_update='$system_date',
            user_update='$user_add'
			WHERE id='$id'";
        $db->consulta($sql);
    }
    
    function get_field_id($id){
        $db = new MySQL();
        $sql = "SELECT * FROM entrada_producto_terminado WHERE id='$id'";
        return $db->fetch_array($db->consulta($sql));
    }

	function eliminar($id){
        $db = new MySQL();
        $sql = "DELETE FROM entrada_producto_terminado WHERE id='$id'";
		$db->consulta($sql);
    }
    
    function make_table($consulta,$id_documento){
        $db = new MySQL();
        
        $tblbody ="<table style='width: 900px; border-collapse:collapse' border='1'>";
        $tblbody .="<thead>D e t a l l e</thead>";
        $tblbody .="<tr>";
        $tblbody .="<td style='background-color: #FFCC99'>Corr</td>";
        $tblbody .="<td style='background-color: #FFCC99'>Tipo entrada</td>";
        $tblbody .="<td style='background-color: #FFCC99'>Producto</td>";
        $tblbody .="<td style='background-color: #FFCC99'>Lote</td>";
        $tblbody .="<td style='background-color: #FFCC99'>Presentacion</td>";
        $tblbody .="<td style='background-color: #FFCC99'>Cantidad</td>";
        $tblbody .="<td style='background-color: #FFCC99'>Precio unitario</td>";
        $tblbody .="</tr>";
        $i=0;
        while ($row = $db->fetch_array($consulta)){
            $i++;
        $tblbody .= "<tr>".
            "<td>".$i."</td>".
            "<td style='text-align: center'>".$row['tipo_entrada']."</td>".
            "<td>"."<a href='entrada_producto_terminado.php?req=3&id=".$row['id']."'>".$row['nombre_producto']."</a></td>".
            "<td style='text-align: right'>".$row['lote']."</td>".
            "<td style='text-align: right'>".$row['presentacion']."</td>".    
            "<td style='text-align: right'>".$row['cantidad']."</td>".
            "<td style='text-align: right'>".$row['precio_unit']."</td>".
            "</tr>";
        }
        $tblbody .="</table>";
        return $tblbody;
    }
    function get_list_fields($tipo_entrada,$n_entrada,$fecha_entrada) {
        $db = new MySQL();
        $sql = "SELECT 
                e.id,
                t.tipo_entrada,
                e.n_entrada,
                DATE_FORMAT(e.fecha_entrada,'%d/%m/%Y') fecha_entrada,
                e.id_producto,
                p.nombre AS nombre_producto,
                e.lote, 
                p.presentacion, 
                e.cantidad,
                e.precio_unit,
                e.comentario 
            FROM  
            entrada_producto_terminado e
            LEFT JOIN tipo_entrada t ON e.tipo_entrada = t.id
            LEFT JOIN producto p ON e.id_producto = p.id
            WHERE e.tipo_entrada = '$tipo_entrada' AND e.n_entrada = '$n_entrada' AND e.fecha_entrada = '$fecha_entrada'";
        return $db->consulta($sql);
    }
}




extract($_GET);
extract($_POST);

if (!$req) {//ingresar nuevo registro desde cero
    $db = new MySQL();
    $vista = new Vista_form();
    $model = new Model_form();
    
    $consulta = $model->get_list_fields($tipo_entrada,$n_entrada,  datetosql($fecha_entrada));
    $tbl_detalle = $model->make_table($consulta,$id);

    
    $args = array ( // parametro que se pasaran a la vista
            'form' => 'entrada_producto_terminado.html',
            'action' => 'entrada_producto_terminado.php?req=2',
            'FormTitle' => 'ENTRADA DE PRODUCTO TERMINADO',
            'tipo_entrada'=> $tipo_entrada,
            'n_entrada' => $n_entrada,
            'fecha_entrada' => $fecha_entrada,
        
            'tbl_detalle'=> $tbl_detalle
            );
    
    $vista->get_form($args);
} 
elseif ($req == 2) {//ingresar un nuevo registro
    $db = new MySQL();
    $model = new Model_form();
    $model->insertar();
    print "<script>window.location = 'entrada_producto_terminado.php?tipo_entrada=".$tipo_entrada.'&n_entrada='.$n_entrada."&fecha_entrada=".$fecha_entrada."'</script>";
}
elseif ($req == 3) {//mostrar para modificar registro
    $db = new MySQL();
    $vista = new Vista_form();
    $model = new Model_form();
    /*
     * declarar parametros para enviar a la vista
     */
    $rec = $model->get_field_id($id);
    
    $consulta = $model->get_list_fields($rec['tipo_entrada'],$rec['n_entrada'],$rec['fecha_entrada']);
    $tbl_detalle = $model->make_table($consulta,$id);
    
    $args = array ( // parametro que se pasaran a la vista
            'form' => 'entrada_producto_terminado.html',
            'action' => 'entrada_producto_terminado.php?req=4&id='.$id,
            'FormTitle' => 'ENTRADA DE PRODUCTO TERMINADO',
            'tipo_entrada'=> $rec['tipo_entrada'],
            'n_entrada' => $rec['n_entrada'],
            'fecha_entrada' => datetosp($rec['fecha_entrada']),
            'id_producto'=>$rec['id_producto'],
            'lote'=>$rec['lote'],
            'cantidad'=>$rec['cantidad'],
            'precio_unit'=>$rec['precio_unit'],
            'comentario'=>$rec['comentario'],
            'id' => $id,
            'tbl_detalle'=> $tbl_detalle
            );
    
    $vista->get_form($args);
}
elseif ($req == 4) {//actualizar registro
    $db = new MySQL();
    $model = new Model_form();
    $model->actualizar($id);
    print "<script>window.location = 'entrada_producto_terminado.php?tipo_entrada=".$tipo_entrada.'&n_entrada='.$n_entrada."&fecha_entrada=".$fecha_entrada."'</script>";
}

elseif ($req == 5) {//eliminar una entrada de producto
    $db = new MySQL();
    $model = new Model_form();
    $model->eliminar($id);
    print "<script>window.location = 'busqueda_entrada_producto_terminado.php?finicio=".$finicio."&ffin=".$ffin."'</script>";
}

?>
