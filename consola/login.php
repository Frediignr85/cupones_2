<?php
session_start();
require_once "_conexion.php";
if ($_POST) {
    require_once "_conexion.php";
    $user=$_POST["username"];
    $pass=MD5($_POST["password"]);
    $sql = "SELECT * FROM usuario WHERE usuario = '$user' AND password ='$pass'";
    $result = _query($sql);
    $num = _num_rows($result);
    if ($num > 0) {
        $row= _fetch_array($result);
        $_SESSION["id_empleado"] = $row['id_empleado'];
        $_SESSION["id_dependiente"] = $row['id_dependiente'];
        $_SESSION["nombre"] = $row['nombre'];
		$_SESSION["id_usuario"]=$row["id_usuario"];
      	$_SESSION["usuario"]=$user;
      	$_SESSION["admin"]=$row["admin"];
      	$_SESSION["name"]=$row["nombre"];
      	$_SESSION["id_sucursal"] = $row['id_sucursal'];

        header('location: dashboard.php');
    } else {
        $error_msg = "Datos ingresados no son correctos";
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
    <title>__::EVALUACIONES ONLINE::__</title>

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
                    <h5>Ingreso a Consola de Administración.</h5>
                </div>
                <div class="login-body">
                    <div class="form-group">
                        <label for="emailID">Usuario</label>
                        <input id="username" name="username" type="text" class="form-control"
                            placeholder="Nombre de Usuario">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" class="form-control"
                            placeholder="Password">
                    </div>
                    <div class="form-group">
                        <a href="forget_password.php">¿Olvido su contraseña?</a>
                    </div>
                    <button class="btn btn-danger btn-block" type="submit">Iniciar Sesión</button>
                </div>
                <div class="checkbox no-margin">
                <?php
                            if (isset($_GET['result'])) {                                
                                if($_GET['result'] == 6) {
                                    echo "<div class=\"alert alert-error\" role=\"alert\">";
                                    echo "<p style='color:black;'>No hay ningun usuario al que recuperar el password con esas credenciales.</p>";
                                    echo "</div>";
                                }
                                if($_GET['result'] == 7) {
                                    echo "<div class=\"alert alert-error\" role=\"alert\">";
                                    echo "<p style='color:black;'>No se pudo actualizar el password, intente mas tarde.</p>";
                                    echo "</div>";
                                }
                                if($_GET['result'] == 8) {
                                    echo "<div class=\"alert alert-success\" role=\"alert\">";
                                    echo "<p style='color:black;'>Password Cambiado con Exito</p>";
                                    echo "</div>";
                                }
                            }
                        ?>

                </div>
            </div>

        </div>

        </div>

        </div>
    </form>
</body>

</html>