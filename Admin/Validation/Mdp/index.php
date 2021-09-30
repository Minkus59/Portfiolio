<?php
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");

$Erreur=$_GET['erreur'];
$Client=$_GET['id'];
$Hash=$_GET['hash'];

if (isset($_POST['Valider'])) {
        $Mdp=FiltreMDP('mdp');
        $Mdp2=FiltreMDP('mdp2');

        if ($Mdp[0]===false) {
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
    else {
        $RecupHash=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Admin_secu_mdp WHERE hash=:hash");
        $RecupHash->bindParam(':hash', $Hash, PDO::PARAM_STR);
        $RecupHash->execute();

        $NbRowsClient=$RecupHash->rowCount();
    
         if ($NbRowsClient!=1) {
                 $Erreur="Erreur de procédure, veuillez recommencer !<br />";
                 ErreurLog($Erreur);
         }
         else {
              $RecupCreated=$cnx->prepare("SELECT (created) FROM ".DB_PREFIX."compte_Admin WHERE email=:email");
              $RecupCreated->bindParam(':email', $Client, PDO::PARAM_STR);
              $RecupCreated->execute();

              $DateCrea=$RecupCreated->fetch(PDO::FETCH_OBJ);
              $Salt=md5($DateCrea->created);
              $MdpCrypt=crypt($Mdp2, $Salt);

              $InsertMdp=$cnx->prepare("UPDATE ".DB_PREFIX."compte_Admin SET mdp=:mdpcrypt WHERE email=:email");
              $InsertMdp->bindParam(':mdpcrypt', $MdpCrypt, PDO::PARAM_STR);
              $InsertMdp->bindParam(':email', $Client, PDO::PARAM_STR);
              $InsertMdp->execute();

              $DeleteSecu=$cnx->prepare("DELETE FROM ".DB_PREFIX."Admin_secu_mdp WHERE email=:email");
              $DeleteSecu->bindParam(':email', $Client, PDO::PARAM_STR);
              $DeleteSecu->execute();

              $Valid= "Votre mot de passe a bien été validé !<br />";
              $Valid.= "Vous pouvez dès à présent vous connecter !<br />";
              $Valid.= '<input type=button onClick=(parent.location="'.HOME.'/Admin/") value="Se connecter"><br/>';
        }
        
    }
}

?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article class="ArticleAccueilAdmin">

<?php if (isset($Erreur)) { echo "<p><font color='#FF0000'>".urldecode($Erreur)."</font><BR />"; }
if (isset($Valid)) { echo "<p><font color='#009900'>".urldecode($Valid)."</font><BR />"; }   

if ((isset($Client))&&(!empty($Client))) {
    $RecupHash=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Admin_secu_mdp WHERE hash=:hash");
    $RecupHash->bindParam(':hash', $Hash, PDO::PARAM_STR);
    $RecupHash->execute();
    $NbRowsHash=$RecupHash->rowCount();

    $VerifClient=$cnx->prepare("SELECT (email) FROM ".DB_PREFIX."Admin_secu_mdp WHERE email=:email");
    $VerifClient->bindParam(':email', $Client, PDO::PARAM_STR);
    $VerifClient->execute();
    $NbRowsClient=$VerifClient->rowCount();

    if ($NbRowsClient!=1) {
        echo "Aucune procédure de changement de mot de passe n'a été demander !<br />";
    }
    elseif ($NbRowsHash!=1) {
        echo "Erreur de procédure, veuillez recommencé !<br />";
    }

    else { ?>
        <form id="form_mdp" action="" method="POST">

        <input type="password" placeholder="Créer un mot de passe" name="mdp" required="required"/> 
        <br />
        <input type="password" placeholder="Confirmer le mot de passe" name="mdp2" required="required"/>
        <BR /><BR />
        <input type="submit" name="Valider" value="Valider"/>
        </form><?php 
    }
}
else {
    echo "Erreur !";
}
?>
</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>