// JavaScript Document
$(document).ready(function(){
	var borderbottom = 70;
    var Winheight = $( window ).height() - borderbottom;
    var Winwidth = $( window ).width();
	
	//Containerelement auf Windowgröße setzen
	
	/* Slider an Monitor anpassen*/
	if ($('#slider').length > 0) {
		posDescription($('#slider .carousel-inner'));
	    var VarImgArrayLoading = setInterval(function(){ checkImgArrayLoading() }, 100);
	}
	
		
/*Slider*/

	function posDescription(container) {
		$(container).css('height',Winheight);
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
		$(el).fadeIn(600);
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

	//Bildgröße für Slider erst berechnen lassen, wenn die Höhe existiert
	function checkImgArrayLoading() {
		if($('#slider .carousel-inner img:first').height() > 0) {
                carouselNormalization($('.fullscreen-images .carousel'));
			    clearInterval(VarImgArrayLoading);
		}
	}

	 function resizeSingleImage(el) {
        var aspectRatio;
		
        if ((aspectRatio = el.ratio) == null) {
			el.ratio =ImageRatio(el);
        }

		//Bildgröße berechnen lassen:
		if (Winwidth >= 768) {
		    resizeImage(el, el.parents('div.fs-image-ident'), el.ratio);
		} else {
	        resizeImageMobile(el, el.parents('.fs-image-ident'), el.ratio);
		}
    };
	
	$(window).on("resize orientationchange", function(){
		if ($('.fs-image-ident figure img').length > 0) {
		    Winheight = $( window ).height() - borderbottom;
	        Winwidth = $( window ).width();
			if (Winwidth >= 768) {
				posDescription($('.fs-image-ident'));
			}
	        //Episodenposterframe in Größe anpassen
            if ($('figure.fs-image img').length > 0)
                resizeSingleImage($('figure.fs-image img'));
		}
    });

	//Bildgröße für Einzelbilder erst berechnen lassen, wenn die Höhe existiert
	function checkImgLoading() {
		//console.log($('figure.fs-image img').prop('naturalHeight'));
		if($('figure.fs-image img').prop('naturalHeight') > 0) {
          resizeSingleImage($('figure.fs-image img'));
		  clearInterval(VarImgLoading);
		}
	}
	
	/* Posterframe an Monitor anpassen*/
    if ($('.fs-image-ident figure img').length > 0) {
		posDescription($('.fs-image-ident'));
		checkImgLoading();
	    var VarImgLoading = setInterval(function(){ checkImgLoading() }, 100);
	}
	
	
});