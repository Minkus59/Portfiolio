<?php

require_once($_SERVER['DOCUMENT_ROOT']."/Admin/impinfbdd/config.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/fonction_perso.inc.php");  
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/redirect.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Admin/lib/script/requete.inc.php");


if ($Cnx_Admin!=TRUE) {
  header('location:'.HOME.'/Admin');
}

require_once($_SERVER['DOCUMENT_ROOT']."/frontend/lib/FPDF/fpdf.php");
require_once($_SERVER['DOCUMENT_ROOT']."/frontend/lib/FPDI-1.6.1/fpdi.php");

$SelectFichier=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Tampon ORDER BY id DESC");
$SelectFichier->execute();

$Now=time();
$Date=date('d/m/Y', $Now);

if (isset($_POST['oui'])) {
    $Id=$_GET['id'];
    $_SESSION['hash']=$Id;
    $_SESSION['suppr']=1;
}
if (isset($_POST['non'])) {  
    $Id=$_GET['id'];
    $_SESSION['hash']=$Id;
    $_SESSION['suppr']=2;
}

if (isset($_POST['Signer'])) {
    $Horizontal=$_POST['horizontal'];
    $Vertical=$_POST['vertical'];
    $Page=$_POST['page'];
    $HashOriginal=$_SESSION['hash'];
    $Action=$_POST['action'];

    $DocumentOriginal=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Signer WHERE hash=:hash");
    $DocumentOriginal->BindParam(":hash", $HashOriginal, PDO::PARAM_STR);
    $DocumentOriginal->execute();
    $Fichier=$DocumentOriginal->fetch(PDO::FETCH_OBJ);

    //Changement de hash pour resigner
    $Code=md5(uniqid(rand(), true));
    $_SESSION['hash2']=substr($Code, 0, 8);
    $HashSigner=$_SESSION['hash2'];

    $NomFichier=substr($Fichier->fichier, 9);
    $NomFichier=preg_replace(array('/.pdf/', '/.PDF/'), "", $NomFichier);
    $NomFichier=$HashSigner."-".$NomFichier;
    $NomFichierPdf=$NomFichier.".pdf";

    $NoPage=0;
    $pdf = new FPDI();                        
    $pageCount = $pdf->setSourceFile($repIntSigner.$Fichier->fichier);
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

        $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."Signature_Signer_Jpg (fichier, page, created, hash) VALUES (:fichier, :page, :created, :hash)");
        $Insert->BindParam(":fichier", $FichierJpg, PDO::PARAM_STR);
        $Insert->BindParam(":page", $pageNo, PDO::PARAM_STR);
        $Insert->BindParam(":created", $Now, PDO::PARAM_STR);
        $Insert->BindParam(":hash", $HashSigner, PDO::PARAM_STR);
        $Insert->execute();

        if ($Page=="All") {
            if ($Action=="Tampon") {                                    
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($Tampon,$Horizontal, $Vertical,'PNG');
            }
            elseif ($Action=="Signature") {                                    
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($Signature,$Horizontal, $Vertical,'PNG');
            }
            elseif ($Action=="Date") {            
                $pdf->SetFont('Arial','',11);      
                $pdf->SetTextColor(255, 0, 0);                   
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->cell(15, 4, $Date, 0, 0, 'L');
            }
            else {
                $SelectTamponPerso=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Tampon WHERE id=:id");
                $SelectTamponPerso->BindParam(':id', $Action, PDO::PARAM_STR);
                $SelectTamponPerso->execute();
                $TamponPerso=$SelectTamponPerso->fetch(PDO::FETCH_OBJ);   

                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($repExtTampon.$TamponPerso->lien ,$Horizontal, $Vertical,'PNG');
            }
        }
        if ($Page==$pageNo) {
            if ($Action=="Tampon") {                                    
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($Tampon ,$Horizontal, $Vertical,'PNG');
            }
            elseif ($Action=="Signature") {                                    
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($Signature,$Horizontal, $Vertical,'PNG');
            }
            elseif ($Action=="Date") {            
                $pdf->SetFont('Arial','',11);      
                $pdf->SetTextColor(255, 0, 0);                      
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->cell(15, 4, $Date, 0, 0, 'L');
            }
            else {
                $SelectTamponPerso=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Tampon WHERE id=:id");
                $SelectTamponPerso->BindParam(':id', $Action, PDO::PARAM_STR);
                $SelectTamponPerso->execute();
                $TamponPerso=$SelectTamponPerso->fetch(PDO::FETCH_OBJ);   

                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($repExtTampon.$TamponPerso->lien ,$Horizontal, $Vertical,'PNG');
            }
        }
        $NoPage++;
    }

    if ($Page=="Last") {
            if ($Action=="Tampon") {                                    
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($Tampon ,$Horizontal, $Vertical,'PNG');
            }
            elseif ($Action=="Signature") {                                    
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($Signature,$Horizontal, $Vertical,'PNG');
            }
            elseif ($Action=="Date") {            
                $pdf->SetFont('Arial','',11);      
                $pdf->SetTextColor(255, 0, 0);                        
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->cell(15, 4, $Date, 0, 0, 'L');
            }
            else {
                $SelectTamponPerso=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Tampon WHERE id=:id");
                $SelectTamponPerso->BindParam(':id', $Action, PDO::PARAM_STR);
                $SelectTamponPerso->execute();
                $TamponPerso=$SelectTamponPerso->fetch(PDO::FETCH_OBJ);   

                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($repExtTampon.$TamponPerso->lien ,$Horizontal, $Vertical,'PNG');
            }
    }

    $pdf->Output($repIntSigner.$NomFichierPdf, "F");

    $pdf_file = $repIntSigner.$NomFichierPdf;
    $save_to = $repIntJpgSigner.$FichierJpg2;
    exec('convert "'.$pdf_file.'" -colorspace RGB -resize 800 "'.$save_to.'"', $output, $return_var);
    
    if($return_var != 0) {
        $Erreur= "Erreur de chargement des images, veuillez réassayer !<BR />";
    }

    $Insert=$cnx->prepare("INSERT INTO ".DB_PREFIX."Signature_Signer (fichier, page, created, hash) VALUES (:fichier, :page, :created, :hash)");
    $Insert->BindParam(":fichier", $NomFichierPdf, PDO::PARAM_STR);
    $Insert->BindParam(":page", $Page, PDO::PARAM_STR);
    $Insert->BindParam(":created", $Now, PDO::PARAM_STR);
    $Insert->BindParam(":hash", $HashSigner, PDO::PARAM_STR);
    $Insert->execute();

    $Valid="Document signer avec succès";
}

if (isset($_POST['Ajuster'])) {
    $Horizontal=$_POST['horizontal'];
    $Vertical=$_POST['vertical'];
    $Page=$_POST['page'];
    $Action=$_POST['action'];
    $HashOriginal=$_SESSION['hash'];

    $DocumentOriginal=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Signer WHERE hash=:hash");
    $DocumentOriginal->BindParam(":hash", $HashOriginal, PDO::PARAM_STR);
    $DocumentOriginal->execute();
    $Fichier=$DocumentOriginal->fetch(PDO::FETCH_OBJ);

    //Changement de hash pour resigner
    $HashSigner=$_SESSION['hash2'];

    $NomFichier=substr($Fichier->fichier, 9);
    $NomFichier=preg_replace(array('/.pdf/', '/.PDF/'), "", $NomFichier);
    $NomFichier=$HashSigner."-".$NomFichier;
    $NomFichierPdf=$NomFichier.".pdf";

    $NoPage=0;
    $pdf = new FPDI();                        
    $pageCount = $pdf->setSourceFile($repIntSigner.$Fichier->fichier);
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

        if ($Page=="All") {
            if ($Action=="Tampon") {                                    
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($Tampon ,$Horizontal, $Vertical,'PNG');
            }
            elseif ($Action=="Signature") {                                    
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($Signature,$Horizontal, $Vertical,'PNG');
            }
            elseif ($Action=="Date") {            
                $pdf->SetFont('Arial','',11);      
                $pdf->SetTextColor(255, 0, 0);                       
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->cell(15, 4, $Date, 0, 0, 'L');
            }
            else {
                $SelectTamponPerso=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Tampon WHERE id=:id");
                $SelectTamponPerso->BindParam(':id', $Action, PDO::PARAM_STR);
                $SelectTamponPerso->execute();
                $TamponPerso=$SelectTamponPerso->fetch(PDO::FETCH_OBJ);   

                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($repExtTampon.$TamponPerso->lien ,$Horizontal, $Vertical,'PNG');
            }
        }
        if ($Page==$pageNo) {
            if ($Action=="Tampon") {                                    
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($Tampon ,$Horizontal, $Vertical,'PNG');
            }
            elseif ($Action=="Signature") {                                    
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($Signature,$Horizontal, $Vertical,'PNG');
            }
            elseif ($Action=="Date") {            
                $pdf->SetFont('Arial','',11);      
                $pdf->SetTextColor(255, 0, 0);                       
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->cell(15, 4, $Date, 0, 0, 'L');
            }
            else {
                $SelectTamponPerso=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Tampon WHERE id=:id");
                $SelectTamponPerso->BindParam(':id', $Action, PDO::PARAM_STR);
                $SelectTamponPerso->execute();
                $TamponPerso=$SelectTamponPerso->fetch(PDO::FETCH_OBJ);   

                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($repExtTampon.$TamponPerso->lien ,$Horizontal, $Vertical,'PNG');
            }
        }
        $NoPage++;
    }

    if ($Page=="Last") {
            if ($Action=="Tampon") {                                    
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($Tampon ,$Horizontal, $Vertical,'PNG');
            }
            elseif ($Action=="Signature") {                                    
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($Signature,$Horizontal, $Vertical,'PNG');
            }
            elseif ($Action=="Date") {            
                $pdf->SetFont('Arial','',11);      
                $pdf->SetTextColor(255, 0, 0);                        
                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->cell(15, 4, $Date, 0, 0, 'L');
            }
            else {
                $SelectTamponPerso=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Tampon WHERE id=:id");
                $SelectTamponPerso->BindParam(':id', $Action, PDO::PARAM_STR);
                $SelectTamponPerso->execute();
                $TamponPerso=$SelectTamponPerso->fetch(PDO::FETCH_OBJ);   

                $pdf->SetXY($Horizontal, $Vertical);
                $pdf->Image($repExtTampon.$TamponPerso->lien ,$Horizontal, $Vertical,'PNG');
            }
    }

    $pdf->Output($repIntSigner.$NomFichierPdf, "F");

    $pdf_file = $repIntSigner.$NomFichierPdf;
    $save_to = $repIntJpgSigner.$FichierJpg2;
    exec('convert "'.$pdf_file.'" -colorspace RGB -resize 800 "'.$save_to.'"', $output, $return_var);
    
    if($return_var != 0) {
        $Erreur= "Erreur de chargement des images, veuillez réassayer !<BR />";
    }

    if ($_SESSION['suppr']==1) {
        unlink($_SERVER['DOCUMENT_ROOT']."/lib/Document/Signer/".$Fichier->fichier);

        $Suppr=$cnx->prepare("DELETE FROM ".DB_PREFIX."Signature_Signer WHERE hash=:hash");
        $Suppr->bindParam(':hash', $HashOriginal, PDO::PARAM_INT);
        $Suppr->execute();

        $SelectArchiveJpg = $cnx -> prepare("SELECT * FROM ".DB_PREFIX."Signature_Signer_Jpg WHERE hash=:hash");
        $SelectArchiveJpg-> BindParam(":hash", $HashOriginal, PDO::PARAM_STR);
        $SelectArchiveJpg-> execute(); 
        
        while ($ArchiveJpg=$SelectArchiveJpg->fetch(PDO::FETCH_OBJ)) {
            unlink($_SERVER['DOCUMENT_ROOT']."/lib/Document/Jpg/Signer/".$ArchiveJpg->fichier);
        }

        $Delete=$cnx->prepare("DELETE FROM ".DB_PREFIX."Signature_Signer_Jpg WHERE hash=:hash)");
        $Delete->BindParam(":hash", $HashOriginal, PDO::PARAM_STR);
        $Delete->execute();

        unset($_SESSION['suppr']);
    }

    unset($_SESSION['hash']);
    unset($_SESSION['hash2']);

    $Valid="Document Ajuster avec succès";
    header('refresh: 0; url='.HOME.'/Admin/Signature/?valid='.urlencode($Valid));
}

if (isset($_SESSION['hash2'])) {  
    $DocumentJpg=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Signer_Jpg WHERE hash=:hash");
    $DocumentJpg->BindParam(":hash", $_SESSION['hash2'], PDO::PARAM_STR);
    $DocumentJpg->execute();
    $CountPage=$DocumentJpg->rowCount();
}
else {
    $DocumentJpg=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Signer_Jpg WHERE hash=:hash");
    $DocumentJpg->BindParam(":hash", $_SESSION['hash'], PDO::PARAM_STR);
    $DocumentJpg->execute();
    $CountPage=$DocumentJpg->rowCount();
}

if (isset($_POST['Terminer'])) {
    if ($_SESSION['suppr']==1) {
        $DocumentOriginal=$cnx->prepare("SELECT * FROM ".DB_PREFIX."Signature_Signer WHERE hash=:hash");
        $DocumentOriginal->BindParam(":hash", $_SESSION['hash'], PDO::PARAM_STR);
        $DocumentOriginal->execute();
        $Fichier=$DocumentOriginal->fetch(PDO::FETCH_OBJ);

        unlink($_SERVER['DOCUMENT_ROOT']."/lib/Document/Signer/".$Fichier->fichier);

        $Suppr=$cnx->prepare("DELETE FROM ".DB_PREFIX."Signature_Signer WHERE hash=:hash");
        $Suppr->bindParam(':hash', $_SESSION['hash'], PDO::PARAM_INT);
        $Suppr->execute();

        $SelectArchiveJpg = $cnx -> prepare("SELECT * FROM ".DB_PREFIX."Signature_Signer_Jpg WHERE hash=:hash");
        $SelectArchiveJpg-> BindParam(":hash", $_SESSION['hash'], PDO::PARAM_STR);
        $SelectArchiveJpg-> execute(); 
        
        while ($ArchiveJpg=$SelectArchiveJpg->fetch(PDO::FETCH_OBJ)) {
            unlink($_SERVER['DOCUMENT_ROOT']."/lib/Document/Jpg/Signer/".$ArchiveJpg->fichier);
        }

        $Delete=$cnx->prepare("DELETE FROM ".DB_PREFIX."Signature_Signer_Jpg WHERE hash=:hash)");
        $Delete->BindParam(":hash", $_SESSION['hash'], PDO::PARAM_STR);
        $Delete->execute();

        unset($_SESSION['suppr']);
    }

    unset($_SESSION['hash']);
    unset($_SESSION['hash2']);

    header('refresh: 0; url='.HOME.'/Admin/Signature/');
}

if (isset($_POST['Annuler'])) {
    unset($_SESSION['suppr']);
    unset($_SESSION['hash']);
    unset($_SESSION['hash2']);

    header('refresh: 0; url='.HOME.'/Admin/Signature/');
}
?>
<script>
	function createInstance() {
        var req = null;
		if (window.XMLHttpRequest)
		{
 			req = new XMLHttpRequest();
		} 
		else if (window.ActiveXObject) 
		{
			try {
				req = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e)
			{
				try {
					req = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) 
				{
					alert("XHR not created");
				}
			}
	    }
        return req;
	};
</script>

<?php
if (isset($_SESSION['hash2'])) {  
    echo "
    <script>
        function submitFormAjust(element) { 
            function storing(data) {
            //envoi des element receptionne dans la div
                var element = document.getElementById('AffichagePage');
                element.innerHTML = data;
            } 
            
            var req =  createInstance();
            //recuperation des champs du formulaire
            var page = document.form_pdfAjust.page.value;
            var action = document.form_pdfAjust.action.value;
            var horizontal = document.form_pdfAjust.horizontal.value;
            var vertical = document.form_pdfAjust.vertical.value;
            //creation >> nomChamp = nomVariable & nomChamp = nomVariable etc...
            var data = 'page=' + page + '&action=' + action + '&horizontal=' + horizontal + '&vertical=' + vertical;

            req.onreadystatechange = function() { 
                if(req.readyState == 4)
                {
                    if(req.status == 200)
                    {
                        storing(req.responseText);  
                    }   
                    else    
                    {
                        alert('Error: returned status code ' + req.status + ' ' + req.statusText);
                    }   
                } 
            }; 
            
            req.open('POST', '".HOME."/Admin/Signature/Resigner/apercuSigner.php', true);
            req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            //envoi
            req.send(data);  
        }
    </script>";
}
else { 
    echo "
    <script>
        function submitFormAjust(element) { 
            function storing(data) {
            //envoi des element receptionne dans la div
                var element = document.getElementById('AffichagePage');
                element.innerHTML = data;
            } 
            
            var req =  createInstance();
            //recuperation des champs du formulaire
            var page = document.form_pdfAjust.page.value;
            var action = document.form_pdfAjust.action.value;
            var horizontal = document.form_pdfAjust.horizontal.value;
            var vertical = document.form_pdfAjust.vertical.value;
            //creation >> nomChamp = nomVariable & nomChamp = nomVariable etc...
            var data = 'page=' + page + '&action=' + action + '&horizontal=' + horizontal + '&vertical=' + vertical;

            req.onreadystatechange = function() { 
                if(req.readyState == 4)
                {
                    if(req.status == 200)
                    {
                        storing(req.responseText);  
                    }   
                    else    
                    {
                        alert('Error: returned status code ' + req.status + ' ' + req.statusText);
                    }   
                } 
            }; 
            
            req.open('POST', '".HOME."/Admin/Signature/Resigner/apercu.php', true);
            req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            //envoi
            req.send(data);  
        }
    </script>";
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

<?php if (!isset($_SESSION['hash'])) { ?>
Souhaitez vous remplacer le documents existant ? </p>

<table class="Admin" width="300">
<form action="" method="POST">
<TR><TD align="center"><input name="oui" type="submit" value="OUI"></TD><TD align="center"><input name="non" type="submit" value="NON"/></TD></TR>
</form>
</TABLE>
<?php } 
else { ?>
<H1>Signer un document</H1>

<form name="form_pdfAjust" action="" method="POST">
<?php
if (isset($_SESSION['hash2'])) {  
    echo '<input type="submit" name="Ajuster" value="Ajuster"/>';
}
else {
    echo '<input type="submit" name="Signer" value="Signer"/>';
}
?>
&nbsp;<input type="submit" name="Annuler" value="Annuler"/>
<input type="submit" name="Terminer" value="Terminer"/>
<BR /><BR />

<select name="action" onChange="submitFormAjust()">
<option value="">-- Action --</option>
<option value="Date">Date</option>
<option value="Tampon">Tampon</option>
<option value="Signature">Signature</option>
<?php while ($Fichier=$SelectFichier->fetch(PDO::FETCH_OBJ)) { 
echo '<option value="'.$Fichier->id.'">'.$Fichier->nom.'</option>';
} ?>
</select>

<select name="page" onChange="submitFormAjust()">
<option value="">-- Page --</option>
<option value="All">Tous</option>
<option value="Last">Dernière</option>
<?php for($i=1;$i<=$CountPage;$i++) {
echo '<option value="'.$i.'">Page '.$i.'</option>';
} ?>
</select>
<BR /><BR />

<table class="Admin">
<TR>
    <TD class="Signature">

    </TD>
    <TD class="Signature">
        <input type="range" name="horizontal" min="0" max="210" value="<?php echo $Horizontal; ?>" onChange="submitFormAjust()"/>
    </TD>
</TR>
<TR>
    <TD class="Signature">
        <input type="range" orient="vertical" name="vertical" min="0" max="297" value="<?php echo $Vertical; ?>" onChange="submitFormAjust()"/>
    </TD>
    <TD class="Signature">
        <div id="AffichagePage"></div>
    </TD>
</TR>
</table>
</form>
<?php } ?>
</article>
</section>
</div>
</CENTER>
</body>

</html>