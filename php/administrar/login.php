
        <?php
        
        require ("../classes/conectionDb.php");


        $con = new Conection();
        $con->conect();
        $con->user_check($_POST['usuario'],$_POST['password']);

        //$con->user_check($usuario = $_POST['usuario'],$pass = $_POST['password']);


        ?>
