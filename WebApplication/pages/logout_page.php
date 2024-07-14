<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/home_css.css">
    <title>Logout - Meeting Booking</title>

    <?php 
        session_start();
    ?>

</head>
<body>
    <div class="container">
        <?php 
            session_unset();
            session_destroy();

            echo "<h2> Logout effettuato con successo. </h2>";
            echo("<form action='login.php'><input type='submit' class='app-button' value='Accedi'></form>"); 
        ?>
    </div>
</body>
</html>