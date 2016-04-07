    <?php
 class MySQL{
  private $conexion;
  private $total_consultas;
 public function MySQL(){
   if(!isset($this->conexion)){
  $this->conexion = (mysql_connect("localhost","root","123")) or die(mysql_error());
   mysql_select_db("ninfac",$this->conexion) or die(mysql_error());
   }
   }
  public function consulta($consulta){
   $this->total_consultas++;
 $resultado = mysql_query($consulta,$this->conexion);
  if(!$resultado){
   echo 'MySQL Error: ' . mysql_error();
  exit;
  }
   return $resultado;
   }
  public function fetch_array($consulta){
  return mysql_fetch_array($consulta);
  }
  public function num_rows($consulta){
  return mysql_num_rows($consulta);
  }

  public function result($query,$i,$campo){
    return mysql_result($query,$i,$campo);
  }

  public function getTotalConsultas(){
  return $this->total_consultas;
   }
 }


class datagrid{
private $cols=0;
private $dato=0;
private $finenc=0;
private $totalfila=0;
    function  __construct($borde=1) {
        ?>
        <table cellspacing="0" cellpadding="2" border="<?php echo $borde; ?>">
        <?php
    }
    function col($titulo,$rowspan=1,$colspan=1){
        if ($this->cols==0){;
        ?>
            <thead>
                <tr>
                <?php
        } ?>
                    <th align="center" rowspan="<?php echo $rowspan ?>" colspan="<?php echo $colspan; ?>"><?php echo $titulo; ?></th>
        <?php
        $this->cols=$this->cols+$colspan;
    }

    function fila($valor,$colspan=1,$estilo="background-color: white;",$align="Left"){
        // si el dato es el primero
            if($this->finenc==0){
              ?>
                </tr>
            </thead>
            <?php
            $this->finenc=1;
            }
        if ($this->dato==0){;
            ?>
              <tr>
            <?php
            }
            ?>
                <td style="<?php echo $estilo ?>" colspan="<?php echo $colspan ?>" align="<?php echo $align ?>"><?php echo $valor ?></td>
                <?php
                // si es el dato de la �ltima columna
                if ($this->dato==($this->cols-1)){?>
                    </tr>
                    <?php
                    $this->dato=0;
                }
                else{
                    $this->dato=$this->dato+$colspan;
                }
    }

    function totalfila($valor){
        $this->totalvalorfila = $this->totalvalorfila + $valor;
        return $this->totalvalorfila;
    }


    function  __destruct() {
        ?>
        </table>
        <?php
    }
}



 ?>