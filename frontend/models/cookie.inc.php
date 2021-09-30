<?php 
if (!isset($_SESSION['cookie'])) { ?>
    <div id="cookie">
        <form name="cookie" action="<?php HOME ?>/frontend/models/FermeCookie.php" method="POST">
            Bienvenue ! En poursuivant votre navigation, vous acceptez l'utilisation de cookies. <BR />
            <input type="hidden" name="page" value="<?php echo $page; ?>"/>
            <input class="cookie" type="submit" name="OK" value="Tout accepter"/>
            <input class="cookie" type="submit" name="NOK" value="Tous refuser"/>
        </form>
    </div>
<?php } ?>

