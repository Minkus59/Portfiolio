<!DOCTYPE html>

<html lang="fr">
<head>

<meta charset="utf-8">

<title><?php echo $SOEPage->titre ?></title>

<meta name="author" content="<?php echo $InfoDivers->publisher ?>">
<meta name="publisher" content="<?php echo $InfoDivers->publisher ?>">
<meta name="reply-to" content="michael-helinckx@hotmail.fr">

<meta name="description" content="<?php echo $SOEPage->description ?>">
<meta name="keywords" content="<?php echo $SOEPage->keywords ?>">

<meta name="robots" content="index, follow">
<meta http-equiv="Cache-control" content="public">

<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="shortcut icon" href="<?php echo $Home; ?>/lib/img/logo.ico" type="image/x-icon"/>
<link rel="stylesheet" type="text/css" media="screen AND (max-width: 480px)" href="<?php echo $Home; ?>/lib/css/misenpatel.css"/>
<link rel="stylesheet" type="text/css" media="screen AND (min-width: 481px) AND (max-width: 1200px)" href="<?php echo $Home; ?>/lib/css/misenpatab.css"/>
<link rel="stylesheet" type="text/css" media="screen AND (min-width: 1201px)" href="<?php echo $Home; ?>/lib/css/misenpapc.css"/>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<?php require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/cookie.inc.php"); ?>

<?php
if (!$_SESSION['cookie'] || $_SESSION['cookie']==1) {
    // visiteur accepte les cookies
    ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-R359T1R5TJ"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-R359T1R5TJ');
    </script>
    <?php
}

?>
</head>