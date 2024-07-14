<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/riepilogo_css.css">
    <title>Riepilogo Prenotazione - Meeting Booking</title>
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
        //Controllo che tutti i dati necessari siano stati inviati
        if(!(isset($_POST["room"]) && isset($_POST["date"]) && isset($_POST["time_slot"]) && isset($_POST["nome_riunione"]))){
            header("location: prenotazioneSala.php");
        }
    ?>
</head>
<body>
    <div class="container">
        <h2> Riepilogo Prenotazione </h2>
        <p align="center"> Verifica i dettagli della tua prenotazione </p>
        <div class="summary-container">
            <div class="summary-item">
                <label> Stanza: </label>
                <span><?php echo htmlspecialchars($_POST["room"]); ?></span>
            </div>
            <div class="summary-item">
                <label> Data: </label>
                <span><?php echo htmlspecialchars($_POST["date"]); ?></span>
            </div>
            <div class="summary-item">
                <label> Fascia Oraria: </label>
                <span><?php echo htmlspecialchars($_POST["time_slot"]); ?></span>
            </div>
            <div class="summary-item">
                <label> Nome Riunione: </label>
                <span><?php echo htmlspecialchars($_POST["nome_riunione"]); ?></span>
            </div>
            <div class="summary-item">
                <label> Descrizione: </label>
                <span><?php echo nl2br(htmlspecialchars($_POST["descrizione"])); ?></span>
            </div>
        </div>

        <!-- Form per confermare la prenotazione -->
        <form action="inserimentoPrenotazione.php" method="POST">
            <input type="hidden" value="<?php echo htmlspecialchars($_POST["room"]); ?>" name="room">
            <input type="hidden" value="<?php echo htmlspecialchars($_POST["date"]); ?>" name="date">
            <input type="hidden" value="<?php echo htmlspecialchars($_POST["time_slot"]); ?>" name="time_slot">
            <input type="hidden" value="<?php echo htmlspecialchars($_POST["nome_riunione"]); ?>" name="nome_riunione">
            <input type="hidden" value="<?php echo htmlspecialchars($_POST["descrizione"]); ?>" name="descrizione">
            <br>
            <div class="button-container">
                <div class="button-group">
                    <input class="app-button" type="submit" value="Conferma Prenotazione">
                </div>
            </div>
        </form>

        <!-- Form per tornare alla pagina di inserimento dettagli -->
        <form action="prenotazioneSala_det.php" method="POST">
            <input type="hidden" value="<?php echo $_POST["room"]; ?>" name="room">
            <input type="hidden" value="<?php echo $_POST["date"]; ?>" name="date">
            <input type="hidden" value="<?php echo $_POST["time_slot"]; ?>" name="time_slot">
            <div class="button-container">
                <div class="button-group">
                    <input class="app-button" type="submit" value="Torna indietro">
                </div>
            </div>
        </form>
    </div>
</body>
</html>
