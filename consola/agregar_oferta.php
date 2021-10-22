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
                    <h5><?php echo "Agregar Oferta"; ?></h5>
                </div>
                <div class="ibox-content">
                    <form name="formulario_oferta" id="formulario_oferta">
                        <div class="row" id="row1">
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Titulo:</label>
                                    <input type="text" id="titulo" class="form-control" maxlenght="255" require placeholder="Ingrese el titulo de la oferta">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Descripcion:</label>
                                    <input type="text" id="descripcion" class="form-control" require placeholder="Ingrese el descripcion de la oferta">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Precio Regular:</label>
                                    <input type="text" id="precio_regular" class="form-control"  require placeholder="Ingrese el precio regular de la oferta">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-info single-line">
                                    <label for="">Precio Promocion:</label>
                                    <input type="text" id="precio_oferta" class="form-control"  require placeholder="Ingrese el precio en promocion de la oferta">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-info single-line">
                                    <label for="">Fecha Inicio:</label>
                                    <input type="text" id="fecha_inicio" class="form-control datepicker"  require placeholder="Ingrese la fecha de inicio de la oferta">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-info single-line">
                                    <label for="">Fecha Fin:</label>
                                    <input type="text" id="fecha_fin" class="form-control datepicker"  require placeholder="Ingrese la fecha de finalizacion de la oferta">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-info single-line">
                                    <label for="">Fecha Limite Canje:</label>
                                    <input type="text" id="fecha_limite_cupon" class="form-control datepicker"  require placeholder="Ingrese la fecha limite de canje de cupones">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-info single-line">
                                    <label for="">Cantidad De Cupones:</label>
                                    <input type="text" id="cantidad_limite_cupones" class="form-control"  require placeholder="Ingrese la cantidad de cupones limite de la oferta">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group has-info single-line">
                                    <div class='checkbox i-checks'><br>
                                        <label id='frentex'>
                                            <input type='checkbox' id='ilimitado' name='ilimitado'> <strong>Ilimitado?</strong>
                                        </label>
                                    </div>
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
                            <div class="col-md-12">
                                <div class="form-group has-info single-line">
                                    <label for="">Otros detalles:</label>
                                    <input type="text" id="detalles" class="form-control"  require placeholder="Ingrese otros detalles acerca de la empresa">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="ilimitado_value" id="ilimitado_value" value="0">
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
        echo "<script src='js/funciones/funciones_oferta.js'></script>";
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
    _begin();
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
        'eliminado' => 0,
        'estado_espera' => 1,
        'id_empresa' => $_SESSION['id_sucursal']
    );
    $insert = _insert($table_insert, $form_data);
    if($insert){
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['msg'] = 'Oferta Agregada Correctamente!';
        _commit();   
    }
    else{
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'La Oferta no pudo ser agregada!';
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