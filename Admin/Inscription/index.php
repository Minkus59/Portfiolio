<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/frontend/impinfbdd/config.inc.php");
 require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
 require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");

if (isset($_GET['erreur']) || isset($_GET['valid'])) {
      $Erreur=$_GET['erreur'];
      $Valid=$_GET['valid'];
} 
$Now=time();

if (isset($_POST['inscription'])) {
    $Email=FiltreEmail('email');
    $Mdp=FiltreMDP('mdp');
    $Mdp2=FiltreMDP('mdp2');
    $Nom=FiltreText('nom');

    $Hash = md5(uniqid(rand(), true));

    $Body ="<H1>Validation d'inscription</H1></font>          
        Veuillez cliquer sur le lien suivant pour valider votre inscription.</p>
        <a href='".HOME."/Admin/Validation/?id=".$Email."&Valid=1'>Cliquez ici</a></p>
        ____________________________________________________</p>
        Cordialement Michael Helinckx<br />
        http://www.michael-helinckx.fr</p>
        <font color='#FF0000'>Cet e-mail contient des informations confidentielles et / ou protégées par la loi. Si vous n'en êtes pas le véritable destinataire ou si vous l'avez reçu par erreur, informez-en immédiatement son expéditeur et détruisez ce message. La copie et le transfert de cet e-mail sont strictement interdits.</font>";

    if ($Email[0]===false) {
        $Erreur=$Email[1];
        ErreurLog($Erreur);
    }
    elseif ($Mdp[0]===false) {
        $Erreur=$Mdp[1];
        ErreurLog($Erreur);
    }
    elseif ($Mdp2[0]===false) {
        $Erreur=$Mdp2[1]; 
        ErreurLog($Erreur);
    }
    elseif ($Mdp2!=$Mdp) {
        $Erreur="Les mots de passe ne sont pas identique !"; 
        ErreurLog($Erreur);
    }
    elseif ($Nom[0]===false) {
        $Erreur=$Nom[1];
        ErreurLog($Erreur);
    }
    else {
        $RecupClient=$cnx->prepare("SELECT * FROM ".DB_PREFIX."compte_Admin WHERE email=:email");
        $RecupClient->bindParam(':email', $Email, PDO::PARAM_STR);
        $RecupClient->execute();
        $RecupC=$RecupClient->fetch(PDO::FETCH_OBJ);
        $NbRowsEmail=$RecupClient->rowCount();

        if ($NbRowsEmail==1) {
            $Erreur="Cette adresse E-mail existe déjà !<br />"; 
            ErreurLog($Erreur);
        }
        else {
            $Compteur=$cnx->prepare("SELECT * FROM ".DB_PREFIX."compte_Admin");
            $Compteur->execute();
            $NbCompte=$Compteur->rowCount();

            if ($NbCompte==0) {
                $Preparation1=$cnx->query("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."compte_Admin (
                `id` int(32) unsigned NOT NULL AUTO_INCREMENT,
                `nom` varchar(50) NOT NULL,
                `email` varchar(80) NOT NULL,
                `mdp` varchar(32) DEFAULT NULL,
                `activate` int(1) NOT NULL DEFAULT '0',
                `admin` int(1) NOT NULL DEFAULT '0',
                `valider` int(1) NOT NULL DEFAULT '0',
                `created` datetime NOT NULL,
                `hash` varchar(32) NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY email (`email`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
                
                $Preparation2=$cnx->query("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."Admin_secu_mdp (
                `id` int(32) unsigned NOT NULL AUTO_INCREMENT,
                `hash` varchar(32) NOT NULL,
                `email` varchar(80) NOT NULL,
                `created` datetime NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

                $Preparation3=$cnx->query("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."Document (
                `id` int(32) unsigned NOT NULL AUTO_INCREMENT,
                `libele` longtext DEFAULT NULL,
                `lien` longtext NOT NULL,
                `type` varchar(50) NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

                $Preparation32=$cnx->query("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."Logo (
                `id` int(32) unsigned NOT NULL AUTO_INCREMENT,
                `logo` longtext NOT NULL,
                `lien` longtext DEFAULT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

                $Preparation5=$cnx->query("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."Page (
                `id` int(32) unsigned NOT NULL AUTO_INCREMENT,
                `libele` longtext NOT NULL,
                `lien` longtext NOT NULL,
                `parrin` longtext DEFAULT NULL,
                `sous_menu` int(1) NOT NULL DEFAULT '0',
                `position` int(2) NOT NULL DEFAULT '1',
                `statue` int(1) NOT NULL DEFAULT '0',
                `titre` varchar(70) DEFAULT NULL,
                `description` varchar(170) DEFAULT NULL,
                `created` int(32) NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

                $Preparation4=$cnx->query("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."Article (
                `id` int(32) unsigned NOT NULL AUTO_INCREMENT,
                `position` int(5) NOT NULL DEFAULT '1',
                `message` longtext NOT NULL,
                `page` longtext NOT NULL,
                `statue` int(1) NOT NULL DEFAULT '1',
                `created` int(11) NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"); 
                
                $Preparation6=$cnx->query("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."Social (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `logo` longtext NOT NULL,
                `lien` longtext DEFAULT NULL,
                `statue` int(1) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"); 

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

                $Accueil="/";
                $Contact="/Contact/";

                $Insert1=$cnx->prepare("INSERT INTO ".DB_PREFIX."Page (libele, lien, position, statue, created) VALUES('Accueil', :lien, '0', '2', :created)");
                $Insert1->BindParam(":lien", $Accueil, PDO::PARAM_STR);
                $Insert1->BindParam(":created", $Now, PDO::PARAM_STR);
                $Insert1->execute();
                                    
                $Insert3=$cnx->prepare("INSERT INTO ".DB_PREFIX."Page (libele, lien, position, statue, created) VALUES('Contact', :lien, '0', '1', :created)");
                $Insert3->BindParam(":created", $Now, PDO::PARAM_STR);
                $Insert3->BindParam(":lien", $Contact, PDO::PARAM_STR);
                $Insert3->execute();

                $VerifInsertLogo=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Logo");
                $VerifInsertLogo->bindParam(':email', $Email, PDO::PARAM_STR);
                $VerifInsertLogo->execute();
                $VerifCount=$VerifInsertLogo->rowCount();

                $Default=HOME."/lib/logo/logoType.png";
                $Insert4=$cnx->prepare("INSERT INTO ".DB_PREFIX."Logo (logo) VALUES(:logo)");
                $Insert4->BindParam(":logo", $Default, PDO::PARAM_STR);
                $Insert4->execute();

                $Default=HOME."/lib/header/headerType.jpg";
                $Insert5=$cnx->prepare("INSERT INTO ".DB_PREFIX."Logo (logo) VALUES(:logo)");
                $Insert5->BindParam(":logo", $Default, PDO::PARAM_STR);
                $Insert5->execute();

                $InsertUser=$cnx->prepare("INSERT INTO ".DB_PREFIX."compte_Admin (nom, email, admin, activate, valider, created, hash) VALUES (:nom, :email, '1', '1', '1', NOW(), :hash)");
                $InsertUser->bindParam(':nom', $Nom, PDO::PARAM_STR);
                $InsertUser->bindParam(':email', $Email, PDO::PARAM_STR);
                $InsertUser->bindParam(':hash', $Hash, PDO::PARAM_STR);
                $InsertUser->execute();                 
            }
            elseif ($NbCompte==1) {
                $InsertUser=$cnx->prepare("INSERT INTO ".DB_PREFIX."compte_Admin (nom, email, admin, created, hash) VALUES (:nom, :email, '1', NOW(), :hash)");
                $InsertUser->bindParam(':nom', $Nom, PDO::PARAM_STR);
                $InsertUser->bindParam(':email', $Email, PDO::PARAM_STR);
                $InsertUser->bindParam(':hash', $Hash, PDO::PARAM_STR);
                $InsertUser->execute();
            }
            else {
                $InsertUser=$cnx->prepare("INSERT INTO ".DB_PREFIX."compte_Admin (nom, email, created, hash) VALUES (:nom, :email, NOW(), :hash)");
                $InsertUser->bindParam(':nom', $Nom, PDO::PARAM_STR);
                $InsertUser->bindParam(':email', $Email, PDO::PARAM_STR);
                $InsertUser->bindParam(':hash', $Hash, PDO::PARAM_STR);
                $InsertUser->execute();
            }

            $RecupCreated=$cnx->prepare("SELECT (created) FROM ".DB_PREFIX."compte_Admin WHERE email=:email");
            $RecupCreated->bindParam(':email', $Email, PDO::PARAM_STR);
            $RecupCreated->execute();

            $DateCrea=$RecupCreated->fetch(PDO::FETCH_OBJ);
            $Salt=md5($DateCrea->created);
            $Mdp2=md5($Mdp2);
            $MdpCrypt=crypt($Mdp2, $Salt);

            $InsertMdp=$cnx->prepare("UPDATE ".DB_PREFIX."compte_Admin SET mdp=:mdpcrypt WHERE email=:email");
            $InsertMdp->bindParam(':mdpcrypt', $MdpCrypt, PDO::PARAM_STR);
            $InsertMdp->bindParam(':email', $Email, PDO::PARAM_STR);
            $InsertMdp->execute();

            if ($InsertMdp) {
                if (EnvoiNotification(SOCIETE, MAIL_DESTINATAIRE, "Validation d'inscription", $Body, $Email)==false) {
                    $Erreur="L'e-mail de confirmation n'a pu être envoyé, vérifiez que vous l'avez entré correctement !<br />"; 
                    ErreurLog($Erreur);
                } 
                else {
                    $Valid="Bonjour,<br />";
                    $Valid.="Merci de vous être inscrit<br />";
                    $Valid.="Un E-mail de confirmation vous a été envoyé à l'adresse suivante : ".$Email."<br />";
                    $Valid.="Veuillez valider votre adresse e-mail avant de vous connecter !<br />";
                    header("location:".HOME."/Admin/?valid=".urlencode($Valid));
                }
            }
        }
    }
}

?>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article class="ArticleAccueilAdmin">

<?php if (isset($Erreur)) { echo "<p><font color='#FF0000'>".urldecode($Erreur)."</font><BR />"; }
if (isset($Valid)) { echo "<p><font color='#009900'>".urldecode($Valid)."</font><BR />"; } ?>

<H1>Inscription</H1>
<p><form name="form_inscription" action="" method="POST">
<input type="text" name="nom" placeholder="Nom et Prénom" required="required"/> 
<br />
<input type="email" name="email" placeholder="Adresse E-mail" required="required"/> 
<br />
<input type="password" name="mdp" placeholder="Créer un mot de passe" required="required"/> 
<br />
<input type="password" name="mdp2" placeholder="Confirmer le mot de passe" required="required"/><br /><br />
<label class="col_1"></label>
<input type="submit" name="inscription" value="Inscription"/>
</form>

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>