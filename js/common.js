/**
 * Created by mike on 10.08.17.
 */
jQuery(document).ready(function ($) {

    //scroll to order form

    $('a.order-link.btn, a.burgers-slider__buy.btn').on('click', function () {
        $('body,html').animate({scrollTop: $('.section.order').offset().top}, 1500);
    });

// ************** menu scroll navigation  *********************

    //ajax request to backend from form

    $("#order-form").submit(function (event) {
        event.preventDefault();
        var form = $(this);
        var error = false;

        var data = form.serialize();

        var name = $('input[name=name]').val();
        var phone = $('input[name=phone]').val();
        var email = $('input[name=email]').val();
        var street = $('input[name=street]').val();
        var home = $('input[name=home]').val();
        var part = $('input[name=part]').val();
        var appt = $('input[name=appt]').val();
        var floor = $('input[name=floor]').val();
        var comment = $('input[name=commet]').val();
        var payment = $('input[name=payment]').val();
        var callback = $('input[name=callback]').val();

        console.log(name + 'до отправки');
        $.ajax({
            type: 'POST',
            url: '/backend/form_handler.php',
            dataType: 'json',
            data: data,
            /*{
            name:name,
            phone:phone,
            email:email,
            street:street,
            home:home,
            part:part,
            appt:appt,
            floor:floor,
            comment:comment,
            payment:payment,
            callback:callback
        },*/
            beforeSend: function () {
                // todo не нужно
                //        var is_empty = false;
                //        $('[required]').each(function (idx, elem) {
                //            is_empty = is_empty || ($(elem).val() == '');
                //        });
                //
                //// now do the thing, but only if ALL the values are not empty
                //        if (is_empty) {
                //            alert("пустые");
                //        } else {
                //            alert("полные");
                //        }
                //блочим кнопку отправки
                $('input[type=submit]').attr('disabled', 'disabled');
            },
            success: function (data) {
                console.log($.parseJSON(data));
                // var response_data = JSON.parse(data);
                // console.log(response_data.name);
                console.log('sussecc');
                alert("success");
                $('.order__form-result').css('display', 'block');
                $('#result').html("");
                $('input[type="submit"]').prop('disabled', false);
            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log('error');
                console.log(JSON.text());
                alert(xhr.responseText)
                alert(xhr.status);
                alert(thrownError);
                $('input[type="submit"]').prop('disabled', false);
            },
            done: function (data) {
                alert('done');
                var response_data = $.parseJSON(data);
                console.log(response_data.name);
                //заполняем форму после ajax'a теми же значениями что ввел юзер для след заказа,
                //чтобы не вводить заного
                alert(response_data.name);
                $('input[name="name"]').val(response_data.name);
                $('input[name="phone"]').val(response_data.phone);
                $('input[name="email"]').val(response_data.email);
                //разблокируем, после завершения запроса, кнопку submit
                $('input[type="submit"]').prop('disabled', false);
            }
        });//ajax request
        // } // error
        // return false;
    });
});
