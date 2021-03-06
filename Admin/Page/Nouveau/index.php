<?php
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");


if ($Cnx_Admin===false) {
  header('location:'.HOME.'/Admin');
}

if (!isset($_SESSION['StatuePage'])) {
    $_SESSION['StatuePage'] = HOME."/";
}

if (isset($_GET['erreur']) || isset($_GET['valid'])) {
      $Erreur=$_GET['erreur'];
      $Valid=$_GET['valid'];
}
$Id=$_GET['id'];
$Now=time();

$SelectPage=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Page");
$SelectPage->execute();

if (isset($_GET['id'])) { 
    $Select=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Page WHERE id=:id");
    $Select->BindParam(":id", $Id, PDO::PARAM_STR);
    $Select->execute();
    $Actu=$Select->fetch(PDO::FETCH_OBJ);
}

if ((isset($_POST['Modifier']))&&(isset($_GET['id']))) {
     
    $Titre=$_POST['titre'];
    $Description=$_POST['description'];
    $Keywords=$_POST['keywords'];
    $Libele=$_POST['libele'];

    $Insert=$cnx->prepare("UPDATE ".DB_PREFIX."Page SET libele=:libele ,titre=:titre, description=:description, keywords=:keywords WHERE id=:id");
    $Insert->BindParam(":id", $Id, PDO::PARAM_STR);
    $Insert->BindParam(":libele", $Libele, PDO::PARAM_STR);
    $Insert->BindParam(":titre", $Titre, PDO::PARAM_STR);
    $Insert->BindParam(":description", $Description, PDO::PARAM_STR);   
    $Insert->BindParam(":keywords", $Keywords, PDO::PARAM_STR); 
    $Insert->execute();

    if (!$Insert) {
        $Erreur="Erreur serveur, veuillez réessayer ultèrieurement !";
        ErreurLog($Erreur);
    }
    else  {     
        $Valid="Page modifier avec succès";
        header('location:'.HOME.'/Admin/Page/Nouveau/?id='.$Id.'&valid='.urlencode($Valid));
    }
} 

if ((isset($_POST['Ajouter']))&&(!isset($_GET['id']))) {
    $Libele=$_POST['libele'];
    $Page=$_POST['page'];
    
    $Lien = preg_replace('#Ç#', 'C', $Libele);
    $Lien = preg_replace('#ç#', 'c', $Lien);
    $Lien = preg_replace('#è|é|ê|ë#', 'e', $Lien);
    $Lien = preg_replace('#È|É|Ê|Ë#', 'E', $Lien);
    $Lien = preg_replace('#à|á|â|ã|ä|å#', 'a', $Lien);
    $Lien = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $Lien);
    $Lien = preg_replace('#ì|í|î|ï#', 'i', $Lien);
    $Lien = preg_replace('#Ì|Í|Î|Ï#', 'I', $Lien);
    $Lien = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $Lien);
    $Lien = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $Lien);
    $Lien = preg_replace('#ù|ú|û|ü#', 'u', $Lien);
    $Lien = preg_replace('#Ù|Ú|Û|Ü#', 'U', $Lien);
    $Lien = preg_replace('#ý|ÿ#', 'y', $Lien);
    $Lien = preg_replace('#Ý#', 'Y', $Lien);
    $Lien = preg_replace('# #', '-', $Lien);
    $Lien = preg_replace('#\'#', '-', $Lien);
    $Lien = preg_replace('#\"#', '-', $Lien);
       
    if (strlen(trim($Libele))<=2) {
        $Erreur="Veuillez saisir un nom de page !";
        ErreurLog($Erreur);
    }
    else {
         $Fichier=$_SERVER['DOCUMENT_ROOT']."/frontend/index.zip";
         
         if ($Page!="") {
             $Destination=$_SERVER['DOCUMENT_ROOT'].$Page.$Lien;
             
             $Verif=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Page WHERE parrin=:parrin AND sous_menu=='1'");
             $Verif->BindParam(":parrin", $Page, PDO::PARAM_STR);  
             $Verif->execute();
             $NbPage=$Verif->rowCount();
         }
         else {
             //$Lien2 = preg_replace("/\//", "", $Lien);
             $Destination=$_SERVER['DOCUMENT_ROOT']."/".$Lien;
             
             $Verif=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Page WHERE statue!='2' AND sous_menu!='1'");
             $Verif->execute();
             $NbPage=$Verif->rowCount();
         }
        
        if (!mkdir($Destination, 0777, true)) {
            $Erreur="Echec lors de la création du répertoire";
            ErreurLog($Erreur);
        }
        else {
            $zip = new ZipArchive;
            
            if ($zip->open($Fichier) === TRUE) {
                $zip->extractTo($Destination);
                $zip->close();
                                    
                if ($Page!="") {
                    $Lien=$Page.$Lien."/";
                    //Dans un sous dossier
                    $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."Page (sous_menu, parrin, libele, lien, created) VALUES('1', :parrin, :libele, :lien, :created)");
                    $Insert->BindParam(":libele", $Libele, PDO::PARAM_STR);  
                    $Insert->BindParam(":lien", $Lien, PDO::PARAM_STR);    
                    $Insert->BindParam(":created", $Now, PDO::PARAM_STR);
                    $Insert->BindParam(":parrin", $Page, PDO::PARAM_STR);
                    $Insert->execute();
                }
                else {
                    //A la racine
                    $Lien="/".$Lien."/";
                    $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."Page (libele, lien, created) VALUES(:libele, :lien, :created)");
                    $Insert->BindParam(":libele", $Libele, PDO::PARAM_STR);     
                    $Insert->BindParam(":lien", $Lien, PDO::PARAM_STR); 
                    $Insert->BindParam(":created", $Now, PDO::PARAM_STR);
                    $Insert->execute();
                }                

                if ($Insert==false) {
                    $Erreur="Erreur serveur, veuillez réessayer ultèrieurement !";
                    ErreurLog($Erreur);
                }
                else  {
                    SiteMap($cnx);
                    $Valid="Page ajouter avec succès";
                    //header('location:'.HOME.'/Admin/Page/Nouveau/?valid='.urlencode($Valid));
                }
            }
            else {
                rmdir($Destination);
                $Erreur="Echec lors de la création du fichier";
                ErreurLog($Erreur);
            }
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
      <H1>Modifier une page</H1><BR /> <?php
} else { ?>
  <H1>Ajouter une nouvelle page</H1><BR /> <?php
} ?>

<div id="Gauche">
Le menu ne peut comporter que 2 sous-niveau, les pages crées au 3em sous-niveau ne seront pas affiché <BR /><BR />

<form name="form_actu" action="" method="POST" enctype="multipart/form-data">

<?php if (!isset($_GET['id'])) { ?>
<select name="page">     
<option value="" <?php if ($_SESSION['StatuePage']==HOME."/") { echo "selected"; } ?>>Racine</option>

<?php while ($Page=$SelectPage->fetch(PDO::FETCH_OBJ)) { ?>
<option value='<?php echo $Page->lien; ?>' ><?php echo $Page->lien; ?></option>
<?php } ?>
</select><BR /><BR />

<input type="text" placeholder="Libellé" name="libele" require="required" value="<?php if (isset($_GET['id'])) { echo $Actu->libele; } ?>"><BR /><BR />

<?php }
if (isset($_GET['id'])) { ?>
      <input type="text" placeholder="Libellé" name="libele" require="required" value="<?php echo $Actu->libele; ?>"><BR /><BR />
      <input class="Long" type="text" maxlength="70" placeholder="Titre de la page" name="titre" value="<?php echo $Actu->titre; ?>"><BR /><BR />
      <input class="Long" type="text" maxlength="170" placeholder="Description de la page" name="description"value="<?php echo $Actu->description; ?>"><BR /><BR />
      <input class="Long" type="text" maxlength="170" placeholder="Mots clés de la page séparé par une virgule" name="keywords"value="<?php echo $Actu->keywords; ?>"><BR /><BR />
      
      <input type="submit" name="Modifier" value="Modifier"/>
<?php } 
else { ?>
    <input type="submit" name="Ajouter" value="Ajouter"/>
    <?php } ?>
</form>
<p><font color='#FF0000'>*</font> Champ de saisie requis<BR /><BR />

</div>
<div id="Droite">
<div id="TitreGoogle"> 
    <?php if (isset($_GET['id'])) {  echo $Actu->titre; } ?>
</div>
<div id="SiteGoogle"> 
    <?php if (isset($_GET['id'])) { echo HOME.$Actu->lien; } ?>
</div>
<div id="DescriptionGoogle"> 
    <?php if (isset($_GET['id'])) {  echo $Actu->description; } ?>
</div>
<div id="keywordsGoogle"> 
    <?php if (isset($_GET['id'])) {  echo $Actu->keywords; } ?>
</div>

</div>

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>