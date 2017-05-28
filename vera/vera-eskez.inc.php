<?php 
if($_SESSION['bent']!==TRUE){
  echo("Ez az oldal azonosításhoz kötött, lépj be!");
  die;
};

if(($_SESSION['regio']!=='7R') or ($_SESSION['rf']!==true)){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};

if(isset($_POST['ujesadatmentgomb'])){
  if(strlen($_POST['eDatumB'])>7){
    $intervallum="'".$_POST['eDatumK']."', '".$_POST['eDatumB']."'";
  }else{
    $intervallum="'".$_POST['eDatumK']."', NULL";  
  };
  ab_lek("INSERT INTO t_esemenyek VALUES (NULL, '".$_POST['rKod']."', '".$_POST['eNev']."', $intervallum, '".$_POST['eCimIrsz']."', '".$_POST['eTelep']."', '".$_POST['eCim']."')");
};

if(isset($_POST['esadatmentgomb'])){
  if(strlen($_POST['eDatumB'])>7){
    $intervallum="'".$_POST['eDatumB']."'";
  }else{
    $intervallum="NULL";  
  };
  ab_lek("UPDATE t_esemenyek SET rKod='".$_POST['rKod']."', eNev='".$_POST['eNev']."', eDatumK='".$_POST['eDatumK']."', eDatumB=$intervallum, eCimIrsz='".$_POST['eCimIrsz']."', eCim='".$_POST['eCim']."', eTelep='".$_POST['eTelep']."' WHERE eSorsz=".$_POST['eSorsz']);
};

?>

<table>
 <thead>
  <tr class="fejlec">
   <td class='kozepre'>Sorszám</td>
   <td class='kozepre'>Régió</td>
   <td class='kozepre'>Esemény<sup>*25</sup></td>
   <td class='kozepre'>Időpont</td>
   <td class='kozepre'>Helyszín</td>
   <td class='kozepre'>Művelet</td>
  </tr> 
 </thead>
 <tbody>
  <form method="post"><tr style="background-color:white;">
          <td>köv.</td>
          <td class="kozepre">
          <?php
            if(substr($_SESSION['rogzito'],-8)=='kateketa'){
          ?>
          <input type="hidden" class="rejtett" value="<?php echo($_SESSION['regio']); ?>">
          <?php
          echo($regionevek[$_SESSION['regio']]);
           }else{
          ?>
          <select name="rKod" class="rejtett">
           <?php
            foreach ($regionevek as $key=>$value) {
            	echo("<option value='$key'>$value</option>");
            };
           ?>
          </select>            
          <?php 
           }; 
          ?>
          </td>
          <td class='kozepre'><input type='text' class='rejtett' name='eNev' /></td>
          <td class='kozepre'><input type='text' class='rejtett' name='eDatumK' />
          <span id='napos2uj' style='visibility:hidden;'>-tól <input type='text' class='rejtett' style='margin:0px;padding:0px;' name='eDatumB' />-ig</span>
          <input type='checkbox' onClick='if(document.getElementById("napos2uj").style.visibility=="hidden")
                                               {document.getElementById("napos2uj").style.visibility="visible";}
                                             else
                                               {document.getElementById("napos2uj").style.visibility="hidden";};' style='margin:0px;padding:0px;' />Több...</td>
          <td class='kozepre'><input type='text' class='rejtett' name='eCimIrsz'  style='width:33px' />, 
          <input type='text' class='rejtett' name='eTelep' style='width:110px' /> 
          <br /><input type='text' class='rejtett' name='eCim' style='width:153px' /></td>
          <td class='kozepre'><input type='submit' value='Mentés' name='ujesadatmentgomb' /></td>
        </tr>
      </form> 
 <?php
  if(substr($_SESSION['rogzito'],-8)=='kateketa'){
    $szuro=" WHERE rKod='".$_SESSION['regio']."'";
  }else{
    $szuro="";
  }; 
  $e=ab_lek("SELECT * FROM t_esemenyek $szuro ORDER BY eDatumK DESC");
  $diksor=0;
  while($sor=mysql_fetch_assoc($e)){
    $diksor++;
    if(fmod($diksor,2)==0){$sty='style="background-color:#FF9900;vertical-align:center;"';}else{$sty='style="vertical-align:center;"';};
    echo("<form method='post' action='".$_SERVER['PHP-SELF']."'><tr $sty>
          <td>$diksor<input type='hidden' name='eSorsz' value='".$sor['eSorsz']."' /></td>
          <td class='kozepre'><select name='rKod' class='rejtett'");
    if(substr($_SESSION['rogzito'],-8)=='kateketa'){
    echo(" disabled ");
    };         
    echo(">");
    foreach ($regionevek as $key=>$value) {
     	if($key==$sor['rKod']){
       echo("<option value='$key' selected='selected'>$value</option>");
      }else{
       echo("<option value='$key'>$value</option>");
      }; 
    };                    
    echo("</select></td>
          <td class='kozepre'><input type='text' class='rejtett' name='eNev' value='".$sor['eNev']."'  style='width:90px' /></td>
          <td class='kozepre'><input type='text' class='rejtett' name='eDatumK' value='".$sor['eDatumK']."' />
          <span id='napos".$sor['eSorsz']."' ");
    if($sor['eDatumB']==NULL){
      echo("style='visibility:hidden;'");
      $bekapcsolte='';
    }else{
      $bekapcsolte=' checked=\'checked\'';
    };
    echo(">-tól <input type='text' class='rejtett' style='margin:0px;padding:0px;' name='eDatumB' value='".$sor['eDatumB']."' />-ig</span>
          <input type='checkbox' onClick='if(document.getElementById(\"napos".$sor['eSorsz']."\").style.visibility==\"hidden\")
                                               {document.getElementById(\"napos".$sor['eSorsz']."\").style.visibility=\"visible\";}
                                             else
                                               {document.getElementById(\"napos".$sor['eSorsz']."\").style.visibility=\"hidden\";};' 
                                            style='margin:0px;padding:0px;' $bekapcsolte />Több...</td>
          <td class='kozepre'><input type='text' class='rejtett' name='eCimIrsz' value='".$sor['eCimIrsz']."' style='width:33px' />, 
          <input type='text' class='rejtett' name='eTelep' value='".$sor['eTelep']."' style='width:110px' /> 
          <input type='text' class='rejtett' name='eCim' value='".$sor['eCim']."' style='width:153px' /></td>
          <td class='kozepre'><input type='submit' value='Mentés' name='esadatmentgomb' /></td></tr></form>");
  };  
 ?>  
 </tbody> 
</table>