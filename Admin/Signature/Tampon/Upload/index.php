<?php
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/fonction_perso.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/redirect.inc.php");

if ($Cnx_Admin!=TRUE) {
  header('location:'.$Home.'/Admin/');
}

$Erreur=$_GET['erreur'];
$Valid=$_GET['valid'];
$Now=time();
$ext = array('.jpeg', '.JPEG', '.jpg', '.JPG', '.png', '.PNG');
$ext1 = array('.jpeg', '.JPEG', '.jpg', '.JPG');
$ext2 = array('.png', '.PNG');

//--------- Etape
$Etape=$_SESSION['Etape'];

if (isset($Etape)) {
  $Etape1=true;

  if ($Etape=="2") {
    $Etape2=false;
    $Etape1=true;
  }
}
else {  
  $Etape1=false;
  $Etape2=true;
}

//--------- Ajouter une photo
// Upload, création étape2
if (isset($_POST['Ajouter'])) {

    if ($_FILES['photo']['name']!="") {
        $chemin=$_FILES['photo']['name'];
        $fichier=basename($chemin);
        $taille_origin=filesize($_FILES['photo']['tmp_name']);
        $ext_origin=strchr($chemin, '.');

        $TailleImage=@getimagesize($_FILES['photo']['tmp_name']);
        $taille_max="20000000";

        $Code=md5(uniqid(rand(), true));
        $Hash=substr($Code, 0, 8);

        if (in_array($ext_origin, $ext2)) {
            if($taille_origin>$taille_max) {
                $Erreur = "fichier trop volumineux, il ne doit dépassé les 20Mo";
            }

            if (!isset($Erreur)) {      
                $ImageChoisie_photo = imagecreatefrompng($_FILES['photo']['tmp_name']);
                $NouvelleImage_photo = imagecreatetruecolor($TailleImage[0] , $TailleImage[1]);
                imagealphablending($NouvelleImage_photo, false);
                imagesavealpha($NouvelleImage_photo, true);
                imagecopyresampled($NouvelleImage_photo , $ImageChoisie_photo, 0, 0, 0, 0, $TailleImage[0], $TailleImage[1], $TailleImage[0],$TailleImage[1]);

                $UpMaqimg=imagepng($NouvelleImage_photo, $repIntTampon.$Hash.$ext_origin, 0);
    
                if ($UpMaqimg==false) {
                        $Erreur="Erreur de téléchargement, veuillez réassayer ultérueurement";
                }
                else {
                    $_SESSION['Etape']="2";
                    $_SESSION['lien']=$Hash.$ext_origin;
                    $_SESSION['nom']=$_POST['nom'];
                    $delai=0;
                    header("Refresh:".$delai.";url=".$Home."/Admin/Signature/Tampon/Upload/");
                }
            } 
        }
        if (in_array($ext_origin, $ext1)) {
            if($taille_origin>$taille_max) {
                $Erreur = "fichier trop volumineux, il ne doit dépassé les 20Mo";
            }

            if (!isset($Erreur)) {
                $ImageChoisie_photo = imagecreatefromjpeg($_FILES['photo']['tmp_name']);
                $NouvelleImage_photo = imagecreatetruecolor($TailleImage[0] , $TailleImage[1]);
                imagecopyresampled($NouvelleImage_photo , $ImageChoisie_photo, 0, 0, 0, 0, $TailleImage[0], $TailleImage[1], $TailleImage[0],$TailleImage[1]);

                $UpMaqimg=imagejpeg($NouvelleImage_photo, $repIntTampon.$Hash.$ext_origin, 100);
    
                if ($UpMaqimg==false) {
                        $Erreur="Erreur de téléchargement, veuillez réassayer ultérueurement";
                }
                else {
                    $_SESSION['Etape']="2";
                    $_SESSION['lien']=$Hash.$ext_origin;
                    $_SESSION['nom']=$_POST['nom'];
                    $delai=0;
                    header("Refresh:".$delai.";url=".$Home."/Admin/Signature/Tampon/Upload/");
                }
            } 
        }
    }
}

//--------- Ajouter une photo
//--------- Validation, Insertion
if (isset($_POST['Valider'])) {
        $Insert=$cnx->prepare("INSERT INTO ".$Prefix."_Signature_Tampon (lien, nom) VALUES(:lien, :nom)");
        $Insert->BindParam(":lien", $_SESSION['lien'], PDO::PARAM_STR);
        $Insert->BindParam(":nom", $_SESSION['nom'], PDO::PARAM_STR);
        $Insert->execute();

        if ($Insert===false) {
            $Erreur="Erreur serveur, veuillez réessayer ultérieurement !";
        }
        else  {     
            unset($_SESSION['Etape']);
            unset($_SESSION['lien']);
            unset($_SESSION['nom']);

            $Valid="Article ajouter avec succés";
            header("location:".$Home."/Admin/Signature/Tampon/?valid=".urlencode($Valid));
        }  
}

if (isset($_POST['Annuler'])) {
    $LienInt=$repIntTampon.$_SESSION['lien'];
    unlink($Lien);
    
    unset($_SESSION['Etape']);
    unset($_SESSION['lien']);
    unset($_SESSION['nom']);
  
    header('location:'.$Home.'/Admin/Signature/Tampon');
}

if (isset($_POST['Rotation'])) {
    $LienInt=$repIntTampon.$_SESSION['lien'];
    $LienExt=$repExtTampon.$_SESSION['lien'];
    $ext_origin=strchr($LienExt, '.');
    
    $TailleImage=@getimagesize($LienExt);
    $degrees = 90;
    
    if (in_array($ext_origin, $ext1)) {
      $source = imagecreatefromjpeg($LienInt);
      $rotate = imagerotate($source, $degrees, 0);  
    }
    else {
      $source = imagecreatefrompng($LienInt); 
      imagealphablending($source, false);
      imagesavealpha($source, true);
      $transparency = imagecolorallocatealpha( $source, 0,0,0,127 ); 
      $rotate = imagerotate($source, $degrees, $transparency); 
      imagealphablending($rotate, false);
      imagesavealpha($rotate, true);  
    }
  
    unlink($LienInt);
    
    if (in_array($ext_origin, $ext1)) {
      $nouvelle_image=imagejpeg($rotate, $LienInt, 100);
    }
    else {
      $nouvelle_image=imagepng($rotate, $LienInt, 0);
    }
      
    imagedestroy($rotate);
  
    $delai=0;
    header("Refresh:".$delai.";url=".$Home."/Admin/Signature/Tampon/Upload/");
}

$LienExt=$repExtTampon.$_SESSION['lien'];
?>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article>
<?php if (isset($Erreur)) { echo "<font color='#FF0000'>".$Erreur."</font><BR />"; }
if (isset($Valid)) { echo "<font color='#009900'>".$Valid."</font><BR />"; } ?>


<?php if ($Etape1==false) { ?>
<H1>Ajouter un tampon personnel</H1>

<font color="#FF6600">Conseil</font> : Pour une qualité optimal, le fichier doit correspondre au critères suivant :<BR />
<ul>
    <li>Le fichier doit être sur <b>fond transparant</b>, ne doit apparaître que le texte et tracé</li>
    <li>Le fichier doit être au <b>format (.png)</b>, <i>les fichiers au format (.jpg) sont toléré</i></li>
    <li>La taille du fichier doit être de <b>taille réel en 300dpi</b></li>
</ul><BR /><BR />

<form name="form_photo" action="" method="POST" enctype="multipart/form-data">

<input type="text"  placeholder="Libelé" name="nom"/><BR />
<input type="file"  placeholder="Photo" name="photo"/><BR /><BR />

<input type="submit" name="Ajouter" value="Ajouter"/>
</form>
<p><font color='#FF0000'>*</font> Champ de saisie requis<BR /><BR />

<?php }
if ($Etape2==false) { ?>

<H1>Validation de la photo - Etape 2

<div id="valid_img">

<img src="<?php echo $LienExt; ?>"><BR /><BR />
</div>

<div id="valid_lien">

<form name="form_rotate" action="" method="POST">
<input type="submit" name="Rotation" value="Rotation 90°"/>
</form></p>

<form name="form_valid" action="" method="POST">
<input type="submit" name="Valider" value="Terminer"/>
</form>

<form name="form_delete" action="" method="POST">
<input type="submit" name="Annuler" value="Annuler"/>
</form>

</div>

<?php } ?> 

</article>
</section>
</div>
</CENTER>
</body>

</html>