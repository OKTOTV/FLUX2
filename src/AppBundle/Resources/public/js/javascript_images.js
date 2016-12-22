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
    //Playergröße festlegen (kann nicht abgefragt werden, da am Beginn noch nicht vorhanden)
    if ($('body').hasClass('playlists')) {
        var playerheight;
        function sizePlayerheight() {
                
            if ($('.container').width >= 1170) {
                playerheight = "658px";
            } else if ($('.container').width >= 970) {
                playerheight = "546px";
            } else if ($('.container').width >= 750) {
                playerheight = "422px";
            } else {
                playerheight = "auto";
            }
        }
    }
    
	
	//Containerelement auf Windowgröße setzen
	
	
	
		
/*Slider*/

	function posDescription(container) {
        if (Winwidth >= 768) {
            if ($('body').hasClass('playlists')) {
                sizePlayerheight();
		        $(container).css('height',playerheight);
            } else
                $(container).css('height',Winheight);
            
			if ($(container).hasClass('fs-image-ident'))
			    $('.fs-image').css('height','100%');
		} else {
            $(container).css('height','auto');
			if ($(container).hasClass('fs-image-ident')) {/*== $('.fs-image-ident')*/
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
   if ($('.description-overlay figure').length > 0) {
		posDescription($('.description-overlay'));
	}
	
	$(window).on("resize orientationchange", function(){
		if ($('.description-overlay figure').length > 0) {
		    Winheight = $( window ).height() - borderbottom;
	        Winwidth = $( window ).width();
			posDescription($('.description-overlay'));
		}
    });
    
    function changeCarouselOpacity() {
        if ($(window).width() >= 768) {
            $('#carousel figcaption a').mouseenter(function(){
                $('#carousel figcaption .bg-opacity').animate({
                    opacity: 0.8
                }, 200);
            });
            $('#carousel figcaption a').mouseleave(function(){
                $('#carousel figcaption .bg-opacity').animate({
                    opacity: 0.7
                }, 200);
            });
        } else {
            $('#carousel figcaption .bg-opacity').removeAttr('style');
        }
        
        /*$('.participant-arrow-down').mouseenter(function(){
            $(this).addClass('jump');
        });
        $('.participant-arrow-down').mouseleave(function(){
            $(this).removeClass('jump');
        });*/
    }
    
    changeCarouselOpacity();
    $(window).on("resize orientationchange", function(){
        changeCarouselOpacity();
    });
    
	
	//Anchor Oktothek und Serie:
	$('#button_down').click(function() {
		if ($('body').hasClass('oktothek')) {
		    var offset = $('#oktothek').offset();
		} else if ($('body').hasClass('series')) {
			var offset = $('section.episode-description').offset();
		}  else if ($('body').hasClass('academy')) {
			var offset = $('section.academy-description').offset();
		}
		if (offset) {
		    $("html, body").animate({scrollTop : (offset.top - $('header .navbar').height()) + "px"}, "slow");
		    $( this ).find('span').css('display','none');
		}
	});
    
    $('.anchor').click(function(event) {
        event.preventDefault();
        var offsetname = $(this).attr('href');
        var offset = $(offsetname).offset();
		
		if (offset) {
		    $("html, body").animate({scrollTop : (offset.top - $('header .navbar').height()) + "px"}, "slow");
		    $( this ).find('span').css('display','none');
		}
	});
	
	function showButtonDown() {
	      if($(document).scrollTop() <= headerScrollheight) {
			  //Downbutton erscheinen lassen
			  $('.fullscreen-images #button_down span').css('display','inline');
	      } else if ($(document).scrollTop() > headerScrollheight) {
			  //Downbutton ausblenden
			  $('.fullscreen-images #button_down span').css('display','none');
		   }
	 }

	 showButtonDown();

	 $(document).scroll(function(){
	     showButtonDown();
	 });
	
	/* Serien und Akademie */
	function Textproperties() {
		if (Winwidth >= 768 && Winheight >600) {
	        ShortString = descriptionString.substr(0,200) + '...';
			LinkString = 'weiterlesen';
		} else if (Winwidth >= 768 && Winheight > 450) {
			ShortString = descriptionString.substr(0,100) + '...';
			LinkString = 'weiterlesen';
		} else if (Winwidth >= 768 && Winheight <= 450) {
			ShortString = "";
			LinkString = 'Inhalt lesen';
		} else if (Winwidth >= 400) {
			ShortString = descriptionString.substr(0,100) + '...';
			LinkString = 'weiterlesen';
	    } else if (Winwidth < 400) {
		    ShortString = "";
			LinkString = 'Inhalt lesen';
	    }
			
		_Textproperties = new Array(ShortString, LinkString);
		return _Textproperties;
	}
	
	function Animate_Wrapper() {
		if (layer == false) {    //Fenster wird geöffnet
			if (Winwidth >= 768 && Winheight >600) {
				_top = '0px';
				_height = '100%';
				_paddingTop = '20px';
				_position = 'absolute';
			} else if (Winwidth >= 768 && Winheight <= 600) {
				_top = '0px';
				_height = '100%';
				_paddingTop = '0px';
				_position = 'fixed';
			} else {
				_top = '50px';
				_height = Winheight + 20;
				_paddingTop = '0px';
				_position = 'fixed';
			}
			_bottom = '0px';
			_left = '0px';
			_width = '100%';
			_marginLeft = '0px';
			_paddingLeft = '0px';
			_paddingRight = '0px';
		} else {    //Fenster wird geschlossen
            if (Winwidth >= 1200) {
                origTop = $('.description-overlay figure').height() - origHeight - 100;
                _bottom = '100px';
				_left = '50%';
				_width = '650px';
				_marginLeft = '-325px';
				_paddingLeft = '0px';
				_paddingRight = '0px';
				_position = 'absolute';
            } else if (Winwidth >= 992) {
                origTop = $('.description-overlay figure').height() - origHeight - 100;
                if ($('body').hasClass('playlists')) {
                   _bottom = '30px';
                } else {
                    _bottom = '100px';
                }
				_left = '50%';
				_width = '650px';
				_marginLeft = '-325px';
				_paddingLeft = '0px';
				_paddingRight = '0px';
				_position = 'absolute';
            } else if (Winwidth >= 768 && Winheight > 600) {
				origTop = $('.description-overlay figure').height() - origHeight - 100;
                if ($('body').hasClass('playlists')) {
                   _bottom = '30px';
                } else {
                    _bottom = '100px';
                }
				_left = '50%';
				_width = '650px';
				_marginLeft = '-325px';
				_paddingLeft = '0px';
				_paddingRight = '0px';
				_position = 'absolute';
			} else if (Winwidth >= 768 && Winheight < 600) {
				origTop = $('.description-overlay figure').height() - origHeight - 30;
				_bottom = '30px';
				_left = '50%';
				_width = '650px';
				_marginLeft = '-325px';
				_paddingLeft = '0px';
				_paddingRight = '0px';
				_position = 'absolute';
		    } else if (Winwidth > 400) {
				origTop = $('.description-overlay figure').height() - origHeight - 30;
				_bottom = '30px';
				_left = '5%';
				_width = '90%';
				_marginLeft = '0px';
				_paddingLeft = '15px';
				_paddingRight = '15px';
				_position = 'absolute';
		    } else if (Winwidth <= 400) {
				origTop = $('.description-overlay figure').height() - origHeight - 20;
				_bottom = '20px';
				_left = '5%';
				_width = '90%';
				_marginLeft = '0px';
				_paddingLeft = '15px';
				_paddingRight = '15px';
				_position = 'absolute';
		    }
			_height = origHeight + 'px';
			_paddingTop = '0px';
			_top = origTop + 'px';
		}
		
		$('.description-overlay .description-wrapper')
		    .animate(
		    {
                top : _top,
			    bottom : _bottom,
                left : _left,
			    width : _width,
			    height : _height,
			    marginLeft : _marginLeft,
			    paddingLeft : _paddingLeft,
			    paddingRight :_paddingRight,
			    paddingTop :_paddingTop
            }, speed)
		    .css('position',_position);
	}
	function Animate_Content() {
		if (layer == false) {
			if (Winwidth >= 768 && Winheight > 600) {
			    _marginTop = (headerHeight + 20) + 'px';
			    _paddingLeft = '60px';
			    _paddingRight = '60px';
				_paddingTop = '15px';
				_String = descriptionString;
			} else if (Winwidth >= 768 && Winheight <= 600) {
				_marginTop = (headerHeight) + 'px';
			    _paddingLeft = '60px';
			    _paddingRight = '60px';
				_paddingTop = '0px';
				_String = descriptionString;
			} else {
				_marginTop = '20px';
			    _paddingLeft = '60px';
			    _paddingRight = '60px';
				_paddingTop = '15px';
				_String = descriptionString;
			}
		} else {
	        _marginTop = '0px';
			_paddingLeft = '15px';
			_paddingRight = '15px';
			_paddingTop = '15px';
			_String = Descriptiontext[0];
		}
		
		$('.description-overlay .content-opacity').animate(
		{
		    marginTop : _marginTop,
			paddingLeft : _paddingLeft,
			paddingRight : _paddingRight,
			paddingTop : _paddingTop
		}, speed, function() {
			$('figcaption .description').html(_String);
	    });
	}
	
	if ($('div').hasClass('description-overlay')) {
		var descriptionString = $('.description-overlay .description').text();
		Descriptiontext = Textproperties();
		$('.description-overlay .description').html(Descriptiontext[0]);
		$('.description-overlay .description-wrapper a.more').text(Descriptiontext[1]);
		var layer = false;
		var resize = false;
		var origHeight;
		var origHeightSafe = null;
		var speed = 'fast';
	}
	
	$('.description-overlay .description-wrapper a.more').click(function(){
		
		if (layer === false) {    //Fenster wird geöffnet
			origHeight = $('.description-overlay .description-wrapper').height() + 10;
            if ($('body').hasClass('playlists')) 
                $('.btn-close').css('display','none');
			Animate_Wrapper();
			
			if (Winwidth >= 768 && Winheight >600) {
				$('.bg-opacity').animate(
		        {
			        marginRight:'0px'
                }, speed);
				//Scrollbalken
				$('body').css('overflow','auto');
				if ($('.description-overlay .description-wrapper').hasClass('details'))
				    $('.description-overlay .description-wrapper').removeClass('details');
			} else if (Winwidth >= 768 && Winheight < 600) {
				$('.bg-opacity').animate(
		        {
			        marginRight:'0px',
					opacity:'1.0'
                }, speed);
				//Scrollbalken
				$('body').css('overflow','hidden');
				if (!$('.description-overlay .description-wrapper').hasClass('details'))
				    $('.description-overlay .description-wrapper').addClass('details');
			} else {
				$('.fs-image-misc-mobile').css('display','none');
				$('.bg-opacity').animate(
		        {
			        marginRight:'0px',
					opacity:'1.0'
                }, speed);
				//Scrollbalken
				$('body').css('overflow','hidden');
				if (!$('.description-overlay .description-wrapper').hasClass('details'))
				    $('.description-overlay .description-wrapper').addClass('details');
			}
			
			Animate_Content();
			$('.description-overlay .facts').css('display','block');
			$('.description-overlay .misc').css('display','block');
			$('.fs-image-misc-mobile').css('display','none');
			$(this).text('zurück');
			layer = true;
		} else {    //Fenster wird geschlossen
            if ($('body').hasClass('playlists'))
                $('.btn-close').css('display','block');
		    if (resize == true) {
				if (origHeightSafe === null) {
					origHeightSafe = origHeight;
				    if (Winwidth >= 768) {
				        origHeight = 300;
					} else {
						origHeight = 150;
					}
			    } else {
					origHeight = origHeightSafe;
					origHeightSafe = null;
				}		
			}
			resize = false;
			
			Descriptiontext = Textproperties();
			
			Animate_Wrapper();
			
			if (Winwidth < 768) {
				$('.description-overlay .facts').css('display','none');
			    $('.description-overlay .misc').css('display','none');
			    $('.fs-image-misc-mobile').css('display','block');
			} else {
				$('.description-overlay .facts').css('display','block');
			    $('.description-overlay .misc').css('display','block');
			    $('.fs-image-misc-mobile').css('display','none');
			}
		    $('.bg-opacity').animate(
		    {
			    marginRight:'15px',
				opacity:'0.7'
            }, speed);
			
			Animate_Content();
			$('.description-overlay .description-wrapper a.more').text(Descriptiontext[1]);
			
			//Scrollbalken
			$('body').css('overflow','auto');
			if ($('.description-overlay .description-wrapper').hasClass('details'))
				    $('.description-overlay .description-wrapper').removeClass('details');
			layer = false;
		}
		
	});
	var winheight = $( window ).height();
	$(window).on("resize orientationchange", function(){
		Sizes();
  		var winwidth = $( window ).width();
		var winheight = $( window ).height();
		//console.log('Breite:' + winwidth + 'Höhe' + winheight);
		if (layer === false) {    //Fenster ist geschlossen 
			Descriptiontext = Textproperties();
		    $('.description-overlay .description').html(Descriptiontext[0]);
			$('.description-overlay .description-wrapper a.more').text(Descriptiontext[1]);
			
			$('.description-overlay .description-wrapper')
			    .css('top','auto')
				.css('height','auto')
				.css('padding-left','15px')
				.css('padding-right','15px');
            if (Winwidth >= 1200) {
                $('.description-overlay .description-wrapper')
				    .css('bottom','100px')
					.css('left','50%')
				    .css('width','650px')
				    .css('margin-left','-325px');
                $('.description-overlay .facts').css('display','block');
			    $('.description-overlay .misc').css('display','block');
			    $('.fs-image-misc-mobile').css('display','none');
            } else if (Winwidth >= 992) {
                if ($('body').hasClass('playlists')) {
                     $('.description-overlay .description-wrapper')
				        .css('bottom','30px')
					    .css('left','50%')
				        .css('width','650px')
				        .css('margin-left','-325px');
                } else {
                     $('.description-overlay .description-wrapper')
				        .css('bottom','100px')
					    .css('left','50%')
				        .css('width','650px')
				        .css('margin-left','-325px');
                }
				$('.description-overlay .facts').css('display','block');
			    $('.description-overlay .misc').css('display','block');
			    $('.fs-image-misc-mobile').css('display','none');
            } else if (Winwidth >= 768 && Winheight >600) {
                if ($('body').hasClass('playlists')) {
                    $('.description-overlay .description-wrapper')
				        .css('bottom','30px')
					    .css('left','50%')
				        .css('width','650px')
				        .css('margin-left','-325px');
                } else {
                    $('.description-overlay .description-wrapper')
				    .css('bottom','100px')
					.css('left','50%')
				    .css('width','650px')
				    .css('margin-left','-325px');
                }
				$('.description-overlay .facts').css('display','block');
			    $('.description-overlay .misc').css('display','block');
			    $('.fs-image-misc-mobile').css('display','none');
			} else if (Winwidth >= 768 && Winheight < 600) {
		        $('.description-overlay .description-wrapper')
					.css('bottom','30px')
					.css('left','50%')
				    .css('width','650px')
				    .css('margin-left','-325px');
				$('.description-overlay .facts').css('display','block');
			    $('.description-overlay .misc').css('display','block');
			    $('.fs-image-misc-mobile').css('display','none');
		    } else if (Winwidth > 400) {
			    $('.description-overlay .description-wrapper')
					.css('bottom','30px')
					.css('left','5%')
				    .css('width','90%')
				    .css('margin-left','0px');
				$('.description-overlay .facts').css('display','none');
			    $('.description-overlay .misc').css('display','none');
			    $('.fs-image-misc-mobile').css('display','block');
		    } else if (Winwidth <= 400) {
				$('.description-overlay .description-wrapper')
					.css('bottom','20px')
					.css('left','5%')
				    .css('width','90%')
				    .css('margin-left','0px');
				$('.description-overlay .facts').css('display','none');
			    $('.description-overlay .misc').css('display','none');
			    $('.fs-image-misc-mobile').css('display','block');
			}
		} else if (layer === true) {    //Fenster ist geöffnet
			resize = true;
			$('.description-overlay .description-wrapper')
				.css('bottom','0px')
				.css('left','0px')
			    .css('width','100%')
			    .css('height',winheight)
			    .css('margin-left','0px')
				.css('padding-left','0px')
				.css('padding-right','0px');
				
			$('.description-overlay .content-opacity').css('margin-top', (headerHeight + 20) + 'px');
			$('.description-overlay .facts').css('display','block');
			$('.description-overlay .misc').css('display','block');
			$('.fs-image-misc-mobile').css('display','none');
			
			if (Winwidth >= 768 && Winheight > 600) {
				$('.description-overlay .description-wrapper')
				    .css('padding-top','10px')
					.css('top','0px')
					.css('position','absolute');
				$('.description-overlay .bg-opacity').css('opacity','0.7');
				//Scrollbalken
				$('body').css('overflow','auto');
				if ($('.description-overlay .description-wrapper').hasClass('details'))
				    $('.description-overlay .description-wrapper').removeClass('details');
			} else if (Winwidth >= 768 && Winheight < 600) {
				$('.description-overlay .description-wrapper')
				    .css('padding-top','10px')
					.css('top','0px')
					.css('position','fixed');
				$('.description-overlay .bg-opacity').css('opacity','1.0');
				//Scrollbalken
				$('body').css('overflow','hidden');
				if (!$('.description-overlay .description-wrapper').hasClass('details'))
				    $('.description-overlay .description-wrapper').addClass('details');
			} else {
				$('.description-overlay .description-wrapper')
				    .css('padding-top','0px')
					.css('top','0px')
					.css('position','fixed');
				$('.description-overlay .bg-opacity').css('opacity','1.0');
				$('.description-overlay .content-opacity').css('padding-top','15px');
				//Scrollbalken
				$('body').css('overflow','hidden');
				if (!$('.description-overlay .description-wrapper').hasClass('details'))
				    $('.description-overlay .description-wrapper').addClass('details');
			}
		}
		//console.log(winheight);
	});
	//console.log(winheight);
	
});