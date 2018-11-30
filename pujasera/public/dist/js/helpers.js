

var AJAX_LOAD = true;

function str_random(length) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < length; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function show_notif(type, message, title, icon) {

    var icon_notif;
    switch(type) {
        case 'danger':
            icon_notif = 'fa fa-exclamation-triangle';
            break;
        case 2:
            icon_notif = 'fa fa-exclamation-triangle';
            type = 'danger';
            break;
        case 'warning':
            icon_notif = 'fa fa-exclamation';
            break;
        case 'success':
            icon_notif = 'fa fa-check-circle';
            break;
        case 1:
            icon_notif = 'fa fa-check-circle';
            type = 'success';
            break;
        default:
            icon_notif = 'now-ui-icons ui-1_bell-53';
    }

    $.notify({
        icon: icon_notif,
        message: title ? "<b>"+title+"</b> <br>" + message : message

    }, {
        type: type,
        timer: 5000,
        placement: {
            from: 'top',
            align: 'right'
        }
    });
}

function toRp(angka){
    var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
    var rev2    = '';
    for(var i = 0; i < rev.length; i++){
        rev2  += rev[i];
        if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
            rev2 += '.';
        }
    }
    return rev2.split('').reverse().join('');
}

(function ($) {
    $.fn.rupiah = function() {

        $(this).keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                return false;
            }
        });

        $(this).keyup(function () {
            separator = ".";
            a = $(this).val();
            b = a.replace(/[^\d]/g,"");
            c = "";
            panjang = b.length;
            j = 0;
            for (i = panjang; i > 0; i--) {
                j = j + 1;
                if (((j % 3) == 1) && (j != 1)) {
                    c = b.substr(i-1,1) + separator + c;
                } else {
                    c = b.substr(i-1,1) + c;
                }
            }

            $(this).val(c);
        })

    };
}(jQuery));

function getSum(total, num) {
    return total + num;
}

//---------------fn confirm delete-------------------

(function ($) {
    $.fn.confirmDelete = function(options) {
        confirmDeleteAlt(this, options, 'jquery.fn');
    };
}(jQuery));
function confirmDeleteAlt(element, options, type_usage, event_param) {
    
    var defaults = {
        beforeConfirm: function() {},
        cencel: function() {},
        url : "",
        type : "warning",
        method : "DELETE",
        title : "Apa Anda Yakin Menghapus Data Ini?",
        text : "Data ini tidak dapat dikembalikan!",
        text_confirm : "Ya, Hapus!",
        text_cancel : "Tidak"
    };

    var options = $.extend(defaults, options);

    if(type_usage === 'jquery.fn')
     $(element).on('click', function (event) {
         event.preventDefault();
         actionConfirmDelete(event, options, this);
        });
    else
        actionConfirmDelete(event_param, options, element);
}
function actionConfirmDelete(event, options, element) {

    if (options.beforeConfirm(event, element) === false)
        return;

    if ($.isEmptyObject(options.url))
        options.url = $(element).attr('href');

    swal({
        title: options.title,
        text: options.text,
        type: options.type,
        showCancelButton: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        confirmButtonText: options.text_confirm,
        cancelButtonText: options.text_cancel,
        buttonsStyling: false
    }).then(function (result) {
            if (result.value) {
                        $("<form/>", {
                            action: options.url,
                            method: 'POST'
                        }).append(
                            $("<input/>", {
                                type: 'hidden',
                                name: '_token',
                                value: $('meta[name="csrf-token"]').attr('content')
                            }),
                            $("<input/>", {
                                type: 'hidden',
                                name: '_method',
                                value: options.method
                            })
                        ).appendTo('body').submit()
            }else if (
                // Read more about handling dismissals
            result.dismiss === swal.DismissReason.cancel
            ) {
               options.cancel();
            }
        }
    ).catch(swal.noop);

 /*   if ($.isEmptyObject(options.data))
        options.data = {'id': $(element).data('id')};*/
}


//---------------fn confirmDialog------------------------
(function ($) {
$.fn.confirmDialog = function(options) {
    confirmDialogAlt(this, options, 'jquery.fn');
};
}(jQuery));
function confirmDialogAlt(element, options, type_usage, event_param) {
    var defaults = {
        beforeConfirm: function() {},
        cancel: function () {},
        confirm: function () {},
        type : "warning",
        title : "Apa Anda Yakin?",
        text : "Proses ini tidak dapat dibatalkan!",
        text_confirm : "Ya",
        text_cancel : "Tidak"
    };

    var options = $.extend(defaults, options);

    if(type_usage === 'jquery.fn')
        $(element).on('click', function (event) {
            actionConfirmDialog(event, options, element);
        });
    else
        actionConfirmDialog(event_param, options, element);

}
function actionConfirmDialog(event, options, element) {
    event.preventDefault();
    AJAX_LOAD = true;

    if (options.beforeConfirm(event, element) === false)
        return;

    swal({
        title: options.title,
        text: options.text,
        type: options.type,
        showCancelButton: true,
        confirmButtonText: options.text_confirm,
        cancelButtonText: options.text_cancel,
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false
    }).then(function() {
        options.confirm(event, element);
    }, function(dismiss) {
        // dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
        if (dismiss === 'cancel') {
            options.cancel(event, element);
        }
    }).catch(swal.noop);
}


function pagination(el) {
    $(document).on('click','a.page-link',function (event) {
        event.preventDefault();
        var that = $(this);
        $.ajax({
            url: $(that).attr('href'),
            type: 'get'
            // data: {''},
        })
            .done(function(e) {
                $(el).html(e.view);
                console.log(e.message);
            })
    });
}

//----------option--------

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on("submit", "form", function() {
    NProgress.start();
    $("#loader-wrapper").fadeIn();
});

$(document).ajaxStop(function() {
    NProgress.done();
    $("#loader-wrapper").fadeOut();
});

$(document).ajaxStart(function() {
    console.log('called');
    console.log(AJAX_LOAD);
    if(AJAX_LOAD){
        NProgress.start();
        $("#loader-wrapper").fadeIn();
    }
});

$('select option[value=""]').attr("disabled","disabled");

$(function () {
    $('input[type=checkbox]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
    });
});

$('input[data-type=rupiah]').rupiah();

$('.select2').select2({
    placeholder: $(this).data('placeholder') ? $(this).data('placeholder') : "Pilih"
});

$('.datepicker').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy',
});

function setMenuActive(menu) {
    $('ul.sidebar-menu li[data-menu-name='+menu+']').toggleClass('active');
}

