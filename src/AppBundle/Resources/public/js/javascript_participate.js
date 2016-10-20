// JavaScript Document
var active_nav = false;
var img_array = new Array('heinrich.svg', 'planet1.svg', 'satelit.svg', 'planet2.svg', 'planet3.svg');
var img_array_act = new Array('heinrich_act.svg', 'planet1_act.svg', 'satelit_act.svg', 'planet2_act.svg', 'planet3_act.svg');
$(document).ready(function(){
    var planets = $("#planets").Cloud9Carousel( {
        autoPlay: 0,
        bringToFront: true,
        yRadius: $('#planets').height()/2.5,
        speed:1,
        onAnimationFinished: function() {
            //if(active_nav == true) {
                //$("#planets").data("carousel").deactivate();
            //}
        },
        onLoaded: function( carousel ) {
            $("#planets").data("carousel").go( 1 );
            $("#planets figure").eq(4).find('nav').addClass('active');
        }
    } );
    
   
    $('#planets figure').click(function(){
            var index = $('#planets figure').index(this);
            $('#planets').find('nav.active').removeClass('active');
            $(this).find('nav').addClass('active');
            var source = $(this).find('img').attr('src');
            var source_array = source.split('/');
            source_array[source_array.length - 1] = img_array_act[index];
            new_source = "";
            for (var i=0; i<source_array.length; i++) {
                new_source += source_array[i];
                if (i != source_array.length - 1) new_source += "/";
            }
            $(this).find('img').attr('src',new_source);
    });

});