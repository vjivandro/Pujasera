(function ($) {
    var _sending_ajax = false;
    $.fn.ajaxForm = function(options) {

      var defaults = {
        beforeSubmit: function() {},
        success: function () {},
        error: function () {}
      };

      var options = $.extend(defaults, options);

      $(this).on('submit', function(event) {
        event.preventDefault();
        options.beforeSubmit(event);
        if(_sending_ajax)
          return;
        _sending_ajax = true;

        var
          that = this,
          type = $(that).attr("form-type"),
          upload = type == "upload",
          data = upload ? new FormData(this) : $(this).serialize(),
            _btn_submit = $(this).find("input[type=submit], button[type=submit]"),
          _btn_submit_default_html = _btn_submit.html()
        ;
          //console.log(data);
        $.ajax({
          url: $(that).attr('action'),
          type: $(that).attr('method'),
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: data,
          dataType: $(that).attr('data-form-type'),
          contentType: upload ? false : "application/x-www-form-urlencoded",
          processData: !upload,
          beforeSend: function() {
            $(that).find('button').attr('disabled', 'disabled');

              _btn_submit.html("<span class='fa fa-spin fa-cog'></span> Loading");
              _btn_submit.prop('disabled', true);

            $('span.form-invalid').each(function(index, el) {
              $(el).remove();
            });

              $('.has-error.form-invalid').each(function(index, el) {
                  $(el).removeClass('has-error form-invalid');
              });
          }
        })
        .done(function(e) {
          //  console.log(e);
          if(typeof e !== "undefined") {

              show_notif(e.status, e.message, e.title);

            if(e.redirect){
                window.location = e.redirect;
            }
              _sending_ajax = true;
            options.success(e);
          } else {
            options.error(e);
          }
        })
        .fail(function(e) {
           // console.log(e);
          var json = e.responseJSON;
          if(json != null)
          {
            var
              message = json.message,
              errors = json.errors
            ;

              show_notif('danger', message, 'Oops!!!', 'fa fa-exclamation-triangle');

            $(that).find('button').removeAttr('disabled');
            $.each(errors, function(index, data) {
              //  console.log(data[0]);
                var _feedback = '<span class="help-block form-invalid">' +
                    '<strong><i class="fa fa-exclamation-triangle"></i> '+ data[0] +'</strong></span>';

                var indexarray = index.split(".", 2);
                if( 1 in indexarray){
                  $("."+indexarray[0]).eq(parseInt(indexarray[1])).closest('div.form-group').addClass('has-error form-invalid').append(_feedback);
                }else{
                    $("input[name="+ index +"]").closest('div.form-group').addClass('has-error form-invalid').append(_feedback);
                    $("textarea[name="+ index +"]").closest('div.form-group').addClass('has-error form-invalid').append(_feedback);
                    $("select[name="+ index +"]").closest('div.form-group').addClass('has-error form-invalid').append(_feedback);
                }

            });
              var inputInvalid = document.getElementsByClassName("has-error form-invalid");
              inputInvalid[0].scrollIntoView();

          }
          options.error(e);
        })
        .always(function() {
            _sending_ajax = false;
            $(that).find('button').removeAttr('disabled');
            _btn_submit.html(_btn_submit_default_html);
        });
      });
    };
}(jQuery));
