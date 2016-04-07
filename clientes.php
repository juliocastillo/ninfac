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
            'nrc' => $args['nrc'],
            'nombre' => $args['nombre'],
            'nombre_comercial' => $args['nombre_comercial'],               
            'giro' => $args['giro'],
            'nit' => $args['nit'],
            'dir' => $args['dir'],
            'dir2' => $args['dir2'],
            'tel' => $args['tel'],
            'venta_a_cta' => $args['venta_a_cta'],
            'iva_retenido' => $args['iva_retenido'],
            'codigo_proveedor' => $args['codigo_proveedor'],    
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
        
        
        $cbo=new Htmldepartamento();
        $lista=$cbo->llenarlista($args['dep']);
        $tpl = $this->set_var('dep', $lista, $tpl);
        
        
        $cbo=new HtmlMunicipio();
        $lista=$cbo->llenarlista($args['dep'],$args['mun']);
        $tpl = $this->set_var('mun', $lista, $tpl);
        
        $cbo=new HtmlZona();
        $lista=$cbo->llenarlista($args['zona']);
        $tpl = $this->set_var('zona', $lista, $tpl);
        
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
                    nrc,
                    nombre,
                    nombre_comercial,
                    giro,
                    nit,
                    dir,
                    dir2,
                    tel,
                    dep,
                    mun,
                    zona,
                    venta_a_cta,
                    iva_retenido,
                    codigo_proveedor,
                    IF(estado='A', 'Habilitado', 'Deshabilitado') estado
                FROM clientes WHERE id='$id'";
        return $db->fetch_array($db->consulta($sql));
    }
    function get_list_fields() {
        $db = new MySQL();
        $sql = "SELECT 
                    id,
                    codigo,
                    nrc,
                    CONCAT(nombre,' ',nombre_comercial) nombre,
                    nombre_comercial,
                    giro,
                    nit,
                    CONCAT(dir,' ',dir2) dir,
                    dir2,
                    tel,
                    dep,
                    mun,
                    zona,
                    venta_a_cta,
                    iva_retenido,
                    codigo_proveedor,
                    IF(estado='A', 'Habilitado', 'Deshabilitado') estado
                FROM clientes 
                ORDER BY nombre";
        return $db->consulta($sql);
    }
    function set_form($id) {
        $system_date=date("Y-m-d");
        $db = new MySQL();
        $sql = "UPDATE clientes SET 
                    codigo='$_POST[codigo]',
                    nrc='$_POST[nrc]',
                    nombre='$_POST[nombre]',
                    nombre_comercial='$_POST[nombre_comercial]',
                    giro='$_POST[giro]',
                    nit='$_POST[nit]',
                    dir='$_POST[dir]',
                    dir2='$_POST[dir2]',
                    tel='$_POST[tel]',
                    dep='$_POST[dep]',
                    mun='$_POST[mun]',
                    zona='$_POST[zona]',
                    venta_a_cta='$_POST[venta_a_cta]',
                    iva_retenido='$_POST[iva_retenido]',
                    codigo_proveedor='$_POST[codigo_proveedor]',
                    user_modify=1,
                    date_modify='$system_date'
                WHERE id='$id'";
        $db->consulta($sql);
    }
    function insert_form() {
        $db = new MySQL();
        $sql = "INSERT INTO clientes(
                    codigo,
                    nrc,
                    nombre,
                    nombre_comercial,
                    giro,
                    nit,
                    dir,
                    dir2,
                    tel,
                    dep,
                    mun,
                    zona,
                    venta_a_cta,
                    iva_retenido,
                    codigo_proveedor,
                    estado) 
            VALUE (
                    '$_POST[codigo]',
                    '$_POST[nrc]',
                    '$_POST[nombre]',
                    '$_POST[nombre_comercial]',
                    '$_POST[giro]',
                    '$_POST[nit]',
                    '$_POST[dir]',
                    '$_POST[dir2]',
                    '$_POST[tel]',
                    '$_POST[dep]',
                    '$_POST[mun]',
                    '$_POST[zona]',
                    '$_POST[venta_a_cta]',
                    '$_POST[iva_retenido]',
                    '$_POST[codigo_proveedor]',
                    'A')";
        $db->consulta($sql);
    }
    
    function make_table($consulta){
        $db = new MySQL();
        while ($row = $db->fetch_array($consulta)){
        $tblbody .= "<tr>".
            "<td>".$row['id']."</td>".
            "<td>".$row['codigo']."</td>".
            "<td>".$row['nombre']."</td>".  
            "<td>".$row['dir']."</td>".                              
            "<td>".$row['zona']."</td>".  
            "<td>"."<a href='clientes.php?req=5&id=".$row['id']."'>".$row['estado']."</a>"."</td>".
            "<td>"."<a href='clientes.php?req=3&id=".$row['id']."'>Modificar</a>"."</td>".
            "</tr>";
        }
        return $tblbody;
    }
    function set_estado($id,$estado){
        $db = new MySQL();
        $sql = "UPDATE clientes SET estado = '$estado' WHERE id='$id'";
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
            'form' => 'clientes.html',
            'FileName' => 'clientes.php?req=2',
            'FormTitle' => 'Creación/Edición de clientes',
            'id'=> '',
            'codigo' => '',
            'nrc' => '',
            'nombre' => '',
            'nombre_comercial' => '',               
            'giro' => '',
            'nit' => '',
            'dir' => '',
            'dir2' => '',
            'dep' => '',
            'mun' => '',
            'zona' => '',
            'tel' => '',
            'venta_a_cta' => '',
            'iva_retenido' => '',
            'codigo_proveedor' => '',             
            'estado' => '',
            'tblbody' => $tblbody
            );
    
    $vista->get_form($args);
} 
elseif ($req == 2) {//ingresar un nuevo registro
    $db = new MySQL();
    $model = new Model_form();
    $model->insert_form($codigo,$nombre,$nombre_comercial,$metodologia,$reporte,$neg,$ind,$pos,$min,$max);
    print "<script>window.location = 'clientes.php'</script>";    
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
            'form' => 'clientes.html',
            'FileName' => 'clientes.php?req=4',
            'FormTitle' => 'Creación/Edición de clientes',
            'id'=> $rec['id'],
            'codigo' => $rec['codigo'],
            'nrc' => $rec['nrc'],
            'nombre' => $rec['nombre'],
            'nombre_comercial' => $rec['nombre_comercial'],               
            'giro' => $rec['giro'],
            'nit' => $rec['nit'],
            'dir' => $rec['dir'],
            'dir2' => $rec['dir2'],
            'dep' => $rec['dep'],
            'mun' => $rec['mun'],
            'zona' => $rec['zona'],
            'tel' => $rec['tel'],
            'venta_a_cta' => $rec['venta_a_cta'],
            'iva_retenido' => $rec['iva_retenido'],
            'codigo_proveedor' => $rec['codigo_proveedor'],             
            'estado' => $rec['estado'], 
            'tblbody' => $tblbody
            );
    $vista->get_form($args);
}

elseif ($req == 4) {//guardar lo modificado
    $db = new MySQL();
    $model = new Model_form();
    $model->set_form($id);
    print "<script>window.location = 'clientes.php'</script>";    
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
    print "<script>window.location = 'clientes.php'</script>";    
}

?>