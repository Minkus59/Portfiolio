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

$Select=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Menu ORDER BY position ASC");
$Select->execute();

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

<H1>Mémo</H1>

Les élément du tableau sont détailler ci-dessous :
<ul>
<li><b>Position :</b> Indique la position d'affichage dans le menu, sous-menu, sous-sous-menu; 0 Indique que cette page ne peut être positionné par l'utilisateur, elle est positionné de façon stratégique.</li>   
<li><b>Libélé :</b> Le texte du bouton afficher dans le menu.</li>
<li><b>Lien de page :</b> Le lien de la page sur le domaine.</li>
<li><b>Action :</b> Les actions a réaliser sur la page.</li>
</ul>

<H2>Liste des boutons</H2>

<table class="Admin">
<tr>
      <th>Position</th>
      <th>Libellé</th>
      <th>Lien de page</th>
      <th>Action</th>
      </tr>
<?php

while ($Menu=$Select->fetch(PDO::FETCH_OBJ)) {
      $SelectSousMenu=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Menu WHERE parrin=:parrin AND sous_menu='1' ORDER BY position ASC");
      $SelectSousMenu->bindParam(':parrin', $Menu->lien, PDO::PARAM_STR);
      $SelectSousMenu->execute();
      $CountSousMenu=$SelectSousMenu->rowCount();
      ?>
      <tr <?php if ($Menu->statue==0) { echo "class='rouge'"; } else { echo "class='vert'"; } ?>>
      <td><?php echo $Menu->position; ?></td>
      <td><?php echo $Menu->libele; ?></td>
      <td><?php echo $Menu->lien; ?></td>
      <td><?php 
      echo '<a title="Apperçu" href="'.HOME.$Menu->lien.'"><img src="'.HOME.'/Admin/lib/img/apercu.png"></a>';
      echo '<a href="'.HOME.'/Admin/Menu/Nouveau/?id='.$Menu->id.'"><img src="'.HOME.'/Admin/lib/img/modifier.png"></a>';
      if ($Menu->statue==1) { ?>
            <a title="Désactiver" href="<?php echo HOME ?>/Admin/Menu/desactiver.php?id=<?php echo $Menu->id; ?>"><img src="<?php echo HOME ?>/Admin/lib/img/desactiver.png" alt="Désactiver"></a>
      <?php } else { ?>
            <a title="Activer" href="<?php echo HOME ?>/Admin/Menu/activer.php?id=<?php echo $Menu->id; ?>"><img src="<?php echo HOME ?>/Admin/lib/img/activer.png" alt="Activer"></a>
      <?php } 
      echo '<a title="Supprimer" href="'.HOME.'/Admin/Menu/supprimer.php?id='.$Menu->id.'"><img src="'.HOME.'/Admin/lib/img/supprimer.png"></a></td></tr>';
      
      if ($CountSousMenu>0) {
            while ($SousMenu=$SelectSousMenu->fetch(PDO::FETCH_OBJ)) { 
                  $SelectSousSousMenu=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Menu WHERE parrin=:parrin AND sous_menu='1' ORDER BY position ASC");
                  $SelectSousSousMenu->bindParam(':parrin', $SousMenu->lien, PDO::PARAM_STR);
                  $SelectSousSousMenu->execute();
                  $CountSousSousMenu=$SelectSousSousMenu->rowCount();

                  ?>
                  <tr <?php if ($SousMenu->statue==0) { echo "class='rouge'"; } else { echo "class='vert'"; } ?>>
                  <td><?php echo $SousMenu->position; ?></td>
                  <td><?php echo $SousMenu->libele; ?></td>
                  <td><?php echo $SousMenu->lien; ?></td>
                  <td><?php 
                  echo '<a title="Apperçu" href="'.HOME.$SousMenu->lien.'"><img src="'.HOME.'/Admin/lib/img/apercu.png"></a>';
                  echo '<a href="'.HOME.'/Admin/Menu/Nouveau/?id='.$SousMenu->id.'"><img src="'.HOME.'/Admin/lib/img/modifier.png"></a>';
                  if ($SousMenu->statue==1) { ?>
                        <a title="Désactiver" href="<?php echo HOME ?>/Admin/Menu/desactiver.php?id=<?php echo $SousMenu->id; ?>"><img src="<?php echo HOME ?>/Admin/lib/img/desactiver.png" alt="Désactiver"></a>
                  <?php } else { ?>
                        <a title="Activer" href="<?php echo HOME ?>/Admin/Menu/activer.php?id=<?php echo $SousMenu->id; ?>"><img src="<?php echo HOME ?>/Admin/lib/img/activer.png" alt="Activer"></a>
                  <?php } 
                  echo '<a title="Supprimer" href="'.HOME.'/Admin/Menu/supprimer.php?id='.$SousMenu->id.'"><img src="'.HOME.'/Admin/lib/img/supprimer.png"></a></td></tr>';

                  if ($CountSousSousMenu>0) {
                        while ($SousSousMenu=$SelectSousSousMenu->fetch(PDO::FETCH_OBJ)) {
                              ?>
                              <tr <?php if ($SousSousMenu->statue==0) { echo "class='rouge'"; } else { echo "class='vert'"; } ?>>
                              <td><?php echo $SousSousMenu->position; ?></td>
                              <td><?php echo $SousSousMenu->libele; ?></td>
                              <td><?php echo $SousSousMenu->lien; ?></td>
                              <td><?php 
                              echo '<a title="Apperçu" href="'.HOME.$SousSousMenu->lien.'"><img src="'.HOME.'/Admin/lib/img/apercu.png"></a>';
                              echo '<a href="'.HOME.'/Admin/Menu/Nouveau/?id='.$SousSousMenu->id.'"><img src="'.HOME.'/Admin/lib/img/modifier.png"></a>';
                              if ($SousSousMenu->statue==1) { ?>
                                    <a title="Désactiver" href="<?php echo HOME ?>/Admin/Menu/desactiver.php?id=<?php echo $SousSousMenu->id; ?>"><img src="<?php echo HOME ?>/Admin/lib/img/desactiver.png" alt="Désactiver"></a>
                              <?php } else { ?>
                                    <a title="Activer" href="<?php echo HOME ?>/Admin/Menu/activer.php?id=<?php echo $SousSousMenu->id; ?>"><img src="<?php echo HOME ?>/Admin/lib/img/activer.png" alt="Activer"></a>
                              <?php } 
                              echo '<a title="Supprimer" href="'.HOME.'/Admin/Menu/supprimer.php?id='.$SousSousMenu->id.'"><img src="'.HOME.'/Admin/lib/img/supprimer.png"></a></td></tr>';
                        }
                  }
            }
      }
      ?><tr><td colspan="7"></tr><?php
}
?>
</table>

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>