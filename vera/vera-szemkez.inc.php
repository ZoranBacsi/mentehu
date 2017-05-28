<?php

if($_SESSION['bent']!==TRUE){
  echo("Ez az oldal azonosításhoz kötött, lépj be!");
  die;
};

if(isset($_POST['csopraszurgomb'])){
  $szurcsop=$_POST['csopnev'];
  if($szurcsop=='nincs'){
    $szuro="WHERE sorsz NOT IN (SELECT DISTINCT szid FROM t_csoptagsagok)";
  }else{
    $szuro="WHERE sorsz IN (SELECT DISTINCT szid FROM t_csoptagsagok WHERE csid=$szurcsop)";
  };
}else{
  if(isset($_POST['reszletkeresgomb'])){
    $szurresz=$_POST['nevreszlet'];
    $szuro="WHERE ".$_POST['holkeres']." LIKE '%$szurresz%'";
    if($_POST['holkeres']=='email'){
      $szuro.="OR ".$_POST['holkeres']."2 LIKE '%$szurresz%'";
    };
  }else{
    if(isset($_POST['mindetmutasdgomb'])){
      if($_SESSION['regio']!='7R'){
      $szuro="WHERE sorsz IN (SELECT DISTINCT szid FROM t_csoptagsagok WHERE csid=".$_SESSION['regiosorsz'].")";
      }else{
      $szuro="";
      }
    }else{
      if(isset($_POST['regreszurgomb'])){
        $szurreg=$_POST['regnev'];
        $szuro="WHERE sorsz IN (SELECT DISTINCT ssorsz FROM vEseReg WHERE eSorsz=$szurreg)";
      }else{
        if(isset($_POST['eletkorkeresgomb'])){
          $szurresz=$_POST['korfeltetel'];
          if(strlen($szurresz)==4){
          $szuro="WHERE szev=$szurresz";
          };
          if((strlen($szurresz)==5) or (strlen($szurresz)==6)){
          $szuro="WHERE szev$szurresz";
          }; 
          if(strlen($szurresz)==9){
          $eva=substr($szurresz,0,4);
          $evb=substr($szurresz,-4);
          $szuro="WHERE szev BETWEEN $eva AND $evb";
          };                   
        }else{
          $szuro="semmi";
        };
      };  
    };
  };
};  

print_r($_SESSION);

echo("<table style='width:100%;padding:0px;'><tr style='background-color:#FF9900;padding:0px;'>");
if($_SESSION['regio']=='7R'){
echo("<td style='text-align:center;padding:0px;");if(isset($_POST['csopraszurgomb'])){echo('background-color:red;');};echo("'>Csoporttagságra szűrés:<br />
      <form method='post' action='".$_SERVER['PHP-SELF']."' style='margin:0px;'>
      <select name='csopnev'  style='margin:3px;'>
        <option value='nincs'>Nincs besorolva</option>");
$e=ab_lek("SELECT * FROM t_csoportok");
while ($sor=mysql_fetch_assoc($e)) {
	echo(" <option value='".$sor['csid']."'");
  if($szurcsop==$sor['csid']){echo(" selected='selected'");};
  echo(">".$sor['csnev']."</option>");
};
echo("</select><br /><input type='submit' value='Szűr...'  style='margin:3px;' name='csopraszurgomb' />
      </form></td>");      


echo("<td style='text-align:center;padding:0px;background-color:#FFFF66;'>V<br />A<br />G<br />Y</td>");
};


echo("<td style='text-align:center;padding:0px;");if(isset($_POST['regreszurgomb'])){echo('background-color:red;');};echo("'>Regisztrációra szűrés:<br />
      <form method='post' action='".$_SERVER['PHP-SELF']."' style='margin:0px;'>
      <select name='regnev'  style='margin:3px;'>");

if($_SESSION['regio']=='7R'){ //
  $e=ab_lek("SELECT * FROM t_esemenyek ORDER BY eDatumK DESC");
}else{
  $e=ab_lek("SELECT * FROM t_esemenyek WHERE rKod LIKE '%".$_SESSION['regio']."%' ORDER BY eDatumK DESC");
};
while ($sor=mysql_fetch_assoc($e)) {
	echo(" <option value='".$sor['eSorsz']."'");
  if( (isset($_POST['regreszurgomb'])) && ($_POST['regnev']==$sor['eSorsz']) ){echo(" selected='selected'");};
  echo(">".$sor['eNev']." - ".$sor['eTelep']." (".$sor['rKod']." - ".substr($sor['eDatumK'], 0, 7).")</option>");
};
echo("</select><br /><input type='submit' value='Szűr...' name='regreszurgomb' />
      </form></td>");


echo("<td style='text-align:center;padding:0px;background-color:#FFFF66;'>V<br />A<br />G<br />Y</td>");


echo("<td style='text-align:center;padding:0px;");if(isset($_POST['reszletkeresgomb'])){echo('background-color:red;color:white;');};echo("'>Szövegrészletre szűrés:<br />
      <form method='post' action='".$_SERVER['PHP-SELF']."'>
      <input type='text' name='nevreszlet' style='margin:3px;' value='");if(isset($_POST['reszletkeresgomb'])){echo($_POST['nevreszlet']);};echo("' /><br />
      <select name='holkeres'  style='margin:3px;'>
        <option value='nev'");if($_POST['holkeres']=='nev'){echo(" selected ");};echo(">Névben</option>
        <option value='email'");if($_POST['holkeres']=='email'){echo(" selected ");};echo(">Email-ben</option>
        <option value='laktelep'");if($_POST['holkeres']=='laktelep'){echo(" selected ");};echo(">Településben</option>
        <option value='mobil'");if($_POST['holkeres']=='mobil'){echo(" selected ");};echo(">Mobilban</option>
        <option value='pleb'");if($_POST['holkeres']=='pleb'){echo(" selected ");};echo(">Plébániában</option>
        <option value='megj'");if($_POST['holkeres']=='megj'){echo(" selected ");};echo(">Megjegyzésben</option>
      </select>
      <input type='submit' name='reszletkeresgomb'  style='margin:3px;' value='Keresés...' />
      </td>");
echo("<td style='text-align:center;padding:0px;background-color:#FFFF66;'>V<br />A<br />G<br />Y</td>");
echo("<td style='text-align:center;padding:0px;");if(isset($_POST['eletkorkeresgomb'])){echo('background-color:red;');};echo("'>Születési évre szűrés:<br />
      <form method='post' action='".$_SERVER['PHP-SELF']."'>
      <input type='text' name='korfeltetel'  style='margin:3px;width:100px;' value='");if(isset($_POST['eletkorkeresgomb'])){echo($_POST['korfeltetel']);};echo("' /><br />
      <input type='submit' name='eletkorkeresgomb'  style='margin:3px;' value='Keresés...' />
      </td>");
echo("<td style='text-align:center;padding:0px;background-color:#FFFF66;'>V<br />A<br />G<br />Y</td>");
echo("<td style='text-align:center;padding:0px;'>
      <form method='post' action='".$_SERVER['PHP-SELF']."'>
      <input type='submit' name='mindetmutasdgomb'  style='margin:3px;' value='Mindet!' />
      </form>
      </td></tr></table>");

if($szuro!='semmi'){
$e=ab_lek("SELECT * FROM t_szemelyek $szuro ORDER BY nev");  
$erdb=mysql_num_rows($e);

echo("<p style='margin:5px;'>Szűrőfeltételeknek megfelelő sorosk száma: <b style='color:red;'>$erdb</b> db</p>");

if($erdb>0){

?>
<table style="width:99%; margin: auto;">
 <thead>
  <tr class="fejlec">
   <td class='kozepre'>Név</td>
   <td class='kozepre'>Lakóhely<hr />Egyházközség</td>
   <td class='kozepre'>Csoporttagságok</td>
   <td class='kozepre'>Szül.év</td>
   <td class='kozepre'>Email</td>
   <td class='kozepre'>Mobil</td>
   <td class='kozepre'>Művelet</td>
  </tr> 
 </thead>
 
 <?php
 function fopen_utf8 ($filename, $mode) {
    $file = @fopen($filename, $mode);
    $bom = fread($file, 3);
    if ($bom != b"\xEF\xBB\xBF")
        rewind($file, 0);
    else
        echo "bom found!\n";
    return $file;
}; 
  $ft = fopen_utf8('szurolista_export_mail.txt', 'w');
  $fc = fopen_utf8('szurolista_export.csv', 'w');
  //fwrite($ft, $regionevek[$szureg]."\r\n");
  fwrite($fc, utf8_decode("név;szülév;email;mobil;flottás-e;lakirsz;laktelep;lakcim;plébánia;utolsómódosítás;megjegyzés"."\r\n"));  

  $diksor=0;
  foreach ($kb as $key=>$value) {
    while($sor=mysql_fetch_assoc($e)){
      $diksor++;
      if(fmod($diksor,2)==0){$sty='style="background-color:#FF9900;vertical-align:center;"';}else{$sty='style="vertical-align:center;"';};
      $csoplista='';
      $e2=ab_lek("SELECT csnev FROM vCsopTagsagNevvel WHERE szid=".$sor['sorsz']);
      while ($seg2=mysql_fetch_assoc($e2)){
      	$csoplista.=$seg2['csnev'].", ";
      };
      $csoplista=substr($csoplista,0,-2);
      echo("<tr $sty>
          <td class='kozepre' title='Sorszám: ".$sor['sorsz']."'>");
      if($sor['nem']=='n'){
        echo("<span style='color:red;'>");
      };
      if($sor['nem']=='f'){
        echo("<span style='color:blue;'>");
      };    
      echo($sor['nev']."</span></td>
          <td class='kozepre'>".$sor['laktelep']);
      if($sor['laktelep']!=$sor['pleb']){echo("<hr />".$sor['pleb']);};
      echo("</td>
          <td class='kozepre'>".$csoplista."</td>
          <td class='kozepre'>".$sor['szev']."</td>
          <td class='kozepre'><span");if($sor['e1tilt']=='i'){echo(" style='font-style:italic;color:#5F5F5F;'");};echo(">".$sor['email']);
      if($sor['email2']!==''){
        echo("</span><br /><span>".$sor['email2']);
      };      
      echo("</span></td>
          <td class='kozepre'>");if(strlen($sor['mobil']>0)){echo("<span");if($sor['kf']=='i'){echo(" style='color:red;font-weight:bold;'");};echo(">+36-".substr($sor['mobil'],0,2)."-".substr($sor['mobil'],2,3)."-".substr($sor['mobil'],5,2)."-".substr($sor['mobil'],7,2)."</span>");}else{echo("-");};echo("</td>
          <td class='kozepre'>
            <form method='post' action='http://vera.mente.hu/index.php?muv=szemszerk' target='_blank'>
            <input type='hidden' name='szemsorsz' value='".$sor['sorsz']."' />
            <input type='submit' class='mpgomb' value='Szerkeszt' title='Utolsó módosítás: ".$sor['utmod']."' />
            </form>
          </td></tr>");
          if($sor['email']<>''){fwrite($ft, utf8_decode($sor['nev']." <".$sor['email'])."> \r\n");};
          fwrite($fc, utf8_decode($sor['nev'].";".$sor['szev'].";".$sor['email'].";".$sor['mobil'].";".$sor['kf'].";".$sor['lakirsz'].";".$sor['laktelep'].";".$sor['lakcim']."; ;".$sor['utmod'].";".str_replace("\r\n"," & ",$sor['megj'])."\r\n"));  
    };
  }; 
  fclose($ft);
  fclose($fc);

 ?>  
  <tr class="fejlec">
    <td colspan="7">
      <?php
      if($_SESSION['regio']=='7R'){
      ?>
      <div style="float:right;text-align:right;">
      <form style="margin:0px;padding:0px;" method="post" 
            action="index.php?muv=hirlevegyesevel">
        <input type="hidden" name="szuro" value="<?php echo($szuro);?>" />
        <input type="submit" name="hirevelcimzetteknekgomb" style="margin:3px;padding:0px;"
               value="Hírlevélcímzetteknek (RÉGI küldőrendszerhez!!!)" />
      </form>
      <hr />
      <form style="margin:0px;padding:0px;" method="post" 
            action="index.php?muv=hcimcsop">     
        Csoport neve: <input type="text" name="csnev" value="" /><br />
        <input type="hidden" name="csszuro" value="<?php echo($szuro);?>" style="width:450px" /><br />
        <input type="submit" name="csmentesgomb" style="margin:3px;padding:0px;"
               value="Hírlevélcímzetteknek (ÚJ)" />
      </form>
      </div>
      <?php      
      };
      ?>
      <br />
      <?php
      if($_SESSION['regio']=='7R'){
      echo("Aktuális lista exportja:<br />
      <a href='szurolista_export_mail.txt' target='_blank'>.TXT</a>
      vagy
      <a href='szurolista_export.csv' target='_blank'>.CSV (Excel)</a> 
      formátumba.");
      };
      ?>
    </td>
  </tr>  
</table>


<?php
}else{
  echo("<br /><br /><p style='color:red;'>Ilyen szűrőkfeltételekre NINCS találat!</p>");
};

};
?>