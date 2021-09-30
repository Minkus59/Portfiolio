<?php
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");


if ($Cnx_Admin===false) {
  header('location:'.HOME.'/Admin');
}

if (!isset($_SESSION['StatueMenu'])) {
    $_SESSION['StatueMenu'] = HOME."/";
}

if (isset($_GET['erreur']) || isset($_GET['valid'])) {
      $Erreur=$_GET['erreur'];
      $Valid=$_GET['valid'];
}

$SelectMenu=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Menu");
$SelectMenu->execute();

$SelectPage=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Page WHERE sous_menu='0' ORDER BY position ASC");
$SelectPage->execute();

if (isset($_GET['id'])) { 
    $Id=$_GET['id'];
    $Select=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Menu WHERE id=:id");
    $Select->BindParam(":id", $Id, PDO::PARAM_STR);
    $Select->execute();
    $Actu=$Select->fetch(PDO::FETCH_OBJ);
}

if ((isset($_POST['Modifier']))&&(isset($_GET['id']))) {
    $Position=$_POST['position'];
    $Libele=$_POST['libele'];
    $Lien=$_POST['lien'];

    $Insert=$cnx->prepare("UPDATE ".DB_PREFIX."Menu SET position=:position, libele=:libele, lien=:lien WHERE id=:id");
    $Insert->BindParam(":id", $Id, PDO::PARAM_STR);
    $Insert->BindParam(":position", $Position, PDO::PARAM_STR);
    $Insert->BindParam(":libele", $Libele, PDO::PARAM_STR);
    $Insert->BindParam(":lien", $Lien, PDO::PARAM_STR);
    $Insert->execute();

    if (!$Insert) {
        $Erreur="Erreur serveur, veuillez réessayer ultèrieurement !";
    }
    else  {     
        $Valid="Menu modifier avec succès";
    }
}

if ((isset($_POST['Ajouter']))&&(!isset($_GET['id']))) {
    $Libele=$_POST['libele'];
    $Parrain=$_POST['parrain'];
    $Lien=$_POST['lien'];
           
    if (strlen(trim($Libele))<=2) {
        $Erreur="Veuillez saisir un libellé de Menu !";
    }
    else {   
        if ($Parrain!="") {         
            $Verif=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Menu WHERE parrin=:parrin AND sous_menu='1'");
            $Verif->BindParam(":parrin", $Parrain, PDO::PARAM_STR);  
            $Verif->execute();
            $NbMenu=$Verif->rowCount();
            $Position=$NbMenu+1;

            $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."Menu (sous_menu, parrin, position, libele, lien) VALUES('1', :parrin, :position, :libele, :lien)");
            $Insert->BindParam(":position", $Position, PDO::PARAM_STR);
            $Insert->BindParam(":libele", $Libele, PDO::PARAM_STR);  
            $Insert->BindParam(":lien", $Lien, PDO::PARAM_STR);    
            $Insert->BindParam(":parrin", $Parrain, PDO::PARAM_STR);
            $Insert->execute();
        }
        else {             
            $Verif=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Menu");
            $Verif->execute();
            $NbMenu=$Verif->rowCount();
            $Position=$NbMenu+1;

            $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."Menu (position, libele, lien) VALUES(:position, :libele, :lien)");
            $Insert->BindParam(":position", $Position, PDO::PARAM_STR);
            $Insert->BindParam(":libele", $Libele, PDO::PARAM_STR);     
            $Insert->BindParam(":lien", $Lien, PDO::PARAM_STR); 
            $Insert->execute();
        }

        if ($Insert==false) {
            $Erreur="Erreur serveur, veuillez réessayer ultèrieurement !";
        }
        else  {
            $Valid="Menu ajouter avec succès";
        }
    }
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

<?php if (isset($_GET['id'])) { ?>
      <H1>Modifier le Menu</H1><BR /> <?php
} else { ?>
  <H1>Ajouter un nouveau bouton</H1><BR /> <?php
} ?>

<div id="Gauche">
<form name="form_actu" action="" method="POST" enctype="multipart/form-data">

<?php if (!isset($_GET['id'])) { ?>
<input type="text" placeholder="Libellé" name="libele" require="required" value="<?php if (isset($_GET['id'])) { echo $Actu->libele; } ?>"><BR /><BR />
<input type="text" placeholder="Lien du bouton" name="lien" require="required" value="<?php if (isset($_GET['id'])) { echo $Actu->lien; } ?>"><BR /><BR />

<select name="parrain">     
<option value="">Bouton parent</option>

<?php while ($MenuParrain=$SelectMenu->fetch(PDO::FETCH_OBJ)) { ?>
<option value='<?php echo $MenuParrain->lien; ?>' ><?php echo $MenuParrain->libele; ?></option>
<?php } ?>
</select><BR /><BR />

<?php }
if (isset($_GET['id'])) { ?>
      <input type="text" placeholder="Position dans le menu" name="position" require="required" value="<?php echo $Actu->position; ?>"><BR /><BR />
      <input type="text" placeholder="Libellé" name="libele" require="required" value="<?php echo $Actu->libele; ?>"><BR /><BR />
      <input type="text" placeholder="Lien du bouton" name="lien" require="required" value="<?php if (isset($_GET['id'])) { echo $Actu->lien; } ?>"><BR /><BR />
      
      <input type="submit" name="Modifier" value="Modifier"/>
<?php } 
else { ?>
    <input type="submit" name="Ajouter" value="Ajouter"/>
    <?php } ?>
</form>
<p><font color='#FF0000'>*</font> Champ de saisie requis<BR /><BR />

</div>
</article>
<article>
<H2>Liste des pages</H2>

<table class="Admin">
<tr>
      <th>Libellé</th>
      <th>Lien de page</th>
      <th>Titre</th>
      <th>Description</th>
      <th>Mots-clés</th>
      <th>Date de création</th>
      </tr>
<?php

while ($Page=$SelectPage->fetch(PDO::FETCH_OBJ)) {
      $SelectSousMenu=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Page WHERE parrin=:parrin AND sous_menu='1' ORDER BY position ASC");
      $SelectSousMenu->bindParam(':parrin', $Page->lien, PDO::PARAM_STR);
      $SelectSousMenu->execute();
      $CountSousMenu=$SelectSousMenu->rowCount();
      ?>
      <tr <?php if ($Page->statue==0) { echo "class='rouge'"; } else { echo "class='vert'"; } ?>>
      <td><?php echo $Page->libele; ?></td>
      <td><?php echo $Page->lien; ?></td>
      <td><?php echo $Page->titre; ?></td>
      <td><?php echo $Page->description; ?></td>
      <td><?php echo $Page->keywords; ?></td>
      <td><?php echo date("d-m-Y", $Page->created); ?></td>
      <?php
      if ($CountSousMenu>0) {
            while ($SousMenu=$SelectSousMenu->fetch(PDO::FETCH_OBJ)) { 
                  $SelectSousSousMenu=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Page WHERE parrin=:parrin AND sous_menu='1' ORDER BY position ASC");
                  $SelectSousSousMenu->bindParam(':parrin', $SousMenu->lien, PDO::PARAM_STR);
                  $SelectSousSousMenu->execute();
                  $CountSousSousMenu=$SelectSousSousMenu->rowCount();

                  ?>
                  <tr <?php if ($SousMenu->statue==0) { echo "class='rouge'"; } else { echo "class='vert'"; } ?>>
                  <td><?php echo $SousMenu->libele; ?></td>
                  <td><?php echo $SousMenu->lien; ?></td>
                  <td><?php echo $SousMenu->titre; ?></td>
                  <td><?php echo $SousMenu->description; ?></td>
                  <td><?php echo date("d-m-Y", $SousMenu->created); ?></td>
                  <?php

                  if ($CountSousSousMenu>0) {
                        while ($SousSousMenu=$SelectSousSousMenu->fetch(PDO::FETCH_OBJ)) {
                              ?>
                              <tr <?php if ($SousSousMenu->statue==0) { echo "class='rouge'"; } else { echo "class='vert'"; } ?>>
                              <td><?php echo $SousSousMenu->libele; ?></td>
                              <td><?php echo $SousSousMenu->lien; ?></td>
                              <td><?php echo $SousSousMenu->titre; ?></td>
                              <td><?php echo $SousSousMenu->description; ?></td>
                              <td><?php echo date("d-m-Y", $SousSousMenu->created); ?></td>
                              <?php
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