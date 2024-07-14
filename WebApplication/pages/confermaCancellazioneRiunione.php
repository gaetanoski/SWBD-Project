<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/prenotazioneSala_css.css">
    <title>Cancellazione riunione - Meeting Booking</title>

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
        <h2>Cancellazione riunione</h2>
        <p align="center">Sei sicuro di voler cancellare la riunione? Anche tutti i partecipanti verranno automaticamente cancellati</p>
        <div class="form-container">
            <form action="eliminazioneRiunione.php" method="POST">
                <div class="form-group">
                    <input class="app-button" type="submit" value="Cancella">
                    <input type="hidden" name="codice" value="<?php echo $codice; ?>">
                </div>
            </form>
        </div>
    </div>
</body>
</html>