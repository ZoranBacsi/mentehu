<?php

if(($_SESSION['regio']!=='7R') or ($_SESSION['rf']!==true)){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};


$mappa="kepmellekletek";

if(isset($_POST['ujfajlfeltoltesgomb'])){
  $kit=strtolower(substr($_FILES['ujfajl']['name'],-4));
  //echo($kit);
  if(substr($kit, 0, 1)!='.'){
    echo("<p style='color:red;margin:15px;'>HIBA: a feltöltendő fájl kiterjesztése 3 karakteres legyen!!</p><hr style='margin:10px 0px;' />");
  }else{
    move_uploaded_file($_FILES['ujfajl']['tmp_name'], $mappa."/".date("Ymd-His").$kit);
  }
};
if((isset($_POST['torlesgomb'])) && (isset($_POST['delell']))){
  unlink($_POST['torlendokep']);
};
?>
<fieldset style="float:right;margin:0px;padding:5px;text-align:center;width:240px;height:150px;background-color:#E1E187;border:1px solid red;">
<legend style='background-color:#E1E187;border:1px solid red;font-weight:bold;color:red;'>Kép törlése</legend>
<form method='post'>  
  <table style='width:100%;height:100%;'><tr><td style='width:155px;vertical-align: middle;'>
  <img src='hiba.gif' id='torlendo' style="max-width:150px;max-height:125px;margin:0px;border:2px solid red;" />
  <input type='hidden' id='torlendokep' name='torlendokep' value='' />
  </td><td style='vertical-align: middle;'>
  <input type="checkbox" name="delell">Biztos!<br /><br /> 
  <input type='submit' name='torlesgomb' value='Törlöm!' style='background-color:red;color:yellow;margin:0px;'>
  </td></tr></table>
</form>
 
</fieldset>

<form enctype="multipart/form-data" method="post">
  <h2>Új feltöltés:</h2> 
  <input type="file" name="ujfajl" />
  <input type="submit" name="ujfajlfeltoltesgomb" value="feltöltés" />  
</form>

<p style='margin:5px 0px;padding:10px;'>
A kijelölt kép URL-je: <br style='margin:5px;' />
<span style='font-weight:bold;margin:0px;padding:3px;border:1px solid black;'>http://vera.mente.hu/kepmellekletek/<span id="beillurl"></span> </span>
</p>

<br style='clear:both;' />

<h3 style='margin:10px;'>Tallózás:</h3>
<?php
$m=opendir($mappa);
while($f=readdir($m)){
 if(($f<>'.')&& ($f<>'..') && ($f<>'index.php')){
  $kepek[]=$f;
 }; 
};

sort($kepek);

// print_r($kepek);

$dik=0;
echo("<table style='width:97%;margin:auto;'>");
foreach ($kepek as $key=>$f) {	  
  if($dik % 7 == 0){
    echo("<tr>");
  }
  echo("<td>");
  $kit=substr($f, -4);
  switch ($kit) {
  case ".jpg":
    echo("<a href='$mappa/$f' target='_blank'><img src='$mappa/$f' style='max-width:90px;max-height:90px;' title='$f' /></a><br />");
    break;
  case ".png":
    echo("<a href='$mappa/$f' target='_blank'><img src='$mappa/$f' style='max-width:90px;max-height:90px;' title='$f' /></a><br />");
    break;
  case ".pdf":
    echo("<a href='$mappa/$f' target='_blank'><img src='../file-pdf.png' style='max-width:90px;max-height:90px;' title='$f' /></a><br />");  	
  	break;
  case ".zip":
    echo("<a href='$mappa/$f' target='_blank'><img src='../file-zip.png' style='max-width:90px;max-height:90px;' title='$f' /></a><br />");  	
  	break;
  default:
    echo("<a href='$mappa/$f' target='_blank'><img src='../file-ures.png' style='max-width:90px;max-height:90px;' title='$f' /></a><br />");  	
  	break;
  }    
      
  //echo("<img src='../pipa.gif' onClick='copyToClipboard(\"$mappa/$f\");' style='float:left;margin:0px;padding:0px;height:10px;cursor:pointer;' />");
  echo("<form method='post'>
        <img src='../pipa.gif' 
        onClick='document.getElementById(\"torlendo\").src=\"$mappa/$f\";document.getElementById(\"torlendokep\").value=\"$mappa/$f\";document.getElementById(\"beillurl\").innerHTML=\"$f\";' style='float:right;margin:0px;padding:0px;height:10px;cursor:pointer;' />
        </form>");      
  echo("</td>");
  if($dik % 7 == 6){
    echo("</tr>");
  };
  $dik++;
};
?>
</table>
