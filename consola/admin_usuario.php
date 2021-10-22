<?php
include ("_core.php");
function initial()
{
	$title = 'Administrar Usuarios';
	include_once "header.php";
	include_once "menu.php";

	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	//$links=permission_usr($id_user,$filename);
	$fini = ED(restar_dias(date("Y-m-d"),30));
	$fin = ED(date("Y-m-d"));
	?>

	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<?php
					$links='NOT';
					if ($links!='NOT' || $admin=='1' )
					{
						echo "<div class='ibox-title'>";
						//permiso del script
						$filename='agregar_usuario.php';
						//$link=permission_usr($id_user,$filename);
						$link='NOT';
						if ($link!='NOT' || $admin=='1' )
            echo "<a href='agregar_usuario.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar Usuario</a>";
            echo "</div>";

						?>
						<div class="ibox-content">
							<!--load datables estructure html-->
							<header>
								<h4><?php echo $title; ?></h4>
							</header>
								<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="editable2">
									<thead>
										<tr>
											<th class="col-lg-1 text-primary font-bold">N°</th>
											<th class="col-lg-2 text-primary font-bold">Nombre</th>
											<th class="col-lg-2 text-primary font-bold">Usuario</th>
											<th class="col-lg-1 text-primary font-bold">Acción</th>
										</tr>
									</thead>
									<tbody>
										<?php
                    $sql_u=_query("SELECT *FROM usuario WHERE id_sucursal='1' AND id_usuario!=-1 ");
                    $count=0;
                    while($row = _fetch_array($sql_u))
      							{
                      $count++;
                      $id_usuario=$row['id_usuario'];
                      $nombre=$row['nombre'];
                      $usuario=$row['usuario'];
      								echo "<tr>";
      								echo"<td>".$count."</td>";
      								echo"<td>".$nombre."</td>
      								<td>".$usuario."</td>";
      								echo"<td class='text-center'>
											<div class='btn-group'>
											<button class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>
											<i class=\"fa	fa-gears (alias)\"></i> Acciones
											<span class='caret'></span>
											</button>
											<ul class='dropdown-menu dropdown-primary'>";
      								$filename='editar_usuario.php';
      								$link=permission_usr($id_user,$filename);
      								if ($link!='NOT' || $admin=='1' )
      								echo "<li><a  href='editar_usuario.php?id_usuario=".$id_usuario."' data-refresh='true'><i class='fa fa-pencil'></i> Editar</a></li>";
      								$filename='permiso_usuario.php';
      								$link=permission_usr($id_user,$filename);
      								if ($link!='NOT' || $admin=='1' )
      								echo "<li><a  href='permiso_usuario.php?id_usuario=".$id_usuario."' data-refresh='true'><i class='fa fa-lock'></i> Permiso</a></li>";
      								$filename='borrar_usuario.php';
      								$link=permission_usr($id_user,$filename);
      								if ($link!='NOT' || $admin=='1' )
      								{
      								if($id_user==$row['id_usuario'])
      								{
      								}else
                      {
      									//echo "<li><a data-toggle='modal' href='borrar_usuario.php?id_usuario=".$id_usuario."' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Eliminar</a></li>";
												echo "<li><a id_usuario='$id_usuario' class='elim'><i class='fa fa-eraser'></i> Eliminar</a></li>";

											}
      								}
      								echo "	</ul>
      								</div>
      								</td>
      								</tr>";
      							}
										?>
									</tbody>
									</table>
								</div>
							</section>
							<!--Show Modal Popups View & Delete -->
							<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog'>
									<div class='modal-content'></div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
							<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog'>
									<div class='modal-content'></div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						</div><!--div class='ibox-content'-->
					</div><!--<div class='ibox float-e-margins' -->
					</div> <!--div class='col-lg-12'-->
				</div> <!--div class='row'-->
			</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
			<?php
			include("footer.php");
			echo" <script type='text/javascript' src='js/funciones/funciones_usuario.js'></script>";
		} //permiso del script
		else {
			echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
			include("footer.php");
		}
	}
	function eliminar()
	{
		$id_usuario = $_POST["id_usuario"];
		$tabla ="usuario";
		$where_clause = "id_usuario='". $id_usuario. "'";
		$delete = _delete($tabla,$where_clause);
		if($delete)
		{
			$xdatos["typeinfo"]="Success";
			$xdatos["msg"]="Registro eliminado correctamente!";
		}
		else
		{
			$xdatos["typeinfo"]="Error";
			$xdatos["msg"]="Registro no pudo ser eliminada!"._error();
		}
		echo json_encode($xdatos);
	}
	if(!isset($_POST['process'])){
		initial();
	}
	else
	{
		if(isset($_POST['process'])){
			switch ($_POST['process']) {
				default:
				initial();
				break;
				case 'elim':
				eliminar();
				break;
			}
		}
	}
	?>
