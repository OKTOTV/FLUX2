// JavaScript Document
$(document).ready(function(){
	/* Headerbackground */
	var headerScrollheight = 50;
	
	function addHeaderBG() {
	  if ($('body').hasClass('fullscreen-images')) {
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
	var borderbottom = 70;
	 
    function ImageRatio(el) {
        aspectRatio = $(el).height() / $(el).width();
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
		Winwidth;

        if (items.length) {
			
            function normalizeHeights() {
				var Winheight = $( window ).height() - borderbottom;
				var Winwidth = $( window ).width();
				
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
	$('.fullscreen-images .carousel img').load(function() {
        carouselNormalization($('.fullscreen-images .carousel'));
	});
	
	 function resizeSingleImage(el) {
        var _ref;
		var Winwidth = $( window ).width();
		var Winheight = $( window ).height() - borderbottom - $('header .navbar').height();

        if ((_ref = el.ratio) == null) {
			el.ratio =ImageRatio(el);
           // image.ratio = (image.height() / image.width()).toFixed(2);
        }
		
		//Bildgröße berechnen lassen:
		resizeImage(el, el.parent('figure').parent('div'), el.ratio);
		
    };
	
	$('.series figure.episode-posterframe img').load(function() {
	    if ($('.series figure img').length > 0)
            resizeSingleImage($('.series figure.episode-posterframe img'));
	});
	
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
	$('#collapseShare .sharingnav button').click(function (e) {
        //e.preventDefault();
		$('#collapseShare .tab-content div').removeClass('active');
		$('#collapseShare .sharingnav button').removeClass('active');
		switch($(this).attr('id')) {
            case "share_sn":
                $('#collapseShare #div_share_sn').addClass('active');
                break;
            case "share_embed":
                $('#collapseShare #div_share_embed').addClass('active');
                break;
            default:
                $('#collapseShare #div_share_sn').addClass('active');
        } 
		$(this).addClass('active');
    })
	 
	/* Oktothek: */
	if (
	    $('body').hasClass('oktothek') ||
		$('body').hasClass('episode') || 
	    $('body').hasClass('series')) {
	    $('[data-toggle="tooltip"]').tooltip({'placement': 'auto right'}); 
	}
	 
	function loadWinHeight() {
	    var Winheight = $( window ).height();
		              
	}
	 
	function resizeWinHeight() {
	
	}
    $(window).on("resize orientationchange", function(){
        if ($('.series figure.episode-posterframe img').length > 0)
           resizeSingleImage($('.series figure.episode-posterframe img'));
		   
    });
	
	/*Sharebuttons*/
	$('#sharingurl').val(window.location.href);
	 
});
