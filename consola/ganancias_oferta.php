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

$sql = "SELECT ofertas.ilimitar, ofertas.cantidad_limite_cupones, empresas.porcentaje, (SELECT SUM(compra_cupones_detalle.total) FROM compra_cupones_detalle INNER JOIN ofertas as ofertas1 on ofertas1.id_oferta = compra_cupones_detalle.id_oferta WHERE ofertas.id_oferta = ofertas1.id_oferta) as 'total_vendido', (SELECT SUM(compra_cupones_detalle.cantidad) FROM compra_cupones_detalle INNER JOIN ofertas as ofertas2 on ofertas2.id_oferta = compra_cupones_detalle.id_oferta WHERE ofertas.id_oferta = ofertas2.id_oferta) AS 'cantidad_cupones_vendidos' FROM ofertas INNER JOIN empresas on empresas.id_empresa = ofertas.id_empresa WHERE ofertas.id_oferta = '$id_oferta'";
$query = _query($sql);
$row = _fetch_array($query);
$cantidad_limite_cupones = $row['cantidad_limite_cupones'];
$porcentaje = $row['porcentaje'];
$total_vendido = $row['total_vendido'];
$cantidad_cupones_vendidos = $row['cantidad_cupones_vendidos'];
if($total_vendido == ""){
    $total_vendido = 0;
}
if($cantidad_cupones_vendidos == ""){
    $cantidad_cupones_vendidos = 0;
}
$tablas.="<table class='table'>";
$tablas.="<thead class='thead-dark'>";
$tablas.="<tr>";

$tablas.="<th scope='col'>Cupones Disponibles</th>";
$tablas.="<th scope='col'>Precio Individual</th>";
$tablas.="<th scope='col'>Comision por Cupon</th>";
$tablas.="<th scope='col'>Cupones Vendidos</th>";
$tablas.="<th scope='col'>Total Vendido</th>";
$tablas.="<th scope='col'>Total Comision</th>";
$tablas.="</tr>";
$tablas.="</thead>";
$tablas.="<tbody>";
$tablas.="<tr>";
$tablas.="<td>".$cantidad_cupones."</td>";
$tablas.="<td>".$precio_oferta."</td>";
$tablas.="<td>".(($precio_oferta*$porcentaje)/100)."</td>";
$tablas.="<td>".$cantidad_cupones_vendidos."</td>";
$tablas.="<td>".$total_vendido."</td>";
$tablas.="<td>".(($total_vendido*$porcentaje)/100)."</td>";
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
    <h4 class="modal-title text-navy">Ganancias Oferta</h4>
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
