<?php
if ($article[0]>0) {
    echo $article[1]->message;
    if (($Cnx_Admin==true)||($Cnx_Client==true)) { 
        echo '<a href="'.$Home.'/Admin/Article/Nouveau/?id='.$Actu->id.'"><img src="'.$Home.'/Admin/lib/img/modifier.png"></a><a href="'.$Home.'/Admin/Article/supprimer.php?id='.$Actu->id.'"><img src="'.$Home.'/Admin/lib/img/supprimer.png"></a>';
    } 
}
else {
    echo 'Aucun article pour le moment !';
}
?>