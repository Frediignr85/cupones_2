<?php
	include ("consola/_core.php");
	// Page setup
	include("consola/header2.php");
    include("consola/menu_inicio.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	//mysql_query("SET NAMES 'utf8'");
?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row" id="row1">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <header>
                        <h3 style="color:#60A5FC;"><i class="fa fa-user-md"></i>
                            <b><?php echo "Compras Realizadas";?></b></h3>
                    </header>
                    <section>
                        <table class="table table-striped table-bordered table-hover" id="editable">
                            <thead>
                                <tr>
                                    <th class="col-lg-1">Id Compra</th>
                                    <?php
                                        if($admin){
                                            ?>
                                                <th class="col-lg-2">Cliente</th>
                                            <?php
                                        }
                                    ?>
                                    <th class="col-lg-2">Total</th>
                                    <th class="col-lg-2">Cantidad de Cupones</th>
                                    <th class="col-lg-2">Tarjeta</th>
                                    <th class="col-lg-2">Numero Tarjeta</th>
                                    <th class="col-lg-2">Propietario</th>
                                    <?php
                                        if($admin){
                                            ?>
                                                <th class="col-lg-2">Ganancia Generada</th>
                                            <?php
                                        }
                                    ?>
                                    <th class="col-lg-2">Canjeado</th>
                                    <th class="col-lg-2">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  
                                    if($admin){
                                        $query_pro = "SELECT compra.id_compra, 
                                        clientes.nombres, clientes.apellidos, 
                                        compra.total, 
                                        (SELECT SUM(compra_cupones_detalle.cantidad) FROM compra_cupones_detalle INNER JOIN compra_cupones on compra_cupones_detalle.id_compra_cupon = compra_cupones.id_compra_cupon INNER JOIN compra as compra1 on compra1.id_compra = compra_cupones.id_compra WHERE compra1.id_compra = compra.id_compra) as 'cantidad_cupones', 
                                        compra.nombre_tarjeta, compra.numero_tarjeta, compra.propietario_tarjeta, 
                                        (SELECT (SUM(compra_cupones.total* empresas.porcentaje)/100) FROM compra_cupones INNER JOIN empresas on empresas.id_empresa = compra_cupones.id_empresa INNER JOIN compra as compra2 on compra2.id_compra = compra_cupones.id_compra WHERE compra2.id_compra = compra.id_compra) as 'ganancia_generada' 	
                                        FROM compra INNER JOIN clientes on clientes.id_cliente = compra.id_cliente WHERE compra.eliminado = 0";
                                    }
                                    else{
                                        $query_pro = "SELECT compra.id_compra, 
                                        compra.total, 
                                        (SELECT SUM(compra_cupones_detalle.cantidad) FROM compra_cupones_detalle INNER JOIN compra_cupones on compra_cupones_detalle.id_compra_cupon = compra_cupones.id_compra_cupon INNER JOIN compra as compra1 on compra1.id_compra = compra_cupones.id_compra WHERE compra1.id_compra = compra.id_compra) as 'cantidad_cupones', 
                                        compra.nombre_tarjeta, compra.numero_tarjeta, compra.propietario_tarjeta
                                        FROM compra WHERE compra.id_cliente = '".$_SESSION['id_cliente']."' AND compra.eliminado = 0";
                                    }
                                    $query_pro = _query($query_pro);
                                    while($row = _fetch_array($query_pro)){
                                        $id_compra = $row['id_compra'];
                                        $total  = $row['total'];
                                        $cantidad_cupones = $row['cantidad_cupones'];
                                        $nombre_tarjeta = $row['nombre_tarjeta'];
                                        $numero_tarjeta = $row['numero_tarjeta'];
                                        $propietario_tarjeta = $row['propietario_tarjeta'];
                                        $sql_canjeados = "SELECT( SELECT SUM(compra_cupones_detalle.cantidad) FROM compra_cupones_detalle INNER JOIN compra_cupones on compra_cupones.id_compra_cupon = compra_cupones_detalle.id_compra_cupon INNER JOIN compra as compra1 on compra1.id_compra = compra_cupones.id_compra WHERE compra.id_compra = compra1.id_compra AND compra_cupones_detalle.canjeado = 1) as 'cupones_canjeados', ( SELECT SUM(compra_cupones_detalle.cantidad) FROM compra_cupones_detalle INNER JOIN compra_cupones on compra_cupones.id_compra_cupon = compra_cupones_detalle.id_compra_cupon INNER JOIN compra as compra1 on compra1.id_compra = compra_cupones.id_compra WHERE compra.id_compra = compra1.id_compra AND compra_cupones_detalle.canjeado = 0) as 'cupones_no_canjeados' FROM compra WHERE compra.eliminado = 0 AND compra.id_compra = '$id_compra'";
                                        $query_canjeados = _query($sql_canjeados);       
                                        $row_canjeados = _fetch_array($query_canjeados);
                                        $cupones_canjeados = $row_canjeados['cupones_canjeados'];
                                        $cupones_no_canjeados = $row_canjeados['cupones_no_canjeados'];      
                                        if($cupones_canjeados == ""){
                                            $estado = 1;
                                            $label = "<label class=\"text-primary\">Sin Canjear</label>";
                                        }
                                        elseif($cupones_no_canjeados == ""){
                                            $estado = 2;
                                            $label = "<label class=\"text-success\">Canjeado</label>";
                                        }
                                        else{
                                            $estado = 3;
                                            $label = "<label class=\"text-warning\">Parcialmente</label>";
                                        }
                                        $boton = "<td><div class=\"btn-group\">
                                        <a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
                                        <ul class=\"dropdown-menu dropdown-primary\">";
                                            if($estado != 2){
                                                $boton .="<li><a href=\"bonos_compra.php?id_compra=".$row['id_compra']."\" target=\"_blank\"><i class=\"fa fa-print\"></i> Generar Reporte</a></li>";
                                            }
                                            $boton .= "<li><a data-toggle='modal' href='ver_compra.php?id_compra=".$row ['id_compra']."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eye\"></i> Ver Compra</a></li>";
                                            $boton .= "	</ul>
                                                </div>
                                                </td>";
                                ?>
                                    <tr>
                                        <th><?php echo $id_compra; ?></th>
                                        <?php
                                            if($admin){
                                                $nombre_cliente = $row['nombres']." ".$row['apellidos'];
                                                ?>
                                                    <th><?php echo $nombre_cliente; ?></th>
                                                <?php
                                            }
                                        ?>
                                        
                                        <th><?php echo $total; ?></th>
                                        <th><?php echo $cantidad_cupones; ?></th>
                                        <th><?php echo $nombre_tarjeta; ?></th>
                                        <th><?php echo $numero_tarjeta; ?></th>
                                        <th><?php echo $propietario_tarjeta; ?></th>
                                        <?php
                                            if($admin){
                                                $ganancia_generada = $row['ganancia_generada'];
                                                ?>
                                                    <th><?php echo $ganancia_generada; ?></th>
                                                <?php
                                            }
                                        ?>
                                        <th><?php echo $label; ?></th>
                                        <?php echo $boton; ?>
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <input type="hidden" name="autosave" id="autosave" value="false-0">
                    </section>


                    <div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
                        aria-hidden='true'>
                        <div class='modal-dialog modal-lg'>
                            <div class='modal-content modal-lg'></div>
                        </div>
                    </div>


                    <div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
                        aria-hidden='true'>
                        <div class='modal-dialog  modal-lg'>
                            <div class='modal-content modal-lg'></div>
                        </div>
                    </div>

                </div>
                <!--div class='ibox-content'-->
            </div>
            <!--<div class='ibox float-e-margins' -->
        </div>
        <!--div class='col-lg-12'-->
    </div>
    <!--div class='row'-->
</div>
<!--div class='wrapper wrapper-content  animated fadeInRight'-->
</div>
<?php
    include("consola/footer2.php");
    echo" <script type='text/javascript' src='js/scripts.js'></script>";
?>