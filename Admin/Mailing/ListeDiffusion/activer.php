<?php
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");

if ($Cnx_Admin!=TRUE) {
  header('location:'.HOME.'/Admin');
}

$Erreur=$_GET['erreur'];
$Id=$_GET['id'];
$Groupe=urldecode($_GET['groupe']);

if ((!empty($_GET['id']))&&(isset($_POST['oui']))) {

    $Select=$cnx->prepare("SELECT * FROM ".DB_PREFIX."mailing_Liste_Diffusion WHERE id=:id");
    $Select->bindParam(':id', $Id, PDO::PARAM_INT);
    $Select->execute();
    $Diffusion=$Select->fetch(PDO::FETCH_OBJ);

    $Update=$cnx->prepare("UPDATE ".DB_PREFIX."mailing_Liste_Diffusion SET diffusion=1 WHERE id=:id");
    $Update->bindParam(':id', $Id, PDO::PARAM_INT);
    $Update->execute();

    header('Location:'.HOME.'/Admin/Mailing/ListeDiffusion/?groupe='.$Groupe);
    
}

if (isset($_POST['non'])) {  
    header('Location:'.HOME.'/Admin/Mailing/ListeDiffusion/?groupe='.$Groupe);
}
?>  


<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article>
<?php if (isset($Erreur)) { echo "<font color='#FF0000'>".urldecode($Erreur)."</font><BR />"; }
if (isset($Valid)) { echo "<font color='#009900'>".urldecode($Valid)."</font><BR />"; } ?>

Etes-vous sur de vouloir activer cette email ? <BR /><BR />

<table class="Admin" width="300">
<form action="" method="POST">
<TR><TD align="center"><input name="oui" type="submit" value="OUI"></TD><TD align="center"><input name="non" type="submit" value="NON"/></TD></TR>
</form></TABLE>

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>