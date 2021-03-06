<?php
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");

if ($Cnx_Admin===false) {
  header('location:'.HOME.'/Admin');
}

if (isset($_GET['erreur']) || isset($_GET['valid'])) {
      $Erreur=$_GET['erreur'];
      $Valid=$_GET['valid'];
}

$Id=$_GET['id'];
$Now=time();

if (isset($_POST['StatueSocial'])) {
   $_SESSION['StatueSocial']=$_POST['StatueSocial'];
}

if ((!isset($_SESSION['StatueSocial']))||($_SESSION['StatueSocial']=="NULL")) {
      $_SESSION['StatueSocial']="NULL";
     $Select=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Social ORDER BY id ASC");
     $Select->execute();
}
else {
     $Select=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Social WHERE statue=:statue ORDER BY id ASC");
     $Select->bindParam(':statue', $_SESSION['StatueSocial'], PDO::PARAM_STR);
     $Select->execute();
}

$SelectPage=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Social");
$SelectPage->execute();
    
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

<H1>Liste des liens Sociaux</H1>

<form name="FormSocial" action="" method="POST">
<select name="StatueSocial" required="required" onChange="this.form.submit()">  
<option value="NULL" <?php if ($_SESSION['StatueSocial']=="NULL") { echo "selected"; } ?>>Tous</option>
<option value='1' <?php if ($_SESSION['StatueSocial']== "1") { echo "selected"; } ?>>Actif</option>
<option value='0' <?php if ($_SESSION['StatueSocial']== "0") { echo "selected"; } ?>>Inactif</option>
</select>
</form>

<table class="Admin">
<tr><th>Image</th><th>Lien</th><th>Action</th></tr>
<?php

while ($Image=$Select->fetch(PDO::FETCH_OBJ)) {
?>
   <tr <?php if ($Image->statue==0) { echo "class='rouge'"; } else { echo "class='vert'"; } ?>>
   <td><?php echo "<img width='40px' src='".$Image->logo."'/>"; ?></td>
   <td><?php echo $Image->lien; ?></td>
   <td><?php echo '<a href="'.HOME.'/Admin/Social/Nouveau/?id='.$Image->id.'"><img src="'.HOME.'/Admin/lib/img/modifier.png"></a>';
   if ($Image->statue==1) { ?>
        <a title="D??sactiver" href="<?php echo HOME ?>/Admin/Social/desactiver.php?id=<?php echo $Image->id; ?>"><img src="<?php echo HOME ?>/Admin/lib/img/desactiver.png" alt="D??sactiver"></a>
  <?php } else { ?>
        <a title="Activer" href="<?php echo HOME ?>/Admin/Social/activer.php?id=<?php echo $Image->id; ?>"><img src="<?php echo HOME ?>/Admin/lib/img/activer.png" alt="Activer"></a>
  <?php } 
        echo '<a title="Supprimer" href="'.HOME.'/Admin/Social/supprimer.php?id='.$Image->id.'"><img src="'.HOME.'/Admin/lib/img/supprimer.png"></a></td></tr>';
}
?>
</table>

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>