<?php

class Connexion {
    private $bdd;

    public function __construct($Ovh_serv_bDd=DB_HOST, $uTil_bDd_serv=DB_USER, $mDp_bDd_serv=DB_PWD)
    {
        $this->Ovh_serv_bDd = $Ovh_serv_bDd;
        $this->uTil_bDd_serv = $uTil_bDd_serv;
        $this->mDp_bDd_serv = $mDp_bDd_serv;
    }

    public function CnxBdd() {
        try {
            if ($this->bdd === null) {
                $bdd = new PDO($this->Ovh_serv_bDd, $this->uTil_bDd_serv, $this->mDp_bDd_serv);
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                $this->bdd = $bdd;
            }
            return $this->bdd;
        }
        catch (PDOException $e) {
            $erreur = "Erreur de connexion à la base de donnée, veuillez réessayer ultèrieurement !";
            return $erreur;
        }
    }

    public function Count($requete) {
		$req = $this->CnxBdd()->prepare($requete);
        $req->execute();
        $NbRows = $req->rowCount();
        return $NbRows;
	}

    public function Prepare($fonction, $requete, array $attribut) {
        $req = $this->CnxBdd()->prepare($requete);
        $req->execute($attribut);
        if ($fonction == "SELECT") {
            $data = $req->fetch(PDO::FETCH_OBJ);
            $NbRows=$req->rowCount();
            return array($NbRows, $data);
        }
        else {
            return true;
        }
    }

    public function Create($requete):bool {
		$this->CnxBdd()->query($requete);
        return true;
	}
}

?>