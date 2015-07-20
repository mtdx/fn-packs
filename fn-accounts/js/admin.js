jQuery(document).ready(function ($) {

    $('#poststuff.fncp .usercoins td form.withdraw').submit(function ($e) {
        if (window.confirm('Are you sure?')) {
            var form = $(this);
            var data = form.serialize();
            $.post(ajaxurl, data, function (response) {
                if (response) {
                    window.location.reload();
                } else {
                    form.find('input[type="number"]').addClass('red').focus();
                }
            });
        }
        return false;
    });

});