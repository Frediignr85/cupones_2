<?php
    include("_core.php");
    if(isset($_SESSION['id_cliente']) || isset($_SESSION['id_dependiente'])){
        if (! isset ( $_REQUEST ['process'] ))
        {
            header("Location: ../index.php");
        } else
        {
            if (isset ( $_REQUEST ['process'] ))
            {
                
                switch ($_REQUEST ['process'])
                {
                    case 'ver_cantidad_carrito' :
                        cantidad_carrito();
                        break;
                    case 'nuevo_item' :
                        nuevo_item();
                        break;
                    case 'eliminar_item' :
                        eliminar_item();
                        break;
                    case 'actualizar_tabla' :
                        actualizar_tabla();
                        break;
                    case 'actualizar_item' :
                        actualizar_item();
                        break;
                    case 'realizar_pago':
                        realizar_pago();
                        break;
                    case 'verificar_codigo':
                        verificar_codigo();
                        break;
                    case 'canjear_codigo':
                        canjear_codigo();
                        break;
                }
            }
        }
    }
    else{
        header("Location: ../index.php");
    }


    /*   FUNCION PARA VER LA CANTIDAD DE PRODUCTOS QUE HAY EN EL CARRITO */
    function cantidad_carrito(){
        $id_cliente = $_SESSION['id_cliente'];
        $sql_contador = "SELECT SUM(carrito_detalle.cantidad) as 'cantidad' FROM carrito_detalle INNER JOIN carrito on carrito_detalle.id_carrito = carrito.id_carrito WHERE carrito.id_cliente = '$id_cliente' AND carrito_detalle.eliminado = 0";
        $query_contador = _query($sql_contador);
        $row_contador = _fetch_array($query_contador);
        $cantidad = $row_contador['cantidad'];
        if($cantidad == ""){
            $cantidad = 0;
        }
        $xdatos['cantidad'] = $cantidad;
        echo json_encode($xdatos);
    }

    function nuevo_item(){
        $id_oferta = $_POST['id_oferta'];
        $cantidad_ofertas = $_POST['cantidad_ofertas'];
        $id_cliente = $_SESSION['id_cliente'];
        if($cantidad_ofertas > 0 && is_numeric($cantidad_ofertas)){
            _begin();
            $datos_oferta = "SELECT ofertas.id_oferta,
            ofertas.precio_oferta,
            ofertas.cantidad_limite_cupones,
            ofertas.ilimitar,
            ofertas.id_empresa,
            empresas.nombre as 'nombre_empresa',
            rubros.id_rubro,
            rubros.nombre as 'nombre_rubro'
            FROM ofertas INNER JOIN empresas on empresas.id_empresa = ofertas.id_empresa
            INNER JOIN rubros on rubros.id_rubro = empresas.id_rubro
            WHERE ofertas.eliminado = 0 AND ofertas.estado_aprobado = 1 AND (ofertas.ilimitar = 1 OR ofertas.cantidad_limite_cupones > 0) AND ofertas.id_oferta = '".$id_oferta."'
            AND CURDATE() BETWEEN ofertas.fecha_inicio and ofertas.fecha_fin";
            $query_oferta = _query($datos_oferta);
            if(_num_rows($query_oferta) > 0){
                $row_ofertas = _fetch_array($query_oferta);
                $precio_oferta = $row_ofertas['precio_oferta'];
                $cantidad_limite_cupones = $row_ofertas['cantidad_limite_cupones'];
                $ilimitar = $row_ofertas['ilimitar'];
                $error = false;
                if($ilimitar == 0){
                    $cantidad_x = 0;
                    $sql_comprobar_x = "SELECT SUM(carrito_detalle.cantidad) as 'cantidad_carrito' FROM carrito_detalle INNER JOIN carrito on carrito.id_carrito = carrito_detalle.id_carrito WHERE carrito_detalle.activo = 1 AND carrito_detalle.eliminado = 0 AND carrito.activo = 1 AND carrito.eliminado = 0 AND carrito.id_cliente = '$id_cliente' AND carrito_detalle.id_oferta = '$id_oferta'";
                    $query_comprobar_x = _query($sql_comprobar_x);
                    $row_comprobar_x = _fetch_array($query_comprobar_x);
                    $cantidad_carrito = $row_comprobar_x['cantidad_carrito'];
                    if(($cantidad_ofertas +$cantidad_carrito) > $cantidad_limite_cupones){
                        $error = true;
                    }
                }
                if(!$error){
                    $error2 = false;
                    $comprobar_carrito = "SELECT * FROM carrito WHERE activo = '1' AND eliminado = '0' AND id_cliente = '".$id_cliente."'";
                    $query_comprobar_carrito = _query($comprobar_carrito);
                    if(_num_rows($query_comprobar_carrito) == 0){
                        $tabla_carrito = 'carrito';
                        $form_data = array(
                            'id_cliente' => $id_cliente,
                            'activo' => 1,
                            'eliminado' => 0
                        );
                        $insertar = _insert($tabla_carrito, $form_data);
                        if($insertar){
                            $id_carrito = _insert_id();
                        }else{
                            $error2 = true;
                        }
                    }
                    else{
                        $row_carrito = _fetch_array($query_comprobar_carrito);
                        $id_carrito = $row_carrito['id_carrito'];
                    }
    
                    if(!$error2){
                        $error3 = false;
                        $total_carrito_detalle = $cantidad_ofertas * $precio_oferta;
                        $comprobacion_oferta = "SELECT * FROM carrito_detalle WHERE activo = '1' AND eliminado = '0' AND id_oferta = '".$id_oferta."' AND id_carrito = '$id_carrito'";
                        $query_comprobar_oferta = _query($comprobacion_oferta);
                        if(_num_rows($query_comprobar_oferta) == 0){
                            $tabla_carrito_detalle = 'carrito_detalle';
                            $form_data_detalle = array(
                                'id_carrito' => $id_carrito,
                                'id_oferta' => $id_oferta,
                                'precio' => $precio_oferta,
                                'cantidad' => $cantidad_ofertas,
                                'total' => $total_carrito_detalle,
                                'activo' => 1,
                                'eliminado' => 0,
                            );
                            $insertar_detalle = _insert($tabla_carrito_detalle,$form_data_detalle);
                            if(!$insertar_detalle){
                                $error3 = true;
                            }
                        }
                        else{
                            $row_carrito_detalle = _fetch_array($query_comprobar_oferta);
                            $id_carrito_detalle = $row_carrito_detalle['id_carrito_detalle'];
                            $cantidad_anterior = $row_carrito_detalle['cantidad'];
                            $cantidad_nueva = $cantidad_anterior + $cantidad_ofertas;
                            $total_nuevo = $cantidad_nueva * $precio_oferta;
                            $tabla_carrito_detalle = 'carrito_detalle';
                            $form_data_detalle = array(
                                'cantidad' => $cantidad_nueva,
                                'total' => $total_nuevo
                            );
                            $where_carrito_detalle = " WHERE id_carrito_detalle = '".$id_carrito_detalle."'";
                            $update_detalle = _update($tabla_carrito_detalle,$form_data_detalle,$where_carrito_detalle);
                            if(!$update_detalle){
                                $error3 = true;
                            }
                        }
                        if(!$error3){
                            $xdatos['typeinfo'] = "Success";
                            $xdatos['msg'] = "Item Agregado con Exito!";
                            _commit();
                        }
                        else{
                            $xdatos['typeinfo'] = "Error";
                            $xdatos['msg'] = "No se pudo agregar la oferta al carrito por el momento!";
                            _rollback();
                        }
                    }
                    else{
                        $xdatos['typeinfo'] = "Error";
                        $xdatos['msg'] = "No se pudo agregar la oferta al carrito por el momento!";
                        _rollback();
                    }
                }
                else{
                    $xdatos['typeinfo'] = "Error";
                    $xdatos['msg'] = "Ya tiene un total de $cantidad_carrito ofertas agregadas, no puede agregar $cantidad_ofertas porque se excede del limite!";
                    _rollback();
                }
            }
            else{
                $xdatos['typeinfo'] = "Error";
                $xdatos['msg'] = "No existe tal oferta o ya no se encuentra activa!";
                _rollback();
            }
        }
        else{
            $xdatos['typeinfo'] = "Error";
            $xdatos['msg'] = "La cantidad de ofertas tiene que ser mayor a 0! ---".$cantidad_ofertas ;
        }
        echo json_encode($xdatos);
    }

    function eliminar_item(){
        $id_carrito_detalle = $_POST['id_carrito_detalle'];
        $desactivar  = _desactivar('carrito_detalle'," WHERE id_carrito_detalle = '$id_carrito_detalle'");
        if ($desactivar) {
            $xdatos ['typeinfo'] = 'Success';
            $xdatos ['msg'] = 'Item eliminado correctamente!';
        } else {
            $xdatos ['typeinfo'] = 'Error';
            $xdatos ['msg'] = 'El Item no pudo ser eliminado';
        }	
        echo json_encode ( $xdatos );
    }
    function actualizar_tabla(){
        $tabla_devolver = "";
        $tabla_devolver.='<div class="col-lg-9 tabla_carrito_x">';
        $tabla_devolver.='<table class="table tabla_carrito" >';
        $tabla_devolver.='<thead class="thead-dark" id="thead-dark">';
        $tabla_devolver.='<tr>';
        $tabla_devolver.='<th scope="col">#</th>';
        $tabla_devolver.='<th scope="col">ID OFERTA</th>';
        $tabla_devolver.='<th scope="col">EMPRESA</th>';
        $tabla_devolver.='<th scope="col">TITULO</th>';
        $tabla_devolver.='<th scope="col">PRECIO</th>';
        $tabla_devolver.='<th scope="col">CANTIDAD</th>';
        $tabla_devolver.='<th scope="col">LIMITE</th>';
        $tabla_devolver.='<th scope="col">TOTAL</th>';
        $tabla_devolver.='<th scope="col">ACCION</th>';
        $tabla_devolver.='</tr>';
        $tabla_devolver.='</thead>';
        $tabla_devolver.='<tbody>';
        $count = 1;
        $id_cliente = $_SESSION['id_cliente'];
        $sql = "SELECT ofertas.id_oferta,carrito_detalle.id_carrito, carrito_detalle.id_carrito_detalle, ofertas.titulo, ofertas.cantidad_limite_cupones, ofertas.ilimitar, ofertas.precio_oferta, carrito_detalle.cantidad, empresas.nombre, carrito_detalle.total FROM ofertas INNER JOIN carrito_detalle ON carrito_detalle.id_oferta = ofertas.id_oferta INNER JOIN carrito on carrito.id_carrito = carrito_detalle.id_carrito INNER JOIN empresas on empresas.id_empresa = ofertas.id_empresa WHERE ofertas.activo = 1 AND ofertas.eliminado = 0 AND carrito_detalle.activo = 1 AND carrito_detalle.eliminado = 0 AND carrito.id_cliente = '$id_cliente'";
        $query = _query($sql);
        if(_num_rows($query) > 0){
            $cantidad_final = 0;
            $total_final =0;
            while($row = _fetch_array($query)){  
                $id_carrito_detalle = $row['id_carrito_detalle'];                                  
                $id_oferta = $row['id_oferta'];
                $titulo = $row['titulo'];
                $nombre_empresa = $row['nombre'];
                $precio_oferta = $row['precio_oferta'];
                $cantidad = $row['cantidad'];
                $cantidad_final+=$cantidad;
                $total = $row['total'];
                $total_final+=$total;
                $cantidad_limite_cupones = $row['cantidad_limite_cupones'];
                $ilimitar = $row['ilimitar'];
                $id_carrito = $row['id_carrito'];
                $cantidad_cupones = $cantidad_limite_cupones;
                if($ilimitar){
                    $cantidad_cupones = "Ilimitado";
                }
                $tabla_devolver.='<tr>';
                $tabla_devolver.='<td>'.$count.'</td>';
                $tabla_devolver.='<td>'.$id_oferta.'</td>';
                $tabla_devolver.='<td>'.$nombre_empresa.'</td>';
                $tabla_devolver.='<td>'.$titulo.'</td>';
                $tabla_devolver.='<td>$ '.number_format($precio_oferta,2).'</td>';
                $tabla_devolver.='<td><input type="number" name="cantidad_oferta" id="'.$id_carrito_detalle.'" class="cantidad_oferta" value="'.$cantidad.'"'; 
                if(!$ilimitar){ 
                    $tabla_devolver.="max=\"$cantidad_cupones\"";
                } 
                $tabla_devolver.='></td>';
                $tabla_devolver.='<td>'.$cantidad_cupones.'</td>';
                $tabla_devolver.='<td>$ '.number_format($total,2).'</td>';
                $tabla_devolver.='<td><button type="button" class="btn btn-danger eliminar_carrito_detalle" id="'.$id_carrito_detalle.'">Borrar</button></td>';
                $tabla_devolver.='</tr>';
                $count++;
            }
            $tabla_devolver.='<tr>';
            $tabla_devolver.='<td></td>';
            $tabla_devolver.='<td></td>';
            $tabla_devolver.='<td></td>';
            $tabla_devolver.='<td></td>';
            $tabla_devolver.='<td>TOTAL</td>';
            $tabla_devolver.='<td class="cantidad_final">'.$cantidad_final.'</td>';
            $tabla_devolver.='<td></td>';
            $tabla_devolver.='<td class="total_final">$ '.number_format($total_final,2).'</td>';
            $tabla_devolver.='<td></td>';
            $tabla_devolver.='</tr>';
        } 
        $tabla_devolver.='</tbody>';
        $tabla_devolver.='</table>';
        $tabla_devolver.='</div>';
        $tabla_devolver.='<div class="col-lg-3 resultados_carrito_x">';
        $tabla_devolver.='<table class="table tabla_carrito">';
        $tabla_devolver.='<thead class="thead-dark" id="thead-dark">';
        $tabla_devolver.='<tr>';
        $tabla_devolver.='<th scope="col">TOTALES</th>';
        $tabla_devolver.='<th scope="col"></th>';
        $tabla_devolver.='</tr>';
        $tabla_devolver.='<tr>';
        $tabla_devolver.='<th scope="col">DESCRIPCION</th>';
        $tabla_devolver.='<th scope="col">VALOR</th>';
        $tabla_devolver.='</tr>';
        $tabla_devolver.='</thead>';
        $tabla_devolver.='<tbody>';
        $tabla_devolver.='<tr>';
        $tabla_devolver.=' <th scope="row">CANTIDAD PRODUCTOS</th>';
        $tabla_devolver.='<td class="cantidad_final">'.$cantidad_final.'</td>';
        $tabla_devolver.='</tr>';
        $tabla_devolver.='<tr>';
        $tabla_devolver.='<th scope="row">TOTAL FINAL</th>';
        $tabla_devolver.='<td class="total_final">$ '.number_format($total_final,2).'</td>';
        $tabla_devolver.='</tr>';
        $tabla_devolver.='<tr>';
        $tabla_devolver.='<th scope="row"><button type="button" class="btn btn-success" id="btnPagar">Pagar</button></th>';
        $tabla_devolver.='<td class="total_final"><button type="button" class="btn btn-warning" id="btnVolver">Volver</button></td>';
        $tabla_devolver.='</tr>';
        $tabla_devolver.='<tr>';
        $tabla_devolver.='</tbody>';
        $tabla_devolver.='</table>';
        $tabla_devolver.='</div>';
        $tabla_devolver.='<input type="hidden" name="id_carrito" id="id_carrito" value="'.$id_carrito.'">';
        echo $tabla_devolver;
    }

    function actualizar_item(){
        $id_carrito_detalle = $_POST['id_carrito_detalle'];
        $cantidad = $_POST['cantidad'];
        $sql = "SELECT * FROM carrito_detalle WHERE id_carrito_detalle = '".$id_carrito_detalle."'";
        $query = _query($sql);
        $row = _fetch_array($query);
        $precio = $row['precio'];
        $total = $cantidad * $precio;
        $tabla = 'carrito_detalle';
        $form_data = array(
            'cantidad' => $cantidad,
            'total' => $total
        );
        $where = " WHERE id_carrito_detalle = '".$id_carrito_detalle."'";
        $update = _update($tabla,$form_data,$where);
        if($update){
            $xdatos['typeinfo'] = "Success";
        }
        else{
            $xdatos['typeinfo'] = "Error";
            $xdatos['msg'] = "No se pudo actualizar el total";
        }
        echo json_encode($xdatos);
    }

    function realizar_pago(){
        $error_final = false;
        $nombre = $_POST['nombre'];
        $tarjeta = $_POST['tarjeta'];
        $numero_tarjeta = $_POST['numero_tarjeta'];
        $ccv = $_POST['ccv'];
        $fecha_vencimiento = $_POST['fecha_vencimiento'];
        $id_carrito = $_POST['id_carrito'];
        $id_cliente = $_SESSION['id_cliente'];
        $sql_total = "SELECT SUM(carrito_detalle.total) as 'total' FROM carrito_detalle INNER JOIN carrito on carrito.id_carrito = carrito_detalle.id_carrito WHERE carrito.id_carrito = '$id_carrito'";
        $query_total = _query($sql_total);
        $row_total = _fetch_array($query_total);
        $total = $row_total['total'];
        _begin();
        $tabla_compra = 'compra';
        $form_data_compra = array(
            'id_cliente' => $id_cliente,
            'total' => $total,
            'numero_tarjeta' => $numero_tarjeta,
            'nombre_tarjeta' => $tarjeta,
            'propietario_tarjeta' => $nombre,
            'ccv' => $ccv,
            'fecha_vencimiento' => $fecha_vencimiento,
            'activo' => 1,
            'eliminado' => 0,
            'fecha' => date("Y-m-d"),
            'hora' => date("H:i:s")
        );
        $insertar_compra = _insert($tabla_compra,$form_data_compra);
        if($insertar_compra){
            $id_compra = _insert_id();
            $sql_empresas = "SELECT SUM(carrito_detalle.total) as 'total_empresa', empresas.id_empresa FROM carrito_detalle INNER JOIN ofertas on ofertas.id_oferta = carrito_detalle.id_oferta INNER JOIN empresas on empresas.id_empresa = ofertas.id_empresa INNER JOIN carrito on carrito.id_carrito = carrito_detalle.id_carrito WHERE carrito.id_carrito = '$id_carrito' GROUP BY empresas.id_empresa ";
            $query_empresas = _query($sql_empresas);
            while($row_empresas = _fetch_array($query_empresas)){
                $total_empresa = $row_empresas['total_empresa'];
                $id_empresa = $row_empresas['id_empresa'];
                $tabla_compra_cupones = 'compra_cupones';
                $form_data_compra_cupones = array(
                    'id_compra' => $id_compra,
                    'id_empresa' => $id_empresa,
                    'total' => $total_empresa,
                    'activo' => '1',
                    'eliminado' => '0',
                );
                $insertar_compra_cupones = _insert($tabla_compra_cupones,$form_data_compra_cupones);
                if($insertar_compra_cupones){
                    $id_compra_cupones = _insert_id();
                    $sql_compra_cupones_detalle = "SELECT carrito_detalle.id_oferta, carrito_detalle.precio, ofertas.cantidad_limite_cupones, ofertas.ilimitar, carrito_detalle.cantidad, carrito_detalle.total FROM carrito_detalle INNER JOIN carrito on carrito.id_carrito = carrito_detalle.id_carrito INNER JOIN ofertas on ofertas.id_oferta = carrito_detalle.id_oferta INNER JOIN empresas on empresas.id_empresa = ofertas.id_empresa WHERE carrito.id_carrito = '$id_carrito' AND empresas.id_empresa = '$id_empresa'";
                    $query_compra_cupones_detalle = _query($sql_compra_cupones_detalle);
                    while($row_compra_cupones_detalle = _fetch_array($query_compra_cupones_detalle)){
                        $id_oferta = $row_compra_cupones_detalle['id_oferta'];
                        $precio = $row_compra_cupones_detalle['precio'];
                        $cantidad = $row_compra_cupones_detalle['cantidad'];
                        $total = $row_compra_cupones_detalle['total'];
                        $ilimitar = $row_compra_cupones_detalle['ilimitar'];
                        $cantidad_limite_cupones = $row_compra_cupones_detalle['cantidad_limite_cupones'];
                        $tabla_compra_cupones_detalle = 'compra_cupones_detalle';
                        $form_data_compra_cupones_detalle = array(
                            'id_compra_cupon' => $id_compra_cupones,
                            'precio' => $precio,
                            'cantidad' => $cantidad,
                            'total' => $total,
                            'id_oferta' => $id_oferta,
                            'canjeado' => 0,
                            'activo' => 1,
                            'eliminado' => 0
                        );
                        $insertar_compra_cupones_detalle = _insert($tabla_compra_cupones_detalle,$form_data_compra_cupones_detalle);
                        if($insertar_compra_cupones_detalle){
                            if(!$ilimitar){
                                $cantidad_nueva = $cantidad_limite_cupones - $cantidad;
                                $tabla_ofertas = 'ofertas';
                                $form_data_ofertas = array(
                                    'cantidad_limite_cupones' => $cantidad_nueva
                                );
                                $where_ofertas = " WHERE id_oferta = '".$id_oferta."'";
                                $update_ofertas = _update($tabla_ofertas,$form_data_ofertas,$where_ofertas);
                                if(!$update_ofertas){
                                    $error_final = true;
                                }
                            }
                        }
                        else{
                            $error_final = true;
                        }
                    }
                }
                else{
                    $error_final = true;
                }
            }
        }
        else{
            $error_final = true;
        }
        if(!$error_final){
            $desactivar  = _desactivar('carrito_detalle'," WHERE id_carrito = '$id_carrito'");
            if ($desactivar) {
                $desactivar2  = _desactivar('carrito'," WHERE id_carrito = '$id_carrito'");
                if ($desactivar2) {
                    $xdatos['typeinfo'] = "Success";
                    $xdatos['msg'] = "Compra realizada con exito!";
                    $xdatos['id_compra'] = $id_compra;
                    _commit();
                    
                } else {
                    $xdatos['typeinfo'] = "Error";
                    $xdatos['msg'] = "No se pudo ingresar la compra!";
                    _rollback();
                }	
            } else {
                $xdatos['typeinfo'] = "Error";
                $xdatos['msg'] = "No se pudo ingresar la compra!";
                _rollback();
            }	
        }
        else{
            $xdatos['typeinfo'] = "Error";
            $xdatos['msg'] = "No se pudo ingresar la compra!";
            _rollback();
        }
        echo json_encode($xdatos);
    }

    function verificar_codigo(){
        $codigo_cupon = $_POST['codigo_cupon'];
        $id_empresa = $_SESSION['id_sucursal'];
        $verificado = false;
        _begin();
        $sql1 = "SELECT COUNT(compra_cupones_detalle.id_compra_cupon_detalle) as 'contador' FROM compra_cupones_detalle INNER JOIN compra_cupones on compra_cupones.id_compra_cupon = compra_cupones_detalle.id_compra_cupon WHERE compra_cupones_detalle.canjeado = 0 AND compra_cupones.id_empresa = '$id_empresa' AND compra_cupones.id_compra_cupon = '$codigo_cupon'";
        $query1 = _query($sql1);
        $row1 = _fetch_array($query1);
        if($row1['contador'] == 0){
            $sql2 = "SELECT compra_cupones.id_compra_cupon FROM compra_cupones_detalle INNER JOIN compra_cupones on compra_cupones.id_compra_cupon = compra_cupones_detalle.id_compra_cupon WHERE compra_cupones_detalle.canjeado = 0 AND compra_cupones.id_empresa = '$id_empresa'";
            $query2 = _query($sql2);
            if(_num_rows($query2) > 0){
                while($row2 = _fetch_array($query2)){
                    $cod_cupon = md5($row2['id_compra_cupon']);
                    if($cod_cupon == $codigo_cupon){
                        $id_compra_cupon = $row2['id_compra_cupon'];
                        $verificado = true;
                    }
                }
            }
        }
        else{
            $verificado = true;
            $id_compra_cupon = $codigo_cupon;
        }
        if($verificado){
            $update = 1;
            if($update){
                $tabla_devolver = "";
                $tabla_devolver.='<div class="col-lg-12">';
                $tabla_devolver.='<table class="table tabla_carrito" >';
                $tabla_devolver.='<thead class="thead-dark" id="thead-dark">';
                
                $tabla_devolver.='<tr>';
                $tabla_devolver.='<th scope="col">#</th>';
                $tabla_devolver.='<th scope="col">ID OFERTA</th>';
                $tabla_devolver.='<th scope="col">EMPRESA</th>';
                $tabla_devolver.='<th scope="col">TITULO</th>';
                $tabla_devolver.='<th scope="col">PRECIO</th>';
                $tabla_devolver.='<th scope="col">CANTIDAD</th>';
                $tabla_devolver.='<th scope="col">TOTAL</th>';
                $tabla_devolver.='</tr>';
                $tabla_devolver.='</thead>';
                $tabla_devolver.='<tbody>';
                $count = 1;
                $sql = "SELECT ofertas.id_oferta, clientes.dui, empresas.nombre as 'nombre_empresa', ofertas.titulo, compra_cupones_detalle.precio, compra_cupones_detalle.cantidad, compra_cupones_detalle.total FROM compra_cupones_detalle INNER JOIN ofertas on ofertas.id_oferta = compra_cupones_detalle.id_oferta INNER JOIN compra_cupones on compra_cupones.id_compra_cupon = compra_cupones_detalle.id_compra_cupon INNER JOIN empresas on empresas.id_empresa = compra_cupones.id_empresa INNER JOIN compra on compra.id_compra = compra_cupones.id_compra INNER JOIN clientes on clientes.id_cliente = compra.id_cliente WHERE compra_cupones.id_compra_cupon = '$id_compra_cupon'";
                $query = _query($sql);
                if(_num_rows($query) > 0){
                    $cantidad_final = 0;
                    $total_final =0;
                    while($row = _fetch_array($query)){                                
                        $id_oferta = $row['id_oferta'];
                        $titulo = $row['titulo'];
                        $nombre_empresa = $row['nombre_empresa'];
                        $precio_oferta = $row['precio'];
                        $cantidad = $row['cantidad'];
                        $cantidad_final+=$cantidad;
                        $total = $row['total'];
                        $dui = $row['dui'];
                        $total_final+=$total;
                        $tabla_devolver.='<tr>';
                        $tabla_devolver.='<td>'.$count.'</td>';
                        $tabla_devolver.='<td>'.$id_oferta.'</td>';
                        $tabla_devolver.='<td>'.$nombre_empresa.'</td>';
                        $tabla_devolver.='<td>'.$titulo.'</td>';
                        $tabla_devolver.='<td>$ '.number_format($precio_oferta,2).'</td>';
                        $tabla_devolver.='<td>'.$cantidad.'</td>';
                        $tabla_devolver.='<td>$ '.number_format($total,2).'</td>';
                        $tabla_devolver.='</tr>';
                        $count++;
                    }
                    $tabla_devolver.='<tr>';
                    $tabla_devolver.='<td>DUI: </td>';
                    $tabla_devolver.='<td>'.$dui.'</td>';
                    $tabla_devolver.='<td></td>';
                    $tabla_devolver.='<td></td>';
                    $tabla_devolver.='<td>TOTAL</td>';
                    $tabla_devolver.='<td class="cantidad_final">'.$cantidad_final.'</td>';
                    $tabla_devolver.='<td class="total_final">$ '.number_format($total_final,2).'</td>';
                    $tabla_devolver.='<td></td>';
                    $tabla_devolver.='</tr>';
                } 
                $tabla_devolver.='</tbody>';
                $tabla_devolver.='</table>';
                $tabla_devolver.='</div>';
                $tabla_devolver.='<div class="col-lg-12">';
                $tabla_devolver.='<button type="button" style="float:right; margin-left:15px;" class="btn btn-primary" id="canjear" name="canjear">Canjear</button>';
                $tabla_devolver.='<button type="button" style="float:right;" class="btn btn-danger" id="limpiar" name="limpiar">Limpiar</button>';
                $tabla_devolver.='<input type="hidden" name="codigo_cupon_hidden" id="codigo_cupon_hidden" value="'.$id_compra_cupon.'">';
                $tabla_devolver.='</div>';
                $xdatos['typeinfo'] = "Success";
                $xdatos['msg'] = "Informacion Traida con Exito!";
                $xdatos['tabla'] = $tabla_devolver;
                _commit();
            }
            else{
                $xdatos['typeinfo'] = "Error";
                $xdatos['msg'] = "No existe ese codigo de canje, intente con otro!";
                _rollback();
            }
        }
        else{
            $xdatos['typeinfo'] = "Error";
            $xdatos['msg'] = "No existe ese codigo de canje, intente con otro!";
            _rollback();
        }
        echo json_encode($xdatos);
    }

    function canjear_codigo(){
        $codigo_cupon = $_POST['codigo_cupon'];
        $tabla = 'compra_cupones_detalle';
        $form_data = array(
            'canjeado' => 1
        );
        $where = " WHERE id_compra_cupon = '$codigo_cupon'";
        $update = _update($tabla,$form_data,$where);
        if ($update) {
            $xdatos ['typeinfo'] = 'Success';
            $xdatos ['msg'] = 'Cupon canjeado correctamente!';
        } else {
            $xdatos ['typeinfo'] = 'Error';
            $xdatos ['msg'] = 'El Cupon no pudo ser canjeado';
        }	
        echo json_encode ( $xdatos );
    }

?>