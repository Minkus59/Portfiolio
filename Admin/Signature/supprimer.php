<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/fonction_perso.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/redirect.inc.php");

if ($Cnx_Admin!=TRUE) {
  header('location:'.$Home.'/Admin');
}

$Id=$_GET['id'];

if ((!empty($_GET['id']))&&(isset($_POST['oui']))) {
    
    $SelectArchive = $cnx -> prepare("SELECT * FROM ".$Prefix."_Signature_Original WHERE id=:id");
    $SelectArchive-> BindParam(":id", $Id, PDO::PARAM_STR);
    $SelectArchive-> execute(); 
    $ArchiveInfo=$SelectArchive->fetch(PDO::FETCH_OBJ);
    
    unlink($repIntOriginal.$ArchiveInfo->fichier);

    $Suppr=$cnx->prepare("DELETE FROM ".$Prefix."_Signature_Original WHERE id=:id");
    $Suppr->bindParam(':id', $Id, PDO::PARAM_INT);
    $Suppr->execute();

    $SelectArchiveJpg = $cnx -> prepare("SELECT * FROM ".$Prefix."_Signature_Original_Jpg WHERE hash=:hash");
    $SelectArchiveJpg-> BindParam(":hash", $ArchiveInfo->hash, PDO::PARAM_STR);
    $SelectArchiveJpg-> execute(); 
    
    while ($ArchiveJpg=$SelectArchiveJpg->fetch(PDO::FETCH_OBJ)) {
        unlink($repIntJpgOriginal.$ArchiveJpg->fichier);
    }

    $Suppr=$cnx->prepare("DELETE FROM ".$Prefix."_Signature_Original_Jpg WHERE hash=:hash");
    $Suppr->BindParam(":hash", $ArchiveInfo->hash, PDO::PARAM_STR);
    $Suppr->execute();

    header('Location:'.$Home.'/Admin/Signature/');
}

if ((!empty($_GET['id']))&&(isset($_POST['non']))) {  
    header('Location:'.$Home.'/Admin/Signature/');
}
?>  

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article>
<?php if (isset($Erreur)) { echo "<font color='#FF0000'>".$Erreur."</font><BR />"; }
if (isset($Valid)) { echo "<font color='#009900'>".$Valid."</font><BR />"; } ?>

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