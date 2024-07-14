<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/prenotazioneSala_css.css">
    <title>Aggiunta partecipanti - Meeting Booking</title>

    <?php 
        session_start();

        //controllo che l'utente sia loggato e che abbia i permessi
        if(isset($_SESSION["user_name"])){
            if($_SESSION["user_role"] == 1){
                header("location: index.php");
            }
        }else{
            header("location: login.php");
        }

        if(!isset($_POST["codice"])){
            header("location: riunioniAziendali.php");
        }
        
        $codice = $_POST["codice"];

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
    ?>
</head>
<body>
    <div class="container">
        <h2>Aggiunta partecipanti</h2>
        <p align="center">Per favore, seleziona i partecipanti da aggiungere</p>
        <div class="form-container">
            <form action="inserimentoPartecipanteDiretto.php" method="POST">
                <div class="form-group">
                    <select id="partecipante" name="partecipante" required>
                    <?php
                        $query = "SELECT codiceFiscale, nome, cognome, email FROM dipendenti WHERE codiceFiscale NOT IN (SELECT DISTINCT cf_dipendente FROM partecipazioni WHERE codice_riunione = '".$codice."') ORDER BY cognome, nome";
                        $tab = mysqli_query($conn, $query);
                        while( $row = mysqli_fetch_array($tab)){
                            echo("<option value='".$row["codiceFiscale"]."'>".$row["cognome"]." ".$row["nome"]." - ".$row["email"]." </option>");
                        }                 
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <input class="app-button" type="submit" value="Aggiungi">
                    <input type="hidden" name="codice" value="<?php echo $codice; ?>">
                </div>
            </form>
            <form action="dettagliRiunione.php" method="POST">
                    <div class="button-container">
                        <div class="button-group">
                            <input class="app-button" type="submit" value="Torna ai dettagli">
                            <input type="hidden" name="codice" value="<?php echo $codice; ?>">
                        </div>
                    </div>
            </form>
        </div>
    </div>
</body>
</html>