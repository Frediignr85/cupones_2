$(document).ready(function() {
    actualizar_carrito();
    moveSlide("next");
    setInterval(function() { moveSlide("next"); }, 5000);
});
/* SELECTORS */
const slides = document.getElementsByClassName("carrousel-item")
let slidePosition = 0
const totalSlides = slides.length

$(function() {
    //binding event click for button in modal form

    $(document).on("click", ".BOTON", function(event) {
        let id_oferta = $(this).attr("id");
        agregar_carrito(id_oferta);
    });

    $(document).on("click", "#btnAgregarCarrito", function(event) {
        var cantidad_ofertas = $("#deleteModal #cantidad_ofertas").val();
        nuevo_item(cantidad_ofertas);
    });

    // Clean the modal form
    $(document).on('hidden.bs.modal', function(e) {
        var target = $(e.target);
        target.removeData('bs.modal').find(".modal-content").html('');
    });

});
/* EVENT LISTENERS */


/* FUNCTIONS */
function hideAllSlides() {
    for (const slide of slides) {
        slide.classList.remove("carrousel-item-visible")
    }
}

function showCurrentSlide() {
    slides[slidePosition].classList.add("carrousel-item-visible")
}

function moveToPreviousPosition() {
    if (slidePosition > 0) {
        slidePosition--
    } else {
        slidePosition = totalSlides - 1
    }
}

function moveToNextPosition() {
    if (slidePosition < totalSlides - 1) {
        slidePosition++
    } else {
        slidePosition = 0
    }
}

function moveSlide(direction) {
    hideAllSlides()

    if (direction === "previous") {
        moveToPreviousPosition()
    } else if (direction === "next") {
        moveToNextPosition()
    }

    showCurrentSlide()
    getDominantColor()
}

function changeBGColor(color) {
    document.body.style.background = `linear-gradient(to bottom right, rgb(15, 15, 15) 50%, rgb(${color[0]}, ${color[1]}, ${color[2]}))`
}

function getDominantColor() {
    const img = document.querySelector('.carrousel-item-visible .carrousel-item-img');
}



/* ACA EMPIEZAN LAS FUNCIONES DE LA PAGINACION */
(function($) {
    var pagify = {
        items: {},
        container: null,
        totalPages: 1,
        perPage: 3,
        currentPage: 0,
        createNavigation: function() {
            this.totalPages = Math.ceil(this.items.length / this.perPage);

            $('.pagination', this.container.parent()).remove();
            var pagination = $('<div class="pagination"></div>').append('<a class="nav prev disabled navegador_listado" data-next="false"><</a>');

            for (var i = 0; i < this.totalPages; i++) {
                var pageElClass = "page";
                if (!i)
                    pageElClass = "page current";
                var pageEl = '<a class="' + pageElClass + '" data-page="' + (
                    i + 1) + '">' + (
                    i + 1) + "</a>";
                pagination.append(pageEl);
            }
            pagination.append('<a class="nav next navegador_listado" data-next="true">></a>');

            this.container.after(pagination);

            var that = this;
            $("body").off("click", ".nav");
            this.navigator = $("body").on("click", ".nav", function() {
                var el = $(this);
                that.navigate(el.data("next"));
            });

            $("body").off("click", ".page");
            this.pageNavigator = $("body").on("click", ".page", function() {
                var el = $(this);
                that.goToPage(el.data("page"));
            });
        },
        navigate: function(next) {
            // default perPage to 5
            if (isNaN(next) || next === undefined) {
                next = true;
            }
            $(".pagination .nav").removeClass("disabled");
            if (next) {
                this.currentPage++;
                if (this.currentPage > (this.totalPages - 1))
                    this.currentPage = (this.totalPages - 1);
                if (this.currentPage == (this.totalPages - 1))
                    $(".pagination .nav.next").addClass("disabled");
            } else {
                this.currentPage--;
                if (this.currentPage < 0)
                    this.currentPage = 0;
                if (this.currentPage == 0)
                    $(".pagination .nav.prev").addClass("disabled");
            }

            this.showItems();
        },
        updateNavigation: function() {

            var pages = $(".pagination .page");
            pages.removeClass("current");
            $('.pagination .page[data-page="' + (
                this.currentPage + 1) + '"]').addClass("current");
        },
        goToPage: function(page) {

            this.currentPage = page - 1;

            $(".pagination .nav").removeClass("disabled");
            if (this.currentPage == (this.totalPages - 1))
                $(".pagination .nav.next").addClass("disabled");

            if (this.currentPage == 0)
                $(".pagination .nav.prev").addClass("disabled");
            this.showItems();
        },
        showItems: function() {
            this.items.hide();
            var base = this.perPage * this.currentPage;
            this.items.slice(base, base + this.perPage).show();

            this.updateNavigation();
        },
        init: function(container, items, perPage) {
            this.container = container;
            this.currentPage = 0;
            this.totalPages = 1;
            this.perPage = perPage;
            this.items = items;
            this.createNavigation();
            this.showItems();
        }
    };

    // stuff it all into a jQuery method!
    $.fn.pagify = function(perPage, itemSelector) {
        var el = $(this);
        var items = $(itemSelector, el);

        // default perPage to 5
        if (isNaN(perPage) || perPage === undefined) {
            perPage = 3;
        }

        // don't fire if fewer items than perPage
        if (items.length <= perPage) {
            return true;
        }

        pagify.init(el, items, perPage);
    };

    $(document).on('hidden.bs.modal', function(e) {
        var target = $(e.target);
        target.removeData('bs.modal').find(".modal-content").html('');
    });
})(jQuery);

$(".contenedor_ofertas").pagify(6, ".single-item");



function agregar_carrito(id_oferta) {
    $('.modal-content').load('consola/agregar_carrito.php?id_oferta=' + id_oferta, function() {
        $('#deleteModal').modal({ show: true });
    });
}


function actualizar_carrito() {
    var dataString = 'process=ver_cantidad_carrito';
    $.ajax({
        type: "POST",
        url: "consola/funciones_compra.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            let cantidad = datax.cantidad;
            $("#cart_menu_num").text(cantidad);
        }
    });
}

function nuevo_item(cantidad_ofertas) {
    var id_oferta = $('#id_oferta').val();
    var dataString = 'process=nuevo_item' + '&id_oferta=' + id_oferta + "&cantidad_ofertas=" + cantidad_ofertas;
    $.ajax({
        type: "POST",
        url: "consola/funciones_compra.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                actualizar_carrito();
                $('#deleteModal').hide();
                setInterval("location.reload();", 1500);
            }
        }
    });
}