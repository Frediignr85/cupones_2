<?php
session_start();
require_once "consola/_conexion.php";
if ($_POST) {
    $error_msg = "";
    require_once "consola/_conexion.php";
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
        $error_msg .=  "Las contraseñas no coinciden.";
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="css/estilo_login.css">
</head>
<body>
    <div class="parent clearfix">
        <div class="bg-illustration">
            <h1 class="titulo">SISTEMA DE CUPONES</h1>
            <div class="burger-btn">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="login">
            <div class="container">
                <h1><br />Panel de recuperacion de Password</h1>

                <div class="login-form">
                    <form action="" method="POST">
                        <input type="password" id="password" name="password" placeholder="Contraseña">
                        <input type="password" id="repetir_password" name="repetir_password" placeholder="Repetir Contraseña">
                        <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario_activo; ?>">
                        <button type="submit">ENVIAR SOLICITUD</button>
                    </form>
                </div>
                <?php 
                    if($_POST){
                        echo $error_msg;
                    }
                ?>
            </div>
        </div>
    </div>
</body>

</html>