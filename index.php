<?php
session_start();
include("consola/_conexion.php");
include("consola/header2.php");
include("consola/menu_inicio.php");

?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="contenedor_index">
        <div class="col-lg-12">
            <div class="carrousel">
                <div class="carrousel-item carrousel-item-visible">
                    <img class="carrousel-item-img" src="img/1.png" alt="Bares">
                </div>

                <div class="carrousel-item">
                    <img class="carrousel-item-img" src="img/2.png" alt="Cursos">
                </div>

                <div class="carrousel-item">
                    <img class="carrousel-item-img" src="img/3.png" alt="Hospitales">
                </div>

                <div class="carrousel-item carrousel-item-visible">
                    <img class="carrousel-item-img" src="img/4.png" alt="Venta de Calzado">
                </div>

                <div class="carrousel-item">
                    <img class="carrousel-item-img" src="img/5.png" alt="Turismo">
                </div>

                <div class="carrousel-item">
                    <img class="carrousel-item-img" src="img/6.png" alt="Restaurantes">
                </div>

                <div class="carrousel-item carrousel-item-visible">
                    <img class="carrousel-item-img" src="img/7.png" alt="Salones de belleza">
                </div>

                <div class="carrousel-item">
                    <img class="carrousel-item-img" src="img/8.png" alt="Talleres">
                </div>

                <div class="carrousel-item">
                    <img class="carrousel-item-img" src="img/9.png" alt="Librerias">
                </div>

                <div class="carrousel-item carrousel-item-visible">
                    <img class="carrousel-item-img" src="img/13.png" alt="Electronica">
                </div>

                <div class="carrousel-item">
                    <img class="carrousel-item-img" src="img/10.png" alt="Kioskos">
                </div>

                <div class="carrousel-item">
                    <img class="carrousel-item-img" src="img/11.png" alt="Jugueterias">
                </div>

                <div class="carrousel-item carrousel-item-visible">
                    <img class="carrousel-item-img" src="img/12.png" alt="Ferreterias">
                </div>
            </div>
        </div>
        <br>
        <div class="col-lg-12">
            <h1 class="titulo_principal">Ofertas</h1>
        </div>
        <div class="col-lg-12 contenedor_ofertas">
            <div class="row">
                <?php
					if(isset($_GET['id_rubro'])){
						$sql_ofertas = "SELECT ofertas.id_oferta,
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
						WHERE ofertas.eliminado = 0 AND ofertas.estado_aprobado = 1 AND (ofertas.ilimitar = 1 OR ofertas.cantidad_limite_cupones > 0)
						AND rubros.id_rubro = '".$_GET['id_rubro']."' AND CURDATE() BETWEEN ofertas.fecha_inicio and ofertas.fecha_fin";
					}
					else{
						$sql_ofertas = "SELECT ofertas.id_oferta,
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
						WHERE ofertas.eliminado = 0 AND ofertas.estado_aprobado = 1 AND (ofertas.ilimitar = 1 OR ofertas.cantidad_limite_cupones > 0)
						AND CURDATE() BETWEEN ofertas.fecha_inicio and ofertas.fecha_fin";
					}
					$query_ofertas = _query($sql_ofertas);
					if(_num_rows($query_ofertas) > 0){
						while($row = _fetch_array($query_ofertas)){
							$id_oferta = $row['id_oferta'];
							$titulo = $row['titulo'];
							$precio_regular = $row['precio_regular'];
							$precio_oferta = $row['precio_oferta'];
							$cantidad_limite_cupones = $row['cantidad_limite_cupones'];
							$porcentaje = 100- (($precio_oferta/$precio_regular)*100);
							$cantidad_limite_cupones = $row['cantidad_limite_cupones'];
							$cantidad_cupones = $cantidad_limite_cupones;
							$ilimitar = $row['ilimitar'];
							if($ilimitar){
								$cantidad_cupones = "Ilimitados";
							}

							$descripcion = $row['descripcion'];
							$id_empresa = $row['id_empresa'];
							$nombre_empresa = $row['nombre_empresa'];
							$fecha_fin = $row['fecha_fin'];
							$fecha_inicio = $row['fecha_inicio'];
							$nombre_rubro = $row['nombre_rubro'];
							$for = rand(1,8);
							for ($i=0; $i < $for; $i++) { 
								$numero = rand(1, 20);
							}
							$imagen = "img/color".$numero.".jpg";
							?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xl-4 single-item">
                    <div id="container">
                        <div class="product-details">
                            <h1><?php echo $nombre_empresa; ?></h1>
                            <p class="information"><b><?php echo $titulo; ?></b><?php echo ".<br>".$descripcion; ?></p>
                            <?php
												if(isset($_SESSION['id_usuario'])){
													?>
                            <div class="control">
                                <button class="BOTON" id="<?php echo $id_oferta; ?>">
                                    <span class="price"><?php echo "$ ".number_format($precio_oferta,2); ?></span>
                                    <span class="shopping-cart"><i class="fa fa-shopping-cart"
                                            aria-hidden="true"></i></span>
                                    <span class="buy"><i class="fa fa-plus"></i> Carrito</span>
                                </button>
                            </div>
                            <?php
												}
											?>
                        </div>
                        <div class="product-image">
                            <img src="<?php echo $imagen; ?>" alt="">
                            <div class="info">
                                <h2> Descripcion</h2>
                                <ul>
                                    <li><strong>Precio Anterior :
                                        </strong><br><?php echo "$ ".number_format($precio_regular,2); ?> </li>
                                    <li><strong>Precio Nuevo :
                                        </strong><br><?php echo "$ ".number_format($precio_oferta,2); ?></li>
                                    <li><strong>Porcentaje Descuento: </strong><br><?php echo number_format($porcentaje,2)." %"; ?></li>
                                    <li><strong>Fecha Inicio: </strong><br><?php echo $fecha_inicio; ?></li>
                                    <li><strong>Fecha Fin: </strong><br><?php echo $fecha_fin; ?></li>
                                    <li><strong>Cantidad Disponible: </strong><br><?php echo $cantidad_cupones; ?></li>

                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
						}
					}
				?>
            </div>
        </div>
    </div>
</div>

<br>

</div>

<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog  modal-lg'>
        <div class='modal-content modal-lg'></div>
    </div>
</div>
<div class="footer-dark">
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-3 item">
                    <h3>Servicios</h3>
                    <ul>
                        <li><a href="#">Servicios de Posicionamient1o</a></li>
                        <li><a href="#">Mejoramiento de Imagen</a></li>
                        <li><a href="#">Crecimiento</a></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-md-3 item">
                    <h3>Acerca</h3>
                    <ul>
                        <li><a href="#">Compañia</a></li>
                        <li><a href="#">Equipo</a></li>
                        <li><a href="#">Servicios</a></li>
                    </ul>
                </div>
                <div class="col-md-6 item text">
                    <h3>Shopee</h3>
                    <p>Nosotros somos una compañia la cual a traves de asociaciones con clientes, los potenciamos a
                        traves de nuestra plataforma para que puedan hacer ofertas a traves de cupones canjeables, los
                        cuales son vistos por personas al rededor del mundo.</p>
                </div>
                <div class="col item social"><a href="#"><i class="icon ion-social-facebook"></i></a><a href="#"><i
                            class="icon ion-social-twitter"></i></a><a href="#"><i
                            class="icon ion-social-snapchat"></i></a><a href="#"><i
                            class="icon ion-social-instagram"></i></a></div>
            </div>
            <p class="copyright">Shopee © <?php echo date("Y"); ?></p>
        </div>
    </footer>
</div>
<?php
include("consola/footer2.php");
echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.umd.js\"></script>";
echo "<script src='js/scripts.js'></script>";

?>

