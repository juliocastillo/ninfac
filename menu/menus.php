<?
require_once ('conexion.inc');
$db = new MySQL;
?>
        <script language="Javascript">

            function obliga(){
                 if (document.getElementById('menus').value == ""){
                     alert('Falta nombre del Menu');
                     return false
                  }
                 if (document.getElementById('ordens').value == ""){
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
          <form name="frmhos_menus" action="./menus_cnx.php?cid_p=<?php echo $_GET['idp'].'&cid_s='.$_GET['ids'].'&acn='.$_GET['acn']; ?>" method="POST" onsubmit="return obliga(this)">
               <?php
                $cid_p=$_GET['idp'];
                $cid_s=$_GET['ids'];
                $acn=$_GET['acn'];
    $result_p=$db->consulta("select * from menup where id_mp='$cid_p'");         
    $aregistros_p = $db->fetch_array($result_p);           
    $menup=$aregistros_p['menup'];            
                
                
       if ($acn==2){                                        
            $result_consulta=$db->consulta("select * from menus where id_ms='$cid_s'");    
                while ($aregistros = $db->fetch_array($result_consulta)){            
                            $menus=$aregistros['menus'];
                            $urls=$aregistros['urls'];
                            $ordens=$aregistros['ordens'];
                            $estado=$aregistros['estado'];
                            $readonly=$aregistros['readonly'];
                            $readwrite=$aregistros['readwrite'];
                            }}
                              ?>

               <table border="1" width="570px"  cellspacing="0">
                <tbody>

                    <tr>
                       <th style="background-color: lightblue;" colspan="2" width="700px" align="center">Menu Secundario<br>Ingreso / modificacion</th>
                    </tr>
                    <tr>
                       <td style="background-color: #86c9ef;" colspan="2"  width="700px" align="center">Menu principal <b><?php echo $menup?></b></td>
                    </tr>
                    <tr>
                       <td align="left" width="210px">Nombre del Sub-menu</td>
                       <td align="lefth" width="360px"><input  type="text" id="menus" name="menus"  maxlength="15" style="" value="<?php echo $menus?>"  size="50" tabindex="1"/></td>
                    </tr>
                    <tr>
                       <td align="left">Url</td>
                       <td align="lefth"><input  type="text" id="urls" name="urls"  maxlength="100" style="" value="<?php echo $urls?>"  size="50" tabindex="2"/></td>
                    </tr>    
                    <tr>
                       <td align="left">Orden</td>
                       <td align="lefth"><input  type="text" id="ordens" name="ordens"  maxlength="2" style="" value="<?php echo $ordens?>"  size="2" tabindex="3"/></td>
                   </tr>
                   <tr>
                       <td  align="left">Permitir ver</td>
                       <td align="lefth">
                           Analista<input type="checkbox" name="readonly" id="readonly" value="1" <?php if ($readonly==1) { echo 'checked="checked"'; }?> tabindex="4"/>
                           Digitador<input type="checkbox" name="readwrite" id="readwrite" value="1" <?php if ($readwrite==1) { echo 'checked="checked"'; }?> tabindex="4"/> </td>
                   </tr> 
                    <tr>
                       <td align="left">Estado</td>
                       <td align="lefth">
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
                            <input type="button" value="Cancelar" type="button" onclick="window.location='arbol.php'" tabindex="8"/></td>
                  </tr>
               </tbody>
             </table>
          </form>
        </div>
    </body>
</html>
