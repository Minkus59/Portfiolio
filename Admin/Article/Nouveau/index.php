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

$SelectDocument=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Document ORDER BY libele ASC");
$SelectDocument->execute();

$SelectPage=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Page");
$SelectPage->execute();

if (isset($_GET['id'])) { 
    $Select=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Article WHERE id=:id");
    $Select->BindParam(":id", $Id, PDO::PARAM_STR);
    $Select->execute();
    $Actu=$Select->fetch(PDO::FETCH_OBJ);
}

if ((isset($_POST['Modifier']))&&(isset($_GET['id']))) {
    $Message=$_POST['message'];
    $Page=$_POST['page'];
    $Position=$_POST['position'];

    if (trim($Message)=="") {
        $Erreur="Le contenue est vide !";
        ErreurLog($Erreur);
    }
    else {
        $Insert=$cnx->prepare("UPDATE ".DB_PREFIX."Article SET position=:position ,message=:message, page=:page, created=:created WHERE id=:id");
        $Insert->BindParam(":id", $Id, PDO::PARAM_STR);
        $Insert->BindParam(":position", $Position, PDO::PARAM_STR);
        $Insert->BindParam(":message", $Message, PDO::PARAM_STR);
        $Insert->BindParam(":page", $Page, PDO::PARAM_STR);   
        $Insert->BindParam(":created", $Now, PDO::PARAM_STR);
        $Insert->execute();

        if (!$Insert) {
            $Erreur="Erreur serveur, veuillez réessayer ultèrieurement !";
            ErreurLog($Erreur);
        }
        else  {     
            $Valid="Article modifier avec succès";
            header('location:'.HOME.'/Admin/Article/Nouveau/?id='.$Id.'&valid='.urlencode($Valid));
        }
    }
} 

if ((isset($_POST['Ajouter']))&&(!isset($_GET['id']))) {
    $Message=$_POST['message'];
    $Page=$_POST['page'];

    if (trim($Message)=="") {
        $Erreur="Le contenue est vide !";
        ErreurLog($Erreur);
    }
    else {
         //verifier si 1er article sinon position +1
         $Verif=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Article WHERE page=:page");
         $Verif->BindParam(":page", $Page, PDO::PARAM_STR);
         $Verif->execute();
         $NbPage=$Verif->rowCount();

         if ($NbPage!=0) {
              $Position=$NbPage+1;
              $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."Article (position, message, page, created) VALUES(:position, :message, :page, :created)");
              $Insert->BindParam(":position", $Position, PDO::PARAM_STR);
              $Insert->BindParam(":message", $Message, PDO::PARAM_STR);
              $Insert->BindParam(":page", $Page, PDO::PARAM_STR);        
              $Insert->BindParam(":created", $Now, PDO::PARAM_STR);
              $Insert->execute();
         }
         else {
              $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."Article (message, page, created) VALUES(:message, :page, :created)");
              $Insert->BindParam(":message", $Message, PDO::PARAM_STR);
              $Insert->BindParam(":page", $Page, PDO::PARAM_STR);        
              $Insert->BindParam(":created", $Now, PDO::PARAM_STR);
              $Insert->execute();
         }

         if ($Insert==false) {
            $Erreur="Erreur serveur, veuillez réessayer ultèrieurement !";
         }
         else  {
            $Valid="Article ajouter avec succès";
            header('location:'.HOME.'/Admin/Article/Nouveau/?valid='.urlencode($Valid));
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
      <H1>Modifier un article</H1> <?php
} else { ?>
  <H1>Ajouter un nouvel article</H1><?php
} ?>

<form name="form_actu" action="" method="POST" enctype="multipart/form-data">
<select name="page">
<option value="">--  --</option>

<?php while ($Page=$SelectPage->fetch(PDO::FETCH_OBJ)) { ?>
<option value='<?php echo $Page->lien; ?>' <?php if(isset($_GET['id'])) { if ($Actu->page == $Page->lien) { echo "selected"; }} ?>><?php echo $Page->lien; ?></option><?php } ?>
</select><BR /><BR />

<textarea id="message" name="message" placeholder="Message*" require="required"><?php if (isset($_GET['id'])) { echo $Actu->message; } ?></textarea><BR /><BR />

<?php if (isset($_GET['id'])) { ?>
      <input type="text" placeholder="Position" name="position" require="required" value="<?php echo $Actu->position; ?>"><BR /><BR />
<?php } ?>

<?php if (isset($_GET['id'])) { ?><input type="submit" name="Modifier" value="Modifier"/> <?php } else { ?><input type="submit" name="Ajouter" value="Ajouter"/><?php } ?>
</form>
<p><font color='#FF0000'>*</font> Champ de saisie requis<BR />

<BR /><HR /><BR />

<H1>Aide</H1>

<a href="<?php echo HOME ?>/Admin/lib/doc/userguide.pdf">Guide utilisateur</a> de l'editeur de texte <BR />

<H1>Infos utiles</H1>

Dans la barre "Outils" cliquer sur "Code source"
Encadrer votre texte dans les balises suivante :<BR />
<xmp>   
<section id="Contact" class="ContentBlanc">
    <div class="row justify-content-center">
        text ici

        <div class="Retour">
            <input type="button" onclick="window.location.href='#Haut'">
        </div>
    </div>
</section>
</xmp>
<BR />
<xmp> 
<section id="Contact" class="ContentBlanc">
    <div class="row justify-content-center">
        text ici

        <div class="Retour">
            <input type="button" onclick="window.location.href='#Haut'">
        </div>
    </div>
</section>
</xmp>
<BR /><HR /><BR />


<H1>Documents</H1>

<table class="Admin">
<tr><th>Libellé</th><th>Type</th><th>Aperçu</th><th>Lien</th><th>Action</th></tr>
<?php

while ($Document=$SelectDocument->fetch(PDO::FETCH_OBJ)) {
      echo "<TR>";
      echo "<TD>".$Document->libele."</TD>";
      echo "<TD>".$Document->type."</TD>";
      echo "<TD>";
      if ($Document->type=="Image") {
          $Taille=getimagesize($Document->lien);
          if (($Taille[0]>250)||($Taille[1]>250)) {
            if ($Taille[0]>=$Taille[1]) {
                echo "<img width='250px' src='$Document->lien'/>";
            }
            else {
                echo "<img width='250px' src='$Document->lien'/>";
            }
        }
        else {
            echo "<img src='$Document->lien'/>";
        }
      }
      echo "</TD>";
      echo "<TD>".$Document->lien."</TD>";
      echo "<TD>";
      if ($Document->type=="PDF") {
        echo "<a href='$Document->lien'><img src='echo HOME/Admin/lib/img/apercu.png'></a>";
      }
      echo "</TD></TR>";
}
?>
</table>

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>