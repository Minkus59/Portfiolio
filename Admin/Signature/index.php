<?php 

require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");


if ($Cnx_Admin!=TRUE) {
  header('location:'.HOME.'/Admin');
}

if (isset($_GET['erreur']) || isset($_GET['valid'])) {
  $Erreur=$_GET['erreur'];
  $Valid=$_GET['valid'];
}

$SelectFichier=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Original ORDER BY id DESC");
$SelectFichier->execute();

$SelectFichierSigner=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Signer ORDER BY id DESC");
$SelectFichierSigner->execute();

?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article>
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

<H1>Télécharger un document</H1>

<form name="form_fichier" action="<?php echo HOME ?>/Admin/Signature/Upload/" method="POST" enctype="multipart/form-data">
<input type="file" placeholder="fichier" name="fichier[]" required="required" onChange="submit()" multiple/><span class="col_3"><img src="<?php echo HOME ?>/Admin/lib/img/intero.png" alt="Information" title="Fichier au format (.pdf)" /></span>
</form><BR />

<H1>Liste des documents</H1>

<ul class="accordion">
  <li class="has-sub">
    <label for="Original">Documents Original</label><input class="Hid" id="Original"type="checkbox" checked />
    <ul class="sub">
      <li>
      <table class="Admin">
        <tr><th>Aperçu</th><th>Original</th><th>Date</th><th>Action</th></tr>
        <form name="FormReSignMultiple" action="<?php echo HOME ?>/Admin/Signature/SignerMulti/" method="POST">
        <?php
        while ($Fichier=$SelectFichier->fetch(PDO::FETCH_OBJ)) {
              $SelectArchiveJpg = $cnx -> prepare("SELECT * FROM ".DB_PREFIX."Signature_Original_Jpg WHERE page='1' AND hash=:hash");
              $SelectArchiveJpg-> BindParam(":hash", $Fichier->hash, PDO::PARAM_STR);
              $SelectArchiveJpg-> execute(); 
              $ArchiveJpg=$SelectArchiveJpg->fetch(PDO::FETCH_OBJ);
        ?>
          <tr>
          <td><?php echo "<img width='150px' src='".$repExtJpgOriginal.$ArchiveJpg->fichier."' />"; ?></td>
          <td><a target="_blank" href="<?php echo $repExtOriginal.$Fichier->fichier; ?>"><?php echo $Fichier->fichier; ?></a></td>
          <td><?php echo date("d-m-Y / G:i:s", $Fichier->created); ?></td>
          <td>
          <input type="checkbox" name="selection[]" value="<?php echo $Fichier->hash; ?>"/>
          </td>
          <td>
            <a href="<?php echo HOME ?>/Admin/Signature/Signer/?id=<?php echo $Fichier->hash; ?>"><acronym title="Ajouter une signature"><img src="<?php echo HOME ?>/Admin/lib/img/modifier.png"/></acronym></a>
            <a href="<?php echo HOME ?>/Admin/Signature/supprimer.php?id=<?php echo $Fichier->id; ?>"><acronym title="Supprimer le document"><img src="<?php echo HOME ?>/Admin/lib/img/supprimer.png"/></acronym></a>
          </td>
          </tr>
        <?php
        }
        ?>
        <tr><td></td><td></td><td>Pour la selection : </td><td><input type="submit" name="Signer1" value="Signer"/></td><td></td></tr>
        </form>
        </table>
      </li>
    </ul>
  </li>
    <li class="has-sub">
    <label for="Signer">Documents Signer</label><input class="Hid" id="Signer"type="checkbox" checked />
    <ul class="sub">
      <li>
        <table class="Admin">
        <form name="FormSignMultiple" action="<?php echo HOME ?>/Admin/Signature/ResignerMulti/" method="POST">
        <tr><th>Aperçu</th><th>Signer</th><th>Date</th><th>Selection</th><th>Action</th></tr>

        <?php
        while ($Fichier1=$SelectFichierSigner->fetch(PDO::FETCH_OBJ)) {
              $Page=$Fichier1->page;
              if ($Page=="All") {
                $NbPage=1;
              }
              elseif ($Page=="Last") {
                $SelectJpg2 = $cnx -> prepare("SELECT * FROM ".DB_PREFIX."Signature_Signer_Jpg WHERE hash=:hash");
                $SelectJpg2-> BindParam(":hash", $Fichier1->hash, PDO::PARAM_STR);
                $SelectJpg2-> execute(); 
                $AfficheJpg2=$SelectJpg2->fetch(PDO::FETCH_OBJ);

                $NbPage=$SelectJpg2->rowCount();
              }
              else {
                $NbPage=$Fichier1->page;
              }

              $SelectArchiveJpg1 = $cnx -> prepare("SELECT * FROM ".DB_PREFIX."Signature_Signer_Jpg WHERE page=:page AND hash=:hash");
              $SelectArchiveJpg1-> BindParam(":hash", $Fichier1->hash, PDO::PARAM_STR);
              $SelectArchiveJpg1->BindParam(":page", $NbPage, PDO::PARAM_STR);
              $SelectArchiveJpg1-> execute(); 
              $ArchiveJpg1=$SelectArchiveJpg1->fetch(PDO::FETCH_OBJ);
        ?>
          <tr>
          <td><?php echo "<img width='150px' src='".$repExtJpgSigner.$ArchiveJpg1->fichier."' />"; ?></td>
          <td><a target="_blank" href="<?php echo $repExtSigner.$Fichier1->fichier; ?>"><?php echo $Fichier1->fichier; ?></a></td>
          <td><?php echo date("d-m-Y / G:i:s", $Fichier1->created); ?></td>
          <td>
          <input type="checkbox" name="selection[]" value="<?php echo $Fichier1->hash; ?>"/>
          </td>
          <td>
            <a href="<?php echo HOME ?>/Admin/Signature/Resigner/?id=<?php echo $Fichier1->hash; ?>"><acronym title="Ajouter une signature"><img src="<?php echo HOME ?>/Admin/lib/img/modifier.png"/></acronym></a>
            <a href="<?php echo HOME ?>/Admin/Signature/supprimer_Signer.php?id=<?php echo $Fichier1->id; ?>"><acronym title="Supprimer le document"><img src="<?php echo HOME ?>/Admin/lib/img/supprimer.png"/></acronym></a>
          </td>
          </tr>
        <?php
        }
        ?>
        <tr><td></td><td></td><td>Pour la selection : </td><td><input type="submit" name="Signer2" value="Signer"/></td><td></td></tr>
        </form>
        </table>
      </li>
    </ul>
  </li>
</ul>


</article>
</section>
</div>
</CENTER>
</body>

</html>