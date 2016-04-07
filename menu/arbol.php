<!--Archivos para estilos del menu principal-->
<link href="../html/css/menuarbolaccesible.css" rel="stylesheet" type="text/css" />
<script src="../html/js/menuarbolaccesible.js" type="text/javascript"></script>

<?
?>

    <body>
    <?
      require_once ('conexion.inc');
      $db = new MySQL;
      $csqlcmd = "SELECT * FROM  menup WHERE tbsistema_id=1 ORDER BY orden";  
      $result = $db->consulta($csqlcmd);
          ?>
<ul id="menu1">
    <?php
       while ($aregistros = $db->fetch_array($result)){
              $menup = htmlentities($aregistros['menup']);
              $idp = $aregistros['id_mp'];
            ?>
    <li><? echo $menup;
      $csqlcmds = "SELECT * FROM  menus WHERE id_mp = $idp";  
      $results = $db->consulta($csqlcmds);
    ?>
   <ul><a href="./menup.php?acn=2&idp=<? echo $idp ?>">modificar <? echo $menup ?></a> --
       <a href="./menus.php?acn=1&idp=<? echo $idp ?>">Nuevo Sub-menu</a>
      <?php
       while ($aregistross = $db->fetch_array($results)){
              $menus = htmlentities($aregistross['menus']);
              $ids = $aregistross['id_ms'];
            ?>  
     <li><? echo $menus; 
      $csqlcmdt = "SELECT * FROM  menut WHERE id_ms = $ids";  
      $resultt = $db->consulta($csqlcmdt);
      ?>
   <ul><a href="./menus.php?acn=2&ids=<? echo $ids ?>&idp=<? echo $idp ?>">modificar <? echo $menus ?></a> --
       <a href="./menut.php?acn=1&ids=<? echo $ids ?>">Nuevo Sub-menu</a>
  <?php
       while ($aregistrost = $db->fetch_array($resultt)){
              $menut = htmlentities($aregistrost['menut']);
              $idt = $aregistrost['id_mt'];
             ?>  
       <li><? echo $menut; 
       $csqlcmdc = "SELECT * FROM  menuc WHERE id_mt = $idt";  
      $resultc = $db->consulta($csqlcmdc);
      ?>
           <ul><a href="./menut.php?acn=2&idt=<? echo $idt ?>&ids=<? echo $ids ?>">modificar <? echo $menut ?></a> --
               <a href="./menuc.php?acn=1&idt=<? echo $idt ?>">Nuevo Sub-menu</a>
          <?php
       while ($aregistrosc = $db->fetch_array($resultc)){
              $menuc = htmlentities($aregistrosc['menuc']);
              $idc = $aregistrosc['id_mc'];
             ?>  
          <li><a href="./menuc.php?acn=2&idc=<? echo $idc ?>&idt=<? echo $idt ?>"><? echo $menuc ?></a></li>
         <?  } ?>
         </ul>
        </li>
       <?  } ?>
    </ul>
    </li>
      <?  } ?>
    </ul>
  </li>
   <? } ?>
  
<a href="menup.php?acn=1">Nuevo Menu princinpal</a></ul>
<script type="text/javascript">
<!--
iniciaMenu('menu1');
//-->
</script>
