<html>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/png" href="assets\folder (1).png" />
<head>
    <title>Navigateur de fichier</title>
</head>
<body>
    <?php
    include "sort.php";
    // $dir = isset($_GET['dir']) ? $_GET['dir'] : '';
    $BASE = "./"; //defini là ou tu navigue (là, htdocs pour le projets)
    function addscheme($entry, $base, $type)
    {
        $tab['name'] = $entry;
        $tab['date'] = filemtime($base . "/" . $entry);
        $tab['size'] = filesize($base . "/" . $entry);
        $tab['perms'] = fileperms($base . "/" . $entry);
        $tab['access'] = fileatime($base . "/" . $entry);
        $t = explode(".", $entry);
        $tab["ext"] = $t[count($t) - 1];
        return $tab;
    }
    function list_dir($base, $cur, $level = 0)
    {
        global $PHP_SELF, $BASE, $order, $asc;
        if ($dir = opendir($base)) {
            $tab = array();
            while ($entry = readdir($dir)) {
                $file = $base . "/" . $entry;
                if (is_dir($file) &&  !in_array(
                    $entry,
                    array(".", "..")
                )) {
                    $tab[] = addScheme($entry, $base, 'dir');
                    for ($i = 1; $i <= (1 * $level); $i++) { //indentation des dossiers et sous dossiers
                        echo  "&nbsp | ";
                    }
                    if ($file == $cur) { // affichage des logo de l'arborescence des fichiers
                        echo "<img src= \"assets\open-folder.png\"/>&nbsp;$entry<br />\n";
                    } else {
                        // Utilisation de urldecode pour encoder le chemin complet du répertoire actuel
                        echo "<img src=\"assets/folder.png\" />&nbsp; <a href=\"/H5AI/{$PHP_SELF}?dir=" . urldecode($file) . "\">$entry</a><br />\n";
                    }
                    if (preg_match("~^" . preg_quote($file, "~") . "/~", $cur . "/")) {
                        list_dir($file, $cur, $level + 1);
                    }
                }
                usort($tab, 'cmp_name');
                foreach ($tab as $elem) {
                    $entry = $elem['name'];
                }
            }
            closedir($dir);
        }
    }
    function list_file($cur)
    { // afficher les fichiers
        global $PHP_SELF, $order, $asc;
        if ($dir = opendir($cur)) {
            $tab_dir = array();
            $tab_file = array();

            while ($file = readdir($dir)) {
                if (is_dir($cur . "/" . $file)) {
                    if (!in_array($file, array('.', '..'))) {
                        $tab_dir[] = addscheme($file, $cur, 'dir');
                    }
                } else {
                    $tab_file[] = addscheme($file, $cur, 'file');
                }
            }

            // usort($tab_file, 'cmp_' . $order);
            // usort($tab_dir, 'cmp_' . $order);
            echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
            echo "<tr style=\"font-size:8pt;font-family:arial;\">
            <th><p>Nom</p></th><td>&nbsp;</td>
            <th> <p>Taille</p></th><td>&nbsp;</td>
            <th><p>Dernière modification</p></th><td>&nbsp;</td>
            <th> <p>Extention</p></th><td>&nbsp;</td>
            <th> <p>Dernier accès</p></th></tr>";
            foreach ($tab_dir as $elem) { // affichage des icones dans l'arborescence
                echo "<tr><td><img src=\"assets/folder.png\" />&nbsp;" . $elem['name'] . "</td><td>&nbsp;</td>
                <td>&nbsp;</td><td>&nbsp;</td>
                <td>" . date("d/m/Y H:i:s", $elem['date']) . "</td><td>&nbsp;</td>
                <td>&nbsp;</td><td>&nbsp;</td>
                <td>" . date("d/m/Y", $elem['access']) . "</td></tr>\n";
            }
            foreach ($tab_file as $elem) { // affichage des icones dans le contenu des fichiers
                echo "<tr><td><img src=\"" . fileicon($elem['ext']) . "\" />";
                echo "<a href='showcontent.php?file=" . urldecode($cur . "/" . $elem['name']) . "'>" . $elem['name'] . " </a></td>";
                echo "<td>&nbsp;</td>";
                echo "<td align=\"right\">" . formatSize($elem['size']) . "</td><td>&nbsp;</td>";
                echo "<td>" . date("d/m/Y H:i:s", $elem['date']) . "</td><td>&nbsp;</td>";
                echo "<td>" . assocExt($elem['ext']) . "</td><td>&nbsp;</td>";
                echo "<td>" . date("d/m/Y", $elem['access']) . "</td></tr>\n";
            }
            closedir($dir);
        }
    }
    function formatSize($s)
    { //definitions de la taille des fichiers
        $u = array('octets', 'Ko', 'Mo', 'Go', 'To');
        $i = 0;
        $m = 0;
        while ($s >= 1) {
            $m = $s;
            $s /= 1024;
            $i++;
        }
        if (!$i) {
            $i = 1;
            $d = explode(".", $m);
        }
        if ($d[0] != $m) {
            $m = number_format($m, 2, ",", " ");
        }
        return $m . " " . $u[$i - 1];
    }
    function assocExt($extensionfile)
    {
        global $icons;
        $ext = array( //discription  des extensions
            '' => "inconnu au bataillon",
            'doc' => "Word",
            'odt' => "Word",
            'xls' => "Excel",
            'pptx' => "Powerpoint",
            'pdf' => "bah un pdf quoi",
            'zip' => "fichier compressé 'zip'",
            'rar' => "fichier compressé 'zip'",
            'txt' => "fichier texte", 
            'gif' => "Image GIF",
            'jpg' => "JPG",
            'png' => "PNG",
            'php' => "script php",
            'php3' => "script php",
            'htm' => "WEB",
            'html' => "WEB",
            'css' => "Feuille de style",
            'js' => "Javascript",
            'exe' => "Exécutable Windows",
            'zrxcxa' => "frr c koi ce fichier",
        );
        if (in_array($extensionfile, array_keys($ext))) {
            return $ext[$extensionfile];
        } else {
            return $ext[''];
        }
    }
    $icons = array( // pour chaque extensions, une icone lui est approprier
        '' => "file.png",
        'doc' => "doc.png",
        'odt' => "doc.png",
        'xls' => "xls.png",
        'pptx' => "fichier-powerpoint.png",
        'pdf' => "pdf.png",
        'zip' => "rar.png",
        'rar' => "rar.png",
        'txt' => "txt.png",
        'gif' => "gif.png",
        'jpg' => "jpg.png",
        'png' => "png.png",
        'php' => "php.png",
        'php3' => "php.png",
        'htm' => "chrome.png",
        'html' => "chrome.png",
        'css' => "css.png",
        'js' => "javascript.png",
        'exe' => "exe.png",
        'zrxcxa' => "file.png",
    );
    function fileicon($extension)
    {// verifie si dans le tableau, les extensions existent et affiche l'icone correspondante
        global $icons;
        if (array_key_exists($extension, $icons)) {
            return "assets/" . $icons[$extension];
        } else {
            return "assets/file.png";
        }
    }
    ?>
</body>
</html>