<?php
class CreateTable {

    private $cnx;

    public function __construct($cnx)
    {
        $this->cnx = $cnx;
    }

    public function preparation():bool {
         try {
            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."compte_admin (
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

            
            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."admin_secu_mdp (
                `id` int(32) unsigned NOT NULL AUTO_INCREMENT,
                `hash` varchar(32) NOT NULL,
                `email` varchar(80) NOT NULL,
                `created` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
            
            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."document (
                `id` int(32) unsigned NOT NULL AUTO_INCREMENT,
                `libele` longtext DEFAULT NULL,
                `lien` longtext NOT NULL,
                `type` varchar(50) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
            
            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."logo (
                `id` int(32) unsigned NOT NULL AUTO_INCREMENT,
                `logo` longtext NOT NULL,
                `lien` longtext DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
            
            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."page (
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
            
            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."article (
                `id` int(32) unsigned NOT NULL AUTO_INCREMENT,
                `position` int(5) NOT NULL DEFAULT '1',
                `message` longtext NOT NULL,
                `page` longtext NOT NULL,
                `statue` int(1) NOT NULL DEFAULT '1',
                `created` int(11) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
            
            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."social (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `logo` longtext NOT NULL,
                `lien` longtext DEFAULT NULL,
                `statue` int(1) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
            
            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."mailing_groupe (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `liste` varchar(50) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
            
            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."mailing_historique (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `destinataire` longtext NOT NULL,
                `objet` longtext NOT NULL,
                `message` longtext NOT NULL,
                `retour` longtext NOT NULL,
                `type` int(5) DEFAULT NULL,
                `created` int(15) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
            
            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."mailing_liste (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `nom` longtext,
                `prenom` longtext,
                `email` longtext NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
            
            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."mailing_liste_diffusion (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `nom` longtext,
                `prenom` longtext,
                `email` varchar(80) NOT NULL,
                `liste` longtext NOT NULL,
                `diffusion` int(1) NOT NULL DEFAULT '1',
                `hash` varchar(32) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."mailing_parametre (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `email` varchar(80) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."mailing_predefini (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `libele` longtext NOT NULL,
                `mailing` longtext NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

            $this->cnx->Create("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."mailing_signature (
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `libelle` longtext NOT NULL,
                `signature` longtext NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

            return true;
        }
        catch (PDOException $e) {
            $erreur = "Une erreur est survenu lors de la création de l'espace admin, veuillez contactez le webmaster";
            return $erreur;
        }
    }

}