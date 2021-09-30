<section>
    
<nav>
<div id="MenuGauche">

<div id="Center">
<ul>
<?php if($Cnx_Admin===true) { ?>
    <li><a href="<?php echo $Home; ?>/Admin/Information/">Information</a>
    
    <li><a href="<?php echo $Home; ?>/Admin/CompteAdmin/">Compte admin</a>
    
    <li><a href="<?php echo $Home; ?>/Admin/Page/">Page</a><ul>
        <li><a href="<?php echo $Home; ?>/Admin/Page/Nouveau/">Nouvelle page</a></li>
    </ul></li>

    <li><a href="<?php echo $Home; ?>/Admin/Article/">Article</a><ul>
        <li><a href="<?php echo $Home; ?>/Admin/Article/Nouveau/">Nouvel article</a></li>
    </ul></li>

    <li><a href="<?php echo $Home; ?>/Admin/Logo/">Logo</a></li>

    <li><a href="<?php echo $Home; ?>/Admin/Document/">Document</a></li>

    <li><a href="<?php echo $Home; ?>/Admin/Social/">Réseaux Sociaux</a><ul>
        <li><a href="<?php echo $Home; ?>/Admin/Social/Nouveau/">Nouveau Réseaux Sociaux</a></li>
    </ul></li>

    <li>Mailing<ul>
        <li><a href="<?php echo $Home; ?>/Admin/Mailing/Envoyer/">Envoyer un mailing</a></li>
        <li><a href="<?php echo $Home; ?>/Admin/Mailing/Historique/">Historique d'envoi</a></li>
        <li><a href="<?php echo $Home; ?>/Admin/Mailing/Liste/">Liste de contact</a></li>
        <li><a href="<?php echo $Home; ?>/Admin/Mailing/Diffusion/">Liste de diffusion</a></li>
        <li><a href="<?php echo $Home; ?>/Admin/Mailing/Predefini/">E-mail enregistré</a></li>
        <li><a href="<?php echo $Home; ?>/Admin/Mailing/Signature/">Signature enregistré</a></li>
        <li><a href="<?php echo $Home; ?>/Admin/Mailing/Param/">E-mail de retour</a></li>
    </ul></li>

    <li><a href="<?php echo $Home; ?>/Admin/loto/">Loto</a></li>

    <li><a href="<?php echo $Home; ?>/Admin/Signature/">Signature numérique</a><ul>
    <li><a href="<?php echo $Home; ?>/Admin/Signature/Tampon/">Mes Tampons</a></li>
    <li><a href="<?php echo $Home; ?>/Admin/Signature/Tampon/Upload/">Ajouter un Tampon</a></li>
    </ul></li>

<?php  } ?>
</ul>
</div>

</div>
</nav>