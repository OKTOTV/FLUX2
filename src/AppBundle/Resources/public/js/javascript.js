// JavaScript Document
$(document).ready(function(){
	/* Headerbackground */
	var headerScrollheight = $(window).height()/2;
	var headerHeight = 180;

	function addHeaderBG() {
	  if ($('body').hasClass('fullscreen-images') || $('body').hasClass('fullscreen-background')) {
	      if($(document).scrollTop() <= headerScrollheight) {
			  //Header transparent setzen und Schlagschatten entfernen
	          /*$('body').addClass('head-white-color')
		               .removeClass('head-white-color')
				       .addClass('head-transparent-bg')
				       .removeClass('head-black-bg');*/
		      //$('.navbar-fixed-top').removeClass('dropshadow'); //Schlagschatten

			  //Downbutton erscheinen lassen
			  $('.fullscreen-images #button_down span').css('display','inline');

	      } else if ($(document).scrollTop() > headerScrollheight) {
			  //Header schwarz setzen und Schlagschatten hinzufügen
		     /* $('body').removeClass('head-white-color')
			          .addClass('head-white-color')
					  .addClass('head-black-bg')
					  .removeClass('head-transparent-bg');*/
		      //$('.navbar-fixed-top').addClass('dropshadow'); //Schlagschatten

			  //Downbutton ausblenden
			  $('.fullscreen-images #button_down span').css('display','none');
		   }
	    } else {
			if($(document).scrollTop() <= headerScrollheight) {
				$('.navbar-fixed-top').removeClass('dropshadow');
		    } else if ($(document).scrollTop() > headerScrollheight) {
				$('.navbar-fixed-top').addClass('dropshadow');
			}
		}
	 }

	 addHeaderBG();

	 $(document).scroll(function(){
	     addHeaderBG();
	 });

	 
	

	//Anchor Oktothek und Serie:
	$('#button_down').click(function() {
		if ($('body').hasClass('oktothek')) {
		    var offset = $('#oktothek').offset();
		} else if ($('body').hasClass('series')) {
			var offset = $('section.series-description').offset();
		}  else if ($('body').hasClass('academy')) {
			var offset = $('section.academy-description').offset();
		}
		$("html, body").animate({scrollTop : (offset.top - $('header .navbar').height()) + "px"}, "slow");
		$( this ).find('span').css('display','none');
	});

	//Sharing Tabs:
	
	$('#ButtonShare').click(function() {
	    $('article .collapse').collapse('hide');
		$('#collapseShareArea .collapse').collapse();
	});
	$('#ButtonPlaylist').click(function() {
	    $('article .collapse').collapse('hide');
		$('#collapsePlaylistArea .collapse').collapse();
	});
	 
	$('#collapseShareArea .sharingnav button').click(function (e) {
        //e.preventDefault();
		$('#collapseShareArea .tab-content div').removeClass('active');
		$('#collapseShareArea .sharingnav button').removeClass('active');
		switch($(this).attr('id')) {
            case "share_sn":
                $('#collapseShareArea #div_share_sn').addClass('active');
                break;
            case "share_embed":
                $('#collapseShareArea #div_share_embed').addClass('active');
                break;
            default:
                $('#collapseShareArea #div_share_sn').addClass('active');
        }
		$(this).addClass('active');
    })


	/*Sharebuttons*/
	$('#sharingurl').val(window.location.href);
	/* Tooltips: */
	    if (
	        $('body').hasClass('oktothek') ||
		    $('body').hasClass('episode') ||
	        $('body').hasClass('series')) {
	        $('[data-toggle="tooltip"]').tooltip({'placement': 'auto right'});
	    }

	
	if ($( window ).width() >= 768) {
		
		/*Ankermenü*/
	    var Ts_offset = 250;
        var Ts_duration = 300;
		var Footer_target = $('#anchor-menu li:last a').attr('href');
	    var Footer_offset = $(Footer_target).offset();
		if (!$('body').hasClass('fullscreen-images')) {
		    $('#anchor-menu').fadeIn(Ts_duration);
		}
		
		if ($('#anchor-menu').length > 0) {
			
			var VarCollapseFinish;
            $(window).scroll(function() {
				//Abfrage ob Slider bzw. Fullscreenbild vorhanden oder nicht (unterschiedliche Ausgangshöhen)
			    if ($('body').hasClass('fullscreen-images')) 
				    TsStart = Ts_offset;
				else 
				    TsStart = 0;
				TsEnd = $(this).scrollTop()+$('footer').height();
				
                if ($(this).scrollTop() >= TsStart && TsEnd < Footer_offset.top)
                    $('#anchor-menu').fadeIn(Ts_duration, function() {$(this).css('display','block')});
				else 
				    $('#anchor-menu').fadeOut(Ts_duration, function() {$(this).css('display','none')});
               
			    //Top Button anzeigen
		 	    $('#anchor-menu .collapse').collapse('hide'); //Ankermenü einklappen bei Scrollen
            });
            
			function hideAnchorlist() {
			    if (!$('#AnchorList').hasClass('in')) {
					$( "#anchor-menu" ).animate({
                        bottom: "-35px"
                    }, Ts_duration, function(){$(this).css('bottom','-35px');});
					clearInterval(VarCollapseFinish);
				}
			}
			
            $('#anchor-menu .list-group-item a').click(function(event) {
                event.preventDefault();
			    var target = $(this).attr('href');
			    var offset = $(target).offset();
			    $("html, body").animate({scrollTop : offset.top - headerHeight + "px"}, Ts_duration);
				VarCollapseFinish = setInterval(function(){ hideAnchorlist() }, 100);
                return false;
            })
			$( "#anchor-menu" ).mouseenter(function() {
				console.log("eingelangt");
                $( "#anchor-menu" ).animate({
                    bottom: "0px"
                }, Ts_duration, function(){$(this).css('bottom','0px');});
            });
			$( "#anchor-menu" ).mouseleave(function() {
				$('#anchor-menu .collapse').collapse('hide');
				VarCollapseFinish = setInterval(function(){ hideAnchorlist() }, 100);
            });
		}
		//Top Button
		$('.btn-top').click(function(event) {
            event.preventDefault();
            jQuery('html, body').animate({scrollTop: 0}, Ts_duration);
            return false;
        })
		$('#anchor-menu .collapse').collapse(); //Ankermenü Collapse aktivieren
    }
	
	//TV
	$('.schedule .collapse').collapse(); //Ankermenü Collapse aktivieren
	
	//Academy
	if ($('body').hasClass('academy')) {
	$('figure.pin img').parent().click(function() {
		for (k=0; k<$('.collapseCoursedetails').length; k++) {
			if ($(this).attr('href') != $('.collapseCoursedetails').eq(k).attr('id')) {
	            $('.collapseCoursedetails').eq(k).collapse('hide');
			}
		}
		$('#collapseShareArea .collapse').collapse();
	});
	}
	
	
});
