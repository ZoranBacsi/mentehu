<?php
if($_SESSION['bent']!==TRUE){
echo("Ez az oldal azonosításhoz kötött, lépj be!");
die;
};

if($_SESSION['regio']!=='7R'){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};

if (isset($_GET['oldal'])) {
  $old=$_GET['oldal'];
}else{
  $old=1;
};

$e=ab_lek("SELECT count(mikor) AS 'db' FROM t_szerknaplo");
$sor=mysql_fetch_assoc($e);
$ossz=$sor['db'];

$elso=100*($old-1);
$utolso=min((100*$old)-1,$ossz);
$utold=ceil($ossz/100);

if($old>1){
 $eloold=$old-1;
 $elogomb="<a href='index.php?muv=szerknaplo&oldal=$eloold' style='text-decoration:none;'>
             <button>Előző<br />oldal</button>
           </a>";
};
if($old<$utold){
 $kovold=$old+1;
 $kovgomb="<a href='index.php?muv=szerknaplo&oldal=$kovold' style='text-decoration:none;'>
             <button>Következő<br />oldal</button>
           </a>";
}else{
 $kovgomb="";
};

echo("<p style='float:right;text-align:right;'>
      Összesen:<br /> 
      $ossz db bejegyzés<br /> $utold oldalon
      <br /><br />
      Most látszik:<br />
      $old. oldal <br />
      ($elso...$utolso)
      <br /><br />
      $elogomb <br /> $kovgomb</p>");
?>
<table>
 <thead>
  <tr class="fejlec">
   <td>Melyik szerkesztő</td>
   <td>Mikor</td>
   <td>Melyik személlyel...</td>
   <td>...mit csinált</td>
  </tr>
 </thead>
 <tbody>
   <?php
    $e=ab_lek("SELECT * FROM t_szerknaplo ORDER BY mikor DESC LIMIT $elso , 50");
    $diksor=0;
    while($sor=mysql_fetch_assoc($e)){
      $diksor++;
      if(fmod($diksor,2)==0){$sty='style="background-color:#FF9900;vertical-align:center;"';}else{$sty='style="vertical-align:center;"';};
      $e2=ab_lek("SELECT nev FROM t_szemelyek WHERE sorsz=".$sor['szem']);
      $sor2=mysql_fetch_assoc($e2);
      if(substr($sor['muv'],0,8)=='Regisztr'){
        $kp=strpos($sor['muv'],'-');
        $ms=substr($sor['muv'],$kp+1);
        $e3=ab_lek("SELECT * FROM t_esemenyek WHERE eSorsz=$ms");
        $sor3=mysql_fetch_assoc($e3);
        $muvelet=substr($sor['muv'],0,$kp)." (".$sor3['rKod'].") ".$sor3['eNev']." esemény";
      }else{
        $muvelet=$sor['muv'];
      };
      echo("<tr $sty>
            <td>".$regionevek[$sor['szerk']]."</td>
            <td>".$sor['mikor']."</td>
            <td>".$sor2['nev']."(".$sor['szem'].")</td>
            <td>$muvelet</td>
          </tr>");
    };      
   ?>
 </tbody>
</table>