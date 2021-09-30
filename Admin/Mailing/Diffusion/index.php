<?php
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");

if ($Cnx_Admin!=TRUE) {
  header('location:'.HOME.'/Admin');
}

if (isset($_GET['erreur']) || isset($_GET['valid'])) {
      $Erreur=$_GET['erreur'];
      $Valid=$_GET['valid'];
}
$Id=$_GET['id'];
$Liste=$_POST['liste'];

if (isset($_GET['id'])) { 
  $SelectGroupeModif=$cnx->prepare("SELECT * FROM ".DB_PREFIX."mailing_Groupe WHERE id=:id");  
  $SelectGroupeModif->BindParam(":id", $Id, PDO::PARAM_STR); 
  $SelectGroupeModif->execute(); 
  $GroupeModif=$SelectGroupeModif->fetch(PDO::FETCH_OBJ);
}

$SelectGroupe=$cnx->prepare("SELECT * FROM ".DB_PREFIX."mailing_Groupe");    
$SelectGroupe->execute(); 

if ((isset($_POST['Modifier']))&&(isset($_GET['id']))) {
     
    if (empty($Liste)) {
        $Erreur="Un libélé doit etre saisie afin de retrouver votre liste !";
    }
    else {
      $Insert=$cnx->prepare("UPDATE ".DB_PREFIX."mailing_Groupe SET liste=:liste WHERE id=:id");
      $Insert->BindParam(":id", $Id, PDO::PARAM_STR);
      $Insert->BindParam(":liste", $Liste, PDO::PARAM_STR);
      $Insert->execute();

      if (!$Insert) {
          $Erreur="Erreur serveur, veuillez réessayer ultèrieurement !";
      }
      else  {     
          $Valid="Mailling modifier avec succès";
          header("location:".HOME."/Admin/Mailing/Diffusion/?valid=".urlencode($Valid));
      }
  } 
}

if ((isset($_POST['Enregistrer4']))&&(!isset($_GET['id']))) {
    if (empty($Liste)) {
        $Erreur="Un libélé doit etre saisie afin de retrouver votre liste !";
    }
    else {
        $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."mailing_Groupe (liste) VALUES(:liste)");
        $Insert->BindParam(":liste", $Liste, PDO::PARAM_STR);
        $Insert->execute();

        $Valid="Mailling ajouter avec succès";
        header("location:".HOME."/Admin/Mailing/Diffusion/?valid=".urlencode($Valid));
    }
}
    
?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article>
<?php if (isset($Erreur)) { echo "<font color='#FF0000'>".urldecode($Erreur)."</font><BR />"; }
if (isset($Valid)) { echo "<font color='#009900'>".urldecode($Valid)."</font><BR />"; } ?>

<div id="Form_Middle5">
<H1>Créer une liste de diffusion</H1>

<form name="SelectMode" action="" method="POST">
<input type="text" name="liste" placeholder="Libélé*" value="<?php if (isset($Liste)) { echo $Liste; }  else { echo $GroupeModif->liste; } ?>"/><BR /><BR />
<?php
if (isset($_GET['id'])) { 
?>
<input type="submit" class="ButtonOrange" name="Modifier" value="Modifier"/>
<?php } 
else { ?>
<input type="submit" class="ButtonOrange" name="Enregistrer4" value="Enregistrer"/>
<?php } ?>
</form>
</div>

<H1>Liste de diffusion</H1>

<table class="Admin">
    <tr>
      <th>Libellé</th>
      <th>Action</th>
    </tr>
<?php

while ($Groupe=$SelectGroupe->fetch(PDO::FETCH_OBJ)) {
?>
   <tr>
   <td>
        <a title="Aperçu" href="<?php echo HOME ?>/Admin/Mailing/ListeDiffusion/?groupe=<?php echo $Groupe->id; ?>"><?php echo $Groupe->liste; ?></a>
   </td>
   <td>
        <?php 
        echo '<a href="'.HOME.'/Admin/Mailing/Diffusion/?id='.$Groupe->id.'"><img src="'.HOME.'/Admin/lib/img/modifier.png"></a>';
        echo '<a title="Supprimer" href="'.HOME.'/Admin/Mailing/Diffusion/supprimer.php?id='.$Groupe->id.'"><img src="'.HOME.'/Admin/lib/img/supprimer.png"></a></td></tr>';
} ?>
</table>
</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>