<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/orario_css.css">
    <title>Prenotazione Sala - Meeting Booking</title>

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
        //controllo che il processo di prenotazione sia nell'ordine giusto
        if(!(isset($_POST["room"]) && isset($_POST["date"]))){
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

        
        $fascia_uno = false;
        $fascia_due = false;
        $fascia_tre = false;
        $fascia_quattro = false;
        $fascia_cinque = false;

        if(isset($_POST["room"]) && isset($_POST["date"])){

            $query = "SELECT * FROM riunioni WHERE ID_sala = '".$_POST["room"]."' AND data_riunione = '".$_POST["date"]."'";
            $tab = mysqli_query($conn, $query);

            while($riunione = mysqli_fetch_array($tab)){
                if($riunione["ora_inizio"] == "08:00:00" && $riunione["ora_fine"] == "10:00:00"){ $fascia_uno = true; }
                if($riunione["ora_inizio"] == "10:00:00" && $riunione["ora_fine"] == "12:00:00"){ $fascia_due = true; }
                if($riunione["ora_inizio"] == "12:00:00" && $riunione["ora_fine"] == "14:00:00"){ $fascia_tre = true; }
                if($riunione["ora_inizio"] == "14:00:00" && $riunione["ora_fine"] == "16:00:00"){ $fascia_quattro = true; }
                if($riunione["ora_inizio"] == "16:00:00" && $riunione["ora_fine"] == "18:00:00"){ $fascia_cinque = true; }
            }
        }

        function confrontaOrari($orario1, $orario2) {
            // Converti gli orari in oggetti DateTime per il confronto
            $dateTime1 = DateTime::createFromFormat('H:i', $orario1);
            $dateTime2 = DateTime::createFromFormat('H:i', $orario2);
        
            if ($dateTime1 == $dateTime2) {
                return true;
            }else{
                return false;
            }
        }
    ?>

</head>
<body>
    <div class="container">
        <h2> Prenota una Sala </h2>
        <p align="center">Per favore, seleziona una fascia oraria </p>
        <div class="form-container">
            <form action="prenotazioneSala_det.php" method="POST">
                <table class="center-table">
                        <?php 
                            echo "<tr>";
                            if($fascia_uno){
                                echo "<td>08:00 - 10:00 non disponibile </td>";
                            }
                            else{
                                echo "<td>08:00 - 10:00 </td>";
                                echo "<td><input type='radio' name='time_slot' value='08:00:00-10:00:00'></td>";
                            }
                            echo "</tr>";
                            echo "<tr>";
                            if($fascia_due){
                                echo "<td>10:00 - 12:00 non disponibile </td>";
                            }
                            else{
                                echo "<td>10:00 - 12:00 </td>";
                                echo "<td><input type='radio' name='time_slot' value='10:00:00-12:00:00'></td>";
                            }
                            echo "</tr>";
                            echo "<tr>";
                            if($fascia_tre){
                                echo "<td>12:00 - 14:00 non disponibile </td>";
                            }
                            else{
                                echo "<td>12:00 - 14:00 </td>";
                                echo "<td><input type='radio' name='time_slot' value='12:00:00-14:00:00'></td>";
                            }
                            echo "</tr>";
                            echo "<tr>";
                            if($fascia_quattro){
                                echo "<td>14:00 - 16:00 non disponibile </td>";
                            }
                            else{
                                echo "<td>14:00 - 16:00 </td>";
                                echo "<td><input type='radio' name='time_slot' value='14:00:00-16:00:00'></td>";
                            }
                            echo "</tr>";
                            echo "<tr>";
                            if($fascia_cinque){
                                echo "<td>16:00 - 18:00 non disponibile </td>";
                            }
                            else{
                                echo "<td>16:00 - 18:00 </td>";
                                echo "<td><input type='radio' name='time_slot' value='16:00:00-18:00:00'></td>";
                            }
                            echo "</tr>";
                        ?>
                </table>
                <br>
                <input type="hidden" value="<?php echo $_POST["room"]; ?>" name="room">
                <input type="hidden" value="<?php echo $_POST["date"]; ?>" name="date">
                <div class="form-group">
                    <input class="app-button" type="submit" value="Continua"> <!-- Invia dati form -->
                </div>
            </form>
        </div>
        <div class="button-container">
            <div class="button-group">
                <button class="app-button" onclick="location.href='prenotazioneSala.php'"> Torna indietro </button>
            </div>
        </div>
    </div>
</body>
</html>