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

$RecupEmail=$cnx->prepare("SELECT * FROM ".DB_PREFIX."mailing_Parametre");
$RecupEmail->execute();
$Info=$RecupEmail->fetch(PDO::FETCH_OBJ);

if (isset($_POST['Valider'])) {
    $Preparation7=$cnx->query("CREATE TABLE ".DB_PREFIX."mailing_Groupe (
        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `liste` varchar(50) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8"); 

        $Preparation8=$cnx->query("CREATE TABLE ".DB_PREFIX."mailing_Historique (
        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `destinataire` longtext NOT NULL,
        `objet` longtext NOT NULL,
        `message` longtext NOT NULL,
        `retour` longtext NOT NULL,
        `type` int(5) DEFAULT NULL,
        `created` int(15) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8"); 

        $Preparation9=$cnx->query("CREATE TABLE ".DB_PREFIX."mailing_Liste (
        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `nom` longtext,
        `prenom` longtext,
        `email` longtext NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8"); 

        $Preparation10=$cnx->query("CREATE TABLE ".DB_PREFIX."mailing_Liste_Diffusion (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `nom` longtext,
            `prenom` longtext,
            `email` varchar(80) NOT NULL,
            `liste` longtext NOT NULL,
            `diffusion` int(1) NOT NULL DEFAULT '1',
            `hash` varchar(32) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8"); 

        $Preparation11=$cnx->query("CREATE TABLE ".DB_PREFIX."mailing_Parametre (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `email` varchar(80) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8"); 

        $Preparation12=$cnx->query("CREATE TABLE ".DB_PREFIX."mailing_Predefini (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `libele` longtext NOT NULL,
            `mailing` longtext NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8"); 

        $Preparation13=$cnx->query("CREATE TABLE ".DB_PREFIX."mailing_Signature (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `libelle` longtext NOT NULL,
            `signature` longtext NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8"); 

    $Email=FiltreEmail('email');
    
    if($Email[0]===false) {
        $Erreur=$Email[1];
    }
    else {
        $VerifEmail=$cnx->prepare("SELECT * FROM ".DB_PREFIX."mailing_Parametre");
        $VerifEmail->execute();
        $Rows=$VerifEmail->rowCount();
        
        if ($Rows==0) {
            $InsertEmail=$cnx->prepare("INSERT INTO ".DB_PREFIX."mailing_Parametre (email) VALUES (:email)");
            $InsertEmail->bindParam(':email', $Email, PDO::PARAM_STR);
            $InsertEmail->execute();  

            $Valid="Enregistrement réussie";
            header("location:".HOME."/Admin/Mailing/Param/?valid=".$Valid);
        }
        else {
            $InsertEmail=$cnx->prepare("UPDATE ".DB_PREFIX."mailing_Parametre SET email=:email");
            $InsertEmail->bindParam(':email', $Email, PDO::PARAM_STR);
            $InsertEmail->execute();

            $Valid="Enregistrement réussie";
            header("location:".HOME."/Admin/Mailing/Param/?valid=".$Valid);
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

Veuillez saisir un adresse e-mail pour les retours d'e-mail <BR /><BR />

<form name="FormEmail" action="" method="POST">   
    <input name="email" type="email" value="<?php echo $Info->email; ?>"/><BR /><BR />
    
    <input type="submit" name="Valider" value="Valider" />
</form> 

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>