<?php 

require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php"); 

if ($Cnx_Admin===false) {
  header('location:'.HOME.'/Admin');
}

$Erreur=$_GET['erreur'];
$Id=$_GET['id'];

if ((!empty($_GET['id']))&&(isset($_POST['oui']))) {

    $Update=$cnx->prepare("UPDATE ".DB_PREFIX."compte_Admin SET admin=0 WHERE id=:id");
    $Update->bindParam(':id', $Id, PDO::PARAM_INT);
    $Update->execute();

    header('Location:'.HOME.'/Admin/CompteAdmin/');
}

if ((!empty($_GET['id']))&&(isset($_POST['non']))) {  
    header('Location:'.HOME.'/Admin/CompteAdmin/');
}
?>  


<!-- ************************************
*** Script réalisé par Michael Helinckx ***
********* http://www.michael-helinckx.fr *************
**************************************-->

<!DOCTYPE html>
<html>
<head>
<title>Michael Helinckx - Accès PRO</title>

<META http-equiv="Content-Type" content="text/html;charset=utf-8"> 
<META name="robots" content="noindex, nofollow">

<META name="author".content="Michael Helinckx">
<META name="publisher".content="Helinckx Michael">
<META name="reply-to" content="michael.helinckx@hotmail.com">

<META name="viewport" content="width=device-width" >                                                            


<link rel="shortcut icon" href="<?php echo HOME ?>/Admin/lib/img/icone.ico">

<link rel="stylesheet" type="text/css" media="screen AND (max-width: 480px)" href="<?php echo HOME ?>/lib/css/misenpatel.css" />
<link rel="stylesheet" type="text/css" media="screen AND (min-width: 480px) AND (max-width: 960px)" href="<?php echo HOME ?>/lib/css/misenpatab.css" />
<link rel="stylesheet" type="text/css" media="screen AND (min-width: 960px)" href="<?php echo HOME ?>/lib/css/misenpapc.css" >
</head>

<body>
<CENTER>
<header>
<div id="int">
<?php require($_SERVER['DOCUMENT_ROOT']."/models/head.inc.php"); ?>
</div>
</header>
<div id="MenuAdmin">
<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>
</div>

<div id="Center">

<article>
        <?php
        if (isset($Erreur)) { echo '
            <div class="alert alert-danger" role="alert">
            '.$Erreur.'
        </div></p>'; }

        if (isset($Valid)) { echo '
            <div class="alert alert-success" role="alert">
            '.$Valid.'
            </div></p>'; }
        ?>

Etes-vous sur de vouloir enlever le statue d'administrateur de ce compte ? </p>

<TABLE width="300">
<form action="" method="POST">
<TR><TD align="center"><input name="oui" type="submit" value="OUI"></TD><TD align="center"><input name="non" type="submit" value="NON"/></TD></TR>
</form></TABLE>

</article>
</CENTER>
</body>

</html>