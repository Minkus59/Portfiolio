<?php
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");

if ($Cnx_Admin===false) {
  header('location:'.HOME.'/Admin');
}

$Erreur=$_GET['erreur'];
$Id=$_GET['id'];

if ((!empty($_GET['id']))&&(isset($_POST['oui']))) {

    $Select=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Document WHERE id=:id");
    $Select->bindParam(':id', $Id, PDO::PARAM_INT);
    $Select->execute();
    $Lien=$Select->fetch(PDO::FETCH_OBJ);

    if ($Lien->type=="PDF") {
        unlink($_SERVER['DOCUMENT_ROOT']."/lib/Document/".basename($Lien->lien));
    }
    if ($Lien->type=="Image") {
        unlink($_SERVER['DOCUMENT_ROOT']."/lib/Photo/".basename($Lien->lien));
    }
    if ($Lien->type=="Video") {
        unlink($_SERVER['DOCUMENT_ROOT']."/lib/Video/".basename($Lien->lien));
    }
    if ($Lien->type=="PPS") {
        unlink($_SERVER['DOCUMENT_ROOT']."/lib/pps/".basename($Lien->lien));
    }

    unlink($_SERVER['DOCUMENT_ROOT']."/lib/Document/".basename($Lien->lien));

    $deleteActu=$cnx->prepare("DELETE FROM ".DB_PREFIX."Document WHERE id=:id");
    $deleteActu->bindParam(':id', $Id, PDO::PARAM_INT);
    $deleteActu->execute();

    header('Location:'.HOME.'/Admin/Document/');
}

if ((!empty($_GET['id']))&&(isset($_POST['non']))) {  
    header('Location:'.HOME.'/Admin/Document/');
}
?>  

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article>
<?php
if (isset($Erreur)) { echo '
<div class="alert alert-danger" role="alert">
'.$Erreur.'
</div></p>'; }

if (isset($Valid)) { echo '
<div class="alert alert-success" role="alert">
'.$Valid.'
</div></p>'; }
?>

Etes-vous sur de vouloir supprimer ce document ? <BR /><BR />

<table class="Admin" width="300">
<form action="" method="POST">
<TR><TD align="center"><input name="oui" type="submit" value="OUI"></TD><TD align="center"><input name="non" type="submit" value="NON"/></TD></TR>
</form></TABLE>

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>