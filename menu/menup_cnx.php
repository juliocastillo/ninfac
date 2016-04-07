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


$cid=$_GET['cid'];
$acn=$_GET['acn'];
$menup=$_POST['menup'];
$url_mp=$_POST['url_mp'];
$id_rol=$_POST['id_rol'];
$orden=$_POST['orden'];
$estado=$_POST['estado'];
$readonly=$_POST['readonly'];
$readwrite=$_POST['readwrite'];
        

       if ($acn==1){ // insertar nuevo
                    $result = $db->consulta("INSERT INTO menup (menup, url_mp, tbRol_id, orden, estado, readonly, readwrite,  tbsistema_id)
                                                VALUES ('$menup', '$url_mp', '$id_rol', '$orden', '$estado', '$readonly', '$readwrite', '1')");
                    echo "<script type='text/javascript'> window.location='arbol.php'</script>";
            }
       elseif ($acn==2){ // modificar registro
               $result = $db->consulta("UPDATE menup SET
                       menup='$menup',
                       url_mp='$url_mp',
                       tbRol_id='$id_rol',
                       estado='$estado',
                       readonly='$readonly',
                       readwrite='$readwrite',
                       orden='$orden'
                     WHERE id_mp='$cid'");
                }
            else{// eliminar registro
//              echo "eliminando...";
//              $db->consulta("DELETE FROM tbpoblacionmeta WHERE id='$cid'");
            }
    echo "<script type='text/javascript'> window.location='arbol.php'</script>";
    ?>   