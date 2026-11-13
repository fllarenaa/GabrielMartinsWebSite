<?php

include 'components/connect.php';

if(!isset($_GET['date'])){
    exit;
}

$date = $_GET['date'];

/*
|--------------------------------------------------------------------------
| Não funciona aos domingos
|--------------------------------------------------------------------------
*/

if(date('w', strtotime($date)) == 0){

    echo json_encode([
        "status"=>"closed"
    ]);

    exit;

}

/*
|--------------------------------------------------------------------------
| Horários de funcionamento
|--------------------------------------------------------------------------
*/

$times = [

"08:30",
"09:00",
"09:30",
"10:00",
"10:30",
"11:00",
"11:30",

"14:00",
"14:30",
"15:00",
"15:30",
"16:00",
"16:30",
"17:00",
"17:30",
"18:00"

];

/*
|--------------------------------------------------------------------------
| Busca horários já ocupados
|--------------------------------------------------------------------------
*/

$sql = $conn->prepare("
SELECT appointment_time
FROM orders
WHERE appointment_date = ?
");

$sql->execute([$date]);

$occupied = [];

while($row = $sql->fetch(PDO::FETCH_ASSOC)){

    $occupied[] = substr($row['appointment_time'],0,5);

}

/*
|--------------------------------------------------------------------------
| Retorna apenas horários livres
|--------------------------------------------------------------------------
*/

$available = [];

foreach($times as $time){

    if(!in_array($time,$occupied)){

        $available[] = $time;

    }

}

echo json_encode([

"status"=>"open",
"times"=>$available

]);

?>