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

if(($_SESSION['regio']=='7R') and ($_SESSION['rf']==true)){
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

if(isset($_POST['ujsajatesemenygomb'])){
  $eSorsz=$_POST['eSorsz'];
  $rKod=$_SESSION['regio'];
  $eNev=$_POST['eNev'];
  $eDatumK=$_POST['eDatumK'];
  $eCimIrsz=$_POST['eCimIrsz'];
  $eTelep=$_POST['eTelep'];
  $eCim=$_POST['eCim'];
  if($_POST['eDatumB']!==''){
    $eDatumB=$_POST['eDatumB'];
    if($eSorsz>0){
    ab_lek("UPDATE t_esemenyek SET eNev='$eNev', eDatumK='$eDatumK', eDatumB='$eDatumB', eCimIrsz='$eCimIrsz', eTelep='$eTelep', eCim='$eCim' WHERE eSorsz=$eSorsz");
    }else{
    ab_lek("INSERT INTO t_esemenyek VALUES (NULL, '$rKod', '$eNev', '$eDatumK', '$eDatumB', '$eCimIrsz', '$eTelep', '$eCim')");
    }  
  }else{
    if($eSorsz>0){
    ab_lek("UPDATE t_esemenyek SET eNev='$eNev', eDatumK='$eDatumK',eDatumB=NULL, eCimIrsz='$eCimIrsz', eTelep='$eTelep', eCim='$eCim' WHERE eSorsz=$eSorsz");
    }else{  
    ab_lek("INSERT INTO t_esemenyek VALUES (NULL, '$rKod', '$eNev', '$eDatumK', NULL, '$eCimIrsz', '$eTelep', '$eCim')");
    };
  };  
};

if(!isset($_GET['esemeny'])){

if($_SESSION['rf']==true){
  $limit="";
}else{
  $limit=" LIMIT 1";
};

$e=ab_lek("SELECT * FROM t_esemenyek $szuro ORDER BY eDatumK DESC$limit");

//echo("<p style='text-align:right;>".mysql_num_rows($e)."</p>");

?>
<form id='ujesemenysor' style='display:none;' method='post'>
ID: <input type='text' name='eSorsz' id='eSorsz' value='* új *' readonly /><br />
Esemény neve: <input type='text' name='eNev' id='eNev' /><br />
Helyszín (irsz): <input type='text' name='eCimIrsz' id='eCimIrsz' /><br />
Helyszín (település): <input type='text' name='eTelep' id='eTelep' /><br />
Helyszín (cím): <input type='text' name='eCim' id='eCim' /><br />
Időpont: <input type='text' name='eDatumK' id='eDatumK' /> - <input type='text' name='eDatumB' id='eDatumB' /><br />
<input type='submit' name='ujsajatesemenygomb' />
<input type='button' value='Mégsem' onClick='document.getElementById("ujesemenysor").style.display="none";' />
</form>

<?php
echo("<table class='sorki' style='width:95%'>
       <tr>
        <td style='background-color:#660000;color:#FFFF99;padding:3px;text-align:center;'>Régió</td>
        <td style='background-color:#660000;color:#FFFF99;padding:3px;text-align:center;'>Esemény</td>
        <td style='background-color:#660000;color:#FFFF99;padding:3px;text-align:center;'>Helyszín</td>
        <td style='background-color:#660000;color:#FFFF99;padding:3px;text-align:center;'>Időpont</td>
        <td style='background-color:#660000;color:#FFFF99;padding:3px;text-align:center;'>Regisztrációk</td>");
if(($_SESSION['regio']!=='7R') and ($_SESSION['rf']==true)){
  echo("<td style='background-color:#660000;color:#FFFF99;padding:3px;text-align:center;'><button class='mpgomb' onClick='document.getElementById(\"ujesemenysor\").style.display=\"block\";'>Új esemény</button></td>");
};        
echo("  </tr>");
       
while ($sor=mysql_fetch_assoc($e)) {
  $e2=ab_lek("SELECT count(ssorsz) AS regdb FROM t_regisztraciok WHERE esorsz=".$sor['eSorsz']);
  $sor2=mysql_fetch_assoc($e2);
  $regdb=$sor2['regdb'];
  echo("<tr style='background-color:".$regioszin[$sor['rKod']].";'>");
  echo(" <td style='text-align:center;'>".$sor['rKod']."</td>
         <td>".$sor['eNev']."</td>
         <td>".$sor['eCimIrsz']." ".$sor['eTelep']."<br />
             ".$sor['eCim']."</td>
         <td style='text-align:center;'>".$sor['eDatumK']);
  if($sor['eDatumB']<>''){  
    echo(" - ".$sor['eDatumB']);
  };               
  echo("</td>
         <td><a href='http://vera.mente.hu/index.php?muv=regel&esemeny=".$sor['eSorsz']."'><button class='mpgomb kotelezo' style='float:right;'>$regdb fő…</button></a></td>");
  if($_SESSION['rf']!==true){
    $_SESSION['alkesem']=$sor['eSorsz'];
  };
  if(($_SESSION['regio']!=='7R') and ($_SESSION['rf']==true)){
    echo("<td><button class='mpgomb' 
    onClick='document.getElementById(\"ujesemenysor\").style.display=\"block\";
             document.getElementById(\"eSorsz\").value=\"".$sor['eSorsz']."\";
             document.getElementById(\"eNev\").value=\"".$sor['eNev']."\";
             document.getElementById(\"eCimIrsz\").value=\"".$sor['eCimIrsz']."\";
             document.getElementById(\"eTelep\").value=\"".$sor['eTelep']."\";
             document.getElementById(\"eCim\").value=\"".$sor['eCim']."\";
             document.getElementById(\"eDatumK\").value=\"".$sor['eDatumK']."\";
             document.getElementById(\"eDatumB\").value=\"".$sor['eDatumB']."\";'
    >Szerkesztés</button></td>");
  };
  echo("</tr>");               
};
echo("</table>");

// eddig az 1.oldal
}else{
// innen a 2.oldal
if($_SESSION['rf']!==true){
  if($_SESSION['alkesem']!=$_GET['esemeny']){
    die("Semmi trükközés, kérlek az a felkínált eseményre regisztrálj!!");
  };
};

$e=ab_lek("SELECT * FROM t_esemenyek WHERE eSorsz=".$_GET['esemeny']." ORDER BY eDatumK DESC");
$es=mysql_fetch_assoc($e);
echo("<table style='width:100%; border: 0px'><tr><td> 
        <span style='float:right;text-align:right;'>".$es['eDatumK']);
  if(strlen($es['eDatumB'])>0){echo("-".$es['eDatumB']);};      
  echo("<br />".$es['eCimIrsz']." ".$es['eTelep']."<br />".$es['eCim']."        
        </span>
        ".$regionevek[$es['rKod']]."-mente<br /><h1 style='margin:3px;'>".$es['eNev']."</h1>");
echo("</td></tr>");

echo("<tr><td style='border:0px;vertical-align:top;'>
      <a href='http://vera.mente.hu/index.php?muv=szemszerk&esem=".$_GET['esemeny']."' target='_blank' style='margin:0px;float:right;'><button class='mpgomb'>Új személy...</button></a>");
if(isset($_POST['minta'])){
  $ertek="value='".$_POST['minta']."'";
}else{
  $ertek='';
};
echo("<form method='post'>
        <input type='text' name='minta' $ertek />
        <input type='submit' name='mintagomb' value='Keres...'  />
        <input type='checkbox' name='visszatero' /> Volt már nálunk :)
      </form>"); 
if(isset($_POST['minta'])){
  if(isset($_POST['visszatero'])){
    $regfel=" AND sorsz IN (SELECT ssorsz FROM vEseReg WHERE rKod='".$es['rKod']."') ";
  }else{
    $regfel="";
  };      
  $e=ab_lek("SELECT * FROM t_szemelyek WHERE nev LIKE '%".$_POST['minta']."%' AND sorsz NOT IN (SELECT ssorsz FROM t_regisztraciok WHERE esorsz=".$_GET['esemeny'].") $regfel ORDER BY nev");
  $sordb=mysql_num_rows($e);
  if($sordb>0){
    echo("<h2>A mintának megfelelő találatok: ($sordb fő)</h2>
          <br />
          <table class='sorki' style='width:100%;'>");
    $dik=0;
    while($sor=mysql_fetch_assoc($e)){
    $dik++;
      if($dik % 3 == 1){
        echo("<tr>");
      };
      echo("<td>".$sor['nev']." (".$sor['szev'].")<br />".$sor['laktelep']."</td><td>
        <form method='post' action='".$_SERVER['PHP-SELF']."' style='margin:0px;padding:0px;float:right;'>
            <input type='hidden' value='".$sor['sorsz']."' name='szemsorsz' style='margin:0px;padding:0px;' />
            <input type='submit' name='szemregel' value='Jelen !' style='margin:0px;padding:0px;text-align:center;font-size:8pt;background-color:lightgreen;'
             onClick='window.open(\"http://vera.mente.hu/index.php?muv=szemszerk&szemsorsz=".$sor['sorsz']."\");' />
        </form>
        </td>");
      if($dik % 3 == 1){
        echo("<td style='width:10px;border:0px;background-color:#FFFFD2;'></td>");
      };        
      if($dik % 3 == 2){
        echo("<td style='width:10px;border:0px;background-color:#FFFFD2;'></td>");
      };        
      if($dik % 3 == 0){
        echo("</tr>");
      };        
    };      
    echo("</table>");          
  };
                  
}else{
  echo("<p style='margin:10px;0px;'>Írj be egy részletet a keresendő névből!</p>");
};

echo("</td></tr>");

$e=ab_lek("SELECT * FROM t_szemelyek WHERE sorsz IN (SELECT ssorsz FROM t_regisztraciok WHERE esorsz=".$_GET['esemeny'].") ORDER BY nev");
$sordb=mysql_num_rows($e);
echo("<tr>
      <td style='width:100%; vertical-align:top;'>
      <h2 style='margin:15px 0px;'>Már regisztráltak: ($sordb fő)</h2>");
echo("<table style='width:100%;' class='sorki'>"); 
$dik=0;
while ($sor=mysql_fetch_assoc($e)){
  $dik++;
  if($dik % 2 == 1){
    echo("<tr>");
  };
  echo("<td>".$sor['nev']."<br />".$sor['laktelep']." - ".(date("Y")-$sor['szev'])." éves</td><td>
        <form method='post' action='".$_SERVER['PHP-SELF']."' style='margin:0px;padding:0px;'>
          <input type='hidden' value='".$sor['sorsz']."' name='szemsorsz' style='margin:0px;padding:0px;' />
          <input type='submit' name='szemunregel' value='Nincs itt!' style='margin:0px;padding:0px;text-align:center;font-size:8pt;background-color:#FF6D6D' />
        </form>
        </td><td>
        <form method='post' action='http://vera.mente.hu/index.php?muv=szemszerk' style='margin:0px;padding:0px;float:left;' target='_blank'>
          <input type='hidden' name='szemsorsz' value='".$sor['sorsz']."' />
          <input type='submit' class='mpgomb' value='Adatlap' style='background-color:yellow;' />
        </form>
        </td>");
  if($dik % 2 == 1){
    echo("<td style='width:15px;border:0px;background-color:#FFFFD2;'></td>");
  };        
  if($dik % 2 == 0){
    echo("</tr>");
  };      


/*  echo("<fieldset style='padding:3px;margin:3px;width:170px;'>
        <legend>".$sor['nev']."</legend>
        
        ".$sor['laktelep']."
        <form method='post' action='".$_SERVER['PHP-SELF']."' style='margin:0px;padding:0px;float:right;'>
          <input type='hidden' value='".$sor['sorsz']."' name='szemsorsz' style='margin:0px;padding:0px;' />
          <input type='submit' name='szemunregel' value='Nincs is itt!' style='margin:0px;padding:0px;text-align:center;font-size:8pt;' />
        </form>

        <form method='post' action='http://vera.mente.hu/index.php?muv=szemszerk' style='margin:0px;padding:0px;float:left;'>
          <input type='hidden' name='szemsorsz' value='".$sor['sorsz']."' />
          <input type='submit' class='mpgomb' value='Adatpontosítás' />
        </form>

        </fieldset>");*/
};
if($dik % 2 == 1);
{
  echo("</tr>");
};
      
echo("</td></tr>");

echo("</table>");
};   
?>
