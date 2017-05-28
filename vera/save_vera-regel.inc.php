
<?php

echo("<script type='text/javascript' language='JavaScript' charset='utf-8'>
      function mindetrejt(){");
foreach ($kb as $key=>$value) {
	echo("document.getElementById('k$value').className='rejtett';");
};
echo("};
      </script>");

echo("<script type='text/javascript' language='JavaScript' charset='utf-8'>
      function mindetmutat(){");
foreach ($kb as $key=>$value) {
	echo("document.getElementById('k$value').className='lathato';");
};
echo("};
      </script>");

 
if($_SESSION['bent']!==TRUE){
  echo("Ez az oldal azonosításhoz kötött, lépj be!");
  die;
};

if($_SESSION['regio']=='7R'){
  $szuro="";
}else{
  $szuro=" WHERE rKod='".$_SESSION['regio']."'";
};  

if(isset($_POST['szemregel'])){
  $e=ab_lek("INSERT INTO t_regisztraciok VALUES (".$_GET['esemeny'].", ".$_POST['szemsorsz'].")");
  $e=ab_lek("INSERT INTO t_szerknaplo VALUES ('".$_SESSION['regio']."', ".$_POST['szemsorsz'].", 'Regisztráció-".$_GET['esemeny']."', '".date("Y-m-d H:m:s")."')");
};

if(isset($_POST['szemunregel'])){
  $e=ab_lek("DELETE FROM t_regisztraciok WHERE esorsz=".$_GET['esemeny']." and ssorsz=".$_POST['szemsorsz']."");
  $e=ab_lek("INSERT INTO t_szerknaplo VALUES ('".$_SESSION['regio']."', ".$_POST['szemsorsz'].", 'Regisztráció törlése-".$_GET['esemeny']."', '".date("Y-m-d H:m:s")."')");

};

if(!isset($_GET['esemeny'])){

$e=ab_lek("SELECT * FROM t_esemenyek $szuro ORDER BY eDatumK DESC");

while ($sor=mysql_fetch_assoc($e)) {
	echo("<fieldset style='background-color:".$regioszin[$sor['rKod']].";'>
         <a href='http://vera.mente.hu/index.php?muv=regel&esemeny=".$sor['eSorsz']."'><button class='mpgomb' style='float:right;'>Ide…</button></a>
        <legend>".$regionevek[$sor['rKod']]." mente</legend>"
        .$sor['eDatumK']);
  if($sor['eDatumB']<>''){  
    echo(" - ".$sor['eDatumB']);
  };      
  $e2=ab_lek("SELECT count(ssorsz) AS regdb FROM t_regisztraciok WHERE esorsz=".$sor['eSorsz']);
  $sor2=mysql_fetch_assoc($e2);
  $regdb=$sor2['regdb'];
  echo("<h2>".$sor['eNev']."</h2>".$sor['eTelep']." - $regdb fő</fieldset>");
};

}else{

$e=ab_lek("SELECT * FROM t_esemenyek WHERE eSorsz=".$_GET['esemeny']." ORDER BY eDatumK DESC");
$es=mysql_fetch_assoc($e);
echo("<table style='width:100%; border: 1px solid red;'><tr><td  colspan='2'>
        <span style='float:right;text-align:right;'>".$es['eDatumK']);
  if(strlen($es['eDatumB'])>0){echo("-".$es['eDatumB']);};      
  echo("<br />".$es['eCimIrsz']." ".$es['eTelep']."<br />".$es['eCim']."</span>
        ".$regionevek[$es['rKod']]."-mente<br /><h1 style='margin:3px;'>".$es['eNev']."</h1>");
echo("</td></tr>
      <tr>
      <td style='width:50%; border: 1px solid red;vertical-align:top;'>Regisztráltak<br />");
$e=ab_lek("SELECT * FROM t_szemelyek WHERE sorsz IN (SELECT ssorsz FROM t_regisztraciok WHERE esorsz=".$_GET['esemeny'].") ORDER BY nev"); 
while ($sor=mysql_fetch_assoc($e)){
  echo("<fieldset style='padding:3px;margin:3px;width:170px;'>
        <legend>".$sor['nev']."</legend>

        ".$sor['email']."<br />
        +36-".substr($sor['mobil'],0,2)."-".substr($sor['mobil'],2,3)."-".substr($sor['mobil'],5,2)."-".substr($sor['mobil'],7,2));
  if($sor['kf']=='i'){echo(" KF");};
  echo("<br />".$sor['lakirsz']." ".$sor['laktelep'].", ".$sor['lakcim']."<br />
        <table style='margin:0px;padding:0px;width:100%;'>
         <tr style='margin:0px;padding:0px;'>
          <td style='margin:0px;padding:0px;text-align:center;'>
        <form method='post' action='http://vera.mente.hu/index.php?muv=szemszerk' style='margin:0px;padding:0px;float:left;'>
          <input type='hidden' name='szemsorsz' value='".$sor['sorsz']."' />
          <input type='submit' class='mpgomb' value='Adatpontosítás' />
        </form>
         </td>
         <td style='margin:0px;padding:0px;text-align:center;'>
        <form method='post' action='".$_SERVER['PHP-SELF']."' style='margin:0px;padding:0px;'>
          <input type='hidden' value='".$sor['sorsz']."' name='szemsorsz' style='margin:0px;padding:0px;' />
          <input type='submit' name='szemunregel' value='Nincs is itt!' style='margin:0px;padding:0px;text-align:center;font-size:8pt;' />
        </form>
         </td>
         </tr>
        </table>   
        </fieldset>");
};      
echo("</td>
      <td style='width:50%; border: 1px solid red;vertical-align:top;'>
      <a href='http://vera.mente.hu/index.php?muv=szemszerk' style='float:left;width:80px;height:20px;'><button class='mpgomb'>Új személy...</button></a>");
foreach ($kb as $key=>$value) {
	echo(" <span class='link' onClick='mindetrejt();document.getElementById(\"k$value\").className=\"lathato\";'>$value</span> |");
};
echo(" <span class='link' onClick='mindetmutat();'>*</span><hr />");
foreach ($kb as $key=>$value) {
  $e=ab_lek("SELECT * FROM t_szemelyek WHERE nev LIKE '$value"."%' AND sorsz NOT IN (SELECT ssorsz FROM t_regisztraciok WHERE esorsz=".$_GET['esemeny'].") ORDER BY nev");
  echo("<div id='k$value' class='rejtett'>"); 
  while($sor=mysql_fetch_assoc($e)){
   if($value=='A'){ 
    if(substr($sor['nev'], 0, 1)==$value){
    echo("<fieldset style='padding:3px;margin:3px;width:170px;'>
          <legend title='".$sor['email']."'>".$sor['nev']."</legend>
          <form method='post' action='".$_SERVER['PHP-SELF']."' style='margin:0px;padding:0px;float:right;'>
            <input type='hidden' value='".$sor['sorsz']."' name='szemsorsz' style='margin:0px;padding:0px;' />
            <input type='submit' name='szemregel' value='Jelen !' style='margin:0px;padding:0px;text-align:center;font-size:8pt;' />
          </form>
          ".$sor['laktelep']."<br />
          </fieldset>");
    };  
   }else{
    echo("<fieldset style='padding:3px;margin:3px;width:170px;'>
          <legend title='".$sor['email']."'>".$sor['nev']."</legend>
          <form method='post' action='".$_SERVER['PHP-SELF']."' style='margin:0px;padding:0px;float:right;'>
            <input type='hidden' value='".$sor['sorsz']."' name='szemsorsz' style='margin:0px;padding:0px;' />
            <input type='submit' name='szemregel' value='Jelen !' style='margin:0px;padding:0px;text-align:center;font-size:8pt;'
             onClick='window.open(\"http://vera.mente.hu/index.php?muv=szemszerk&szemsorsz=".$sor['sorsz']."\");' />
          </form>
          ".$sor['laktelep']."<br />
          </fieldset>");   
   }; 
  };
  echo("</div>");
};

/*
$e=ab_lek("SELECT * FROM t_szemelyek WHERE sorsz NOT IN (SELECT ssorsz FROM t_regisztraciok WHERE esorsz=".$_GET['esemeny'].") ORDER BY nev"); 
while($sor=mysql_fetch_assoc($e)){
  echo("<fieldset style='padding:3px;margin:3px;width:170px;'>
        <legend title='".$sor['email']."'>".$sor['nev']."</legend>
        <form method='post' action='".$_SERVER['PHP-SELF']."' style='margin:0px;padding:0px;float:right;'>
          <input type='hidden' value='".$sor['sorsz']."' name='szemsorsz' style='margin:0px;padding:0px;' />
          <input type='submit' name='szemregel' value='Jelen !' style='margin:0px;padding:0px;text-align:center;font-size:8pt;' />
        </form>
        ".$sor['laktelep']."<br />");
  echo("</fieldset>");  
};           
*/
echo("</td></tr></table>");
};   
?>
