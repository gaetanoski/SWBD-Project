<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/prenotazioneSala_css.css">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('date');
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);
            const yyyy = tomorrow.getFullYear();
            const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
            const dd = String(tomorrow.getDate()).padStart(2, '0');
            const minDate = `${yyyy}-${mm}-${dd}`;

            dateInput.setAttribute('min', minDate);
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Prenota una Sala</h2>
        <p align="center">Per favore, seleziona una sala ed un giorno per continuare</p>
        <div class="form-container">
            <form action="prenotazioneSala_orario.php" method="POST">
                <div class="form-group">
                    <label for="room">Seleziona la sala:</label>
                    <select id="room" name="room" required>
                    <?php
                        $query = "SELECT * FROM sale_riunioni";
                        $tab = mysqli_query($conn, $query);
                        while( $row = mysqli_fetch_array($tab)){
                            echo("<option value='".$row["idSala"]."'>".$row["idSala"]." - ".$row["nome_sala"]."</option>");
                        }                 
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Seleziona il giorno:</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="form-group">
                    <input class="app-button" type="submit" value="Prosegui">
                </div>
                
                <div class="form-group">
                    <button class="app-button" onclick="location.href='index.php'">Torna alla Home</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
