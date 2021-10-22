$(document).ready(function() {
    $.validator.addMethod('regexp', function(value, element, param) {
        return this.optional(element) || value.match(param);
    }, 'Mensaje a mostrar si se incumple la condición');

    $('#formulario_rubro').validate({
        rules: {
            nombre: {
                required: true,
            },
            estado: {
                required: true,
            },
        },
        messages: {
            nombre: {
                required: "Por favor introducir el nombre del rubro!",
            },
            estado: {
                required: "Por favor seleccionar el estado del rubbro!",
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
    location.href = 'admin_rubros.php';
}

function senddata() {
    var process = $('#process').val();
    var nombre = $("#nombre").val();
    var estado = $("#estado").val();

    var urlprocess = "";
    var id_rubro = 0;
    if (process == 'insert') {
        urlprocess = 'agregar_rubro.php';
    }
    if (process == 'edited') {
        urlprocess = 'editar_rubro.php';
        id_rubro = $("#id_rubro").val();
    }
    var dataString = 'process=' + process + '&id_rubro=' + id_rubro;
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

    var id_rubro = $('#id_rubro').val();
    var dataString = 'process=deleted' + '&id_rubro=' + id_rubro;
    $.ajax({
        type: "POST",
        url: "borrar_rubro.php",
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