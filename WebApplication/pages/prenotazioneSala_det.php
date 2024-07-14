<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/inserimento_detriun_css.css">
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
        if(!(isset($_POST["room"]) && isset($_POST["date"]) && isset($_POST["time_slot"]))){
            header("location: prenotazioneSala.php");
        }
    ?>

</head>
<body>
    <div class="container">
        <h2> Prenota una Sala </h2>
        <p align="center"> Inserisci ora i dettagli della riunione </p>
        <div class="form-container">
            <form action="riepilogoPrenotazione.php" method="POST">
                <div class="form-group">
                    <label for="nome"> Argomento </label>
                    <input type="text" id="nome_riunione" name="nome_riunione" required>
                </div>
                <div class="form-group">
                    <label for="descrizione"> Descrizione </label>
                    <textarea id="descrizione" name="descrizione" cols="50" rows="15"> </textarea>
                </div>

                <input type="hidden" value="<?php echo $_POST["room"]; ?>" name="room">
                <input type="hidden" value="<?php echo $_POST["date"]; ?>" name="date">
                <input type="hidden" value="<?php echo $_POST["time_slot"]; ?>" name="time_slot">
                <div class="button-container">
                    <div class="button-group">
                        <input class="app-button" type="submit" value="Continua">
                    </div>
                </div>
            </form>
        </div>

        <!--form per tornare alla pagina precedente-->
        <form action="prenotazioneSala_orario.php" method="POST">
                <input type="hidden" value="<?php echo $_POST["room"]; ?>" name="room">
                <input type="hidden" value="<?php echo $_POST["date"]; ?>" name="date">
                <div class="button-container">
                    <div class="button-group">
                        <input class="app-button" type="submit" value="Torna indietro">
                    </div>
                </div>
        </form>
        
    </div>
</body>
</html>