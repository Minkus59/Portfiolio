<?php
class Redirect {
    public function __construct() {
        if (isset($_SESSION['cnx'])) {
            switch($_SESSION['type']) {
            case "admin":
                $cnx = new Connexion();
                $VerifSessionAdmin=$cnx->prepare("SELECT", "SELECT * FROM ".DB_PREFIX."_compte_Admin WHERE hash=:hash", array(':hash' => $_SESSION['cnx']));

                if ((isset($_SESSION['cnx']))&&($VerifSessionAdmin[1]->admin==1)) {
                    if($VerifSessionAdmin[1]->admin==1) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                else {
                    return false;
                }
                break;
            case "member":
                $cnx = new Connexion();
                $VerifSession=$cnx->prepare("SELECT", "SELECT * FROM ".DB_PREFIX."_compte WHERE hash=:hash", array(':hash' => $_SESSION['cnx']));

                if ((isset($_SESSION['cnx']))&&($_SESSION['cnx'] == $VerifSession[1]->hash)) {
                    return true;
                }
                else {
                    return false;
                }
                break;
            }
        }
        else {
            return false;
        }
    }
}
?>