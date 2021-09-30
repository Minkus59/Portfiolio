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

    $Update=$cnx->prepare("UPDATE ".DB_PREFIX."compte_Admin SET valider=0 WHERE id=:id");
    $Update->bindParam(':id', $Id, PDO::PARAM_INT);
    $Update->execute();

    header('Location:'.HOME.'/Admin/CompteAdmin/');
}

if ((!empty($_GET['id']))&&(isset($_POST['non']))) {  
    header('Location:'.HOME.'/Admin/CompteAdmin/');
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

Etes-vous sur de vouloir désactiver ce compte ? </p>

<TABLE width="300">
<form action="" method="POST">
<TR><TD align="center"><input name="oui" type="submit" value="OUI"></TD><TD align="center"><input name="non" type="submit" value="NON"/></TD></TR>
</form></TABLE>

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>