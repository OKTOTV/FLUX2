// JavaScript Document
var active_nav = false;
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
        }
    } );
    console.log(planets);
    $('#planets a').click(function(event){
        event.preventDefault();
        //if (active_nav == false) {
            //console.log("1");
            //active_nav = true;
            //$('#planets').find('nav.active').removeClass('active');
            //$(this).parents('figure').find('nav').addClass('active');
       //} else {
            //console.log("2");
            //active_nav = false;
            //$('#planets').find('nav.active').removeClass('active');
            //$(this).parents('figure').find('nav').addClass('active');
            //$("#planets").data("carousel").go(0);
            //active_nav = true;
        //}
    });
    $('#planets figure').click(function(){
            $('#planets').find('nav.active').removeClass('active');
            $(this).find('nav').addClass('active');
    });

});