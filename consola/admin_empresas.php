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
                <?php
				echo "<div class='ibox-title'>";
				$filename='agregar_empresa.php';
				$link=permission_usr($id_user,$filename);
				if ($link!='NOT' || $admin=='1' )
					echo "<a href='agregar_empresa.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar Empresa</a>";
				//permiso del script
				?>
                <div class="ibox-content">
                    <header>
                        <h3 style="color:#60A5FC;"><i class="fa fa-user-md"></i>
                            <b><?php echo "Administrar Empresas";?></b></h3>
                    </header>
                    <section>
                        <table class="table table-striped table-bordered table-hover" id="editable">
                            <thead>
                                <tr>
                                    <th class="col-lg-1">Codigo</th>
                                    <th class="col-lg-2">Nombre</th>
                                    <th class="col-lg-2">Encargado</th>
                                    <th class="col-lg-2">Telefono</th>
                                    <th class="col-lg-2">Correo</th>
                                    <th class="col-lg-2">Rubro</th>
                                    <th class="col-lg-2">Estado</th>
                                    <th class="col-lg-2">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  
                                    $query_pro = "SELECT empresas.id_empresa, empresas.codigo, empresas.nombre, empresas.encargado, empresas.telefono,
                                    empresas.correo, rubros.nombre as 'nombre_rubro', empresas.activo FROM empresas INNER JOIN rubros on rubros.id_rubro = empresas.id_rubro
                                    WHERE empresas.eliminado = 0 ORDER BY id_empresa DESC";
                                    $query_pro = _query($query_pro);
                                    while($row = _fetch_array($query_pro)){
                                        $id_empresa = $row['id_empresa'];
                                        $codigo = $row['codigo'];
                                        $nombre = $row['nombre'];
                                        $activo = $row['activo'];
                                        $encargado = $row['encargado'];
                                        $telefono = $row['telefono'];
                                        $correo = $row['correo'];
                                        $nombre_rubro = $row['nombre_rubro'];
                                        
                                        $boton = "<td><div class=\"btn-group\">
                                        <a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
                                        <ul class=\"dropdown-menu dropdown-primary\">";
                                            $filename='editar_empresa.php';
                                            $link=permission_usr($id_user,$filename);
                                            if ($link!='NOT' || $admin=='1' )
                                            $boton .="<li><a href=\"editar_empresa.php?id_empresa=".$row['id_empresa']."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";
                                            $filename='borrar_empresa.php';
                                            $link=permission_usr($id_user,$filename);
                                            if ($link!='NOT' || $admin=='1' )
                                            $boton .= "<li><a data-toggle='modal' href='borrar_empresa.php?id_empresa=".$row ['id_empresa']."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";
                                            $boton .= "	</ul>
                                                </div>
                                                </td>";
                                                $msg_activo = "";
                                        if($activo == 1){
                                            $msg_activo = "Activo";
                                        }
                                        else{
                                            $msg_activo = "Inactivo";
                                        }

                                ?>
                                    <tr>
                                        <th><?php echo $codigo; ?></th>
                                        <th><?php echo $nombre; ?></th>
                                        <th><?php echo $encargado; ?></th>
                                        <th><?php echo $telefono; ?></th>
                                        <th><?php echo $correo; ?></th>
                                        <th><?php echo $nombre_rubro; ?></th>
                                        <th><?php echo $msg_activo; ?></th>
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

    echo" <script type='text/javascript' src='js/funciones/funciones_empresas.js'></script>";
} //permiso del script
else
{
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}
?>