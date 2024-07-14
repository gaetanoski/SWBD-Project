<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/vis_cod_css.css">
    <title>Cancellazione Riunione - Meeting Booking</title>

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

        if(!isset($_POST["codice"])){
            header("location: index.php");
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

        $msg = '';

        try {
            // Inizia la transazione
            $conn->begin_transaction();
        
            // Cancella le partecipazioni alla riunione
            $sql1 = "DELETE FROM partecipazioni WHERE codice_riunione = '".$codice."'";
            if (!mysqli_query($conn, $sql1)) {
                throw new Exception("Errore durante l'eliminazione delle partecipazioni: " . mysqli_error($conn));
            }
        
            // Cancella la riunione stessa
            $sql2 = "DELETE FROM riunioni WHERE codice = '".$codice."'";
            if (!mysqli_query($conn, $sql2)) {
                throw new Exception("Errore durante l'eliminazione della riunione: " . mysqli_error($conn));
            }
        
            // Completa la transazione
            $conn->commit();
        
            $msg = "<h2> Riunione cancellata con successo </h2>";
        }
        catch (Exception $e) {
            // Annulla la transazione se qualcosa va storto
            $conn->rollback();
            $msg = "Errore: " . $e->getMessage();
        }
        
        // Chiudi la connessione
        $conn->close();

        ?>

</head>
<body>
    <div class="container">
       <?php echo $msg; ?>

       <form action="areaPersonale2.php" method="POST">
            <div class="button-container">
                <div class="button-group">
                    <input class="app-button" type="submit" value="Area personale">
                </div>
            </div>
        </form>
    </div>
</body>
</html>