<?php
require_once($_SERVER['DOCUMENT_ROOT']."/config/config.inc.php");

if (isset($_POST['inscription']) && $_POST['inscription'] == "Inscription") {
    $verif = new Admin($_POST['email'], $_POST['mdp'], $_POST['mdp2'], $_POST['nom']);
    if($verif->inscription()) {
        $valid = $verif;
    }
    else {
        $erreur = $verif;
    }
}

?>
<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); 
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); 
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); 
?>

<article class="ArticleAccueilAdmin">

<?php if (isset($erreur)) { echo '<div class="alert alert-danger" role="alert">'.$erreur."</div>"; }
if (isset($valid)) { echo '<div class="alert alert-success" role="alert">'.$valid."</div>"; } ?>

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