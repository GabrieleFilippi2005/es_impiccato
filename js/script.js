let url = "http://localhost/esercizi/es_impiccato/"

let parola

let tentativi_rimasti = 5
let testo_tentativi
let aus = ""
function botone(){
    aus = ""
    let bottone_inviaP = document.getElementById("bottone_inviaP")
    let bottone_inviaL = document.getElementById("bottone_inviaL")
    let parola_da_indovinare = document.getElementById("parola_da_indovinare")

    tentativi_rimasti = 5
    fetch(url + "server/richiesta.php", {method: "get"})
        .then(response => response.json())
        .then(data => {
            parola = data.parola
            console.log(parola)

            //////////////////

            for (let i = 0; i < parola.length; i++)
            {
                aus += "_"
            }
            console.log(aus)
            parola_da_indovinare.innerHTML = aus
        })
        .catch(error => {
            console.log('Si è verificato un errore:', error)
        })
    bottone_inviaP.disabled = false
    bottone_inviaL.disabled = false
    testo_tentativi = document.getElementById("tentativi")
    testo_tentativi.innerHTML = "tentativi rimasti : "+tentativi_rimasti+""

}

function indovina_parola(){
    let bottone_inviaL = document.getElementById("bottone_inviaL")
    let tentativo = document.getElementById("txt_parola")
    let bottone_inviaP = document.getElementById("bottone_inviaP")
    console.log(tentativo.value)
    console.log(parola.length)
    if (tentativo.value === parola)
        alert("hai indovinato")
    else
    {
        if (tentativi_rimasti === 1){
            alert("hai perso")
            tentativi_rimasti = 0
            bottone_inviaP.disabled = true
            bottone_inviaL.disabled = true
        }
        else {
            alert("non hai indovinato")
            tentativi_rimasti--
        }
    }
    testo_tentativi.innerHTML = "tentativi rimasti : "+tentativi_rimasti+""
}

function indovina_lettera() {
    let bottone_inviaP = document.getElementById("bottone_inviaP")
    let bottone_inviaL = document.getElementById("bottone_inviaL")
    let parola_da_indovinare = document.getElementById("parola_da_indovinare")
    let lettera = document.getElementById("txt_lettera")
    let verifica = false

    for (let i = 0; i < parola.length; i++)
    {
        if (parola[i] === lettera.value){
            aus = aus.substring(0,i) + lettera.value + aus.substring(i+1,parola.length)
            verifica = true
        }
    }

    if (!verifica)
    {
        if (tentativi_rimasti === 1){
            alert("hai perso")
            tentativi_rimasti = 0
            bottone_inviaL.disabled = true
            bottone_inviaP.disabled = true
        }
        else {
            alert("lettera non presente")
            tentativi_rimasti--
        }
    }

    parola_da_indovinare.innerHTML = aus
    testo_tentativi.innerHTML = "tentativi rimasti : "+tentativi_rimasti+""
}



/*********************************************************************************************************************/


//inserimento nome
function inserisci_nome(){
    let nome = document.getElementById("input_nome")


    fetch(url + "server/inserisci_nome.php", {
            method: "post",
            body: JSON.stringify(nome.value)
        }
    ).then(r  =>{
        console.log(r)
    });

}


function inserisci_file() {
    let file = document.querySelector("input[type=file]");
    console.log(file.files)
    alert("Sto per caricare il file");



    let reader = new FileReader();
    //Indico alla libreria chi contattare terminata la lettura
    reader.onload = async function(datiletti) {
        //console.log(datiletti);// Oggetto FileReader
        console.log(datiletti.currentTarget.result)
        let dati = datiletti.currentTarget.result.split(",");

        //Trasformato da base64 utf8
        let datiDecodificati = atob(dati[1]);
        console.log(datiDecodificati)

        //Divido le righe del file in array
        let righe = datiDecodificati.split("\r\n");


        let record= righe[0].split("\n");
        for(let i=0; i< record.length; i++){
            record[i] = record[i].replaceAll("'", "");
        }



        for(let i=0; i< record.length; i++){
            let busta = await fetch(url + "server/inserisci_file.php", {
                    method:"post",
                    body:JSON.stringify(record[i])
                }
            );
            //Leggo il contenuto della busta
            console.log(await busta.json());
            console.log(i);
        }
    }

    reader.readAsDataURL(file.files[0]);
}

