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
        require_once ('./llenarlistas.php'); 
    }

    public function get_form($args) {
        
        $diccionario = array(
            'form' => array(
            'FileName'=>$args['FileName'],
            'FormTitle'=>$args['FormTitle'],
            'id'=>$args['id'],
            'codigo' => $args['codigo'],
            'nombre' => $args['nombre'],
            'precio_cls_iva' => $args['precio_cls_iva'],
            'precio_clc_iva' => $args['precio_clc_iva'],
            'precio_pc_iva' => $args['precio_pc_iva'],
            'precio_costo' => $args['precio_costo'],
            'vineta' => $args['vineta'],
            'tblbody'=>$args['tblbody']
            )
        );
        /*
         * cargar contenido de archivo
         * para hacer el parse
         */

        $tpl = file_get_contents($args['form']);
        
        foreach ($diccionario['form'] as $clave => $valor) {
            $tpl = $this->set_var($clave, $valor, $tpl);
        }
        
        
        $cbo=new HtmlGrupo();
        $lista=$cbo->llenarlista($args['id_grupo']);
        $tpl = $this->set_var('id_grupo', $lista, $tpl);
        
        
        $cbo=new HtmlPresentacion();
        $lista=$cbo->llenarlista($args['presentacion'],$args['presentacion']);
        $tpl = $this->set_var('presentacion', $lista, $tpl);
        
        $cbo=new Htmlestado();
        $lista=$cbo->llenarlista($args['estado']);
        $tpl = $this->set_var('estado', $lista, $tpl);
        
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
    function get_field_id($id){
        $db = new MySQL();
        $sql = "SELECT
                    id,
                    codigo,
                    id_grupo,
                    nombre,
                    presentacion,
                    precio_cls_iva,
                    precio_clc_iva,
                    precio_pc_iva,
                    precio_costo,
                    vineta,
                    IF(estado='A', 'Habilitado', 'Deshabilitado') estado
                FROM producto WHERE id='$id'";
        return $db->fetch_array($db->consulta($sql));
    }
    function get_list_fields() {
        $db = new MySQL();
        $sql = "SELECT 
                    id,
                    codigo,
                    id_grupo,
                    nombre,
                    presentacion,
                    precio_cls_iva,
                    precio_clc_iva,
                    precio_pc_iva,
                    precio_costo,
                    vineta,
                    IF(estado='A', 'Habilitado', 'Deshabilitado') estado
                FROM producto 
                ORDER BY id_grupo,nombre";
        return $db->consulta($sql);
    }
    function set_form($id) {
        $system_date=date("Y-m-d");
        $db = new MySQL();
        $sql = "UPDATE producto SET 
                    codigo='$_POST[codigo]',
                    id_grupo='$_POST[id_grupo]',
                    nombre='$_POST[nombre]',
                    presentacion='$_POST[presentacion]',
                    precio_cls_iva='$_POST[precio_cls_iva]',
                    precio_clc_iva='$_POST[precio_clc_iva]',
                    precio_pc_iva='$_POST[precio_pc_iva]',
                    precio_costo='$_POST[precio_costo]',
                    vineta='$_POST[vineta]',
                    estado='$_POST[estado]',
                    user_update=1,
                    date_update='$system_date'
                WHERE id='$id'";
        $db->consulta($sql);
    }
    function insert_form() {
        $db = new MySQL();
        $sql = "INSERT INTO producto(
                    codigo,
                    id_grupo,
                    nombre,
                    presentacion,
                    precio_cls_iva,
                    precio_clc_iva,
                    precio_pc_iva,
                    precio_costo,
                    vineta,
                    user_add,
                    date_add,
                    estado) 
                VALUE (
                    '$_POST[codigo]',
                    '$_POST[id_grupo]',
                    '$_POST[nombre]',
                    '$_POST[presentacion]',
                    '$_POST[precio_cls_iva]',
                    '$_POST[precio_clc_iva]',
                    '$_POST[precio_pc_iva]',
                    '$_POST[precio_costo]',
                    '$_POST[vineta]',
                    1,
                    '$system_date',
                    '$_POST[estado]')";
        $db->consulta($sql);
    }
    
    function make_table($consulta){
        $db = new MySQL();
        while ($row = $db->fetch_array($consulta)){
        $tblbody .= "<tr>".
            "<td>".$row['codigo']."</td>".
            "<td>".$row['id_grupo']."</td>".
            "<td><a href='productos.php?req=3&id=".$row['id']."'>".$row['nombre']."</td>".  
            "<td>".$row['presentacion']."</td>".
            "<td>".$row['precio_cls_iva']."</td>".
            "<td>".$row['precio_clc_iva']."</td>".
            "<td>".$row['precio_pc_iva']."</td>".
            "<td> <input style='text-align: right' name='precio_costo[]' id='precio_costo' value='".$row['precio_costo']."' size='10px'".
            "onblur='runajax(\"producto_ajax.php?id=$row[id]&precio_costo=\"+this.value,\"div_mess\")'></td>".
            "<td>".$row['vineta']."</td>".  
            "</tr>";
        }
        return $tblbody;
    }
    function set_estado($id,$estado){
        $db = new MySQL();
        $sql = "UPDATE producto SET estado = '$estado' WHERE id='$id'";
        $db->consulta($sql);
    }
}




extract($_GET);
extract($_POST);

if (!$req) {//ingresar nuevo registro desde cero
    $db = new MySQL();
    $vista = new Vista_form();
    $model = new Model_form();
    /*
     * declarar parametros para enviar a la vista
     */
    $consulta = $model->get_list_fields();
    $tblbody = $model->make_table($consulta);
    $args = array ( // parametro que se pasaran a la vista
            'form' => 'productos.html',
            'FileName' => 'productos.php?req=2',
            'FormTitle' => 'Creación/Edición de productos',
            'id'=> '',
            'codigo' => '',
            'id_grupo' => '',
            'nombre' => '',
            'presentacion' => '',               
            'giro' => '',
            'Precio_cls_iva' => '',
            'Precio_clc_iva' => '',
            'Precio_pc_iva' => '',
            'Precio_costo' => '',
            'vineta' => '',
            'estado' => '',
            'tblbody' => $tblbody
            );
    
    $vista->get_form($args);
} 
elseif ($req == 2) {//ingresar un nuevo registro
    $db = new MySQL();
    $model = new Model_form();
    $model->insert_form($codigo,$nombre,$nombre_comercial,$metodologia,$reporte,$neg,$ind,$pos,$min,$max);
    print "<script>window.location = 'productos.php'</script>";    
}
elseif ($req == 3) {//mostrar para modificar registro
    $db = new MySQL();
    $vista = new Vista_form();
    $model = new Model_form();
    /*
     * declarar parametros para enviar a la vista
     */
    $rec = $model->get_field_id($id);
    $consulta = $model->get_list_fields();
    $tblbody = $model->make_table($consulta);
    $args = array ( // parametro que se pasaran a la vista
            'form' => 'productos.html',
            'FileName' => 'productos.php?req=4',
            'FormTitle' => 'Creación/Edición de productos',
            'id'=> $rec['id'],
            'codigo' => $rec['codigo'],
            'id_grupo' => $rec['id_grupo'],
            'nombre' => $rec['nombre'],
            'presentacion' => $rec['presentacion'],               
            'precio_cls_iva' => $rec['precio_cls_iva'],
            'precio_clc_iva' => $rec['precio_clc_iva'],
            'precio_pc_iva' => $rec['precio_pc_iva'],
            'precio_costo' => $rec['precio_costo'],
            'vineta' => $rec['vineta'],
            'estado' => $rec['estado'],
            'tblbody' => $tblbody
            );
    $vista->get_form($args);
}

elseif ($req == 4) {//guardar lo modificado
    $db = new MySQL();
    $model = new Model_form();
    $model->set_form($id);
    print "<script>window.location = 'productos.php'</script>";    
}

elseif ($req == 5) {//cambiar el estado
    $db = new MySQL();
    $model = new Model_form();
    $rec = $model->get_field_id($id);
    if ($rec['estado']=='Habilitado')
        $estado = 'I';
    else 
        $estado = 'A';
    
    $model->set_estado($id,$estado);
    print "<script>window.location = 'productos.php'</script>";    
}

?>