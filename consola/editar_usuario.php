<?php
include("_core.php");
function formulario()
{
	include("header.php");
	include("menu.php");
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
    if($links !="NOT" || $admin=="1")
	{ $id_usuario=$_REQUEST["id_usuario"];
    $datos=_query("SELECT *FROM usuario WHERE id_usuario='$id_usuario' AND id_sucursal='1'");
    $datos_usuario=_fetch_array($datos);
    $nombre=$datos_usuario["nombre"];
    $usuario=$datos_usuario["usuario"];
    $password=$datos_usuario["password"];
    $admin=$datos_usuario["admin"];
    $activo=$datos_usuario["activo"];
    $id_empleado=$datos_usuario["id_empleado"];
		?>
		<br>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class='text-primary'><i class="fa fa-user"></i> Editar Usuario</h4>
					</div>
					<div class="panel-body">
						<div class="col-lg-12">
							<form class="form-horizontal" id="frm_usuario" autocomplete="off">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group has-info single-line">
											<label class="control-label">Nombre</label>
											<input type="text" class="form-control" name="nombre" id="nombre"  value="<?php echo $nombre;?>" onkeyup="mayus(this)">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group has-info single-line">
											<label class="control-label">Usuario</label>
											<input type="text" class="form-control" name="usuario" id="usuario"  value="<?php echo $usuario;?>" >
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group has-info single-line">
											<label class="control-label">Contraseña</label>
											<input type="password" class="form-control" name="clave1" id="clave1"  value="<?php echo $password;?>">
										</div>
									</div>
                  <div class="col-lg-6">
										<div class="form-group has-info single-line">
											<label class="control-label">Confirmar</label>
											<input type="password" class="form-control" name="clave2" id="clave2" value="<?php echo $password;?>">
										</div>
									</div>
								</div>
                <div class="row">
                  <div class="col-lg-3">
                    <div class="form-group has-info single-line">
											<div class='checkbox i-checks'>
												<label id='frentex'>
												<?php if($admin==1){ ?>
													<input type='checkbox' id='checkadmin' name='checkadmin' che checked value="0"><strong> Admin</strong>
												<?php }else{?>
													<input type='checkbox' id='checkadmin' name='checkadmin' value="0"><strong> Admin</strong>
												<?php }?>
												</label>
											</div>
											<input type='hidden' id='admin' name='admin' value="<?php echo $admin?>">
                    </div>
									</div>
                  <div class="col-lg-3">
                    <div class="form-group has-info single-line">
											<div class='checkbox i-checks'>
												<label id='frentex'>
												<?php if($activo==1){ ?>
													<input type='checkbox' id='checkactivo' name='checkactivo' che checked value="0"><strong> Activo</strong>
												<?php }else{?>
													<input type='checkbox' id='checkactivo' name='checkactivo' value="0"><strong> Activo</strong>
												<?php }?>
												</label>
											</div>
											<input type='hidden' id='activo' name='activo' value="<?php echo $activo?>">
                    </div>
									</div>
                  <div class="col-lg-6">
                    <div class="form-group has-info single-line">
    									<label>Empleado</label>
    									<select class="form-control select" name="id_empleado" id="id_empleado" style="width:100%;">
    										<?php
                       $sql_emp = _query("SELECT id_profesor,nombres, apellidos FROM profesor ORDER BY nombres ASC");
                       while ($row_emp=_fetch_array($sql_emp))
                       {
                           echo "<option value='".$row_emp["id_profesor"]."'";
                           if($id_empleado==$row_emp["id_profesor"] ){ echo " selected "; }
                         echo ">".$row_emp["nombres"]." ".$row_emp['apellidos']."</option>";
                       }
                   ?>
    									</select>
    								</div>
                  </div>
                </div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<input type="hidden" name="process" id="process" value="edit">
											<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario;?>">
											<input type="submit" value="Guardar" class="btn btn-primary">
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
	}
	else
	{
		echo "<br><div class='col-lg-12'>
		<div class='row'>
		<div class='col-lg-12'>
		<div class='alert alert-warning'>Usted no tiene permiso para acceder a este modulo</div>
		</div>
		</div>
		</div>";
	}
	include("footer.php");
	echo "<script type='text/javascript' src='js/funciones/funciones_usuario.js'></script>";
}

function editar()
{
  $id_usuario=$_POST["id_usuario"];
	$id_empleado=$_POST["id_empleado"];
	$nombre=$_POST["nombre"];
	$usuario=$_POST["usuario"];
	$clave = $_POST['clave'];
	$admin=$_POST["admin"];
	$activo=$_POST["activo"];
	//$id_sucursal = $_SESSION["id_sucursal"];
    $existe_usuario =_query("SELECT id_usuario FROM usuario WHERE usuario='$usuario' AND id_usuario!='$id_usuario' AND id_sucursal='1'");
    $numrowss=_num_rows($existe_usuario);
    if($numrowss==0)
    {
        $query_password = _query("SELECT password FROM usuario WHERE password = '$clave' AND id_usuario = '$id_usuario'");
        if(_num_rows($query_password) > 0){
            $form_data = array(
                'id_empleado'=>$id_empleado,
                    'usuario' => $usuario,
                    'nombre' => $nombre,
                    'admin'=>$admin,
                    'activo'=>$activo,
                    'id_sucursal'=>'1'
                );
        }
        else{
            $form_data = array(
                'id_empleado'=>$id_empleado,
                    'usuario' => $usuario,
                    'nombre' => $nombre,
                    'password' => md5($clave),
                    'admin'=>$admin,
                    'activo'=>$activo,
                    'id_sucursal'=>'1'
                );
        }
        
        $table = 'usuario';
		$where = "id_usuario='".$id_usuario."'";
        $update = _update($table,$form_data, $where);
        if($update)
        {
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Registro ingresado con exito!';
            $xdatos['process']='editar';
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Registro no pudo ser editato!';
        }
    }else{
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='El usuario ya existe!';
    }
	echo json_encode($xdatos);
}

if(!isset($_POST['process'])){
	formulario();
}
else
{
	if(isset($_POST['process'])){
		switch ($_POST['process']) {
			case 'edit':
			editar();
			break;
		}
	}
}


?>
