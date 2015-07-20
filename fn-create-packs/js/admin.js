jQuery(document).ready(function ($) {
    $('#poststuff.fncp .postbox .inside.pack .collapse').click(function () {
        var jthis = $(this),
            parent = jthis.parent(),
            hide = parent.find('.hide'),
            icon = parent.find('div.collapse');

        if (jthis.hasClass('closed')) {
            hide.show();
            jthis.removeClass('closed');
            icon.removeClass('closed');
        } else {
            hide.hide();
            jthis.addClass('closed');
            icon.addClass('closed');
        }
    });

    //$('#poststuff.fncp .postbox .admin-card-types .hide table tr:last-child td:last-child').attr('colspan', '2');

    $('#poststuff.fncp .postbox .inside.pack.admin-card-types form').submit(function () {
        var form = $(this);
        var total = 0;

        form.addClass('processing');
        form.find('table.probs input[type="text"]').each(function () {
            var val = parseFloat($(this).val());
            if (val) {
                total += val
            } else {
                total += 0;
            }
        });
        if (total != 200) {
            form.find('input[type="submit"]').after("<br/><span> Probabilities must add to 100%. </span>").css('color', 'red');
            form.removeClass('processing');
            return false;
        }

        var settings = true;
        form.find('table.settings input[type="text"]').each(function () {
            var val = parseInt($(this).val());
            if (!val) {
                settings = false;
            }
        });
        if (!settings) {
            form.find('input[type="submit"]').after("<br/><span> Price/Cards Nr. Invalid or Empty. </span>").css('color', 'red');
            form.removeClass('processing');
            return false;
        }

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
        }).done(function () {
            form.addClass("done");
            form.removeClass('processing');
        });
        return false;
    });
});