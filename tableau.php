<?php include "fonction.php" ?>

<div class="explorer">
    <div class='search'>
        <div class="arrow">
             <a href="javascript:history.go(-1)"><==</a>
             <a href="javascript:history.go(1)">==></a> <br><br>
        </div>
        <input type="text" id="search" name="search"><button>Rechercher</button>
        <p class="files">
            <!-- afffiche l'arborescence des dossiers -->
            <?php

            $dir = isset($_GET['dir']) ? $_GET['dir'] : null;

            if (!$dir) {
                echo "/<br />";
            } else {
                echo "<a href=\"" . $_SERVER['PHP_SELF'] . "\" class='tree'>retour au debut</a><br />";
            }
            list_dir($BASE, rawurldecode($dir), 0);
            ?>
        </p>
        <p>---------------------------------------</p>
    </div>
    <!-- affiche les contenu des dossiers -->
    <p class="liste">
        <?php
        if ($dir) {
            $file = $BASE;
        }
        echo "<a>" . list_file(rawurldecode($dir)) . "</a>";

        echo  "<form method='post'><br><input type='text'class='path' value='" . rawurldecode($dir) . "'><br></form>";
        ?>
    </p>
    
    
</div>
