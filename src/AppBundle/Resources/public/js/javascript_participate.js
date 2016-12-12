// JavaScript Document
var active_nav = false;
var img_array = new Array('heinrich.svg', 'planet1.svg', 'satelit.svg', 'planet2.svg', 'planet3.svg');
var img_array_act = new Array('heinrich_act.svg', 'planet1_act.svg', 'satelit_act.svg', 'planet2_act.svg', 'planet3_act.svg');
$(document).ready(function(){
    var planets;
    var source_array;
    if ($(window).width() >= 992) {
    planets = $("#planets").Cloud9Carousel( {
        autoPlay: 0,
        bringToFront: true,
        yRadius: $('#planets').height()/2.5,
        xRadius: $('#planets').width() / 2.3,
        speed:1,
        onLoaded: function( carousel ) {
            $("#planets").data("carousel").go( 1 );
            $("#planets figure").eq(4).find('nav').addClass('active');
            source_array = coloredFigure(true);
        }
    } );
    }
    
    var resizeTimer;
    $( window ).on('resize', function() {
        $('#planets').addClass('move');
        if(resizeTimer){
            clearTimeout(resizeTimer);
        }
        resizeTimer = setTimeout(function() {
            if ($(window).width() >= 992) {
                
                planets = $("#planets").css('opacity','0').Cloud9Carousel( {
                autoPlay: 0,
                bringToFront: true,
                yRadius: $('#planets').height()/2.5,
                xRadius: $('#planets').width() / 2.3,
                speed:1,
                onLoaded: function( carousel ) {
                    $('#planets').removeClass('move');
                    $('#planets').css('opacity','1');
                    $("#planets").data("carousel").go( 1 );
                    $("#planets figure").eq(4).find('nav').addClass('active');
                    source_array = coloredFigure(true);
            } } );
            } else {
                $('#planets').removeClass('move');
                $('#planets').css('opacity','1');
                $("#planets").data("carousel").deactivate();
                $('#planets figure').removeAttr('style');
                $('#planets').find('nav.active').removeClass('active');
                source_array = coloredFigure(false);
            }
            resizeTimer = null;
        }, 300);
     });
     $(window).on("orientationchange", function(){
        //if ($(window).width() < 768) {
            //console.log(planets);
        //}
        
    });
    
    function coloredFigure(newEl) {
        var elNotActive = $("#planets figure");
        for (var i=0; i<elNotActive.length; i++) {
            var source = $(elNotActive[i]).find('img').attr('src');
            var source_array = source.split('/');
            source_array[source_array.length - 1] = img_array[i];
            new_source = "";
            for (var m=0; m<source_array.length; m++) {
                new_source += source_array[m];
                if (m != source_array.length - 1) new_source += "/";
            }
            $('#planets figure').eq(i).find('img').attr('src',new_source);
        }
        if (newEl == true) {
            var el = $('#planets figure nav.active').parents('figure');
            var index = $('#planets figure').index(el);
            var source = el.find('img').attr('src');
            var source_array = source.split('/');
            source_array[source_array.length - 1] = img_array_act[index];
            new_source = "";
            for (var i=0; i<source_array.length; i++) {
                new_source += source_array[i];
                if (i != source_array.length - 1) new_source += "/";
            }
        
            $('#planets figure').eq(index).find('img').attr('src',new_source);
        }
    }
    var close = false;
    $('#planets figure').click(function(e) { 
            if (close == false) {
                $('#planets').find('nav.active').removeClass('active');
                $(this).find('nav').addClass('active');
                source_array = coloredFigure(true);
            } else {
                close = false;
            }
    });
    $('#planets nav .close').click(function(e) {
            close = true;
            $('#planets').find('nav.active').removeClass('active');
            source_array = coloredFigure(false);
    });

});