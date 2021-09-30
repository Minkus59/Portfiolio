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

<?php

if ($article[0]>0) {
    for($i = 0; $i < $article[0]; $i++) {
        echo $article[1][$i]->message;
        if (($Cnx_Admin==true)) {
            echo '<a href="'.HOME.'/Admin/Article/Nouveau/?id='.$article[1][$i]->id.'"><img src="'.HOME.'/Admin/lib/img/modifier.png"></a><a href="'.HOME.'/Admin/Article/supprimer.php?id='.$article[1][$i]->id.'"><img src="'.HOME.'/Admin/lib/img/supprimer.png"></a>';
        }
    }
}
else {
    echo '<section>
        <div class="container">
            <div class="row justify-content-center">
                Aucun article pour le moment !
            </div>
        </div>
    </section>';
}
?>