//    fancybox
jQuery(".fancybox").fancybox();

$(document).ready(function(){    
    
    var $container = $('#gallery');
    $container.isotope({
        itemSelector: '.item',
        animationOptions: {
            duration: 750,
            easing: 'linear',
            queue: false
        }
    });
    
    //
    // filter items when filter link is clicked
    $('#filters a').click(function() {
        $("#filters a").removeClass("active");        
        $(this).addClass( "active" );
        var selector = $(this).attr('data-filter'); 
        $container.isotope({filter: selector});
        return false;
    });
    
    //top menu smootscroll 
    $('a[href^="#"]').bind('click.smoothscroll',function (e) {
        e.preventDefault();
      
        var target = this.hash;
        $target = $(target);
      
        $('html, body').stop().animate({
            scrollTop: $target.offset().top-100
        }, 1000, 'swing', function () {
            window.location.hash = target;
        });
    });
    
    
    // home balls animatin
    /* (function fadeInDiv() {
        var divs = $('.fade');
        var elem = divs.eq(Math.floor(Math.random() * divs.length));
        if (!elem.is(':visible')) {
            elem.prev().remove();
            elem.animate({
                opacity: 1
            }, Math.floor(Math.random() * 1000), fadeInDiv);
        } else {

            elem.animate({
                opacity: (Math.random() * 1)
            }, Math.floor(Math.random() * 1000), function () {
                elem.before('<img>');
                window.setTimeout(fadeInDiv);
                //fadeInDiv();
            });
        }
    })();*/

    
});