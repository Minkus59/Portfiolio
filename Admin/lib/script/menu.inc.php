<section>
    
<nav>
<div id="MenuGauche">

<div id="Center">
<ul>
<?php if($Cnx_Admin===true) { ?>
    <li><a href="<?php echo HOME ?>/Admin/Information/">Information</a>
    
    <li><a href="<?php echo HOME ?>/Admin/CompteAdmin/">Compte admin</a>
    
    <li><a href="<?php echo HOME ?>/Admin/Page/">Page</a><ul>
        <li><a href="<?php echo HOME ?>/Admin/Page/Nouveau/">Nouvelle page</a></li>
    </ul></li>

    <li><a href="<?php echo HOME ?>/Admin/Menu/">Menu</a><ul>
        <li><a href="<?php echo HOME ?>/Admin/Menu/Nouveau/">Nouveau bouton</a></li>
    </ul></li>

    <li><a href="<?php echo HOME ?>/Admin/Article/">Article</a><ul>
        <li><a href="<?php echo HOME ?>/Admin/Article/Nouveau/">Nouvel article</a></li>
    </ul></li>

    <li><a href="<?php echo HOME ?>/Admin/Logo/">Logo</a></li>

    <li><a href="<?php echo HOME ?>/Admin/Document/">Document</a></li>

    <li><a href="<?php echo HOME ?>/Admin/Social/">Réseaux Sociaux</a><ul>
        <li><a href="<?php echo HOME ?>/Admin/Social/Nouveau/">Nouveau Réseaux Sociaux</a></li>
    </ul></li>

    <li>Mailing<ul>
        <li><a href="<?php echo HOME ?>/Admin/Mailing/Envoyer/">Envoyer un mailing</a></li>
        <li><a href="<?php echo HOME ?>/Admin/Mailing/Historique/">Historique d'envoi</a></li>
        <li><a href="<?php echo HOME ?>/Admin/Mailing/Liste/">Liste de contact</a></li>
        <li><a href="<?php echo HOME ?>/Admin/Mailing/Diffusion/">Liste de diffusion</a></li>
        <li><a href="<?php echo HOME ?>/Admin/Mailing/Predefini/">E-mail enregistré</a></li>
        <li><a href="<?php echo HOME ?>/Admin/Mailing/Signature/">Signature enregistré</a></li>
        <li><a href="<?php echo HOME ?>/Admin/Mailing/Param/">E-mail de retour</a></li>
    </ul></li>

    <li><a href="<?php echo HOME ?>/Admin/loto/">Loto</a></li>

    <li><a href="<?php echo HOME ?>/Admin/Signature/">Signature numérique</a><ul>
    <li><a href="<?php echo HOME ?>/Admin/Signature/Tampon/">Mes Tampons</a></li>
    <li><a href="<?php echo HOME ?>/Admin/Signature/Tampon/Upload/">Ajouter un Tampon</a></li>
    </ul></li>

<?php  } ?>
</ul>
</div>

</div>
</nav>