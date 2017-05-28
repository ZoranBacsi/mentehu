<?php 
if($_SESSION['bent']!==TRUE){
  echo("Ez az oldal azonosításhoz kötött, lépj be!");
  die;
};

if(($_SESSION['regio']!=='7R') or ($_SESSION['rf']!==true)){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};

if(isset($_POST['csmentesgomb'])){
  $csopnev=$_POST['csnev'];
  $most=date("Y-m-d H:i:s");
  ab_lek("INSERT INTO t_ecimcsop VALUES (NULL, '$csopnev', '$most')");
  echo("'$csopnev' csoport sikeresen mentve ($most)");
  $e=ab_lek("SELECT csid FROM t_ecimcsop WHERE csnev='$csopnev' and csdatum='$most'");
  $sor=mysql_fetch_assoc($e);
  $csop=$sor['csid'];
  $csopszuro=$_POST['csszuro'];
  $e=ab_lek("SELECT nev, email FROM t_szemelyek $csopszuro ORDER BY email");
  while($sor=mysql_fetch_assoc($e)){
    ab_lek("INSERT INTO t_ecimzettek VALUES ($csop, '".$sor['nev']."', '".$sor['email']."', false)");
  };
};

if(isset($_POST['cskilistazgomb'])){
$e=ab_lek("SELECT csnev FROM t_ecimcsop WHERE csid=".$_POST['csid']);
$sor=mysql_fetch_assoc($e);
echo("<h2>".$sor['csnev']."</h2>");
?>
<table style="width:97%;margin: auto;">
  <tr class='fejlec'>
    <td>címzett neve</td>
    <td>címzett email címe</td>
    <td>kiküldve?</td>
  </tr>
  <?php
  $e=ab_lek("SELECT * FROM t_ecimzettek WHERE csop=".$_POST['csid']." ORDER BY email ASC");
  while ($sor=mysql_fetch_assoc($e)) {
    echo("<tr><td>".$sor['nev']."</td><td>".$sor['email']."</td><td>");
    if($sor['kiment']==true){
      echo("igen");
    }else{
      echo("nem");
    };
    echo("</td></tr>");
  }
  ?>
</table>
<?php

}else{
?>
<table style="width:97%;margin: auto;">
  <tr class='fejlec'>
    <td>csoport neve</td>
    <td>csoport tagság</td>
    <td>csoport létrehozás</td>
  </tr>
  <?php
  $e=ab_lek("SELECT * FROM t_ecimcsop ORDER BY csid DESC");
  while ($sor=mysql_fetch_assoc($e)) {
    echo("<tr><td><form method='post'><input type='text' name='csnev' value='".$sor['csnev']."' style='width:256px;' /> <input type='hidden' name='csid' value='".$sor['csid']."' /> <input type='submit' class='almpgomb' name='csatnevezgomb' value='átnevez' /></form></td><td class='kozepre'>");
    $e2=ab_lek("SELECT count(*) AS db FROM t_ecimzettek WHERE csop=".$sor['csid']);
    $sor2=mysql_fetch_assoc($e2);    
    echo("<form method='post'>".$sor2['db']." <input type='hidden' name='csid' value='".$sor['csid']."' /> <input type='submit' class='almpgomb' name='cskilistazgomb' value='...' /></form></td><td>".$sor['csdatum']."</td></tr>");
  }
  ?>
</table>
<?php
};
?>