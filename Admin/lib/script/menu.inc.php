<?php
require_once($_SERVER['DOCUMENT_ROOT']."/classes/redirect.class.php");
$redirect = new Redirect();
?>
<section>
    
<nav>
<div id="MenuGauche">

<div id="Center">
<?php if($redirect->__construct()) { ?>
<ul>
    <li><a href="<?php HOME ?>/Admin/Information/">Information</a>
    
    <li><a href="<?php HOME ?>/Admin/CompteAdmin/">Compte admin</a>
    
    <li><a href="<?php HOME ?>/Admin/Page/">Page</a><ul>
        <li><a href="<?php HOME ?>/Admin/Page/Nouveau/">Nouvelle page</a></li>
    </ul></li>

    <li><a href="<?php HOME ?>/Admin/Article/">Article</a><ul>
        <li><a href="<?php HOME ?>/Admin/Article/Nouveau/">Nouvel article</a></li>
    </ul></li>

    <li><a href="<?php HOME ?>/Admin/Logo/">Logo</a></li>

    <li><a href="<?php HOME ?>/Admin/Document/">Document</a></li>

    <li><a href="<?php HOME ?>/Admin/Social/">Réseaux Sociaux</a><ul>
        <li><a href="<?php HOME ?>/Admin/Social/Nouveau/">Nouveau Réseaux Sociaux</a></li>
    </ul></li>

    <li>Mailing<ul>
        <li><a href="<?php HOME ?>/Admin/Mailing/Envoyer/">Envoyer un mailing</a></li>
        <li><a href="<?php HOME ?>/Admin/Mailing/Historique/">Historique d'envoi</a></li>
        <li><a href="<?php HOME ?>/Admin/Mailing/Liste/">Liste de contact</a></li>
        <li><a href="<?php HOME ?>/Admin/Mailing/Diffusion/">Liste de diffusion</a></li>
        <li><a href="<?php HOME ?>/Admin/Mailing/Predefini/">E-mail enregistré</a></li>
        <li><a href="<?php HOME ?>/Admin/Mailing/Signature/">Signature enregistré</a></li>
        <li><a href="<?php HOME ?>/Admin/Mailing/Param/">E-mail de retour</a></li>
    </ul></li>

    <li><a href="<?php HOME ?>/Admin/loto/">Loto</a></li>

    <li><a href="<?php HOME ?>/Admin/Signature/">Signature numérique</a><ul>
    <li><a href="<?php HOME ?>/Admin/Signature/Tampon/">Mes Tampons</a></li>
    <li><a href="<?php HOME ?>/Admin/Signature/Tampon/Upload/">Ajouter un Tampon</a></li>
    </ul></li>
</ul>
<?php  } ?>
</div>

</div>
</nav>