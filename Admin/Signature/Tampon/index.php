<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/fonction_perso.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/redirect.inc.php");

if ($Cnx_Admin!=TRUE) {
  header('location:'.$Home.'/Admin');
}

$Erreur=$_GET[erreur];
$Valid=$_GET['valid'];

$SelectFichier=$cnx->prepare("SELECT * FROM ".$Prefix."_Signature_Tampon ORDER BY id DESC");
$SelectFichier->execute();

?>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article>
<?php if (isset($Erreur)) { echo "<font color='#FF0000'>".$Erreur."</font><BR />"; }
if (isset($Valid)) { echo "<font color='#009900'>".$Valid."</font><BR />"; } ?>

<H1>Liste des tampons</H1>

<table class="Admin">
<tr><th>Aperçu</th><th>Libelé</th><th>Action</th></tr>

<?php
while ($Fichier=$SelectFichier->fetch(PDO::FETCH_OBJ)) {
?>
  <tr>
  <td><?php echo "<img src='".$repExtTampon.$Fichier->lien."' />"; ?></td>
  <td><?php echo $Fichier->nom; ?></td>
  <td>
    <a href="<?php echo $Home; ?>/Admin/Signature/Tampon/supprimer.php?id=<?php echo $Fichier->id; ?>"><acronym title="Supprimer le tampon"><img src="<?php echo $Home; ?>/Admin/lib/img/supprimer.png"/></acronym></a>
  </td>
  </tr>
<?php
}
?>
</table>

</article>
</section>
</div>
</CENTER>
</body>

</html>