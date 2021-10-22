<?php
include_once "_core.php";
?>
<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span>
                            <img alt="image" class="logo1" id='logo_menu' src="img/fondo.jpg"
                                style="width:80%; margin-left:-3%;" />
                        </span>
                    </div>
                    <div class="logo-element">

                    </div>
                </li>
                <?php
                //&& $active=='t'
                $id_user=$_SESSION["id_usuario"];
                $admin=$_SESSION["admin"];
                $icono='fa fa-home';
                $sql_menus="SELECT id_menu, nombre, prioridad,icono FROM menu WHERE visible = 1 order by prioridad ";
                $result=_query($sql_menus);
                $numrows=_num_rows($result);
                $main_lnk='dashboard.php';
                echo  "<li class='active'>";
                echo "<a href='dashboard.php'><i class='".$icono."'></i> <span class='nav-label'>Inicio</span></a>";
                echo  "</li>";

                for($i=0;$i<$numrows;$i++){
                  $row=_fetch_array($result);
                  $menuname=$row['nombre'];
                  $id_menu=$row['id_menu'];
                  $icono=$row['icono'];
                  if($admin=='1')
                  {
                    $sql_links="SELECT distinct menu.id_menu, menu.nombre as nombremenu, menu.prioridad,
                    modulo.id_modulo, modulo.nombre as nombremodulo, modulo.descripcion, modulo.filename
                    FROM menu, modulo, usuario
                    WHERE usuario.id_usuario='$id_user'
                    AND menu.id_menu='$id_menu'
                    AND menu.id_menu=modulo.id_menu
                    AND modulo.mostrarmenu='1'";
                  }
                  else
                  {
                    $sql_links="SELECT menu.id_menu, menu.nombre as nombremenu, menu.prioridad,
                    modulo.id_modulo,  modulo.nombre as nombremodulo, modulo.descripcion, modulo.filename,
                    usuario_modulo.id_usuario,usuario.admin
                    FROM menu, modulo, usuario_modulo, usuario
                    WHERE usuario.id_usuario='$id_user'
                    AND menu.id_menu='$id_menu'
                    AND usuario.id_usuario=usuario_modulo.id_usuario
                    AND usuario_modulo.id_modulo=modulo.id_modulo
                    AND menu.id_menu=modulo.id_menu
                    AND modulo.mostrarmenu='1'";
                  }
                  //echo $sql_links;
                  $result_modules=_query($sql_links);
                  $numrow2=_num_rows($result_modules);
                  if($numrow2>0)
                  {
                    echo "<li><a href='".$main_lnk."'><i class='".$icono."'></i></i> <span class='nav-label'>".$menuname."</span> <span class='fa arrow'></span></a>";
                    echo " <ul class='nav nav-second-level'>";
                    for($j=0;$j<$numrow2;$j++)
                    {
                      $row_modules=_fetch_array($result_modules);
                      $lnk=strtolower($row_modules['filename']);
                      $modulo=$row_modules['nombremodulo'];
                      $id_modulo=$row_modules['id_modulo'];
                      echo "<li><a href='".$lnk."'>".ucfirst($modulo)."</a></li>";
                    }
                    echo"</ul>";
                    echo" </li>";
                  }
                }
                ?>
            </ul>

        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0;">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary"><i class="fa fa-bars"></i> </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">SISTEMA</span>
                    </li>
                    <li>
                        <span class="m-r-sm text-muted welcome-message"><b>(<?php echo $_SESSION["name"]; ?>)</b></span>
                    </li>
                    <li>
                                    <a data-toggle='modal' href='cambiar_pass.php' data-target='#viewModalpw' data-refresh='true'>
                                        <i class="fa fa-lock"></i> Contraseña
                                    </a>
                                </li>
                    <li>
                        <a href="logout.php">
                            <i class="fa fa-sign-out"></i> Salir
                        </a>
                    </li>
                </ul>

            </nav>
        </div>


        <div class='modal fade' id='viewModalpw' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
		<div class='modal-dialog'>
			<div class='modal-content'>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>