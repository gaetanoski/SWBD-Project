<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/reg_css.css">
    <title>Meeting Booking</title>
    
    <?php 

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

           if(isset($_POST["email"]) && isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["fiscalCode"]) && isset($_POST["birthDate"]) && isset($_POST["role"]) && isset($_POST["password"])){
                $email = $_POST['email'];
                $nome = $_POST['firstName'];
                $cognome = $_POST['lastName'];
                $codice_fiscale = $_POST["fiscalCode"];
                $data_nascita=$_POST["birthDate"];
                $ruolo = $_POST["role"];
                $pass = $_POST['password'];

                $hashedPassword = crypt($pass, '$2a$07$usesomesillystringforsalt$');	
                    
                $sql = "INSERT INTO dipendenti (codiceFiscale, nome, cognome, data_nascita, email, tipo, password) VALUES ('".$codice_fiscale."', '".$nome."', '".$cognome."', '".$data_nascita."', '".$email."', '".$ruolo."', '".$hashedPassword."')" ;
                $tab = mysqli_query($conn, $sql) ;
                if($tab)
                {
                    header("location: login.php?registrazione=1");
                }
                else
                {
                    $errore = 1;
                    $msg = "<h3 align='center'> Sei già registrato! </h3>";
                }
            }

      ?>

</head>
<body>
    <div class="container">

        <h2> Meeting Booking </h2>
        <?php
            if($errore == 0){
                echo "<p align='center'> registrati con la tua e-mail aziendale per continuare </p>";
            }else if($errore == 1){
                echo $msg;
            }
        ?>

        <form id="registrationForm" action="registrazione.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <span class="error" id="emailError">L'email è obbligatoria.</span>
            </div>
            <div class="form-group">
                <label for="firstName">Nome</label>
                <input type="text" id="firstName" name="firstName" required>
                <span class="error" id="firstNameError">Il nome è obbligatorio.</span>
            </div>
            <div class="form-group">
                <label for="lastName">Cognome</label>
                <input type="text" id="lastName" name="lastName" required>
                <span class="error" id="lastNameError">Il cognome è obbligatorio.</span>
            </div>
            <div class="form-group">
                <label for="fiscalCode">Codice Fiscale</label>
                <input type="text" id="fiscalCode" name="fiscalCode" required>
                <span class="error" id="fiscalCodeError">Il codice fiscale inserito è errato.</span>
            </div>
            <div class="form-group">
                <label for="birthDate">Data di Nascita</label>
                <input type="date" id="birthDate" name="birthDate" required>
                <span class="error" id="birthDateError">La data di nascita è obbligatoria.</span>
            </div>
            <div class="form-group">
                <label for="role">Ruolo</label>
                <select id="role" name="role" required>
                    <option value="">Seleziona il ruolo</option>
                    <option value="1">Dipendente</option>
                    <option value="2">Funzionario</option>
                    <option value="3">Dirigente</option>
                </select>
                <span class="error" id="roleError">Il ruolo è obbligatorio.</span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <span class="error" id="passwordError">La password è obbligatoria.</span>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Conferma Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
                <span class="error" id="confirmPasswordError">La conferma della password è obbligatoria.</span>
                <span class="error" id="passwordMismatchError">Le password non corrispondono.</span>
            </div>
            <div class="form-group">
                <input type="submit" value="Registrati">
            </div>
        </form>
        <p align="center"> Sei già registrato? </p>
        <div class="button-container">
            <button class="log-button" id="loginButton"  onclick="location.href='login.php'"> Accedi </button>
        </div>
    </div>

    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            let isValid = true;

            const fields = [
                { id: 'email', errorId: 'emailError' },
                { id: 'firstName', errorId: 'firstNameError' },
                { id: 'lastName', errorId: 'lastNameError' },
                { id: 'fiscalCode', errorId: 'fiscalCodeError' },
                { id: 'birthDate', errorId: 'birthDateError' },
                { id: 'role', errorId: 'roleError', checkEmpty: true },
                { id: 'password', errorId: 'passwordError' },
                { id: 'confirmPassword', errorId: 'confirmPasswordError' }
            ];
        
            
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            const fiscalCode = document.getElementById('fiscalCode').value;
            const errorMessage = document.getElementById('fiscalCodeError');

            if (!isValidItalianFiscalCode(fiscalCode)) {
                event.preventDefault();
                errorMessage.textContent = 'Codice fiscale non valido';
            } else {
                errorMessage.textContent = '';
            }
        });
        

            fields.forEach(field => {
                const input = document.getElementById(field.id);
                const error = document.getElementById(field.errorId);

                if ((field.checkEmpty && input.value === '') || input.value.trim() === '') {
                    error.style.display = 'block';
                    isValid = false;
                } else {
                    error.style.display = 'none';
                }
            });

            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirmPassword');
            const passwordMismatchError = document.getElementById('passwordMismatchError');

            if (password.value !== confirmPassword.value) {
                passwordMismatchError.style.display = 'block';
                isValid = false;
            } else {
                passwordMismatchError.style.display = 'none';
            }

            if (!isValid) {
                event.preventDefault();
            }
        });

        function validaCodiceFiscale(fiscalCode) {
            if (!fiscalCode || fiscalCode.length !== 16) {
                return false;
            }

            const regex = /^[A-Z]{6}[0-9LMNPQRSTUV]{2}[A-Z][0-9LMNPQRSTUV]{2}[A-Z][0-9LMNPQRSTUV]{3}[A-Z]$/;
            if (!regex.test(fiscalCode)) {
                return false;
            }

            const evenValues = {
                '0': 0, '1': 1, '2': 2, '3': 3, '4': 4, '5': 5, '6': 6, '7': 7, '8': 8, '9': 9,
                'A': 0, 'B': 1, 'C': 2, 'D': 3, 'E': 4, 'F': 5, 'G': 6, 'H': 7, 'I': 8, 'J': 9,
                'K': 10, 'L': 11, 'M': 12, 'N': 13, 'O': 14, 'P': 15, 'Q': 16, 'R': 17, 'S': 18, 'T': 19,
                'U': 20, 'V': 21, 'W': 22, 'X': 23, 'Y': 24, 'Z': 25
            };

            const oddValues = {
                '0': 1, '1': 0, '2': 5, '3': 7, '4': 9, '5': 13, '6': 15, '7': 17, '8': 19, '9': 21,
                'A': 1, 'B': 0, 'C': 5, 'D': 7, 'E': 9, 'F': 13, 'G': 15, 'H': 17, 'I': 19, 'J': 21,
                'K': 1, 'L': 0, 'M': 5, 'N': 7, 'O': 9, 'P': 13, 'Q': 15, 'R': 17, 'S': 19, 'T': 21,
                'U': 1, 'V': 0, 'W': 5, 'X': 7, 'Y': 9, 'Z': 13
            };

            const checkCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

            let sum = 0;
            for (let i = 0; i < 15; i++) {
                const c = fiscalCode[i];
                if (i % 2 === 0) {
                    sum += oddValues[c];
                } else {
                    sum += evenValues[c];
                }
            }

            const checkChar = checkCharacters[sum % 26];

            return checkChar === fiscalCode[15];
        }
    </script>
</body>
</html>
