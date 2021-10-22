<?php


if ($_POST) {
    session_start();
    require_once "consola/_conexion.php";
    $user=$_POST["username"];
    $pass=MD5($_POST["password"]);
    $sql = "SELECT * FROM usuario WHERE usuario = '$user' AND password ='$pass' AND activo = 1 AND eliminado = 0 AND id_cliente != 0";
    $result = _query($sql);
    $num = _num_rows($result);
    if ($num > 0) {
        $row= _fetch_array($result);
        $_SESSION["id_cliente"] = $row['id_cliente'];
        $_SESSION["nombre"] = $row['nombre'];
		$_SESSION["id_usuario"]=$row["id_usuario"];
      	$_SESSION["usuario"]=$user;
      	$_SESSION["admin"]=$row["admin"];
      	$_SESSION["name"]=$row["nombre"];
      	$_SESSION["id_sucursal"] = $row['id_sucursal'];
        header('location: index.php');
    } else {
        $error_msg = "Datos ingresados no son correctos";
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
    <title>Login</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
                <h1>Inicia sesion para<br />entrar a tu cuenta</h1>

                <div class="login-form">
                    <form action="" method="post">
                        <input type="text" id="username" name="username" placeholder="Nombre de Usuario">
                        <input type="password" id="password" name="password" placeholder="Contraseña">
                        <div class="forget-pass">
                            <a href="forget_password.php">¿Olvidaste tu contraseña?</a>
                        </div>
                        <div >
                            ¿No tienes cuenta? <a href="register.php">Registrate</a>
                        </div>
                        <button type="submit">INICIAR SESION</button>
                        
                    </form>
                        <?php
                            if (isset($_GET['result'])) {
                                if($_GET['result'] == 1) {
                                    echo "<div class=\"alert alert-success\" role=\"alert\">";
                                    echo "Se ha registrado con exito!! validar el Email enviado.";
                                    echo "</div>";
                                }
                                if($_GET['result'] == 2) {
                                    echo "<div class=\"alert alert-error\" role=\"alert\">";
                                    echo "No hay ningun usuario que activar con esas credenciales.";
                                    echo "</div>";
                                }
                                if($_GET['result'] == 3) {
                                    echo "<div class=\"alert alert-error\" role=\"alert\">";
                                    echo "No se pudo activar el usuario, intente mas tarde.";
                                    echo "</div>";
                                }
                                if($_GET['result'] == 4) {
                                    echo "<div class=\"alert alert-success\" role=\"alert\">";
                                    echo "Usuario Activado con Exito iniciar sesion.";
                                    echo "</div>";
                                }
                                if($_GET['result'] == 5) {
                                    echo "<div class=\"alert alert-success\" role=\"alert\">";
                                    echo "Link de recuperacion de contraseña enviado, revisar correo.";
                                    echo "</div>";
                                }
                                if($_GET['result'] == 6) {
                                    echo "<div class=\"alert alert-error\" role=\"alert\">";
                                    echo "No hay ningun usuario al que recuperar el password con esas credenciales.";
                                    echo "</div>";
                                }
                                if($_GET['result'] == 7) {
                                    echo "<div class=\"alert alert-error\" role=\"alert\">";
                                    echo "No se pudo actualizar el password, intente mas tarde.";
                                    echo "</div>";
                                }
                                if($_GET['result'] == 8) {
                                    echo "<div class=\"alert alert-success\" role=\"alert\">";
                                    echo "Password Cambiado con Exito";
                                    echo "</div>";
                                }
                            }
                        ?>
                </div>

            </div>
        </div>
    </div>
</body>

</html>