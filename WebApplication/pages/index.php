<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/home_css.css">
    <title>Home - Meeting Booking</title>

    <?php 
        session_start();
        if(!isset($_SESSION["user_name"])){
            header("location: login.php");
        }
    ?>

</head>
<body>
    <div class="container">
        <h2>Benvenuto, <?php if(isset($_SESSION["user_name"])) { echo $_SESSION["user_name"]; } ?> </h2>
        <p align="center">Seleziona un'opzione per continuare</p>
        <div class="button-container">
            <button class="app-button" onclick="location.href='riunioniAziendali.php'">Riunioni aziendali</button>
            <button class="app-button" onclick="location.href='selezioneAreaPersonale.php'">Area personale</button>
            <?php
                if(isset( $_SESSION['user_role'])){
                    if( $_SESSION['user_role'] == 2 ||  $_SESSION['user_role']==3)
                    echo("<form action='prenotazioneSala.php'><input type='submit' class='app-button' value='Prenota una sala'></form>");
                }
            ?>
            <form action="logout_page.php" method="POST">
                <input class="app-button" type="submit" value="Logout">
            </form>
        </div>
    </div>
</body>
</html>