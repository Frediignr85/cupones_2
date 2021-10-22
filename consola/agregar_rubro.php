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
                    <form name="formulario_rubro" id="formulario_rubro">
                        <div class="row" id="row1">
                            <div class="col-md-12">
                                <div class="form-group has-info single-line">
                                    <label for="">Nombres del Rubro:</label>
                                    <input autocomplete="off" type="text" id="nombre" class="form-control" maxlenght="250" require>
                                </div>
                            </div>
                            <div class="col-md-12">
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
        echo "<script src='js/funciones/funciones_rubros.js'></script>";
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
    $estado = $_POST['estado'];
    $table_insert="rubros";
    $form_data = array(
        'nombre' => $nombre,
        'activo' => $estado,
        'eliminado' => 0
    );
    $insert = _insert($table_insert, $form_data);
    if($insert){
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['msg'] = 'Rubro Agregado Correctamente!';
    }
    else{
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'El Rubro no pudo ser agregado!';
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
            case 'insert':
                insertar();
            break;
        }
    }
}
?>