<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/riunioni_css.css">
    <title>Visualizza Riunioni - Meeting Booking</title>
    <?php
        session_start();
        // Controllo che l'utente sia loggato e che abbia i permessi
        if(isset($_SESSION["user_name"])){
            if(!($_SESSION["user_role"] == 1)){
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

        //query per ottenere le riunioni
        $query = "SELECT r.codice, r.nome AS nome_riunione, r.descrizione, r.data_riunione, r.ora_inizio, r.ora_fine, r.ID_sala FROM Riunioni r JOIN Partecipazioni p ON r.codice = p.codice_riunione WHERE p.cf_dipendente = '".$_SESSION["user_CF"]."' ";
        $tab = mysqli_query($conn, $query);
        if (!$tab) {
            trigger_error(mysqli_error($conn), E_USER_ERROR); //in caso di errore lo visualizzo
        }
        $tab2 = mysqli_query($conn, $query);
        if (!$tab2) {
            trigger_error(mysqli_error($conn), E_USER_ERROR); //in caso di errore lo visualizzo
        }

    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('data_filtrata');
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
        <div class="header-container">
        <button class="app-button back-button" onclick="location.href='index.php'"> Torna indietro </button>
            <h2 align="center"> La tua area personale </h2>
        </div>
        <p align="center"> in programma per te </p>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Titolo</th>
                        <th>Data</th>
                        <th>Ora inizio</th>
                        <th>Ora fine</th>
                        <th>Sala</th>
                        <th>Info</th>
                    </tr>
                </thead>
                <tbody>
                        <?php while($row = mysqli_fetch_array($tab)){ ?>
                            
                            <tr>
                                <?php 
                                    //data odierna
                                    $today_date = date("Y-m-d");
                                    if( $today_date <= $row["data_riunione"] ){       
                                ?>
                                    <td><?php echo htmlspecialchars($row['nome_riunione']); ?></td>
                                    <td><?php echo htmlspecialchars($row['data_riunione']); ?></td>
                                    <td><?php echo htmlspecialchars($row['ora_inizio']); ?></td>
                                    <td><?php echo htmlspecialchars($row['ora_fine']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($row['ID_sala'])); ?></td>
                                    <td> 
                                        <form class="button-container" action="dettagliRiunione.php" method="POST"> 
                                            <input type="submit" class="app-button" value="Dettagli"> 
                                            <input type="hidden" value="<?php echo $row["codice"]; ?>" name="codice">
                                        </form> 
                                <?php } ?>
                            </tr>
                        <?php } ?>
                </tbody>
            </table>

        <p align="center"> riunioni a cui hai preso parte </p>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Titolo</th>
                        <th>Data</th>
                        <th>Ora inizio</th>
                        <th>Ora fine</th>
                        <th>Sala</th>
                        <th>Info</th>
                    </tr>
                </thead>
                <tbody>
                        <?php while($row2 = mysqli_fetch_array($tab2)){ ?>
                            
                            <tr>
                                <?php 
                                    //data odierna
                                    $today_date = date("Y-m-d");
                                    if( $today_date > $row2["data_riunione"] ){                                        
                                ?>
                                    <td><?php echo htmlspecialchars($row2['nome_riunione']); ?></td>
                                    <td><?php echo htmlspecialchars($row2['data_riunione']); ?></td>
                                    <td><?php echo htmlspecialchars($row2['ora_inizio']); ?></td>
                                    <td><?php echo htmlspecialchars($row2['ora_fine']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($row2['ID_sala'])); ?></td>
                                    <td> 
                                        <form class="button-container" action="dettagliRiunione.php" method="POST"> 
                                            <input type="submit" class="app-button" value="Dettagli"> 
                                            <input type="hidden" value="<?php echo $row2["codice"]; ?>" name="codice">
                                        </form> 
                                <?php } ?>
                            </tr>
                        <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</body>
</html>
