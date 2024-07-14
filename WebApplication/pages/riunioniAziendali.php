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
        if(!isset($_SESSION["user_name"])){
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

        //ricerca per data
        $dataFiltrata = '';
        if (isset($_POST['data_filtrata'])) {
            $dataFiltrata = $_POST['data_filtrata'];
        }

        //query per ottenere le riunioni
        if ($dataFiltrata) {
            $query = "SELECT riunioni.codice, riunioni.nome, riunioni.data_riunione, riunioni.ora_inizio, riunioni.ora_fine, sale_riunioni.nome_sala FROM riunioni JOIN sale_riunioni ON (riunioni.ID_sala = sale_riunioni.idSala) WHERE riunioni.data_riunione = '".$dataFiltrata."' ORDER BY riunioni.data_riunione, riunioni.ora_inizio";
            $tab = mysqli_query($conn, $query);
        } else {
            $query = "SELECT riunioni.codice, riunioni.nome, riunioni.data_riunione, riunioni.ora_inizio, riunioni.ora_fine, sale_riunioni.nome_sala FROM riunioni JOIN sale_riunioni ON (riunioni.ID_sala = sale_riunioni.idSala) ORDER BY riunioni.data_riunione, riunioni.ora_inizio";
            $tab = mysqli_query($conn, $query);
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
            <h2 align="center"> Riunioni Aziendali Programmate </h2>
        </div>
        <p align="center"> Visualizza tutte le riunioni o filtra per un giorno specifico </p>

        <div class="form-container">
            <form action="riunioniAziendali.php" method="POST">
                <div class="form-group">
                    <label for="data_filtrata"> Data </label>
                    <input type="date" id="data_filtrata" name="data_filtrata" value="<?php echo htmlspecialchars($dataFiltrata); ?>">
                </div>
                <div class="form-group">
                    <input class="app-button" type="submit" value="Filtra">
                </div>
            </form>
        </div>           
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Titolo</th>
                        <th>Data</th>
                        <th>Ora inizio</th>
                        <th>Ora fine</th>
                        <th>Sala</th>
                        <th> Info </th>
                    </tr>
                </thead>
                <tbody>
                        <?php while($row = mysqli_fetch_array($tab)){ ?>
                            <tr>
                                <?php 
                                     //prendo la data odierna
                                    $today_date = date("Y-m-d");
                                    if( $today_date < $row["data_riunione"]){ 
                                ?>
                                    <td><?php echo htmlspecialchars($row['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($row['data_riunione']); ?></td>
                                    <td><?php echo htmlspecialchars($row['ora_inizio']); ?></td>
                                    <td><?php echo htmlspecialchars($row['ora_fine']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($row['nome_sala'])); ?></td>
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
        </div>
    </div>
</body>
</html>
