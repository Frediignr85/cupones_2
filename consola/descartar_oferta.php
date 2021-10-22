<?php
include ("_core.php");
function initial(){
$id_oferta = $_REQUEST['id_oferta'];
$sql1="SELECT ofertas.id_oferta, ofertas.titulo, ofertas.precio_regular, ofertas.precio_oferta, ofertas.fecha_inicio, ofertas.fecha_fin, ofertas.fecha_limite_cupon, ofertas.cantidad_limite_cupones, ofertas.ilimitar, ofertas.descripcion, ofertas.detalles, empresas.nombre FROM ofertas INNER JOIN empresas on ofertas.id_empresa = empresas.id_empresa WHERE ofertas.id_oferta = '$id_oferta'";
$consulta1 = _query($sql1);
$row1 = _fetch_array($consulta1);
$id_oferta = $row1['id_oferta'];
$titulo = $row1['titulo'];
$descripcion = $row1['descripcion'];
$precio_regular = $row1['precio_regular'];
$precio_oferta = $row1['precio_oferta'];
$fecha_inicio = $row1['fecha_inicio'];
$fecha_fin = $row1['fecha_fin'];
$fecha_limite_cupon = $row1['fecha_limite_cupon'];
$cantidad_limite_cupones = $row1['cantidad_limite_cupones'];
$cantidad_cupones = $cantidad_limite_cupones;
$ilimitar = $row1['ilimitar'];
if($ilimitar){
    $cantidad_cupones = "Ilimitados";
}
$detalles = $row1['detalles'];
$nombre = $row1['nombre'];

$tablas="";
$tablas.="<table class='table'>";
$tablas.="<thead class='thead-dark'>";
$tablas.="<tr>";
$tablas.="<th scope='col'>Id Oferta</th>";
$tablas.="<th scope='col'>Titulo</th>";
$tablas.="<th scope='col'>Descripcion</th>";
$tablas.="<th scope='col'>Precio Regular</th>";
$tablas.="<th scope='col'>Precio Oferta</th>";
$tablas.="<th scope='col'>Fecha de Inicio</th>";
$tablas.="<th scope='col'>Fecha de Finalizacion</th>";
$tablas.="</tr>";
$tablas.="</thead>";
$tablas.="<tbody>";
$tablas.="<tr>";
$tablas.="<td>".$id_oferta."</td>";
$tablas.="<td>".$titulo."</td>";
$tablas.="<td>".$descripcion."</td>";
$tablas.="<td>".$precio_regular."</td>";
$tablas.="<td>".$precio_oferta."</td>";
$tablas.="<td>".$fecha_inicio."</td>";
$tablas.="<td>".$fecha_fin."</td>";
$tablas.="</tr>";
$tablas.="</tbody>";
$tablas.="</table>";

$tablas.="<table class='table'>";
$tablas.="<thead class='thead-dark'>";
$tablas.="<tr>";

$tablas.="<th scope='col'>Fecha Limite de Canje</th>";
$tablas.="<th scope='col'>Cantidad de Cupones</th>";
$tablas.="<th scope='col'>Otros Detalles</th>";
$tablas.="<th scope='col'>Nombre de la Empresa</th>";
$tablas.="</tr>";
$tablas.="</thead>";
$tablas.="<tbody>";
$tablas.="<tr>";
$tablas.="<td>".$fecha_limite_cupon."</td>";
$tablas.="<td>".$cantidad_cupones."</td>";
$tablas.="<td>".$detalles."</td>";
$tablas.="<td>".$nombre."</td>";
$tablas.="</tr>";
$tablas.="</tbody>";
$tablas.="</table>";


$id_user=$_SESSION["id_usuario"];
$admin=$_SESSION["admin"];
$uri = $_SERVER['SCRIPT_NAME'];
$filename=get_name_script($uri);
$links=permission_usr($id_user,$filename);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title text-navy">Descartar Oferta</h4>
</div>
<div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row" id="row1">
            <div class="col-lg-12">
            <?php	if ($links!='NOT' || $admin=='1' ){ ?>
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group has-info text-center alert alert-info">
                          <label><?php echo "Informacion de la Oferta."; ?></label>
                      </div>
                    </div>
                    <br>
                    <div class='col-md-12'>
                        <?php 
                        echo $tablas;
                        
                        ?>
                    
                    </div>
                    <div class="col-lg-12">
                        <label for="">Motivo de Descarte: </label>
                        <textarea name="motivo_descarte" id="motivo_descarte" style="width:100%" rows="5"></textarea>
                    </div>
                </div>
                <input type="hidden" name="id_oferta" id='id_oferta' value="<?php echo $id_oferta; ?>">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-primary" id="btnDescartar">Descartar</button>
<?php
    echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
    </div><!--/modal-footer -->";
}
else {
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
}
}

function descartar()
{
    $id_oferta = $_POST['id_oferta'];
    $motivo_descarte = $_POST['motivo_descarte'];
    $tabla = 'ofertas';
    $form_data = array(
        'estado_espera' => 0,
        'estado_rechazado' => 0,
        'estado_descartado' => 1,
        'estado_aprobado' => 0,
        'motivo_descarte' => $motivo_descarte
    );
    $where = " id_oferta = '$id_oferta'";
    $descartar = _update($tabla, $form_data, $where);
    if ($descartar) {
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['msg'] = 'Oferta descartada correctamente!';
    } else {
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'La Oferta no pudo ser descartada!';
    }	
	echo json_encode ( $xdatos );
}
if (! isset ( $_REQUEST ['process'] ))
{
	initial();
} else
{
	if (isset ( $_REQUEST ['process'] ))
	{
		switch ($_REQUEST ['process'])
		{
			case 'formDelete' :
				initial();
				break;
			case 'descartar' :
				descartar();
				break;
		}
	}
}
?>
