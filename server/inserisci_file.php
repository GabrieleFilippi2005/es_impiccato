<?php

// Connessione al database

$indirizzoServer = "localhost";
$nomeDb = "parole";
$conn = mysqli_connect($indirizzoServer, "root", "", $nomeDb);
$jObj = null;

// Controlla la connessione al database
if($conn->connect_errno>0){
    $jObj = preparaRisp(-1, "Connessione rifiutata");
}else{
    $jObj = preparaRisp(0, "Connessione ok");
}


//2. Prelevare un dato json che arriva dal client
$record = file_get_contents("php://input");
$record = json_decode($record);
$jObj->record = $record;


//3 Verificare se non esiste già il record
$query = "SELECT * 
        FROM lista_parole
        WHERE parola='".$record."'";
$ris = $conn->query($query);
if($ris){
    //Quando la query non ha errori -> finisco qua anche con tabella vuota
    if($ris->num_rows > 0){
        $jObj = preparaRisp(0, "Record presente", $jObj);
        $jObj->risp = $ris->num_rows;
    }else{
        $jObj = preparaRisp(-1, "Record non presente", $jObj);

        //4. Costruire la INSERT
        $query = "INSERT INTO `lista_parole`(`parola`)
                        VALUES ('$record')";
        $ris = $conn->query($query);
        if($ris && $conn->affected_rows > 0){
            $jObj = preparaRisp(0, "Inserimento della parola avvenuto con successo");
        }else{
            $jObj = preparaRisp(-2, "Errore nella query: ".$conn->error);
        }
    }
}else{
    //Quando ci sono errori
    $jObj = preparaRisp(-1, "Errore nella query: ".$conn->error);
}

//Rispondo al javascript (al client)
echo json_encode($jObj);


function preparaRisp($cod, $desc, $jObj = null){
    if(is_null($jObj)){
        $jObj = new stdClass();
    }
    $jObj->cod = $cod;
    $jObj->desc = $desc;
    return $jObj;
}



// Chiudo la connessione al database
$conn->close();

