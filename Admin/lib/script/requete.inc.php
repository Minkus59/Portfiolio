<?php 
$Section= explode("?",$_SERVER['REQUEST_URI']);
$PageActu=$Section[0];

try {
    //Recup des articles de la page courante
    $RecupArticle=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Article WHERE page=:page AND statue='1' ORDER BY position ASC");
    $RecupArticle->bindParam(':page', $PageActu, PDO::PARAM_STR);
    $RecupArticle->execute();
    $Count=$RecupArticle->rowcount();

    //Recup du menu principal actif
    $SelectPageActif=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Page WHERE statue IN ('1', '2') AND sous_menu='0' ORDER BY position ASC");
    $SelectPageActif->execute();

    //Recup du menu footer actif
    $SelectPageActifFooter=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Page WHERE statue IN ('1', '2') AND sous_menu='0' ORDER BY position");
    $SelectPageActifFooter->execute();

    //Recup du referencement
    $SelectPageSOE=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Page WHERE lien=:page");
    $SelectPageSOE->bindParam(':page', $PageActu, PDO::PARAM_STR);
    $SelectPageSOE->execute();
    $SOEPage=$SelectPageSOE->fetch(PDO::FETCH_OBJ);

    //Recup du logo principal
    $SelectParamLogoHeader=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Logo WHERE id='2'");    
    $SelectParamLogoHeader->execute(); 
    $ParamLogoHeader=$SelectParamLogoHeader->fetch(PDO::FETCH_OBJ);

    //Recup du destinataire mailing
    $RecupEmailDestinataire=$cnx->prepare("SELECT * FROM ".DB_PREFIX."mailing_Parametre");
    $RecupEmailDestinataire->execute();
    $EmailDestinataire=$RecupEmailDestinataire->fetch(PDO::FETCH_OBJ);

    //Recup du Information divers
    $RecupinfoDivers=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Information");
    $RecupinfoDivers->execute();
    $InfoDivers=$RecupinfoDivers->fetch(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    die("Erreur de requete !");
}

?>