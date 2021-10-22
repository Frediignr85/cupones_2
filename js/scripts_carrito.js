$(document).ready(function() {
    actualizar_tabla();
    $("#page-top").addClass("mini-navbar");
});

$(document).ready(function() {
    $('#agregar_compra').validate({
        rules: {
            nombre: {
                required: true,
            },
            tarjeta: {
                required: true,
            },
            numero_tarjeta: {
                required: true,
            },
            ccv: {
                required: true,
            },
            fecha_vencimiento: {
                required: true,
            },
        },
        messages: {
            nombre: {
                required: "Por favor introducir el nombre del propietario de la tarjeta!",
            },
            tarjeta: {
                required: "Por favor seleccionar la tarjeta!",
            },
            numero_tarjeta: {
                required: "Por favor ingrese el numero de tarjeta!",
            },
            ccv: {
                required: "Por favor ingrese el ccv!",
            },
            fecha_vencimiento: {
                required: "Por favor ingrese la fecha de vencimiento de la tarjeta!",
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


$(function() {
    //binding event click for button in modal form
    $(document).on("change", ".cantidad_oferta", function(event) {

        let id_carrito_detalle = $(this).attr("id");
        let cantidad_actual = $(this).val();
        if (actualizar_item(id_carrito_detalle, cantidad_actual)) {
            event.preventDefault();
        }
    });
    $(document).on("click", "#btnPagar", function(event) {
        $("#agregar_compra").show();
    });
    $(document).on("click", "#btnVolver", function(event) {
        location.href = 'index.php';
    });
    $(document).on("click", ".eliminar_carrito_detalle", function(event) {
        let id_carrito_detalle = $(this).attr("id");
        swal({
                title: "Esta seguro que desea eliminar este item?",
                text: "Usted no podra deshacer este cambio",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Si, Eliminar",
                cancelButtonText: "No, Cerrar",
                closeOnConfirm: true
            },
            function() {
                eliminar_item(id_carrito_detalle);
            });

    });

    // Clean the modal form
    $(document).on('hidden.bs.modal', function(e) {
        var target = $(e.target);
        target.removeData('bs.modal').find(".modal-content").html('');
    });

});

function eliminar_item(id_carrito_detalle) {
    var dataString = 'process=eliminar_item' + '&id_carrito_detalle=' + id_carrito_detalle;
    $.ajax({
        type: "POST",
        url: "consola/funciones_compra.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                actualizar_tabla();
            }
        }
    });
}

function actualizar_tabla() {
    var dataString = 'process=actualizar_tabla';
    $.ajax({
        type: "POST",
        url: "consola/funciones_compra.php",
        data: dataString,
        success: function(datax) {
            $("#contenido_tabla").html("");
            $("#contenido_tabla").html(datax);
        }
    });
}

function actualizar_item(id_carrito_detalle, cantidad) {
    var dataString = 'process=actualizar_item' + '&id_carrito_detalle=' + id_carrito_detalle + '&cantidad=' + cantidad;
    $.ajax({
        type: "POST",
        url: "consola/funciones_compra.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            if (datax.typeinfo == "Success") {
                actualizar_tabla();
                return 0;
            } else {
                display_notify(datax.typeinfo, datax.msg);
                return 1;
            }
        }
    });
}

function senddata() {
    var nombre = $("#nombre").val();
    var tarjeta = $("#tarjeta").val();
    var numero_tarjeta = $("#numero_tarjeta").val();
    var ccv = $("#ccv").val();
    var fecha_vencimiento = $("#fecha_vencimiento").val();
    var id_carrito = $("#id_carrito").val();

    var dataString = 'process=realizar_pago';
    dataString += "&nombre=" + nombre;
    dataString += "&tarjeta=" + tarjeta;
    dataString += "&numero_tarjeta=" + numero_tarjeta;
    dataString += "&ccv=" + ccv;
    dataString += "&fecha_vencimiento=" + fecha_vencimiento;
    dataString += "&id_carrito=" + id_carrito;
    $.ajax({
        type: 'POST',
        url: "consola/funciones_compra.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                let id_compra = datax.id_compra;
                window.open("bonos_compra.php?id_compra=" + id_compra + "", '_blank');
                setInterval("reload1();", 1500);
            }
        }
    });
}

function reload1() {
    location.href = 'index.php';
}