<?php
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/script/requete.inc.php");

if ($Cnx_Admin===false) {
  header('location:'.$Home.'/Admin');
}

$Erreur=$_GET['erreur'];
$Valid=$_GET['valid'];
$Now=time();

$RecupParam=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Parametre");
$RecupParam->execute();
$ParamEmail=$RecupParam->fetch(PDO::FETCH_OBJ);      

if (isset($_POST['type'])) {
    $_SESSION['type']=$_POST['type'];
    $_SESSION['objet']=$_POST['objet'];
    $_SESSION['message']=$_POST['message'];
    $_SESSION['retour']=$_POST['retour'];
}

if (isset($_SESSION['type'])) {
    $RecupParam=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Predefini WHERE id=:id");
    $RecupParam->BindParam(":id", $_SESSION['type'], PDO::PARAM_STR);
    $RecupParam->execute();
    $Param=$RecupParam->fetch(PDO::FETCH_OBJ); 
}

if (isset($_POST['signature'])) {
    $_SESSION['signature']=$_POST['signature'];
    $_SESSION['objet']=$_POST['objet'];
    $_SESSION['message']=$_POST['message'];
    $_SESSION['retour']=$_POST['retour'];
}

if (isset($_SESSION['signature'])) {
    $RecupSignature=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Signature WHERE id=:id");
    $RecupSignature->BindParam(":id", $_SESSION['signature'], PDO::PARAM_STR);
    $RecupSignature->execute();
    $ParamSignature=$RecupSignature->fetch(PDO::FETCH_OBJ); 
}

if (isset($_POST['groupe'])) {
    $_SESSION['groupe']=$_POST['groupe'];
    $_SESSION['objet']=$_POST['objet'];
    $_SESSION['message']=$_POST['message'];
    $_SESSION['retour']=$_POST['retour'];
}

if ((isset($_POST['Envoyer']))&&($_POST['Envoyer']=="Envoyer")) { 
    $_SESSION['objet']=$_POST['objet'];
    $_SESSION['message']=$_POST['message'];
    $_SESSION['retour']=$_POST['retour'];

    if ((isset($_POST['objet']))&&(!empty($_POST['objet']))) {
        if ((isset($_POST['message']))&&(!empty($_POST['message']))) {               
            if (preg_match("#^[A-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['retour'])) {  
                if (!isset($Erreur)) {
                    if ((isset($_FILES['fichier1']['name']))&&(!empty($_FILES['fichier1']['name']))) {
                    
                        $Fichier1=$_FILES['fichier1']['name'];
                        $FichierTmp1=$_FILES['fichier1']['tmp_name'];
                        $NomFichier1=basename($Fichier1);
                        $Taille1=filesize($FichierTmp1);
                        $ExtOrigin1=strchr($Fichier1, '.');
                        $TailleMax="20000000";
                        
                        $Code1=md5(uniqid(rand(), true));
                        $Hash1=substr($Code1, 0, 8);

                        $RepInt=$_SERVER['DOCUMENT_ROOT']."/lib/Mail/Document/";
                        $RepExt=$Home."/lib/Mail/Document/";
                        
                        //upload fichier
                        
                        $Upload1 = move_uploaded_file($FichierTmp1, $RepInt.$Hash1.$ExtOrigin1);

                        if ($Upload1==FALSE) {
                            $Erreur="Erreur de téléchargement du fichier, veuillez réassayer ultérueurement";
                        }
                        else {
                            $CheminFichier1 = $RepInt.$Hash1.$ExtOrigin1;
                            // Pièce jointe
                            $content1 = file_get_contents($CheminFichier1);
                            $content1 = chunk_split(base64_encode($content1));
                        }
                    }
                    
                    if ((isset($_FILES['fichier2']['name']))&&(!empty($_FILES['fichier2']['name']))) {
                    
                        $Fichier2=$_FILES['fichier2']['name'];
                        $FichierTmp2=$_FILES['fichier2']['tmp_name'];
                        $NomFichier2=basename($Fichier2);
                        $Taille2=filesize($FichierTmp2);
                        $ExtOrigin2=strchr($Fichier2, '.');
                        $TailleMax="20000000";
                        
                        $Code2=md5(uniqid(rand(), true));
                        $Hash2=substr($Code2, 0, 8);

                        $RepInt=$_SERVER['DOCUMENT_ROOT']."/lib/Mail/Document/";
                        $RepExt=$Home."/lib/Mail/Document/";
                        
                        //upload fichier
                        
                        $Upload2 = move_uploaded_file($FichierTmp2, $RepInt.$Hash2.$ExtOrigin2);

                        if ($Upload2==FALSE) {
                            $Erreur="Erreur de téléchargement du fichier, veuillez réassayer ultérueurement";
                        }
                        else {
                            $CheminFichier2 = $RepInt.$Hash2.$ExtOrigin2;
                            // Pièce jointe
                            $content2 = file_get_contents($CheminFichier2);
                            $content2 = chunk_split(base64_encode($content2));
                        }
                    }
                    
                    if ((isset($_FILES['fichier3']['name']))&&(!empty($_FILES['fichier3']['name']))) {
                    
                        $Fichier3=$_FILES['fichier3']['name'];
                        $FichierTmp3=$_FILES['fichier3']['tmp_name'];
                        $NomFichier3=basename($Fichier3);
                        $Taille3=filesize($FichierTmp3);
                        $ExtOrigin3=strchr($Fichier3, '.');
                        $TailleMax="20000000";
                        
                        $Code3=md5(uniqid(rand(), true));
                        $Hash3=substr($Code3, 0, 8);

                        $RepInt=$_SERVER['DOCUMENT_ROOT']."/lib/Mail/Document/";
                        $RepExt=$Home."/lib/Mail/Document/";
                        
                        //upload fichier
                        
                        $Upload3 = move_uploaded_file($FichierTmp3, $RepInt.$Hash3.$ExtOrigin3);

                        if ($Upload3==FALSE) {
                            $Erreur="Erreur de téléchargement du fichier, veuillez réassayer ultérueurement";
                        }
                        else {
                            $CheminFichier3 = $RepInt.$Hash3.$ExtOrigin3;
                            // Pièce jointe
                            $content3 = file_get_contents($CheminFichier3);
                            $content3 = chunk_split(base64_encode($content3));
                        }
                    }
                    
                    if ((isset($_FILES['fichier4']['name']))&&(!empty($_FILES['fichier4']['name']))) {
                    
                        $Fichier4=$_FILES['fichier4']['name'];
                        $FichierTmp4=$_FILES['fichier4']['tmp_name'];
                        $NomFichier4=basename($Fichier4);
                        $Taille4=filesize($FichierTmp4);
                        $ExtOrigin4=strchr($Fichier4, '.');
                        $TailleMax="20000000";
                        
                        $Code4=md5(uniqid(rand(), true));
                        $Hash4=substr($Code4, 0, 8);

                        $RepInt=$_SERVER['DOCUMENT_ROOT']."/lib/Mail/Document/";
                        $RepExt=$Home."/lib/Mail/Document/";
                        
                        //upload fichier
                        
                        $Upload4 = move_uploaded_file($FichierTmp4, $RepInt.$Hash4.$ExtOrigin4);

                        if ($Upload4==FALSE) {
                            $Erreur="Erreur de téléchargement du fichier, veuillez réassayer ultérueurement";
                        }
                        else {
                            $CheminFichier4 = $RepInt.$Hash4.$ExtOrigin4;
                            // Pièce jointe
                            $content4 = file_get_contents($CheminFichier4);
                            $content4 = chunk_split(base64_encode($content4));
                        }
                    }
                            
                    if ((isset($_FILES['fichier5']['name']))&&(!empty($_FILES['fichier5']['name']))) {
                    
                        $Fichier5=$_FILES['fichier5']['name'];
                        $FichierTmp5=$_FILES['fichier5']['tmp_name'];
                        $NomFichier5=basename($Fichier5);
                        $Taille5=filesize($FichierTmp5);
                        $ExtOrigin5=strchr($Fichier5, '.');
                        $TailleMax="20000000";
                        
                        $Code5=md5(uniqid(rand(), true));
                        $Hash5=substr($Code5, 0, 8);

                        $RepInt=$_SERVER['DOCUMENT_ROOT']."/lib/Mail/Document/";
                        $RepExt=$Home."/lib/Mail/Document/";
                        
                        //upload fichier
                        
                        $Upload5 = move_uploaded_file($FichierTmp5, $RepInt.$Hash5.$ExtOrigin5);

                        if ($Upload5==FALSE) {
                            $Erreur="Erreur de téléchargement du fichier, veuillez réassayer ultérueurement";
                        }
                        else {
                            $CheminFichier5 = $RepInt.$Hash5.$ExtOrigin5;
                            // Pièce jointe
                            $content5 = file_get_contents($CheminFichier5);
                            $content5 = chunk_split(base64_encode($content5));
                        }
                    }

                    if ($_SESSION['groupe']=="Destinataire") {
                        if ((isset($_POST['destinataire']))&&(!empty($_POST['destinataire']))) {
                            $Destinataire2=$_SESSION['destinataire']=$_POST['destinataire'];

                            if (!preg_match("#^[a-z0-9._-]+@(dbmail|hotmail|live|msn).[a-z]{2,4}$#", $Destinataire2)) {
                                $passage_ligne = "\r\n";
                            }
                            else {
                                $passage_ligne = "\n";
                            }

                            $passage_ligne = "\r\n";
                            $Retour=$_SESSION['retour'];
                            $boundary = md5(uniqid(mt_rand()));

                            $Entete = "From: \"$Societe\"<$Retour>".$passage_ligne;
                            $Entete.= "Reply-to: \"$Societe\" <$Retour>".$passage_ligne;
                            $Entete.= "MIME-Version: 1.0".$passage_ligne;
                            $Entete.= "Content-Type: multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
                            
                            $message.=$passage_ligne."--$boundary".$passage_ligne;
                            $message.="Content-Type: text/html; charset=utf-8".$passage_ligne;  
                            $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;

                            $message.="<html><head>
                                        <title>".$_SESSION['objet']."</title>
                                        </head>
                                        <body>
                                        ".$_SESSION['message']."
                                        </body>
                                        </html>";
                                        
                            $message.=$passage_ligne."--$boundary".$passage_ligne; 

                            if ((isset($_FILES['fichier1']['name']))&&(!empty($_FILES['fichier1']['name']))) {

                                if ($Upload1==TRUE) {
                                    if (in_array($ExtOrigin1, array(".jpg", ".jpeg", ".jpe", ".JPG", ".JPEG", ".JPE"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".png", ".PNG"))) {                  
                                        $message.= "Content-Type: image/png;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".gif", ".GIF"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".doc", ".DOC"))) {                  
                                        $message.= "Content-Type: application/msword;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".pdf", ".PDF"))) {                  
                                        $message.= "Content-Type: application/pdf;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }  
                                    if (in_array($ExtOrigin1, array(".rtf", ".RFT"))) {                  
                                        $message.= "Content-Type: application/rtf;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".xls", ".XLS"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-excel;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".ppt", ".PPT"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-powerpoint;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }      
                                    if (in_array($ExtOrigin1, array(".zip", ".ZIP"))) {                  
                                        $message.= "Content-Type: application/zip;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    } 
                                    if (in_array($ExtOrigin1, array(".tif", ".tiff", "TIF", "TIFF"))) {                  
                                        $message.= "Content-Type: image/tiff;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".avi", ".AVI"))) {                  
                                        $message.= "Content-Type: video/msvideo;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".mov", ".qt", ".MOV", ".QT"))) {                  
                                        $message.= "Content-Type: video/quicktime;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".mpeg", ".mpg", ".mpe", ".MPEG", ".MPG", ".MPE"))) {                  
                                        $message.= "Content-Type: video/mpeg;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    
                                    $message.= "Content-Transfer-Encoding: base64".$passage_ligne;
                                    $message.= "Content-Disposition:attachment;filename=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    
                                    $message.=$passage_ligne."$content1".$passage_ligne;      
                                    
                                    $message.=$passage_ligne."--$boundary".$passage_ligne;                   
                                }
                            }
                            
                            if ((isset($_FILES['fichier2']['name']))&&(!empty($_FILES['fichier2']['name']))) {
                                if ($Upload2==TRUE) {
                                    if (in_array($ExtOrigin2, array(".jpg", ".jpeg", ".jpe", ".JPG", ".JPEG", ".JPE"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".png", ".PNG"))) {                  
                                        $message.= "Content-Type: image/png;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".gif", ".GIF"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".doc", ".DOC"))) {                  
                                        $message.= "Content-Type: application/msword;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".pdf", ".PDF"))) {                  
                                        $message.= "Content-Type: application/pdf;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }  
                                    if (in_array($ExtOrigin2, array(".rtf", ".RFT"))) {                  
                                        $message.= "Content-Type: application/rtf;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".xls", ".XLS"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-excel;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".ppt", ".PPT"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-powerpoint;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }      
                                    if (in_array($ExtOrigin2, array(".zip", ".ZIP"))) {                  
                                        $message.= "Content-Type: application/zip;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    } 
                                    if (in_array($ExtOrigin2, array(".tif", ".tiff", "TIF", "TIFF"))) {                  
                                        $message.= "Content-Type: image/tiff;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".avi", ".AVI"))) {                  
                                        $message.= "Content-Type: video/msvideo;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".mov", ".qt", ".MOV", ".QT"))) {                  
                                        $message.= "Content-Type: video/quicktime;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".mpeg", ".mpg", ".mpe", ".MPEG", ".MPG", ".MPE"))) {                  
                                        $message.= "Content-Type: video/mpeg;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    
                                    $message.= "Content-Transfer-Encoding: base64".$passage_ligne;
                                    $message.= "Content-Disposition:attachment;filename=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    
                                    $message.=$passage_ligne."$content2".$passage_ligne;      
                                    
                                    $message.=$passage_ligne."--$boundary".$passage_ligne;                          
                                }
                            }
                            
                            if ((isset($_FILES['fichier3']['name']))&&(!empty($_FILES['fichier3']['name']))) {
                                if ($Upload3==TRUE) {
                                    if (in_array($ExtOrigin3, array(".jpg", ".jpeg", ".jpe", ".JPG", ".JPEG", ".JPE"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".png", ".PNG"))) {                  
                                        $message.= "Content-Type: image/png;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".gif", ".GIF"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".doc", ".DOC"))) {                  
                                        $message.= "Content-Type: application/msword;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".pdf", ".PDF"))) {                  
                                        $message.= "Content-Type: application/pdf;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }  
                                    if (in_array($ExtOrigin3, array(".rtf", ".RFT"))) {                  
                                        $message.= "Content-Type: application/rtf;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".xls", ".XLS"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-excel;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".ppt", ".PPT"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-powerpoint;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }      
                                    if (in_array($ExtOrigin3, array(".zip", ".ZIP"))) {                  
                                        $message.= "Content-Type: application/zip;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    } 
                                    if (in_array($ExtOrigin3, array(".tif", ".tiff", "TIF", "TIFF"))) {                  
                                        $message.= "Content-Type: image/tiff;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".avi", ".AVI"))) {                  
                                        $message.= "Content-Type: video/msvideo;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".mov", ".qt", ".MOV", ".QT"))) {                  
                                        $message.= "Content-Type: video/quicktime;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".mpeg", ".mpg", ".mpe", ".MPEG", ".MPG", ".MPE"))) {                  
                                        $message.= "Content-Type: video/mpeg;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    
                                    $message.= "Content-Transfer-Encoding: base64".$passage_ligne;
                                    $message.= "Content-Disposition:attachment;filename=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    
                                    $message.=$passage_ligne."$content3".$passage_ligne;      
                                    
                                    $message.=$passage_ligne."--$boundary".$passage_ligne;                              
                                }
                            }
                            
                            if ((isset($_FILES['fichier4']['name']))&&(!empty($_FILES['fichier4']['name']))) {
                                if ($Upload4==TRUE) {
                                    if (in_array($ExtOrigin4, array(".jpg", ".jpeg", ".jpe", ".JPG", ".JPEG", ".JPE"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".png", ".PNG"))) {                  
                                        $message.= "Content-Type: image/png;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".gif", ".GIF"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".doc", ".DOC"))) {                  
                                        $message.= "Content-Type: application/msword;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".pdf", ".PDF"))) {                  
                                        $message.= "Content-Type: application/pdf;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }  
                                    if (in_array($ExtOrigin4, array(".rtf", ".RFT"))) {                  
                                        $message.= "Content-Type: application/rtf;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".xls", ".XLS"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-excel;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".ppt", ".PPT"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-powerpoint;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }      
                                    if (in_array($ExtOrigin4, array(".zip", ".ZIP"))) {                  
                                        $message.= "Content-Type: application/zip;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    } 
                                    if (in_array($ExtOrigin4, array(".tif", ".tiff", "TIF", "TIFF"))) {                  
                                        $message.= "Content-Type: image/tiff;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".avi", ".AVI"))) {                  
                                        $message.= "Content-Type: video/msvideo;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".mov", ".qt", ".MOV", ".QT"))) {                  
                                        $message.= "Content-Type: video/quicktime;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".mpeg", ".mpg", ".mpe", ".MPEG", ".MPG", ".MPE"))) {                  
                                        $message.= "Content-Type: video/mpeg;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    
                                    $message.= "Content-Transfer-Encoding: base64".$passage_ligne;
                                    $message.= "Content-Disposition:attachment;filename=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    
                                    $message.=$passage_ligne."$content4".$passage_ligne;      
                                    
                                    $message.=$passage_ligne."--$boundary".$passage_ligne;                                
                                }
                            }
                            
                            if ((isset($_FILES['fichier5']['name']))&&(!empty($_FILES['fichier5']['name']))) {
                                if ($Upload5==TRUE) {
                                    if (in_array($ExtOrigin5, array(".jpg", ".jpeg", ".jpe", ".JPG", ".JPEG", ".JPE"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".png", ".PNG"))) {                  
                                        $message.= "Content-Type: image/png;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".gif", ".GIF"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".doc", ".DOC"))) {                  
                                        $message.= "Content-Type: application/msword;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".pdf", ".PDF"))) {                  
                                        $message.= "Content-Type: application/pdf;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }  
                                    if (in_array($ExtOrigin5, array(".rtf", ".RFT"))) {                  
                                        $message.= "Content-Type: application/rtf;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".xls", ".XLS"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-excel;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".ppt", ".PPT"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-powerpoint;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }      
                                    if (in_array($ExtOrigin5, array(".zip", ".ZIP"))) {                  
                                        $message.= "Content-Type: application/zip;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    } 
                                    if (in_array($ExtOrigin5, array(".tif", ".tiff", "TIF", "TIFF"))) {                  
                                        $message.= "Content-Type: image/tiff;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".avi", ".AVI"))) {                  
                                        $message.= "Content-Type: video/msvideo;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".mov", ".qt", ".MOV", ".QT"))) {                  
                                        $message.= "Content-Type: video/quicktime;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".mpeg", ".mpg", ".mpe", ".MPEG", ".MPG", ".MPE"))) {                  
                                        $message.= "Content-Type: video/mpeg;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    
                                    $message.= "Content-Transfer-Encoding: base64".$passage_ligne;
                                    $message.= "Content-Disposition:attachment;filename=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    
                                    $message.=$passage_ligne."$content5".$passage_ligne;      
                                    
                                    $message.=$passage_ligne."--$boundary".$passage_ligne;                          
                                }
                            }

                            if (mail($Destinataire2, $_SESSION['objet'], $message, $Entete)===FALSE) {
                                $Erreur = "L'e-mail n'a pu être envoyé, vérifiez que vous l'avez entré correctement !";
                            }
                            else {     
                                //Ajout historique
                                $Insert=$cnx->prepare("INSERT INTO ".$Prefix."_mailing_Historique (destinataire, objet, message, retour, type, created) VALUES(:destinataire, :objet, :message, :retour, :type, :created)");
                                $Insert->BindParam(":destinataire", $Destinataire2, PDO::PARAM_STR);
                                $Insert->BindParam(":objet", $_SESSION['objet'], PDO::PARAM_STR);
                                $Insert->BindParam(":message", $_SESSION['message'], PDO::PARAM_STR);
                                $Insert->BindParam(":retour", $_SESSION['retour'], PDO::PARAM_STR);
                                $Insert->BindParam(":type", $_SESSION['type'], PDO::PARAM_STR);
                                $Insert->BindParam(":created", $Now, PDO::PARAM_STR);
                                $Insert->execute();

                                unset($_SESSION['type']);
                                unset($_SESSION['signature']);
                                unset($_SESSION['groupe']);
                                unset($_SESSION['objet']);
                                unset($_SESSION['message']);
                                unset($_SESSION['retour']);

                                $Valid="Votre message a bien été envoyé !";
                                header("location:".$Home."/Admin/Mailing/Envoyer/?valid=".urlencode($Valid));
                            }
                        }
                    }
                    else {
                        $SelectDesti=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Liste_Diffusion WHERE liste=:liste AND diffusion=1");
                        $SelectDesti->bindParam(':liste', $_SESSION['groupe'], PDO::PARAM_STR);
                        $SelectDesti->execute();

                        while($Desti=$SelectDesti->fetch(PDO::FETCH_OBJ)) {

                            if (!preg_match("#^[a-z0-9._-]+@(dbmail|hotmail|live|msn).[a-z]{2,4}$#", $Desti->email)) {
                                $passage_ligne = "\r\n";
                            }
                            else {
                                $passage_ligne = "\n";
                            }

                            $passage_ligne = "\r\n";
                            $Retour=$_SESSION['retour'];
                            $boundary = md5(uniqid(mt_rand()));

                            $Entete = "From: \"$Societe\"<$Retour>".$passage_ligne;
                            $Entete.= "Reply-to: \"$Societe\" <$Retour>".$passage_ligne;
                            $Entete.= "MIME-Version: 1.0".$passage_ligne;
                            $Entete.= "Content-Type: multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
                            
                            $message.=$passage_ligne."--$boundary".$passage_ligne;
                            $message.="Content-Type: text/html; charset=utf-8".$passage_ligne;  
                            $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;

                            $message.="<html><head>
                                        <title>".$_SESSION['objet']."</title>
                                        </head>
                                        <body>
                                        ".$_SESSION['message']."
                                        </body>
                                        </html>";
                                        
                            $message.=$passage_ligne."--$boundary".$passage_ligne; 

                            if ((isset($_FILES['fichier1']['name']))&&(!empty($_FILES['fichier1']['name']))) {

                                if ($Upload1==TRUE) {
                                    if (in_array($ExtOrigin1, array(".jpg", ".jpeg", ".jpe", ".JPG", ".JPEG", ".JPE"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".png", ".PNG"))) {                  
                                        $message.= "Content-Type: image/png;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".gif", ".GIF"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".doc", ".DOC"))) {                  
                                        $message.= "Content-Type: application/msword;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".pdf", ".PDF"))) {                  
                                        $message.= "Content-Type: application/pdf;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }  
                                    if (in_array($ExtOrigin1, array(".rtf", ".RFT"))) {                  
                                        $message.= "Content-Type: application/rtf;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".xls", ".XLS"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-excel;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".ppt", ".PPT"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-powerpoint;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }      
                                    if (in_array($ExtOrigin1, array(".zip", ".ZIP"))) {                  
                                        $message.= "Content-Type: application/zip;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    } 
                                    if (in_array($ExtOrigin1, array(".tif", ".tiff", "TIF", "TIFF"))) {                  
                                        $message.= "Content-Type: image/tiff;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".avi", ".AVI"))) {                  
                                        $message.= "Content-Type: video/msvideo;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".mov", ".qt", ".MOV", ".QT"))) {                  
                                        $message.= "Content-Type: video/quicktime;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin1, array(".mpeg", ".mpg", ".mpe", ".MPEG", ".MPG", ".MPE"))) {                  
                                        $message.= "Content-Type: video/mpeg;name=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    }
                                    
                                    $message.= "Content-Transfer-Encoding: base64".$passage_ligne;
                                    $message.= "Content-Disposition:attachment;filename=\"$Hash1$ExtOrigin1\"".$passage_ligne;
                                    
                                    $message.=$passage_ligne."$content1".$passage_ligne;      
                                    
                                    $message.=$passage_ligne."--$boundary".$passage_ligne;                   
                                }
                            }
                            
                            if ((isset($_FILES['fichier2']['name']))&&(!empty($_FILES['fichier2']['name']))) {
                                if ($Upload2==TRUE) {
                                    if (in_array($ExtOrigin2, array(".jpg", ".jpeg", ".jpe", ".JPG", ".JPEG", ".JPE"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".png", ".PNG"))) {                  
                                        $message.= "Content-Type: image/png;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".gif", ".GIF"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".doc", ".DOC"))) {                  
                                        $message.= "Content-Type: application/msword;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".pdf", ".PDF"))) {                  
                                        $message.= "Content-Type: application/pdf;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }  
                                    if (in_array($ExtOrigin2, array(".rtf", ".RFT"))) {                  
                                        $message.= "Content-Type: application/rtf;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".xls", ".XLS"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-excel;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".ppt", ".PPT"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-powerpoint;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }      
                                    if (in_array($ExtOrigin2, array(".zip", ".ZIP"))) {                  
                                        $message.= "Content-Type: application/zip;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    } 
                                    if (in_array($ExtOrigin2, array(".tif", ".tiff", "TIF", "TIFF"))) {                  
                                        $message.= "Content-Type: image/tiff;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".avi", ".AVI"))) {                  
                                        $message.= "Content-Type: video/msvideo;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".mov", ".qt", ".MOV", ".QT"))) {                  
                                        $message.= "Content-Type: video/quicktime;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin2, array(".mpeg", ".mpg", ".mpe", ".MPEG", ".MPG", ".MPE"))) {                  
                                        $message.= "Content-Type: video/mpeg;name=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    }
                                    
                                    $message.= "Content-Transfer-Encoding: base64".$passage_ligne;
                                    $message.= "Content-Disposition:attachment;filename=\"$Hash2$ExtOrigin2\"".$passage_ligne;
                                    
                                    $message.=$passage_ligne."$content2".$passage_ligne;      
                                    
                                    $message.=$passage_ligne."--$boundary".$passage_ligne;                          
                                }
                            }
                            
                            if ((isset($_FILES['fichier3']['name']))&&(!empty($_FILES['fichier3']['name']))) {
                                if ($Upload3==TRUE) {
                                    if (in_array($ExtOrigin3, array(".jpg", ".jpeg", ".jpe", ".JPG", ".JPEG", ".JPE"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".png", ".PNG"))) {                  
                                        $message.= "Content-Type: image/png;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".gif", ".GIF"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".doc", ".DOC"))) {                  
                                        $message.= "Content-Type: application/msword;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".pdf", ".PDF"))) {                  
                                        $message.= "Content-Type: application/pdf;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }  
                                    if (in_array($ExtOrigin3, array(".rtf", ".RFT"))) {                  
                                        $message.= "Content-Type: application/rtf;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".xls", ".XLS"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-excel;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".ppt", ".PPT"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-powerpoint;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }      
                                    if (in_array($ExtOrigin3, array(".zip", ".ZIP"))) {                  
                                        $message.= "Content-Type: application/zip;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    } 
                                    if (in_array($ExtOrigin3, array(".tif", ".tiff", "TIF", "TIFF"))) {                  
                                        $message.= "Content-Type: image/tiff;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".avi", ".AVI"))) {                  
                                        $message.= "Content-Type: video/msvideo;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".mov", ".qt", ".MOV", ".QT"))) {                  
                                        $message.= "Content-Type: video/quicktime;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin3, array(".mpeg", ".mpg", ".mpe", ".MPEG", ".MPG", ".MPE"))) {                  
                                        $message.= "Content-Type: video/mpeg;name=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    }
                                    
                                    $message.= "Content-Transfer-Encoding: base64".$passage_ligne;
                                    $message.= "Content-Disposition:attachment;filename=\"$Hash3$ExtOrigin3\"".$passage_ligne;
                                    
                                    $message.=$passage_ligne."$content3".$passage_ligne;      
                                    
                                    $message.=$passage_ligne."--$boundary".$passage_ligne;                              
                                }
                            }
                            
                            if ((isset($_FILES['fichier4']['name']))&&(!empty($_FILES['fichier4']['name']))) {
                                if ($Upload4==TRUE) {
                                    if (in_array($ExtOrigin4, array(".jpg", ".jpeg", ".jpe", ".JPG", ".JPEG", ".JPE"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".png", ".PNG"))) {                  
                                        $message.= "Content-Type: image/png;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".gif", ".GIF"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".doc", ".DOC"))) {                  
                                        $message.= "Content-Type: application/msword;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".pdf", ".PDF"))) {                  
                                        $message.= "Content-Type: application/pdf;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }  
                                    if (in_array($ExtOrigin4, array(".rtf", ".RFT"))) {                  
                                        $message.= "Content-Type: application/rtf;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".xls", ".XLS"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-excel;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".ppt", ".PPT"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-powerpoint;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }      
                                    if (in_array($ExtOrigin4, array(".zip", ".ZIP"))) {                  
                                        $message.= "Content-Type: application/zip;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    } 
                                    if (in_array($ExtOrigin4, array(".tif", ".tiff", "TIF", "TIFF"))) {                  
                                        $message.= "Content-Type: image/tiff;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".avi", ".AVI"))) {                  
                                        $message.= "Content-Type: video/msvideo;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".mov", ".qt", ".MOV", ".QT"))) {                  
                                        $message.= "Content-Type: video/quicktime;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin4, array(".mpeg", ".mpg", ".mpe", ".MPEG", ".MPG", ".MPE"))) {                  
                                        $message.= "Content-Type: video/mpeg;name=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    }
                                    
                                    $message.= "Content-Transfer-Encoding: base64".$passage_ligne;
                                    $message.= "Content-Disposition:attachment;filename=\"$Hash4$ExtOrigin4\"".$passage_ligne;
                                    
                                    $message.=$passage_ligne."$content4".$passage_ligne;      
                                    
                                    $message.=$passage_ligne."--$boundary".$passage_ligne;                                
                                }
                            }
                            
                            if ((isset($_FILES['fichier5']['name']))&&(!empty($_FILES['fichier5']['name']))) {
                                if ($Upload5==TRUE) {
                                    if (in_array($ExtOrigin5, array(".jpg", ".jpeg", ".jpe", ".JPG", ".JPEG", ".JPE"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".png", ".PNG"))) {                  
                                        $message.= "Content-Type: image/png;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".gif", ".GIF"))) {                  
                                        $message.= "Content-Type: image/jpeg;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".doc", ".DOC"))) {                  
                                        $message.= "Content-Type: application/msword;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".pdf", ".PDF"))) {                  
                                        $message.= "Content-Type: application/pdf;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }  
                                    if (in_array($ExtOrigin5, array(".rtf", ".RFT"))) {                  
                                        $message.= "Content-Type: application/rtf;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".xls", ".XLS"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-excel;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".ppt", ".PPT"))) {                  
                                        $message.= "Content-Type: application/vnd.ms-powerpoint;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }      
                                    if (in_array($ExtOrigin5, array(".zip", ".ZIP"))) {                  
                                        $message.= "Content-Type: application/zip;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    } 
                                    if (in_array($ExtOrigin5, array(".tif", ".tiff", "TIF", "TIFF"))) {                  
                                        $message.= "Content-Type: image/tiff;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".avi", ".AVI"))) {                  
                                        $message.= "Content-Type: video/msvideo;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".mov", ".qt", ".MOV", ".QT"))) {                  
                                        $message.= "Content-Type: video/quicktime;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    if (in_array($ExtOrigin5, array(".mpeg", ".mpg", ".mpe", ".MPEG", ".MPG", ".MPE"))) {                  
                                        $message.= "Content-Type: video/mpeg;name=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    }
                                    
                                    $message.= "Content-Transfer-Encoding: base64".$passage_ligne;
                                    $message.= "Content-Disposition:attachment;filename=\"$Hash5$ExtOrigin5\"".$passage_ligne;
                                    
                                    $message.=$passage_ligne."$content5".$passage_ligne;      
                                    
                                    $message.=$passage_ligne."--$boundary".$passage_ligne;                          
                                }
                            }

                            if (mail($Desti->email, $_SESSION['objet'], $message, $Entete)===FALSE) {
                                $Erreur = "L'e-mail n'a pu être envoyé, vérifiez que vous l'avez entré correctement !";
                                header("location:".$Home."/Admin/Mailing/Envoyer/?erreur=".urlencode($Erreur));
                            }
                            //Ajout historique
                            $Insert=$cnx->prepare("INSERT INTO ".$Prefix."_mailing_Historique (destinataire, objet, message, retour, type, created) VALUES(:destinataire, :objet, :message, :retour, :type, :created)");
                            $Insert->BindParam(":destinataire", $Desti->email, PDO::PARAM_STR);
                            $Insert->BindParam(":objet", $_SESSION['objet'], PDO::PARAM_STR);
                            $Insert->BindParam(":message", $_SESSION['message'], PDO::PARAM_STR);
                            $Insert->BindParam(":retour", $_SESSION['retour'], PDO::PARAM_STR);
                            $Insert->BindParam(":type", $_SESSION['type'], PDO::PARAM_STR);
                            $Insert->BindParam(":created", $Now, PDO::PARAM_STR);
                            $Insert->execute();
                        }

                        unset($_SESSION['type']);
                        unset($_SESSION['signature']);
                        unset($_SESSION['groupe']);
                        unset($_SESSION['objet']);
                        unset($_SESSION['message']);
                        unset($_SESSION['retour']);

                        $Valid="Votre message a bien été envoyé !";
                        header("location:".$Home."/Admin/Mailing/Envoyer/?valid=".urlencode($Valid));
                    }
                }
            }
            else {
                $Erreur="L'adresse e-mail de retour n'est pas conforme !";
            }  
        }
        else {
            $Erreur="Veuillez entrer un message !";
        }
    }
    else {
        $Erreur="Veuillez entrer un objet de message !";
    }
}
    
?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/head.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/header.inc.php"); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/menu.inc.php"); ?>

<article>
<?php if (isset($Erreur)) { echo "<p><font color='#FF0000'>".urldecode($Erreur)."</font><BR /><BR />"; }
if (isset($Valid)) { echo "<p><font color='#009900'>".urldecode($Valid)."</font><BR /><BR />"; }   ?>

<div id="Form_Middle3">
<H1>Envoyer un e-mail</H1>

<form name="form" action="" method="POST" >
<select name="type" id="type" onChange="submit()">
<option value="NULL">-- Modèle --</option>
<?php 
$mailing=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Predefini");    
$mailing->execute(); 
while($Model=$mailing->fetch(PDO::FETCH_OBJ)) { ?>
    <option value="<?php echo $Model->id; ?>" <?php if ($Model->id==$_SESSION['type']) { echo "selected"; } ?> ><?php echo $Model->libele; ?></option>
<?php } ?>
</select><BR />
</form>

<form name="form" action="" method="POST" >
<select name="signature" id="signature" onChange="submit()">
<option value="NULL">-- Signature --</option>
<?php 
$SignatureListe=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Signature");    
$SignatureListe->execute(); 
while($ListeSignature=$SignatureListe->fetch(PDO::FETCH_OBJ)) { ?>
    <option value="<?php echo $ListeSignature->id; ?>" <?php if ($ListeSignature->id==$_SESSION['signature']) { echo "selected"; } ?> ><?php echo $ListeSignature->libelle; ?></option>
<?php } ?>
</select><BR />
</form>

<form name="form" action="" method="POST" >
<select name="groupe" id="groupe" onChange="submit()">
<option value="NULL">-- Liste de diffusion --</option>
<option value="Destinataire" <?php if ($_SESSION['groupe']=="Destinataire") { echo "selected"; } ?> >Destinataire</option>
<?php 
$GroupeListe=$cnx->prepare("SELECT * FROM ".$Prefix."_mailing_Groupe");    
$GroupeListe->execute(); 
while($Groupe=$GroupeListe->fetch(PDO::FETCH_OBJ)) { ?>
    <option value="<?php echo $Groupe->liste; ?>" <?php if ($Groupe->liste==$_SESSION['groupe']) { echo "selected"; } ?> ><?php echo $Groupe->liste; ?></option>
<?php } ?>
</select><BR /><BR />
</form>

<form name="form_mail" action="" method="POST" enctype="multipart/form-data">

<?php 
if ($_SESSION['groupe']=="Destinataire") { ?>
    <input type="text" placeholder="Destinataire :" name="destinataire" require="required" value="<?php echo $Destinataire2; ?>"/><BR />
<?php }
?>
<input type="text" placeholder="Objet :" name="objet" require="required" value="<?php echo $_SESSION['objet']; ?>"/><BR />
<input type="text" placeholder="Adresse de retour" name="retour" value="<?php echo $ParamEmail->email; ?>" require="required"/><BR /><BR />

<input type="file"  placeholder="pièce jointe 1" name="fichier1"/><BR />
<input type="file"  placeholder="pièce jointe 2" name="fichier2"/><BR />
<input type="file"  placeholder="pièce jointe 3" name="fichier3"/><BR />
<input type="file"  placeholder="pièce jointe 4" name="fichier4"/><BR />
<input type="file"  placeholder="pièce jointe 5" name="fichier5"/><BR /><BR />

<textarea id="message" name="message" placeholder="Message*" require="required">
<?php echo $Param->mailing ?>
<BR /><BR />
<?php echo $ParamSignature->signature ?>
</textarea><BR />

<input type="submit" name="Envoyer" value="Envoyer"/>
</form>
</div>

</article>

<?php require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/footer.inc.php"); ?>