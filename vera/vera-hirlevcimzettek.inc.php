<?php

if(($_SESSION['regio']!=='7R') or ($_SESSION['rf']!==true)){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};


if(isset($_POST['ezttoroldkigomb'])){
  ab_lek("DELETE FROM t_hirlevelcimzettek WHERE neve='".$_POST['neve']."' and mailja='".$_POST['mailja']."'");
};

if(isset($_POST['mindtoroldkigomb'])){
  ab_lek("TRUNCATE TABLE t_hirlevelcimzettek");
};

?>
<form method='post' style='float:right;' >
  <input type='submit' name='mindtoroldkigomb' value='Lista kiürítése!' style='background-color:red;' />
</form>
<?php
$e=ab_lek("SELECT * FROM t_hirlevelcimzettek");
$db=mysql_num_rows($e);
echo("Címzettek ($db db):");
?>
<br />
<ul class='szinesfelsorlas'>
<?php

while($sor=mysql_fetch_assoc($e)){
 echo("<form method='post' >
       <li>".$sor['neve']." &lt;".$sor['mailja']."&gt; 
       <input type='hidden' name='neve' value='".$sor['neve']."' />
       <input type='hidden' name='mailja' value='".$sor['mailja']."' />
       <input type='submit' name='ezttoroldkigomb' value='Ezt töröld!' />
       </li>
       </form>");
};
?>
</ul>