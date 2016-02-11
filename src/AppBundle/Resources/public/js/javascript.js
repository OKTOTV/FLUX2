// JavaScript Document
$(document).ready(function(){
	/* Headerbackground */
	var headerScrollheight = $(window).height()/2;
	var headerHeight = 180;
	var borderbottom = 70;

	function addHeaderBG() {
	  if ($('body').hasClass('fullscreen-images') || $('body').hasClass('fullscreen-background')) {
	      if($(document).scrollTop() <= headerScrollheight) {
			  //Header transparent setzen und Schlagschatten entfernen
	          $('body').addClass('head-white-color')
		               .removeClass('head-white-color')
				       .addClass('head-transparent-bg')
				       .removeClass('head-black-bg');
		      //$('.navbar-fixed-top').removeClass('dropshadow'); //Schlagschatten

			  //Downbutton erscheinen lassen
			  $('.fullscreen-images #button_down span').css('display','inline');

	      } else if ($(document).scrollTop() > headerScrollheight) {
			  //Header schwarz setzen und Schlagschatten hinzufügen
		      $('body').removeClass('head-white-color')
			          .addClass('head-white-color')
					  .addClass('head-black-bg')
					  .removeClass('head-transparent-bg');
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

	 /*Slider*/

    function ImageRatio(el) {
        var aspectRatio = $(el).height() / $(el).width();
        return aspectRatio;
    }

	function resizeImage(el, container, _ratio) {
		var Winheight = $( window ).height() - borderbottom;
		var Winwidth = $( window ).width();

		$(container).find('figure').parent('div').css('height',Winheight + 'px').css('overflow','hidden');

		if (Winheight / _ratio > Winwidth) {
			newMargin = (Winwidth - (Winheight / _ratio)) / 2;
			el.css('height', Winheight + 'px').css('width', (Winheight / _ratio) + 'px').css('margin-left', newMargin + "px");
        } else {
			el.css('height',(Winwidth * _ratio) + 'px').css('width',Winwidth + 'px').css('margin-left','0px');
        }
	}

	 /*Alle Bilder auf gleiche Höhe bringen (nach der kleinsten Höhe)*/
	function carouselNormalization(container) {
        var items = $(container).find('.item'), //grab all slides
        width = [], //create empty array to store height values
		Winheight,
		Winwidth,
		aspectRatio;

        if (items.length) {

            function normalizeHeights() {

				if (width.length == 0) { //beim ersten Mal Array füllen
                	items.each(function() { //Aspect Ratio Array hinzufügen
						aspectRatio = ImageRatio(this);
                    	width.push(aspectRatio);
                	});
				}

				var i=0;
                items.each(function() {
					//Bildgröße berechnen lassen:
                    resizeImage($(this).find('img'), container, width[i]);
					i++;
                });
            };
            normalizeHeights();

            $(window).on('resize orientationchange', function () {
                Winheight = 0, Winwidth = 0; //reset vars
                normalizeHeights(); //run it again
            });
        }
    }

	//Bildgröße für Slider erst berechnen lassen, wenn die Höhe existiert
	function checkImgArrayLoading() {
		//console.log($('#slider .carousel-inner img:first').height());
		if($('#slider .carousel-inner img:first').height() > 0) {
                carouselNormalization($('.fullscreen-images .carousel'));
			    clearInterval(VarImgArrayLoading);
		}
	}
	var VarImgArrayLoading = setInterval(function(){ checkImgArrayLoading() }, 100);


	 function resizeSingleImage(el) {
        var aspectRatio;

        if ((aspectRatio = el.ratio) == null) {
			el.ratio =ImageRatio(el);
        }

		//Bildgröße berechnen lassen:
		resizeImage(el, el.parents('div.series-ident'), el.ratio);
    };

	//Bildgröße für Einzelbilder erst berechnen lassen, wenn die Höhe existiert
	function checkImgLoading() {
		if($('.series .episode-posterframe img:last').height() > 0) {
                resizeSingleImage($('.series figure.episode-posterframe img'));
			    clearInterval(VarImgLoading);
		}
	}
	if ($('.series figure img').length > 0)
	    var VarImgLoading = setInterval(function(){ checkImgLoading() }, 100);

	//Anchor Oktothek und Serie:
	$('#button_down').click(function() {
		if ($('body').hasClass('oktothek')) {
		    var offset = $('#oktothek').offset();
		} else if ($('body').hasClass('series')) {
			var offset = $('section.series-description').offset();
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


    $(window).on("resize orientationchange", function(){
        if ($('.series figure.episode-posterframe img').length > 0)
           resizeSingleImage($('.series figure.episode-posterframe img'));
    });

	/*Sharebuttons*/
	$('#sharingurl').val(window.location.href);

	
	if ($( window ).width() >= 768) {
		
		/* Tooltips: */
	    if (
	        $('body').hasClass('oktothek') ||
		    $('body').hasClass('episode') ||
	        $('body').hasClass('series')) {
	        $('[data-toggle="tooltip"]').tooltip({'placement': 'auto right'});
	    }
		
		/*Ankermenü*/
	    var Ts_offset = 250;
        var Ts_duration = 300;
		var Footer_target = $('#anchor-menu li:last a').attr('href');
	    var Footer_offset = $(Footer_target).offset();
		if (!$('body').hasClass('fullscreen-images')) {
		    $('#anchor-menu').fadeIn(Ts_duration);
		}
		if ($('#anchor-menu').length > 0) {
            $(window).scroll(function() {
			    //"Mehr" Button anzeigen
                if ($('body').hasClass('fullscreen-images') && $(this).scrollTop() > Ts_offset && ($(this).scrollTop()+$(window).height()) < Footer_offset.top) {
                    $('#anchor-menu').fadeIn(Ts_duration);
				} else if (!$('body').hasClass('fullscreen-images') && $(this).scrollTop() >= 0 && ($(this).scrollTop()+$(window).height()) < Footer_offset.top) {
                    $('#anchor-menu').fadeIn(Ts_duration);
                } else {
                    $('#anchor-menu').fadeOut(Ts_duration);
                }
			    //Top Button anzeigen
		 	    $('#anchor-menu .collapse').collapse('hide'); //Ankermenü einklappen bei Scrollen
            });
            //Anker Menü
            $('#anchor-menu .list-group-item a').click(function(event) {
                event.preventDefault();
			    var target = $(this).attr('href');
			    var offset = $(target).offset();
			    $("html, body").animate({scrollTop : offset.top - headerHeight + "px"}, Ts_duration);
                return false;
            })
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

});
