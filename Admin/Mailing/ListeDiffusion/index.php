<?php
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");

if ($Cnx_Admin!=TRUE) {
  header('location:'.HOME.'/Admin');
}

if (isset($_GET['erreur']) || isset($_GET['valid'])) {
      $Erreur=$_GET['erreur'];
      $Valid=$_GET['valid'];
}
$Groupe=urldecode($_GET['groupe']);

$Selection=$_POST['selection'];
$Compteur=count($Selection);

$SelectListeDiffusion=$cnx->prepare("SELECT * FROM ".DB_PREFIX."mailing_Liste ORDER BY nom ASC");
$SelectListeDiffusion->execute();

$SelectGroupe=$cnx->prepare("SELECT * FROM ".DB_PREFIX."mailing_Groupe WHERE id=:id");
$SelectGroupe->bindParam(':id', $Groupe, PDO::PARAM_STR);
$SelectGroupe->execute();
$GroupeLibele=$SelectGroupe->fetch(PDO::FETCH_OBJ);

if (isset($_POST['Modifier'])) {

    //On supprime toutes les email de la liste pour ajouter la nouvelle selection
    $delete=$cnx->prepare("DELETE FROM ".DB_PREFIX."mailing_Liste_Diffusion WHERE liste=:liste AND diffusion!=2");
    $delete->bindParam(':liste', $GroupeLibele->liste, PDO::PARAM_STR);
    $delete->execute();

    //On ajoute toutes les nouvelles adresses sauf ceux existante non diffuser
    for($u=0;$u<$Compteur;$u++) {
        $Select=$cnx->prepare("SELECT * FROM ".DB_PREFIX."mailing_Liste_Diffusion WHERE email=:email AND liste=:liste");
        $Select->bindParam(':liste', $GroupeLibele->liste, PDO::PARAM_STR);
        $Select->bindParam(':email', $Selection[$u], PDO::PARAM_STR);
        $Select->execute();
        $Count=$Select->rowCount();
        $Data=$Select->fetch(PDO::FETCH_OBJ);

        if($Count==0) {
            $Hash = md5(uniqid(rand(), true));
            $Ajout=$cnx->prepare("INSERT INTO ".DB_PREFIX."mailing_Liste_Diffusion (nom, prenom, email, liste, hash) VALUES(:nom, :prenom, :email, :liste, :hash)");
            $Ajout->bindParam(':nom', $Data->nom, PDO::PARAM_STR);
            $Ajout->bindParam(':prenom', $Data->prenom, PDO::PARAM_STR);
            $Ajout->bindParam(':email', $Selection[$u], PDO::PARAM_STR);
            $Ajout->bindParam(':liste', $GroupeLibele->liste, PDO::PARAM_STR);
            $Ajout->bindParam(':hash', $Hash, PDO::PARAM_STR);
            $Ajout->execute();
        }
    }

    $Valid="E-mail ajoutée avec succès";
    header('Location:'.HOME.'/Admin/Mailing/ListeDiffusion/?groupe='.$Groupe.'&valid='.urlencode($Valid));
}
?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article>
<?php if (isset($Erreur)) { echo "<font color='#FF0000'>".urldecode($Erreur)."</font><BR />"; }
if (isset($Valid)) { echo "<font color='#009900'>".urldecode($Valid)."</font><BR />"; } ?>

<H1>Mémo</H1>

Les élément du tableau sont détailler ci-dessous :
<ul>
<li><b>Nom :</b> Nom du client</li>  
<li><b>Prénom :</b> Prénom du client</li>  
<li><b>Email :</b> E-mail du client</li>   
<li><b>Liste :</b> Indique la liste auxquels il appartient.</li>
<li><b>Diffusion :</b> Indique l'etat de diffusion (0 : l'amail ne recevra pas la diffusion, 1 : l'email recevra la diffusion').</li>
<li><b>Ajouter / Supprimer :</b> Ajouter ou supprimer de la liste de diffusion.</li>
<li><b>Action :</b> Les actions a réaliser.</li>
</ul>

<H2>Liste de diffusion</H2>   

<table class="Admin" width=900>
<tr>
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
        Liste
    </th>
    <th>
        Diffusion
    </th>
    <th>
        Ajouter / Supprimer
    </th>
    <th>
        Action
    </th>
</tr>
<form name="liste" action="" method="POST">
<?php
while($ListeDiffusion=$SelectListeDiffusion->fetch(PDO::FETCH_OBJ)) { 
    $SelectDiffusionListe=$cnx->prepare("SELECT * FROM ".DB_PREFIX."mailing_Liste_Diffusion WHERE email=:email AND liste=:liste");
    $SelectDiffusionListe->bindParam(':email', $ListeDiffusion->email, PDO::PARAM_STR);
    $SelectDiffusionListe->bindParam(':liste', $GroupeLibele->liste, PDO::PARAM_STR);
    $SelectDiffusionListe->execute();
    $DiffusionListe=$SelectDiffusionListe->fetch(PDO::FETCH_OBJ);
    $CountDiffusionListe=$SelectDiffusionListe->rowCount();
    ?>
    <tr <?php if ($DiffusionListe->diffusion==1) { echo 'class="vert"'; } elseif ($DiffusionListe->diffusion==2) { echo 'class="gris"'; } else { echo 'class="rouge"'; } ?> >
        <td>
            <?php echo stripslashes($ListeDiffusion->nom); ?>
        </td>
        <td>
            <?php echo stripslashes($ListeDiffusion->prenom); ?>
        </td>
        <td>
            <?php echo stripslashes($ListeDiffusion->email); ?>
        </td>
        <td>
            <?php echo $DiffusionListe->liste ?>
        </td>
        <td>
            <?php echo $DiffusionListe->diffusion ?>
        </td>
        <td>
            <input type="checkbox" name="selection[]" value="<?php echo $ListeDiffusion->email; ?>" <?php if ($CountDiffusionListe==1) { echo "checked"; } ?>/>
        </td>
        <td>
            <?php
            if ($DiffusionListe->diffusion==1) { 
                echo '<a title="Désactiver" href="'.HOME.'/Admin/Mailing/ListeDiffusion/desactiver.php?id='.$DiffusionListe->id.'&groupe='.$Groupe.'"><img src="'.HOME.'/Admin/lib/img/desactiver.png" alt="Désactiver"></a>';
            } 
            else { 
                echo '<a title="Activer" href="'.HOME.'/Admin/Mailing/ListeDiffusion/activer.php?id='.$DiffusionListe->id.'&groupe='.$Groupe.'"><img src="'.HOME.'/Admin/lib/img/activer.png" alt="Activer"></a>';
            } 
            ?>
        </td>
    </tr>
<?php
}
?>
<tr>
    <td></td><td></td><td></td><td></td><td></td>
    <td>
        Tout cocher : <input type="checkbox" onclick="cocher1()" />
    </td>
    <td>
        <input type="submit" name="Modifier" value="Mettre à jour">
    </td>
</tr>
</form>
</table>

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>