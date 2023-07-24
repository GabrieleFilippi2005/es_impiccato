<?php
//password vuota

$indirizzoServer = "localhost";
$nomeDb = "parole";

$conn = mysqli_connect($indirizzoServer, "root", "", $nomeDb);


$query2 = "SELECT max(id) as max_id FROM lista_parole";
$n_parole = $conn->query($query2);

if ($n_parole) {
    $row = $n_parole->fetch_assoc(); // Estrai il risultato come un array associativo
    $max_id = $row['max_id']; // Ottengo il valore dell' id dalla colonna calcolata "max_id"
}
$num = rand(1,$max_id);

$query = "SELECT parola FROM `lista_parole` where id = '$num'";
$ris = $conn->query($query);

if ($ris->num_rows > 0) {
    // Restituzione del dato come risposta JSON
    $row = $ris->fetch_assoc();
    $response = array('parola' => $row['parola']);
    echo json_encode($response);
} else {
    echo "Nessun dato trovato";
}

$conn->close();
?>