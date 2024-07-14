<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/vis_cod_css.css">
    <title>Partecipazione - Meeting Booking</title>

    <?php 
        session_start();
        // Controllo che l'utente sia loggato e che abbia i permessi
        if(!isset($_SESSION["user_name"])){
            header("location: login.php");
        }

        if(!isset($_POST["codice"]) && isset($_POST["capienza"]) && isset($_POST["idSala"])){
            header("location: index.php");
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

        $msg = '';
        $codice = $_POST["codice"];

        $query = "INSERT INTO partecipazioni (cf_dipendente, codice_riunione) VALUES ('".$_SESSION['user_CF']."', '".$codice."')";
        $tab = mysqli_query($conn, $query);

        if($tab){
            $msg = '<h2> Partecipazione confermata </h2>';
        }
        else{
            $msg = '<h2> Si è verificato un errore </h2>';
            echo('Errore : '.mysqli_error($conn));
        }
    ?>

</head>
<body>
    <div class="container">
       <?php echo $msg; ?>
       <form action="dettagliRiunione.php" method="POST">
            <div class="button-container">
                <div class="button-group">
                    <input class="app-button" type="submit" value="Torna ai dettagli">
                    <input type="hidden" name="codice" value="<?php echo $codice; ?>">
                </div>
            </div>
        </form>
    </div>
</body>
</html>