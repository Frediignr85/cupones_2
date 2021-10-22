<?php
include_once "_conexion.php";
?>
<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span>
                            <img alt="image" class="logo1" id='logo_menu' src="consola/img/fondo.png"
                                style="width:100%; margin-left:-1%;" />
                        </span>
                    </div>
                    <div class="logo-element">

                    </div>
                </li>
                
                <li class="active">
                    <a href="index.php"><i class="fa fa-star"></i><span class="nav-label">HOME</span></a>
                </li>
                <?php
                    $sql_rubros = "SELECT * FROM rubros WHERE eliminado = 0";
                    $query_rubros = _query($sql_rubros);
                    if(_num_rows($query_rubros) > 0){
                        $main_lnk='index.php';
                        echo "<li><a href='".$main_lnk."'><i class='fa fa-coffee'></i></i> <span class='nav-label'>Rubros</span> <span class='fa arrow'></span></a>";
                        echo " <ul class='nav nav-second-level'>";
                        while($row = _fetch_array($query_rubros)){
                            $id_rubro = $row['id_rubro'];
                            $nombre_rubro = $row['nombre'];
                            echo "<li><a href='index.php?id_rubro=$id_rubro'>".ucfirst($nombre_rubro)."</a></li>";
                        }
                        echo"</ul>";
                        echo" </li>";
                    }
                    if(isset($_SESSION['id_cliente'])){

                        ?>
                            <li><a href="index.php"><i class="fa fa-money"></i><span class="nav-label">Compras</span><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li><a href="compras_realizadas.php">Compras Realizadas</a></li>
                                </ul>
                            </li>
                        <?php
                    }


                ?>
                

            </ul>

        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top sidebar-social" role="navigation" style="margin-bottom: 0;">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary"><i class="fa fa-bars"></i> </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">SISTEMA DE CUPONERIA</span>
                    </li>
                    <li>
                        <?php
                            if(isset($_SESSION['id_cliente'])){
                                ?>
                                    <span class="m-r-sm text-muted welcome-message"><b>(<?php echo "Bienvenid@ ".$_SESSION['name']; ?>)</b></span>

                                <?php
                            }
                            else{
                                ?>
                                    <span class="m-r-sm text-muted welcome-message"><b></b></span>
                                <?php
                            }
                        ?>
                    </li>
                    <?php
                        if(isset($_SESSION['id_cliente'])){
                            ?>
                            
                                <li>
                                    <a href="carrito_compras.php" class="cart" title="Facebook" target="_blank" rel="nofollow"><i class="fa fa-shopping-cart"></i><span>Carrito</span>
                                    <span id="cart_menu_num" data-action="cart-can" class="badge rounded-circle"></span>
                                    </a>

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
                            <?php
                        }
                        else{
                            ?>
                                <li>
                                    <a href="login.php">
                                        <i class="fa fa-arrow-right "></i> Login
                                    </a>
                                </li>
                                <li>
                                    <a href="register.php">
                                        <i class="fa fa-user"></i> Registrate
                                    </a>
                                </li>
                            <?php
                        }
                    ?>
                    
                </ul>

            </nav>
        </div>


        <div class='modal fade' id='viewModalpw' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
		<div class='modal-dialog'>
			<div class='modal-content'>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>

