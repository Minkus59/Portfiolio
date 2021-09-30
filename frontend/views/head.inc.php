<!DOCTYPE html>

<html lang="fr">
<head>

<meta charset="utf-8">

<title><?php echo $seo[1][0]->titre ?></title>

<meta name="author" content="<?php PUBLISHER ?>">
<meta name="publisher" content="<?php PUBLISHER ?>">
<meta name="reply-to" content="<?php MAIL_DESTINATAIRE ?>">

<meta name="description" content="<?php echo $seo[1][0]->description ?>">
<meta name="keywords" content="<?php echo $seo[1][0]->keywords ?>">

<meta name="robots" content="index, follow">
<meta http-equiv="Cache-Control" content="public">

<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="shortcut icon" href="<?php HOME ?>/frontend/public/img/logo.ico" type="image/x-icon"/>
<link rel="stylesheet" type="text/css" media="screen AND (max-width: 480px)" href="<?php HOME ?>/frontend/public/css/misenpatel.css"/>
<link rel="stylesheet" type="text/css" media="screen AND (min-width: 481px) AND (max-width: 1200px)" href="<?php HOME ?>/frontend/public/css/misenpatab.css"/>
<link rel="stylesheet" type="text/css" media="screen AND (min-width: 1201px)" href="<?php HOME ?>/frontend/public/css/misenpapc.css"/>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<?php require_once($_SERVER['DOCUMENT_ROOT']."/frontend/models/cookie.inc.php"); ?>
</head>