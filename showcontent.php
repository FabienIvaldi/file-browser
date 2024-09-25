
             <a href="javascript:history.go(-1)"><==</a><br>
       

<?php
include "fonction.php";
if (isset($_GET['file'])) {
    $filename = $_GET['file'];
    if (is_image($filename)) {
        echo "<img src='". rawurlencode($dir) ."/". "' alt='Image' />";
    } else {
        showcontent($filename);
    }
}else{
    echo "je suis dans ton fichier alors que j'ai pas le droit !";
};



function is_image($file)
{
    $extname = ['jpg', 'png', 'jpeg', 'gif'];
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    return in_array(strtolower($ext), $extname);
}
function showcontent($filename) {
    if (file_exists($filename) && is_readable($filename)) {
        $file_handle = fopen($filename, "r");
        if ($file_handle) {
            while (!feof($file_handle)) {
                $line = fgets($file_handle);
                echo htmlspecialchars($line) . "<br>";
            }
            fclose($file_handle);
        } else {
            echo "Not found.";
        }
    } else {
        echo "Not existe.";
    }
}