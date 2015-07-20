jQuery.fn.rotate = function (degrees) {
    jQuery(this).css({
        '-webkit-transform': 'rotate(' + degrees + 'deg)',
        '-moz-transform': 'rotate(' + degrees + 'deg)',
        '-ms-transform': 'rotate(' + degrees + 'deg)',
        'transform': 'rotate(' + degrees + 'deg)'
    });
    return jQuery(this);
};
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

jQuery(document).ready(function ($) {
    $('html, body').animate({
        scrollTop: $("#content").offset().top
    });

    //Update coins
    $('#fncp-open-pack .coins').text(numberWithCommas($('#fncp-open-pack .coins').text()));
    coinTimer = setInterval(function () {
        var cv = $('#fncp-open-pack .coins').data('coins');
        if (cv != finalAmt) {
            if (finalAmt > cv) {
                cv += coinCounter;
            } else {
                cv -= coinCounter;
            }
            if (Math.abs(finalAmt - cv) < coinCounter) {
                cv = finalAmt;
            }
            $('#fncp-open-pack .coins').data('coins', cv);
            $('#fncp-open-pack .coins').text(numberWithCommas(cv));
        } else {
            clearInterval(coinTimer);
        }
    }, 1);

    //Load the cards in a random order
    posX = $('#content #fncp-open-pack #MFContainerPlaceholder').offset().left; //Special card X pos
    posY = $('#content #fncp-open-pack #MFContainerPlaceholder').offset().top - $('#masthead').outerHeight(true); //Special card Y pos
    //posY = 280; //Special card Y pos
    cardTimer = setInterval(function () {
        //Select random variable form card
        if (cards.length > 0) {
            var index = Math.floor((Math.random() * cards.length));
            var card = cards[index];
            cards.splice(index, 1);
            $('#fncp-open-pack .card.c' + card).animate({
                'opacity': 1,
                'top': '+=30'
            });
        } else {
            //Final card
            rotationTimer = setInterval(function () {
                rotation += 1;
                jQuery('#MFContainer img.goldCircle').rotate(rotation);
                if (rotation == (360 * 1.5)) {
                    $('#MFContainer img.goldCircle').fadeOut('slow', function () {
                        clearInterval(rotationTimer);
                    });
                }
            }, 20);
            $('#MFContainer, #page .blackBG').fadeIn(2000, function () {
                $('#page .blackBG').fadeOut(2000, function () {
                    $('html, body').animate({
                        scrollTop: $("#content").offset().top
                    }, 0, function () {
                        $('#MFContainer').animate({
                            'top': posY,
                            'left': posX,
                            'margin-left': 0
                        }, 320, 'swing', function () {
                            $('#MFContainer').stop(true, true).prependTo('#MFContainerPlaceholder').css({
                                'position': 'absolute',
                                'left': 0,
                                'top': 0
                            });
                        });
                    });
                });
            });
            clearInterval(cardTimer);
        }
    }, 100);
    $('#MFContainer, #page .blackBG').hide();
});