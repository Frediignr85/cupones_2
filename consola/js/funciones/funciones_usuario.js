$(document).ready(function() {
    $('#editable2').DataTable({
        "pageLength": 5,
        "language": {
            "url": "js/Spanish.json"
        }
    });
    if ($("#process").val() != "permissions") {
        $('#frm_usuario').validate({
            rules: {
                nombre: {
                    required: true,
                },
                usuario: {
                    required: true,
                },
                clave1: {
                    required: true,
                },
                clave2: {
                    equalTo: clave1,
                },
            },
            messages: {
                nombre: "Por favor ingrese el nombre del usuario",
                usuario: "Por favor ingrese el usuario",
                clave1: "Por favor ingrese la clave",
                clave2: "La contraseña no coincide",
            },
            submitHandler: function(form) {
                senddata();
            }
        });
    } else {
        $("#formulario").submit(function(event) {
            senddata();
            event.preventDefault();
        });
    }
    $('#id_empleado').select2();
    $('#usuario').on('keyup', function(evt) {
        if (evt.keyCode == 32) {
            $(this).val($(this).val().replace(" ", ""));
        } else {
            $(this).val($(this).val().toLowerCase());
        }
    });
    $("#checkadmin").on("ifChecked", function(event) {
        $("#admin").val("1");
    });
    $("#checkadmin").on("ifUnchecked", function(event) {
        $("#admin").val("0");
    });
    $("#checkactivo").on("ifChecked", function(event) {
        $("#activo").val("1");
    });
    $("#checkactivo").on("ifUnchecked", function(event) {
        $("#activo").val("0");
    });
    //________________
    $('#admi').on('ifChecked', function(event) {
        if ($("#process").val() == "permissions") {
            $('.i-checks').iCheck('check');
            $('#admin').val("1");
        }
    });
    $('#admi').on('ifUnchecked', function(event) {
        if ($("#process").val() == "permissions") {
            $('.i-checks').iCheck('uncheck');
            $('#admin').val("0");
        }
    });
    $('#activ').on('ifChecked', function(event) {
        $('#activo').val("1");
    });
    $('#activ').on('ifUnchecked', function(event) {
        $('#activo').val("0");
    });
});

$(function() {
    //binding event click for button in modal form
    $(document).on("click", "#btnDelete", function(event) {
        deleted();
    });
    // Clean the modal form
    $(document).on('hidden.bs.modal', function(e) {
        var target = $(e.target);
        target.removeData('bs.modal').find(".modal-content").html('');
    });

});

function autosave(val) {
    var name = $('#name').val();
    if (name == '' || name.length == 0) {
        var typeinfo = "Info";
        var msg = "The field name is required";
        display_notify(typeinfo, msg);
        $('#name').focus();
    } else {
        senddata();
    }
}

function senddata() {
    //Get the value from form if edit or insert
    var process = $('#process').val();
    //alert("process"+process);
    if (process == 'insert') {
        var nombre = $('#nombre').val();
        var usuario = $('#usuario').val();
        var clave = $('#clave1').val();
        var admin = $('#admin').val();
        var activo = $('#activo').val();
        var id_empleado = $('#id_empleado').val();
        var id_usuario = 0;
        var urlprocess = 'agregar_usuario.php';
        var dataString = 'process=' + process + '&nombre=' + nombre + '&usuario=' + usuario + '&clave=' + clave + '&admin=' + admin + '&id_empleado=' + id_empleado + '&activo=' + activo;
    }
    if (process == 'edit') {
        var nombre = $('#nombre').val();
        var usuario = $('#usuario').val();
        var clave = $('#clave1').val();
        var admin = $('#admin').val();
        var activo = $('#activo').val();
        var id_empleado = $('#id_empleado').val();
        var id_usuario = $('#id_usuario').val();
        var urlprocess = 'editar_usuario.php';
        var dataString = 'process=' + process + '&nombre=' + nombre + '&usuario=' + usuario + '&clave=' + clave + '&admin=' + admin + '&id_usuario=' + id_usuario + '&id_empleado=' + id_empleado + '&activo=' + activo;
    }
    if (process == 'permissions') {
        var id_usuario = $('#id_usuario').val();
        var urlprocess = 'permiso_usuario.php';
        var myCheckboxes = new Array();
        var cuantos = 0;
        var admin = $("#admin").val();
        $("input[name='myCheckboxes']:checked").each(function(index) {
            myCheckboxes.push($(this).val());
            cuantos = cuantos + 1;
        });
        if (cuantos == 0) {
            myCheckboxes = '0';
        }
        var dataString = 'process=' + process + '&admin=' + admin + '&id_usuario=' + id_usuario + '&myCheckboxes=' + myCheckboxes + '&qty=' + cuantos;
        //alert(dataString);
    }
    $.ajax({
        type: 'POST',
        url: urlprocess,
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            process = datax.process;
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                setInterval("reload1();", 1500);
            }
        }
    });
}

function reload1() {
    location.href = 'admin_usuario.php';
}
$(document).on("click", ".elim", function() {
    deleted($(this).attr("id_usuario"));
});

function deleted(id) {
    swal({
            title: "Esta seguro que desea eliminar este usuario????",
            text: "Usted no podra deshacer este cambio",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Eliminar",
            cancelButtonText: "No, Cerrar",
            closeOnConfirm: true
        },
        function() {
            $.ajax({
                type: "POST",
                url: "admin_usuario.php",
                data: "process=elim&id_usuario=" + id,
                dataType: "JSON",
                success: function(datax) {
                    display_notify(datax.typeinfo, datax.msg);
                    if (datax.typeinfo == "success" || datax.typeinfo == "Success") {
                        setInterval("reload1();", 1000);
                    }
                }
            });
        });
}

function mayus(e) {
    var v = e.value;
    var nombre = v.toUpperCase();
    $("#nombre").val(nombre);
}