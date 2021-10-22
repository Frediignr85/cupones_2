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
    $id_dependiente = $_REQUEST['id_dependiente'];
    $sql = "SELECT * FROM dependientes where id_dependiente = '$id_dependiente'";
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
                    <h5><?php echo "Editar Dependiente"; ?></h5>
                </div>
                <div class="ibox-content">
                    <form name="formulario_empresa" id="formulario_empresa">
                        <div class="row" id="row1">
                        <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Nombres:</label>
                                    <input type="text" id="nombre" class="form-control" maxlenght="100" require placeholder="Ingrese los nombres del dependiente." value="<?php echo $row['nombres']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Apellidos:</label>
                                    <input type="text" id="apellido" class="form-control" maxlenght="100" require placeholder="Ingrese los apellidos del dependiente." value="<?php echo $row['apellidos']; ?>">
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
                        <input type="hidden" name="id_dependiente" id="id_dependiente" value="<?php echo $id_dependiente; ?>">
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
        echo "<script src='js/funciones/funciones_dependientes.js'></script>";
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
    $apellido = $_POST['apellido'];
    $estado = $_POST['estado'];
    $id_dependiente = $_POST['id_dependiente'];
    $table_insert="dependientes";
    $form_data = array(
        'nombres' => $nombre,
        'apellidos' => $apellido,
        'activo' => $estado,
    );
    $where = " id_dependiente = '$id_dependiente'";

    $insert = _update($table_insert, $form_data, $where);
    if($insert){
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['msg'] = 'Dependiente actualizado correctamente!';
    }
    else{
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'El Dependiente no pudo ser actualizado!';
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