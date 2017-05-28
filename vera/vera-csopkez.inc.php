<?php 
if($_SESSION['bent']!==TRUE){
  echo("Ez az oldal azonosításhoz kötött, lépj be!");
  die;
};

if(($_SESSION['regio']!=='7R') or ($_SESSION['rf']!==true)){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};

if(isset($_POST['ujcsopgomb'])){
  ab_lek("INSERT INTO t_csoportok VALUES (NULL, '".$_POST['ujcsopnev']."')");
  ab_lek("INSERT INTO t_szerknaplo VALUES ('".$_SESSION['rogzito']."', 'Új csoport: ".$_POST['ujcsopnev']."', '".date("Y-m-d H:m:s")."')");
  hibauz("A létrehozás sikerült...");
};

if(isset($_POST['csoptorlesgomb'])){
  ab_lek("DELETE FROM t_csoportok WHERE csid=".$_POST['csid']);
  ab_lek("INSERT INTO t_szerknaplo VALUES ('".$_SESSION['rogzito']."', 'Csoporttörlés: ".$_POST['csnev']."', '".date("Y-m-d H:m:s")."')");
  hibauz("A törlés sikerült...");
};

if(isset($_POST['csopatnevezgomb'])){
  ab_lek("UPDATE t_csoportok SET csnev='".$_POST['csnev']."' WHERE csid=".$_POST['csid']);
  ab_lek("INSERT INTO t_szerknaplo VALUES ('".$_SESSION['rogzito']."', 'Csoport átnevezés: (".$_POST['csid'].") ".$_POST['csnev']."', '".date("Y-m-d H:m:s")."')");
  hibauz("Az átnevezés sikerült...");
};

?>
<table>
 <tr class="fejlec">
  <td>Sorszám</td>
  <td>Csoportnév</td>
  <td>Művelet</td>
 </tr>
 <tr>
   <form method="post">
     <td style='text-align:center;'>ÚJ</td>
     <td><input type="text" name="ujcsopnev" /></td>
     <td><input type="submit" name="ujcsopgomb" value="Rögzítés" /></td>
   </form>
 </tr>
 <?php
 $diksor=0;
 $e=ab_lek("SELECT * FROM vCsopLetszam ORDER BY csid");
 while ($sor=mysql_fetch_assoc($e)) {
   $diksor++;
   if(fmod($diksor,2)==0){$sty='style="background-color:#FF9900;vertical-align:center;height:22px;"';}else{$sty='style="vertical-align:center;height:22px;"';};
   echo("<tr $sty>
         <td style='text-align:center;'>".$sor['csid']."</td>
         <td>
           <form method='post' style='margin:0px;padding:0px;'>
             <input type='hidden' name='csid' value='".$sor['csid']."' />
             <input type='text' name='csnev' value='".$sor['csnev']."' />
             <input type='submit' name='csopatnevezgomb' value='Átnevez...' />
           </form>
         </td>
         <td><form method='post'>".$sor['letszam']." fő");
   if($sor['letszam']==0){
     echo("<input type='hidden' name='csid' value='".$sor['csid']."'/>
           <input type='hidden' name='csnev' value='".$sor['csnev']."'/>
           <input type='submit' name='csoptorlesgomb' value='Törlés' style='margin:0px 0px 0px 10px' />");
   };      
   echo("</form></td>
         </tr>");
 };
 ?>
</table>