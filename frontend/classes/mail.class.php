<?php

class Mail {

    public function __construct($Societe, $EmailServeur, $Objet, $Body, $Destinataire)
    {
        $this->societe = $Societe;
        $this->emailServeur = $EmailServeur;
        $this->objet = $Objet;
        $this->body = $Body;
        $this->destinataire = $Destinataire;
    }

    public function EnvoiNotification() {
        if ((isset($this->objet))&&(!empty($this->objet))) {      
            if ((isset($this->body))&&(!empty($this->body))) {  
                if (!preg_match("#^[a-z0-9._-]+@(dbmail|hotmail|live|msn|outlook).[a-z]{2,4}$#", $this->destinataire)) {
                    $passage_ligne = "\r\n";
                }
                else {
                    $passage_ligne = "\n";
                }

                $boundary = md5(uniqid(mt_rand()));
            
                $Entete = "From: \"$this->societe\" <$this->emailServeur>".$passage_ligne;
                $Entete.= "Reply-to: \"$this->societe\" <$this->emailServeur>".$passage_ligne;
                $Entete.= "MIME-Version: 1.0".$passage_ligne;
                $Entete.= "Content-Type: multipart/mixed; boundary=".$boundary." ".$passage_ligne;
                
                $message="--".$boundary.$passage_ligne;
                $message.="Content-Type: text/html; charset=utf-8".$passage_ligne; 
                $message.="Content-Transfer-Encoding: 8bit".$passage_ligne;

                $message.="<html><head>
                            <title>".$this->objet."</title>
                            </head>
                            <body>
                            ".$this->body."
                            </body>
                            </html>".$passage_ligne;

                if (mail($this->destinataire, $this->objet, $message, $Entete)) {
                    return true;
                }
                else {   
                    $erreur =  "L'e-mail n'a pu être envoyé, vérifiez que vous l'avez entré correctement ! <BR />
                    E-mail = ".$this->destinataire;
                    return $erreur;
                }
            }
            else {
                $erreur =  "Veuillez saisir un message !";
                return $erreur;
            }
        }
        else {
            $erreur =  "Veuillez saisir un objet de message !";
            return $erreur;
        }
    

    }
}
?>