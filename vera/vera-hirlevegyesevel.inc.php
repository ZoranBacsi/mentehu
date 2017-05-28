<?php 
if($_SESSION['bent']!==TRUE){
  echo("Ez az oldal azonosításhoz kötött, lépj be!");
  die;
};

if(($_SESSION['regio']!=='7R') or ($_SESSION['rf']!==true)){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};

//mail('Gödöny Péter <peter@godony.hu>', 'Teszt űzenet tőlem...', $HTML_mail_fej.'<hr />Árvírtűrő tükörfúrógép<hr />'.$HTML_mail_lab, $HTML_headers);

if(isset($_POST['hirevelcimzetteknekgomb'])){
  ab_lek("TRUNCATE TABLE t_hirlevelcimzettek");
  
/*  $f=@fopen('szurolista_export_mail.txt', 'r');
  while(($fsor = fgets($f,4096)) !== false){
    $egycimzett=utf8_encode($fsor);
    ab_lek("INSERT INTO t_hirlevelcimzettek VALUES ('$egycimzett')");
  };
*/
  $szuro=$_POST['szuro'];
  //$sql="";
  $e=ab_lek("SELECT nev, email FROM t_szemelyek $szuro");
  while($sor=mysql_fetch_assoc($e)){
   if($sor['email']<>""){
     ab_lek("INSERT INTO t_hirlevelcimzettek  VALUES ('".$sor['nev']."','".$sor['email']."')");
   };
  };  
};

if(isset($_POST['stopgomb'])){
  $_SESSION['muk']=FALSE;
  $muk=FALSE;
};

if(!isset($_SESSION['muk'])){
  $_SESSION['muk']=FALSE;
  $i=0;
  $_SESSION['editor1']='';
};

$e=ab_lek("SELECT count(neve) AS db FROM t_hirlevelcimzettek");
$sor=mysql_fetch_assoc($e);
$i=$sor['db'];

 if(isset($_POST['kuldgomb'])){
   $_SESSION['muk']=TRUE;
   $_SESSION['editor1']=$_POST['editor1'];
   $_SESSION['targy']=htmlspecialchars(($_POST['targy']));
 };
 
 if($_SESSION['muk']==TRUE){
   if($i==0){
     $_SESSION['muk']=FALSE;
   }else{  
     $e=ab_lek("SELECT * FROM t_hirlevelcimzettek LIMIT 0 , 1");
     $sor=mysql_fetch_assoc($e);   
     $cimzett=$sor['neve']." &lt;".$sor['mailja']."&gt;";
     $cnev=$sor['neve'];
   };
 };
 ?>
	<script src="ckeditor/ckeditor.js"></script>
	<script src="ckeditor/samples/sample.js"></script>
	<link href="ckeditor/samples/sample.css" rel="stylesheet"> 
 
 <form id="korlevelform" method="post">
   Feladó: Váci Egyházmegyei Ifjúsági Iroda &lt;ifiroda@vaciegyhazmegye.hu&gt; <br />
   Tárgy: <input type='text' name='targy' value='<?php echo($_SESSION['targy']); ?>' style='width:300px;'/><br />
   Címzett: <input type='text' readonly='readonly' value='<?php echo($cimzett); ?>'  
             style='width:300px;' /> 
   <?php echo("még ".$i." címzett"); ?><br /> 
   Üzenet:<br />
   <h2>Kedves <?php echo($cnev); ?>!</h2>
   <textarea cols="80" rows="17" class="ckeditor" name="editor1"><?php echo($_SESSION['editor1']); ?></textarea><br />
   <p>Körlevélmezők: <span style='color:red;font-weight:bold;'>%C%</span> <=> Címzett neve</p>
   <input type='submit' value='Küldés...' name='kuldgomb' /> 
   <font color="red">...sablonmentés lesz a következő...</font>
 </form>
 <form method="post">
   <input type='submit' value='STOP' name='stopgomb' /> 
 </form>
 <?php

 if($_SESSION['muk']==TRUE){
  echo("Küldés $cimzett részére...");
  $uzenet=str_replace("%C%", $cnev, $_SESSION['editor1']);
  mail(html_entity_decode($cimzett), $_SESSION['targy'], $HTML_mail_fej."<h3 style='text-align:right;'>Váci Egyházmegyei Ifjúsági Körlevél <br />".date("Y-m-d")."</h3>".$uzenet.$HTML_mail_lab, $HTML_headers);
  $e=ab_lek("DELETE FROM t_hirlevelcimzettek WHERE mailja='".$sor['mailja']."'");
  echo('<script type="text/javascript">
        var myTimer = setTimeout("document.getElementById(\'korlevelform\').submit()",3600);
        </script>');
 }; 
 ?>
