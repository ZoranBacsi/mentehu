<?php

function ab_lek($sql){
  $link = mysql_connect('localhost', 'menteh01_veradmi', 'Sz3ntImr3');
  if(!$link){
    die("<hr>Nem sikerült csatlakozni:" . mysql_error()."<hr>");
  };
  mysql_select_db("menteh01_vera");
  $e=mysql_query($sql);
  return $e;
};

function ab_bont(){
  mysql_close;
};

function hibauz($szov){
  echo("<font color='red'><b><i>$szov</i></b></font>");
};

$napok[1]='hétfő';
$napok[2]='kedd';
$napok[3]='szerda';
$napok[4]='csütörtök';
$napok[5]='péntek';
$napok[6]='szombat';
$napok[7]='vasárnap';


$honapok[1]='január';
$honapok[2]='február';
$honapok[3]='március';
$honapok[4]='április';
$honapok[5]='május';
$honapok[6]='június';
$honapok[7]='július';
$honapok[8]='augusztus';
$honapok[9]='szeptember';
$honapok[10]='október';
$honapok[11]='november';
$honapok[12]='december';

$regiokod['ve_ifiiroda']='7R';
$regiokod['ve_delduna']='DD';
$regiokod['ve_eszakduna']='ED';
$regiokod['ve_galga']='GA';
$regiokod['ve_ipoly_kateketa']='IP';
$regiokod['ve_tapio']='TA';
$regiokod['ve_tisza']='TI';
$regiokod['ve_zagyva']='ZA';
$regiokod['dd_go_kateketa']='DD';
$regiokod['dd_ug_kateketa']='DD';
$regiokod['ed_kateketa']='ED';
$regiokod['ga_kateketa']='GA';
$regiokod['ip_kt_kateketa']='IP';
$regiokod['ip_ma_kateketa']='IP';
$regiokod['ta_kateketa']='TA';
$regiokod['ti_kateketa']='TI';
$regiokod['za_kateketa']='ZA';

$regiokodnevek['DD']='delduna';
$regiokodnevek['ED']='eszakduna';
$regiokodnevek['GA']='galga';
$regiokodnevek['IP']='ipoly';
$regiokodnevek['TA']='tapio';
$regiokodnevek['TI']='tisza';
$regiokodnevek['ZA']='zagyva';

$regionevek['DD']='Dél-Duna';
$regionevek['ED']='Észak-Duna';
$regionevek['GA']='Galga';
$regionevek['IP']='Ipoly';
$regionevek['TA']='Tápió';
$regionevek['TI']='Tisza';
$regionevek['ZA']='Zagyva';
$regionevek['7R']='Egyházmegye';

//$eszolg[]='gmail.com';
//$eszolg[]='freemail.hu';
//$eszolg[]='citromail.hu';
//$eszolg[]='hotmail.com';
//$eszolg[]='vaciegyhazmegye.hu';


$regioszin['DD']='#FFFF45';
$regioszin['ED']='#BCE7A1';
$regioszin['GA']='#FFCE9D';
$regioszin['IP']='#FFAAFF';
$regioszin['TA']='#B1CCFF';
$regioszin['TI']='#FF9D9D';
$regioszin['ZA']='#FFBAFF';
$regioszin['7R']='#CCFFFF';


/*
$jogkorszintek['sz1']='1.szint';
$jogkorszintek['sz2']='2.szint';
$jogkorszintek['sz3']='3.szint';
$jogkorszintek['sz4']='4.szint';
*/

$kb[1]='A';
$kb[2]='Á';
$kb[3]='B';
$kb[4]='C';
$kb[5]='D';
$kb[6]='E';
$kb[7]='É';
$kb[8]='F';
$kb[9]='G';
$kb[10]='H';
$kb[11]='I';
$kb[12]='Í';
$kb[13]='J';
$kb[14]='K';
$kb[15]='L';
$kb[16]='M';
$kb[17]='N';
$kb[18]='O';
$kb[19]='Ó';
$kb[20]='Ö';
$kb[21]='Ő';
$kb[22]='P';
$kb[23]='Q';
$kb[24]='R';
$kb[25]='S';
$kb[26]='T';
$kb[27]='U';
$kb[28]='Ú';
$kb[29]='Ü';
$kb[30]='Ű';
$kb[31]='V';
$kb[32]='W';
$kb[33]='X';
$kb[34]='Y';
$kb[35]='Z';

$HTML_mail_fej="
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
<title>Váci Egyházmegye Ifjúsági Iroda Körlevele</title>
</head>
<body style='background-color:white; margin:0px; padding:10px;'>
";

$HTML_mail_lab="
<hr style='color:gray;' />
<p><a href='http://mente.hu'>Váci Egyházmegye Ifjúsági Oldala</a></p>
<p style='font-style:italic;color:gray;'>Ez egy teszt fázisban lévő körlevélküldőrendszer... Ha észrevételeddel segíteni akarod munkám, írj a peter@godony.hu címre... Jézus szeret!</p>
</body>
</html>
"; 

$HTML_headers = "From: Váci Egyházmegyei Ifjúsági Iroda <ifiroda@vaciegyhazmegye.hu> \r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8";

function nl2p($inString)
{
  $resz1="<p>".preg_replace("%\n%", "</p>\n<p>", $inString)."</p>";
  $resz2=ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" rel=\"nofollow\">\\0</a>", $resz1);
  return $resz2;
}
?>