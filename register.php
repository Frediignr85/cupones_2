
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/estilo_registro.css">
</head>
<body>
    <div class="register">
        <div class="row">
            <div class="col-md-3 register-left">
                <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt="" />
                <h3>Bienvenido</h3>
                <p>Registrate en nuestra plataforma y accede a las mejores ofertas de nuestras empresas asociadas y
                    comienza a ahorrar.</p>
                <a class="btnLogin" href="login.php">Iniciar Sesion</a>
            </div>
            <div class="col-md-9 register-right">
                <form action="registrar.php" method="POST">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h3 class="register-heading">Registrarse como Cliente</h3>
                            <div class="row register-form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="nombres" id="nombres" class="form-control" placeholder="Nombres *" value="" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Apellidos *" value="" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña *"
                                            value="" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirmar Contraseña *"
                                            value="" required />
                                    </div>
                                    <div class="form-group">
                                        <div class="maxl">
                                            <label class="radio inline">
                                                <input type="radio" name="gender" value="Hombre" checked>
                                                <span>Hombre </span>
                                            </label>
                                            <label class="radio inline">
                                                <input type="radio" name="gender" value="Mujer">
                                                <span>Mujer </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Usuario *" value="" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="correo" id="correo" class="form-control" placeholder="Correo Electronico *"
                                            value="" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="text" minlength="8" maxlength="9" name="telefono" id="telefono"
                                            class="form-control" placeholder="Numero de Telefono *" value="" required />
                                    </div>

                                    <div class="form-group">
                                        <input type="text" name="dui" id="dui" class="form-control" placeholder="Numero de DUI *"
                                            value="" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Direccion *" required />
                                    </div>
                                    <input type="submit" class="btnRegister" value="Registrarse" />
                                </div>
                            </div>
                        </div>
                        <?php
                            if (isset($_GET['result'])) {
                                if($_GET['result'] == 0) {
                                    echo "<div class=\"alert alert-danger\" role=\"alert\">";
                                    echo "Las contraseñas no coinciden!";
                                    echo "</div>";
                                }
                                elseif($_GET['result'] == 1 ){
                                    echo "<div class=\"alert alert-danger\" role=\"alert\">";
                                    echo "Ya hay un cliente registrado con ese correo!";
                                    echo "</div>";
                                } 
                                elseif($_GET['result'] == 2){
                                    echo "<div class=\"alert alert-danger\" role=\"alert\">";
                                    echo "Ya hay un cliente registrado con ese usuario!";
                                    echo "</div>";
                                } 
                                elseif($_GET['result'] == 3){
                                    echo "<div class=\"alert alert-danger\" role=\"alert\">";
                                    echo "No se pudo registrar el cliente por el momento, intente mas tarde";
                                    echo "</div>";
                                } 
                            }
                        ?>
                    </div>
                </form>

            </div>
        </div>

    </div>
    <script src="js/funciones_registro.js"></script>
</body>

</html>