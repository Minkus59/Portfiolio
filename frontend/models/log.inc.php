<?php
function ErreurLog($ErreurVar) {
    $ErreurLogDoc=$_SERVER['DOCUMENT_ROOT']."/frontend/lib/Log/";
    $ErreurLogFich=$ErreurLogDoc."erreurlog.txt";
    $Ip=$_SERVER['REMOTE_ADDR'];

    if(!file_exists($_SERVER['DOCUMENT_ROOT']."/frontend/lib/Log/")) {
        mkdir($ErreurLogDoc, 0777);
    }

    $Ouverture=fopen($ErreurLogFich, "a+");
    //Si une session existe
    if (isset($_SESSION['Client'])) { 
        $Ecriture=fwrite($Ouverture, $SessionClient." ; ".$ErreurVar." ; ".date("d-m-y / G:i:s", time())."\n");
    }
    // si aucune session
    else {
        $Ecriture=fwrite($Ouverture, $Ip." ; ".$ErreurVar." ; ".date("d-m-y / G:i:s", time())."\n");
    }
    fclose();
}

?>