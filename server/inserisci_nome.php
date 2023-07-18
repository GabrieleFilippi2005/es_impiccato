<?php


$indirizzoServer = "localhost";
$nomeDb = "parole";
$conn = mysqli_connect($indirizzoServer, "root", "", $nomeDb);


$nome = file_get_contents("php://input");
$nome = json_decode($nome);


$query = "INSERT INTO lista_parole (parola) VALUES ('$nome')";
$ris = $conn->query($query);

if($ris && $conn->affected_rows > 0){
     echo "Inserimento del mezzo avvenuto con successo";
}else{
    echo "Errore nella query: ".$conn->error;
}

$conn->close();

?>