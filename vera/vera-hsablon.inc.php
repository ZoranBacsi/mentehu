<?php 
if($_SESSION['bent']!==TRUE){
  echo("Ez az oldal azonosításhoz kötött, lépj be!");
  die;
};

if(($_SESSION['regio']!=='7R') or ($_SESSION['rf']!==true)){
  echo("A hozzáféréseddel elérhető oldalkat az oldalsó menüből választhatod ki!");
  die;
};
?>

<script src="ckeditor/ckeditor.js"></script>
<script src="ckeditor/samples/sample.js"></script>
<!-- <link href="ckeditor/samples/sample.css" rel="stylesheet"> --> 
 
<form id="korlevelform" method="post">
 <textarea  class="ckeditor" name="editor1">
 <?php echo($_SESSION['editor1']); ?>
 </textarea><br />
</form>
