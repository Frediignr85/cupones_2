<?php
include ("_core.php");
function initial(){
$id_oferta = $_REQUEST['id_oferta'];
$id_user=$_SESSION["id_usuario"];
$admin=$_SESSION["admin"];
$uri = $_SERVER['SCRIPT_NAME'];
$filename=get_name_script($uri);
$links=permission_usr($id_user,$filename);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title text-navy">Responder Motivo de Rechazo</h4>
</div>
<div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row" id="row1">
            <div class="col-lg-12">
            <?php	if ($links!='NOT' || $admin=='1' ){ ?>
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group has-info text-center alert alert-info">
                          <label><?php echo "Ingrese su respuesta."; ?></label>
                      </div>
                    </div>
                    <br>
                    <div class="col-lg-12">
                        <label for="">Respuesta: </label>
                        <textarea name="respuesta_motivo" id="respuesta_motivo" style="width:100%" rows="5"></textarea>
                    </div>
                </div>
                <input type="hidden" name="id_oferta" id='id_oferta' value="<?php echo $id_oferta; ?>">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-primary" id="btnResponder">Responder</button>
<?php
    echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
    </div><!--/modal-footer -->";
}
else {
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
}
}

function responder()
{
    $id_oferta = $_POST['id_oferta'];
    $respuesta_motivo = $_POST['respuesta_motivo'];
    $tabla = 'ofertas';
    $form_data = array(
        'respuesta_motivo' => $respuesta_motivo
    );
    $where = " id_oferta = '$id_oferta'";
    $responder = _update($tabla, $form_data, $where);
    if ($responder) {
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['msg'] = 'Respuesta enviada correctamente!';
    } else {
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'La Respuesta no pudo ser enviada!';
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
			case 'responder' :
				responder();
				break;
		}
	}
}
?>
