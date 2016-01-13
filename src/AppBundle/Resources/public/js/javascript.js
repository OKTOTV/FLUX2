// JavaScript Document
$(document).ready(function(){
	/* Headerbackground */
	var headerScrollheight = 50;
	
	function addHeaderBG() {
	  if ($('body').hasClass('slider')) {
	      if($(document).scrollTop() <= headerScrollheight) {
			  //Header transparent setzen und Schlagschatten entfernen
	          $('body').addClass('head-white-color')
		               .removeClass('head-white-color')
				       .addClass('head-transparent-bg')
				       .removeClass('head-black-bg');
		      //$('.navbar-fixed-top').removeClass('dropshadow'); //Schlagschatten
			  
			  //Downbutton erscheinen lassen
			  $('.slider #button_down span').css('display','inline');
			  
	      } else if ($(document).scrollTop() > headerScrollheight) {
			  //Header schwarz setzen und Schlagschatten hinzufügen
		      $('body').removeClass('head-white-color')
			          .addClass('head-white-color')
					  .addClass('head-black-bg')
					  .removeClass('head-transparent-bg');
		      //$('.navbar-fixed-top').addClass('dropshadow'); //Schlagschatten
			  
			  //Downbutton ausblenden
			  $('.slider #button_down span').css('display','none');
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
	var borderbottom = 60;
	 
    function Imagesize(el) {
        aspectRatio = $(el).width() / $(el).height();
        return aspectRatio;
    }
	 
	 /*Alle Bilder auf gleiche Höhe bringen (nach der kleinsten Höhe)*/
	function carouselNormalization() {
        var items = jQuery('#slider .item'), //grab all slides
        width = [], //create empty array to store height values
        //shortest; //create variable to make note of the shortest slide
		Winheight,
		Winwidth;

        if (items.length) {
			
            function normalizeHeights() {
				var Winheight = $( window ).height() - borderbottom;
				var Winwidth = $( window ).width();
				
				if (width.length == 0) { //beim ersten Mal Array füllen
                	items.each(function() { //Aspect Ratio Array hinzufügen
						aspectRatio = Imagesize(this);
                    	width.push(aspectRatio);
                	});
				}
				
				var i=0;
                items.each(function() {
					
                    jQuery('.carousel-inner').css('height',Winheight + 'px').css('overflow','hidden');
					
					newWidth = Winheight * width[i];
					if (newWidth >= Winwidth) {
						newMargin = (Winwidth - newWidth) / 2;
						jQuery('.carousel-inner .item img').css('height',Winheight + 'px').css('width',newWidth + 'px').css('margin-left',newMargin + "px");
					} else {
						newHeight = parseInt(Winwidth / width[i]);
					    jQuery('.carousel-inner .item img').css('height',newHeight + 'px').css('width',Winwidth + 'px').css('margin-left','0px');
					}
					i++;
                });
            };
            normalizeHeights();

            jQuery(window).on('resize orientationchange', function () {
                Winheight = 0, Winwidth = 0; //reset vars
                normalizeHeights(); //run it again
            });
        }
    }
    carouselNormalization();
	
	 function resizeImage(el) {
        var image, _ref;
		var Winwidth = $( window ).width();
		var Winheight = $( window ).height() - borderbottom;

        image = $(el);
        if ((_ref = image.ratio) == null) {
           // image.ratio = (image.height() / image.width()).toFixed(2);
		    image.ratio = image.height() / image.width();
        }
		console.log($(el).height() + "+" + $(el).width() + "+" + image.ratio);
        if ($(window).height() / image.ratio > Winwidth) {
            $(image).width(Winwidth);
            $(image).height(Winwidth * image.ratio);
        } else {
            $(image).height(Winheight);
            $(image).width(Winheight / image.ratio);
        }
    };
	//Fullscreen Images (Serien,...)
	//function ImageToFullscreen(el) {
		
		//aspectRatio = Imagesize(el);
		//$(el).width(Winwidth);
		//$(el).height(Winwidth * aspectRatio);
	//}
	if ($('.series figure img').length > 0)
        resizeImage($('.series figure.series-posterframe img'));
	
	//Anchor:
	$('.slider #button_down').click(function() {
		console.log('click');
		var offset = $('#oktothek').offset();
		$("html, body").animate({scrollTop : (offset.top - $('header .navbar').height()) + "px"}, "slow");
		$( this ).find('span').css('display','none');
	});
	
	//Sharing Tabs:
	$('#collapseShare .sharingnav button').click(function (e) {
        //e.preventDefault();
		$('.tab-content div').removeClass('active');
		switch($(this).attr('id')) {
            case "share_sn":
                $('#div_share_sn').addClass('active');
                break;
            case "share_embed":
                $('#div_share_embed').addClass('active');
                break;
			case "share_email":
                $('#div_share_email').addClass('active');
                break;
            default:
                $('#div_share_sn').addClass('active');
        } 
    })
	 
	 
	function loadWinHeight() {
	    var Winheight = $( window ).height();
		              
	}
	 
	function resizeWinHeight() {
	
	}
    $(window).on("resize", function(){
        if ($('.series figure.series-posterframe img').length > 0)
           resizeImage($('.series figure.series-posterframe img'));
    });
	
	/*Sharebuttons*/
	$('#sharingurl').val(window.location.href);
	 
});
