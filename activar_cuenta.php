<?php
    require("consola/_conexion.php");
    $id_usuario=$_REQUEST["id"];
    $sql= "SELECT * FROM usuario WHERE activo = 0 AND eliminado = 0";
    $query = _query($sql);
    $id_usuario_activo = 0;
    while($row = _fetch_array($query)){
        $id_usu = $row['id_usuario'];
        $id_usu = md5($id_usu);
        if($id_usu == $id_usuario){
            $id_usuario_activo = $row['id_usuario'];
        }
    }   
    if($id_usuario_activo != 0){
        $tabla = 'usuario';
        $form_data = array(
            'activo' => 1
        );
        $where = " WHERE id_usuario = '$id_usuario_activo'";
        $update = _update($tabla,$form_data,$where);
        if($update){
            header('Location: login.php?result=4');
        }
        else{
            header('Location: login.php?result=3');
        }
    }
    else{
        header('Location: login.php?result=2');
    }

?>