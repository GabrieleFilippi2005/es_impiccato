<?php

// Connessione al database

$indirizzoServer = "localhost";
$nomeDb = "parole";
$conn = mysqli_connect($indirizzoServer, "root", "", $nomeDb);


// Controlla la connessione al database
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Ottieni i nomi inviati da JavaScript
if (isset($_POST['nomi'])) {
    $nomi = $_POST['nomi'];
    // Dividi i nomi in un array usando il separatore di riga "\n"
    $nomiArray = explode("\n", $nomi);

    // Loop attraverso gli array di nomi e inseriscili nella tabella SQL
    foreach ($nomiArray as $nome) {

        $query = "INSERT INTO lista_parole (parola) VALUES ('$nome')";

        // Esegui la query
        if ($conn->query($query) === TRUE) {
            echo "Nome '$nome' inserito correttamente.\n";
        } else {
            echo "Errore nell'inserimento del nome '$nome': " . $conn->error . "\n";
        }
    }
}

// Chiudo la connessione al database
$conn->close();

