<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/fonction_perso.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/redirect.inc.php");

$Page=$_POST['page'];
$Action=$_POST['action'];
$Horizontal=$_POST['horizontal'];
$Vertical=$_POST['vertical'];
$Now=time();
$Date=date('d/m/Y', $Now);

if ($Page=="All") {
    $SelectJpg = $cnx -> prepare("SELECT * FROM ".$Prefix."_Signature_Original_Jpg WHERE page='1' AND hash=:hash");
    $SelectJpg-> BindParam(":hash", $_SESSION['hash'], PDO::PARAM_STR);
    $SelectJpg-> execute(); 
    $AfficheJpg=$SelectJpg->fetch(PDO::FETCH_OBJ);

    echo "<img src='".$repExtJpgOriginal.$AfficheJpg->fichier."' />";
}
elseif ($Page=="Last") {
    $SelectJpg2 = $cnx -> prepare("SELECT * FROM ".$Prefix."_Signature_Original_Jpg WHERE hash=:hash");
    $SelectJpg2-> BindParam(":hash", $_SESSION['hash'], PDO::PARAM_STR);
    $SelectJpg2-> execute(); 
    $AfficheJpg2=$SelectJpg2->fetch(PDO::FETCH_OBJ);

    $CountPage2=$SelectJpg2->rowCount();

    $SelectJpg = $cnx -> prepare("SELECT * FROM ".$Prefix."_Signature_Original_Jpg WHERE page=:page AND hash=:hash");
    $SelectJpg-> BindParam(":hash", $_SESSION['hash'], PDO::PARAM_STR);
    $SelectJpg-> BindParam(":page", $CountPage2, PDO::PARAM_STR);
    $SelectJpg-> execute(); 
    $AfficheJpg=$SelectJpg->fetch(PDO::FETCH_OBJ);

    echo "<img src='".$repExtJpgOriginal.$AfficheJpg->fichier."' />";
}
else {
    $SelectJpg = $cnx -> prepare("SELECT * FROM ".$Prefix."_Signature_Original_Jpg WHERE page=:page AND hash=:hash");
    $SelectJpg-> BindParam(":hash", $_SESSION['hash'], PDO::PARAM_STR);
    $SelectJpg-> BindParam(":page", $Page, PDO::PARAM_STR);
    $SelectJpg-> execute(); 
    $AfficheJpg=$SelectJpg->fetch(PDO::FETCH_OBJ);

    echo "<img src='".$repExtJpgOriginal.$AfficheJpg->fichier."' />"; 
}
$Horizontal=$Horizontal/10;
$Horizontal=($Horizontal*118)*0.32271077;
$Vertical=$Vertical/10;
$Vertical=($Vertical*118)*0.32271077;
?>
<div id="ApercuTampon" style="top: <?php echo $Vertical.'px'; ?>; left: <?php echo $Horizontal.'px'; ?>;">
<?php

if ($Action=="Tampon") { echo '<img src="'.$Tampon_Ext.'"/>'; }
elseif ($Action=="Signature") { echo '<img src="'.$Signature_Ext.'"/>'; }
elseif ($Action=="Date") { echo $Date; }
else { 
    $SelectFichier=$cnx->prepare("SELECT * FROM ".$Prefix."_Signature_Tampon WHERE id=:id");
    $SelectFichier->BindParam(':id', $Action, PDO::PARAM_STR);
    $SelectFichier->execute();
    $Select=$SelectFichier->fetch(PDO::FETCH_OBJ);

    echo '<img src="'.$repExtTampon.$Select->lien.'" />'; 
}
?>
</div>