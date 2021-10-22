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
    $id_rubro = $_REQUEST['id_rubro'];
    $sql = "SELECT * FROM rubros where id_rubro = '$id_rubro'";
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
                            <h5><?php echo "Editar Rubro"; ?></h5>
                        </div>
                        <div class="ibox-content">
                        <form name="formulario_rubro" id="formulario_rubro">
                        <div class="row" id="row1">
                        <div class="col-md-12">
                                <div class="form-group has-info single-line">
                                    <label for="">Nombre del Rubro:</label>
                                    <input  autocomplete="off" type="text" id="nombre" class="form-control" maxlenght="250" value="<?php echo $row['nombre']; ?>" require>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group has-info single-line">
                                <label for="">Estado:</label>
                                    <select name="id_estado" id="id_estado" class="select form-control">
                                        <option value="">Seleccione</option>
										<option value="1" <?php if($row['activo'] == 1) echo "selected"; ?>  >Activo</option>
                                        <option value="0" <?php if($row['activo'] == 0) echo "selected"; ?> >Inactivo</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <input type="hidden" name="process" id="process" value="edited"><br>
                        <input type="hidden" name="id_rubro" id="id_rubro" value="<?php echo $id_rubro; ?>">
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
        echo "<script src='js/funciones/funciones_rubros.js'></script>";
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
    $estado = $_POST['estado'];
    $id_rubro = $_POST['id_rubro'];
    $table_insert="rubros";
    $form_data = array(
        'nombre' => $nombre,
        'activo' => $estado,
    );
    $where = " id_rubro = '$id_rubro'";
    $insert = _update($table_insert, $form_data, $where);
    if($insert){
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['msg'] = 'Rubro actualizado correctamente!';
    }
    else{
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'El Rubro no pudo ser actualizado!';
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
