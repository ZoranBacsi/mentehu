<?php 
if($_SESSION['bent']!==TRUE){
  echo("Ez az oldal azonosításhoz kötött, lépj be!");
  die;
};

if(isset($_POST['szemadatlapmentesgomb'])){
  $email1=$_POST['email1'];
  $email2=$_POST['email2'];  
  $nev=$_POST['nev'];
  if(($_POST['mobilkorzet']=='nincs') || ($_POST['mobil']=='')){
    $mobil='';
  }else{
    $mobil=$_POST['mobilkorzet'].$_POST['mobil'];
  }
  if((isset($_POST['kf'])) and ($_POST['kf']=='on')){
    $flottas='i';
  }else{
    $flottas='n';
  };
  if((isset($_POST['e1tilt'])) and ($_POST['e1tilt']=='on')){
    $e1tilt='i';
  }else{
    $e1tilt='n';
  };  
  if((isset($_POST['e2tilt'])) and ($_POST['e2tilt']=='on')){
    $e2tilt='i';
  }else{
    $e2tilt='n';
  };
  $szulev=$_POST['szev'];
  $lakirsz=$_POST['irsz'];
  $laktelep=$_POST['telep'];
  $lakcim=$_POST['cim'];
  $plebania=$_POST['etelep'];
  $ujplebania=$_POST['etelepuj'];
  if($ujplebania!=""){
    $plebania=$ujplebania;
  };
  $megj=$_POST['megj'];
  $nem=$_POST['nem'];
  if((isset($_POST['szemsorsz']) and ($_POST['szemsorsz']>0))){ //mentés
    $szem=$_POST['szemsorsz'];
    $e=ab_lek("UPDATE t_szemelyek SET email='$email1', email2='$email2', nev='$nev', mobil='$mobil', kf='$flottas', szev=$szulev, lakirsz='$lakirsz', laktelep='$laktelep', lakcim='$lakcim', pleb='$plebania', utmod=NULL, megj='$megj', nem='$nem', e1tilt='$e1tilt', e2tilt='$e2tilt' WHERE sorsz=$szem");
    $e=ab_lek("INSERT INTO t_szerknaplo VALUES ('".$_SESSION['regio']."', $szem, 'Mentés', '".date("Y-m-d H:m:s")."')");
  }else{ //létrehozás
    $e=ab_lek("INSERT INTO t_szemelyek VALUES (NULL, '$email1', '$nev', '$mobil', '$flottas', $szulev, '$lakirsz', '$laktelep', '$lakcim', '$plebania', NULL, '$megj','$nem','$email2','$e1tilt','$e2tilt' )");
    $e=ab_lek("SELECT sorsz FROM t_szemelyek WHERE email='$email1' and nev='$nev' and szev=$szulev and laktelep='$laktelep'");
    $sor=mysql_fetch_assoc($e);
    $szem=$sor['sorsz'];
    $e=ab_lek("INSERT INTO t_szerknaplo VALUES ('".$_SESSION['regio']."', $szem, 'Rögzítés', '".date("Y-m-d H:m:s")."')");
    $_GET['szemsorsz']=$szem;
    $kcs=$_POST['kezdocsop'];
    //echo("<h1>## $kcs ##</h1>");
    if($kcs !='nincs'){
      ab_lek("INSERT INTO t_csoptagsagok VALUES ($szem, $kcs)");
    };
    if(isset($_GET['esem'])){
      $e=ab_lek("INSERT INTO t_regisztraciok VALUES (".$_GET['esem'].", $szem)");
      $e=ab_lek("INSERT INTO t_szerknaplo VALUES ('".$_SESSION['regio']."', ".$_POST['szemsorsz'].", 'Regisztráció-".$_GET['esem']."', '".date("Y-m-d H:m:s")."')");
    };
  };
  if((isset($_POST['ablakbezaras'])) or (isset($_GET['esem']))){
  ?>
  <script type="text/javascript">
  window.close();
  </script>
  <?php
  };
};

if(isset($_POST['csoportbasorolasgomb'])){
  ab_lek("INSERT INTO t_csoptagsagok VALUES (".$_POST['szemsorsz'].", ".$_POST['csoportbahova'].")");
  ab_lek("INSERT INTO t_szerknaplo VALUES ('".$_SESSION['regio']."', $szem, 'Csoportbasorolás (".$_POST['szemsorsz'].",".$_POST['csoportbahova'].")', '".date("Y-m-d H:m:s")."')");
};

if(isset($_POST['csoportboltorlesgomb'])){
  ab_lek("DELETE FROM t_csoptagsagok WHERE szid=".$_POST['szemsorsz']." AND csid=".$_POST['ebbolki']);
  ab_lek("INSERT INTO t_szerknaplo VALUES ('".$_SESSION['regio']."', $szem, 'Csoportbasorolás törlése (".$_POST['szemsorsz'].",".$_POST['ebbolki'].")', '".date("Y-m-d H:m:s")."')");
};


?>
<center>
<table border="0" width="900">
 <tr class="fejlec" style="vertical-align:top;">
  <td colspan="2" class='kozepre'>
   <span class='cim'> . .. .: :: 
    <font size='+1'><b>V</b></font>áci 
    <font size='+1'><b>E</b></font>gyházmegyei 
    <font size='+1'><b>R</b></font>egionális 
    <font size='+1'><b>A</b></font>datbázis  :: :. .. .
   </span>
   <a href="http://vera.mente.hu<?php echo($_SERVER['REQUEST_URI']); ?>"><input type="button" value="Nézet frissítés" style="float:right;border-color:orange;"></a>
  <?php
     echo("<p style='text-align:left;margin: 5px;'>Isten hozott:<b><i> ".$_SESSION['megszol']."</b> (");if($_SESSION['rf']==true){echo("régiófelelős hozzáférés");}else{echo("alkalmi rögzítői hozzáférés");};echo(")</i></p>");
   ?>
  </td>
 </tr>
 <tr>
  <td width="100" class="menu" id="fomenu">
   <?php
   if($_SESSION['rf']==TRUE){
   ?>
   <a href="index.php?muv=rogadmin"><button class="mpgomb">Hozzáférések</button></a><br />
   <?php
   if($_SESSION['regio']=='7R'){
   ?>
    <a href="index.php?muv=szerknaplo"><button class="mpgomb almpgomb">Napló</button></a><br />
   <?php
   };
   ?>    
   <a href="index.php?muv=szemkez"><button class="mpgomb">Személyek</button></a><br />
    <a href="http://vera.mente.hu/index.php?muv=szemszerk" target="_blank"><button class='mpgomb almpgomb' >Új rögzítése</button></a><br />
    <a href="http://vera.mente.hu/index.php?muv=szemimp" target="_blank"><button class='mpgomb almpgomb' >Importálás</button></a><br />
   <?php
   };
   
   if(($_SESSION['regio']=='7R') and ($_SESSION['rf']==true)){
   ?>
   <a href="index.php?muv=szemdel"><button class="mpgomb almpgomb">Törlendők</button></a><br />
   <a href="index.php?muv=csopkez"><button class="mpgomb">Csoportok</button></a><br />   
   <a href="index.php?muv=hirlevegyesevel"><button class="mpgomb">Hírlevélküldés</button></a><br />      
    <a href="index.php?muv=hirlevcimzettek"><button class="mpgomb almpgomb">Címzettek</button></a><br />
    <!-- <a href="index.php?muv=hirlevmellekletek"><button class="mpgomb almpgomb">Melléklet</button></a><br /> -->
   <a href="index.php?muv=hidozit"><button class="mpgomb">Hírlevél időzítés</button></a><br />      
    <a href="index.php?muv=hcimcsop"><button class="mpgomb almpgomb">Címzettcsoportok</button></a><br />
    <a href="index.php?muv=hsablon"><button class="mpgomb almpgomb">Üzenetsablonok</button></a><br />
    <a href="index.php?muv=hmellek"><button class="mpgomb almpgomb">Mellékletek</button></a><br />
   <?php
   };
   
   ?>
   <a href="index.php?muv=regel"><button class="mpgomb">Regisztráció</button></a><br />
   <?php
   if(($_SESSION['regio']=='7R') and ($_SESSION['rf']==true)){
   ?>
    <a href="index.php?muv=eskez"><button class="mpgomb almpgomb">Események</button></a><br />   
   <?php
   };

   $kisokos='alkalmi-kisokos.pdf';
   if(($_SESSION['regio']=='7R') and ($_SESSION['rf']==true)){
     $kisokos='ifiroda-kisokos-7g32od293278odz3dg2i7d361odl2v1d.pdf';
   };
   if(($_SESSION['regio']!='7R') and ($_SESSION['rf']==true)){
     $kisokos='regiofelelos-kisokos-ieubcwkc26be6wuve3k.pdf';
   };   
   ?>
    <a href="<?php echo($kisokos); ?>" target="_blank" ><button class="mpgomb">Kisokos</button></a><br />
   <?php 
   if(($_SESSION['regio']=='7R') and ($_SESSION['rf']==true)){   
   ?>      
    <a href="index.php?muv=kisokosok"><button class="mpgomb almpgomb">Csere-bere</button></a><br />
   <?php
   };
   
   ?>
   <a href="index.php?muv=kilepes"><button class="mpgomb">Kilépés</button></a>  
 </td>
 <td id="tartalom"> 
  <?php
  if(is_file('vera-'.$muv.'.inc.php')){
   include('vera-'.$muv.'.inc.php');
  }else{
    if($_SESSION['rf']==true){
      echo("Kérlek válassz a menünkből!!");
    }else{
      include('vera-regel.inc.php');
    };    
  }; 
  ?>

 </td>
 </tr>
</table>