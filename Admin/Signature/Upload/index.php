<?php
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/fonction_perso.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/redirect.inc.php");

if ($Cnx_Admin!=TRUE) {
  header('location:'.$Home.'/Admin/');
}

require_once($_SERVER['DOCUMENT_ROOT']."/lib/FPDF/fpdf.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/FPDI-1.6.1/fpdi.php");

$Count=count($_FILES['fichier']['name']);

for($i=0;$i<$Count;$i++) {
    $Code=md5(uniqid(rand(), true));
    $_SESSION['hash']=substr($Code, 0, 8);
    $Now=time();

    $chemin=$_FILES['fichier']['name'][$i];
    $fichier=basename($chemin);
    $Fichier=$_SESSION['hash']."-".$fichier;
    $NomFichier=preg_replace(array('/.pdf/', '/.PDF/'), "", $Fichier);

    $VerifExist=$cnx->prepare("SELECT * FROM ".$Prefix."_Signature_Original WHERE fichier=:fichier");
    $VerifExist->bindParam(':fichier', $Fichier, PDO::PARAM_STR);
    $VerifExist->execute();
    $NbRows=$VerifExist->rowCount();
            
    if ($NbRows==1) {
        $Erreur= "Ce fichier existe déjà !<br />";
        header('location:'.$Home.'/Admin/Signature/?erreur='.urlencode($Erreur));
    }
    else {
        $ext = array('.pdf', '.PDF');
        $ext2 = array('.pdf', '.PDF');

        if ($_FILES['fichier']['name'][$i]!="") {
            $taille_origin=filesize($_FILES['fichier']['tmp_name'][$i]);
            $ext_origin=strchr($chemin, '.');

            $TailleImage=@getimagesize($_FILES['fichier']['tmp_name'][$i]);
            $taille_max="20000000";

            if (!in_array($ext_origin, $ext)) {
                $Erreur= "Extention de fichier non pris en charge !<BR />";
                header('location:'.$Home.'/Admin/Signature/?erreur='.urlencode($Erreur));
            }
            else {
                //PDF
                if (in_array($ext_origin, $ext2)) {
                    if($taille_origin>$taille_max) {
                        $Erreur= "fichier trop volumineux, il ne doit dépassé les 20Mo<BR />";
                        header('location:'.$Home.'/Admin/Signature/?erreur='.urlencode($Erreur));
                    }

                    if (!isset($Erreur)) {
                        $Upload = move_uploaded_file($_FILES['fichier']['tmp_name'][$i], $repIntOriginal.$Fichier);
                        if ($Upload==false) {
                            $Erreur= "Erreur de téléchargement, veuillez réassayer ultérueurement<BR />";
                            header('location:'.$Home.'/Admin/Signature/?erreur='.urlencode($Erreur));
                        }
                        else {
                            $NoPage=0;
                            $pdf = new FPDI();                        
                            $pageCount = $pdf->setSourceFile($repIntOriginal.$Fichier);
                            // iterate through all pages
                            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                                // import a page
                                $templateId = $pdf->importPage($pageNo);
                                // get the size of the imported page
                                $size = $pdf->getTemplateSize($templateId);

                                // create a page (landscape or portrait depending on the imported page size)
                                if ($size['w'] > $size['h']) {
                                    $pdf->AddPage('L', array($size['w'], $size['h']));
                                } 
                                else {
                                    $pdf->AddPage('P', array($size['w'], $size['h']));
                                }
                                // use the imported page
                                $pdf->useTemplate($templateId);

                                if($pageCount>1) {
                                    $FichierJpg=$NomFichier."-".$NoPage.".jpg";
                                }
                                else {
                                    $FichierJpg=$NomFichier.".jpg";
                                }
                                $FichierJpg2=$NomFichier.".jpg";

                                $Insert=$cnx->prepare("INSERT INTO ".$Prefix."_Signature_Original_Jpg (fichier, page, created, hash) VALUES (:fichier, :page, :created, :hash)");
                                $Insert->BindParam(":fichier", $FichierJpg, PDO::PARAM_STR);
                                $Insert->BindParam(":page", $pageNo, PDO::PARAM_STR);
                                $Insert->BindParam(":created", $Now, PDO::PARAM_STR);
                                $Insert->BindParam(":hash", $_SESSION['hash'], PDO::PARAM_STR);
                                $Insert->execute();

                                $NoPage++;
                            }

                            $pdf_file = $repIntOriginal.$Fichier;
                            $save_to = $repIntJpgOriginal.$FichierJpg2;
                            exec('convert "'.$pdf_file.'" -colorspace RGB -resize 800 "'.$save_to.'"', $output, $return_var);
                            
                            if($return_var != 0) {
                                $Erreur= "Erreur de chargement des images, veuillez réassayer !<BR />";
                                header('location:'.$Home.'/Admin/Signature/?erreur='.urlencode($Erreur));
                            }
                            
                            $Insert=$cnx->prepare("INSERT INTO ".$Prefix."_Signature_Original (fichier, created, hash) VALUES (:fichier, :created, :hash)");
                            $Insert->BindParam(":fichier", $Fichier, PDO::PARAM_STR);
                            $Insert->BindParam(":created", $Now, PDO::PARAM_STR);
                            $Insert->BindParam(":hash", $_SESSION['hash'], PDO::PARAM_STR);
                            $Insert->execute();

                            unset($_SESSION['hash']);
                            $Valid="Fichier ajouter avec succès";
                        }
                    } 
                }
            }
        }
    }
}

header('location:'.$Home.'/Admin/Signature/?valid='.urlencode($Valid));
?>