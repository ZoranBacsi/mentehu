<?php 
if($_SESSION['bent']!==TRUE){
  echo("Ez az oldal azonosításhoz kötött, lépj be!");
  die;
};

if(($_SESSION['regio']!=='7R')){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};

if(isset($_POST['dupliktorlesgomb'])){
  $ts=$_POST['torlendoszemely'] ;
  ab_lek("DELETE FROM t_csoptagsagok WHERE szid=$ts");
  ab_lek("DELETE FROM t_regisztraciok WHERE ssorsz=$ts");
  ab_lek("DELETE FROM t_szemelyek WHERE sorsz=$ts");
  ab_lek("INSERT INTO t_szerknaplo VALUES ('".$_SESSION['regio']."', $ts, 'Teljes törlés', '".date("Y-m-d H:m:s")."')");
};

$e=ab_lek("SELECT * FROM t_szemelyek WHERE megj LIKE '%KITÖRÖLNI%'");
?>
<form method="post">
Megjelöltek közül törlés:<br />
<select name="torlendoszemely">
<?php
while($sor=mysql_fetch_assoc($e)){
  echo("<option value='".$sor['sorsz']."'>(".$sor['sorsz'].") ".$sor['nev']." - ".$sor['szev']." - ".$sor['laktelep']."</option>");
};
?>
</select><br />
<input type="submit" name="dupliktorlesgomb" value="Kijelölt törlésre megjelölt személy (és minden csoporttagságának/regisztrációjának) törlése">
</form>

