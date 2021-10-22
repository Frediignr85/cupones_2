<?php
include ("_core.php");
function initial(){
$id_oferta = $_REQUEST['id_oferta'];
$sql1="SELECT ofertas.id_oferta, ofertas.titulo,ofertas.motivo_descarte, ofertas.precio_regular, ofertas.precio_oferta, ofertas.fecha_inicio, ofertas.fecha_fin, ofertas.fecha_limite_cupon, ofertas.cantidad_limite_cupones, ofertas.ilimitar, ofertas.descripcion, ofertas.detalles, empresas.nombre FROM ofertas INNER JOIN empresas on ofertas.id_empresa = empresas.id_empresa WHERE ofertas.id_oferta = '$id_oferta'";
$consulta1 = _query($sql1);
$row1 = _fetch_array($consulta1);
$motivo_descarte = $row1['motivo_descarte'];
if($motivo_descarte == ""){
    $motivo_descarte = "Todavia no ha respondido";
}
$tablas = "";
$tablas.="<table class='table'>";
$tablas.="<thead class='thead-dark'>";
$tablas.="<tr>";

$tablas.="<th scope='col'>Motivo de Rechazo</th>";
$tablas.="</tr>";
$tablas.="</thead>";
$tablas.="<tbody>";
$tablas.="<tr>";
$tablas.="<td>".$motivo_descarte."</td>";
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
    <h4 class="modal-title text-navy">Ver Motivo Descarte</h4>
</div>
<div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row" id="row1">
            <div class="col-lg-12">
            <?php	if ($links!='NOT' || $admin=='1' ){ ?>
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group has-info text-center alert alert-info">
                          <label><?php echo "Motivo."; ?></label>
                      </div>
                    </div>
                    <br>
                    <div class='col-md-12'>
                        <?php 
                        echo $tablas;
                        
                        ?>
                    </div>
                </div>
                <input type="hidden" name="id_oferta" id='id_oferta' value="<?php echo $id_oferta; ?>">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">

<?php
    echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
    </div><!--/modal-footer -->";
}
else {
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
}
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
			case 'deleted' :
				deleted();
				break;
		}
	}
}
?>
