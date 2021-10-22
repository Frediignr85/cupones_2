<?php
include ("_core.php");
function initial(){
$id_oferta = $_REQUEST['id_oferta'];
$sql1="SELECT ofertas.id_oferta,
ofertas.titulo,
ofertas.precio_regular,
ofertas.precio_oferta,
ofertas.cantidad_limite_cupones,
ofertas.ilimitar,
ofertas.descripcion,
ofertas.id_empresa,
empresas.nombre as 'nombre_empresa',
ofertas.fecha_fin, 
ofertas.fecha_inicio,
rubros.id_rubro,
rubros.nombre as 'nombre_rubro'
FROM ofertas INNER JOIN empresas on empresas.id_empresa = ofertas.id_empresa
INNER JOIN rubros on rubros.id_rubro = empresas.id_rubro
WHERE ofertas.eliminado = 0 AND ofertas.estado_aprobado = 1 AND (ofertas.ilimitar = 1 OR ofertas.cantidad_limite_cupones > 0) AND ofertas.id_oferta = '".$id_oferta."'
AND CURDATE() BETWEEN ofertas.fecha_inicio and ofertas.fecha_fin";
$consulta1 = _query($sql1);
$row1 = _fetch_array($consulta1);
$id_oferta = $row1['id_oferta'];
$titulo = $row1['titulo'];
$precio_regular = $row1['precio_regular'];
$precio_oferta = $row1['precio_oferta'];
$cantidad_limite_cupones = $row1['cantidad_limite_cupones'];
$porcentaje = 100- (($precio_oferta/$precio_regular)*100);
$cantidad_limite_cupones = $row1['cantidad_limite_cupones'];
$cantidad_cupones = $cantidad_limite_cupones;
$ilimitar = $row1['ilimitar'];
if($ilimitar){
    $cantidad_cupones = "Ilimitados";
}
$diferencia = $precio_regular - $precio_oferta;
$tablas="";
$tablas.="<table class='table'>";
$tablas.="<thead class='thead-dark'>";
$tablas.="<tr>";
$tablas.="<th scope='col'>Titulo</th>";
$tablas.="<th scope='col'>Precio Anterior</th>";
$tablas.="<th scope='col'>Precio Oferta</th>";
$tablas.="<th scope='col'>Cantidad</th>";
$tablas.="<th scope='col'>Ahorro</th>";
$tablas.="</tr>";
$tablas.="</thead>";
$tablas.="<tbody>";
$tablas.="<tr>";
$tablas.="<td>".$titulo."</td>";
$tablas.="<td>"."$ ".number_format($precio_regular,2)."</td>";
$tablas.="<td>"."$ ".number_format($precio_oferta,2)."</td>";
$tablas.="<td>".$cantidad_cupones."</td>";
$tablas.="<td>"."$ ".number_format($diferencia,2)." (".number_format($porcentaje,2)."%)</td>";
$tablas.="</tr>";
$tablas.="</tbody>
    </table>";

?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title text-navy">Agregar Oferta al Carrito</h4>
</div>
<div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row" id="row1">
            <div class="col-lg-12">
               
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
                        <label for="" id="label_cantidad_carrito">Cantidad: </label>
                        <input type="number" class="form-data" style="width: 100%;" name="cantidad_ofertas" id="cantidad_ofertas" <?php if($cantidad_cupones != "Ilimitados"){ echo "max=\"$cantidad_cupones\"";} ?>>
                    </div>
                </div>
                <input type="hidden"  name="id_oferta" id='id_oferta' value="<?php echo $id_oferta; ?>">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" id="btnAgregarCarrito">Agregar Al Carrito</button>
    <?php
    echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
    </div><!--/modal-footer -->";

}

function deleted()
{
    $id_oferta = $_POST['id_oferta'];
    $desactivar  = _desactivar('empresas'," WHERE id_oferta = '$id_oferta'");
    if ($desactivar) {
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['msg'] = 'Empresa eliminada correctamente!';
    } else {
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'La Empresa no pudo ser eliminada!';
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