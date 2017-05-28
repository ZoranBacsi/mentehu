<?php

if(($_SESSION['regio']!=='7R') or ($_SESSION['rf']!==true)){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};


$mappa="kepmellekletek";
if(isset($_POST['ujfajlfeltoltesgomb'])){
  move_uploaded_file($_FILES['ujfajl']['tmp_name'], $mappa."/".$_FILES['ujfajl']['name']);
};
if((isset($_POST['torlesgomb'])) && (isset($_POST['delell']))){
  unlink($_POST['torlendo']);
};
?>
<form enctype="multipart/form-data" method="post">
  Új feltöltés: <span style="color:red;">Nem javasolt ékezet, szóköz, speciális karakterek (pl.: ,%!?;()[]{}@&hellip;) használata fájlénévben!</span><br />
  <input type="file" name="ujfajl" />
  <input type="submit" name="ujfajlfeltoltesgomb" value="feltöltés" />  
</form>
<hr />
<p>Tallózás:</p>
<?php
$m=opendir($mappa);
while($f=readdir($m)){
 if(($f<>'.')&& ($f<>'..') && ($f<>'index.php')){
  echo("<div style='border:1px dotted lightgray;width:90px;height:100px;float:left;margin:2px;text-align:center;background-color:white;'>");
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
        <img src='../pipa.gif' onClick='document.getElementById(\"torlendo\").value=\"$mappa/$f\";document.getElementById(\"beillurl\").innerHTML=\"$f\";' style='float:right;margin:0px;padding:0px;height:10px;cursor:pointer;' />
        </form>");      
  echo("</div>");
 };
};
?>
<br style='clear:both;' />

<hr />
<form method='post'>
  Beillesztéshez: http://vera.mente.hu/kepmellekletek/<span id="beillurl"></span>
  <br />
  Törlendő: <input type='text' id='torlendo' name='torlendo' style="width:600px" />
  <input type="checkbox" name="delell"> Igen, biztos törlöm!<br /> 
  <input type='submit' name='torlesgomb' value='Törölni!' style='background-color:red;color:yellow;float:right;'>
</form>
