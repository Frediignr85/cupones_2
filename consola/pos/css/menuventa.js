var urlprocess='menuventa.php';
var scrolled = 0;
$(document).ready(function() {

  /* View in fullscreen */
  /*
  if (screenfull.enabled) {
		screenfull.request();
	}
  $( "body" ).click(function( event ) {
    if (screenfull.enabled) {
  		screenfull.request();
  	}
  }); */
  $(document).on('click', '.categori', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    //obtengo el valor  de cada input  dentro del div
    var valor =  $('#id_cate').val();
    var valor = $(this).closest("div").find("#id_cate").val();
    sel_by_cat(valor)
  });
  sel_by_cat();

  $(".i-checks").iCheck({
    checkboxClass: "icheckbox_square-green",
    radioClass: "iradio_square-green",
  });


});

//function para scroll
  $(document).on('click', '#down', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
      $('.scrolltable').scroll(function() {
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                scrolled=-200;
      }

    });
    scrolled=scrolled+200;
    $(".scrolltable").animate({
           scrollTop:  scrolled
     });
   });

   $(document).on('click', '#up', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
      scrolled=scrolled-200;
      if (scrolled<0){
         scrolled=0;
      }
      $(".scrolltable").animate({
              scrollTop:  scrolled
      });


    });

//virtual keyboard set
$('#efectivo').keyboard({
  openOn   : null,
  stayOpen : true,
  layout   : 'num',
  accepted : function(event, keyboard, el) {
  total_efectivo();
 }
});
$('#efectivo').click(function(){
  var kb = $('#efectivo').getkeyboard();
  // close the keyboard if the keyboard is visible and the button is clicked a second time
  if ( kb.isOpen ) {
    kb.close();
  } else {
    kb.reveal();
  }
    total_efectivo();
});
function cuenta_caracter(str) {
  var coincidencia=".";
  var regex = new RegExp("[^"+ coincidencia +"]","g");
  count = str.replace(regex, "").length
  return count;
}
//obtener subtotal cantidad x precio
function subt(qty,price){
  subtotal=parseFloat(qty)*parseFloat(price);
  subtotal=round( subtotal,2);
  return subtotal;
}
$(function(){
  // Clean the modal form
  $(document).on('hidden.bs.modal', function(e) {
    var target = $(e.target);
    target.removeData('bs.modal').find(".modal-content").html('');
  });
});
//seleccionar producto y agregarlo a la orden de compra
$(document).on('click', '.btnSelProd', function(e) {
  e.preventDefault();
    e.stopImmediatePropagation();
    var id_prod= $(this).find(".prod").val();
    addProductList(id_prod,1);
    totales();
});
// Evento que selecciona la fila y la elimina de la tabla
$(document).on("click", ".Delete", function() {
  var parent = $(this).parents().get(0);
  var tr = $(this).parents("tr");
  var id_prod = tr.find('td:eq(0)').find("#id_producto_base").val();
  var fila=tr.find('td:eq(0)').text();
  var fila_id_base=fila+"-"+id_prod;
  //tengo la fila y el id que voy eliminar, revisar en toda la tabla si hay extras de este prod y eliminar
  $("#inventable>tbody tr").each(function(index) {
    if (index>=0) {
      fila_prod=$(this).find('td:eq(0)').find('.prod_origen').val();
      if(fila_id_base==fila_prod){
        $(this).remove();
      }
    } //if index>=0
    });
    $(parent).remove();
    totales();
});
// Evento que selecciona la fila y le pone precio cero, por ser regalia
$(document).on('ifChecked','.chkRegalias', function(event){
  var parent = $(this).parents().get(0);
  var tr = $(this).parents("tr");
  var id_prod = tr.find('td:eq(0)').find("#id_producto_base").val();
  var fila=tr.find('td:eq(0)').text();
  var fila_id_base=fila+"-"+id_prod;
  tr.find('td:eq(3)').text('0.0');
  ////tengo la fila y el id  revisar en toda la tabla si hay extras de este prod y poner precio cero
  $("#inventable>tbody tr").each(function(index) {
    if (index>=0) {
      fila_prod=$(this).find('td:eq(0)').find('.prod_origen').val();
      if(fila_id_base==fila_prod){
        $(this).find('td:eq(3)').text(0);
        $(this).find('td:eq(5)').find('#chk_Regalia').iCheck('check');
      }
    } //if index>=0
    });
    totales();
});
$(document).on('ifUnchecked','.chkRegalias', function(event){
  var parent = $(this).parents().get(0);
  var tr = $(this).parents("tr");
  var id_prod = tr.find('td:eq(0)').find("#id_producto_base").val();
  var precio_origen= tr.find('td:eq(0)').find("#precio_origen").val();
  var fila=tr.find('td:eq(0)').text();
  var fila_id_base=fila+"-"+id_prod;
  tr.find('td:eq(3)').text(precio_origen);
  //tengo la fila y el id  revisar en toda la tabla si hay extras de este prod y poner precio original
  $("#inventable>tbody tr").each(function(index) {
    if (index>=0) {
      fila_prod=$(this).find('td:eq(0)').find('.prod_origen').val();
      var precio_origen= $(this).find('td:eq(0)').find("#precio_origen").val();
      if(fila_id_base==fila_prod){
        $(this).find('td:eq(3)').text(precio_origen);
      }
    } //if index>=0
    });
    totales();
});

//seleccionar topping y agregarlo a un item
$(document).on('click', '.btnSelTopp', function(e) {
  e.preventDefault();
    e.stopImmediatePropagation();
  var tr = $(this).parents("tr");
  var id_prod = tr.find('td:eq(0)').text();
  var fila_id_noextra = tr.find('td:eq(0)').find('.prodnoextra').val();
    id_prod =parseInt(id_prod)
    addProductList(id_prod,1,fila_id_noextra,true);
    totales();
});
//restar cantidades de producto que  NO lleva extras
$(document).on('click', '.RestarCant', function(e) {
  e.preventDefault();
    e.stopImmediatePropagation();
  var tr = $(this).parents("tr");
  var id_prod = tr.find('td:eq(0)').text();
  var cantidad_nueva =0;
  var precio=tr.find('td:eq(4)').find("#precio_origen").val();
  var cantidad_anterior=parseInt(tr.find('td:eq(2)').text());
  if(cantidad_anterior>1){
     cantidad_nueva = cantidad_anterior-1 ;
  }
    else {
       cantidad_nueva = 1 ;
    }
  var subtotal= subt(cantidad_nueva,precio);
  btnMas='<button type="button" id="btnSumarCant" class="btn-sm btn-info SumarCant"><i class="icon-square-plus"></i> </button>';
  btnMenos='<button type="button" id="btnRestarCant" class="btn-sm btn-warning RestarCant" >	<i class="icon-square-minus"></i> </button>';
  subt_mostrar=subtotal.toFixed(2);
  lc=cantidad_nueva.toString()
  var longcant=lc.length
  space=agregar_espacio(longcant);
  tr.find('td:eq(2)').html(btnMenos+"&nbsp;&nbsp;<strong>"+cantidad_nueva+"</strong>"+space+btnMas);
  tr.find('td:eq(3)').text(subt_mostrar);
  totales();
});
//agregar espacios en blanco
function agregar_espacio(longcant){
    var space1='&nbsp;'.repeat(1);
  if(longcant==1){
    space1='&nbsp;'.repeat(2);
  }
  if(longcant==2){
    space1='&nbsp;'.repeat(1);
  }
  if(longcant==3){
    space1='&nbsp;'.repeat(1);
  }
  return space1;
}
//Sumar cantidades de producto que  NO lleva extras
$(document).on('click', '.SumarCant', function(e) {
  e.preventDefault();
    e.stopImmediatePropagation();
  var tr = $(this).parents("tr");
  var cantidad_nueva =0;
  var precio=tr.find('td:eq(4)').find("#precio_origen").val();
  var cantidad_anterior=parseInt(tr.find('td:eq(2)').text());
  if(cantidad_anterior>=1){
     cantidad_nueva = cantidad_anterior+1 ;
  }
    else {
       cantidad_nueva = 1 ;
    }
  var subtotal= subt(cantidad_nueva,precio);

  btnMas='<button type="button" id="btnSumarCant" class="btn-sm btn-info SumarCant"><i class="icon-square-plus"></i> </button>';
  btnMenos='<button type="button" id="btnRestarCant" class="btn-sm btn-warning RestarCant"><i class="icon-square-minus"></i> </button>';
  subt_mostrar=subtotal.toFixed(2);
  lc=cantidad_nueva.toString()
  var longcant=lc.length
  space=agregar_espacio(longcant);
  tr.find('td:eq(2)').html(btnMenos+"&nbsp;&nbsp;<strong>"+cantidad_nueva+"</strong>"+space+btnMas);
  tr.find('td:eq(3)').text(subt_mostrar);
  totales();
});
function sel_by_cat(id_cate) {
  id_cate = id_cate ? id_cate : '';
  $.ajax({
    type: 'POST',
    url: urlprocess,
    data: {
      process: 'mostrar_prodcat',
      id_cat: id_cate,
    },
    success: function(datos) {
      $('#mostrardatos').html(datos);
    }
  });
  //scrolltable();
}
// Agregar productos a la orden
function addProductList(id_prod,cantidad,fila_id_noextra='',xtra=false){
  var filas = 1;
  var id_previo = new Array();
  cantidad=parseInt(cantidad)
  $("#inventable>tbody tr").each(function(index) {
    if (index>=0) {
      id_prod0=$(this).find("td:eq(0)").find("#id_producto_base").val()
      //id_prod1=id_prod0.split("-");
      id_previo.push(id_prod0);
      if(!xtra)
      {
        filas = filas + 1;
      }
    } //if index>=0
  });
 //fila & id del producto que invoca Toppings
 val_noextra=fila_id_noextra.split('-');
 fila_noextra=val_noextra[0]
 id_noextra=val_noextra[1]
 var dataString = 'process=consultar_prod' + '&id_producto=' + id_prod;
 $.ajax({
    type: "POST",
    url:  urlprocess,
    data: dataString,
    dataType: 'json',
    success: function(data) {
      var descripcion=data.descripcion;
      var precio = data.precio;
      var extra=data.extra;
      var lleva_extra=data.lleva_extra;
      var subtotal= subt(cantidad,precio);
      subt_mostrar=subtotal.toFixed(2);

      var input_prod="<input type='hidden'  class='producto_base' name='id_producto_base'  id='id_producto_base' value='"+id_prod +"'>";
      var input_precio="<input type='hidden'  class='pre_origen' name='precio_origen'  id='precio_origen' value='"+precio +"'>";
      //var btnRegalia='<button type="button" id="btnRegalia" class="btn-sm btn-info Regalia"><i class="icon-square-check"></i> </button>';
      var btnRegalia="<div class='i-checks chkRegalias'><label><input id='chk_Regalia' name='chk_Regalia' type='checkbox' value='"+id_prod +"'> <i></i></label></div>";
      if(extra==1){
        btnMas='';
        btnMenos='';
        btnMas='<button type="button" id="btnSumarCant" class="btn-sm btn-info SumarCant" >	<i class="icon-square-plus"></i></button>';
        btnMenos='<button type="button" id="btnRestarCant" class="btn-sm btn-warning RestarCant"><i class="icon-square-minus"></i></button>';

        btnSelect= "<td class='td_green' style='width:10%'>" +input_precio+"&nbsp;"+ '</td>';
        var prod_origen="<input type='hidden'  class='prod_origen' name='id_prod_origen'  id='id_prod_origen' value='"+fila_id_noextra +"'>";
        td_descrip="<td class='td_blue texto_peq' style='width:20%'>"+'&nbsp;-'+descripcion+ '</td>';

      }
      if(extra==0 && lleva_extra==0){
        fila_id_prod=-1;
        btnSelect="<td style='width:10%'>"+input_precio+'&nbsp;' +'-'+'</td>';
        btnMas='<button type="button" id="btnSumarCant" class="btn-sm btn-info SumarCant" >	<i class="icon-square-plus"></i></button>';
        btnMenos='<button type="button" id="btnRestarCant" class="btn-sm btn-warning RestarCant"><i class="icon-square-minus"></i></button>';
        td_descrip="<td class'td_red texto_peq' style='width:20%'>"+descripcion+ '</td>';
        var prod_origen="<input type='hidden'  class='prod_origen' name='id_prod_origen'  id='id_prod_origen'value='"+fila_id_prod +"'>";

      }
      if(extra==0 && lleva_extra==1){
        fila_id_prod=-1;
        btnMas='';
        btnMenos='';
        btnSelect="<td style='width:10%'>"+input_precio+'&nbsp;';
        btnSelect+='<button type="button" id="btnSelect" class="btn-sm btn-success" data-target="#viewModal1" data-toggle="modal" data-refresh="true" href="consultar_stock.php?id='+id_prod+'&fila='+filas+'">	<i class="icon-square-plus"></i> </button></td>';
        td_descrip="<td class='td_green texto_peq' style='width:20%'>"+descripcion+ '</td>';
        var prod_origen="<input type='hidden'  class='prod_origen' name='id_prod_origen'  id='id_prod_origen'value='"+fila_id_prod +"'>";

      }

      lc=cantidad.toString()
      var longcant=lc.length
      space=agregar_espacio(longcant);

      btnDelete='<button type="button" id="btndelprod" class="btn-sm btn-danger">	<i class="icon-trash"></i> </button>';
      tr_add = '';
      tr_add += "<tr  class='tr1' id='"+filas+"'>";
      if(!xtra)
      {
        tr_add += "<td  class='col1 td1' style='width: 6%'>"+prod_origen+input_precio+input_prod+filas+ '</td>';
      }
      else
      {
        tr_add += "<td  class='col1 td1' style='width: 6%'>"+prod_origen+input_precio+input_prod+ '</td>';
      }
      tr_add += td_descrip;
      tr_add += "<td class='texto_med col1 tdCant' id='cantidad' style='width: 24%;'>"+btnMenos +'&nbsp;'+cantidad  + '&nbsp;'+  btnMas+ "</td>";
      tr_add += "<td class='texto_med col1 td1' id='subtotal' style='width: 14%' >" + subt_mostrar + "</td>";

      tr_add += btnSelect;
      tr_add += "<td class='Regalias col8 td1' style='width: 12%; display:inline;'>"+ btnRegalia+'</td>';
      tr_add += "<td class='Delete col8 td1'   style='width: 12%'  >"+ btnDelete+'</td>';
      tr_add += '</tr>';

      var existe = false;
      var posicion_fila = 0;
      $.each(id_previo, function(i, id_prod_ant) {
        if (id_prod == id_prod_ant) {
          existe = true;
          if(!xtra)
          {
            posicion_fila = i+1;
          }
        }
      });
      if (existe == false && lleva_extra==1 && extra==0){
        $("#inventable").append(tr_add);
      }
      if (existe == true && lleva_extra==1 && extra==0){
        $("#inventable").append(tr_add);
      }
      if (existe == false && lleva_extra==0  && extra==0){
          $("#inventable").append(tr_add);
      }
      if (existe == true && lleva_extra==0 && extra==0){
        setRowCant(posicion_fila,precio,lleva_extra);
      }
      if (existe == true && lleva_extra==0 && extra==1){
         $("#"+fila_noextra).after(tr_add);
      }
      if (existe == false && lleva_extra==0 && extra==1){
         $("#"+fila_noextra).after(tr_add);
      }
      $(".i-checks").iCheck({
     checkboxClass: "icheckbox_square-green",
         radioClass: "iradio_square-green",
      });
      totales();
    }
  });
}

// Sumar cantidades a filas que no lleva extra
function setRowCant(rowId,precio,lleva_extra){

  var cantidad_anterior =parseFloat($('#inventable tr:nth-child(' + rowId + ')').find('td:eq(2)').text());
  var cantidad_nueva = cantidad_anterior+1 ;
  var subtotal= subt(cantidad_nueva,precio);
  lc=cantidad_nueva.toString()
  var longcant=lc.length
  space=agregar_espacio(longcant);
  btnMas='<button type="button" id="btnSumarCant" class="btn-sm btn-info SumarCant"><i class="icon-square-plus"></i></button>';
  btnMenos='<button type="button" id="btnRestarCant" class="btn-sm btn-warning RestarCant" >	<i class="icon-square-minus"></i> </button>';

  subt_mostrar=subtotal.toFixed(2);
  $('#inventable tr:nth-child(' + rowId + ')').find('td:eq(2)').html(btnMenos+"&nbsp;<strong>"+cantidad_nueva+"</strong>"+space+btnMas);
  $('#inventable tr:nth-child(' + rowId + ')').find('td:eq(3)').text(subt_mostrar);
  totales();
}

function totales(){
  var subtotal = 0;
  var total = 0;
  var subt_cant=0;
  var totalcantidad = 0;
  var cantidad = 0;
  var total_dinero = 0;
  var filas=0;
  $("#inventable>tbody tr").each(function(index) {
    if (index >=0){
      subt_cant=$(this).find("td:eq(2)").text()
      subtotal=$(this).find("td:eq(3)").text()
      total+=parseFloat(subtotal);
      totalcantidad +=parseFloat(subt_cant);
    }
  });
  total=round(total,2);
  total_mostrar=total.toFixed(2)
  totcant_mostrar=totalcantidad.toFixed(2)
  $('#totcant').text(totcant_mostrar);
  $('#totfin').text(total_mostrar);
  $('#totalventa').val(total_mostrar);
}
//Fin busqueda por la caja de texto solo para barcode
  //$("#submit1").one("click", function(e) {
  $("#submit1").on("click", function(e) {
    if ($('#totcant').text()!=0.00){
      e.preventDefault();
      senddata();
    //  $("#inventable").find("tr:gt(0)").remove();

    }
    else{
      var typeinfo = 'Error';
      var msg = 'Registrar productos para venta!';
     display_notify(typeinfo, msg);
    //alert(msg)
    }
  });
  $("#BtnClear").on("click", function(e) {
    var typeinfo = 'Success';
    var msg = 'Limpiando Datos de Orden!';
    display_notify(typeinfo, msg);
    $("#inventable").find("tr:gt(0)").remove();
    $('#totcant').html("0");
    $('#totfin').html("0.00");
  });
function senddata(){
  var array_json = new Array();
  var subtotal = 0;
  var total = 0;
  var subt_cant=0;
  var totalcantidad = 0;
  var cantidad = 0;
  var total_dinero = 0;
  var fila=0;

  $("#inventable>tbody tr").each(function(index) {
    if (index >=0){
      id_prod=$(this).find("td:eq(0)").find('#id_producto_base').val();
      fila=$(this).find("td:eq(0)").text();
      id_prod_superior=$(this).find("td:eq(0)").find('#id_prod_origen').val();
      precio  = $(this).find("td:eq(4)").find("#precio_origen").val()
      cantidad=parseInt($(this).find("td:eq(2)").text());
      subtotal= $(this).find("td:eq(3)").text();
      var regalia=0;
      if($(this).find('#chk_Regalia').is(':checked') ){
        regalia=1;
      }


      var obj = new Object();
      obj.id =id_prod;
      obj.fila_orden =fila;
      obj.id_prod_superior =  id_prod_superior;
      obj.precio  =precio;
      obj.cantidad = cantidad ;
      obj.regalia  =regalia;
      obj.subtotal  = subtotal;
      //convert object to json string
      text=JSON.stringify(obj);
      array_json.push(text);
      fila = fila + 1;
    }
  });


  json_arr = '['+array_json+']';
  //console.log(json_arr);
  total=$('#totfin').text();
  cuantos=$('#totcant').text();
  urlprocess = urlprocess;
  dataString={
    process: 'guardar_orden',
    cuantos:cuantos,
      total:total,
    datos:json_arr
  };
  var mesa=$('#id_mesa option:selected').val();
  var efectivo = parseFloat($('#efectivo').val());
  if (!mesa || mesa == '-1') {
    sel1 = 0;
  } else {
    sel1 = 1;
  }
  if (!efectivo || efectivo == '' ||  efectivo <= 0) {
    sel2 = 0;
  } else {
    sel2 = 1;
  }
  if (sel1 == 0) {
    msg = 'Falta  seleccionar mesa !';
  }
  if (sel2 == 0 ) {
     msg = 'Falta digitar dinero a pagar!';
  }
    if (sel1 == 1 && sel2 == 1) {
      $.ajax({
        type: 'POST',
        url: urlprocess,
        data: dataString,
        dataType: 'json',
        success: function(datax) {
          factura = datax.factura;
          numero_doc=datax.numero_doc;
          imprime1(factura,numero_doc);
          $('totcant').text('0.00');
        }
      });
     }
     else{
       var typeinfo = 'Warning';
       display_notify(typeinfo, msg);
     }
}
$(document).on('click', '#btnPrint', function(e) {
  e.preventDefault();
    e.stopImmediatePropagation();
  imprime1();
});
function activa_modal(numfact,numdoc){
  $('#viewModal2').modal({backdrop: 'static',keyboard: false});
  $('#fact_num').val(numfact);
  var totalfinal=round(parseFloat($('#totalventa').val()),2);
  var facturado=totalfinal.toFixed(2);
  $("#facturado").val(facturado);
}

function imprime1(factura,numdoc){

  var efectivo = parseFloat($('#efectivo').val());
  var cambio= parseFloat($('#cambio').val());
  var id_mesa=$('#id_mesa option:selected').val();
  var mesa=$('#id_mesa option:selected').text();
  var print = 'imprimir_fact';
  var dataString = 'process=' + print+'&mesa=' +id_mesa
      dataString+= '&tipo_impresion=TIK'+  '&num_doc_fact=' + factura
  $.ajax({
    type: 'POST',
    url:urlprocess,
    data: dataString,
    dataType: 'json',
    success: function(datos) {
			var sist_ope = datos.sist_ope;
      var dir_print=datos.dir_print;
      var shared_printer_win=datos.shared_printer_win;
			var shared_printer_pos=datos.shared_printer_pos;
      var headers=datos.headers;
      var footers=datos.footers;
      //estas opciones son para generar ticket en  printer local y validar si es win o linux
      $('#efectivo').val("")
      if (sist_ope == 'win') {
                $.post("http://"+dir_print+"printposwin1.php", {
      						datosventa: datos.facturar,
      						efectivo: efectivo,
      						cambio: cambio,
                  mesa:mesa,
      						shared_printer_pos:shared_printer_pos,
                  headers:headers,
                  footers:footers,
                })
        } else {
          //  alert ("imprimir")
                $.post("http://"+dir_print+"printpos1.php", {
                  datosventa: datos.facturar,
                  efectivo: efectivo,
                  cambio: cambio,
                  mesa:mesa,
                  headers:headers,
                  footers:footers,
                });
              }
              $("#inventable").find("tr:gt(0)").remove();
              $('#totcant').html("0");
              $('#totfin').html("0");
              $('#viewModal2').hide();
              if(datos.info_remoto!=-1){
                $.post("http://"+dir_print+"print_remote1.php", {
                  info_remoto: datos.info_remoto,
                  mesa:mesa,
                })
              }
              waiting(300);
    }
  });
}
function waiting(time1){
	setTimeout(function(time1) {
	for (i = 0; i < time1; i++){text="abc";};
		reload1();
	}, 200);
}

function reload1(){
    //urlprocess = "menuventa2.php";
  location.href = urlprocess;
}
// Clean the modal form
	$(document).on('hidden.bs.modal', function(e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});
  function total_efectivo() {
      //var pagar=$('#totfin').text();
    var efectivo = parseFloat($('#efectivo').val());
    var totalfinal = parseFloat($('#totfin').text());

    if (isNaN(parseFloat(efectivo))) {
      efectivo = 0;
    }
    if (isNaN(parseFloat(totalfinal))) {
      totalfinal = 0;
    }
    var cambio = efectivo - totalfinal;
    var cambio = round(cambio, 2);
    var cambio_mostrar = cambio.toFixed(2);

    //$('#cambio').val(cambio_mostrar);
    if ($('#efectivo').val() != '' && efectivo >= totalfinal) {
      $('#cambio').val(cambio_mostrar);
    //  $('#mensajes').text('');
    } else {
      $('#cambio').val('0');
      if (efectivo < totalfinal) {
    //    $('#mensajes').html("<h5 class='text-danger'>" + "Falta dinero !!!" + "</h5>");
      }
    }
  }
