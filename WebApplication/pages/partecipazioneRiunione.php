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

        if(!isset($_POST["codice"])){
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
        $partecipazione = false;
        $posto = false;

       if(isset($_POST["codice"]) && isset($_POST["capienza"]) && isset($_POST["idSala"])){
            $codice_riunione = $_POST["codice"];
            $capienza = $_POST["capienza"];
            $id_sala = $_POST["idSala"];


            $query_part = "SELECT * FROM partecipazioni WHERE codice_riunione = '".$codice_riunione."' AND cf_dipendente = '".$_SESSION["user_CF"]."' ";
            $tab_part = mysqli_query($conn, $query_part);
            if(mysqli_num_rows($tab_part) == 1){
                $partecipazione = true;
            }


            $query = "SELECT R.codice, COUNT(P.cf_dipendente) AS numero_partecipanti FROM riunioni R JOIN partecipazioni P ON R.codice = P.codice_riunione WHERE (R.ID_sala = '".$id_sala."' AND R.codice = '".$codice_riunione."') GROUP BY R.ID_sala, R.codice";
            $tab = mysqli_query($conn, $query);
            if (!$tab) {
                trigger_error(mysqli_error($conn), E_USER_ERROR); //in caso di errore lo visualizzo
            }
            $row = mysqli_fetch_array($tab);
            if($row["numero_partecipanti"] <= $capienza){
                $posto = true;
                $msg = " <h2> C'è ancora posto </h2> ";
            }
            else{
                 $msg = "Posti terminati";
            }
        }
        
       else{
        header("location: riunioniAziendali.php");
       } 
    ?>

</head>
<body>
    <div class="container">
        <br>

        <?php if($partecipazione){ ?>
                <h2> <?php echo "<h2> Partecipi già a questa riunione </h2>"; ?> </h2>
                
                    <div class='form-container'>
                    <form class='form-group' action='rimozionePartecipante.php' method='POST'>
                    <input type='submit' class='app-button' value='Rimuovi prenotazione'>
                    <input type='hidden' name='codice' value='<?php echo $codice_riunione ?>'>
                    </form>
                    </div>
                    <p> oppure </p>
        <?php 
            }
            else{
                echo $msg;
                if($posto){ 
                    echo "<div class='form-container'>";
                    echo "<p> Puoi prenotarti </p>";
                    echo "<form class='form-group' action='inserimentoPartecipante.php' method='POST'> ";
                    echo "<input type='submit' class='app-button' value='Conferma prenotazione'>";
                    echo "<input type='hidden' name='codice' value='".$codice_riunione."'>";
                    echo "<input type='hidden' name='capienza' value='".$capienza."'>";
                    echo "<input type='hidden' name='idSala' value='".$id_sala."'>";
                    echo "</form> ";
                    echo "</div>";
                    echo "<p> oppure </p>";
                }
            }
        ?>

        <div class="button-container">
                <div class="button-group">
                    <button class="app-button" onclick="location.href='riunioniAziendali.php'"> Visualizza riunioni </button>
                </div>
        </div>
        <div class="button-container">
                <form class="button-group" action="index.php">
                    <input class="app-button" type="submit" value="Torna alla home">
                </form>
        </div>
    </div>
</body>
</html>