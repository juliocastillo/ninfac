<?
require_once ('conexion.inc');
$db = new MySQL;
?>
        <script language="Javascript">

            function obliga(){
                 if (document.getElementById('menut').value == ""){
                     alert('Falta nombre del Menu');
                     return false
                  }
                 if (document.getElementById('ordent').value == ""){
                     alert('Falta el numero de orden');
                     return false
                  }
                  if (document.getElementById('estado').value == ""){
                     alert('Falta el estado');
                     return false
                  }
                 
            return error
            }

</script>
<br></br>
        <div align="center">
                   <form name="frmhos_menut" action="menut_cnx.php?cid_s=<?php echo $_GET['ids'].'&cid_t='.$_GET['idt'].'&acn='.$_GET['acn']; ?>" method="POST" onsubmit="return obliga(this)">
               <?php
                $cid_s=$_GET['ids'];
                $cid_t=$_GET['idt'];
                $acn=$_GET['acn'];
    $result_s=$db->consulta("select * from menus where id_ms='$cid_s'");         
    $aregistros_s = $db->fetch_array($result_s);           
    $menus=$aregistros_s['menus'];            
                
                
       if ($acn==2){                                        
            $result_consulta=$db->consulta("select * from menut where id_mt='$cid_t'");    
                while ($aregistros = $db->fetch_array($result_consulta)){            
                            $menut=$aregistros['menut'];
                            $url=$aregistros['url'];
                            $ordent=$aregistros['ordent'];
                            $estado=$aregistros['estado'];
                            $readonly=$aregistros['readonly'];
                            $readwrite=$aregistros['readwrite'];
                            }}
                              ?>

            <table border="1" width="570px"  cellspacing="0">
             <tbody>
               <tr>
               <th colspan="2" style="background-color: lightblue;" align="center">Menú tercer nivel<br>Ingreso / modificación</th>
              </tr>
              <tr>
               <td colspan="2" style="background-color: #86c9ef;" colspan="2" align="center">Menú secundario <b><?php echo $menus?></b></td>
              </tr>
              <tr>
               <td align="left" width="170px">Nombre del Sub-menú</td>
               <td align="left" width="400px"><input type="text" id="menut" name="menut"  maxlength="15" style="" value="<?php echo $menut?>"  size="50" tabindex="1"/></td>
              </tr>
              <tr>
               <td align="left">Url</td>
               <td align="left"><input  type="text" id="url" name="url"  maxlength="100" style="" value="<?php echo $url?>"  size="50" tabindex="2"/></td>
              </tr>    
              <tr>
               <td align="left">Orden</td>
               <td align="left"><input  type="text" id="ordent" name="ordent"  maxlength="2" style="" value="<?php echo $ordent?>"  size="2" tabindex="3"/></td>
              </tr> 
              <tr>
                   <td  align="left">Permitir ver</td>
                   <td align="left">
                       Analista<input type="checkbox" name="readonly" id="readonly" value="1" <?php if ($readonly==1) { echo 'checked="checked"'; }?> tabindex="4"/>
                       Digitador<input type="checkbox" name="readwrite" id="readwrite" value="1" <?php if ($readwrite==1) { echo 'checked="checked"'; }?> tabindex="5"/> </td>
              </tr> 
              <tr>
                       <td align="left">Estado</td>
                       <td align="left">
                       <select name="estado" id="estado" tabindex="6">
                               <option value="A" <?php if ($estado=='A'){ echo 'selected'; }?>>Activo</option>
                               <option value="I" <?php if ($estado=='I'){ echo 'selected'; }?>>Inactivo</option>
                         </select>
                       </td>
                    </tr> 
               <tr align="right">  
                  <td colspan="2" style="background-color: lightblue;">
                  <?php
                    if ($acn==1)
                        echo "<input value='Guardar' name='cguardar' type='submit' tabindex='7'/>";
                    elseif ($acn==2)
                        echo "<input value='Modificar' name='cmodificar' type='submit' tabindex='7'/>";
                    elseif ($acn==3)
                        echo "<input value='Eliminar' name='celiminar' type='submit' tabindex='7'/>";
                   ?>
                   <input type="button" value="Cancelar" type="button" onclick="window.location='arbol.php'" tabindex="8"/>          
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </body>
</html>
