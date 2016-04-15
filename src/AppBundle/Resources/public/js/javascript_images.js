// JavaScript Document
$(document).ready(function(){
	var borderbottom = 70;
    var Winheight = $( window ).height() - borderbottom;
    var Winwidth = $( window ).width();
	var carouselslide = false;
	
	//Containerelement auf Windowgröße setzen
	
	
	
		
/*Slider*/

	function posDescription(container) {
		if (Winwidth >= 768) {
		    $(container).css('height',Winheight);
			if ($(container).hasClass('fs-image-ident'))
			    $('.fs-image').css('height','100%');
		} else {
			if ($(container).hasClass('fs-image-ident')) {/*== $('.fs-image-ident')*/
			    $(container).css('height','auto');
				$('.fs-image').css('height',(Winwidth/16)*9);
			}
		}
	}

    function ImageRatio(el) {
        var aspectRatio = $(el).prop('naturalHeight') / $(el).prop('naturalWidth');
        return aspectRatio;
    }

	function resizeImage(el, container, _ratio) {

		if (Winheight / _ratio > Winwidth) {
			newMargin = (Winwidth - (Winheight / _ratio)) / 2;
			$(el).css('height', Winheight + 'px').css('width', (Winheight / _ratio) + 'px').css('margin-left', newMargin + "px");
        } else {
			$(el).css('height',(Winwidth * _ratio) + 'px').css('width',Winwidth + 'px').css('margin-left','0px');
        }
		//$(el).fadeIn(600);
	}
	
	function resizeImageMobile(el, container, _ratio) {
		_parentContainer = $(container).find('figure').parent('div');
		if (_parentContainer.css('height') != "auto")
			$(container).find('figure').parent('div').css('height','auto');
		el.css('height',(Winwidth * _ratio) + 'px').css('width',Winwidth + 'px').css('margin-left','0px');
	}

	 /*Alle Bilder auf gleiche Höhe bringen (nach der kleinsten Höhe)*/
	function carouselNormalization(container) {
        var items = $(container).find('.item'), //grab all slides
		aspectRatio;

        if (items.length) {

            function normalizeHeights() {

				aspectRatio = ImageRatio($(items[0]).find('img'));
				var i=0;
				//Bildgröße berechnen lassen:
				if (Winwidth >= 768) {
					resizeImage($(items).find('img'), container, aspectRatio);
				} else {
					resizeImageMobile($(items).find('img'), container, aspectRatio);
				}
				if (carouselslide == false) {
					$('.preloader').fadeOut('slow','linear',function(){
				        $('.carousel').carousel();
					    carouselslide = true;
					});
				}
            };
            normalizeHeights();

            $(window).on('resize orientationchange', function () {
				Winheight = $( window ).height() - borderbottom;
				Winwidth = $( window ).width();
				posDescription($('#slider .carousel-inner'));
                normalizeHeights(); //run it again
            });
        }
    }
	
	/* Slider an Monitor anpassen*/
	if ($('#slider').length > 0) {
		posDescription($('#slider .carousel-inner'));
		$('#slider figure a').imagesLoaded()
            .done( function( instance ) {
                carouselNormalization($('.fullscreen-images .carousel'));
        });
	}
	
	/* Posterframe an Monitor anpassen*/
   if ($('.fs-image-ident figure').length > 0) {
		posDescription($('.fs-image-ident'));
	}
	
	$(window).on("resize orientationchange", function(){
		if ($('.fs-image-ident figure').length > 0) {
		    Winheight = $( window ).height() - borderbottom;
	        Winwidth = $( window ).width();
			posDescription($('.fs-image-ident'));
		}
    });
	
	
});