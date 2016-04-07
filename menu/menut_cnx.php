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

$cid_s=$_GET['cid_s'];
$cid_t=$_GET['cid_t'];
$acn=$_GET['acn'];
$menut=$_POST['menut'];
$url=$_POST['url'];
$ordent=$_POST['ordent'];
$estado=$_POST['estado'];
$readonly=$_POST['readonly'];
$readwrite=$_POST['readwrite'];
               
       if ($acn==1){ // insertar nuevo
                    $result = $db->consulta("INSERT INTO menut (menut, url, ordent, estado, id_ms, readonly, readwrite)
                                                VALUES ('$menut', '$url', '$ordent', '$estado', '$cid_s', '$readonly', '$readwrite')");
                    echo "<script type='text/javascript'> window.location='arbol.php'</script>";
            }
       elseif ($acn==2){ // modificar registro
               $result = $db->consulta("UPDATE menut SET
                       menut='$menut',
                       url='$url',
                       estado='$estado',
                       ordent='$ordent',
                       readonly='$readonly',
                       readwrite='$readwrite'
                     WHERE id_mt='$cid_t'");
                }
            else{// eliminar registro
//              echo "eliminando...";
//              $db->consulta("DELETE FROM tbpoblacionmeta WHERE id='$cid'");
            }
    echo "<script type='text/javascript'> window.location='arbol.php'</script>";
    ?>     