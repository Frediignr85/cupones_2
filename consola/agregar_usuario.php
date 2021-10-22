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
	{
		?>
		<br>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class='text-primary'><i class="fa fa-user"></i> Registro de Usuario</h4>
					</div>
					<div class="panel-body">
						<div class="col-lg-12">
							<form class="form-horizontal" id="frm_usuario" autocomplete="off">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group has-info single-line">
											<label class="control-label">Nombre</label>
											<input type="text" class="form-control" name="nombre" id="nombre" onkeyup="mayus(this)">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group has-info single-line">
											<label class="control-label">Usuario</label>
											<input type="text" class="form-control" name="usuario" id="usuario" >
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group has-info single-line">
											<label class="control-label">Contraseña</label>
											<input type="password" class="form-control" name="clave1" id="clave1">
										</div>
									</div>
                  <div class="col-lg-6">
										<div class="form-group has-info single-line">
											<label class="control-label">Confirmar</label>
											<input type="password" class="form-control" name="clave2" id="clave2">
										</div>
									</div>
								</div>
                <div class="row">
                  <div class="col-lg-3">
                    <div class="form-group has-info single-line">
                        <div class='checkbox i-checks'>
                          <label id='frentex'>
                              <input type='checkbox' id='checkadmin' name='checkadmin' value="0"><strong> Admin</strong>
                          </label>
                        </div>
                        <input type='hidden' id='admin' name='admin' value="0">
                    </div>
									</div>
                  <div class="col-lg-3">
                    <div class="form-group has-info single-line">
                        <div class='checkbox i-checks'>
                          <label id='frentex'>
                              <input type='checkbox' id='checkactivo' name='checkactivo' value="0"><strong> Activo</strong>
                          </label>
                        </div>
                        <input type='hidden' id='activo' name='activo' value="0">
                    </div>
									</div>
                  <div class="col-lg-6">
                    <div class="form-group has-info single-line">
    									<label>Empleado</label>
    									<select class="form-control select" name="id_empleado" id="id_empleado" style="width:100%;">
    										<?php
    										$sql_ubi = _query("SELECT id_profesor,nombres,apellidos FROM profesor WHERE activo = 1 ORDER BY nombres ASC");
    										while ($row = _fetch_array($sql_ubi))
    										{
    											echo "<option value='".$row["id_profesor"]."'>".$row["nombres"]." ".$row['apellidos']."</option>";
    										}
    										?>
    									</select>
    								</div>
                  </div>
                </div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<input type="hidden" name="process" id="process" value="insert">
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

function insertar()
{
	$nombre=$_POST["nombre"];
	$usuario=$_POST["usuario"];
	$clave1=md5($_POST["clave"]);
	$id_empleado=$_POST["id_empleado"];
	$admin=$_POST["admin"];
	$activo=$_POST["activo"];

	//$id_sucursal = $_SESSION["id_sucursal"];
	$sql_result=_query("SELECT id_usuario FROM usuario WHERE usuario='$usuario' AND id_sucursal='1'");
	$row_update=_fetch_array($sql_result);
	$numrows=_num_rows($sql_result);
	$table = 'usuario';
	$form_data = array(
    'id_empleado'=>$id_empleado,
		'usuario' => $usuario,
		'nombre' => $nombre,
		'password' => $clave1,
		'admin'=>$admin,
		'activo'=>$activo,
		'id_sucursal'=>'1'
	);
	if($numrows == 0)
	{
		$insertar = _insert($table,$form_data );
		if($insertar)
		{
			$xdatos['typeinfo']='Success';
			$xdatos['msg']='Registro ingresado con exito!';
			$xdatos['process']='insert';
		}
		else
		{
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Registro no pudo ser ingresado!';
		}
	}
	else
	{
		$xdatos['typeinfo']='Error';
		$xdatos['msg']='Ya existe este usuario!';
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
			case 'insert':
			insertar();
			break;
		}
	}
}


?>
