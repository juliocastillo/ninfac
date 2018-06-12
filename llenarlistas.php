<?php
class Htmltipo_entrada{
    function llenarlista($sel){
        $db = new MySQL();
        $sqlcommand = "SELECT id, tipo_entrada as nombre
                        FROM tipo_entrada";
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            /*
             * seleccionar el registro por default enviado
             */
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".htmlentities($row['nombre'])."</option>";
            }
            else{
                $html .= "<option value='".$row['id']."'>".htmlentities($row['nombre'])."</option>";
            }
        }
        return $html;
    }
}


class Htmlproducto{
    function llenarlista($sel){
        $db = new MySQL();
        $sqlcommand = "SELECT id, CONCAT(nombre,' (',presentacion,')') AS nombre
                        FROM producto";
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            /*
             * seleccionar el registro por default enviado
             */
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".htmlentities($row['nombre'])."</option>";
            }
            else{
                $html .= "<option value='".$row['id']."'>".htmlentities($row['nombre'])."</option>";
            }
        }
        return $html;
    }
}

class Htmlproducto_existencia{
    function llenarlista($sel){
        $db = new MySQL();
        $sqlcommand = "SELECT
                  p.id,
                  p.nombre,
                  IF(pt.cant_entrada is not null, pt.cant_entrada,0) AS cant_entrada,
                  IF(fa.cant_salida is not null,fa.cant_salida,0) AS cant_salida,
                  IF(pt.cant_entrada is not null, pt.cant_entrada,0) - IF(fa.cant_salida is not null,fa.cant_salida,0) AS saldo_actual

                FROM producto p
                  LEFT JOIN (SELECT id_producto,SUM(cantidad) AS cant_entrada
                            FROM entrada_producto_terminado GROUP BY id_producto) pt ON pt.id_producto = p.id
                  LEFT JOIN (SELECT id_producto,SUM(cantidad) AS cant_salida
                            FROM facturas_detalle fd, facturacion f
                            WHERE fd.id_documento = f.id GROUP BY fd.id_producto) fa ON fa.id_producto = p.id
                WHERE (IF(pt.cant_entrada is not null, pt.cant_entrada,0) - IF(fa.cant_salida is not null,fa.cant_salida,0)) >0
                ORDER BY p.id_grupo,p.nombre";
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            /*
             * seleccionar el registro por default enviado
             */
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".htmlentities($row['nombre'])."</option>";
            }
            else{
                $html .= "<option value='".$row['id']."'>".htmlentities($row['nombre'])."</option>";
            }
        }
        return $html;
    }
}





class Htmlpresentacion{
    function llenarlista($sel){
        $db = new MySQL();
        $sqlcommand = "SELECT id, presentacion AS nombre
                        FROM producto_presentacion";
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            /*
             * seleccionar el registro por default enviado
             */
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".htmlentities($row['nombre'])."</option>";
            }
            else{
                $html .= "<option value='".$row['id']."'>".htmlentities($row['nombre'])."</option>";
            }
        }
        return $html;
    }
}


class Htmltipo_documento{
    function llenarlista($sel){
        $db = new MySQL();
        $sqlcommand = "SELECT id, tipo_documento AS nombre
                        FROM tipo_documento";
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            /*
             * seleccionar el registro por default enviado
             */
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".htmlentities($row['nombre'])."</option>";
            }
            else{
                $html .= "<option value='".$row['id']."'>".htmlentities($row['nombre'])."</option>";
            }
        }
        return $html;
    }
}


class Htmlcondicion_pago{
    function llenarlista($sel){
        $db = new MySQL();
        $sqlcommand = "SELECT id, condicion_pago AS nombre
                        FROM condicion_pago";
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            /*
             * seleccionar el registro por default enviado
             */
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".htmlentities($row['nombre'])."</option>";
            }
            else{
                $html .= "<option value='".$row['id']."'>".htmlentities($row['nombre'])."</option>";
            }
        }
        return $html;
    }
}

class Htmlcliente{
    function llenarlista($sel){
        $db = new MySQL();
        $sqlcommand = "SELECT id, CONCAT(nombre,' ',nombre_comercial) AS nombre
                        FROM clientes WHERE estado='A' AND nombre is not null ORDER BY nombre,nombre_comercial";
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            /*
             * seleccionar el registro por default enviado
             */
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".htmlentities($row['nombre'])."</option>";
            }
            else{
                $html .= "<option value='".$row['id']."'>".htmlentities($row['nombre'])."</option>";
            }
        }
        return $html;
    }
}

class Htmlvendedor{
    function llenarlista($sel){
        $db = new MySQL();
        $sqlcommand = "SELECT id, nombre AS nombre
                        FROM vendedor";
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            /*
             * seleccionar el registro por default enviado
             */
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".htmlentities($row['nombre'])."</option>";
            }
            else{
                $html .= "<option value='".$row['id']."'>".htmlentities($row['nombre'])."</option>";
            }
        }
        return $html;
    }
}


class Htmlzonas{
    function llenarlista($sel){
        $db = new MySQL();
        $sqlcommand = "SELECT id, zona AS nombre
                        FROM zonas";
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            /*
             * seleccionar el registro por default enviado
             */
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".htmlentities($row['nombre'])."</option>";
            }
            else{
                $html .= "<option value='".$row['id']."'>".htmlentities($row['nombre'])."</option>";
            }
        }
        return $html;
    }
} 

class Htmltipo_pago{
    function llenarlista($sel){
        $db = new MySQL();
        $sqlcommand = "SELECT id, tipo_pago AS nombre
                        FROM tipo_pago";
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            /*
             * seleccionar el registro por default enviado
             */
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".htmlentities($row['nombre'])."</option>";
            }
            else{
                $html .= "<option value='".$row['id']."'>".htmlentities($row['nombre'])."</option>";
            }
        }
        return $html;
    }
    
}


class Htmlbancos{
    function llenarlista($sel){
        $db = new MySQL();
        $sqlcommand = "SELECT id, banco AS nombre
                        FROM bancos";
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            /*
             * seleccionar el registro por default enviado
             */
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".htmlentities($row['nombre'])."</option>";
            }
            else{
                $html .= "<option value='".$row['id']."'>".htmlentities($row['nombre'])."</option>";
            }
        }
        return $html;
    }
    
}


class Htmlestado{
    function llenarlista($sel){
        $db = new MySQL();
        $sqlcommand = "SELECT id, estado AS nombre
                        FROM estado";
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            /*
             * seleccionar el registro por default enviado
             */
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".htmlentities($row['nombre'])."</option>";
            }
            else{
                $html .= "<option value='".$row['id']."'>".htmlentities($row['nombre'])."</option>";
            }
        }
        return $html;
    }
    
}


class HtmlDepartamento{//DEPARTAMENTO
    function llenarlista($sel=0,$depto=0){
        $db = new MySQL();
        $sqlcommand = "SELECT id as id, departamento as nombre FROM departamento";
        if ($depto>0)
            $sqlcommand = "SELECT departamentoId as id, UPPER(departamento) as nombre FROM departamento WHERE departamentoId='$depto'";
        
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".utf8_decode($row['nombre'])."</option>";
            }
            else {
                $html .= "<option value='".$row['id']."'>".utf8_decode($row['nombre'])."</option>";
            }
        }
        return $html;
    }
}



class HtmlMunicipio{//Municipio
    function llenarlista($depto=0, $sel=0, $muni=0){
        $db = new MySQL();
        /*
         * seleccionar deacuerdo a parametros enviados
         */
        
        $sqlcommand = "SELECT id as id, municipio as nombre FROM municipio WHERE id='$muni'";
        if ($depto>0)
            $sqlcommand = "SELECT id as id, municipio as nombre FROM municipio WHERE id_depto='$depto'";
            
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".utf8_decode($row['nombre'])."</option>";
            }
            else {
                $html .= "<option value='".$row['id']."'>".utf8_decode($row['nombre'])."</option>";
            }
        }
        return $html;
    }
}


class HtmlZona{//Municipio
    function llenarlista($sel=0){
        $db = new MySQL();
        /*
         * seleccionar deacuerdo a parametros enviados
         */
        
        $sqlcommand = "SELECT id as id, zona as nombre FROM zonas";
    
            
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".utf8_decode($row['nombre'])."</option>";
            }
            else {
                $html .= "<option value='".$row['id']."'>".utf8_decode($row['nombre'])."</option>";
            }
        }
        return $html;
    }
}

class Htmlgrupo{
    function llenarlista($sel){
        $db = new MySQL();
        $sqlcommand = "SELECT id, grupo as nombre
                        FROM grupo";
        $result = $db->consulta($sqlcommand);
        $html = "";
        while($row = $db->fetch_array($result)){
            /*
             * seleccionar el registro por default enviado
             */
            if ($row['id']==$sel){
                $html .= "<option value='".$row['id']."' selected>".htmlentities($row['nombre'])."</option>";
            }
            else{
                $html .= "<option value='".$row['id']."'>".htmlentities($row['nombre'])."</option>";
            }
        }
        return $html;
    }
}
