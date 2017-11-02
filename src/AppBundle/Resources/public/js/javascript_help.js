// JavaScript Document
var winHeight;
var winWidth;
var show_features = false;
$(document).ready(function(){
    
    $('#help-experience-h2').css('display','none');
    $('#help-experience-container .subheadline').css('display','none');
    $('.help-experience li').css('display','none');
    
    $('#help-participate-container h2').css('display','none');
    $('#help-participate-container .subheadline').css('display','none');
    $('.help-participate li figure').css('display','none');
    $('#circle1_outer').css('display','none');
    $('#circle2_outer').css('display','none');
    $('#circle3_outer').css('display','none');
    $('#circle4_outer').css('display','none');
    $('#circle5_outer').css('display','none');
    
    $('#help-action-container h2').css('display','none');
    $('.help-action li').css('display','none');
    
    $(window).on("scroll", function(){
        var offset_experience_h2 = $('#help-experience-container h2').offset();
        var offset_experience = $('#linecontainer-1').offset();
        var offset_participate_h2 = $('#help-participate-container h2').offset();
        var offset_participate = $('#linecontainer-2').offset();
        var offset_action_h2 = $('#help-action-container h2').offset();
        var offset_action = $('#linecontainer-3').offset();
        
        winHeight = $( window ).height();
        winWidth = $( window ).width();
        
        if (show_features == false && $(window).scrollTop() > (offset_experience.top - winHeight)) {
            if (winWidth >= 1214) {
                var vivus0 = new Vivus('arc_0', {start : 'manual'});
            }
            var vivus1 = new Vivus('arc_1', {start : 'manual'});
            var vivus2 = new Vivus('arc_2', {start : 'manual'});
            var vivus3 = new Vivus('arc_3', {start : 'manual'});
            var vivus4 = new Vivus('arc_4', {start : 'manual'});
            var vivus5 = new Vivus('arc_5', {start : 'manual'});
            var vivus6 = new Vivus('arc_6', {start : 'manual'});
            var vivus7 = new Vivus('arc_7', {start : 'manual'});
            var vivus8 = new Vivus('arc_8', {start : 'manual'});
            var vivus9 = new Vivus('arc_9', {start : 'manual'});
            var vivus10 = new Vivus('arc_10', {start : 'manual'});
            var vivus11 = new Vivus('arc_11', {start : 'manual'});
            var vivus12 = new Vivus('arc_12', {start : 'manual'});
            var vivus13 = new Vivus('arc_13', {start : 'manual'});
        }
        
        
        //console.log('Window:' + winHeight + " : " + offset_experience.top);
        
        /*if ($(window).scrollTop() > (offset_experience_h2.top + 100 - winHeight)) {
            $('#help-experience-h2').fadeIn('slow');
        }*/
        if ($(window).scrollTop() > (offset_experience.top - winHeight)) {
            if (show_features == false) {
   
                $('#help-experience-h2').delay( 200 ).fadeIn(300,function(){                                             // Animation Bereich Erleben
                    $('#help-experience-container .subheadline').delay( 200 ).fadeIn(300, function() {
                            setTimeout(function(){
                            for (i=0; i<$('#linecontainer-1 .line').length; i++) {
                                $('#linecontainer-1 .line').eq(i).css('webkitAnimationPlayState', "running");
                            }
                                if (winWidth >= 992) {
                                    $('.help-experience li').eq(0).delay( 200 ).fadeIn(300);
                                    $('.help-experience li').eq(1).delay( 500 ).fadeIn(300);
                                    $('.help-experience li').eq(2).delay( 900 ).fadeIn(300);
                                } else {
                                    $('.help-experience li').eq(0).delay( 600 ).fadeIn(300);
                                    $('.help-experience li').eq(1).delay( 900 ).fadeIn(300);
                                    $('.help-experience li').eq(2).delay( 1300 ).fadeIn(300);
                                }
                        }, 500);
                    });
                });

            $('#help-participate-container h2').delay( 3500 ).fadeIn(300, function() {                                  // Animation Bereich Mitmachen
                $('#help-participate-container .subheadline').delay( 300 ).fadeIn(300, function() {
                    $('#linecontainer-2 .line2_top').css('webkitAnimationPlayState', "running");
                    
                    if (winWidth >= 992) {
                        setTimeout(function(){
                        if (winWidth >= 1214) { vivus0.play(6); }
                        setTimeout(function(){
                            vivus1.play(6);
                            setTimeout(function(){
                                $('.help-participate li:nth-child(5) figure').fadeIn(300);
                                vivus2.play(6);
                                setTimeout(function(){
                                    $('.help-participate li:nth-child(4) figure').fadeIn(300);
                                    vivus3.play(6, function() {
                                        vivus5.play(6);
                                        vivus6.play(6);
                                        $('.help-participate li:nth-child(3) figure').fadeIn(300);
                                        vivus7.play(6, function() {
                                            vivus8.play(6);
                                            $('.help-participate li:nth-child(2) figure').fadeIn(300);
                                            vivus9.play(6, function() {
                                                vivus10.play(6, function() {
                                                    $('.help-participate li:nth-child(1) figure').fadeIn(300);
                                                        vivus11.play(6, function() {
                                                            vivus12.play(6);
                                                            vivus13.play(6);
                                                    });
                                                });
                                            });
                                        });
                                    });
                                    vivus4.play(6);  
                                }, 100);
                            }, 100);
                        }, 150);
                        }, 250);
                    } else {
                        $('.help-participate li:nth-child(1) figure').fadeIn(300, function() {
                            $('#circle1_outer').fadeIn(200);
                            $('.help-participate li:nth-child(2) figure').delay( 200 ).fadeIn(300, function() {
                                $('#circle2_outer').fadeIn(200);
                                $('.help-participate li:nth-child(3) figure').delay( 200 ).fadeIn(300, function() {
                                    $('#circle3_outer').fadeIn(200);
                                    $('.help-participate li:nth-child(4) figure').delay( 200 ).fadeIn(300, function() {
                                        $('#circle4_outer').fadeIn(200);
                                        $('.help-participate li:nth-child(5) figure').delay( 200 ).fadeIn(300);
                                        $('#circle5_outer').fadeIn(200);
                                    });
                                });
                            });
                        });
                    }
                });
            });
            $('#help-action-container h2').delay( 8000 ).fadeIn(300, function() {
                setTimeout(function(){
                    for (i=0; i<$('#linecontainer-3 .line').length; i++) {
                        $('#linecontainer-3 .line').eq(i).css('webkitAnimationPlayState', "running");
                    }
                    if (winWidth >= 992) {
                        $('.help-action li').eq(0).delay( 600 ).fadeIn(300);
                        $('.help-action li').eq(1).delay( 1200 ).fadeIn(300);
                    } else {
                        $('.help-action li').eq(0).delay( 400 ).fadeIn(300);
                        $('.help-action li').eq(1).delay( 800 ).fadeIn(300);
                    }
                }, 400);
            });
            }
            show_features = true;
        }
    });
    
    $(window).on("resize orientationchange", function(){
        winWidth = $( window ).width();
        if (winWidth >= 992 && show_features == true) {
            $('.help-participate li figure').css('display','block');
            $('#circle1_outer').css('display','none');
            $('#circle2_outer').css('display','none');
            $('#circle3_outer').css('display','none');
            $('#circle4_outer').css('display','none');
            $('#circle5_outer').css('display','none');
        } else if (winWidth < 992 && show_features == true) {
            $('#circle1_outer').css('display','block');
            $('#circle2_outer').css('display','block');
            $('#circle3_outer').css('display','block');
            $('#circle4_outer').css('display','block');
            $('#circle5_outer').css('display','block');
        }
    });
});