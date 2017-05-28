<?php
if($_SESSION['bent']!==TRUE){
echo("Ez az oldal azonosításhoz kötött, lépj be!");
die;
};

if((isset($_POST['szemsorsz'])) or (isset($_GET['szemsorsz']))) { 
  if(isset($_POST['szemsorsz'])){
    $szemsorsz=$_POST['szemsorsz'];
  }else{
    $szemsorsz=$_GET['szemsorsz'];
  };
  $e=ab_lek("SELECT * FROM t_szemelyek WHERE sorsz=".$szemsorsz);
  $sor=mysql_fetch_assoc($e);
  $sz_nev=$sor['nev'];
  $sz_szev=$sor['szev'];
  $sz_mobil=$sor['mobil'];
  $sz_kf=$sor['kf'];
  $sz_irsz=$sor['lakirsz'];
  $sz_telep=$sor['laktelep'];
  $sz_cim=$sor['lakcim'];
  $sz_pleb=$sor['pleb'];
  $sz_email1=$sor['email'];
  $sz_email2=$sor['email2'];
  $sz_sorsz=$sor['sorsz'];
  $sz_utmod=$sor['utmod'];
  $sz_megj=$sor['megj'];
  $sz_nem=$sor['nem'];
  $sz_e1tilt=$sor['e1tilt'];
  $sz_e2tilt=$sor['e2tilt'];
}else{
  $sz_nev='';
  $sz_szev='';
  $sz_mobil='';
  $sz_kf='';
  $sz_irsz='';
  $sz_telep='';
  $sz_cim='';
  $sz_pleb='';
  $sz_email1='';
  $sz_email2='';
  $sz_utmod="-";
  $sz_megj='';
  $sz_nem='';
};
?>


<p style="float:right;">
<?php
if(isset($szemsorsz)){
?>
Utolsó módosítás: <?php echo($sz_utmod); ?>
<?php
};
?>
<input type="text" readonly value=" Az ilyen mezők kitöltése kötelező! " class="kotelezo" style="width:175px;cursor: none;margin-left:20px;" />
</p>
<h1>Adatlap</h1>
<form method="post"> 
<input type="hidden" name="szemsorsz" value="<?php echo($sz_sorsz); ?>"/>
<table border="0">
<tr style="">
<td class="fejlec" style="text-align:right;border-bottom:1px dotted black;">Név:</td>
<td style="border-bottom:1px dotted black;">
    <input type="text" name="nev" required id="nev" size="33" value="<?php echo($sz_nev); ?>" 
     class="kotelezo" />
    <span style="font-style:italic;margin-left:5px;">Szül.év:</span>
    <input type="number" min=1900 max="<?php echo(date("Y")); ?>" name="szev" required id="szev" value="<?php echo($sz_szev); ?>"
     class="kotelezo" style="width:40px;" />
    <span style="font-style:italic;margin-left:5px;">Nem:</span>
    <input type="text" name="nem" required id="nem" size="1" value="<?php echo($sz_nem); ?>"
     class="kotelezo" />
     <span onClick='document.getElementById("nem").value="n";' style='padding:3px;margin:0px;border:1px solid gray;background-color:white;color:red;background-color:pink;font-wieght:bold;cursor:pointer;'>n</span>
     <span onClick='document.getElementById("nem").value="f";' style='padding:3px;margin:0px;border:1px solid gray;background-color:white;color:blue;background-color:lightblue;font-wieght:bold;cursor:pointer;'>f</span>
</td>
<td rowspan="7" style="vertical-align:top;border-left:1px dotted black;<?php
if($_SESSION['rf']!==true){
  echo("display:none;");
};
?>"" >
Megjegyzés:<br />
<textarea name="megj" style="height:300px;width:250px;font-size:85%;"><?php echo($sz_megj); ?></textarea>
<br />max: 2.147.483.647 karakter :)<br />
*törlésre megjelöléshez bárhol<br />szerepeljen benne a "KITÖRÖLNI" szó!
</td>
</tr>
<tr>
<td class="fejlec" style="text-align:right;border-bottom:1px dotted black;">Email:</td>
<td style="border-bottom:1px dotted black;">
  <p style="float:right;margin:0px;padding:0px;">
  <input type="checkbox" name="e1tilt" title="Elsődleges email címre hírlevél tiltása" <?php if($sz_e1tilt=='i'){echo('checked="checked"');}; ?> />
  </p>
  <input type="email" name="email1" required id="email1" style="width:270px;" value="<?php echo($sz_email1); ?>" class="kotelezo" />
  <img src="citromail.png" title="Citromail" onClick="document.getElementById('email1').value+='@citromail.hu';" />
  <img src="gmail.png" title="Gmail" onClick="document.getElementById('email1').value+='@gmail.com';" />
  <img src="freemail.png" title="Freemail" onClick="document.getElementById('email1').value+='@freemail.hu';" />
  <img src="indamail.png" title="Indamail" onClick="document.getElementById('email1').value+='@indamail.hu';" />
  <img src="hotmail.png" title="Hotmail" onClick="document.getElementById('email1').value+='@hotmail.com';" />

  <br />
  
  <p style="float:right;margin:0px;padding:0px;">
  <input type="checkbox" name="e2tilt" title="Másodlagos email címre hírlevél tiltása" <?php if($sz_e2tilt=='i'){echo('checked="checked"');}; ?> />
  </p>
  <input type="email" name="email2" id="email2" style="width:270px;" value="<?php echo($sz_email2); ?>" />
  <img src="citromail.png" title="Citromail" onClick="document.getElementById('email2').value+='@citromail.hu';" />
  <img src="gmail.png" title="Gmail" onClick="document.getElementById('email2').value+='@gmail.com';" />
  <img src="freemail.png" title="Freemail" onClick="document.getElementById('email2').value+='@freemail.hu';" />
  <img src="indamail.png" title="Indamail" onClick="document.getElementById('email2').value+='@indamail.hu';" />
  <img src="hotmail.png" title="Hotmail" onClick="document.getElementById('email2').value+='@hotmail.com';" />
</td>
</tr>
<tr>
<td class="fejlec" style="text-align:right;border-bottom:1px dotted black;" >Mobil:</td>
<td style="border-bottom:1px dotted black;">+36 - 
<select name="mobilkorzet" id="mobilkorzet" class="kotelezo" 
 onChange="if(this.value=='nincs'){document.getElementById('mobilszam').style.visibility='hidden';
                                   document.getElementById('mobilszam').value='';}
                            else{document.getElementById('mobilszam').style.visibility='visible';}; mobil_ell();">
 <option <?php if($sz_mobil==''){echo(' selected="selected" ');}; ?>>nincs</option>
 <option <?php if(substr($sz_mobil,0,2)==20){echo(' selected="selected" ');}; ?>>20</option>
 <option <?php if(substr($sz_mobil,0,2)==30){echo(' selected="selected" ');}; ?>>30</option>
 <option <?php if(substr($sz_mobil,0,2)==70){echo(' selected="selected" ');}; ?>>70</option>
</select>
<input type="number" name="mobil" min="1000000" max="9999999" id="mobilszam" size="9" value="<?php echo(substr($sz_mobil,2,7)); ?>" 
class="kotelezo" style="width:60px;<?php if($sz_mobil==''){echo('visibility:hidden;');}; ?> " />
<p style="float:right;margin:0px;padding:0px;">
<input type="checkbox" name="kf" title="Katolikus Flottás Mobilszám" <?php if($sz_kf=='i'){echo('checked="checked"');}; ?> />KF
</p>
</td>
</tr>
<tr>
<td class="fejlec" style="text-align:right;border-bottom:1px dotted black;">Lakhely:</td>
<td style="border-bottom:1px dotted black;">
    <input type="text" name="irsz" id="irsz" size="3" title="Irányítószám"  value="<?php echo($sz_irsz); ?>" />
    <input type="text" name="telep" required id="telep" title="Település" style="width:100px;" value="<?php echo($sz_telep); ?>" class="kotelezo" />
    <input type="text" name="cim" id="cim" title="Címrész" style="width:150px;" value="<?php echo($sz_cim); ?>" onBlur="cim_ell();" />
</td>
</tr>
<tr>
<td class="fejlec" style="text-align:right;border-bottom:1px dotted black;">Plébánia:</td>
<td style="border-bottom:1px dotted black;">  
  <select name="etelep">
    <?php
    $ep=ab_lek("SELECT DISTINCT pleb FROM t_szemelyek WHERE length(pleb)>0 ORDER BY pleb");
    while ($sp=mysql_fetch_assoc($ep)) {
    	echo("<option");
      if($sp['pleb']==$sz_pleb){
        echo(' selected="selected" ');
      };
      echo(">".$sp['pleb']."</option>");
    };
    ?>
  </select>
  vagy:
  <input type="text" name="etelepuj" id="etelepuj" title="Egyházközség"  value="" onBlur="etelep_ell();" />
</td>
</tr>        
<?php
if(!isset($szemsorsz))
{
?>
<tr>
<td style="border-bottom:1px dotted black;">
  Régió
</td>
<td style="border-bottom:1px dotted black;">
  <select name="kezdocsop" >
    <option value="18">Vendég</option>
    <option value="3">ÉszakDunás</option>
    <option value="2">DélDunás</option>
    <option value="4">Galgás</option>
    <option value="5">Ipolyos</option>
    <option value="6">Tápiós</option>
    <option value="7">Tiszás</option>
    <option value="8">Zagyvás</option>
  </select> &lt;&lt;<i>ennek a régiónak az eseményeiről értesítjük</i>
</td>
</tr>
<?php
};
?>
<tr>
  <td colspan="2">
    <input type="button" value="Bezár módosítás nélkül..." onClick="window.close();" style="float:right;margin-left:5px;" />
    <input type="submit" value="Adatlap mentése" name="szemadatlapmentesgomb" id="szemadatlapmentesgomb" style="margin-left: 77px;float:right;" class="kotelezo" /> 
    <input type="checkbox" name="ablakbezaras" checked /> Ablak bezárása mentés után!
  </td>
</tr>
</table>
</form>
<?php
if(( isset($szemsorsz)) and ($_SESSION['rf']==true)){
?>
<h2 style="margin-top:15px;">Csoporttagságok</h2>
<table border="0">
<tr>
<td style="vertical-align:top;border-right:1px dotted black;">Jelenlegi tagságok:
<?php
$e2=ab_lek("SELECT * FROM vCsopTagsagNevvel WHERE szid=$sz_sorsz");
if(mysql_num_rows($e2)==0){
 echo("<br />Nincs még tagság...");
}else{
  echo("<ul>");
  while($sor2=mysql_fetch_assoc($e2)){
    echo("<li>".$sor2['csnev']."</li>");
  };
  echo("</ul>");
};
?>
</td>
<td style="vertical-align:top;border-right:1px dotted black;">
<span style="font-weight:bold;color:red;"><br />Új csoportba felvétel:</span><br />
<form method="post">
  <select name="csoportbahova">
   <?php
    $e2=ab_lek("SELECT * FROM t_csoportok WHERE csid NOT IN (SELECT csid FROM t_csoptagsagok WHERE szid=$sz_sorsz)");
    while($sor2=mysql_fetch_assoc($e2)){
      echo("<option value='".$sor2['csid']."'>".$sor2['csnev']."</option>");
    };
   ?>
 </select>
 <input type="hidden" name="szemsorsz" value="<?php echo($sz_sorsz); ?>" />
 <input type="submit" name="csoportbasorolasgomb" value="Csoportba felvétel!" />
</form>
<span style="font-weight:bold;color:red;"><br />Csoportból törlés:</span><br />
<form method="post">
  <select name="ebbolki">
   <?php
    $e2=ab_lek("SELECT * FROM t_csoportok WHERE csid IN (SELECT csid FROM t_csoptagsagok WHERE szid=$sz_sorsz)");
    while($sor2=mysql_fetch_assoc($e2)){
      echo("<option value='".$sor2['csid']."'>".$sor2['csnev']."</option>");
    };
   ?>
 </select>
 <input type="hidden" name="szemsorsz" value="<?php echo($sz_sorsz); ?>" />
 <input type="submit" name="csoportboltorlesgomb" value="Csoportból törlés!" />
</form>
</td>
<td style="width:50px;"></td>
<td style="vertical-align:top;">Regisztrált a következő eseményeken:
  <ul>
   <?php
    $e2=ab_lek("SELECT * FROM vEseReg WHERE ssorsz=$sz_sorsz");
    while($sor2=mysql_fetch_assoc($e2)){
      echo("<li>".$sor2['eNev']." - ".$sor2['eTelep']." (".$sor2['rKod'].") ".$sor2['eDatumK']."</li>");
    };
   ?>
   </ul>
</td>
</tr>
</table>
</td>
</tr>
<?php
};

?>
  