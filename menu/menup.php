<?
require_once ('conexion.inc');
$db = new MySQL;

?>
  <script language="Javascript">
            
            window.onload = function(){
                document.getElementById('id_rol').onchange = desabilitar;
            }
            
            function desabilitar(){
                    combotabdos   = document.getElementById('id_rol');
                    readonly   = document.getElementById('readonly');
                    readwrite   = document.getElementById('readwrite');
                    if (combotabdos[1].selected==true){
                                readwrite.checked = true;
                                readonly.checked = false;
                               }
                        else {
                            if (combotabdos[2].selected==true){
                                readwrite.checked = false;
                                readonly.checked = true;
                               }
                        else {
                              if (combotabdos[3].selected==true || combotabdos[4].selected==true){
                                readonly.checked = false;
                                readwrite.checked = false;
                       }
                     }
                   }
                } 

            function obliga(){
                 if (document.getElementById('menup').value == ""){
                     alert('Falta nombre del Menu');
                     return false
                  }
//                 if (document.getElementById('id_rol').value == ""){
//                     alert('Falta el Rol');
//                     return false
//                  }
                 if (document.getElementById('orden').value == ""){
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
                   <form name="frmhos_menup" action="./menup_cnx.php?cid=<?php echo $_GET['idp'].'&acn='.$_GET['acn']; ?>" method="POST" onsubmit="return obliga(this)">
               <?php
                $cid=$_GET['idp'];
                $acn=$_GET['acn'];
      if ($acn==2){                                        
            $result_consulta=$db->consulta("select * from menup where id_mp='$cid'");    
                while ($aregistros = $db->fetch_array($result_consulta)){            
                            $menup=$aregistros['menup'];
                            $url_mp=$aregistros['url_mp'];
                            $id_rol=$aregistros['tbRol_id'];
                            $orden=$aregistros['orden'];
                            $estado=$aregistros['estado'];
                            $readonly=$aregistros['readonly'];
                            $readwrite=$aregistros['readwrite'];
                            }}
                              ?>

            <table border="1" width="570px"  cellspacing="0">
        <tbody>
                     
            <tr>
               <th style="background-color: lightblue;" colspan="2"  align="center">Menú Principal<br>Ingreso / modificación</th>
            </tr>
            <tr>
               <td align="left" width="150px">Nombre del Menú</td>
               <td align="lefth" width="420px"><input type="text" id="menup" name="menup"  maxlength="15" style="" value="<?php echo $menup?>"  size="50" tabindex="1"/></td>
            </tr>
            <tr>
               <td align="left">Url</td>
               <td align="lefth"><input  type="text" id="url_mp" name="url_mp"  maxlength="100" style="" value="<?php echo $url_mp?>"  size="50" tabindex="2"/></td>
            </tr>    
            <tr>
               <td  align="left">Rol</td>
               <td align="lefth">
                   <select name="id_rol" id="id_rol" tabindex="3">
                       <option value="">Todos</option>
                       <option value="1" <?php if ($id_rol==1){ echo 'selected'; }?>>Digitador</option>
                       <option value="2" <?php if ($id_rol==2){ echo 'selected'; }?>>Analista</option>
                       <option value="3" <?php if ($id_rol==3){ echo 'selected'; }?>>Administrador Institucional</option>
                       <option value="4" <?php if ($id_rol==4){ echo 'selected'; }?>>Administrador General</option>
                   </select>
             </td>
           </tr>
            <tr>
               <td  align="left">Orden</td>
               <td align="lefth"><input  type="text" id="orden" name="orden"  maxlength="2" style="" value="<?php echo $orden?>"  size="2" tabindex="4"/></td>
           </tr>
           <tr>
               <td  align="left">Permitir ver</td>
               <td align="lefth">
                   Analista<input type="checkbox" name="readonly" id="readonly" value="1" <?php if ($readonly==1) { echo 'checked="checked"'; }?> tabindex="5"/>
                   Digitador<input type="checkbox" name="readwrite" id="readwrite" value="1" <?php if ($readwrite==1) { echo 'checked="checked"'; }?> tabindex="6"/> </td>
           </tr> 
            <tr>
               <td  align="left">Estado</td>
               <td align="lefth">
               <select name="estado" id="estado" tabindex="7">
                       <option value="A" <?php if ($estado=='A'){ echo 'selected'; }?>>Activo</option>
                       <option value="I" <?php if ($estado=='I'){ echo 'selected'; }?>>Inactivo</option>
                 </select>
               </td>
            </tr> 
            <tr align="right" style="background-color: lightblue;">  
              <td colspan="2">
                  <?php

                    if ($acn==1)
                        echo "<input value='Guardar' name='cguardar' type='submit' tabindex='8'/>";
                    elseif ($acn==2)
                        echo "<input value='Modificar' name='cmodificar' type='submit' tabindex='8'/>";
                    elseif ($acn==3)
                        echo "<input value='Eliminar' name='celiminar' type='submit' tabindex='8'/>";
                    elseif ($acn==4);
                   ?>
                    <input type="button" value="Cancelar" type="button" onclick="window.location='./arbol.php'" tabindex="9"/>          
                 </td>
          </tr>
        </tbody>
        </table>
         </form>
        </div>
    </body>

