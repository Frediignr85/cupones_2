<?php
session_start();
require_once "_conexion.php";
if ($_POST) {
    $error_msg = "";
    require_once "_conexion.php";
    $password=$_POST["password"];
    $repetir_password=$_POST["repetir_password"];
    $id_usuario_activo = $_POST['id_usuario'];
    if($password == $repetir_password){
        $tabla = 'usuario';
        $form_data = array(
            'password' => md5($password),
            'password_noencrypt' => $password
        );
        $where = " WHERE id_usuario = '$id_usuario_activo'";
        $update = _update($tabla,$form_data,$where);
        if($update){
            header('Location: login.php?result=8');
        }
        else{
            header('Location: login.php?result=7');
        }
    }
    else{
        $error_msg =  "<div class=\"alert alert-error\" role=\"alert\">";
        $error_msg .=  "<p style='color:black;'>Las contraseñas no coinciden</p>.";
        $error_msg .=  "</div>";
    }
    db_close();
}
else{
    $id_usuario=$_REQUEST["id"];
    $sql= "SELECT * FROM usuario WHERE activo = 1 AND eliminado = 0";
    $query = _query($sql);
    $id_usuario_activo = 0;
    while($row = _fetch_array($query)){
        $id_usu = $row['id_usuario'];
        $id_usu = md5($id_usu);
        if($id_usu == $id_usuario){
            $id_usuario_activo = $row['id_usuario'];
        }
    }   
    if($id_usuario_activo == 0){
        header('Location: login.php?result=6');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Login" />
    <meta name="keywords" content="Admin, Dashboard" />
    <meta name="author" content="cindyLisseth" />
    <link rel="shortcut icon" href="img/favicon.ico">
    <title>__::COMPRAS ONLINE::__</title>

    <link href="pos/docs/css/jquery-ui.min.css" rel="stylesheet">
    <!-- still using jQuery v2.2.4 because Bootstrap doesn't support v3+ -->
    <script src="pos/docs/js/jquery-latest.min.js"></script>
    <script src="pos/docs/js/jquery-ui.min.js"></script>
    <!-- <script src="docs/js/jquery-migrate-3.0.0.min.js"></script> -->

    <!-- keyboard widget css & script (required) -->
    <link href="pos/css/keyboard.css" rel="stylesheet">
    <script src="pos/js/jquery.keyboard.js"></script>

    <!-- demo only -->
    <link rel="stylesheet" href="pos/docs/css/bootstrap.min.css">
    <link rel="stylesheet" href="pos/docs/css/font-awesome.min.css">

    <script src="pos/js/control.js"></script>

    <!-- Login CSS -->
    <link href="pos/css/main.css" rel="stylesheet" />


    <!-- Ion Icons -->
    <link href="pos/fonts/icomoon/icomoon.css" rel="stylesheet" />
</head>

<body class="login-bg">
    <form method="post">
        <div class="login-wrapper">
            <div class="login">
                <div class="login-header">
                    <div class="logo">
                        <img src="img/fondo.jpg" alt="Sistema de Evaluaciones" />
                    </div>
                    <h5>Recuperacion de Contraseña.</h5>
                </div>
                <div class="login-body">
                    <div class="form-group">
                        <label for="emailID">Password</label>
                        <input id="password" name="password" type="password" class="form-control"
                            placeholder="Ingrese el Password">
                            <label for="emailID">Confirmar Password</label>
                        <input id="repetir_password" name="repetir_password" type="password" class="form-control"
                            placeholder="Confirme el Password">

                    </div>
                    <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario_activo; ?>">
                    <button class="btn btn-danger btn-block" type="submit">Recuperar Contraseña</button>
                </div>
                <?php 
                    if($_POST){
                        echo $error_msg;
                    }
                ?>
            </div>

        </div>

        </div>

        </div>
    </form>
</body>

</html>