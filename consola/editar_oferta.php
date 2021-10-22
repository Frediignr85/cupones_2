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
    $id_oferta = $_REQUEST['id_oferta'];
    $sql = "SELECT * FROM ofertas where id_oferta = '$id_oferta'";
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
                    <h5><?php echo "Editar Oferta"; ?></h5>
                </div>
                <div class="ibox-content">
                    <form name="formulario_oferta" id="formulario_oferta">
                        <div class="row" id="row1">
                        <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Titulo:</label>
                                    <input type="text" id="titulo" class="form-control" maxlenght="255" require placeholder="Ingrese el titulo de la oferta" value="<?php echo $row['titulo']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Descripcion:</label>
                                    <input type="text" id="descripcion" class="form-control" require placeholder="Ingrese el descripcion de la oferta" value="<?php echo $row['descripcion']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Precio Regular:</label>
                                    <input type="text" id="precio_regular" class="form-control"  require placeholder="Ingrese el precio regular de la oferta"  value="<?php echo $row['precio_regular']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Precio Promocion:</label>
                                    <input type="text" id="precio_oferta" class="form-control"  require placeholder="Ingrese el precio en promocion de la oferta" value="<?php echo $row['precio_oferta']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-info single-line">
                                    <label for="">Fecha Inicio:</label>
                                    <input type="text" id="fecha_inicio" class="form-control datepicker"  require placeholder="Ingrese la fecha de inicio de la oferta"  value="<?php echo $row['fecha_inicio']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-info single-line">
                                    <label for="">Fecha Fin:</label>
                                    <input type="text" id="fecha_fin" class="form-control datepicker"  require placeholder="Ingrese la fecha de finalizacion de la oferta" value="<?php echo $row['fecha_fin']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-info single-line">
                                    <label for="">Fecha Limite Canje:</label>
                                    <input type="text" id="fecha_limite_cupon" class="form-control datepicker"  require placeholder="Ingrese la fecha limite de canje de cupones"  value="<?php echo $row['fecha_limite_cupon']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-info single-line">
                                    <label for="">Cantidad De Cupones:</label>
                                    <input type="text" id="cantidad_limite_cupones" class="form-control"  require placeholder="Ingrese la cantidad de cupones limite de la oferta"  value="<?php echo $row['cantidad_limite_cupones']; ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group has-info single-line">
                                    <div class='checkbox i-checks'><br>
                                        <label id='frentex'>
                                            <input type='checkbox' id='ilimitado' name='ilimitado' <?php if($row['ilimitar']) echo " checked "; ?>> <strong>Ilimitado?</strong>
                                        </label>
                                    </div>
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
                            <div class="col-md-12">
                                <div class="form-group has-info single-line">
                                    <label for="">Otros detalles:</label>
                                    <input type="text" id="detalles" class="form-control"  require placeholder="Ingrese otros detalles acerca de la empresa" value="<?php echo $row['detalles']; ?>">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="ilimitado_value" id="ilimitado_value" value="<?php echo $row['ilimitar']; ?>">
                        <input type="hidden" name="process" id="process" value="edited"><br>
                        <input type="hidden" name="id_oferta" id="id_oferta" value="<?php echo $id_oferta; ?>">
                        <div>
                            <input type="submit" id="agregar_oferta" name="agregar_oferta" value="Guardar"
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
        echo "<script src='js/funciones/funciones_oferta.js'></script>";
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
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $precio_regular = $_POST['precio_regular'];
    $precio_oferta = $_POST['precio_oferta'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $fecha_limite_cupon = $_POST['fecha_limite_cupon'];
    $cantidad_limite_cupones = $_POST['cantidad_limite_cupones'];
    $ilimitado_value = $_POST['ilimitado_value'];
    if($ilimitado_value){
        $cantidad_limite_cupones = 0;
    }
    $detalles = $_POST['detalles'];
    $estado = $_POST['estado'];
    $id_oferta = $_POST['id_oferta'];
    $table_insert="ofertas";
    $form_data = array(
        'titulo' => $titulo,
        'descripcion' => $descripcion,
        'detalles' => $detalles,
        'ilimitar' => $ilimitado_value,
        'precio_regular' => $precio_regular,
        'precio_oferta' => $precio_oferta,
        'fecha_inicio' => $fecha_inicio,
        'fecha_fin' => $fecha_fin,
        'fecha_limite_cupon' => $fecha_limite_cupon,
        'cantidad_limite_cupones' => $cantidad_limite_cupones,
        'activo' => $estado,
    );
    $where = " id_oferta = '$id_oferta'";

    $insert = _update($table_insert, $form_data, $where);
    if($insert){
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['msg'] = 'Oferta actualizada correctamente!';
    }
    else{
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'La Oferta no pudo ser actualizada!';
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