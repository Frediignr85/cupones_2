<?php
include ("consola/_core.php");
function initial(){
    $id_compra = $_REQUEST['id_compra'];
    $tabla_devolver = "";
    $tabla_devolver.='<table class="table" >';
    $tabla_devolver.='<thead class="thead-dark" id="thead-dark">';
    
    $tabla_devolver.='<tr>';
    $tabla_devolver.='<th scope="col">#</th>';
    $tabla_devolver.='<th scope="col">ID OFERTA</th>';
    $tabla_devolver.='<th scope="col">EMPRESA</th>';
    $tabla_devolver.='<th scope="col">TITULO</th>';
    $tabla_devolver.='<th scope="col">PRECIO</th>';
    $tabla_devolver.='<th scope="col">CANTIDAD</th>';
    $tabla_devolver.='<th scope="col">TOTAL</th>';
    $tabla_devolver.='<th scope="col">CANJEADO</th>';
    $tabla_devolver.='</tr>';
    $tabla_devolver.='</thead>';
    $tabla_devolver.='<tbody>';
    $count = 1;
    $sql = "SELECT ofertas.id_oferta, clientes.dui, empresas.nombre as 'nombre_empresa', ofertas.titulo, compra_cupones_detalle.precio,compra_cupones_detalle.canjeado, compra_cupones_detalle.cantidad, compra_cupones_detalle.total FROM compra_cupones_detalle INNER JOIN ofertas on ofertas.id_oferta = compra_cupones_detalle.id_oferta INNER JOIN compra_cupones on compra_cupones.id_compra_cupon = compra_cupones_detalle.id_compra_cupon  INNER JOIN empresas on empresas.id_empresa = compra_cupones.id_empresa INNER JOIN compra on compra.id_compra = compra_cupones.id_compra INNER JOIN clientes on clientes.id_cliente = compra.id_cliente WHERE compra.id_compra = '$id_compra'";
    $query = _query($sql);
    if(_num_rows($query) > 0){
        $cantidad_final = 0;
        $total_final =0;
        while($row = _fetch_array($query)){                                
            $id_oferta = $row['id_oferta'];
            $titulo = $row['titulo'];
            $nombre_empresa = $row['nombre_empresa'];
            $precio_oferta = $row['precio'];
            $cantidad = $row['cantidad'];
            $cantidad_final+=$cantidad;
            $total = $row['total'];
            $dui = $row['dui'];
            $canjeado = $row['canjeado'];
            $canj = "No";
            if($canjeado){
                $canj = "Si";
            }
            $total_final+=$total;
            $tabla_devolver.='<tr>';
            $tabla_devolver.='<td>'.$count.'</td>';
            $tabla_devolver.='<td>'.$id_oferta.'</td>';
            $tabla_devolver.='<td>'.$nombre_empresa.'</td>';
            $tabla_devolver.='<td>'.$titulo.'</td>';
            $tabla_devolver.='<td>$ '.number_format($precio_oferta,2).'</td>';
            $tabla_devolver.='<td>'.$cantidad.'</td>';
            $tabla_devolver.='<td>$ '.number_format($total,2).'</td>';
            $tabla_devolver.='<td>'.$canj.'</td>';


            $tabla_devolver.='</tr>';
            $count++;
        }
        $tabla_devolver.='<tr>';
        $tabla_devolver.='<td></td>';
        $tabla_devolver.='<td></td>';
        $tabla_devolver.='<td></td>';
        $tabla_devolver.='<td></td>';
        $tabla_devolver.='<td>TOTAL</td>';
        $tabla_devolver.='<td class="cantidad_final">'.$cantidad_final.'</td>';
        $tabla_devolver.='<td class="total_final">$ '.number_format($total_final,2).'</td>';
        $tabla_devolver.='<td></td>';
        $tabla_devolver.='</tr>';
    } 
    $tabla_devolver.='</tbody>';
    $tabla_devolver.='</table>';
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title text-navy">Ver Compra</h4>
</div>
<div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row" id="row1">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group has-info text-center alert alert-info">
                          <label><?php echo "Informacion de la compra."; ?></label>
                      </div>
                    </div>
                    <br>
                    <div class='col-md-12'>
                        <?php 
                        echo $tabla_devolver;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
<?php
    echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
    </div><!--/modal-footer -->";
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
