<?php 
if($_SESSION['bent']!==TRUE){
  echo("Ez az oldal azonosításhoz kötött, lépj be!");
  die;
};

if($_SESSION['rf']!==true){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};

//if(isset($_POST['rogadatmentgomb'])){
//  ab_lek("UPDATE t_rogzitok SET rognev='".$_POST['rognev']."', rogjszo='".$_POST['rogjszo']."', ervtol='".$_POST['ervtol']."', ervig='".$_POST['ervig']."' WHERE rogzito='".$_POST['rogzito']."'");
//};

//if(isset($_POST['ujrogmentgomb'])){
//  $rognev=$_POST['rognev'];
//  $regio=$_POST['regio'];
//  $rogjszo=$_POST['rogjszo'];
//  ab_lek("INSERT INTO t_rogzitok VALUES (NULL, '$rognev', '$regio', '$rogjszo', 0)");
//};

if(isset($_POST['rfjszomentgomb'])){
  $rognev=$_POST['rognev'];
  $rfjszo=$_POST['rfjszo'];
  if($rfjszo=='*'){
    $rfjszo=strtoupper(substr(md5(date("Y-m-d H:s:m")),0,9));
  };
  $sorsz=$_POST['sorsz'];
  $rferv=$_POST['rferv'];
  if($rferv=='*'){
    $rferv = date("Y-m-d", strtotime("+1 day"));
  }
  if($sorsz==1){
   $rferv = '2077-12-24';
  }
  ab_lek("UPDATE t_rogzitok SET rfjszo='$rfjszo', rferv='$rferv' WHERE sorsz=$sorsz");
};


if(isset($_POST['rogjszomentgomb'])){
  $rognev=$_POST['rognev'];
  $rogjszo=$_POST['rogjszo'];
  if($rogjszo=='*'){
    $rogjszo=strtoupper(substr(md5(date("Y-m-d H:s:m")),0,9));
  };
  $sorsz=$_POST['sorsz'];
  $rogerv=$_POST['rogerv'];
  if($rogerv=='*'){
    $rogerv = date("Y-m-d", strtotime("+1 day"));
  }
  ab_lek("UPDATE t_rogzitok SET rogjszo='$rogjszo', rogerv='$rogerv' WHERE sorsz=$sorsz");
};

if(isset($_POST['rogrfjszomentgomb'])){
  $rognev=$_POST['rognev'];
  $rogjszo=strtoupper(substr(md5(date("Y-m-d H:s:m")),0,9));
  $sorsz=$_POST['sorsz'];
  $rogerv = date("Y-m-d", strtotime("+3 day"));
  ab_lek("UPDATE t_rogzitok SET rogjszo='$rogjszo', rogerv='$rogerv' WHERE sorsz=$sorsz");
};

if(isset($_POST['delrogemailgomb'])){
  ab_lek("DELETE FROM t_rogemails WHERE sorsz=".$_POST['delrogemailsorsz']); 
};

if(isset($_POST['ujrogemailgomb'])){
  ab_lek("INSERT INTO t_rogemails VALUES (NULL, ".$_POST['ujrogregio'].", '".$_POST['ujrogemail']."')");
};

if($_SESSION['regio']=='7R'){

};

if($_SESSION['regio']=='7R'){
  $e=ab_lek("SELECT * FROM t_rogzitok");
}else{
  if($_SESSION['rf']==true){
    $e=ab_lek("SELECT * FROM t_rogzitok WHERE regio='".$_SESSION['regio']."'");    
  };
}

?>
<table class="sorki" style="width:97%;margin: auto;">
  <tr class="fejlec">
    <?php if($_SESSION['regio']=='7R'){echo("<td>Sorzsám</td>");}; ?>
    <td>Rögzítő neve</td>

    <td>Régiófelelős jelszava</td>
    <td>Alkalmi rögzítő jelszava</td>    
    <td>Emailcímek</td>
  </tr>
  <?php  
  while ($sor=mysql_fetch_assoc($e)) {
  	echo("<tr");
  	if($sor['sorsz']==1){
  	  echo(" style='background-color:orange;'");
  	};
  	echo(">");
    if($_SESSION['regio']=='7R'){
        echo("           
            <td style='text-align:center;border-bottom:1px dotted black;'>
              ".$sor['sorsz']."
            </td>"); 
    };           
    echo("  <td style='border-bottom:1px dotted black;'>
              ".$sor['rognev']."
            </td>
            <td style='text-align:center;border-bottom:1px dotted black;'>");
    if($_SESSION['regio']=='7R'){
    echo(   " <form method='post' style='border: 1px solid orange;'>
              <input type='hidden' name='sorsz' value='".$sor['sorsz']."' />
              <input type='text' name='rfjszo' style='width:90px;padding:1px;' value='".$sor['rfjszo']."' />
              <input type='submit' name='rfjszomentgomb' value='Ment' />
              <br>Érv: <input type='date' name='rferv' style='width:90px;padding:1px;' value='".$sor['rferv']."' />
              </form>");
    }else{
    echo("<input type='text' style='width:90px;padding:1px;' value='".$sor['rfjszo']."' readonly='readonly'><br/>
          Érv: ".$sor['rferv']);
    };              
        
    echo("</td>");
    if($_SESSION['regio']=='7R'){
    echo("  <td style='text-align:center;border-bottom:1px dotted black;'>
              <form method='post' style='border: 1px solid orange;'>
              <input type='hidden' name='sorsz' value='".$sor['sorsz']."' />
              <input type='text' name='rogjszo' style='width:90px;padding:1px;' value='".$sor['rogjszo']."' />
              <input type='submit' name='rogjszomentgomb' value='Ment' />
              <br>Érv: <input type='date' name='rogerv' style='width:90px;padding:1px;' value='".$sor['rogerv']."' />
              </form>");
    }else{
    echo("  <td style='text-align:center;border-bottom:1px dotted black;'>
              <input type='text' readonly='readonly' style='width:90px;padding:1px;' value='".$sor['rogjszo']."' />
              <form method='post' style='float:right;' >
              <input type='hidden' name='sorsz' value='".$sor['sorsz']."' />              
              <input type='submit' name='rogrfjszomentgomb' value='Megújít' />
              </form>
              <br>Érv: ".$sor['rogerv']."");
    };         
    echo("  </td>
             ");
        
    echo("  <td>");
    $e2=ab_lek("SELECT * FROM t_rogemails WHERE rog=".$sor['sorsz']);  
    while($sor2=mysql_fetch_assoc($e2)){
      if($_SESSION['regio']=='7R'){
      echo("<form method='post' style='border: 1px solid orange;'>
           ".$sor2['email']."
           <input type='hidden' name='delrogemailsorsz' value='".$sor2['sorsz']."'>
           <input type='submit' name='delrogemailgomb' value='Töröl'>           
           </form>
           <br>");
      }else{
      echo($sor2['email']."<br>");
      };
    };   
    echo(" </td>           
          </tr>");
  };
  ?>  

</table>
<p>Tipp: automata jelszógeneráláshoz írj a jelszó helyére egy * karaktert, és ments... ;-)</p>
<?php
if($_SESSION['regio']=='7R'){
  echo("<p>Tipp: automatikusan a holnap éjfélig érvényességi időhöz írj a dátum helyére egy * karaktert, és ments... 8-)</p>");
  echo("<form method='post'>
       Régió: <select name='ujrogregio'>");
  $e=ab_lek("SELECT * FROM t_rogzitok WHERE sorsz>1");
  while($sor=mysql_fetch_assoc($e)){
    echo("<option value='".$sor['sorsz']."'>".$sor['rognev']." (".$sor['sorsz'].")</option>");
  };     
  echo("</select><br>
       Email: <input type='email' name='ujrogemail'>
       <input type='submit' name='ujrogemailgomb' value='Rögzít'>
     </form>");
}else{
  echo("<p>Fontos: Megújít gombra kattintva az új jelszó generálásával automatikusan 3 napos érvényességet állítunk be!</p>");
};

?>
