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
    $id_empresa = $_REQUEST['id_empresa'];
    $sql = "SELECT * FROM empresas where id_empresa = '$id_empresa'";
    $query = _query($sql);
    $row = _fetch_array($query);
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
                    <h5><?php echo "Editar Empresa"; ?></h5>
                </div>
                <div class="ibox-content">
                    <form name="formulario_empresa" id="formulario_empresa">
                        <div class="row" id="row1">
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Nombre de la Empresa:</label>
                                    <input  autocomplete="off" type="text" id="nombre" class="form-control" maxlenght="150" require value="<?php echo $row['nombre']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Direccion de la Empresa:</label>
                                    <input autocomplete="off" type="text" id="direccion" class="form-control" maxlenght="255" require value="<?php echo $row['direccion']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Nombres del Encargado:</label>
                                    <input autocomplete="off" type="text" id="encargado" class="form-control" maxlenght="150" require value="<?php echo $row['encargado']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Telefono de Contacto:</label>
                                    <input autocomplete="off" type="text" id="telefono" class="form-control" maxlenght="10" require value="<?php echo $row['telefono']; ?>">
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
                                            while($row1 = _fetch_array($query)){
                                                $nombre_rubro = $row1['nombre'];
                                                $id_rubro = $row1['id_rubro'];
                                                echo "<option value='".$id_rubro."'";
                                                if($row['id_rubro'] = $id_rubro){
                                                    echo " selected ";
                                                }                                                
                                                echo ">".$nombre_rubro."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Porcentaje de Comision:</label>
                                    <input autocomplete="off" type="text" id="porcentaje" class="form-control" require value="<?php echo $row['porcentaje']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Estado:</label>
                                    <select name="estado" id="estado" class="select form-control" require>
                                        <option value="">Seleccione</option>
                                        <option value="1" <?php if($row['activo'] == 1) echo "selected"; ?>  >Activo</option>
                                        <option value="0" <?php if($row['activo'] == 0) echo "selected"; ?> >Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="process" id="process" value="edited"><br>
                        <input type="hidden" name="id_empresa" id="id_empresa" value="<?php echo $id_empresa; ?>">
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

function edited()
{
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $encargado = $_POST['encargado'];
    $telefono = $_POST['telefono'];
    $rubro = $_POST['rubro'];
    $porcentaje = $_POST['porcentaje'];
    $estado = $_POST['estado'];
    $id_empresa = $_POST['id_empresa'];
    $table_insert="empresas";
    $form_data = array(
        'nombre' => $nombre,
        'direccion' => $direccion,
        'encargado' => $encargado,
        'telefono' => $telefono,
        'id_rubro' => $rubro,
        'porcentaje' => $porcentaje,
        'activo' => $estado,
    );
    $where = " id_empresa = '$id_empresa'";

    $insert = _update($table_insert, $form_data, $where);
    if($insert){
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['msg'] = 'Empresa actualizada correctamente!';
    }
    else{
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'La Empresa no pudo ser actualizada!';
    }
	echo json_encode ( $xdatos );
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
            case 'edited':
                edited();
            break;
        }
    }
}
?>