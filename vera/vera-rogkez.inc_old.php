<?php 
if($_SESSION['bent']!==TRUE){
  echo("Ez az oldal azonosításhoz kötött, lépj be!");
  die;
};

if(($_SESSION['regio']!=='7R') and (substr($_SESSION['rogzito'],-8)!=='kateketa')){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};

if(isset($_POST['rogadatmentgomb'])){
  ab_lek("UPDATE t_rogzitok SET rognev='".$_POST['rognev']."', rogjszo='".$_POST['rogjszo']."', ervtol='".$_POST['ervtol']."', ervig='".$_POST['ervig']."' WHERE rogzito='".$_POST['rogzito']."'");
};
?>

<table>
 <thead>
  <tr class="fejlec">
   <td class='kozepre'>Azonosító</td>
   <td class='kozepre'>Név <sup>*25</sup></td>
   <td class='kozepre'>Jelszó <sup>*11</sup></td>
   <td class='kozepre'>Érvényesség kezdete</td>
   <td class='kozepre'>Érvényesség vége</td>
   <td class='kozepre'>Művelet</td>
  </tr> 
 </thead>
 <tbody>
 <?php
  if(substr($_SESSION['rogzito'],-8)=='kateketa'){
    $szuro=" WHERE rogzito='ve_".$regiokodnevek[$_SESSION['regio']]."'";
  }else{
    $szuro="";
  };
  $e=ab_lek("SELECT * FROM t_rogzitok $szuro ORDER BY rogzito");
  $diksor=0;
  while($sor=mysql_fetch_assoc($e)){
    $diksor++;
    if(fmod($diksor,2)==0){$sty='style="background-color:#FF9900;vertical-align:center;"';}else{$sty='style="vertical-align:center;"';};
    echo("<form method='post' action='".$_SERVER['PHP-SELF']."'><tr $sty>
          <td>".$sor['rogzito']."<input type='hidden' name='rogzito' value='".$sor['rogzito']."' /></td>
          <td class='kozepre'><input type='text' class='rejtett' name='rognev' value='".$sor['rognev']."' /></td>
          <td class='kozepre'><input type='text' class='rejtett' name='rogjszo' value='".$sor['rogjszo']."' /></td>
          <td class='kozepre'><input type='text' class='rejtett' name='ervtol' value='".$sor['ervtol']."' /></td>
          <td class='kozepre'><input type='text' class='rejtett' name='ervig' value='".$sor['ervig']."' /></td>
          <td class='kozepre'><input type='submit' value='Mentés' name='rogadatmentgomb' /></td></tr></form>");
  };
 ?>  
 </tbody> 
</table>