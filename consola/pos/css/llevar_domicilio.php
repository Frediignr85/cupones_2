<?php
//include ("_core.php");
require_once "_conexion.php";
include ('num2letras.php');
include ('facturacion_funcion_imprimir.php');
function initial() {
	include("header.php");
	include("menu2.php");
	echo "<link href='css/estilo.css' media='screen' rel='stylesheet' />";
	echo "<link href='css/iCheck/custom.css'  media='screen' rel='stylesheet' />";
	echo "<link href='css/keyboard/keyboard.css' media='screen' rel='stylesheet' />";
	//modificarlos para llevar y domicilio en un modal que valide si es llevar o domicilio y que pida los campos
	/* si es llevar solo el nombre del cliente, si es domicilio, nombre cliente, tel y direccion.
	guardar en cada orden el id tipo orden 10local, 2 =llevar y 3=domicilio
	*/
	$fecha=date('Y-m-d');
	//revisar mesas desocupadas por dia
	//revisar_mesaas();
	//apertura de caja
	$sql_ap="SELECT id_apertura FROM apertura_caja
	WHERE fecha='$fecha'
	AND vigente=1";
	$result_ap= _query($sql_ap);
	$rows_ap=_fetch_array($result_ap);
	$nrows_ap=_num_rows($result_ap);
	//crear array cat
	$array0= array();
	$sqltp="SELECT  COUNT(p.id_producto) as totprodcant
	FROM producto AS p
	JOIN categoria c ON p.id_categoria=c.id_categoria
	WHERE  p.inactivo=0
	";
	//todos
	$resulttp=_query($sqltp);
	$counttp=_num_rows($resulttp);
	$rowtp=_fetch_array($resulttp);
	//productos por categoria
	$sql0="SELECT c.id_categoria, c.nombre, COUNT(p.id_producto) as totprodcat
	FROM categoria AS c,producto AS p
	WHERE p.id_categoria=c.id_categoria
	AND p.inactivo=0
	GROUP BY c.id_categoria";
	$result0=_query($sql0);
	$count0=_num_rows($result0);
	$array0[0] =("|"." TODOS|".$rowtp['totprodcant']);
	for ($a=1;$a<=$count0;$a++){
		$row0=_fetch_array($result0);
		$array0[$a] =$row0['id_categoria']."|".$row0['nombre']."|".$row0['totprodcat'];
	}
	$rows = array_chunk($array0,4);
	$array1= array(-1=>"Seleccione");
	$sql1=_query("SELECT * FROM mesa ORDER BY id");
	$count1=_num_rows($sql1);
	for ($j=0;$j<$count1;$j++) {
		 $row1=_fetch_array($sql1);
		 $id=$row1['id'];
		 $description=$row1['descripcion'];
		 $array1[$id] = $description;
	}
	$array2= array(-1=>"Seleccione Mesero");
	$sql2=_query("SELECT * FROM profesor where tipo_emp='MESERO' ORDER BY id_empleado");
	$count2=_num_rows($sql2);
	for ($k=0;$k<$count2;$k++) {
		 $row2=_fetch_array($sql2);
		 $id=$row2['id_empleado'];
		 $description=$row2['nombre'];
		 $array2[$id] = $description;
	}
?>
<!-- Main container starts -->
<div class="main-container" id='main1'>
<div class="row">
	<?php
	if ($nrows_ap>0){
	?>
	  <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><!--div  -->
  <div class="gutter">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><!--div de categorias -->
        <div class="panel">
          <div class="panel-body min-padding">
            <?php
						$n_cat=0;
            if ($count0>0){
               foreach ($rows as $row) {
            ?>
            <div class="row gutter">
              <?php
                foreach ($row as $value) {
                list($id_categoria,$descripcion,$total)=explode("|",$value);
								 $descripcion=trim($descripcion);
              ?>
							<div class="target col-md-3">
								<button type="button" id="btnCatego" class="btn btn-info btn-block categori"><?php echo $descripcion;?></button>
										<input type='hidden'  class='catego' name='id_cate'  id='id_cate' value='<?php echo $id_categoria;?>' />
							</div>
            <?php 	} ?>
            </div>
              <?php } ?>
          </div>
            <?php } ?>
        </div>
      </div>
			<div class="col-lg-12 col-md-12  col-sm-12 col-xs-12">  <!--div de productos -->
				<div class="panel   min-padding">
					<div class="col-lg-12 col-md-12  col-sm-12 col-xs-12">
						<div class="col-lg-6 col-md-6  col-sm-6 col-xs-6">
						<button class="btn btn-success btn-md btn-block" id="down"><i class="icon-white icon-arrow-down"></i></button>
						</div>
							<div class="col-lg-6 col-md-6  col-sm-6 col-xs-6">
		       	<button class="btn btn-warning btn-md btn-block"  id="up"><i class="icon-white icon-arrow-up"></i> </button>
						</div>
				</div>
					<div class="panel-body scrolltable  fixed-panel"  id="mostrardatos"></div>
			</div>
		</div>
    </div>
	  </div-->
		</div>
			<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5"> <!--div de ordenes -->
			<div class="panel">
				<div class="panel-body fixed-panel">
					<div class="row" style="padding-left: 20px">
						<div class="col-md-12">
							<div class="col-lg-6 col-md-6  text-center">
								<input type='hidden'  class='ordenselected' name='orden_sel'  id='orden_sel' value='-1' />
								<input type='hidden'  class='orden_acti' name='orden_activa'  id='orden_activa' value='-1' />
								<button  id="btnGuardarOrden" name="btnGuardarOrden" class="btn btn-success btn-lg btn-block btn-huge"><i class="icon-add"></i> Nueva Orden</button>
							</div>
							<div class="col-lg-6 col-md-6  text-center">
    						<input type='hidden'  class='ordencambioselected' name='orden_selcambio'  id='orden_selcambio' value='-1' />
								<!--a href="javascript:void(0);" data-href="ver_ordens.php" class="btn btn-primary btn-md btn-block  btn-huge popupCambiarOrden">
									<b id="cambio_orden">Ver Ordenes Pendientes</b></a-->
									<button  id="btnVerOrden" name="btnVerOrden" class="btn btn-primary btn-lg btn-block btn-huge popupCambiarOrden"><i class="icon-check"></i> Cambiar Orden</button>
							</div>
	</div>
	<div class="row" id="datoss_client">
		<div class="col-md-12 cell2 mostrar_orden_act">Orden Act:No</div>
		<div class="col-md-12 cell2 cliente_act">Cliente:</div>
</div>
</div>
					<table class="table_venta" id="inventable">
						<thead class='thead1'>
							<tr class='tr1'>
								<th  style="width:10%">#</th>
								<th   style="width:30%;align:center;">Producto&nbsp;</th>
								<th   style="width:20%;align:center;">Cantidad&nbsp;</th>
								<th   style="width:20%">Subt.</th>

								<th   style="width:12%">Quit</th>
							</tr>
						</thead>
						<tbody class='tbody1 tbody2 mostrardatos_orden2' id='mostrardatos_orden'></tbody>
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

			</div>

			</div>
		</div>
	<?php }
	else{
		?>
		<div><h3>No ha realizado apertura de Caja !!!</h3></div>
	<?php
	}
	?>
	</div>
  <!-- Row starts -->
	<!--div id='mostrar_modal_Orden'><!--?php mostrar_modal2();?></div-->
	<div class="modal fade" id="modalCambioOrden" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;       </button>
			<h4 class="modal-title" id="myModalLabel">  Seleccionar Orden</h4>

	            </div>
	            <div class="modal-body">
								<div class="row" id="select_orden fixed-panel2"></div>
	            </div>
							<div class="modal-footer">
 						 	<button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
 						 </div>
	        </div>

	    </div>
	</div>
  <!-- Row ends -->
			<!-- Modal -->
			<div id='mostrar_modal'><?php mostrar_modal();?></div>
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
echo "<script src='js/screenfull/screenfull.min.js'></script>";
echo "<script src='js/iCheck/icheck.min.js'></script>";
echo "<script src='js/funciones/llevar_domicilio.js'></script>";
}
function traer_productos($id_categoria = ""){
	$sql1="SELECT p.*
	FROM producto AS p
	WHERE p.inactivo=0
	";
	$sql1="SELECT p.id_producto,p.descripcion,p.precio,p.imagen
	FROM producto AS p
  JOIN categoria c ON p.id_categoria=c.id_categoria
	WHERE  p.inactivo=0
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
	foreach ($rows1 as $row1) {
	?>
	<div class="row" id='fila'>
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
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
		<div class="card-wrapper orange"  id="imagen1">
			<a class="btnSelProd">
			<div class="card clearfix" >
				  <span class="card-type"><?php echo "  $".$precio_f;?></span>
					<img src='<?php echo $imagen?>' class="img-responsive card-avatar"  alt="Producto">
					<label class='descript <?php echo $clase;?>'><?php echo $nombre;?></label>
			</div>
		  <input type='hidden' class='prod' name='id_prod' id='id_prod' value='<?php echo $id_producto;?>' />
		</a>
		</div>
	</div>
 <?php } ?>
	</div>
<?php
}

 }

function consultar_prod(){
	$id_producto = $_REQUEST['id_producto'];
	$id_orden=$_REQUEST['id_orden'];
	$id_orden_detalle=$_REQUEST['id_orden_detalle'];
	$qty= $_REQUEST['qty'];
	$hora=date("H:i:s");
	$fecha=date('Y-m-d');

	$sql="SELECT p.*
  FROM producto AS p
 	JOIN categoria c ON p.id_categoria=c.id_categoria
  WHERE  p.inactivo=0
  AND p.id_producto='$id_producto'";

	$sql="SELECT p.descripcion,p.precio,od.id_orden,od.id_orden_detalle
	FROM producto AS p
	JOIN categoria c ON p.id_categoria=c.id_categoria
	JOIN orden_detalle od ON p.id_producto=od.id_producto
	WHERE  od.id_orden='$id_orden'
	AND od.id_orden_detalle='$id_orden_detalle'
	AND p.inactivo=0
	AND p.id_producto='$id_producto'
	ORDER BY od.id_orden,od.id_orden_detalle
	";
  $result=_query($sql);
  $count=_num_rows($result);
  if($count>0){
  	$row=_fetch_array($result);
		$id_orden_detalle=$row['id_orden_detalle'];
  	$descrip=trim(substr($row['descripcion'],0,50))." $".$row['precio'];
  	$desc=divtextlin($descrip, 25,2);
	  $ln=2-count($desc);
  	for($j=0;$j<$ln;$j++){
 	 		array_push($desc,'&nbsp;');
  	}
  $descripcion="";
  foreach($desc as $d1){
 	 $descripcion.="<p>".$d1."</p>";
 	}
	$xdatos['id_orden']=$id_orden;
	$xdatos['id_orden_detalle']=$id_orden_detalle;
  $xdatos['descripcion'] =$descripcion;
  $xdatos['precio']= $row['precio'];
  $xdatos['extra']= 0;
  $xdatos['lleva_extra']= 0;

 }
 else{
	 $xdatos['descripcion'] ='';
	 $xdatos['precio']= '';
	 $xdatos['extra']= 0;
	 $xdatos['lleva_extra']= 0;
 }
 //Verificar si existe orden

 echo json_encode($xdatos); //Return the JSON Array
}
function agregar_prod(){
	$id_producto = $_REQUEST['id_producto'];
	$id_orden = $_REQUEST['id_orden'];
	$id_mesa = $_REQUEST['id_mesa'];
	$id_mesero = $_REQUEST['id_mesero'];
	$id_orden_detalle =-1;
	if(isset($_REQUEST['id_orden_detalle'])){
			$id_orden_detalle = $_REQUEST['id_orden_detalle'];
	}
	$qty= $_REQUEST['qty'];
	$hora=date("H:i:s");
	$fecha=date('Y-m-d');
  //Verificar si existe orden, sino Crearla !!
	$sql2="SELECT * FROM orden WHERE id_orden='$id_orden'
	AND  fecha='$fecha' AND  finalizada=0
  ";
	$result2=_query($sql2);
	$nrows2=_num_rows($result2);
	//SELECT id_orden, id_mesa, id_mesero, fecha, hora_inicio, hora_fin, finalizada FROM orden WHERE 1
	/*
	$table_ord= 'orden';
	$fd_ord = array(
			 'fecha' => $fecha,
			 'hora_inicio' => $hora,
			 'finalizada' => 0,
			 'id_mesero'=>$id_mesero,
			 'id_mesa'=>$id_mesa,
	);
	*/
	if($nrows2>0){
	actualiza_orden_detalle($id_orden,$id_producto,$qty,$id_orden_detalle);
  }
	/*
	if ($nrows2==0){
		 $insert_ord = _insert($table_ord,$fd_ord );
		 $id_orden= _insert_id();
		 //mesa en estado ocupado !!!
		 $data_mesa= array(
		 'ocupado' => 1,
		 'fecha_ultima'=>$fecha,
		 );
		 $table="mesa";
		 $wc=" WHERE id='$id_mesa'";
		 $actualiza_mesa = _update($table,$data_mesa,$wc );
		 actualiza_orden_detalle($id_mesero,$id_mesa,$id_orden,$id_producto,$qty,$id_orden_detalle);
	 }
	 if($nrows2>0){
	 		$row2=_fetch_array($result2);
	 		$mesero_asignado=$row2['id_mesero'];
			$finalizada= $row2['finalizada'];
			$id_orden= $row2['id_orden'];
			if ($id_mesero<>$mesero_asignado){
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Otro Mesero tiene Asignada esa mesa !';
				 echo json_encode($xdatos); //Return the JSON Array

			}
			if ($id_mesero==$mesero_asignado){
				//mesa en estado ocupado !!!
				$data_mesa= array(
				'ocupado' => 1,
				'fecha_ultima'=>$fecha,
				);
				$table="mesa";
				$wc=" WHERE id='$id_mesa'";
				$actualiza_mesa = _update($table,$data_mesa,$wc );
				 actualiza_orden_detalle($id_mesero,$id_mesa,$id_orden,$id_producto,$qty,$id_orden_detalle);
				 	$xdatos['mostrar']='NO';
				 $xdatos['id_orden']=$id_orden;
				 $xdatos['typeinfo']='Info';
				 $xdatos['msg']='Actualizando Orden de mesa !';
				//echo json_encode($xdatos); //Return the JSON Array

			}
	 }
	 */
}
function actualiza_orden_detalle($id_orden,$id_producto,$qty,$id_orden_detalle){
	$sql_item="SELECT *
	FROM orden_detalle
	WHERE id_orden='$id_orden'
	AND id_producto='$id_producto'
	";
	$result=_query($sql_item);
	$count=_num_rows($result);
	$table_ordd='orden_detalle';



	if ($count==0 || $qty>0){
		$sql_item_p="SELECT precio
		FROM producto
		WHERE id_producto='$id_producto'
		";
		$result_p=_query($sql_item_p);
		$precio=_fetch_result($result_p);

			 $fd_ord = array(
			 'id_orden' => $id_orden,
			 'id_producto' => $id_producto,
			 'cantidad' =>$qty,
			 'precio'=>$precio,
			 );

		 	$insert_ord = _insert($table_ordd,$fd_ord );
			$id_orden_detalle =  _insert_id();
			$xdatos['mostrar']='SI';
			$xdatos['id_orden']=$id_orden;
		  $xdatos['id_orden_detalle']=$id_orden_detalle;
			echo json_encode($xdatos); //Return the JSON Array
	 }
	if	($qty==0){
					$wc="	WHERE id_orden='$id_orden'
						AND id_producto='$id_producto'
						AND id_orden_detalle='$id_orden_detalle'
						ORDER BY id_orden_detalle
						LIMIT 1
						";
					$insert_ord = _delete($table_ordd,$wc );
						$xdatos['mostrar']='NO';
					$xdatos['id_orden']=$id_orden;
					$xdatos['typeinfo']='Info';
	 			 $xdatos['msg']='eliminando Item de Orden !';
	 				echo json_encode($xdatos); //Return the JSON Array
	}
	$sql_tot_ord="SELECT ROUND(SUM(cantidad*precio),2)
	FROM orden_detalle
	WHERE id_orden='$id_orden'
	";
	$result_tot_ord=_query($sql_tot_ord);
	$total_orden=_fetch_result($result_tot_ord);
	$table="orden";
	$wc=" WHERE id_orden='$id_orden'";
	$data= array(
	'total'=>$total_orden,
	);
	$actualizar = _update($table,$data,$wc );
}
/*
function revisar_mesaas(){
	$fecha=date('Y-m-d');
	$sql="SELECT * FROM mesa where fecha_ultima!='$fecha'
	";
	 $result= _query($sql);
 $count=_num_rows($result);
 $table='mesa';
 $wc="where fecha_ultima!='$fecha'";
 $fd= array(
 'ocupado' => 0,
 );
	 $update = _update($table,$fd,$wc );
}
*/
function mostrar_encab_orden(){
	$id_orden = $_REQUEST['id_orden'];
	$fecha=date('Y-m-d');
	$sql1="SELECT orden.id_cliente,orden.id_tipo_orden
	FROM orden
	WHERE orden.id_orden='$id_orden'
	AND orden.fecha='$fecha'
	AND orden.finalizada=0
	";
	$result1=_query($sql1);
	$count1=_num_rows($result1);

 if($count1>0){
 	for ($b=0;$b<$count1;$b++) {
		$row1=_fetch_array($result1);
		$id_tipo_orden=$row1['id_tipo_orden'];

		$q_to="SELECT descripcion FROM tipo_orden WHERE id_tipo_orden='$id_tipo_orden'";
		$r_to=_query($q_to);
		$tipo_orden=_fetch_result($r_to);
		$id_cliente=$row1['id_cliente'];
		$q_c="SELECT nombre FROM cliente WHERE id_cliente='$id_cliente'";
		$r_c=_query($q_c);
		$cliente=_fetch_result($r_c);
		?>
		<div class="col-md-12 cell2 mostrar_orden_act">Orden :<?php echo $tipo_orden ?> #:<?php echo $id_orden ?></div>
		<div class="col-md-12 cell2 cliente_act">Cliente:<?php echo $cliente ?></div>
		<?php

	}
 }
}
function mostrar_orden(){
	$id_orden = $_REQUEST['id_orden'];
	$fecha=date('Y-m-d');
	$sql1="SELECT  orden_detalle.id_producto,producto.precio, producto.descripcion,orden.id_tipo_orden,
	orden.id_mesero, orden_detalle.id_orden, orden_detalle.id_orden_detalle, orden_detalle.cantidad
	FROM orden
  JOIN orden_detalle ON orden.id_orden=orden_detalle.id_orden
	JOIN producto ON orden_detalle.id_producto=producto.id_producto
	WHERE orden.id_orden='$id_orden'
  AND orden.fecha='$fecha'
  AND orden.finalizada=0
	ORDER BY orden_detalle.id_orden,orden_detalle.id_orden_detalle
	";
	$result1=_query($sql1);
  $count1=_num_rows($result1);

 if($count1>0){
	 $array_prod = array();
	 $n=0;
  for ($b=0;$b<$count1;$b++) {
      $row1=_fetch_array($result1);
			$id_orden=$row1['id_orden'];
			$id_orden_detalle=$row1['id_orden_detalle'];
			$id_producto=$row1['id_producto'];
			$descripcion =$row1['descripcion'];
			$cantidad =$row1['cantidad'];
			$precio =$row1['precio'];
			$id_mesero =$row1['id_mesero'];
			$subtotal =round($cantidad*$precio,2);
			$n++;
			$id_tipo_orden=$row1['id_tipo_orden'];
			$q_to="SELECT descripcion FROM tipo_orden WHERE id_tipo_orden='$id_tipo_orden'";
			$r_to=_query($q_to);
			$tipo_orden=_fetch_result($r_to);
			$array_prod[] = array(
	 		'id_producto' => $id_producto,
	 		'cantidad' =>  $cantidad,
			'id_orden' =>  $id_orden,
			'id_orden_detalle' =>  $id_orden_detalle,
			'tipo_orden' => $tipo_orden,
			'n' =>  $n,
			);
			$input_prod="<input type='hidden'  class='producto_base' name='id_producto_base'  id='id_producto_base' value='$id_producto' />";
			$input_precio="<input type='hidden' id='precio_origen' value='$precio' /> ";
			$input_id_ordet="<input type='hidden' id='id_orden_detalle' value='$id_orden_detalle' /> ";
			$btnDelete='<button type="button" id="btndelprod" class="btn-sm btn-danger">	<i class="icon-trash"></i> </button>';

			?>
			<tr>
				<td class='texto_med' style='width:8%;  text-align: center;'><?php echo $id_producto; ?></td>
				<td class='td_green texto_peq' style='width:36%'><?php echo $input_precio.$input_prod.$input_id_ordet.$descripcion; ?></td>
				<td class='texto_med col1 tdCant' id='cantidad' style='width:20%;'><?php echo $cantidad; ?></td>
				<td class='texto_med col1 td1' id='subtotal' style='width: 20%'><?php echo $subtotal; ?></td>
				<td class='Delete col8 td1'   style='width: 16%;padding-top:5px;'  ><?php echo $btnDelete;?></td>
				</tr>
			<?php
		}
		$n++;
		$array_prod[] = array(
	 'id_producto' => -1,
	 'cantidad' =>  -1,
	 'id_orden' =>  $id_orden,
	 'id_orden_detalle' => -1,
	 'tipo_orden' => $tipo_orden,
	 'n' =>  $n,
	 );
  //fin prueba
	}else {
		$array_prod['id_orden'] =-1;
	}
	//echo json_encode($array_prod);
}
function mostrar_orden2(){
	$id_orden = $_REQUEST['id_orden'];
	$fecha=date('Y-m-d');
	$sql1="SELECT  orden_detalle.id_producto,producto.precio, producto.descripcion,orden.id_tipo_orden,
	orden.id_mesero, orden_detalle.id_orden, orden_detalle.id_orden_detalle, orden_detalle.cantidad
	FROM orden
  JOIN orden_detalle ON orden.id_orden=orden_detalle.id_orden
	JOIN producto ON orden_detalle.id_producto=producto.id_producto
	WHERE orden.id_orden='$id_orden'
  AND orden.fecha='$fecha'
  AND orden.finalizada=0
	ORDER BY orden_detalle.id_orden,orden_detalle.id_orden_detalle
	";
	$result1=_query($sql1);
  $count1=_num_rows($result1);

 if($count1>0){
	 $array_prod = array();
	 $n=0;
  for ($b=0;$b<$count1;$b++) {
      $row1=_fetch_array($result1);
			$id_orden=$row1['id_orden'];
			$id_orden_detalle=$row1['id_orden_detalle'];
			$id_producto=$row1['id_producto'];
			$descripcion =$row1['descripcion'];
			$cantidad =$row1['cantidad'];
			$precio =$row1['precio'];
			$id_mesero =$row1['id_mesero'];
			$subtotal =round($cantidad*$precio,2);
			$n++;
			$id_tipo_orden=$row1['id_tipo_orden'];
			$q_to="SELECT descripcion FROM tipo_orden WHERE id_tipo_orden='$id_tipo_orden'";
			$r_to=_query($q_to);
			$tipo_orden=_fetch_result($r_to);
			$array_prod[] = array(
	 		'id_producto' => $id_producto,
	 		'cantidad' =>  $cantidad,
			'id_orden' =>  $id_orden,
			'id_orden_detalle' =>  $id_orden_detalle,
			'tipo_orden' => $tipo_orden,
			'n' =>  $n,
			);
		}
		$n++;
		$array_prod[] = array(
	 'id_producto' => -1,
	 'cantidad' =>  -1,
	 'id_orden' =>  $id_orden,
	 'id_orden_detalle' => -1,
	 'tipo_orden' => $tipo_orden,
	 'n' =>  $n,
	 );
  //fin prueba
	}else {
		$array_prod['id_orden'] =-1;
	}
	echo json_encode($array_prod);
}
function cambiar_orden(){
	$id_mesa_origen = $_REQUEST['id_mesa_origen'];
	$id_mesa_destino = $_REQUEST['id_mesa_destino'];
	$fecha=date('Y-m-d');

	$table='mesa';
	//desocupar mesa origen
  $wc="where fecha_ultima='$fecha' and id='$id_mesa_origen'";
  $fd= array(
  'ocupado' => 0,
  );
 	 $update0 = _update($table,$fd,$wc );
	 //ocupar mesa destino
   $wc1="where  id='$id_mesa_destino'";
   $fd1= array(
   'ocupado' => 1,
	 'fecha_ultima'=>$fecha,
   );
   $update1 = _update($table,$fd1,$wc1 );
	 //actualizar orden y id_orden_detalle
	 $table='orden';
	 $ord= array(
   'id_mesa' => $id_mesa_destino,
   );
	 $wc2="where fecha='$fecha' and id_mesa='$id_mesa_origen'";
	 $update2 = _update($table,$ord,$wc2 );
	 $xdatos['typeinfo']='Info';
	$xdatos['msg']='Cambiando Mesa !';
	 echo json_encode($xdatos); //Return the JSON Array
}

function mostrar_modal(){
	//cliente
	$array1= array();
	$sql1="SELECT * FROM cliente";
	$result1=_query($sql1);
	$count1=_num_rows($result1);
	for ($j=0;$j<$count1;$j++) {
	   $row1=_fetch_array($result1);
	   $id=$row1['id_cliente'];
	   $nombre=$row1['nombre'];
	   $array1[$id] = $nombre;
	}
	  //array de tipo_pagos
	$array4= array(-1=>"Seleccione Tipo Orden");
  $sql4  = "SELECT * FROM tipo_orden WHERE inactivo=0";
	$result4=_query($sql4);
	$count4=_num_rows($result4);
	for ($a=0;$a<$count4;$a++) {
	  $row4=_fetch_array($result4);
	  $id4=$row4['id_tipo_orden'];
	  $description4=trim($row4['descripcion']);
	  $array4[$id4] = $description4;
	}

	?>
	<!-- Modal -->
	<div class="modal fade" id="modalTipoOrden"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content modal-md">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Pago y Cambio</h4>
				</div>
				<div class="modal-body">
					<input type='hidden' name='id_factura' id='id_factura' value=''>
					<input type='hidden' name='num_interno' id='num_interno' value=''>
					<input type='hidden' name='num_impresion' id='num_impresion' value=''>
					<input type="hidden" id="facturado" name="facturado" value='' />

					<div class="wrapper wrapper-content  animated fadeInRight">
						<div class="row">
									<div class="col-md-3  cell2"> Tipo Ord.</div>
									<div class="col-md-9 cell2">
										<?php
												$nombre_select="tipo_orden";
												$style='';
												$select0=crear_select($nombre_select, $array4,"-1", $style);
												echo $select0;
										?>
									</div>
						</div>
						<div class="row">
							<div class="col-md-3  cell2">Cliente:</div>
							<div class="col-md-9 cell2">
								<?php
								$nombre_select="id_cliente";
								$style='';
								$select0=crear_select2($nombre_select, $array1,"-1", $style);
								echo $select0;
								?>
							</div>
						</div>
						<div class="row" id='nombre_cliente'>
							<div class="col-md-3">
								<div class="form-group">
									<label><strong><h5 class='text-navy'>Nombre: </h5></strong></label>
								</div>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<input type="text" id='nombreape' name='nombreape' value='' class="form-control">
								</div>
							</div>
						</div>
							<div id='datos_fact_cliente'>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label><strong>Direccion : </strong></label>
										</div>
									</div>
								<div class="col-md-9">
									<div class="form-group">
										<input type="text" id='direccion' name='direccion' value='' class="form-control" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label>Telefono</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input type='text' placeholder='Telefono' class='form-control' id='telefono' name='telefono' value='' />
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group" id='mensajes'></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="btnProcesaOrden">Guardar</button>
					<button type="button" class="btn btn-warning" id="btnEsc">Salir</button>
				</div>
			</div>
		</div>
	</div>
	<?php
}
function  mostrar_modal2()
{

  $array0=ordenes_fecha();
  $count0=count($array0);
  $rows = array_chunk($array0,4);
?>
<!--div class="modal fade" id="modalCambioOrden" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
				<div class="modal-content">

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Seleccionar Orden</h4>
</div>
<div class="modal-body"-->

		<!--div class="row" id="rowOrdenes"-->
			<section>
				<div class="table-responsive m-t">
        <?php
          foreach ($rows as $row) {
       ?>
       <div class="row gutter">
         <?php
           foreach ($row as $value) {
               $mostrar_valor="";
           list($id,$hora,$total,$tipo_orden)=explode("|",$value);
             $btnclass="btn-info";
            $mostrar_valor.=$id."<br>";
            $mostrar_valor.=$hora."<br>";
            $mostrar_valor.=$tipo_orden."<br>";
         ?>
         <div class="div_mesa target col-md-3">
           <button type="button" id="btnOrden" class="btn <?php echo $btnclass;?> btn-block btn-huge btnOrdenesCambio"><?php echo $mostrar_valor;?></button>
          <input type='hidden'  class='ordenes' name='id_ordenes'  id='id_ordenes' value='<?php echo $id;?>' />
         </div>
       <?php 	} ?>
       </div>
         <?php } ?>
         <input type='hidden'  class='mesa_selected' name='mesa_selectt'  id='mesa_selectt' value='<?php echo $id;?>' />
				</div>
			</section>
		<!--/div-->
<!--/div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
</div>

				</div>

		</div>
</div-->
<?php
}

function datos_cliente(){
  $id_cliente = $_REQUEST['id_cliente'];
  $sql="SELECT * from cliente WHERE id_cliente='$id_cliente'";
  $result = _query($sql);
  $numrows = _num_rows($result);

  if ($numrows>0){
  	while ($row = _fetch_array($result)) {
      $array['id_cliente'] =$row['id_cliente'];
    	$array['nombre'] =$row['nombre'];
      $array['direccion'] =$row['direccion'];
    	$array['telefono'] =$row['telefono1'];
      $array['nrc'] =$row['nrc'];
      $array['dui'] =$row['dui'];
  	}
  }
  echo json_encode ($array); //Return the JSON Array
}
function crear_orden(){
		$tipo_orden = $_POST['tipo_orden'];
		$id_cliente = $_POST['id_cliente'];
		$nombreape = $_POST['nombreape'];
		if (isset($_POST['direccion'])) {
			$direccion= $_POST['direccion'];
			$dir="'direccion' => $direccion,";
		}
		else{
			$dir="'direccion' => '',";
		}
		if (isset($_POST['telefono'])) {
			$telefono = $_POST['telefono'];
			$tel="'telefono1' => $telefono,";
		}
		else {
				$tel="'telefono1' => '',";
		}
		$hora_inicio=date("H:i:s");
		$fecha=date('Y-m-d');
    //validar que el cliente exista sino agregarlo, validando el nombre!!!
		$sql_cte="SELECT * FROM cliente WHERE id_cliente='$id_cliente' AND nombre='$nombreape'";
		$res_cte=_query($sql_cte);
		$nrow_cte=_num_rows($res_cte);
		$table= 'cliente';
		$data = array(
				 'nombre' => $nombreape,
				 $dir,
				 $tel,
		);
		if($nrow_cte==0){
			$insert_cte = _insert($table,$data );
			$new_id= _insert_id();
		}
		else{
			$wc="WHERE id_cliente='$id_cliente'";
			$insert_cte = _update($table,$data,$wc);
		}
		$table0= 'orden';
		$data0 = array(
				 'fecha' => $fecha,
				 'hora_inicio' => $hora_inicio,
				 'finalizada' => 0,
				 'id_mesero'=>-1,
				 'id_mesa'=>-1,
				 'id_cliente'=>$id_cliente,
				 'id_tipo_orden'=>$tipo_orden,
		);

		$insert = _insert($table0,$data0 );
	  $id_orden= _insert_id();

		//hacer un select count de finalizado por orden_detalle y si es cero finalizar la orden completa poenr en orden finalizada=1
		if ($insert) {
			$xdatos ['typeinfo'] = 'Success';
			$xdatos ['msg'] = 'Orden Creada!';
			$xdatos ['id_orden']=$id_orden;
		} else {
			$xdatos ['typeinfo'] = 'Error';
			$xdatos ['msg'] = 'Orden No Creada! ';
		}
		echo json_encode ( $xdatos );

}
function ordenes_fecha(){
	//crear array ordenes
	$array0= array();
	$fecha_actual=date('Y-m-d');
 // ubicacion
$sql0="SELECT * FROM orden WHERE fecha='$fecha_actual' AND finalizada=0 order by id_orden ASC";
	//ordenes para facturas
	$result0=_query($sql0);
	$count0=_num_rows($result0);
	for ($a=1;$a<=$count0;$a++){
		$row0=_fetch_array($result0);
		$id_tipo_orden=$row0['id_tipo_orden'];
		$q_to="SELECT descripcion FROM tipo_orden WHERE id_tipo_orden='$id_tipo_orden'";
		$r_to=_query($q_to);
		$tipo_orden=_fetch_result($r_to);
	//	$array0[$a] =$row0['id_orden']."|".$row0['hora_inicio']."|".$row0['total']."|".$row0['anulada']."|".$tipo_orden;
  	$array0[$a] =$row0['id_orden']."|".$row0['hora_inicio']."|".$row0['total']."|".$tipo_orden;
	}
	return $array0;
}
//functions to load
if(!isset($_REQUEST['process'])){
	initial();
}

if (isset($_REQUEST['process'])) {
	switch ($_REQUEST['process']) {

	case 'cargar_cat':
		cargar_cat();
		break;
	case 'consultar_prod':
		consultar_prod();
		break;
	case 'agregar_prod':
		agregar_prod();
		break;
 	case 'mostrar_prodcat':
		mostrar_prodcat();
		break;
	case 'mostrar_encab_orden':
		mostrar_encab_orden();
		break;
		case 'mostrar_orden':
			mostrar_orden();
			break;
	case 'cambiar_orden':
		cambiar_orden();
		break;
	case 'cambia_cliente':
		datos_cliente();
		break;
	case 'crear_orden':
	crear_orden();
	break;
	case 'mostrar_modal2':
	mostrar_modal2();
	break;
	}
}
 ?>
