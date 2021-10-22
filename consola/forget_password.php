<?php
session_start();
require_once "_conexion.php";
if ($_POST) {
    $error_msg = "";
    require_once "_conexion.php";
    $user=$_POST["username"];
    $sql = "SELECT * FROM usuario WHERE usuario = '$user' and activo =1";
    $result = _query($sql);
    $num = _num_rows($result);
    if ($num > 0) {
        $row= _fetch_array($result);
        $id_usuario = $row['id_usuario'];
        $id_empleado = $row['id_empleado'];
        $id_dependiente = $row['id_dependiente'];
        if($id_empleado != 0){
            $sql1 = "SELECT * FROM empresas WHERE id_empresa = '$id_empleado'";
        }   
        else{
            $sql1 = "SELECT * FROM dependientes WHERE id_dependiente = '$id_dependiente'";
        }
        $query = _query($sql1);
        $row1 = _fetch_array($query);
        $correo = $row1['correo'];

        

        $to = $correo;
        $subject = "Recuperacion de Credenciales";
        
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
        <h1>Hola <b>$nombres $apellidos</b>, esperamos que tengas un excelente dia!</h1>
        <p>Vemos que has tenido un inconveniente con el password de tu cuenta, esperemos solucionarlo lo mas pronto posible!!</p>
        <br>
        <p>Para recuperar tu password procede a entrar al siguiente enlace: </p>
        <br>
        <a href='http://shopee.web-uis.com/consola/recuperar_contra.php?id=$id_usuario_enviar' target='_blank'>Recuperar Password</a>
        </body>
        </html>";
        
        mail($to, $subject, $message,$headers);

        header('location: dashboard.php');
    } else {
        $error_msg =  "<div style=\"color:black;\" class=\"alert alert-error\" role=\"alert\">";
        $error_msg .=  "No hay ningun cliente registrado con ese correo.";
        $error_msg .=  "</div>";
    }
    db_close();
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
                        <label for="emailID">Usuario</label>
                        <input id="username" name="username" type="text" class="form-control"
                            placeholder="Nombre de Usuario">
                    </div>

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