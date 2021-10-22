<?php
include ("_core.php");
//require_once "_conexion.php";
include ('num2letras.php');
include ('facturacion_funcion_imprimir.php');
function initial() {

	include("header.php");
	include("menu.php");
	echo "<link href='css/estilo.css' media='screen' rel='stylesheet' />";

empleados_dia();
//crear array cat
$array0= array();

$sqltp="SELECT  COUNT(p.id_producto) as totprodcant
FROM producto AS p
	JOIN categoria c ON p.id_categoria=c.id_categoria
WHERE  p.inactivo=0
AND c.extra=0
";

//todos
$resulttp=_query($sqltp);
$counttp=_num_rows($resulttp);
$rowtp=_fetch_array($resulttp);
//prodctos por categoria
$sql0="SELECT c.id_categoria, c.nombre, COUNT(p.id_producto) as totprodcat
FROM categoria AS c,producto AS p
WHERE p.id_categoria=c.id_categoria
AND c.extra=0
AND p.inactivo=0
GROUP BY c.id_categoria";
$result0=_query($sql0);
$count0=_num_rows($result0);

$array0[0] =("|"." TODOS|".$rowtp['totprodcant']);
for ($a=1;$a<=$count0;$a++){
		$row0=_fetch_array($result0);
		$array0[$a] =$row0['id_categoria']."|".$row0['nombre']."|".$row0['totprodcat'];
}
$rows = array_chunk($array0,6);


$array1= array();
 $sql1=_query("SELECT * FROM mesa ORDER BY id");
 $count1=_num_rows($sql1);
 for ($j=0;$j<$count1;$j++) {
		 $row1=_fetch_array($sql1);
		 $id=$row1['id'];
		 $description=$row1['descripcion'];
		 $array1[$id] = $description;
 }

?>
<!-- Main container starts -->
<div class="main-container">
<div class="row">
	  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><!--div  -->
  <div class="gutter">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><!--div de categorias -->
        <div class="panel">
          <div class="panel-body">
            <?php
						$n_cat=0;
            if ($count0>0){
               foreach ($rows as $row) {
            ?>
            <div class="row gutter">
              <?php
                foreach ($row as $value) {
                list($id_categoria,$descrip,$total)=explode("|",$value);
								 $descripcion=str_pad($descrip,16," ",STR_PAD_BOTH);
              ?>
							<div class="col-md-2  target">
								<button type="button" id="btnCatego" class="btn  btn-info btn-block categori"><strong><?php echo $descripcion." ".$total;?></strong></button>
										<input type='hidden'  class='catego' name='id_cate'  id='id_cate' value='<?php echo $id_categoria;?>' />
									&nbsp;&nbsp;
							</div>
            <?php 	} ?>

            </div>
              <?php } ?>
          </div>
            <?php } ?>
        </div>
      </div>
			<div class="col-lg-12 col-md-12  col-sm-12 col-xs-12">  <!--div de productos -->
				<div class="scroll">
					<div class="panel-body"  id="mostrardatos"></div>
			</div>
		</div>
    </div>
		<!--div class="row gutter">
	  </div-->
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"> <!--div de ordenes -->
			<div class="panel">
				<div class="panel-heading">
					<h4>Ordenes</h4>
				</div>
				<div class="panel-body fixed-panel">
					<table class="table table2 table-fixed table-striped "id="inventable">
						<thead class='thead1'>
							<tr class='tr1'>
								<th class="text-info " >#</th>
								<th class="text-info">Producto</th>
								<th class="text-info">Cantidad&nbsp;&nbsp;&nbsp;</th>
								<th class="text-info">Subt.</th>
								<th class="text-info">Extra</th>
								<th class="text-info">Quit</th>
							</tr>
						</thead>
						<tbody class='tbody1 tbody2'></tbody>
					</table>
			</div>
			<div class="panel-body">
				<div class="row border1">
					<div class="col-md-3 text-center cell">Items:</div>
					<div class="col-md-3 text-center cell borderright" id="totcant">0</div>
					<div class="col-md-3 text-center cell">Total $:</div>
					<div class="col-md-3 text-center cell"  id='totfin'>0.0</div>
						<input type="hidden" id="totalventa" value=0 />
				</div>
				<div class="row " id='form_pago'>

					<div class="col-md-6  cell2">Mesa:</div>
					<div class="col-md-6 text-center cell2">
					<?php
					$nombre_select0="id_mesa";
					$select0=crear_select2($nombre_select0, $array1, -1,"");
					echo $select0; ?>
					</div>
					<div class="col-md-3 cell2">Pago:</div>
					<div class="col-md-3 text-center">
							 <input type="text" id="efectivo" name="efectivo" value=""  class="form-control" >
					</div>

					 <div class="col-md-3 cell2">
							 <label>Cambio $</label>
					 </div>
					 <div class="col-md-3 cell2">
							 <input type="text" id="cambio" name="cambio" value=0 placeholder="cambio" class="form-control teclado_text decimal" readonly >
					 </div>

			 </div>
			</div>
			<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12  text-center">
				<button  id="submit1" name="submit1" class="btn btn-success btn-md btn-block  btn-huge "><i class="icon-checkmark"></i> Cobrar</button>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-center">
				<button  id="BtnClear" name="submit2" class="btn btn-danger btn-md btn-block btn-huge"><i class="icon-checkmark"></i> Limpiar</button>
			</div>

				</div>
			</div>
		</div>
	</div>
  <!-- Row starts -->

  <!-- Row ends -->
	<div class="modal fade" id="viewModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
			   		<div class="modal-content modal-md">
			   		</div>
			   	</div>
			</div>


			<!-- Modal -->
  <!-- Row ends -->
</div>
<!-- Main container ends -->
</div>
<!-- Dashboard Wrapper End -->
</div>
<!-- Container fluid ends -->
<?php
include("footer.php");
//echo "<script src='js/funciones/base.js'></script>";
echo "<script src='js/funciones/menuventa2.js'></script>";
}
function traer_productos($id_categoria = ""){
	$sql1="SELECT p.*
	FROM producto AS p
	WHERE p.extra=0
	AND p.inactivo=0
	";
	$sql1="SELECT p.id_producto,p.descripcion,p.precio,p.imagen
	FROM producto AS p
    JOIN categoria c ON p.id_categoria=c.id_categoria
	WHERE c.extra=0
	AND p.inactivo=0
	";
	$array1= array();
	if($id_categoria == ""){
		$sql1.= " ORDER BY c.id_categoria,p.descripcion ASC";
	}else{
		$sql1.= " AND p.id_categoria='$id_categoria'
		ORDER BY c.id_categoria,p.descripcion ASC";
	}
	$result1=_query($sql1);
	$count1=_num_rows($result1);
	for ($b=0;$b<$count1;$b++){
			$row1=_fetch_array($result1);
			$array1[$b] =$row1['id_producto']."|".$row1['descripcion']."|".$row1['precio']."|".$row1['imagen'];
	}
	return $array1;
}
function mostrar_prodcat(){
	$id_categoria = $_REQUEST['id_cat'];
	$array1= traer_productos($id_categoria);
	$rows1 = array_chunk($array1,4);
	//if ($count1>0){
	foreach ($rows1 as $row1) {
	?>
	<div class="row">
	<?php
		foreach ($row1 as $value1) {

		list($id_producto,$nombre,$precio,$imagen)=explode("|",$value1);
		$precio_f = sprintf('%.2f',$precio);
		$nombre=trim($nombre);
		if($imagen==""){
			$imagen="img/productos/noimage.jpg";
		}
		if(strlen($nombre)>10){
			$nombre.="";
			$clase='texto_peq';
		}
		else{
			$clase='texto_med';
			$nombre.="\n";
		}
	?>
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">

		<div class="card-wrapper orange"  id="imagen1">
			<a class="btnSelProd">

			<div class="card clearfix" >
				  <span class="card-type"><?php echo "  $".$precio_f;?></span>

				<img src='<?php echo $imagen?>' class="img-responsive card-avatar"  alt="Producto">

				<label class='descript <?php echo $clase;?>'><?php echo $nombre;?></label>


			</div>

			<!--ul class="card-actions clearfix">
				<li-->
					 <!--<button type="button" id="btnSelect1" class="btn-sm btn-success btnSelProd"><i class="icon-square-plus"></i></button>-->
						<input type='hidden' class='prod' name='id_prod' id='id_prod' value='<?php echo $id_producto;?>' />
				<!--/li>

			</ul-->

		</a>
		</div>
	</div>
		<?php } ?>
			</div>
	<?php }
}
function consultar_prod(){
	$id_producto = $_REQUEST['id_producto'];

	$sql="SELECT p.*, c.extra
	FROM producto AS p
    JOIN categoria c ON p.id_categoria=c.id_categoria
	WHERE  p.inactivo=0
	AND p.id_producto='$id_producto'";
	//AND p.extra=0
	$result=_query($sql);
  $count=_num_rows($result);
	if($count>0){
	$row=_fetch_array($result);
	$xdatos['descripcion'] =$row['descripcion'];
	$xdatos['precio']= $row['precio'];
  $xdatos['extra']= $row['extra'];
	$xdatos['lleva_extra']= $row['lleva_extra'];
 }
 echo json_encode($xdatos); //Return the JSON Array
}

function insertar(){
	//ACA se va insertar a las sig tablas:factura y detale_factura, correlativos
	$cuantos = $_POST['cuantos'];
  $array_json=$_POST['datos'];
	$total= $_POST['total'];
	$id_usuario=-1;
	$id_sucursal= 1;
  /*
	$id_usuario=$_SESSION["id_usuario"];
	$id_sucursal= $_SESSION['id_sucursal'];
	*/
	$alias_tipodoc = 'TIK';
	$id_cliente=-1;
	$array = json_decode($array_json,true);
	if ($cuantos>0){
		 _begin();
		 $hora=date("H:i:s");
		 $fecha=date('Y-m-d');
		 //turno de caja pendiente

		// Correlativos tipo=1 interno, tipo=2 cliente, 3 es proveedor
			 $table_corr="correlativos";
			 $where_clause_n=" WHERE alias='$alias_tipodoc'
 			 AND tipo=2
 			 AND id_sucursal='$id_sucursal'";
			 $sql_corr="SELECT id_correlativo, alias, numero, tipo, id_sucursal
			 FROM $table_corr ".$where_clause_n;
			 $result_corr= _query($sql_corr);
	 		$rows_corr=_fetch_array($result_corr);
	 		$nrows_corr=_num_rows($result_corr);
	 		$ult_corr=$rows_corr['numero']+1;

			$numero_doc=zfill($ult_corr,10);

			$data_corr = array(
			'numero' => $ult_corr
			);
			$insertar_numdoc = _update($table_corr,$data_corr,$where_clause_n );

		 $sql_ventas="SELECT * FROM factura WHERE numero_doc='$numero_doc'
		  AND  fecha='$fecha'
			AND id_cliente='$id_cliente'
			AND alias_tipodoc='$alias_tipodoc'
		  AND id_sucursal='$id_sucursal'";
			$result_fc=_query($sql_ventas);
			$row_fc=_fetch_array($result_fc);
			$nrows_fc=_num_rows($result_fc);

			if($nrows_fc==0){
				$table_fc= 'factura';
				$form_data_fc = array(
				'id_cliente' => $id_cliente,
				'fecha' => $fecha,
				'numero_doc' => $numero_doc,
				'alias_tipodoc'=>$alias_tipodoc,
				'hora_inicio' => $hora,
				'total' => $total,
				'id_cajero'=> $id_usuario,
				'finalizada' => 0,
				'impresa' => 0,
				'id_sucursal' => $id_sucursal,
				//'turno'=>   $turno,
				);
				//falta en compras vencimiento a 30, 60, 90 dias y vence iva
				$insertar_fc = _insert($table_fc,$form_data_fc );
				$id_fact= _insert_id();
			}
		foreach ($array as $fila1){
			 if($fila1['cantidad']>0 ){
				 $id_producto=$fila1['id'];
				 $prod_superior=$fila1['id_prod_superior'];
				 if( $prod_superior!=-1){
					  list($fila_base,$id_prod_superior)=explode("-",$prod_superior);
				 }else{
					 $id_prod_superior=-1;
					 $fila_base=-1;
				 }
				 $sql_prod="SELECT * FROM producto
				 WHERE id_producto='$id_producto'";
				 $sql_prod="SELECT p.*, c.extra
			 	  FROM producto AS p
			    JOIN categoria c ON p.id_categoria=c.id_categoria
				  WHERE id_producto='$id_producto'";
				 $result_prod=_query($sql_prod);
	 			 $row_prod=_fetch_array($result_prod);
				 $es_roll=$row_prod['es_roll'];
				 $extra=$row_prod['extra'];
				 $lleva_extra=$row_prod['lleva_extra'];
				 $fila_orden=$fila1['fila_orden'];
				 $precio=$fila1['precio'];
				 $cantidad=$fila1['cantidad'];
				 $subtotal=$fila1['subtotal'];
          if($es_roll==0){
						$finished=1;
					}
					else {
						$finished=0;
					}
					if($extra==1){
						$finished=0;
					}
				 $form_data_dc = array(
			 			'id_factura' => $id_fact,
			 			'id_producto' => $id_producto,
						'id_prod_superior' => $id_prod_superior,
			 			'cantidad' => $cantidad,
			 			'precio' => $precio,
						'fila_orden' => $fila_orden,
						'fila_base' => $fila_base,
						'subtotal' => $subtotal,
						'fecha' => $fecha,
						//'roll'=>$es_roll,
			 			'finalizado' => $finished, //es el porcentaje descto sin dividir entre 100
			 		);
			 //detalle de factura
			 $table_dc= 'factura_detalle';
			 $insertar_dc = _insert($table_dc,$form_data_dc );
		}

	 }
}//if $cuantos>0

//  if ($insertar1 && $insertar2 && $insertar3){
if ($insertar_fc )
{
	_commit(); // transaction is committed
	$xdatos['typeinfo']='Success';
		 $xdatos['msg']='Registro de Inventario Actualizado !';
		 $xdatos['process']='insert';
		$xdatos['guardar']="compras: ".$insertar_fc." det compra: ".$insertar_dc." ";
		$xdatos['factura']=$id_fact;
		$xdatos['numero_doc']=$numero_doc;
	}
	else{
	_rollback(); // transaction not committed
		 $xdatos['typeinfo']='Error';
		 $xdatos['msg']='Registro de Inventario no pudo ser Actualizado !';
		 $xdatos['guardar']="compras: ".$insertar_fc." det compra: ".$insertar_dc." ";
}

	echo json_encode($xdatos);
}
//Impresion
function imprimir_fact() {

  $tipo_impresion= $_POST['tipo_impresion'];
  $id_factura= $_POST['num_doc_fact'];
	$mesa= $_POST['mesa'];

	$id_sucursal=1;
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
		$so_cliente='win';
	else
		$so_cliente='lin';

	$sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
	$result_fact=_query($sql_fact);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){

		$table_fact= 'factura';
		$form_data_fact = array(
		//	'finalizada' => '0',
			'mesa' => $mesa,

		);

		$where_clause="WHERE  id_factura='$id_factura'";
		$actualizar = _update($table_fact,$form_data_fact, $where_clause );
	}

  $headers=""; $footers="";

	if ($tipo_impresion=='TIK'){
		$info_facturas=print_ticket($id_factura);
		$sql_pos="SELECT *  FROM config_pos  WHERE id_sucursal='$id_sucursal' AND alias_tipodoc='TIK'";

		$result_pos=_query($sql_pos);
		$row1=_fetch_array($result_pos);

		$headers=$row1['header1']."|".$row1['header2']."|".$row1['header3']."|".$row1['header4']."|".$row1['header5']."|";
		$headers.=$row1['header6']."|".$row1['header7']."|".$row1['header8']."|".$row1['header9']."|".$row1['header10'];
		$footers=$row1['footer1']."|".$row1['footer2']."|".$row1['footer3']."|".$row1['footer4']."|".$row1['footer5']."|";
		$footers.=$row1['footer6']."|".$row1['footer7']."|".$row1['footer8']."|".$row1['footer8']."|".$row1['footer10']."|";
	}
  //falta validar
	$sql_fd="SELECT p.id_producto,c.id_categoria,u.impresion_remoto
	FROM factura_detalle AS fd
	JOIN producto AS p ON p.id_producto=fd.id_producto
	JOIN categoria AS c ON c.id_categoria=p.id_categoria
	JOIN ubicacion AS u ON u.id_ubicacion=c.id_ubicacion
	WHERE fd.id_factura='$id_factura'
	AND u.impresion_remoto=1
	";
	$r_fd=_query($sql_fd);
	$nr_fd=_num_rows($r_fd);
	if ($nr_fd>0){
			$info_print_remoto=print_ticket_remoto($id_factura); //aca se envia el ticket de cocina
	}
	else{
		$info_print_remoto="-1";
	}
	//asignar profesor para despachar item deorden
	asignacion($id_factura);
	//directorio de script impresion cliente
	$sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
	$result_dir_print=_query($sql_dir_print);
	$row0=_fetch_array($result_dir_print);
	$dir_print=$row0['dir_print_script'];
	$shared_printer_win=$row0['shared_printer_matrix'];
	$shared_printer_pos=$row0['shared_printer_pos'];

	$nreg_encode['shared_printer_win'] =$shared_printer_win;
	$nreg_encode['shared_printer_pos'] =$shared_printer_pos;
	$nreg_encode['dir_print'] =$dir_print;
	$nreg_encode['facturar'] =$info_facturas;
	$nreg_encode['sist_ope'] =$so_cliente;
	$nreg_encode['headers'] =$headers;
	$nreg_encode['footers'] =$footers;
	$nreg_encode['mesa'] =$mesa;
	$nreg_encode['info_remoto'] =$info_print_remoto;
	echo json_encode($nreg_encode);

}

//functions to load
if(!isset($_REQUEST['process'])){
	initial();
}
//else {
if (isset($_REQUEST['process'])) {
	switch ($_REQUEST['process']) {
	case 'guardar_orden':
		insertar();
		break;
	case 'cargar_cat':
		cargar_cat();
		break;
		case 'consultar_prod':
		consultar_prod();
		break;
 	case 'mostrar_prodcat':
		mostrar_prodcat();
		break;
	case 'imprimir_fact':
		imprimir_fact();
		break;
	}
}
 ?>
