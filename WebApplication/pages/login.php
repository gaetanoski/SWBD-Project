<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/css_login.css">
    <title>Login Aziendale</title>

    <?php 
        session_start();

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

           if(isset($_SESSION["user_name"])){
                header("location: index.php");
            }

           $errore = 0;

           if(isset($_POST["login_email"]) && isset($_POST["login_password"]))
           {
                $email = $_POST["login_email"];
                $password = $_POST["login_password"];

                $hashedPassword = crypt($password, '$2a$07$usesomesillystringforsalt$');	

                $query = " SELECT * FROM Dipendenti WHERE email = '".$email."' ";
                $tab = mysqli_query($conn, $query);

                if ( $user = mysqli_fetch_array($tab))
                {
                    if($hashedPassword == $user['password']){
                        $_SESSION['user_CF'] = $user['codiceFiscale'];
                        $_SESSION['user_name'] = $user['nome'];
                        $_SESSION['user_surname'] = $user['cognome'];
                        $_SESSION['user_dn'] = $user['data_nascita'];
                        $_SESSION['user_role'] = $user['tipo'];
                        $_SESSION['user_email'] = $user['email'] ;

                        header("location: index.php");
                    }
                    else{
                        $errore = 2;
                        $msg = "<p align='center'> password di accesso errata! </p>";
                    }
                }
                else{
                    $errore = 1;
                    $msg = "<p align='center'> account non esistente! </p>";
                }
           }

      ?>


</head>
<body>
    <div class="container">
        <h2>Meeting Booking</h2>
        <?php 
            if(isset($_GET['registrazione'])){
                if($_GET['registrazione'] == '1'){
                    echo "<p align='center'> registrazione effettuata, accedi per continuare </p>";
                }
            }
            else{
                if($errore == 0){
                    echo " <p align='center'> accedi per continuare </p> ";
                }
                else if($errore != 0){
                    echo $msg;       
                 }
            }
                
        ?>
        <form id="loginForm" action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="login_email" name="login_email" required>
                <span class="error" id="emailError">L'email è obbligatoria.</span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="login_password" name="login_password" required>
                <span class="error" id="passwordError">La password è obbligatoria.</span>
            </div>
            <div class="form-group">
                <input type="submit" value="Accedi">
            </div>
        </form>

        <p align="center"> Non sei registrato? </p>
        <div class="button-container">
            <button class="reg-button" id="registerButton"  onclick="location.href='registrazione.php'"> Registrati </button>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            let isValid = true;

            const fields = [
                { id: 'login_email', errorId: 'emailError' },
                { id: 'login_password', errorId: 'passwordError' }
            ];

            fields.forEach(field => {
                const input = document.getElementById(field.id);
                const error = document.getElementById(field.errorId);

                if (input.value.trim() === '') {
                    error.style.display = 'block';
                    isValid = false;
                } else {
                    error.style.display = 'none';
                }
            });

            if (!isValid) {
                event.preventDefault();
            }
        }
    </script>
</body>
</html>
