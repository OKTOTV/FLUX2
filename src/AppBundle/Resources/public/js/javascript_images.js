// JavaScript Document
$(document).ready(function(){
	var borderbottom = 70;
    var Winheight = $( window ).height() - borderbottom;
    var Winwidth = $( window ).width();
	var carouselslide = false;
	var headerHeight;
	
	function Sizes() {
		if (Winwidth >= 768) {
	        headerHeight = 180;
	    } else {
		    headerHeight = 50;
	    }
	}
	Sizes();
	
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
	
	/* Serien und Akademie */
	function DescriptionText() {
		if (Winwidth >= 768) {
	        lengthText = 200;
	    } else {
		    lengthText = 100;
	    }
		ShortString = $('figcaption .description').html(descriptionString.substr(0,lengthText) + '...');
		return ShortString;
	}
	
	if ($('section').hasClass('fs-image-content')) {
		var descriptionString = $('figcaption .description').text();
		DescriptionText();
		var layer = false;
		var resize = false;
		var origHeightSafe = null;
	}
	
	$('figcaption.description-wrapper a.more').click(function(){
		
		if (layer === false) {    //Fenster wird geöffnet
			origHeight = $('.fs-image-ident .description-wrapper').height() + 10;
			if (Winwidth >= 768) {
				$('.fs-image-ident .description-wrapper').animate(
		        {
                    top: '0px',
					bottom: '0px',
                    left: '0px',
					width: '100%',
			        height: '100%',
			        marginLeft:'0px',
			        paddingLeft:'0px',
			        paddingRight:'0px',
					paddingTop:'20px'
                }, 'fast')
				.css('position','absolute')
				.css('overflow','visible');
				$('.bg-opacity').animate(
		        {
			        marginRight:'0px'
                }, 'fast');
		        $(this).parents('.content-opacity').animate(
		        {
			        marginTop: (headerHeight + 20) + 'px',
			        paddingLeft:'60px',
			        paddingRight:'60px'
		        }, 'fast', function() {
			        $('figcaption .description').html(descriptionString);
			    });
			} else {
				$('.fs-image-ident .description-wrapper')
				    .animate(
		            {
					    top: '50px',
                        bottom: '0px',
                        left: '0px',
					    width: '100%',
			            height: Winheight + 20,
			            marginLeft:'0px',
			            paddingLeft:'0px',
			            paddingRight:'0px',
					    paddingTop:'0px'
                    }, 'fast')
				    .css('position','fixed')
					.css('overflow','auto');
				$('.fs-image-misc-mobile').css('display','none');
				$('.bg-opacity').animate(
		        {
			        marginRight:'0px',
					opacity:'1.0'
                }, 'fast');
				$(this).parents('.content-opacity').animate(
		        {
			        marginTop: '20px',
			        paddingLeft:'60px',
			        paddingRight:'60px'
		        }, 'fast', function() {
			        $('figcaption .description').html(descriptionString);
			    });
			}
			$(this).text('zurück');
			layer = true;
		} else {    //Fenster wird geschlossen
		    if (resize == true) {
					if (origHeightSafe === null) {
					    origHeightSafe = origHeight;
						if (Winwidth >= 768) {
				            origHeight = 300;
					    } else {
						    origHeight = 125;
					    }
					} else {
						origHeight = origHeightSafe;
						origHeightSafe = null;
					}
					
				}
				resize = false;
			if (Winwidth >= 768) {
				origTop = $('.fs-image-ident figure').height() - origHeight - 100;
		        $('.fs-image-ident .description-wrapper').animate(
		        {
					top: origTop + 'px',
                    bottom: '100px',
                    left: '50%',
					width: '650px',
			        height: origHeight + 'px',
			        marginLeft:'-325px',
			        paddingLeft:'15px',
			        paddingRight:'15px',
					paddingTop:'0px'
                }, 'fast')
				.css('position','absolute')
				.css('overflow','visible');
			} else {
				origTop = $('.fs-image-ident figure').height() - origHeight - 30;
			    $('.fs-image-ident .description-wrapper').animate(
		        {
					top: origTop + 'px',
					bottom: '30px',
			        left: '5%',
					width: '90%',
			        height: origHeight + 'px',
			        marginLeft:'0px',
			        paddingLeft:'15px',
			        paddingRight:'15px',
					paddingTop:'0px'
                }, 'fast')
				.css('position','absolute')
				.css('overflow','visible');
				$('.fs-image-misc-mobile').css('display','none');
			}
		    $('.bg-opacity').animate(
		    {
			    marginRight:'15px',
				opacity:'0.7'
            }, 'fast');
		    $(this).parents('.content-opacity').animate(
		    {
			    marginTop: '0px',
			    paddingLeft:'15px',
			    paddingRight:'15px'
		    }, 'fast', function() {
			        DescriptionText();
			});
			$(this).text('weiterlesen');
			layer = false;
		}
		
	});
	var winheight = $( window ).height();
	$(window).on("resize orientationchange", function(){
		Sizes();
  		var winwidth = $( window ).width();
		var winheight = $( window ).height();
		if (layer === false) {
			DescriptionText();
			$('.fs-image-ident .description-wrapper')
			    .css('top','auto')
				.css('height','auto')
				.css('padding-left','15px')
				.css('padding-right','15px');
		    if (Winwidth >= 768) {
		        $('.fs-image-ident .description-wrapper')
					.css('bottom','100px')
					.css('left','50%')
				    .css('width','650px')
				    .css('margin-left','-325px');
		    } else {
			    $('.fs-image-ident .description-wrapper')
					.css('bottom','30px')
					.css('left','5%')
				    .css('width','90%')
				    .css('margin-left','0px')
				    .css('display','block');
		    }
		} else {
			resize = true;
			$('.fs-image-ident .description-wrapper')
			    .css('top','0px')
				.css('bottom','0px')
				.css('left','0px')
			    .css('width','100%')
			    .css('height',winheight)
			    .css('margin-left','0px')
				.css('padding-left','0px')
				.css('padding-right','0px');
			if (Winwidth >= 768) {
				$('.fs-image-ident .description-wrapper').css('padding-top','10px');
				$('.fs-image-ident .bg-opacity').css('opacity','0.7');
				$('.fs-image-ident .content-opacity').css('margin-top','200px');
			} else {
				$('.fs-image-ident .description-wrapper').css('padding-top','0px')
				$('.fs-image-ident .bg-opacity').css('opacity','1.0');
				$('.fs-image-ident .content-opacity').css('margin-top','20px');
				$('.fs-image-misc-mobile').css('display','none');
			}
		}
		//console.log(winheight);
	});
	//console.log(winheight);
	
});