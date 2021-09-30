<?php
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/requete.inc.php");

if ($Cnx_Admin!=TRUE) {
  header('location:'.$Home.'/Admin');
}

$Erreur=$_GET['erreur'];
$Valid=$_GET['valid'];

//Moteur de recherche
if (isset($_POST['MoteurRecherche'])) {
    if ($_POST['RechercheCategorie']!="") {
        $RechercheCategorie=trim($_POST['RechercheCategorie']);
        $SelectListe=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Liste WHERE categorie=:categorie");
        $SelectListe->execute(array(':categorie'=> $RechercheCategorie)); 
    }
    elseif (!empty($_POST['RechercheAlumni'])) {
        $RechercheAlumni=trim($_POST['RechercheAlumni']);
        $SelectListe=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Liste WHERE alumni LIKE :alumni");
        $SelectListe->execute(array(':alumni' => "%".$RechercheAlumni."%")); 
    }
    elseif ($_POST['RechercheStatut']!="") {
        $RechercheStatut=trim($_POST['RechercheStatut']);
        $SelectListe=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Liste WHERE statut=:statut");
        $SelectListe->execute(array(':statut' =>$RechercheStatut)); 
    }
    elseif (!empty($_POST['RechercheNom'])) {
        $RechercheNom=trim($_POST['RechercheNom']);
        $SelectListe=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Liste WHERE nom LIKE :nom");
        $SelectListe->execute(array(':nom' => "%".$RechercheNom."%")); 
    }
    elseif (!empty($_POST['RecherchePrenom'])) {
        $RecherchePrenom=trim($_POST['RecherchePrenom']);
        $SelectListe=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Liste WHERE prenom LIKE :prenom");
        $SelectListe->execute(array(':prenom' => "%".$RecherchePrenom."%")); 
    }
    elseif (!empty($_POST['RechercheEmail'])) {
        $RechercheEmail=trim($_POST['RechercheEmail']);
        $SelectListe=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Liste WHERE email=:email");
        $SelectListe->execute(array(':email' => $RechercheEmail)); 
    }
    else {
        $SelectListe=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Liste ORDER BY nom ASC");
        $SelectListe->execute();
    }
}
else {
    $SelectListe=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Liste ORDER BY nom ASC");
    $SelectListe->execute();
}

$SelectGroupe=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Groupe");
$SelectGroupe->execute();

if (isset($_POST['Ajouter'])) {
$ext = array('.csv', '.CSV');
$ext_origin=strchr($_FILES['fichier']['name'], '.');

    if (!in_array($ext_origin, $ext)) {
       $Erreur="Ce n'est pas un fichier de type .csv<BR />";
    }
    else {
        //Process the CSV file
        $handle = fopen($_FILES['fichier']['tmp_name'], "r");
        $Compteur=0;
        while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
            if($Compteur!=0) {
                $Categorie= $data[0];
                $Alumni= $data[1];
                $Statut= $data[2];
                $Nom = $data[3];
                $Prenom = $data[4];
                $Email = $data[5];

                $Select=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Liste WHERE email=:email");
                $Select->bindParam(':email', $Email, PDO::PARAM_STR);
                $Select->execute();
                $Count=$Select->rowCount();

                if($Count==0) {
                    $Ajout=$cnx->prepare("INSERT INTO ".$Prefix."_mailing_Liste (categorie, alumni, statut, nom, prenom, email) VALUES(:categorie, :alumni, :statut, :nom, :prenom, :email)");
                    $Ajout->bindParam(':categorie', $Categorie, PDO::PARAM_STR);
                    $Ajout->bindParam(':alumni', $Alumni, PDO::PARAM_STR);
                    $Ajout->bindParam(':statut', $Statut, PDO::PARAM_STR);
                    $Ajout->bindParam(':nom', $Nom, PDO::PARAM_STR);
                    $Ajout->bindParam(':prenom', $Prenom, PDO::PARAM_STR);
                    $Ajout->bindParam(':email', $Email, PDO::PARAM_STR);
                    $Ajout->execute();
                }
            }
            $Compteur++;
        }

        $Valid="E-mail ajoutée avec succès";
        header('Location:'.$Home.'/Admin/Mailing/Liste/?valid='.urlencode($Valid));
    }
}

if (isset($_POST['inserer'])) {
    $Categorie= $_POST['categorie'];
    $Alumni= $_POST['alumni'];
    $Statut= $_POST['statut'];
    $Nom = $_POST['nom'];
    $Prenom = $_POST['prenom'];
    $Email = $_POST['email'];

    $Select=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Liste WHERE email=:email");
    $Select->bindParam(':email', $Email, PDO::PARAM_STR);
    $Select->execute();
    $Count=$Select->rowCount();

    if ($Count!=0) {
       $Erreur="Ce contact existe déjà !";
    }
    else {
        $Ajout=$cnx->prepare("INSERT INTO ".$Prefix."_mailing_Liste (categorie, alumni, statut, nom, prenom, email) VALUES(:categorie, :alumni, :statut, :nom, :prenom, :email)");
        $Ajout->bindParam(':categorie', $Categorie, PDO::PARAM_STR);
        $Ajout->bindParam(':alumni', $Alumni, PDO::PARAM_STR);
        $Ajout->bindParam(':statut', $Statut, PDO::PARAM_STR);
        $Ajout->bindParam(':nom', $Nom, PDO::PARAM_STR);
        $Ajout->bindParam(':prenom', $Prenom, PDO::PARAM_STR);
        $Ajout->bindParam(':email', $Email, PDO::PARAM_STR);
        $Ajout->execute();   

        $Valid="E-mail ajoutée avec succès";
        header('Location:'.$Home.'/Admin/Mailing/Liste/?valid='.urlencode($Valid));  
    }
}

if (isset($_POST['Supprimer'])) {
    $Selection=$_POST['selection'];
    $Compteur=count($Selection);

    for($u=0;$u<$Compteur;$u++) {
        $delete=$cnx->prepare("DELETE FROM ".$Prefix."_mailing_Liste WHERE id=:id");
        $delete->bindParam(':id', $Selection[$u], PDO::PARAM_STR);
        $delete->execute();
    }

    $Valid="E-mail supprimer avec succès";
    header('Location:'.$Home.'/Admin/Mailing/Liste/?valid='.urlencode($Valid));
}

if (isset($_POST['ExporterListe'])) {
    $Selection=$_POST['selection'];
    $Compteur=count($Selection);

    for($u=0;$u<$Compteur;$u++) {
        $delete=$cnx->prepare("DELETE FROM ".$Prefix."_mailing_Liste WHERE id=:id");
        $delete->bindParam(':id', $Selection[$u], PDO::PARAM_STR);
        $delete->execute();
    }

    $Valid="E-mail supprimer avec succès";
    header('Location:'.$Home.'/Admin/Mailing/Liste/?valid='.urlencode($Valid));
}

?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article>
<?php if (isset($Erreur)) { echo "<font color='#FF0000'>".urldecode($Erreur)."</font><BR />"; }
if (isset($Valid)) { echo "<font color='#009900'>".urldecode($Valid)."</font><BR />"; } ?>
   
<H1>Liste des contacts</H1>   

Ajout d'adresse email <BR />

<form name="form_ajout" action="" method="POST" enctype="multipart/form-data">
<input type="file" name="fichier" placeholder="Fichier CSV" required/><BR />
(Fichier .CSV comportant les colonnes "categorie", "alumni", "statut", "nom", "prenom" et "email", la preniere ligne ne sera pas importé, encodage:UTF-8)
<BR />

<input type="submit" name="Ajouter" value="Ajouter"/>
</form>

<form name="form_export" action="/Admin/Mailing/Liste/Export.php" method="POST">
<input type="submit" name="Exporter" value="Exporter"/>
</form>

<BR /><HR /><BR />

<table class="Admin" width=900>
<tr>
    <th>
        Catégorie
    </th>
    <th>
        Alumni
    </th>
    <th>
        Statut
    </th>
    <th>
        Nom
    </th>
    <th>
        Prénom
    </th>
    <th>
        Email
    </th>
    <th>
        Action
    </th>
</tr>
<form name="form_recherche" action="" method="POST">
<tr>
    <th>
        <select name="RechercheCategorie" class="mini">
        <option value="">-- --</option>
        <option value="Mi">Mi</option>
        <option value="Pers morale">Pers morale</option>
        <option value="Membre associé">Membre associé</option>
        <option value="Junior">Junior</option>
        <option value="Institution">Institution</option>
        </select>
    </th>
    <th>
        <input type="text" name="RechercheAlumni" class="Moyen"/>
    </th>
    <th>
        <select name="RechercheStatut" class="mini">
        <option value="">-- --</option>
        <option value="Président">Président</option>
        <option value="Trésorier">Trésorier</option>
        <option value="Secrétaire">Secrétaire</option>
        <option value="Webmaster">Webmaster</option>
        </select>
    </th>
    <th>
        <input type="text" name="RechercheNom" class="Moyen"/>
    </th>
    <th>
        <input type="text" name="RecherchePrenom" class="Moyen"/>
    </th>
    <th>
        <input type="text" name="RechercheEmail" class="Moyen"/>
    </th>
    <th>
        <input type="submit" name="MoteurRecherche" value="Rechercher"/>
    </th>
</tr>
</form>
<form name="FormAjout" action="" method="POST">
<tr>
    <th>
        <select name="categorie" class="mini">
        <option value="">-- --</option>
        <option value="Mi">Mi</option>
        <option value="Pers morale">Pers morale</option>
        <option value="Membre associé">Membre associé</option>
        <option value="Junior">Junior</option>
        <option value="Institution">Institution</option>
        </select>
    </th>
    <th>
        <input type="text" name="alumni" class="Moyen"/>
    </th>
    <th>
        <select name="statut" class="mini">
        <option value="">-- --</option>
        <option value="Président">Président</option>
        <option value="Trésorier">Trésorier</option>
        <option value="Secrétaire">Secrétaire</option>
        <option value="Webmaster">Webmaster</option>
        </select>
    </th>
    <th>
        <input type="text" name="nom" class="Moyen"/>
    </th>
    <th>
        <input type="text" name="prenom" class="Moyen"/>
    </th>
    <th>
        <input type="text" name="email" class="Moyen"/>
    </th>
    <th>
        <input type="submit" name="inserer" value="Ajouter"/>
    </th>
</tr>

</form>
<form name="liste" action="/Admin/Mailing/Liste/Liste.php" method="POST">

<?php
while($Liste=$SelectListe->fetch(PDO::FETCH_OBJ)) { ?>
    <tr>
        <td>
            <?php echo $Liste->categorie; ?>
        </td>
        <td>
            <?php echo $Liste->alumni; ?>
        </td>
        <td>
            <?php echo $Liste->statut; ?>
        </td>
        <td>
            <?php echo $Liste->nom; ?>
        </td>
        <td>
            <?php echo $Liste->prenom; ?>
        </td>
        <td>
            <?php echo $Liste->email; ?>
        </td>
        <td>
            <input type="checkbox" name="selection[]" value="<?php echo $Liste->id; ?>"/>
        </td>
        <td>
            <a href="<?php echo $Home; ?>/Admin/Mailing/Liste/Modifier/?id=<?php echo $Liste->id; ?>"><img src="<?php echo $Home; ?>/Admin/lib/img/modifier.png"></a>
        </td>
    </tr>
<?php
}
?>

<tr>
    <td></td><td></td><td></td><td></td><td></td><td></td>
    <td>
        Tout cocher : <input type="checkbox" onclick="cocher1()" />
    </td>
    <td></td>
</tr>

<tr>
    <td></td><td></td><td></td><td></td><td></td><td></td>
    <td>
        <input type="submit" name="Supprimer" value="Supprimer">
    </td>
    <td></td>
</tr>

<tr>
    <td></td><td></td><td></td><td></td><td></td><td></td>
    <td>
        <input type="submit" name="ExporterListe" value="Exporter">
    </td>
    <td></td>
</tr>

</form>

</table>

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>