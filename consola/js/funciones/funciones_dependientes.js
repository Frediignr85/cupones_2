$(document).ready(function() {
    $.validator.addMethod('regexp', function(value, element, param) {
        return this.optional(element) || value.match(param);
    }, 'Mensaje a mostrar si se incumple la condición');

    $('#formulario_empresa').validate({
        rules: {
            nombre: {
                required: true,
            },
            apellido: {
                required: true,
            },
            correo: {
                required: true,
            },
            estado: {
                required: true,
            },
        },
        messages: {
            nombre: {
                required: "Por favor introducir los nombres del dependiente!",
            },
            apellido: {
                required: "Por favor introducir los apellidos del dependiente!",
            },
            correo: {
                required: "Por favor introducir el correo del dependiente!",
            },
            estado: {
                required: "Por favor introducir el estado del dependiente!",
            },
        },
        highlight: function(element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        submitHandler: function(form) {
            senddata();
        }
    });
    //jquery validate y regexp
});

function reload1() {
    location.href = 'admin_dependientes.php';
}

function senddata() {
    var process = $('#process').val();
    var nombre = $("#nombre").val();
    var apellido = $("#apellido").val();
    var correo = $("#correo").val();
    var estado = $("#estado").val();

    var urlprocess = "";
    var id_dependiente = 0;
    if (process == 'insert') {
        urlprocess = 'agregar_dependiente.php';
    }
    if (process == 'edited') {
        urlprocess = 'editar_dependiente.php';
        id_dependiente = $("#id_dependiente").val();
    }
    var dataString = 'process=' + process + '&id_dependiente=' + id_dependiente;
    dataString += "&apellido=" + apellido;
    dataString += "&correo=" + correo;
    dataString += "&nombre=" + nombre;
    dataString += "&estado=" + estado;
    $.ajax({
        type: 'POST',
        url: urlprocess,
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                setInterval("reload1();", 1500);
            }
        }
    });
}


function deleted() {

    var id_dependiente = $('#id_dependiente').val();
    var dataString = 'process=deleted' + '&id_dependiente=' + id_dependiente;
    $.ajax({
        type: "POST",
        url: "borrar_dependiente.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                setInterval("reload1();", 1500);
                $('#deleteModal').hide();
            }
        }
    });
}
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