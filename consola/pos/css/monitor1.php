<?php
//include ("_core.php");
require "_conexion.php";

function initial()
{
 $title='MONITOR ORDENES ICE ROLLS';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title><?php echo $title;?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link href='css/tablemon.css' media='screen' rel='stylesheet' />
        <link href="css/toastr/toastr.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    </head>
    <body>
      <!-- Header starts -->
      <header>  <h4 style="padding-top:10px;color:white;"><?php echo $title;?></h4>
      </header>
      <!-- Header ends -->
        <div class="demo">

            <div class="container" >

                  <div class="row">
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                      <div class="row" id='item_sirviendo'></div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

                      <div class="card">
         <div class="card-body">
           <h5 class="card-title">Finalizar Item</h5>
           <h6 class="card-subtitle mb-2 text-muted">  # Empleado</h6>
           <p class="card-text">  <input type="text" class="form-control integer_positive" placeholder="digitar Id Empleado+ Enter" id="id_empleado" name="id_empleado" />
           </p>
         </div>
         </div>
           <div class="card card-ordenes">
          <div class="card-body">
            <h6 class="card-title">Ordenes Pendientes!</h6>


                          <!--table class="table table2 table-fixed table-striped " id="table_orden"-->
                          <div class="table-responsive div-fix" >
                          <table class="table table-striped table-sm" id="table_orden">
                            <thead class='thead1'>
                              <tr class='tr1'>
                                <th class="text-info" ># </th>
                                <th class="text-info">Total</th>
                                <th class="text-info">Hora</th>
                              </tr>
                            </thead>
                            <tbody class='tbody1' id="orden_pendiente"></tbody>
                          </table>
                           </div>
                     </div>
                      </div>

                    </div>
                  </div>

            </div>
            <footer>
          <span class='foottext1'>    © OpenSolutions </span><span class='foottext2'>    2018	</span>
            </footer>
        </div>




        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
          <script src="js/toastr/toastr.min.js"></script>
        <script src="js/funciones/base.js"></script>
        <script src='js/funciones/monitor1.js'></script>
    </body>

</html>
<?php
}

function traer_ordenes(){
	$array1= array();
  $fecha=date('Y-m-d');
  $sql1="SELECT  *
	FROM factura AS f
	WHERE  f.fecha='$fecha'
	AND f.finalizada=0
	";
	$sql1="SELECT  f.id_factura,f.total, f.hora_inicio
	 FROM factura f
	 JOIN factura_detalle fd on fd.id_factura=f.id_factura
	 JOIN producto p  ON fd.id_producto=p.id_producto
	 WHERE  f.fecha='$fecha'
	 AND fd.asignado=0
	 AND fd.finalizado=0
	 AND fd.id_prod_superior=-1
	 AND p.es_roll=1
	 GROUP BY f.id_factura ORDER BY  f.id_factura";
  $result1=_query($sql1);
  $count1=_num_rows($result1);
  for ($b=0;$b<$count1;$b++) {
      $row1=_fetch_array($result1);
      $array1[$b] =$row1['id_factura']."|".$row1['total']."|".$row1['hora_inicio'];
  }
  return $array1;
}
function mostrar_orden_pendiente(){
	$array1= traer_ordenes();
	foreach ($array1 as $row1){
		list($id_orden, $total, $hora)=explode("|", $row1); ?>
		<tr>
			<td><?php echo $id_orden; ?></td>
			<td>$ <?php echo $total; ?></td>
			<td><?php echo $hora; ?></td>
		</tr>
		<?php
  }
}

function traer_item_sirviendo()
{
    $array1= array();
    $fecha=date('Y-m-d');
    //Obtener informacion de tabla Factura_detalle y producto que  es ice roll
    $sql1="SELECT  p.id_producto, p.descripcion,f.mesa,
		fd.*,e.nombre,e.color
		FROM factura f
    JOIN factura_detalle fd on fd.id_factura=f.id_factura
		JOIN producto p ON fd.id_producto=p.id_producto
    JOIN profesor e ON e.id_empleado=fd.id_empleado
		WHERE  f.fecha='$fecha'
		AND fd.asignado=1
		AND fd.finalizado=0
		AND fd.id_prod_superior=-1
		AND p.es_roll=1
		ORDER BY  f.id_factura,fd.fila_orden
		";
    $result1=_query($sql1);
    $count1=_num_rows($result1);
		$n=0;
    for ($b=0;$b<$count1;$b++) {
        $row1=_fetch_array($result1);
        $id_producto=$row1['id_producto'];
				$id_factura = $row1['id_factura'];
        $id_prod_sup=$row1['id_prod_superior'];
        $fila_orden=$row1['fila_orden'];
        $descripcion =$row1['descripcion'];
        $cantidad =$row1['cantidad'];
        $precio =$row1['precio'];
        $color =$row1['color'];
        $mesa =$row1['mesa'];
        $sql_mesa="SELECT * FROM mesa where id='$mesa'";
        $result_mesa=_query($sql_mesa);
        $row_mesa=_fetch_array($result_mesa);
        $desc_mesa=$row_mesa['descripcion'];
        $subt =$row1['subtotal'];
        $id_empleado =$row1['id_empleado']."-".$row1['nombre'];
        //linea a linea
        $precio_unit=sprintf("%.2f", $precio);
        $subtotal=sprintf("%.2f", $subt);
				$item_array="";

          $descripcion2="";
          $descripcion_top="";
				$n+=1;
        $sql2="SELECT  p.id_producto, p.descripcion,fd.*,
        c.extra
        FROM factura f
        JOIN factura_detalle fd on fd.id_factura=f.id_factura
        JOIN producto p ON fd.id_producto=p.id_producto
        JOIN categoria c ON p.id_categoria=c.id_categoria
        WHERE  fd.fecha='$fecha'
        AND fd.id_factura='$id_factura'
        AND fd.finalizado=0
        AND  fd.id_prod_superior='$id_producto'
        AND fd.fila_base='$fila_orden'
        AND p.es_roll=0
        AND c.extra=1
        ORDER BY  f.id_factura,fd.fila_orden
        ";
        $r2=_query($sql2);
        $nr2=_num_rows($r2);
				$num=0;
        $toppings="";
        for ($i=0;$i<$nr2;$i++) {
            $row2=_fetch_array($r2);
						$id_factura2= $row2['id_factura'];
            $id_producto2=$row2['id_producto'];
            $descripcion2="  ** ".$row2['descripcion'];

            $cantidad =$row2['cantidad'];
						$num++;
            $toppings.="<li>".$descripcion2." </li> ";
        }

        $item_array=$id_factura."|".$id_producto."|".$descripcion."|".$id_empleado."|".$id_prod_sup."|".$desc_mesa."|".$color."|".$toppings."|".$num;
        array_push($array1, $item_array);
    }
    return $array1;
}

function mostrar_item_sirviendo()
{
  $colorbg0="#00695c";
  $colorbg1="#ae003d";
  $colorbg2="#005c99";
  $d_path="M0.732,193.75c0,0,29.706,28.572,43.736-4.512c12.976-30.599,37.005-27.589,44.983-7.061
      c8.09,20.815,22.83,41.034,48.324,27.781c21.875-11.372,46.499,4.066,49.155,5.591c6.242,3.586,28.729,7.626,38.246-14.243
      s27.202-37.185,46.917-8.488c19.715,28.693,38.687,13.116,46.502,4.832c7.817-8.282,27.386-15.906,41.405,6.294V0H0.48
      L0.732,193.75z";
      $array1= traer_item_sirviendo();
      if(count($array1)==0){
        ?>
        <div class="example1">
          <h3>No hay Ordenes pendientes !</h3>
</div>
        <?php
        //  echo "<h3>No hay ordenes pendientes !</h3>";
      }
      else {


      $rows1 = array_chunk($array1,3);

    	foreach ($rows1 as $row1) {
      foreach ($row1 as $val1) {
        list($id_factura, $id_producto, $descripcion, $id_empleado, $id_prod_sup,$mesa,$colorbg,$toppings,$ntop)=explode("|", $val1);
        if($colorbg==""){
          $colorbg="#212121";
        }
        $desc=divtextlin($descripcion, 10,2);
        $ln=3-count($desc);
        for($j=0;$j<$ln;$j++){
          array_push($desc,'&nbsp;');
        }
          $toptitle="<li>lineas desc:".$ln."</li>";
        $color_style="background:".$colorbg.";";
        $title_style='color:'.$colorbg.";";
        $cuantos=3-$ntop;
          if($ntop>0){
              $toptitle="<li><b>TOPPINGS</b></li>";
          }else{
            $toptitle="<li><b>--SIN TOPPINGS--</b></li>";
          }
        if($ntop<3){
          for($i=0;$i<$cuantos;$i++){
            $toppings.="<li>&nbsp;</li>";
          }
        }
        ?>
      <div class="col-md-4 col-sm-4">
          <div class="pricingTable">
              <svg x="0" y="0" viewBox="0 0 360 220">
                  <g>
                    <path fill="<?php echo $colorbg;?>" d="<?php echo $d_path;?>"></path>
                  </g>
                  <text transform="matrix(1 0 0 1 39.7256 116.2686)" fill="#fff" font-size="35" font-weight="bold" >  <?php echo $id_empleado;?></text>
              </svg>

              <div class="pricing-content">
                  <h3 class="title" style="<?php echo $title_style;?>">
                    <?php

                    foreach($desc as $d1){
                		echo $d1."<br>";
	                   }
                    ?>
                  </h3>
                  <ul class="pricing-content">
                      <li>ORDEN #<b><?php echo $id_factura."-" .$id_producto;?></b></li>
                      <?php echo $toptitle;?>
                      <?php echo $toppings;?>
                  </ul>
                  <div class="pricingTable-signup" style='<?php echo $color_style;?>'><?php echo $mesa;?></div>
              </div>
          </div>
      </div>
      <?php
      }
    }
    }

    ?>
    <!--/div>--
    <?php
}
function finalizar_item_orden(){
  date_default_timezone_set('America/El_Salvador');
	$id_empleado=$_POST['id_empleado'];
	$fecha=date('Y-m-d');
	$hora=date("H:i:s");
	_begin();
	// 1) finalizar primero el item en factura_detalle, si el profesor tiene asignacion
   $t_fd='factura_detalle';
	 $where_clause=" WHERE id_empleado='$id_empleado' and fecha='$fecha'";
	 $form_data= array(
		 'finalizado' => 1,
		 'hora_fin_prepara'=>$hora,
	 );

	 $actualizar = _update($t_fd,$form_data, $where_clause );
	 $sql0="SELECT * FROM  empleado_asigna_dia
	 WHERE id_empleado='$id_empleado' and fecha='$fecha' ";
	 $r0  =_query($sql0);
	 $nr0 =_num_rows($r0);
	 $row0=_fetch_array($r0);
	 $id_factura=$row0['id_factura'];
	 $total_servidos=$row0['total_servidos'];
	 $total_servidos+=1;
	 //empleado_asigna_dia quitarle la asignacion para q este disponible para otra orden
	 $t_e='empleado_asigna_dia';
	 $where_clause0=" WHERE id_empleado='$id_empleado' and fecha='$fecha'";
	 $form_data0= array(
 		 'sirviendo' => 0,
		 'total_servidos'=>$total_servidos
 		);
	 $actualizar2 = _update($t_e,$form_data0, $where_clause0);
	 // 3) si la orden esta completada asignar nueva orden al profesor de otra factura, sino seguira esperando
	 $sql1="SELECT * FROM  factura_detalle
	 WHERE fecha='$fecha' and id_factura='$id_factura' and finalizado=0";
	 $r1  =_query($sql1);
	 $nr1 =_num_rows($r1);
   if($nr1==0){
		 //verificar la proxima factura pendiente que tenga ice_rolls en la orden!!!!
		 $sql2="SELECT  f.id_factura
 			FROM factura f
     	JOIN factura_detalle fd on fd.id_factura=f.id_factura
 			JOIN producto  p ON fd.id_producto=p.id_producto
 			WHERE  f.fecha='$fecha'
 			AND fd.asignado=0
 			AND fd.finalizado=0
 			AND fd.id_prod_superior=-1
 			AND p.es_roll=1
 			GROUP BY f.id_factura ORDER BY  f.id_factura LIMIT 1
 		";
     $r2=_query($sql2);
     $nr2=_num_rows($r2);
		 if($nr2>0){
			 $row2=_fetch_array($r2);
			 $id_factura2=$row2['id_factura'];
			 //Reasignar items a empleados
			asignacion($id_factura2);
		 }

	 }
	 if ($actualizar && $actualizar2 )
	 {
			 _commit(); // transaction is committed
			 $xdatos['typeinfo']='Success';
			 $xdatos['msg']='Información Actualizada !';

		 }
		 else{
			 _rollback(); // transaction not committed
			 $xdatos['typeinfo']='Error';
			 $xdatos['msg']='Información No Actualizada !';
	 }
		 echo json_encode($xdatos);
}
function asigna_init(){
	$fecha=date('Y-m-d');
	$sql2="SELECT  f.id_factura
	 FROM factura f
	 JOIN factura_detalle fd on fd.id_factura=f.id_factura
	 JOIN producto  p ON fd.id_producto=p.id_producto
	 WHERE  f.fecha='$fecha'
	 AND fd.asignado=0
	 AND fd.finalizado=0
	 AND fd.id_prod_superior=-1
	 AND p.es_roll=1
	 GROUP BY f.id_factura ORDER BY  f.id_factura LIMIT 1
 ";
	$r2=_query($sql2);
	$nr2=_num_rows($r2);
	if($nr2>0){
		$row2=_fetch_array($r2);
		$id_factura2=$row2['id_factura'];
		//Reasignar items a empleados
		asignacion($id_factura2);
	}
}

//functions to load
if (!isset($_REQUEST['process'])) {
    initial();
}
//else {
if (isset($_REQUEST['process'])) {
    switch ($_REQUEST['process']) {
    case 'guardar_orden':
        insertar();
        break;
    case 'mostrar_item_sirviendo':
        mostrar_item_sirviendo();
        break;
    case 'mostrar_orden_pendiente':
        mostrar_orden_pendiente();
        break;
		case 'finalizar_item_orden':
		   finalizar_item_orden();
		   break;
    case 'asigna_init':
   	   asigna_init();
   	   break;
    }
}
 ?>
