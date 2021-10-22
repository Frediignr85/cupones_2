<?php
session_start();
include("consola/_conexion.php");
include("consola/header2.php");
include("consola/menu_inicio.php");

?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h1 class="titulo_principal">Carrito de Compras</h1>
    </div>
    <div class="col-lg-12">
        <br><br>
        <div class="row" id="contenido_tabla">
            
        </div>
    </div>
    <form name="agregar_compra" id="agregar_compra" hidden>
        <div class="col-lg-12">
            <h1 class="titulo_principal">Datos de Pago</h1>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group has-info single-line">
                        <label for="">Nombres del Propietario:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" maxlenght="150" placeholder="Ingrese el nombre del Propietario de la Tarjeta" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group has-info single-line">
                        <label for="">Tarjeta :</label>
                        <select name="tarjeta" id="tarjeta" class="select form-control" require>
                            <option value="">Seleccione</option>
                            <option value="Diners Club">Diners Club</option>
                            <option value="American Express">American Express</option>
                            <option value="Visa">Visa</option>
                            <option value="Mastercard">Mastercard</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group has-info single-line">
                        <label for="">Numero de Tarjeta:</label>
                        <input type="text" id="numero_tarjeta" name="numero_tarjeta" class="form-control" maxlenght="20" placeholder="Ingrese el numero de la Tarjeta" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group has-info single-line">
                        <label for="">CCV:</label>
                        <input type="text" id="ccv" name="ccv" class="form-control" maxlenght="5" placeholder="Ingrese el ccv de la Tarjeta" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group has-info single-line">
                        <label for="">Fecha de Vencimiento:</label>
                        <input type="text" id="fecha_vencimiento" name="fecha_vencimiento" class="form-control" maxlenght="10" placeholder="Ingrese el La fecha de vencimiento (mm/aa)" required>
                    </div>
                </div>
            </div>
            <div>
                <input type="submit" style="float: right;" id="agregar_compra" name="agregar_compra" value="Realizar_Pago" class="btn btn-primary m-t-n-xs" />
            </div>
        </div>
    </form>
    
</div>

<div class="footer-dark">
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-3 item">
                    <h3>Servicios</h3>
                    <ul>
                        <li><a href="#">Servicios de Posicionamiento</a></li>
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
echo "<script src='js/scripts_carrito.js'></script>";

$files = glob('imagenes_qr/*'); //obtenemos todos los nombres de los ficheros
foreach($files as $file){
    if(is_file($file))
    unlink($file); //elimino el fichero
}

?>