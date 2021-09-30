<!DOCTYPE html>

<html lang="fr">
<head>

<meta charset="utf-8">

<title><?php echo $seo[1]->titre ?></title>

<meta name="author" content="<?php SOCIETE ?>">
<meta name="publisher" content="<?php PUBLISHER ?>">
<meta name="reply-to" content="<?php MAIL_DESTINATAIRE ?>">

<meta name="description" content="<?php echo $seo[1]->description ?>">
<meta name="keywords" content="<?php echo $seo[1]->keywords ?>">

<meta name="robots" content="index, follow">
<meta http-equiv="Cache-control" content="public">

<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="shortcut icon" href="<?php echo $Home; ?>/lib/img/logo.ico" type="image/x-icon"/>
<link rel="stylesheet" type="text/css" media="screen AND (max-width: 480px)" href="<?php HOME ?>/public/css/misenpatel.css"/>
<link rel="stylesheet" type="text/css" media="screen AND (min-width: 481px) AND (max-width: 1200px)" href="<?php HOME ?>/public/css/misenpatab.css"/>
<link rel="stylesheet" type="text/css" media="screen AND (min-width: 1201px)" href="<?php HOME ?>/public/css/misenpapc.css"/>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<?php require_once($_SERVER['DOCUMENT_ROOT']."/view/cookie.inc.php"); ?>

<?php
if (!$_SESSION['cookie'] || $_SESSION['cookie']==1) {
    // visiteur accepte les cookies
    ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-R359T1R5TJ"></script>
    <script type="text/javascript" src="<?php HOME ?>/public/js/googleAnalistic.js></script>
    <?php
}
?>

<!--[if !IE]><!-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8]>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<![endif]-->
<!--[if gt IE 8]>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<![endif]-->

<script type="text/javascript" src="<?php HOME ?>/public/js/menu.js"></script>
<script type="text/javascript" src="<?php HOME ?>/public/js/pageTop.js"></script>
<script type="text/javascript" src="<?php HOME ?>/public/js/skills.js"></script>
</head>