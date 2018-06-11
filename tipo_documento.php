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
            'tipo_documento' => $args['tipo_documento'],
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
                    tipo_documento
                FROM tipo_documento WHERE id='$id'";
        return $db->fetch_array($db->consulta($sql));
    }
    function get_list_fields() {
        $db = new MySQL();
        $sql = "SELECT
                    id,
                    tipo_documento
                FROM tipo_documento 
                ORDER BY tipo_documento";
        return $db->consulta($sql);
    }
    function set_form($id) {
        $db = new MySQL();
        $sql = "UPDATE tipo_documento SET 
                    tipo_documento='$_POST[tipo_documento]'
                WHERE id='$id'";
        $db->consulta($sql);
    }
    function insert_form() {
        $db = new MySQL();
        $sql = "INSERT INTO tipo_documento(
                    tipo_documento) 
                VALUE (
                    '$_POST[tipo_documento]')";
        $db->consulta($sql);
    }
    
    function make_table($consulta){
        $db = new MySQL();
        while ($row = $db->fetch_array($consulta)){
        $tblbody .= "<tr>".
            "<td><a href='tipo_documento.php?req=3&id=".$row['id']."'>".$row['tipo_documento']."</td>".  
            "</tr>";
        }
        return $tblbody;
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
            'form' => 'tipo_documento.html',
            'FileName' => 'tipo_documento.php?req=2',
            'FormTitle' => 'Creación/Edición de tipo_documento',
            'id'=> '',
            'tipo_documento' => '',
            'tblbody' => $tblbody
            );
    
    $vista->get_form($args);
} 
elseif ($req == 2) {//ingresar un nuevo registro
    $db = new MySQL();
    $model = new Model_form();
    $model->insert_form($tipo_documento);
    print "<script>window.location = 'tipo_documento.php'</script>";    
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
            'form' => 'tipo_documento.html',
            'FileName' => 'tipo_documento.php?req=4',
            'FormTitle' => 'Creación/Edición de tipo_documento',
            'id'=> $rec['id'],
            'tipo_documento' => $rec['tipo_documento'],
            'tblbody' => $tblbody
            );
    $vista->get_form($args);
}

elseif ($req == 4) {//guardar lo modificado
    $db = new MySQL();
    $model = new Model_form();
    $model->set_form($id);
    print "<script>window.location = 'tipo_documento.php'</script>";    
}

?>