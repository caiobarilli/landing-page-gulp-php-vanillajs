/**
 * File forms.js.
 */

( function( $ ) {

    "use strict"; // Start of use strict

    // Newsletter
    $('form').on('submit', function (evt) {

        evt.preventDefault();

        var form = $(this);
        var serializedData = form.serialize();
        var button = $(this).find('button');

        $.ajax({
        url:  'php/email-submit.php',
        type: 'POST',
        data: serializedData,
        beforeSend: function () {
            button[0].disabled = true;
        }
        })
        .done(function (response) {
            $('#modalSucesso').modal('show');
        })
        .fail(function () {
            $('#modalFail').modal('show');
        })
        .always(function () {
            form.trigger('reset');
            button[0].disabled = false;
            button[0].removeAttribute('disabled');
        });

    });
    
})(jQuery); // End of use strict