<?php
session_start();

include("konfig.inc.php");

?>
<!DOCTYPE html>
<html >
<head>

<title>.. .: :: Váci Egyházmegye Regionális Adatbank :: :. ..</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf8"/>
<meta name="copyright" content="Gödöny Péter, peter@godony.hu" />
<meta name="desing" content="Gödöny Péter, peter@godony.hu" />


<style type="text/css" media="all">
@import url("vera.css");

body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

</style>

<?php
if(isset($_GET['muv'])){$muv=$_GET['muv'];}else{$muv='rogkez';};

if($muv=='kilepes'){
  session_destroy();
  echo("<br /><br /><center><a href='http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]."'>újra belépés...</a></center>");
  die;
};
?>

</head>
<body style="margin:10px;">

<?php

if(isset($_POST['kijelentkezesgomb'])){
  echo('Sikeres kilépés...');
  session_destroy();
  include("belepteto.html");
  die;
};

if(isset($_POST['belepesgomb'])){
  $lek=ab_lek("SELECT * FROM t_rogzitok WHERE rognev='".$_POST['azonosito']."'");
  if(mysql_num_rows($lek)==1){
    $sor=mysql_fetch_assoc($lek);
    if(($sor['rfjszo']==$_POST['jelszo']) and (strlen($_POST['azonosito'])>0) and (strlen($_POST['jelszo'])>0) and ($sor['rferv']>=date('Y-m-d')) ){
      $_SESSION['rogzito']=$_POST['azonosito'];
      $_SESSION['megszol']=$_POST['azonosito'];
      $_SESSION['bent']=TRUE;
      $_SESSION['regio']=$sor['regio'];
      $_SESSION['regiosorsz']=$sor['sorsz'];
      $_SESSION['rf']=TRUE;
      $e=ab_lek("INSERT INTO t_szerknaplo VALUES ('".$_SESSION['regio']."', 0, 'Belépés (rf)', '".date("Y-m-d H:m:s")."')");
      }else{
      if(($sor['rogjszo']==$_POST['jelszo']) and (strlen($_POST['azonosito'])>0) and (strlen($_POST['jelszo'])>0) and ($sor['rogerv']>=date('Y-m-d')) ){
        $_SESSION['rogzito']=$_POST['azonosito'];
        $_SESSION['megszol']=$_POST['azonosito'];
        $_SESSION['bent']=TRUE;
        $_SESSION['regio']=$sor['regio'];
        $_SESSION['rf']=FALSE;
        $e=ab_lek("INSERT INTO t_szerknaplo VALUES ('".$_SESSION['regio']."', 0, 'Belépés (alk)', '".date("Y-m-d H:m:s")."')");

        }else{
          echo("Hibás a beírt felhasználói azonosító és/vagy (lejárt) a jelszó!");
          //próbálkozás növelése?!?
        };
      };
  }else{
   echo("Hibás a beírt felhasználói azonosító és/vagy jelszó!");
  };
  ab_bont();
};

if((isset($_SESSION['bent'])) and ($_SESSION['bent']==TRUE)){  
  include("felulet.inc.php");
}else{
  include("belepteto.html");
};
  
include("lablec.inc.php");  
?>
</body>