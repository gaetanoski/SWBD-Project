<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/inserimento_css.css">
    <title>Prenotazione effettuata - Meeting Booking</title>
    <?php 
        session_start();
        // Controllo che l'utente sia loggato e che abbia i permessi
        if(isset($_SESSION["user_name"])){
           if($_SESSION["user_role"] == 1){
                header("location: index.php");
           }
        }else{
            header("location: login.php");
        }
        //Controllo che tutti i dati necessari siano stati inviati
        if(!(isset($_POST["room"]) && isset($_POST["date"]) && isset($_POST["time_slot"]) && isset($_POST["nome_riunione"]))){
            header("location: prenotazioneSala.php");
        }

        $mysql_dbname = 'my_meetingbooking';
        $mysql_host = 'localhost';
        $mysql_username = 'root';
        $mysql_password = '';														

        /* Connessione al DB */

        $conn = mysqli_connect($mysql_host, $mysql_username, $mysql_password);
        if(!$conn) 
        {
            echo('Errore : '.mysqli_error($conn));
            exit;
        }                                                                  
        
        /* Seleziona DB */

        $db = mysqli_select_db($conn, $mysql_dbname);
        if(!$db) 
        {
            echo('Errore : '.mysqli_error($conn));
            exit;
        }

        //funzione per la generazione di un codice univoco
        function generaCodiceUnivoco($lunghezza = 6) {
            $caratteri = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $lunghezzaCaratteri = strlen($caratteri);
            $codiceUnivoco = '';
            
            for ($i = 0; $i < $lunghezza; $i++) {
                $codiceUnivoco .= $caratteri[rand(0, $lunghezzaCaratteri - 1)];
            }
            
            return $codiceUnivoco;
        }

        //genero un codice univoco per la riunione
        $codice = generaCodiceUnivoco(); 
        $id_sala = $_POST["room"];
        $data = $_POST["date"];
        $nome = $_POST["nome_riunione"];
        $descrizione = $_POST["descrizione"];
        list($orarioInizio, $orarioFine) = explode('-', $_POST["time_slot"]);

        $query = "INSERT INTO `riunioni` (`codice`, `nome`, `descrizione`, `data_riunione`, `ora_inizio`, `ora_fine`, `cf_organizzatore`, `ID_sala`) VALUES ('".$codice."', '".$nome."', '".$descrizione."', '".$data."', '".$orarioInizio."', '".$orarioFine."', '".$_SESSION["user_CF"]."', '".$id_sala."')";
        if(mysqli_query($conn, $query)){
           $query2 = "INSERT INTO `partecipazioni` (`cf_dipendente`, `codice_riunione`) VALUES ('".$_SESSION["user_CF"]."', '".$codice."')";
           if(mysqli_query($conn, $query2)){
                header("location: visualizzazioneCodice.php?codice='".$codice."'");
           }
           else{
                echo('Errore : '.mysqli_error($conn));
           }
        }else{
            echo "Si è verificato un errore.";
            header("location: visualizzazioneCodice.php");
        }
    ?>
</head>
<body>
</body>
</html>
