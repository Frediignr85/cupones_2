<?php
include ("_core.php");
function initial(){
    $id_rubro = $_REQUEST['id_rubro'];
$sql1="SELECT * FROM rubros WHERE id_rubro = '$id_rubro'";
$consulta1 = _query($sql1);
$row1 = _fetch_array($consulta1);
$tablas="";
$tablas.="<table class='table'>";
$tablas.="<thead class='thead-dark'>";
$tablas.="<tr>";
$tablas.="<th scope='col'>Id Rubro</th>";
$tablas.="<th scope='col'>Nombre Rubro</th>";
$tablas.="<th scope='col'>Estado</th>";
$tablas.="</tr>";
$tablas.="</thead>";
$tablas.="<tbody>";
$tablas.="<tr>";
$tablas.="<td>".$row1['id_rubro']."</td>";
$tablas.="<td>".$row1['nombre']."</td>";
$tablas.="<td>";
if($row1['activo'] == '1'){
    $tablas.= "<label class='badge' style='background:#58FF3B; color:#FFF; font-weight:bold;'>Activo</label>";
}
if($row1['activo'] == '0'){
    $tablas.= "<label class='badge' style='background:#FF3B3B; color:#FFF; font-weight:bold;'>Inactivo</label>";
}
$tablas.="</td>";
$tablas.="</tr>";
$tablas.="</tbody>
    </table>";


$id_user=$_SESSION["id_usuario"];
$admin=$_SESSION["admin"];
$uri = $_SERVER['SCRIPT_NAME'];
$filename=get_name_script($uri);
$links=permission_usr($id_user,$filename);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title text-navy">Datos del rubro</h4>
</div>
<div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row" id="row1">
            <div class="col-lg-12">
            <?php	if ($links!='NOT' || $admin=='1' ){ ?>
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group has-info text-center alert alert-info">
                          <label><?php echo "Informacion del rubro."; ?></label>
                      </div>
                    </div>
                    <br>
                    <div class='col-md-12'>
                        <?php 
                        echo $tablas;
                        
                        ?>
                    
                    </div>
                </div>
                <input type="hidden" name="id_rubro" id='id_rubro' value="<?php echo $id_rubro; ?>">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-primary" id="btnDelete">Borrar</button>
<?php
    echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
    </div><!--/modal-footer -->";
}
else {
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
}
}

function deleted()
{
    $id_rubro = $_POST['id_rubro'];
    $desactivar  = _desactivar('rubros'," WHERE id_rubro = '$id_rubro'");
    if ($desactivar) {
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['msg'] = 'Rubro eliminado correctamente!';
    } else {
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'El Rubro no pudo ser eliminado';
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
			case 'deleted' :
				deleted();
				break;
		}
	}
}
?>
