$(function() {
    $(document).on("click", "#btnCanjear", function(event) {
        let codigo_cupon = $("#codigo_cupon").val();
        comprobar_codigo(codigo_cupon);
    });
    $(document).on("click", "#canjear", function(event) {
        let codigo_cupon = $("#codigo_cupon_hidden").val();
        canjear_codigo(codigo_cupon);
    });
    $(document).on("click", "#limpiar", function(event) {
        $("#contenido_tabla_canje").html("");
        $("#codigo_cupon").val("");
    });
    $(document).on('hidden.bs.modal', function(e) {
        var target = $(e.target);
        target.removeData('bs.modal').find(".modal-content").html('');
    });

});

function comprobar_codigo(codigo_cupon) {
    var dataString = 'process=verificar_codigo' + '&codigo_cupon=' + codigo_cupon;
    $.ajax({
        type: "POST",
        url: "funciones_compra.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            $("#contenido_tabla_canje").html("");
            if (datax.typeinfo == "Success") {
                $("#contenido_tabla_canje").html(datax.tabla);
            }
        }
    });
}

function canjear_codigo(codigo_cupon) {
    var dataString = 'process=canjear_codigo' + '&codigo_cupon=' + codigo_cupon;
    $.ajax({
        type: "POST",
        url: "funciones_compra.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                $("#contenido_tabla_canje").html("");
                $("#codigo_cupon").val("");
            }
        }
    });
}