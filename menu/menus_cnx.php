<?php
session_start();
//require_once 'variables_globales.php';
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

$cid_p=$_GET['cid_p'];
$cid_s=$_GET['cid_s'];
$acn=$_GET['acn'];
$menus=$_POST['menus'];
$urls=$_POST['urls'];
$ordens=$_POST['ordens'];
$estado=$_POST['estado'];
$readonly=$_POST['readonly'];
$readwrite=$_POST['readwrite'];
               
       if ($acn==1){ // insertar nuevo
                    $result = $db->consulta("INSERT INTO menus (menus, urls, ordens, estado, id_mp, readonly, readwrite)
                                                VALUES ('$menus', '$urls', '$ordens', '$estado', '$cid_p', '$readonly', '$readwrite')");
                    echo "<script type='text/javascript'> window.location='arbol.php'</script>";
            }
       elseif ($acn==2){ // modificar registro
               $result = $db->consulta("UPDATE menus SET
                       menus='$menus',
                       urls='$urls',
                       estado='$estado',
                       ordens='$ordens',
                       readonly='$readonly',
                       readwrite='$readwrite'
                     WHERE id_ms='$cid_s'");
                }
            else{// eliminar registro
//              echo "eliminando...";
//              $db->consulta("DELETE FROM tbpoblacionmeta WHERE id='$cid'");
            }
    echo "<script type='text/javascript'> window.location='arbol.php'</script>";
    ?>
   
    