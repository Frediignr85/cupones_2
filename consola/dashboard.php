<?php
include("_core.php");
include("header.php");
include("menu.php");
$admin=$_SESSION["admin"];
$id_empleado = $_SESSION['id_empleado'];
$id_dependiente = $_SESSION['id_dependiente'];

if($admin != 0){
?>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-9">
		<h2>Panel de Administraci&oacute;n</h2>
		<h3>Bienvenid@ <?php echo $_SESSION["name"];?></h3>
	</div>
</div>
<?php
}
elseif($id_empleado != 0){
?>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-9">
		<h2>Panel de Administraci&oacute;n</h2>
		<h3>Bienvenid@ <?php echo $_SESSION["name"];?></h3>
	</div>
</div>
<?php
}
elseif($id_dependiente != 0){
?>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-12">
		<h1 class="titulo_principal">INGRESE EL CODIGO DE LA OFERTA</h1>
	</div>
	<div class="col-lg-12">
		<div class="form-group has-info single-line">
			<label for="">Codigo:</label>
			<input type="text" id="codigo_cupon" name="codigo_cupon" class="form-control" placeholder="Ingrese el codigo del cupon, o escanee el QR y ponga el resultado en este campo." required>
		</div>
	<button type="button" name="btnCanjear" id="btnCanjear" style="float: right;" class="btn btn-warning">Canjear</button>
	</div>
	<div id="contenido_tabla_canje">

	</div>
</div>
<?php
}



?>
<?php
include("footer.php");
echo "<script src='js/funciones/funciones_dashboard.js'></script>";

?>
