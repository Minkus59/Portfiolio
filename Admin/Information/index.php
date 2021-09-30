<?php
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");

if ($Cnx_Admin===false) {
  header('location:'.HOME.'/Admin');
}

if (isset($_GET['erreur']) || $_GET['valid']) {
    $Erreur=$_GET['erreur'];
    $Valid=$_GET['valid']; 
}

$Recupinfo=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Information");
$Recupinfo->execute();
$Info=$Recupinfo->fetch(PDO::FETCH_OBJ);

if (isset($_POST['Valider'])) {
    
    $Publisher=$_POST['publisher'];
    $Adresse=$_POST['adresse'];
    
    if(strlen($Publisher)<=2) {
        $Erreur="Veuillez saisir le nom d'un directeur de la publication";
    }
    else {
        $Verif=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Information");
        $Verif->execute();
        $Rows=$Verif->rowCount();
        
        if ($Rows==0) {
            $Preparation=$cnx->query("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."Information (
                `id` int(32) unsigned NOT NULL AUTO_INCREMENT,
                `publisher` longtext NOT NULL,
                `adresse` longtext DEFAULT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

            $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."Information (publisher, adresse) VALUES (:publisher, :adresse)");
            $Insert->bindParam(':publisher', $Publisher, PDO::PARAM_STR);
            $Insert->bindParam(':adresse', $Adresse, PDO::PARAM_STR);
            $Insert->execute();  

            $Valid="Enregistrement réussie";
            header("location:".HOME."/Admin/Information/?valid=".$Valid);
        }
        else {
            $Insert=$cnx->prepare("UPDATE ".DB_PREFIX."Information SET publisher=:publisher, adresse=:adresse WHERE id='1'");
            $Insert->bindParam(':publisher', $Publisher, PDO::PARAM_STR);
            $Insert->bindParam(':adresse', $Adresse, PDO::PARAM_STR);
            $Insert->execute();

            $Valid="Enregistrement réussie";
            header("location:".HOME."/Admin/Information/?valid=".$Valid);
        }
    }
}
?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article>
<?php if (isset($Erreur)) { echo "<p><font color='#FF0000'>".urldecode($Erreur)."</font><BR /><BR />"; }
if (isset($Valid)) { echo "<p><font color='#009900'>".urldecode($Valid)."</font><BR /><BR />"; }   ?>

<H1>Paramètre</H1>

<form name="FormInfo" action="" method="POST">   
    Directeur de publication (obligatoire) :
    <input name="publisher" type="text" value="<?php echo $Info->publisher; ?>"/><BR /><BR />

    Adresse (page contact) :
    <input name="adresse" type="text" value="<?php echo $Info->adresse; ?>"/><BR /><BR />
    
    <input type="submit" name="Valider" value="Valider" />
</form> 

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>