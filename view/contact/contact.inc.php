<?php 
$Erreur=$_GET['ErreurContact'];
$Valid=$_GET['ValidContact'];
?>

<div id="Contact" class="ContentBlanc">
<div class="Center">

<ARTICLE>
<div class="row">  
<div class="col">
<div class="right">
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

<H1>Contact</H1></p>

Pour toutes demandes de renseignements <b>06 52 94 26 92</b> ou via le <b>formulaire ci-dessous</b> </p>

<form name="form_contact" id="form_contact" action="<?php echo $Home; ?>lib/script/contact.php" method="POST">

<textarea cols="40" rows="10" name="message" placeholder="Message *" required="required"><?php if (isset($_SESSION['message'])) { echo $_SESSION['message']; } ?></textarea></p>
<input type="email" value="<?php if (isset($_SESSION['email'])) { echo $_SESSION['email']; } ?>" name="email" placeholder="Votre adresse e-mail *" required="required"/></p>
<input type="text" value="<?php if (isset($_SESSION['tel'])) { echo $_SESSION['tel']; } ?>" name="tel" placeholder="Numéro de téléphone *" required="required"/></p>
<input type="submit" name="Envoyer" value="Envoyer"/>

</form></p>

<font color='#FF0000'>*</font> : Informations requises</p>
</div>
</div>
<div class="col">
<img class="gauche" src="/lib/img/Contact.png" alt="Contact" />
</div>
</div>
</ARTICLE>
 
<div class="Retour">
<input type="button" onclick="window.location.href='#Haut'">
</div>
</div>
</div>