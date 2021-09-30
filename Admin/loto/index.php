<?php
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/requete.inc.php");

if ($Cnx_Admin===false) {
  header('location:'.$Home.'/Admin');
}

$Erreur=$_GET['erreur'];
$Valid=$_GET['valid']; 

if (isset($_POST['Rechercher'])) {
    $RechercheBoule_1=$_POST['boule_1'];
    $RechercheBoule_2=$_POST['boule_2'];
    $RechercheBoule_3=$_POST['boule_3'];
    $RechercheBoule_4=$_POST['boule_4'];
    $RechercheBoule_5=$_POST['boule_5'];
    $RechercheBoule_6=$_POST['boule_6'];

    $RecupRechercheGagnant=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE boule_1=:boule_1 AND boule_2=:boule_2 AND boule_3=:boule_3 AND boule_4=:boule_4 AND boule_5=:boule_5 AND numero_chance=:numero_chance");
    $RecupRechercheGagnant->bindParam(':boule_1', $RechercheBoule_1, PDO::PARAM_STR);
    $RecupRechercheGagnant->bindParam(':boule_2', $RechercheBoule_2, PDO::PARAM_STR);
    $RecupRechercheGagnant->bindParam(':boule_3', $RechercheBoule_3, PDO::PARAM_STR);
    $RecupRechercheGagnant->bindParam(':boule_4', $RechercheBoule_4, PDO::PARAM_STR);
    $RecupRechercheGagnant->bindParam(':boule_5', $RechercheBoule_5, PDO::PARAM_STR);
    $RecupRechercheGagnant->bindParam(':numero_chance', $RechercheBoule_6, PDO::PARAM_STR);
    $RecupRechercheGagnant->execute();
    $RechercheGagnant=$RecupRechercheGagnant->rowCount();
}

if (isset($_POST['Ajouter'])) {
    $ext = array('.csv', '.CSV');
    $ext_origin=strchr($_FILES['fichier']['name'], '.');
    
        if (!in_array($ext_origin, $ext)) {
           $Erreur="Ce n'est pas un fichier de type .csv<BR />";
        }
        else {
            //On supprime toutes les email de la liste pour ajouter la nouvelle selection
            $delete=$cnx->prepare("DELETE FROM ".$Prefix."loto");
            $delete->execute();

            //Process the CSV file
            $handle = fopen($_FILES['fichier']['tmp_name'], "r");
            while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
                $Boule_1= $data[4];
                $Boule_2= $data[5];
                $Boule_3= $data[6];
                $Boule_4 = $data[7];
                $Boule_5 = $data[8];
                $Numero_chance = $data[9];

                $Ajout=$cnx->prepare("INSERT INTO ".$Prefix."loto (boule_1, boule_2, boule_3, boule_4, boule_5, numero_chance) VALUES(:boule_1, :boule_2, :boule_3, :boule_4, :boule_5, :numero_chance)");
                $Ajout->bindParam(':boule_1', $Boule_1, PDO::PARAM_STR);
                $Ajout->bindParam(':boule_2', $Boule_2, PDO::PARAM_STR);
                $Ajout->bindParam(':boule_3', $Boule_3, PDO::PARAM_STR);
                $Ajout->bindParam(':boule_4', $Boule_4, PDO::PARAM_STR);
                $Ajout->bindParam(':boule_5', $Boule_5, PDO::PARAM_STR);
                $Ajout->bindParam(':numero_chance', $Numero_chance, PDO::PARAM_STR);
                $Ajout->execute();
            }
    
            $Valid="Numéro ajoutée avec succès";
            header('Location:'.$Home.'/Admin//loto/?valid='.urlencode($Valid));
        }
    }
?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article>
<?php if (isset($Erreur)) { echo "<p><font color='#FF0000'>".urldecode($Erreur)."</font><BR /><BR />"; }
if (isset($Valid)) { echo "<p><font color='#009900'>".urldecode($Valid)."</font><BR /><BR />"; }   ?>

<form name="form_ajout" action="" method="POST" enctype="multipart/form-data">
<input type="file" name="fichier" placeholder="Fichier CSV" required/><BR />
<BR />

<input type="submit" name="Ajouter" value="Ajouter" class="normal"/>
</form>

<p><a href="https://www.fdj.fr/jeux/jeux-de-tirage/loto/" target="_blank">Tirage loto</a></p>

<table>
<form name="recherche" action="" method="POST">
<H1>Rechercher une combinaison</H1>
<tr>
    <td>Boule 1</td>
    <td>Boule 2</td>
    <td>Boule 3</td>
    <td>Boule 4</td>
    <td>Boule 5</td>
    <td>Numéro chance</td>
</tr>
<tr>
    <td><input name="boule_1" type="text"/></td>
    <td><input name="boule_2" type="text"/></td>
    <td><input name="boule_3" type="text"/></td>
    <td><input name="boule_4" type="text"/></td>
    <td><input name="boule_5" type="text"/></td>
    <td><input name="boule_6" type="text"/></td>
</tr>
<tr>
<td colspan="6"><input type="submit" name="Rechercher" value="Rechercher"/></td>
</tr>

<tr>
<td colspan="6">
<?php if ($RechercheGagnant>=1) {
    echo "Numéro gagnant trouvé !!";
}
else {
    echo "Numéro non trouvé";
} ?>
</td>
</tr>
</form>
</table>

<H1>Taux de drop</H1>

<table>
<tr>
    <td>Numéro</td>
    <td>Boule 1</td>
    <td>Boule 2</td>
    <td>Boule 3</td>
    <td>Boule 4</td>
    <td>Boule 5</td>
    <td>Numéro chance</td>
</tr>

<?php
$NombreMaxBoule_1=array();
for($Boule=1;$Boule<=49;$Boule++) {
    $RecupNumero1=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE boule_1=:boule");
    $RecupNumero1->bindParam(':boule', $Boule, PDO::PARAM_STR);
    $RecupNumero1->execute();
    $PlusUtil1=$RecupNumero1->rowCount();

    $RecupNumero2=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE boule_2=:boule");
    $RecupNumero2->bindParam(':boule', $Boule, PDO::PARAM_STR);
    $RecupNumero2->execute();
    $PlusUtil2=$RecupNumero2->rowCount();

    $RecupNumero3=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE boule_3=:boule");
    $RecupNumero3->bindParam(':boule', $Boule, PDO::PARAM_STR);
    $RecupNumero3->execute();
    $PlusUtil3=$RecupNumero3->rowCount();
    
    $RecupNumero4=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE boule_4=:boule");
    $RecupNumero4->bindParam(':boule', $Boule, PDO::PARAM_STR);
    $RecupNumero4->execute();
    $PlusUtil4=$RecupNumero4->rowCount();
    
    $RecupNumero5=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE boule_5=:boule");
    $RecupNumero5->bindParam(':boule', $Boule, PDO::PARAM_STR);
    $RecupNumero5->execute();
    $PlusUtil5=$RecupNumero5->rowCount();

    $RecupNumero6=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE numero_chance=:boule");
    $RecupNumero6->bindParam(':boule', $Boule, PDO::PARAM_STR);
    $RecupNumero6->execute();
    $PlusUtil6=$RecupNumero6->rowCount();
 
    echo "<tr>
    <td>".$Boule."</td>
    <td>".$PlusUtil1."</td>
    <td>".$PlusUtil2."</td>
    <td>".$PlusUtil3."</td>
    <td>".$PlusUtil4."</td>
    <td>".$PlusUtil5."</td>
    <td>".$PlusUtil6."</td>
    </tr>";
    $NombreMaxBoule_1[]=$PlusUtil1;
    $NombreMaxBoule_2[]=$PlusUtil2;
    $NombreMaxBoule_3[]=$PlusUtil3;
    $NombreMaxBoule_4[]=$PlusUtil4;
    $NombreMaxBoule_5[]=$PlusUtil5;
    $NombreMaxBoule_6[]=$PlusUtil6;
}

$MaxBoule_1=max($NombreMaxBoule_1);
$MaxBoule_2=max($NombreMaxBoule_2);
$MaxBoule_3=max($NombreMaxBoule_3);
$MaxBoule_4=max($NombreMaxBoule_4);
$MaxBoule_5=max($NombreMaxBoule_5);
$MaxBoule_6=max($NombreMaxBoule_6);

for($Boule=1;$Boule<=49;$Boule++) {
    $RecupNumero1=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE boule_1=:boule");
    $RecupNumero1->bindParam(':boule', $Boule, PDO::PARAM_STR);
    $RecupNumero1->execute();
    $PlusUtil1=$RecupNumero1->rowCount();

    if ($MaxBoule_1==$PlusUtil1) {
        $NumBoule_1=$Boule;
    }

    $RecupNumero2=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE boule_2=:boule");
    $RecupNumero2->bindParam(':boule', $Boule, PDO::PARAM_STR);
    $RecupNumero2->execute();
    $PlusUtil2=$RecupNumero2->rowCount();

    if ($MaxBoule_2==$PlusUtil2) {
        $NumBoule_2=$Boule;
    }

    $RecupNumero3=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE boule_3=:boule");
    $RecupNumero3->bindParam(':boule', $Boule, PDO::PARAM_STR);
    $RecupNumero3->execute();
    $PlusUtil3=$RecupNumero3->rowCount();

    if ($MaxBoule_3==$PlusUtil3) {
        $NumBoule_3=$Boule;
    }
    
    $RecupNumero4=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE boule_4=:boule");
    $RecupNumero4->bindParam(':boule', $Boule, PDO::PARAM_STR);
    $RecupNumero4->execute();
    $PlusUtil4=$RecupNumero4->rowCount();

    if ($MaxBoule_4==$PlusUtil4) {
        $NumBoule_4=$Boule;
    }
    
    $RecupNumero5=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE boule_5=:boule");
    $RecupNumero5->bindParam(':boule', $Boule, PDO::PARAM_STR);
    $RecupNumero5->execute();
    $PlusUtil5=$RecupNumero5->rowCount();

    if ($MaxBoule_5==$PlusUtil5) {
        $NumBoule_5=$Boule;
    }

    $RecupNumero6=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE numero_chance=:boule");
    $RecupNumero6->bindParam(':boule', $Boule, PDO::PARAM_STR);
    $RecupNumero6->execute();
    $PlusUtil6=$RecupNumero6->rowCount();

    if ($MaxBoule_6==$PlusUtil6) {
        $NumBoule_6=$Boule;
    }
}

$RecupNumeroGagnant=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE boule_1=:boule_1 AND boule_2=:boule_2 AND boule_3=:boule_3 AND boule_4=:boule_4 AND boule_5=:boule_5 AND numero_chance=:numero_chance");
$RecupNumeroGagnant->bindParam(':boule_1', $NumBoule_1, PDO::PARAM_STR);
$RecupNumeroGagnant->bindParam(':boule_2', $NumBoule_2, PDO::PARAM_STR);
$RecupNumeroGagnant->bindParam(':boule_3', $NumBoule_3, PDO::PARAM_STR);
$RecupNumeroGagnant->bindParam(':boule_4', $NumBoule_4, PDO::PARAM_STR);
$RecupNumeroGagnant->bindParam(':boule_5', $NumBoule_5, PDO::PARAM_STR);
$RecupNumeroGagnant->bindParam(':numero_chance', $NumBoule_6, PDO::PARAM_STR);
$RecupNumeroGagnant->execute();
$NumeroGagnant=$RecupNumeroGagnant->rowCount();

?>
</table>

<table>
<H1>Meilleurs drop</H1>
<tr>
    <td>Boule 1</td>
    <td>Boule 2</td>
    <td>Boule 3</td>
    <td>Boule 4</td>
    <td>Boule 5</td>
    <td>numero_chance</td>
</tr>
<tr>
    <td>
    <?php echo $NumBoule_1; ?>
    </td>
    <td>
    <?php echo $NumBoule_2; ?>
    </td>
    <td>
    <?php echo $NumBoule_3; ?>
    </td>
    <td>
    <?php echo $NumBoule_4; ?>
    </td>
    <td>
    <?php echo $NumBoule_5; ?>
    </td>
    <td>
    <?php echo $NumBoule_6; ?>
    </td>
</tr>
</table>

<?php if ($NumeroGagnant>=1) {
    echo "Numéro gagnant trouvé !!";
}
else {
    echo "Numéro non tombé";
} ?>
<BR /><BR />

<H1>Recherche de Drop double</H1>

<table>
<?php
$recupTotal=$cnx->prepare("SELECT * FROM ".$Prefix."loto");
$recupTotal->bindParam(':boule', $Boule, PDO::PARAM_STR);
$recupTotal->execute();

while($Total=$recupTotal->fetch(PDO::FETCH_OBJ)) {
    $RecupTotalGagnant=$cnx->prepare("SELECT * FROM ".$Prefix."loto WHERE boule_1=:boule_1 AND boule_2=:boule_2 AND boule_3=:boule_3 AND boule_4=:boule_4 AND boule_5=:boule_5 AND numero_chance=:numero_chance");
    $RecupTotalGagnant->bindParam(':boule_1', $Total->boule_1, PDO::PARAM_STR);
    $RecupTotalGagnant->bindParam(':boule_2', $Total->boule_2, PDO::PARAM_STR);
    $RecupTotalGagnant->bindParam(':boule_3', $Total->boule_3, PDO::PARAM_STR);
    $RecupTotalGagnant->bindParam(':boule_4', $Total->boule_4, PDO::PARAM_STR);
    $RecupTotalGagnant->bindParam(':boule_5', $Total->boule_5, PDO::PARAM_STR);
    $RecupTotalGagnant->bindParam(':numero_chance', $Total->numero_chance, PDO::PARAM_STR);
    $RecupTotalGagnant->execute();
    $TotalGagnant=$RecupTotalGagnant->rowCount();

    if ($TotalGagnant>=3) {
        echo "<tr><td>".$Total->boule_1." - ".$Total->boule_2." - ".$Total->boule_3." - ".$Total->boule_4." - ".$Total->boule_5." - ".$Total->numero_chance."</td><td> -> Très bon drop trouvé !</td></tr>";
    }
    elseif ($TotalGagnant==2) {
        echo "<tr><td>".$Total->boule_1." - ".$Total->boule_2." - ".$Total->boule_3." - ".$Total->boule_4." - ".$Total->boule_5." - ".$Total->numero_chance."</td><td> -> Drop trouvé !</td></tr>";
    }
    else {
        
    }
}
?>
</table>

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>