<?php
session_start();
require_once ('conexion.inc');
$db = new MySQL;
$fecha_control=date("d-m-Y");
$time=date("H:i:s");
$_SESSION["user_session"];
$_SESSION["id_user_session"];
$rol=$_SESSION["q_es_session"];
$tipo_user=$_SESSION["tipo_user_session"];
$id_usuario=$_SESSION["id_user_session"];
$usuario=$_SESSION["user_session"];
$_SESSION["institucion"];
$fechaadd=date("Y-m-d");

$cid_t=$_GET['cid_t'];
$cid_c=$_GET['cid_c'];
$acn=$_GET['acn'];
$menuc=$_POST['menuc'];
$url=$_POST['url'];
$ordenc=$_POST['ordenc'];
$estado=$_POST['estado'];
$readonly=$_POST['readonly'];
$readwrite=$_POST['readwrite'];
               
       if ($acn==1){ // insertar nuevo
                    $result = $db->consulta("INSERT INTO menuc (menuc, url, ordenc, estado, id_mt, readonly, readwrite)
                                                VALUES ('$menuc', '$url', '$ordenc', '$estado', '$cid_t', '$readonly', '$readwrite')");
                    echo "<script type='text/javascript'> window.location='arbol.php'</script>";
            }
       elseif ($acn==2){ // modificar registro
               $result = $db->consulta("UPDATE menuc SET
                       menuc='$menuc',
                       url='$url',
                       estado='$estado',
                       ordenc='$ordenc',
                       readonly='$readonly',
                       readwrite='$readwrite'
                     WHERE id_mc='$cid_c'");
                }
            else{// eliminar registro
//              echo "eliminando...";
//              $db->consulta("DELETE FROM tbpoblacionmeta WHERE id='$cid'");
            }
    echo "<script type='text/javascript'> window.location='arbol.php'</script>";
    ?>
 
    