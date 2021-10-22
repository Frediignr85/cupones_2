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
                    <h5><?php echo "Agregar Rubro"; ?></h5>
                </div>
                <div class="ibox-content">
                    <form name="formulario_empresa" id="formulario_empresa">
                        <div class="row" id="row1">
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Nombre de la Empresa:</label>
                                    <input  autocomplete="off" type="text" id="nombre" class="form-control" maxlenght="150" require>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Direccion de la Empresa:</label>
                                    <input  autocomplete="off" type="text" id="direccion" class="form-control" maxlenght="255" require>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Nombres del Encargado:</label>
                                    <input autocomplete="off" type="text" id="encargado" class="form-control" maxlenght="150" require>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Telefono de Contacto:</label>
                                    <input autocomplete="off" type="text" id="telefono" class="form-control" maxlenght="10" require>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Correo de la Empresa:</label>
                                    <input autocomplete="off" type="email" id="correo" class="form-control" maxlenght="150" require>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                <label for="">Rubro:</label>
                                    <select name="rubro" id="rubro" class="select form-control" require>
                                        <option value="">Seleccione</option>
										<?php
                                            $query = "SELECT * FROM rubros WHERE eliminado = 0";
                                            $query = _query($query);
                                            while($row = _fetch_array($query)){
                                                $nombre_rubro = $row['nombre'];
                                                $id_rubro = $row['id_rubro'];
                                                echo "<option value='".$id_rubro."'>".$nombre_rubro."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Porcentaje de Comision:</label>
                                    <input autocomplete="off" type="text" id="porcentaje" class="form-control"  require>
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
                            <input type="submit" id="agregar_rubro" name="agregar_rubro" value="Guardar"
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
        echo "<script src='js/funciones/funciones_empresas.js'></script>";
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
    $direccion = $_POST['direccion'];
    $encargado = $_POST['encargado'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $rubro = $_POST['rubro'];
    $porcentaje = $_POST['porcentaje'];
    $estado = $_POST['estado'];
    _begin();
    $table_insert="empresas";
    $form_data = array(
        'nombre' => $nombre,
        'direccion' => $direccion,
        'codigo' => generar_codigo(),
        'encargado' => $encargado,
        'telefono' => $telefono,
        'correo' => $correo,
        'id_rubro' => $rubro,
        'porcentaje' => $porcentaje,
        'activo' => $estado,
        'eliminado' => 0
    );
    $insert = _insert($table_insert, $form_data);
    if($insert){
        $id_empresa = _insert_id();
        $tabla_usuarios = 'usuario';
        $pass = generar_contrasenia();
        $form_data_usuarios = array(
            'id_empleado' => $id_empresa,
            'id_dependiente' => 0,
            'usuario' => "empresa_".$id_empresa,
            'nombre' => $nombre,
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
            $permisos = [9,10,11,12,13,14,15,16,17,18,21,22,25,26,27,28,29,30];
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
                <h1>Hola Empresa <b>$nombre</b>, esperamos que tengas un excelente dia!</h1>
                <p>Te has registrado para poder recibir todos los beneficios de estar en nuestra plataforma!!</p>
                <br>
                <p>Tus credenciales de ingreso a la plataforma son: </p>
                <br>
                <p><b>Usuario: </b>empresa_$id_empresa</p><br>
                <p><b>Password: </b>$pass</p>
                </body>
                </html>";
                
                mail($to, $subject, $message,$headers);



                $xdatos ['typeinfo'] = 'Success';
                $xdatos ['msg'] = 'Empresa Agregada Correctamente!';
                _commit();
            }
            else{
                $xdatos ['typeinfo'] = 'Error';
                $xdatos ['msg'] = 'La Empresa no pudo ser agregada!';
                _rollback();
            }
        }
        else{
            $xdatos ['typeinfo'] = 'Error';
            $xdatos ['msg'] = 'La Empresa no pudo ser agregada!';
            _rollback();
        }        
    }
    else{
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'La Empresa no pudo ser agregada!';
    }
	echo json_encode ( $xdatos );
}

function generar_codigo(){
    $caracteres1 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $caracteres2 = "0123456789";
    $salir = false;
    while(!$salir){
        $codigo = substr(str_shuffle($caracteres1), 0, 3).substr(str_shuffle($caracteres2), 0, 3);
        $query_repetido = _query("SELECT * FROM empresas WHERE codigo = '$codigo'");
        if(_num_rows($query_repetido) == 0){
            $salir = true;
        }
    }
    return $codigo;
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