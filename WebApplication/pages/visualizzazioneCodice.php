<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/vis_cod_css.css">
    <title>Prenotazione effettuata - Meeting Booking</title>
</head>
<body>
    <div class="container">
        <br>
        <?php 
            if(isset($_GET["codice"])){
                echo "<h3 align='center'> Riunione pianificata con successo </h3>";
                echo "<p> Codice riunione: </p> <h3 align='center'> ".$_GET["codice"]." </h3>";
            }else{
                echo "<h3 align='center'> Si è verificato un errore. </h3>";
            }
        ?>
        <div class="button-container">
                <div class="button-group">
                    <button class="app-button" onclick="location.href='riunioniAziendali.php'"> Visualizza riunioni </button>
                </div>
        </div>
        <div class="button-container">
                <div class="button-group">
                    <button class="app-button" onclick="location.href='index.php'"> Torna alla home </button>
                </div>
        </div>
    </div>
</body>
</html>