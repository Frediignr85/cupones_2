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
                            <b><?php echo "Administrar Ofertas Pasadas";?></b></h3>
                    </header>
                    <section>
                        <table class="table table-striped table-bordered table-hover" id="editable">
                            <thead>
                                <tr>
                                    <th class="col-lg-1">ID</th>
                                    <?php
                                        if($admin){
                                            ?>
                                                <th class="col-lg-2">Empresa</th>
                                            <?php
                                        }
                                    ?>
                                    <th class="col-lg-2">Titulo</th>
                                    <th class="col-lg-2">Prec. Regular</th>
                                    <th class="col-lg-2">Prec. Promocion</th>
                                    <th class="col-lg-2">Cupones Disponibles</th>
                                    <th class="col-lg-2">Cupones Vendidos</th>
                                    <th class="col-lg-2">% Desc</th>
                                    <th class="col-lg-2">Fecha Fin</th>
                                    <th class="col-lg-2">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  
                                    if($admin){
                                        $query_pro = "SELECT ofertas.id_oferta, empresas.nombre, ofertas.titulo, ofertas.precio_regular, ofertas.precio_oferta, ofertas.cantidad_limite_cupones, ofertas.ilimitar, ofertas.fecha_inicio, ofertas.fecha_fin, (SELECT SUM(compra_cupones_detalle.cantidad) FROM  compra_cupones_detalle INNER JOIN ofertas as ofertas1 on ofertas1.id_oferta = compra_cupones_detalle.id_oferta WHERE ofertas.id_oferta = ofertas1.id_oferta) as 'cupones_vendidos' FROM ofertas INNER JOIN empresas on empresas.id_empresa = ofertas.id_empresa WHERE ofertas.estado_aprobado = 1 AND ofertas.eliminado = 0 AND CURDATE() > fecha_fin";
                                    }
                                    else{
                                        $query_pro = "SELECT ofertas.id_oferta, empresas.nombre, ofertas.titulo, ofertas.precio_regular, ofertas.precio_oferta, ofertas.cantidad_limite_cupones, ofertas.ilimitar, ofertas.fecha_inicio, ofertas.fecha_fin, (SELECT SUM(compra_cupones_detalle.cantidad) FROM  compra_cupones_detalle INNER JOIN ofertas as ofertas1 on ofertas1.id_oferta = compra_cupones_detalle.id_oferta WHERE ofertas.id_oferta = ofertas1.id_oferta) as 'cupones_vendidos' FROM ofertas INNER JOIN empresas on empresas.id_empresa = ofertas.id_empresa WHERE ofertas.estado_aprobado = 1 AND ofertas.id_empresa = '".$_SESSION['id_sucursal']."' AND ofertas.eliminado = 0 AND CURDATE() > fecha_fin";
                                    }
                                    $query_pro = _query($query_pro);
                                    while($row = _fetch_array($query_pro)){
                                        $id_oferta = $row['id_oferta'];
                                        $titulo = $row['titulo'];
                                        $precio_regular = $row['precio_regular'];
                                        $precio_oferta = $row['precio_oferta'];
                                        $porcentaje = 100- (($precio_oferta/$precio_regular)*100);
                                        $cantidad_limite_cupones = $row['cantidad_limite_cupones'];
                                        $cantidad_cupones = $cantidad_limite_cupones;
                                        $ilimitar = $row['ilimitar'];
                                        if($ilimitar){
                                            $cantidad_cupones = "Ilimitados";
                                        }
                                        $cupones_vendidos = $row['cupones_vendidos'];
                                        if($cupones_vendidos == ""){
                                            $cupones_vendidos = "0";
                                        }
                                        $fecha_fin = $row['fecha_fin'];

                                        $boton = "<td><div class=\"btn-group\">
                                        <a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
                                        <ul class=\"dropdown-menu dropdown-primary\">";
                                            $filename='ver_oferta.php';
                                            $link=permission_usr($id_user,$filename);
                                            if ($link!='NOT' || $admin=='1' )
                                            $boton .= "<li><a data-toggle='modal' href='ver_oferta.php?id_oferta=".$row ['id_oferta']."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eye\"></i> Ver</a></li>";
                                            $filename='ganancias_oferta.php';
                                            $link=permission_usr($id_user,$filename);
                                            if ($link!='NOT' || $admin=='1' )
                                            $boton .= "<li><a data-toggle='modal' href='ganancias_oferta.php?id_oferta=".$row ['id_oferta']."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-money\"></i> Ganancias</a></li>";
                                            $boton .= "	</ul>
                                                </div>
                                                </td>";                               
                                ?>
                                    <tr>
                                        <th><?php echo $id_oferta; ?></th>
                                        <?php 
                                            if($admin){
                                                $empresa = $row['nombre'];
                                                ?>
                                                    <th><?php echo $empresa; ?></th>
                                                <?php
                                            }
                                        ?>
                                        
                                        <th><?php echo $titulo; ?></th>
                                        <th><?php echo $precio_regular; ?></th>
                                        <th><?php echo $precio_oferta; ?></th>
                                        <th><?php echo $cantidad_cupones; ?></th>
                                        <th><?php echo $cupones_vendidos; ?></th>
                                        <th><?php echo $porcentaje." %"; ?></th>
                                        <th><?php echo $fecha_fin; ?></th>
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
    include("footer.php");

    echo" <script type='text/javascript' src='js/funciones/funciones_oferta.js'></script>";
} //permiso del script
else
{
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}
?>