<?php
session_start();

if (isset($_SESSION['Admin'])) {
    $SessionAdmin=$_SESSION['Admin'];

    $VerifSessionAdmin=$cnx->prepare("SELECT * FROM ".$Prefix."_compte_Admin WHERE hash=:hash");
    $VerifSessionAdmin->bindParam(':hash', $SessionAdmin, PDO::PARAM_STR);
    $VerifSessionAdmin->execute();
    $Admin=$VerifSessionAdmin->fetch(PDO::FETCH_OBJ);

    $NumRowSessionAdmin=$VerifSessionAdmin->rowCount();

    if ((isset($SessionAdmin))&&($NumRowSessionAdmin==1)) {
        $Cnx_Ok=true;
        if($Admin->admin==1) {
            $Cnx_Admin=true;
        }
        else {
            $Cnx_Admin=false;
        }
    }
    else {
        $Cnx_Ok=false;
        $Cnx_Admin=false;
    }
}    
else {
        $Cnx_Ok=false;
        $Cnx_Admin=false;
}

?>