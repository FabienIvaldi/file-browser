
<?php

$recipes_json = file_get_contents('json file/recette.json');


header('Content-Type: application/json');
echo $recipes_json;
?>
