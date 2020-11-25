<!DOCTYPE html>
<html>
<head>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <link rel="stylesheet" type="text/css" href="table_css.css">
    <title>Corona Personen Service</title>
    <link rel="icon" type="image/png" href="heart.png">
</head>
<body>



<?php
require __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

$renderArray=array();
$str = file_get_contents('test.json');
$db = json_decode($str, true);
$fromdate='';
$todate='';

if (isset($_POST['update_button'])) {

    $userID=$_POST['userID'];
    $nr=array_search($userID, array_column($db["persons"], 'id'));

    $db["persons"][$nr]["age"]=$_POST['dateinfect'];
    $db["persons"][$nr]["age"]=$_POST['dateend'];
    $db["persons"][$nr]["age"]=$_POST['age'];
    $db["persons"][$nr]["gender"]=$_POST['gender'];
    $db["persons"][$nr]["preill"]=$_POST['preill'];
    $db["persons"][$nr]["district"]=$_POST['district'];
    $db["persons"][$nr]["state"]=$_POST['state'];

    file_put_contents('test.json', json_encode($db));
    $renderArray=$db["persons"];

} else if (isset($_POST['delete_button'])) {
    $userID=$_POST['userID'];
    $nr=array_search($userID, array_column($db["persons"], 'id'));
    array_splice($db["persons"], $nr, 1);
    file_put_contents('test.json', json_encode($db));
    $renderArray=$db["persons"];
} else if (isset($_POST['search_button'])) {
    $fromdate=$_POST['fromdate'];
    $todate=$_POST['todate'];
    if ($fromdate === '' || $todate === ''){
        $renderArray=$db["persons"];
    }else{
        foreach ($db["persons"] as $item) {
            if ($item["dateinfect"] >= $fromdate &&
                $item["dateinfect"] <= $todate) {
                    array_push($renderArray, $item);
            }
        }
    }

} else if (isset($_POST['add_button'])) {
    //check if id is already in db :§
    $id=rand(0,10000);

    $object = new stdClass();
    $object->id = $id;
    $object->dateinfect = $_POST['dateinfect'];
    $object->dateend = $_POST['dateend'];
    $object->age = $_POST['age'];
    $object->gender = $_POST['gender'];
    $object->preill = $_POST['preill'];
    $object->district = $_POST['district'];
    $object->state = $_POST['state'];
    array_push($db["persons"], $object);

    file_put_contents('test.json', json_encode($db));
    $renderArray=$db["persons"];
} else {
    //invalid action!
    $renderArray=$db["persons"];
}

echo $twig->render('first.html.twig', ['input' => $renderArray, 'dateFrom' =>$fromdate, 'dateTo'=>$todate]);


?>



</body>
</html>
