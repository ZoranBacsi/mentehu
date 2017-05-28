<?php 
if($_SESSION['bent']!==TRUE){
  echo("Ez az oldal azonosításhoz kötött, lépj be!");
  die;
};

if($_SESSION['rf']!==true){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};

if(isset($_POST['csvimportgomb'])){
//  print_r($_POST);
  $csv="csvimport/".$_POST['csvnev'];
  $sdb=$_POST['sordb'];
  $csv=fopen($csv, "r") or die("Nem lehet megnyitni a feltöltött fájlt!");
  $sor=fgets($csv);  
  for($i=0;$i<$sdb;$i++) {
    $sor=fgets($csv);
    list($nev,$nem,$szev,$email,$email2,$mob,$kf,$irsz,$telep,$cim,$pleb,$megj) = split(',', $sor);
    $sql="INSERT INTO t_szemelyek VALUES (NULL,'$email','$nev','$mob','$kf',$szev,'$irsz','$telep','$cim','$pleb',NULL,'Importálva: ".date("Y.m.d H:i:s")."\r$megj','$nem','$email2')";
    ab_lek($sql);
    $ese=$_POST['esem'];
    if($ese!=='*nincs*'){
      $e2=ab_lek("SELECT sorsz FROM t_szemelyek WHERE email='$email' and nev='$nev' and szev=$szev and laktelep='$telep'");
      $s2=mysql_fetch_assoc($e2);
      $szem=$s2['sorsz'];
      ab_lek("INSERT INTO t_regisztraciok VALUES ($ese,$szem)");
    };
  };
  fclose($csv);
  
  echo("<h4>Sikeresen beimportálva $sdb személy...</h4><hr />");
};

if(isset($_POST['csvfeltoltgomb'])){
echo("<h2>Adatok előnézete:</h2>");
//print_r($_FILES);
//echo("<hr />");
if($_FILES['impcsv']['type']=='text/csv'){
  $ujnev=date("YmdHis").".csv";
  move_uploaded_file($_FILES['impcsv']['tmp_name'], "csvimport/$ujnev");
  $csv=fopen("csvimport/$ujnev", "r") or die("Nem lehet megnyitni a feltöltött fájlt!");
  $sor=fgets($csv);
  $s = split(',', $sor);
  echo("<table class='sorki'><tr class='fejlec'>");
  foreach ($s as $key=>$value) {
  	echo("<td style='font-weight:bold;font-size:85%;'>$value</td>");
  };
  echo("</tr>");
  $dik=0;
  while(!feof($csv)) {
    $sor=fgets($csv);
    list($nev,$nem,$szev,$email,$email2,$mob,$kf,$irsz,$telep,$cim,$pleb,$megj) = split(',', $sor);
    echo("<tr>");
    echo("<td>$nev</td>");
    echo("<td>$nem</td>");
    echo("<td>$szev</td>");
    echo("<td>$email</td>");
    echo("<td>$email2</td>");
    echo("<td>$mob</td>");
    echo("<td>$kf</td>");
    echo("<td>$irsz</td>");
    echo("<td>$telep</td>");
    echo("<td>$cim</td>");
    echo("<td>$pleb</td>");
    echo("<td>$megj</td>");
    echo("</tr>");
    $dik++;
  };
  fclose($csv);
  $dik--;
  echo("<tr class='fejlec'><td colspan=12' style='text-align:center;border:3px solid #660000;'>
        <form method='post'>
          Fájlnév (dátumból): <input type='text' name='csvnev' readonly value='$ujnev' style='margin:0px;' /><br />
          Eseményre regisztrálás:<select name='esem' style='margin:0px;'>
          <option>*nincs*</option>");
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
      
  echo("  </select><br />
          Adatsorok száma: <input type='number' name='sordb' value='$dik' readonly style='width:35px;' style='margin:0px;' />
          <input type='submit' name='csvimportgomb' value='feltöltés' style='margin:0px;' />
        </td></tr></table>");
  
}else{
  echo("CSV állományt kellene feltölteni!!!");
};

  

echo("<br /><br /><hr /><a href='http://vera.mente.hu/index.php?muv=szemimp'>vissza</a>");
}else{
?>
<p style='float:right;'><a href='http://vera.mente.hu/importsablon.csv' target='_blank'>Sablon letöltése</a><br />
utolsó módosítás: <?php echo(date ("Y.m.d", filemtime('importsablon.csv'))); ?></p>
<h2>Feltöltés</h2>
<form enctype="multipart/form-data" method="post">
Importálandó állomány (*.csv):<input type='file' name='impcsv'><br />
<input type="submit" name="csvfeltoltgomb" value="Állományellenőrzés" />
</form>
<?php
};
?>