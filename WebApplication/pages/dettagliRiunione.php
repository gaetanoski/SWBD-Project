<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/riepilogo_css.css">
    <title>Dettagli Riunione - Meeting Booking</title>
    <?php 
        session_start();
        // Controllo che l'utente sia loggato e che abbia i permessi
        if(!isset($_SESSION["user_name"])){
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

        $query = "SELECT riunioni.codice, riunioni.nome, riunioni.data_riunione, riunioni.ora_inizio, riunioni.ora_fine, riunioni.descrizione, riunioni.cf_organizzatore, sale_riunioni.nome_sala, sale_riunioni.capienza, sale_riunioni.idSala, dipendenti.nome FROM riunioni JOIN sale_riunioni ON riunioni.ID_sala = sale_riunioni.idSala JOIN dipendenti ON riunioni.cf_organizzatore = dipendenti.codiceFiscale WHERE riunioni.codice = '".$codice."'";
        $tab = mysqli_query($conn, $query);
        if (!$tab) {
            trigger_error(mysqli_error($conn), E_USER_ERROR); //in caso di errore lo visualizzo
        }
    ?>

    <style>
        #partecipantiTabellaContainer {
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: max-height 0.5s ease, opacity 0.5s ease;
        }
        #partecipantiTabella {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        #partecipantiTabella, #partecipantiTabella th, #partecipantiTabella td {
            border: 1px solid #ddd;
        }
        #partecipantiTabella th, #partecipantiTabella td {
            padding: 10px;
            text-align: left;
        }
        #partecipantiTabella th {
            background-color: #f2f2f2;
        }
 
        #toggleButton {
            cursor: pointer;
            padding: 10px 20px;
            background-color: #008CBA;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #toggleButton:hover {
            background-color: #007B9E;
        }
        
        #toggleButton .arrow {
            margin-left: 10px;
            transition: transform 0.3s ease;
        }
        #toggleButton.rotate .arrow {
            transform: rotate(180deg);
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
        var toggleButton = document.getElementById("toggleButton");
        var tabellaContainer = document.getElementById("partecipantiTabellaContainer");
        
        toggleButton.addEventListener("click", function() {
            if (tabellaContainer.style.maxHeight === "0px" || tabellaContainer.style.maxHeight === "") {
                tabellaContainer.style.maxHeight = tabellaContainer.scrollHeight + "px";
                tabellaContainer.style.opacity = 1;
                toggleButton.querySelector(".arrow").style.transform = "rotate(180deg)";
                toggleButton.textContent = "Nascondi Partecipanti";
                toggleButton.appendChild(document.createElement('span')).className = "arrow";
                toggleButton.querySelector(".arrow").innerHTML = "&#9650;";
            } else {
                tabellaContainer.style.maxHeight = "0px";
                tabellaContainer.style.opacity = 0;
                toggleButton.querySelector(".arrow").style.transform = "rotate(0deg)";
                toggleButton.textContent = "Mostra Partecipanti";
                toggleButton.appendChild(document.createElement('span')).className = "arrow";
                toggleButton.querySelector(".arrow").innerHTML = "&#9660;";
            }
        });
    });
    </script>

</head>
<body>
    <div class="container">
        <h2> Dettagli riunione </h2>
        <p align="center"> Di seguito visualizzare tutti i dettagli della riunione selezionata </p>
        <div class="summary-container">
            <?php
                if($row = mysqli_fetch_array($tab)){ 
                $organizzatore = $row["cf_organizzatore"];
            ?>
                <div class="summary-item">
                    <label> Codice riunione: </label>
                    <span><?php echo htmlspecialchars($row["codice"]); ?></span>
                </div>
                <div class="summary-item">
                    <label> Stanza: </label>
                    <span><?php echo htmlspecialchars($row["nome_sala"]); ?></span>
                </div>
                <div class="summary-item">
                    <label> Data: </label>
                    <span><?php echo htmlspecialchars($row["data_riunione"]); ?></span>
                </div>
                <div class="summary-item">
                    <label> Fascia Oraria: </label>
                    <span>
                        <?php 
                            echo htmlspecialchars($row["ora_inizio"]);
                            echo " - ";
                            echo htmlspecialchars($row["ora_fine"]);
                        ?>
                    </span>
                </div>
                <div class="summary-item">
                    <label> Organizzatore: </label>
                    <span><?php echo htmlspecialchars($row["nome"]); ?></span>
                </div>
                <div class="summary-item">
                    <label> Descrizione: </label>
                    <span><?php echo nl2br(htmlspecialchars($row["descrizione"])); ?></span>
                </div>
            <?php 
                } 
                else{
                    echo "<p align='center'> Si è verificato un errore </p>";
                }
            ?>
        </div>
        
        <?php
            //ottengo i partecipanti alla riunione selezionata
            $query_partecipanti = "SELECT d.nome, d.cognome as cognome_dipendente, d.tipo as ruolo FROM Dipendenti d JOIN Partecipazioni p ON d.codiceFiscale = p.cf_dipendente WHERE p.codice_riunione = '".$codice."'";
            $tab_partecipanti = mysqli_query($conn, $query_partecipanti);
        ?>

         <!-- mostro i partecipanti alla riunione -->
         <div>
            <button id="toggleButton">
            Mostra Partecipanti
            <span class="arrow">&#9660;</span>
            </button>
            <div id="partecipantiTabellaContainer">
            <table id="partecipantiTabella">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Ruolo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row_partecipanti = mysqli_fetch_array($tab_partecipanti)){ ?>
                    <tr>
                        <td><?php echo $row_partecipanti["nome"]; ?></td>
                        <td><?php echo $row_partecipanti["cognome_dipendente"]; ?></td>
                        <td><?php echo $row_partecipanti["ruolo"]; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
        </div>

        <?php
             //data odierna
             $today_date = date("Y-m-d");
             if( $today_date <= $row["data_riunione"] ){ 
        ?>
                <div>
                    <?php if($_SESSION["user_CF"] == $organizzatore){  ?>
                        <form action="aggiuntaPartecipanti.php" method="POST">
                            <div class="button-container">
                                <div class="button-group">
                                    <input class="app-button" type="submit" value="Aggiungi partecipanti">
                                    <input type="hidden" name="codice" value="<?php echo $row["codice"]; ?>">
                                    <input type="hidden" name="capienza" value="<?php echo $row["capienza"]; ?>">
                                    <input type="hidden" name="idSala" value="<?php echo $row["idSala"]; ?>">
                                </div>
                            </div>
                        </form>

                        <form action="confermaCancellazioneRiunione.php" method="POST">
                            <div class="button-container">
                                <div class="button-group">
                                    <input class="app-button" type="submit" value="Cancella riunione">
                                    <input type="hidden" name="codice" value="<?php echo $row["codice"]; ?>">
                                </div>
                            </div>
                        </form>

                    <?php } ?>

                    <form action="partecipazioneRiunione.php" method="POST">
                        <div class="button-container">
                            <div class="button-group">
                                <input class="app-button" type="submit" value="Partecipa">
                                <input type="hidden" name="codice" value="<?php echo $row["codice"]; ?>">
                                <input type="hidden" name="capienza" value="<?php echo $row["capienza"]; ?>">
                                <input type="hidden" name="idSala" value="<?php echo $row["idSala"]; ?>">
                            </div>
                        </div>
                    </form>

                    <!-- Form per tornare indietro -->
                    <form action="index.php" method="POST">
                        <div class="button-container">
                            <div class="button-group">
                                <input class="app-button" type="submit" value="Torna alla home">
                            </div>
                        </div>
                    </form>
                </div>

        <?php } 
            else{
        ?>
                <p> la riunione è stata svolta </p>

                <?php 
                    $query_partecipazione = "SELECT COUNT(*) AS partecipazione_esistente FROM partecipazioni WHERE cf_dipendente = '".$_SESSION['user_CF']."' AND codice_riunione = '".$codice."' ";
                    $tab_partecipazione = mysqli_query($conn, $query_partecipazione);
                    if(mysqli_num_rows($tab_partecipazione) > 0){
                ?>

                    <form action="commentaRiunione.php" method="POST">
                        <div class="button-container">
                            <div class="button-group">
                                <input class="app-button" type="submit" value="Commenta">
                                <input type="hidden" name="codice" value="<?php echo $row["codice"]; ?>">
                            </div>
                        </div>
                    </form>
                
                    <?php } 
                        else{
                            echo "<p> Non hai partecipato a questa riunione </p> ";
                        }
                        ?>


                <!-- Form per tornare indietro -->
                <form action="index.php" method="POST">
                    <div class="button-container">
                        <div class="button-group">
                            <input class="app-button" type="submit" value="Torna alla home">
                        </div>
                    </div>
                </form>

        <?php } ?>
    </div>
</body>
</html>