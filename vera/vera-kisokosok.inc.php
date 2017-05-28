<?php 
if($_SESSION['bent']!==TRUE){
  echo("Ez az oldal azonosításhoz kötött, lépj be!");
  die;
};

if(($_SESSION['regio']!=='7R') or ($_SESSION['rf']!==true)){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};

if(isset($_POST['alkalmikisokofelgomb'])){
  move_uploaded_file($_FILES['alklamikisokosfile']['tmp_name'], 'alkalmi-kisokos.pdf');
};

if(isset($_POST['regiofeleloskisokofelgomb'])){
  move_uploaded_file($_FILES['regiofeleloskisokosfile']['tmp_name'], 'regiofelelos-kisokos-ieubcwkc26be6wuve3k.pdf');
};

if(isset($_POST['ifirodakisokofelgomb'])){
  move_uploaded_file($_FILES['ifirodakisokosfile']['tmp_name'], 'ifiroda-kisokos-7g32od293278odz3dg2i7d361odl2v1d.pdf');
};

?>

<form enctype="multipart/form-data" method="post">
<a href='alkalmi-kisokos.pdf' target='_blank' >Alkalmi-Kisokos</a>
, csere: <input type='file' name='alklamikisokosfile' />
<input type='submit' name='alkalmikisokofelgomb' value='Feltöltés' />
</form>

<hr />

<form enctype="multipart/form-data" method="post">
<a href='regiofelelos-kisokos-ieubcwkc26be6wuve3k.pdf' target='_blank' >Régiófelelős-Kisokos</a>
, csere: <input type='file' name='regiofeleloskisokosfile' />
<input type='submit' name='regiofeleloskisokofelgomb' value='Feltöltés' />
</form>

<hr />

<form enctype="multipart/form-data" method="post">
<a href='ifiroda-kisokos-7g32od293278odz3dg2i7d361odl2v1d.pdf' target='_blank' >Ifiroda-Kisokos</a>
, csere: <input type='file' name='ifirodakisokosfile' />
<input type='submit' name='ifirodakisokofelgomb' value='Feltöltés' />
</form>