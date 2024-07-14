<html>
    <head>
        <?php 
            session_start();

            if(isset($_SESSION["user_name"])){
                if($_SESSION["user_role"] == 1){
                    header("location: areaPersonale1.php");
                }
                if($_SESSION["user_role"] == 2 || $_SESSION["user_role"] == 3){
                    header("location: areaPersonale2.php");
                }
            }else{
                    header("location: login.php");
            }
        ?>
    </head>
</html>