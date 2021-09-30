<?php
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");

if ($Cnx_Admin===false) {
  header('location:'.HOME.'/Admin');
}

if (isset($_GET['erreur']) || isset($_GET['valid'])) {
      $Erreur=$_GET['erreur'];
      $Valid=$_GET['valid'];
}
$Now=time();
$ext = array('.jpeg', '.JPEG', '.jpg', '.JPG', '.png', '.PNG');
$ext1 = array('.jpeg', '.JPEG', '.jpg', '.JPG');
$ext2 = array('.png', '.PNG');
$ext3 = array('.pdf', '.PDF');
$ext4 = array('.avi', '.AVI', '.mov', '.MOV', '.mp4', '.mp4', '.mpg', '.MPG', '.mpa', '.MPA', '.mp2', '.MP2', '.m2p', '.M2P', '.wma', '.WMA', '.asf', '.ASF');
$ext5 = array('.pps', '.PPS', '.ppsx', '.PPSX');

$SelectDocument=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Document ORDER BY libele ASC");
$SelectDocument->execute();

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
// Upload, creation etape2
if (isset($_POST['Ajouter'])) {
    if ($_FILES['document']['name']!="") {
        $chemin=$_FILES['document']['name'];
        $fichier=basename($chemin);
        $taille_origin=filesize($_FILES['document']['tmp_name']);
        $ext_origin=strchr($chemin, '.');
        $Code=md5(uniqid(rand(), true));
        $Hash=substr($Code, 0, 8);
        $Libele=$_POST['libele'];

        if (in_array($ext_origin, $ext2)) {
            $TailleImage=@getimagesize($_FILES['document']['tmp_name']);
            $taille_max="2000000000";
            $repInt=$_SERVER['DOCUMENT_ROOT']."/lib/Photo/";
            $repExt=HOME."/lib/Photo/";
            
            if (!file_exists($repInt)) {
                mkdir($repInt, 0777);
            }

            if($taille_origin>$taille_max) {
                $Erreur = "fichier trop volumineux, il ne doit dépassé les 20Mo";
                ErreurLog($Erreur);
            }

            if (!isset($Erreur)) {
            
                if ($TailleImage[0]>880) {
                  $NouvelleLargeur_photo = 880;                
                } 
                if (($TailleImage[0]>480)&&($TailleImage[0]<880)) {
                  $NouvelleLargeur_photo = 460;               
                } 
                if ($TailleImage[0]<480) {
                  $NouvelleLargeur_photo = $TailleImage[0];                
                }                
                                              
                $NouvelleHauteur_photo = ( ($TailleImage[1] * (($NouvelleLargeur_photo)/$TailleImage[0])) );      
                $ImageChoisie_photo = imagecreatefrompng($_FILES['document']['tmp_name']);
                $NouvelleImage_photo = imagecreatetruecolor($NouvelleLargeur_photo , $NouvelleHauteur_photo);
                imagealphablending($NouvelleImage_photo, false);
                imagesavealpha($NouvelleImage_photo, true);
                imagecopyresampled($NouvelleImage_photo , $ImageChoisie_photo, 0, 0, 0, 0, $NouvelleLargeur_photo, $NouvelleHauteur_photo, $TailleImage[0],$TailleImage[1]);

                $UpMaqimg=imagepng($NouvelleImage_photo, $repInt.$Hash.$ext_origin, 0);
    
                if ($UpMaqimg==false) {
                    $Erreur="Erreur de téléchargement, veuillez réassayer ultèrieurement";
                    ErreurLog($Erreur);
                }
                else {
                    $_SESSION['Etape']="2";
                    $_SESSION['lien']=$repExt.$Hash.$ext_origin;
                    $_SESSION['lienInt']=$repInt.$Hash.$ext_origin;
                    $_SESSION['libele']=$Libele;
                    $delai=0;
                    header("Refresh:".$delai.";url=".HOME."/Admin/Document/");
                }
            } 
        }
        if (in_array($ext_origin, $ext1)) {
            $TailleImage=@getimagesize($_FILES['document']['tmp_name']);
            $taille_max="2000000000";
            $repInt=$_SERVER['DOCUMENT_ROOT']."/lib/Photo/";
            $repExt=HOME."/lib/Photo/";

            if (!file_exists($repInt)) {
                mkdir($repInt, 0777);
            }

            if($taille_origin>$taille_max) {
                $Erreur = "fichier trop volumineux, il ne doit dépassé les 20Mo";
                ErreurLog($Erreur);
            }

            if (!isset($Erreur)) {
            
                if ($TailleImage[0]>880) {
                  $NouvelleLargeur_photo = 880;                
                } 
                if (($TailleImage[0]>480)&&($TailleImage[0]<880)) {
                  $NouvelleLargeur_photo = 460;               
                } 
                if ($TailleImage[0]<480) {
                  $NouvelleLargeur_photo = $TailleImage[0];                
                }
                
                $NouvelleHauteur_photo = ( ($TailleImage[1] * (($NouvelleLargeur_photo)/$TailleImage[0])) );
                $ImageChoisie_photo = imagecreatefromjpeg($_FILES['document']['tmp_name']);
                $NouvelleImage_photo = imagecreatetruecolor($NouvelleLargeur_photo , $NouvelleHauteur_photo);
                imagecopyresampled($NouvelleImage_photo , $ImageChoisie_photo, 0, 0, 0, 0, $NouvelleLargeur_photo, $NouvelleHauteur_photo, $TailleImage[0],$TailleImage[1]);

                $UpMaqimg=imagejpeg($NouvelleImage_photo, $repInt.$Hash.$ext_origin, 100);
    
                if ($UpMaqimg==false) {
                    $Erreur="Erreur de téléchargement, veuillez réassayer ultèrieurement";
                    ErreurLog($Erreur);
                }
                else {
                    $_SESSION['Etape']="2";
                    $_SESSION['lien']=$repExt.$Hash.$ext_origin;
                    $_SESSION['lienInt']=$repInt.$Hash.$ext_origin;
                    $_SESSION['libele']=$Libele;
                    $delai=0;
                    header("Refresh:".$delai.";url=".HOME."/Admin/Document/");
                }
            } 
        }
        if (in_array($ext_origin, $ext3)) {
            $taille_max="2000000000";
            $repInt=$_SERVER['DOCUMENT_ROOT']."/lib/Document/";
            $repExt=HOME."/lib/Document/";
            $Type="PDF";
            $Lien=$repExt.$Hash.$ext_origin;

            if($taille_origin>$taille_max) {
                $Erreur = "fichier trop volumineux, il ne doit dépassé les 20Mo";
                ErreurLog($Erreur);
            }

            if (!isset($Erreur)) {
                if (move_uploaded_file($_FILES['document']['tmp_name'], $repInt.$Hash.$ext_origin)==false) {
                    $Erreur="Erreur de téléchargement, veuillez réassayer ultèrieurement";
                    ErreurLog($Erreur);
                }
                else {
                    $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."Document (libele, lien, type) VALUES(:libele, :lien, :type)");
                    $Insert->BindParam(":type", $Type, PDO::PARAM_STR);
                    $Insert->BindParam(":lien", $Lien, PDO::PARAM_STR);
                    $Insert->BindParam(":libele", $Libele, PDO::PARAM_STR);
                    $Insert->execute();

                    if (!$Insert) {
                        $Erreur="Erreur serveur, veuillez réessayer ultèrieurement !";
                        ErreurLog($Erreur);
                    }
                    else  {     
                        $Valid="Document ajouter avec succès";
                        header("location:".HOME."/Admin/Document/?valid=".urlencode($Valid));
                    }
                }
            }
        }
        if (in_array($ext_origin, $ext4)) {
            $taille_max="2000000000";
            $repInt=$_SERVER['DOCUMENT_ROOT']."/lib/Video/";
            $repExt=HOME."/lib/Video/";
            $Type="Video";
            $Lien=$repExt.$Hash.$ext_origin;

            if (!file_exists($repInt)) {
                mkdir($repInt, 0777);
            }

            if($taille_origin>$taille_max) {
                $Erreur = "fichier trop volumineux, il ne doit dépassé les 20Mo";
                ErreurLog($Erreur);
            }

            if (!isset($Erreur)) {
                if (move_uploaded_file($_FILES['document']['tmp_name'], $repInt.$Hash.$ext_origin)==false) {
                    $Erreur="Erreur de téléchargement, veuillez réassayer ultèrieurement";
                    ErreurLog($Erreur);
                }
                else {
                    $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."Document (libele, lien, type) VALUES(:libele, :lien, :type)");
                    $Insert->BindParam(":type", $Type, PDO::PARAM_STR);
                    $Insert->BindParam(":lien", $Lien, PDO::PARAM_STR);
                    $Insert->BindParam(":libele", $Libele, PDO::PARAM_STR);
                    $Insert->execute();

                    if (!$Insert) {
                        $Erreur="Erreur serveur, veuillez réessayer ultèrieurement !";
                        ErreurLog($Erreur);
                    }
                    else  {     
                        $Valid="Document ajouter avec succès";
                        header("location:".HOME."/Admin/Document/?valid=".urlencode($Valid));
                    }
                }
            }
        }
        if (in_array($ext_origin, $ext5)) {
            $taille_max="2000000000";
            $repInt=$_SERVER['DOCUMENT_ROOT']."/lib/pps/";
            $repExt=HOME."/lib/pps/";
            $Type="PPS";
            $Lien=$repExt.$Hash.$ext_origin;

            if (!file_exists($repInt)) {
                mkdir($repInt, 0777);
            }

            if($taille_origin>$taille_max) {
                $Erreur = "fichier trop volumineux, il ne doit dépassé les 20Mo";
                ErreurLog($Erreur);
            }

            if (!isset($Erreur)) {
                if (move_uploaded_file($_FILES['document']['tmp_name'], $repInt.$Hash.$ext_origin)==false) {
                    $Erreur="Erreur de téléchargement, veuillez réassayer ultèrieurement";
                    ErreurLog($Erreur);
                }
                else {
                    $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."Document (libele, lien, type) VALUES(:libele, :lien, :type)");
                    $Insert->BindParam(":type", $Type, PDO::PARAM_STR);
                    $Insert->BindParam(":lien", $Lien, PDO::PARAM_STR);
                    $Insert->BindParam(":libele", $Libele, PDO::PARAM_STR);
                    $Insert->execute();

                    if (!$Insert) {
                        $Erreur="Erreur serveur, veuillez réessayer ultèrieurement !";
                        ErreurLog($Erreur);
                    }
                    else  {     
                        $Valid="Document ajouter avec succès";
                        header("location:".HOME."/Admin/Document/?valid=".urlencode($Valid));
                    }
                }
            }
        }
    }
}

//--------- Ajouter une photo
//--------- Validation, Insertion
if (isset($_POST['Valider'])) {
        $Type="Image";
        $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."Document (libele, lien, type) VALUES(:libele, :lien, :type)");
        $Insert->BindParam(":lien", $_SESSION['lien'], PDO::PARAM_STR);
        $Insert->BindParam(":libele", $_SESSION['libele'], PDO::PARAM_STR);
        $Insert->BindParam(":type", $Type, PDO::PARAM_STR);
        $Insert->execute();

        if (!$Insert) {
            $Erreur="Erreur serveur, veuillez réessayer ultèrieurement !";
            ErreurLog($Erreur);
        }
        else  {     
            unset($_SESSION['Etape']);
            unset($_SESSION['lien']);
            unset($_SESSION['lienInt']);
            unset($_SESSION['libele']);

            $Valid="Document ajouter avec succès";
            header("location:".HOME."/Admin/Document/?valid=".urlencode($Valid));
        }  
}

if (isset($_POST['Annuler'])) {
    unlink($_SESSION['lienInt']);
    
    unset($_SESSION['Etape']);
    unset($_SESSION['lien']);
    unset($_SESSION['lienInt']);
  
    header('location:'.HOME.'/Admin/Document');
}

if (isset($_POST['Rotation'])) {
    $ext_origin=strchr($_SESSION['lienInt'], '.');
    
    $TailleImage=@getimagesize($_SESSION['lien']);
    $degrees = 90;
    
    if (in_array($ext_origin, $ext1)) {
      $source = imagecreatefromjpeg($_SESSION['lienInt']);
      $rotate = imagerotate($source, $degrees, 0);  
    }
    else {
      $source = imagecreatefrompng($_SESSION['lienInt']); 
      imagealphablending($source, false);
      imagesavealpha($source, true);
      $transparency = imagecolorallocatealpha( $source, 0,0,0,127 ); 
      $rotate = imagerotate($source, $degrees, $transparency); 
      imagealphablending($rotate, false);
      imagesavealpha($rotate, true);  
    }
  
    unlink($_SESSION['lienInt']);
    
    if (in_array($ext_origin, $ext1)) {
      $nouvelle_image=imagejpeg($rotate, $_SESSION['lienInt'], 100);
    }
    else {
      $nouvelle_image=imagepng($rotate, $_SESSION['lienInt'], 0);
    }
      
    imagedestroy($rotate);
  
    $delai=0;
    header("Refresh:".$delai.";url=".HOME."/Admin/Document/");
}

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


<?php if ($Etape1==false) { ?>
<H1>Ajouter un document</H1>

<form name="form_document" action="" method="POST" enctype="multipart/form-data">

<input type="text"  placeholder="Libellé" name="libele"/><BR />
<input type="file"  placeholder="Document" name="document"/><BR /><BR />

<input type="submit" name="Ajouter" value="Ajouter"/>
</form>
<p><font color='#FF0000'>*</font> Champ de saisie requis

<?php }
if ($Etape2==false) { ?>

<H1>Validation de la photo - Etape 2</H1>

<div id="valid_img">

<img src="<?php echo $_SESSION['lien']; ?>"><BR /><BR />
</div>

<div id="valid_lien">

<form name="form_rotate" action="" method="POST">
<input type="submit" name="Rotation" value="Rotation 90°"/>
</form>

<form name="form_valid" action="" method="POST">
<input type="submit" name="Valider" value="Terminer"/>
</form>

<form name="form_delete" action="" method="POST">
<input type="submit" name="Annuler" value="Annuler"/>
</form>

</div>

<?php } ?> 

<BR /><HR /><BR />

<H1>Document</H1>

<table class="Admin">
<tr><th>Libellé</th><th>Type</th><th>Aperçu</th><th>Lien</th><th>Action</th></tr>
<?php

while ($Document=$SelectDocument->fetch(PDO::FETCH_OBJ)) {
      echo "<TR>";
      echo "<TD>".$Document->libele."</TD>";
      echo "<TD>".$Document->type."</TD>";
      echo "<TD>";
      if ($Document->type=="Image") {
          $Taille=getimagesize($Document->lien);
          if (($Taille[0]>250)||($Taille[1]>250)) {
            if ($Taille[0]>=$Taille[1]) {
                echo "<img width='250px' src='$Document->lien'/>";
            }
            else {
                echo "<img width='250px' src='$Document->lien'/>";
            }
        }
        else {
            echo "<img src='$Document->lien'/>";
        }
      }
      echo "</TD>";
      echo "<TD>".$Document->lien."</TD>";
      echo "<TD>";
      if ($Document->type=="PDF") {
        echo "<a href='$Document->lien'><img src='echo HOME/Admin/lib/img/apercu.png'></a>";
      }
      if ($Document->type=="PPS") {
        echo "<a href='$Document->lien'><img src='echo HOME/Admin/lib/img/apercu.png'></a>";
      }
      echo "<a href='echo HOME/Admin/Document/supprimer.php?id=$Document->id'><img src='echo HOME/Admin/lib/img/supprimer.png'></a>";
      echo "</TD></TR>";
}
?>
</table>

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>