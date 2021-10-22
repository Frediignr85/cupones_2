<?php
if ($_POST) {
    session_start();
    require_once "consola/_conexion.php";
    $nombres=$_POST["nombres"];
    $apellidos=$_POST["apellidos"];
    $password=$_POST["password"];
    $confirm_password=$_POST["confirm_password"];
    $gender=$_POST["gender"];
    $usuario=$_POST["usuario"];
    $correo=$_POST["correo"];
    $telefono=$_POST["telefono"];
    $dui=$_POST["dui"];
    $direccion=$_POST["direccion"];
    if($password == $confirm_password){
        $sql_repetido = "SELECT * FROM clientes WHERE correo = '$correo'";
        $query_repetido = _query($sql_repetido);
        if(_num_rows($query_repetido) == 0){
            $sql_repetido2 = "SELECT * FROM clientes WHERE usuario = '$usuario'";
            $query_repetido2 = _query($sql_repetido2);
            if(_num_rows($query_repetido2) == 0){
                _begin();
                $tabla1 = 'clientes';
                $form_data1 = array(
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'correo' => $correo,
                    'usuario' => $usuario,
                    'direccion' => $direccion,
                    'telefono' => $telefono,
                    'dui' => $dui,
                    'sexo' => $gender,
                    'activo' => 1,
                    'eliminado' => 0
                );
                $insertar = _insert($tabla1,$form_data1);
                if($insertar){
                    $id_cliente = _insert_id();
                    $tabla2 = 'usuario';
                    $form_data2 = array(
                        'id_empleado' => 0,
                        'id_dependiente' => 0,
                        'id_cliente' => $id_cliente,
                        'usuario' => $usuario,
                        'nombre' => $nombres." ".$apellidos,
                        'password' => MD5($password),
                        'password_noencrypt' => $password,
                        'admin' => 0,
                        'activo' => 0,
                        'id_sucursal' => 0,
                        'eliminado' => 0
                    );
                    $insertar2 = _insert($tabla2,$form_data2);
                    if($insertar2){
                        $id_usuario = _insert_id();
                        
                        $to = $correo;
                        $subject = "Activacion de Cuenta";
                        
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
                        <p>Te has registrado para poder acceder como cliente a nuestra plataforma y empezar a consumir todas las ofertas de nuestros aliados!!</p>
                        <br>
                        <p>Para activar tu cuenta necesitas entrar al siguiente link: </p>
                        <br>
                        <a href='http://shopee.web-uis.com/activar_cuenta.php?id=$id_usuario_enviar' target='_blank'>Activar Cuenta</a>
                        </body>
                        </html>";
                        
                        mail($to, $subject, $message,$headers);


                        _commit();
                        header('Location: login.php?result=1');
                    }
                    else{
                        _rollback();
                        header('Location: register.php?result=3');
                    }
                }
                else{
                    _rollback();
                    header('Location: register.php?result=3');
                }
            }
            else{
                header('Location: register.php?result=2');
            }
        }
        else{
            header('Location: register.php?result=1');
        }
    }
    else{
        header('Location: register.php?result=0');
    }
}
?>