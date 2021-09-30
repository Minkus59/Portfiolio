<?php 
if (isset($_GET['ErreurContact'])) {
  $Erreur=$_GET['ErreurContact'];
}
if (isset($_GET['ValidContact'])) {
  $Valid=$_GET['ValidContact'];
}
?>

<section id="Contact" class="ContentBlanc">
  <div class="container">
    <div class="row justify-content-center">
        <div class="row">  
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
              <img class="row justify-content-center" src="/frontend/public/img/Contact.png" alt="Contact" />
            </div>
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

                Pour toutes demandes de renseignements <b>06 52 94 26 92</b> ou via le <b>formulaire ci-dessous</b>

                <form name="form_contact" id="form_contact" action="<?php HOME ?>/frontend/models/contact.php" method="POST">

                <textarea cols="40" rows="10" name="message" placeholder="Message *" required="required"><?php if (isset($_SESSION['message'])) { echo $_SESSION['message']; } ?></textarea>
                <input type="email" value="<?php if (isset($_SESSION['email'])) { echo $_SESSION['email']; } ?>" name="email" placeholder="Votre adresse e-mail *" required="required"/>
                <input type="text" value="<?php if (isset($_SESSION['tel'])) { echo $_SESSION['tel']; } ?>" name="tel" placeholder="Numéro de téléphone *" required="required"/>
                <input type="submit" name="Envoyer" value="Envoyer"/>

                </form>

                <p><font color='#FF0000'>*</font> : Informations requises</p>
              </div>
            </div>
          </div>
          <div class="Retour">
            <input type="button" title="revenir en haut de la page" onclick="window.location.href='#Haut'">
          </div>
        </div>
    </div>
</section>

