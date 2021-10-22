$(document).ready(function() {
    $.validator.addMethod('regexp', function(value, element, param) {
        return this.optional(element) || value.match(param);
    }, 'Mensaje a mostrar si se incumple la condición');

    $('#formulario_oferta').validate({
        rules: {
            titulo: {
                required: true,
            },
            descripcion: {
                required: true,
            },
            precio_regular: {
                required: true,
            },
            precio_oferta: {
                required: true,
            },
            fecha_inicio: {
                required: true,
            },
            fecha_fin: {
                required: true,
            },
            fecha_limite_cupon: {
                required: true,
            },
            estado: {
                required: true,
            },
        },
        messages: {
            titulo: {
                required: "Por favor introducir el titulo de la oferta",
            },
            descripcion: {
                required: "Por favor introducir la descripcion de la oferta",
            },
            precio_regular: {
                required: "Por favor introducir el precio regular de la oferta",
            },
            precio_oferta: {
                required: "Por favor introducir el precio en promocion de la oferta",
            },
            fecha_inicio: {
                required: "Por favor introducir la fecha de inicio de la oferta",
            },
            fecha_fin: {
                required: "Por favor introducir la fecha de finalizacion de la oferta",
            },
            fecha_limite_cupon: {
                required: "Por favor introducir la fecha limite para canje de cupones de la oferta",
            },
            estado: {
                required: "Por favor introducir el estado de la oferta",
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

    $('#ilimitado').on('ifChecked', function(event) {
        $('.i-checks').iCheck('check');
        $('#ilimitado_value').val("1");
    });
    $('#ilimitado').on('ifUnchecked', function(event) {
        $('.i-checks').iCheck('uncheck');
        $('#ilimitado_value').val("0");
    });


});

function reload1() {
    location.href = 'dashboard.php';
}

function senddata() {
    var process = $('#process').val();
    var titulo = $("#titulo").val();
    var descripcion = $("#descripcion").val();
    var precio_regular = $("#precio_regular").val();
    var precio_oferta = $("#precio_oferta").val();
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var fecha_limite_cupon = $("#fecha_limite_cupon").val();
    var cantidad_limite_cupones = $("#cantidad_limite_cupones").val();
    var ilimitado_value = $("#ilimitado_value").val();
    var detalles = $("#detalles").val();
    var estado = $("#estado").val();

    var urlprocess = "";
    var id_oferta = 0;
    if (process == 'insert') {
        urlprocess = 'agregar_oferta.php';
    }
    if (process == 'edited') {
        urlprocess = 'editar_oferta.php';
        id_oferta = $("#id_oferta").val();
    }
    var dataString = 'process=' + process + '&id_oferta=' + id_oferta;
    dataString += "&precio_regular=" + precio_regular;
    dataString += "&precio_oferta=" + precio_oferta;
    dataString += "&fecha_inicio=" + fecha_inicio;
    dataString += "&fecha_fin=" + fecha_fin;
    dataString += "&fecha_limite_cupon=" + fecha_limite_cupon;
    dataString += "&descripcion=" + descripcion;
    dataString += "&cantidad_limite_cupones=" + cantidad_limite_cupones;
    dataString += "&estado=" + estado;
    dataString += "&titulo=" + titulo;
    dataString += "&ilimitado_value=" + ilimitado_value;
    dataString += "&detalles=" + detalles;
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

    var id_oferta = $('#id_oferta').val();
    var dataString = 'process=deleted' + '&id_oferta=' + id_oferta;
    $.ajax({
        type: "POST",
        url: "borrar_oferta.php",
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

function aprobar() {
    var id_oferta = $('#id_oferta').val();
    var dataString = 'process=aprobar' + '&id_oferta=' + id_oferta;
    $.ajax({
        type: "POST",
        url: "aprobar_oferta.php",
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

function rechazar() {
    var id_oferta = $('#id_oferta').val();
    var motivo_rechazo = $("#motivo_rechazo").val();
    var dataString = 'process=rechazar' + '&id_oferta=' + id_oferta + "&motivo_rechazo=" + motivo_rechazo;
    $.ajax({
        type: "POST",
        url: "rechazar_oferta.php",
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

function descartar() {
    var id_oferta = $('#id_oferta').val();
    var motivo_descarte = $("#motivo_descarte").val();
    var dataString = 'process=descartar' + '&id_oferta=' + id_oferta + "&motivo_descarte=" + motivo_descarte;
    $.ajax({
        type: "POST",
        url: "descartar_oferta.php",
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

function responder() {
    var id_oferta = $('#id_oferta').val();
    var respuesta_motivo = $("#respuesta_motivo").val();
    var dataString = 'process=responder' + '&id_oferta=' + id_oferta + "&respuesta_motivo=" + respuesta_motivo;
    $.ajax({
        type: "POST",
        url: "responder_motivo.php",
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

function recuperar() {
    var id_oferta = $('#id_oferta').val();
    var dataString = 'process=recuperar' + '&id_oferta=' + id_oferta;
    $.ajax({
        type: "POST",
        url: "recuperar_oferta.php",
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
    $(document).on("click", "#btnAprobar", function(event) {
        aprobar();
    });
    $(document).on("click", "#btnRechazar", function(event) {
        rechazar();
    });
    $(document).on("click", "#btnDescartar", function(event) {
        descartar();
    });
    $(document).on("click", "#btnResponder", function(event) {
        responder();
    });
    $(document).on("click", "#btnRecuperar", function(event) {
        recuperar();
    });
    // Clean the modal form
    $(document).on('hidden.bs.modal', function(e) {
        var target = $(e.target);
        target.removeData('bs.modal').find(".modal-content").html('');
    });

});