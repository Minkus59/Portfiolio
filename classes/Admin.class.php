<?php

class Admin {
    private $nom;
    private $email;
    private $mdp;
    private $mdp2;

    public function __construct($email, $mdp, $mdp2, $nom)
    {
        $this->nom = $nom;
        $this->email = $email;
        $this->mdp = trim($mdp);
        $this->mdp2 = trim($mdp2);
    }

    private function VerifMdp() {
        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/', $this->mdp)) { 
            $erreur =  "Un mot de passe valide aura : <BR />
            - aucun espace <BR />
            - de 8 à 15 caractères <BR />
            - au moins une lettre minuscule <BR />
            - au moins une lettre majuscule <BR />
            - au moins un chiffre <BR />
            - au moins un de ces caractères spéciaux: $ @ % * + - _ !";
        }
        else {
            return $this->mdp;
            return true;
        }
    }
      
    private function verifEmail() {
        $emailClean=filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $emailClean=filter_var($emailClean, FILTER_SANITIZE_EMAIL);
        
        if(!filter_var($emailClean, FILTER_VALIDATE_EMAIL)) { 
            $erreur =  "L'adresse e-mail n'est pas conforme !";
        }
        else {  
            return $emailClean;
            return true;
        }
    }

    public function inscription() {
        $Now=time();

        if ($this->verifEmail()) {
            if ($this->VerifMdp()) {
                if ($this->VerifMdp() != $this->mdp2) {
                    $erreur =  "les mots de passe ne sont pas identique !";
                }
                else {                    
                    $cnx = new Connexion();
                    $count = $cnx->Count("SELECT * FROM ".DB_PREFIX."compte_admin");
                    if ($count == 0) {
                        $createTable = new CreateTable($cnx);
                        $createTable->preparation();
                        
                        $Accueil="/";
                        $Contact="/Contact/";
            
                        $cnx->Prepare("INSERT", "INSERT INTO ".DB_PREFIX."page (libele, lien, position, statue, created) VALUES('Accueil', :lien, '0', '2', :created)", 
                        array(':lien'=>$Accueil, 
                              ':created'=>$Now));
            
                        $cnx->Prepare("INSERT", "INSERT INTO ".DB_PREFIX."page (libele, lien, position, statue, created) VALUES('Contact', :lien, '0', '1', :created)", 
                        array(':lien'=>$Contact, 
                            ':created'=>$Now));

                        $DefaultLogo=HOME."/lib/logo/logoType.png";
                        $cnx->Prepare("INSERT", "INSERT INTO ".DB_PREFIX."logo (logo) VALUES(:logo)", 
                        array(':logo'=>$DefaultLogo));

                        $DefaultHeader=HOME."/lib/header/headerType.jpg";
                        $cnx->Prepare("INSERT", "INSERT INTO ".DB_PREFIX."logo (logo) VALUES(:logo)", 
                        array(':logo'=>$DefaultHeader));

                        $Requete = "INSERT INTO ".DB_PREFIX."compte_admin (nom, email, mdp, admin, activate, valider, created) VALUES (:nom, :email, :mdp, '1', '1', '1', NOW())";
                    }
                    elseif ($count==1) {
                        $Requete = "INSERT INTO ".DB_PREFIX."compte_admin (nom, email, mdp, admin, created) VALUES (:nom, :email, :mdp, '1', NOW())";
                    }
                    else {
                        $Requete = "INSERT INTO ".DB_PREFIX."compte_admin (nom, email, mdp, created) VALUES (:nom, :email, :mdp, NOW())";
                    }

                    $data = $cnx->Prepare("SELECT", "SELECT * FROM ".DB_PREFIX."compte_admin WHERE email=:email", 
                    array(':email'=>$this->email));

                    if ($data[0] > 0) {
                        $erreur =  "Cette adresse E-mail existe déjà !<br />";
                    }
                    else {
                        $mdpCrypt= hash("SHA256", $this->mdp, false);
                        $Insert = $cnx->Prepare("INSERT", $Requete, 
                        array(':nom'=>$this->nom,
                            ':email'=>$this->email,
                            ':mdp' => $mdpCrypt));

                        if ($Insert) {
                            $Body ="<H1>Validation d'inscription</H1></font>          
                            Veuillez cliquer sur le lien suivant pour valider votre inscription.</p>
                            <a href='".HOME."/Admin/Validation/?id=".$this->email."&Valid=1'>Cliquez ici</a></p>
                            ____________________________________________________</p>
                            Cordialement ".SOCIETE."<br />
                            ".HOME."</p>
                            <font color='#FF0000'>Cet e-mail contient des informations confidentielles et / ou protégées par la loi. 
                            Si vous n'en êtes pas le véritable destinataire ou si vous l'avez reçu par erreur, informez-en immédiatement son expéditeur et détruisez ce message. 
                            La copie et le transfert de cet e-mail sont strictement interdits.</font>";

                            $mail = new Mail(SOCIETE, MAIL_SERVER, "Validation d'inscription", $Body, $this->email);
                            if ($mail->EnvoiNotification()) {
                                $valid="Bonjour,<br />";
                                $valid.="Merci de vous être inscrit<br />";
                                $valid.="Un E-mail de confirmation vous a été envoyé à l'adresse suivante : ".$this->email."<br />";
                                $valid.="Veuillez valider votre adresse e-mail avant de vous connecter !<br />";
                                return $valid;
                            }
                        }
                        else {
                            $erreur = "une erreur est survenue, veuillez contacter le webmaster";
                            return $erreur;
                        }
                    }
                }
            }
        }
    }

    public function cnx() {
        if ($this->verifEmail()) {
            if ($this->VerifMdp()) {
                
                
                
                
                
                
                
                return true;
            }
        }
    }

}

?>