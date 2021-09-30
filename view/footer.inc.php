<FOOTER>
<div class="Center">
<div id="BoutonBas">

</div>

<div class="Cadre">
<?php 
    $SelectSocial=$cnx->prepare("SELECT * FROM ".$Prefix."_Social WHERE statue='1' ORDER BY id ASC");
    $SelectSocial->bindParam(':parrin', $PageFooter->lien, PDO::PARAM_STR);
    $SelectSocial->execute();
    
    while ($LienSocial=$SelectSocial->fetch(PDO::FETCH_OBJ)) { ?>
        <a href='<?php echo $LienSocial->lien; ?>' target="_blank"><img src="<?php echo $LienSocial->logo; ?>"/></a>
<?php } ?>
</div>

</FOOTER>

</CENTER>
</body>

</html>
