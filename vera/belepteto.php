<?php
session_start();

include("konfig.inc.php");

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
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


</head>
<body style="margin:10px;">
<center>
<span class='cim'>
<font size='+3'><b>V</b></font>áci 
<font size='+3'><b>E</b></font>gyházmegyei 
<font size='+3'><b>R</b></font>egionális 
<font size='+3'><b>A</b></font>datbázis
</span>

<br><br>

<?php
$e=ab_lek("SELECT rog FROM t_rogemails WHERE email='".$_POST['azonosito']."'");
if((isset($_POST['belepesgomb'])) && (mysql_num_rows($e)!=1)){
  die("Nincs ilyen email címnek engedélyezve a belépés...");
}
else
{
  $sor=mysql_fetch_assoc($e);
  if((isset($_POST['belepesgomb'])) && ($_POST['jelszo']!=date("ym"))){
    die("Hibás a PIN kód!");
  }
  else{
    $e2=ab_lek("SELECT rferv, rfjszo, regio FROM t_rogzitok WHERE sorsz=".$sor['rog']);
    $sor2=mysql_fetch_assoc($e2);
    if(date("Y-m-d")>$sor2['rferv']){
      $ujpw=strtoupper(substr(md5(date("Y-m-d H:m:s")),0,9));
      $holnap = date("Y-m-d", strtotime("+1 day"));
      $uzenet = $holnap." éjfélig érvényes jelszó: ".$ujpw;
      ab_lek("UPDATE t_rogzitok SET rferv='$holnap', rfjszo='$ujpw' WHERE sorsz=".$sor['rog']);      
    }
    else
    {
      $uzenet = $sor2['rferv']." ejfelig ervenyes jelszo: ".$sor2['rfjszo'];
    }
    $cimzett = $_POST['azonosito'];    
    $targy = "Belépési jelszó";
    $fej = "From: Vera "." <" . "vera@mente.hu" . ">\r\n"; 
    mail($cimzett, $targy, $uzenet, $fej);   
    if(isset($_POST['belepesgomb'])){
      $e=ab_lek("INSERT INTO t_szerknaplo VALUES ('".$sor2['regio']."', 0, 'Jelszóigénylés: ".$_POST['azonosito']."', '".date("Y-m-d H:m:s")."')");      
    };
  };

?>

<form name='belepoform' method='post' action='index.php'>
<table>
<tr>
<td>
<a href='http://vera.mente.hu'><img src='lakat.png' alt='belépés' border='0'></a>
</td>
<td class='kozepre'>
<br><br>
<i>Azonosító:</i>
<br>
<input type='text' name='azonosito' size='10' class='gomb' style='margin:0px;' />
<br /><br />
<i>Emailban kapott jelszó:</i>
<br>
<input type='password' name='jelszo' size='10' class='gomb' style='margin:0px;' />
<br><br>
<!-- <span style='color:red;font-weight:bold;font-style:italic;'> Rendszerkarbantartás miatt <br> 2017.04.03 20:00 és 2017.04.04 8:00 <br>között korlátozva lesz a belépés!!</span> -->
<br><br>
<input type='submit' name='belepesgomb' value='Belépés' class='gomb' style='background-color:red;color:yellow;padding:0px 10px;'/>
</td>
</tr>
</table>
</form>
<?php
};
?>
</center>
</body>