<?
require_once ('conexion.inc');
$db = new MySQL;
?>
         <script language="Javascript">

            function obliga(){
                 if (document.getElementById('menuc').value == ""){
                     alert('Falta nombre del Menu');
                     return false
                  }
                 if (document.getElementById('ordenc').value == ""){
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
                   <form name="frmho_menuc" action="menuc_cnx.php?cid_t=<?php echo $_GET['idt'].'&cid_c='.$_GET['idc'].'&acn='.$_GET['acn']; ?>" method="POST" onsubmit="return obliga(this)">
               <?php
                $cid_t=$_GET['idt'];
                $cid_c=$_GET['idc'];
                $acn=$_GET['acn'];
    $result_t=$db->consulta("select * from menut where id_mt='$cid_t'");         
    $aregistros_t = $db->fetch_array($result_t);           
    $menut=$aregistros_t['menut'];            
                
                
       if ($acn==2){                                        
            $result_consulta=$db->consulta("select * from menuc where id_mc='$cid_c'");    
                while ($aregistros = $db->fetch_array($result_consulta)){            
                            $menuc=$aregistros['menuc'];
                            $url=$aregistros['url'];
                            $ordenc=$aregistros['ordenc'];
                            $estado=$aregistros['estado'];
                            $readonly=$aregistros['readonly'];
                            $readwrite=$aregistros['readwrite'];
                            }}
                              ?>

            <table border="1" width="570px"  cellspacing="0">
             <tbody>
               <tr>
                   <th colspan="2" style="background-color: lightblue;" align="center">Menú cuarto nivel<br>Ingreso / modificación</th>
               </tr>
               <tr>
                   <td colspan="2" style="background-color: #86c9ef;" colspan="2" align="center">Menú tercer nivel <b><?php echo $menut?></b></td>
              </tr>
               <tr>
                   <td align="left" width="170px">Nombre del Sub-menú</td>
                   <td align="left" width="400px"><input type="text" id="menuc" name="menuc"  maxlength="15" style="" value="<?php echo $menuc?>"  size="50" tabindex="1"/></td>
               </tr>
               <tr>
                   <td align="left">Url</td>
                   <td align="left"><input  type="text" id="url" name="url"  maxlength="100" style="" value="<?php echo $url?>"  size="50" tabindex="2"/></td>
               </tr>    
               <tr>
                   <td align="left">Orden</td>
                   <td align="left"><input  type="text" id="ordenc" name="ordenc"  maxlength="2" style="" value="<?php echo $ordenc?>"  size="2" tabindex="3"/></td>
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
                 <td colspan="2" style="background-color: lightblue;"   >
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
