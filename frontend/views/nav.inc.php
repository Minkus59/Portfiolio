<NAV>
    <div class="container">
        <ul class='nav justify-content-center'>
            <?php
            for($i = 0; $i < $Nbmenu; $i++) {
                $cnx = new Connexion();
                $SelectSousMenu=$cnx->preparer("SELECT", "SELECT * FROM ".DB_PREFIX."Menu WHERE parrin=:parrin AND sous_menu='1' AND statue='1' ORDER BY position ASC", array(':parrin'=>$menu[$i]->lien));
                ?>
                <li <?php if ($page==$menu[$i]->lien) { echo "class='Up'"; } ?>>
                <a href="<?php echo $menu[$i]->lien; ?>"><?php echo $menu[$i]->libele; ?></a>
                <?php if ($SelectSousMenu[0]>0) { echo '<label for="NavMenu'.$menu[$i]->id.'" class="LabelNavMenu"></label><input class="NavMenu" id="NavMenu'.$menu[$i]->id.'" type="checkbox"/>'; } ?>

                <?php 
                if ($SelectSousMenu[0]>0) {
                    echo "<ul class='niveau2'>";
                    for($j = 0; $j < $SelectSousMenu[0]; $j++) {
                        $SelectSousSousMenu=$cnx->preparer("SELECT", "SELECT * FROM ".DB_PREFIX."Menu WHERE parrin=:parrin AND sous_menu='1' AND statue='1' ORDER BY position ASC", array(':parrin'=>$SelectSousMenu[1][$j]->lien));
                        ?>
                        <li <?php if ($page==$SelectSousMenu[1][$j]->lien) { echo "class='Up'"; } ?>>
                        <a href="<?php echo $SelectSousMenu[1][$j]->lien; ?>"><?php echo $SelectSousMenu[1][$j]->libele; ?></a>
                        <?php if ($SelectSousMenu[0]>0) { echo '<label for="NavMenu'.$SelectSousMenu[1][$j]->id.'" class="LabelNavMenu"></label><input class="NavMenu" id="NavMenu'.$SelectSousMenu[1][$j]->id.'" type="checkbox"/>'; } ?>

                        <?php 
                        if ($CountSousSousMenu>0) {
                            echo "<ul class='niveau3'>";
                            for($k = 0; $k < $SelectSousSousMenu[0]; $k++) { ?>
                                <li <?php if ($page==$SelectSousSousMenu[1][$k]->lien) { echo "class='Up'"; } ?>><a href="<?php echo $SelectSousSousMenu[1][$k]->lien; ?>"><?php echo $SelectSousSousMenu[1][$k]->libele ?></a></li>
                            <?php
                            }
                        echo "</ul>";
                        }
                        ?></li>
                        <?php
                    }
                    echo "</ul>";
                }
            }
            ?></li>        
        </ul>
    </div>
</NAV>