<?php
	include ("_core.php");
	// Page setup
	include("header.php");
    include("menu.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

    if ($links!='NOT' || $admin=='1' ){
	//mysql_query("SET NAMES 'utf8'");
?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row" id="row1">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <header>
                        <h3 style="color:#60A5FC;"><i class="fa fa-user-md"></i>
                            <b><?php echo "Administrar Clientes";?></b></h3>
                    </header>
                    <section>
                        <table class="table table-striped table-bordered table-hover" id="editable">
                            <thead>
                                <tr>
                                    <th class="col-lg-1">Codigo</th>
                                    <th class="col-lg-2">Nombre</th>
                                    <th class="col-lg-2">Usuario</th>
                                    <th class="col-lg-2">Telefono</th>
                                    <th class="col-lg-2">Correo</th>
                                    <th class="col-lg-2">Cup. Canj.</th>
                                    <th class="col-lg-2">Cup. No Canj.</th>
                                    <th class="col-lg-2">Cup. Venci.</th>
                                    <th class="col-lg-2">Cant. Compras.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  
                                    $query_pro = "SELECT clientes.id_cliente, clientes.nombres, clientes.apellidos, clientes.usuario, clientes.telefono, clientes.correo, 
                                    (SELECT SUM(compra_cupones_detalle.cantidad) FROM compra_cupones_detalle INNER JOIN compra_cupones on compra_cupones.id_compra_cupon = compra_cupones_detalle.id_compra_cupon INNER JOIN compra on compra.id_compra = compra_cupones.id_compra WHERE compra.id_cliente = clientes.id_cliente AND compra_cupones_detalle.canjeado = 1) as 'cupones_canjeados',
                                    (SELECT SUM(compra_cupones_detalle.cantidad) FROM compra_cupones_detalle INNER JOIN compra_cupones on compra_cupones.id_compra_cupon = compra_cupones_detalle.id_compra_cupon INNER JOIN compra on compra.id_compra = compra_cupones.id_compra INNER JOIN ofertas on ofertas.id_oferta = compra_cupones_detalle.id_oferta WHERE compra.id_cliente = clientes.id_cliente AND compra_cupones_detalle.canjeado = 0 AND CURDATE() <= ofertas.fecha_fin) as 'cupones_sin_canjear',
                                    (SELECT SUM(compra_cupones_detalle.cantidad) FROM compra_cupones_detalle INNER JOIN compra_cupones on compra_cupones.id_compra_cupon = compra_cupones_detalle.id_compra_cupon INNER JOIN compra on compra.id_compra = compra_cupones.id_compra INNER JOIN ofertas on ofertas.id_oferta = compra_cupones_detalle.id_oferta WHERE compra.id_cliente = clientes.id_cliente AND compra_cupones_detalle.canjeado = 0 AND CURDATE() > ofertas.fecha_fin) as 'cupones_vencidos',
                                    (SELECT COUNT(compra.id_compra) FROM compra WHERE compra.eliminado = 0 AND compra.id_cliente = clientes.id_cliente) as 'cantidad_compras'
                                    FROM clientes WHERE clientes.eliminado = 0";
                                    $query_pro = _query($query_pro);
                                    while($row = _fetch_array($query_pro)){
                                        $id_cliente = $row['id_cliente'];
                                        $nombre = $row['nombres']." ".$row['apellidos'];
                                        $usuario = $row['usuario'];
                                        $telefono = $row['telefono'];
                                        $correo = $row['correo'];
                                        $cupones_canjeados = $row['cupones_canjeados'];
                                        if($cupones_canjeados == ""){
                                            $cupones_canjeados = 0;
                                        }
                                        $cupones_sin_canjear = $row['cupones_sin_canjear'];
                                        if($cupones_sin_canjear == ""){
                                            $cupones_sin_canjear = 0;
                                        }
                                        $cupones_vencidos = $row['cupones_vencidos'];
                                        if($cupones_vencidos == ""){
                                            $cupones_vencidos = 0;
                                        }
                                        $cantidad_compras = $row['cantidad_compras'];
                                ?>
                                    <tr>
                                        <th><?php echo $id_cliente; ?></th>
                                        <th><?php echo $nombre; ?></th>
                                        <th><?php echo $usuario; ?></th>
                                        <th><?php echo $telefono; ?></th>
                                        <th><?php echo $correo; ?></th>
                                        <th><?php echo $cupones_canjeados; ?></th>
                                        <th><?php echo $cupones_sin_canjear; ?></th>
                                        <th><?php echo $cupones_vencidos; ?></th>
                                        <th><?php echo $cantidad_compras; ?></th>
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
    include("footer.php");

    echo" <script type='text/javascript' src='js/funciones/funciones_empresas.js'></script>";
} //permiso del script
else
{
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}
?>