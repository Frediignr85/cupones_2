<?php
include_once "_core.php";
function initial()
{
    include("header.php");
    include("menu.php");
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);
    ?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-2">
    </div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <?php
                    //permiso del script
                    if ($links!='NOT' || $admin=='1' ){
                    ?>
                <div class="ibox-title">
                    <h5><?php echo "Agregar Dependiente"; ?></h5>
                </div>
                <div class="ibox-content">
                    <form name="formulario_empresa" id="formulario_empresa">
                        <div class="row" id="row1">
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Nombres:</label>
                                    <input type="text" id="nombre" class="form-control" maxlenght="100" require placeholder="Ingrese los nombres del dependiente.">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Apellidos:</label>
                                    <input type="text" id="apellido" class="form-control" maxlenght="100" require placeholder="Ingrese los apellidos del dependiente.">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Correo:</label>
                                    <input type="email" id="correo" class="form-control" maxlenght="150" require placeholder="Ingrese el correo del dependiente.">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                <label for="">Estado:</label>
                                    <select name="estado" id="estado" class="select form-control" require>
                                        <option value="">Seleccione</option>
										<option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="process" id="process" value="insert"><br>
                        <div>
                            <input type="submit" id="agregar_dependiente" name="agregar_dependiente" value="Guardar"
                                class="btn btn-primary m-t-n-xs" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
        include_once ("footer.php");
        echo "<script src='js/funciones/funciones_dependientes.js'></script>";
    } //permiso del script
    else
    {
        $mensaje = "No tiene permiso para acceder a este modulo";
        echo "<br><br>$mensaje<div><div></div></div</div></div>";
        include "footer.php";
    }
}

function insertar()
{
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $estado = $_POST['estado'];
    _begin();
    $table_insert="dependientes";
    $id_empresa = $_SESSION['id_sucursal'];
    $form_data = array(
        'nombres' => $nombre,
        'apellidos' => $apellido,
        'correo' => $correo,
        'activo' => $estado,
        'id_empresa' => $id_empresa,
        'eliminado' => 0
    );
    $insert = _insert($table_insert, $form_data);
    if($insert){
        $id_dependiente = _insert_id();
        $tabla_usuarios = 'usuario';
        
        $pass = generar_contrasenia();
        $form_data_usuarios = array(
            'id_empleado' => 0,
            'id_dependiente' => $id_dependiente,
            'usuario' => "dependiente_".$id_dependiente,
            'nombre' => $nombre." ".$apellido,
            'password' => MD5($pass),
            'password_noencrypt' => $pass,
            'admin' => 0,
            'activo' => 1,
            'eliminado' => 0,
            'id_sucursal' => $id_empresa
        );
        $insert_usuario = _insert($tabla_usuarios, $form_data_usuarios);
        if($insert_usuario){
            $id_usuario = _insert_id();
            $permisos = [31];
            $error = false;
            foreach($permisos as $permiso){
                $tabla_permiso = 'usuario_modulo';
                $form_data_permiso = array(
                    'id_usuario' => $id_usuario,
                    'id_modulo' => $permiso
                );
                $insertar_permiso = _insert($tabla_permiso,$form_data_permiso);
                if(!$insertar_permiso){
                    $error = true;
                }
            }
            if(!$error){
                
                $query_empresa = _query("SELECT * FROM empresas WHERE id_empresa = '$id_empresa'");
                $row_empresa = _fetch_array($query_empresa);
                $nombre_empresa = $row_empresa['nombre'];

                $to = $correo;
                $subject = "Credenciales de Ingreso";
                
                $headers = "From: informacion@shopee.web-uis.com". "\r\n";
                $headers .= "CC:  informacion@shopee.web-uis.com";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                $message = "
                <html>
                <head>
                <title>Bienvenido a Shopee</title>
                </head>
                <body>
                <h1>Hola $nombre $apellido, esperamos que tengas un excelente dia!</h1>
                <p>Has sido elegido para ser dependiente de la empresa: $nombre_empresa, y tus credenciales son las siguientes:</p>
                <br>
                <p><b>Usuario: </b>dependiente_$id_dependiente</p><br>
                <p><b>Password: </b>$pass</p>
                </body>
                </html>";
                
                mail($to, $subject, $message,$headers);


                $xdatos ['typeinfo'] = 'Success';
                $xdatos ['msg'] = 'Dependiente Agregado Correctamente!';
                _commit();
            }
            else{
                $xdatos ['typeinfo'] = 'Error';
                $xdatos ['msg'] = 'EL Dependiente no pudo ser agregado!';
                _rollback();
            }
        }
        else{
            $xdatos ['typeinfo'] = 'Error';
            $xdatos ['msg'] = 'EL Dependiente no pudo ser agregado!';
            _rollback();
        }        
    }
    else{
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'EL Dependiente no pudo ser agregado!';
    }
	echo json_encode ( $xdatos );
}
function generar_contrasenia(){
    $caracteres1 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789!@$%^&*()_+";
    $codigo = substr(str_shuffle($caracteres1), 0, 6);
    return $codigo;
}

if(!isset($_POST['process']))
{
    initial();
}
else
{
    if(isset($_POST['process']))
    {
        switch ($_POST['process'])
        {
            case 'insert':
                insertar();
            break;
        }
    }
}
?>