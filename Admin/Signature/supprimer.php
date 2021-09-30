<?php 

require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");


if ($Cnx_Admin!=TRUE) {
  header('location:'.HOME.'/Admin');
}

$Id=$_GET['id'];

if ((!empty($_GET['id']))&&(isset($_POST['oui']))) {
    
    $SelectArchive = $cnx -> prepare("SELECT * FROM ".DB_PREFIX."Signature_Original WHERE id=:id");
    $SelectArchive-> BindParam(":id", $Id, PDO::PARAM_STR);
    $SelectArchive-> execute(); 
    $ArchiveInfo=$SelectArchive->fetch(PDO::FETCH_OBJ);
    
    unlink($repIntOriginal.$ArchiveInfo->fichier);

    $Suppr=$cnx->prepare("DELETE FROM ".DB_PREFIX."Signature_Original WHERE id=:id");
    $Suppr->bindParam(':id', $Id, PDO::PARAM_INT);
    $Suppr->execute();

    $SelectArchiveJpg = $cnx -> prepare("SELECT * FROM ".DB_PREFIX."Signature_Original_Jpg WHERE hash=:hash");
    $SelectArchiveJpg-> BindParam(":hash", $ArchiveInfo->hash, PDO::PARAM_STR);
    $SelectArchiveJpg-> execute(); 
    
    while ($ArchiveJpg=$SelectArchiveJpg->fetch(PDO::FETCH_OBJ)) {
        unlink($repIntJpgOriginal.$ArchiveJpg->fichier);
    }

    $Suppr=$cnx->prepare("DELETE FROM ".DB_PREFIX."Signature_Original_Jpg WHERE hash=:hash");
    $Suppr->BindParam(":hash", $ArchiveInfo->hash, PDO::PARAM_STR);
    $Suppr->execute();

    header('Location:'.HOME.'/Admin/Signature/');
}

if ((!empty($_GET['id']))&&(isset($_POST['non']))) {  
    header('Location:'.HOME.'/Admin/Signature/');
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

Etes-vous sur de vouloir supprimer ces documents ? </p>

<TABLE width="300">
<form action="" method="POST">
<TR><TD align="center"><input name="oui" type="submit" value="OUI"></TD><TD align="center"><input name="non" type="submit" value="NON"/></TD></TR>
</form></TABLE>

</article>
</section>
</div>
</CENTER>
</body>

</html>