<?php
session_start();
require_once "consola/_conexion.php";
if ($_POST) {
    $error_msg = "";
    require_once "consola/_conexion.php";
    $correo=$_POST["correo"];
    $sql = "SELECT * FROM clientes WHERE correo = '$correo' AND activo = 1 AND eliminado = 0";
    $result = _query($sql);
    $num = _num_rows($result);
    if ($num > 0) {
        $row= _fetch_array($result);
        $id_cliente = $row['id_cliente'];

        $sql2 = "SELECT * FROM usuario WHERE id_cliente = '$id_cliente'";
        $query2 = _query($sql2);
        $row2 = _fetch_array($query2);
        $id_usuario = $row2['id_usuario'];

        $to = $correo;
        $subject = "Recuperacion de Password";
        
        $headers = "From: informacion@shopee.web-uis.com". "\r\n";
        $headers .= "CC:  informacion@shopee.web-uis.com";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $id_usuario_enviar = md5($id_usuario);
        $message = "
        <html>
        <head>
        <title>Bienvenido a Shopee</title>
        </head>
        <body>
        <h1>Hola, esperamos que tengas un excelente dia!</h1>
        <p>Vemos que has tenido un inconveniente con el password de tu cuenta, esperemos solucionarlo lo mas pronto posible!!</p>
        <br>
        <p>Para recuperar tu password procede a entrar al siguiente enlace: </p>
        <br>
        <a href='http://shopee.web-uis.com/recuperar_contra.php?id=$id_usuario_enviar' target='_blank'>Recuperar Password</a>
        </body>
        </html>";
        
        mail($to, $subject, $message,$headers);
        header('Location: login.php?result=5');
    } else {
        $error_msg =  "<div class=\"alert alert-error\" role=\"alert\">";
        $error_msg .=  "No hay ningun cliente registrado con ese correo.";
        $error_msg .=  "</div>";
    }
    db_close();
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
                <h1>¿Olvido su contraseña?<br />Ingrese Su Correo y le envariaremos un link para recuperarlo</h1>

                <div class="login-form">
                    <form action="" method="POST">
                        <input type="email" id="correo" name="correo" placeholder="Correo Electronico">
                        <div class="forget-pass">
                            <a href="login.php">Regresar</a>
                        </div>
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