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
// MAIN ajax form send and save to DB
    function ajax2() {

    }
    $('#order-form').submit('click', function (e) {
        e.preventDefault();
        var form = $(this);
        form = form.serialize();
        $('input[type=submit]').prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: '/backend/form_handler.php',
            dataType: 'json',
            data: form,
            success: function (data) {
                if(data.error) {
                    alert(data.error);
                }
                console.log('ok..success response data from handler');
                // console.log(data.order_id);
//                    var json = $.parseJSON(data);
//                    var parsed_data = JSON.parse(data);
//                    alert(data.email);
                $('.order__form-result').css('display', 'block');
                $('#result').html("Спасибо! Это уже " + data.count +  " ваш заказ");
                $('input[type=submit]').prop('disabled', false);

            },//success
            error: function (xhr, ajaxOption, thrownError) {
                console.log('error');
                alert("error " + xhr.status + " " + xhr.responseText);
//                    console.log(JSON.text());
                console.log(xhr.responseText);
                console.log(xhr.status);
                console.log(thrownError);
                $('input[type=submit]').prop('disabled', false);
            }//error
        }); //ajax
    });//function body
});
