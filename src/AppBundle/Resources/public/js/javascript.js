// JavaScript Document
$(document).ready(function(){
	/* Headerbackground */
	var headerScrollheight = 50;
	
	function addHeaderBG() {
	  if($(document).scrollTop() <= headerScrollheight) {
	      $('body').addClass('head-white-color');
		  $('body').removeClass('head-black-color');
		  $('body').addClass('head-transparent-bg');
		  $('body').removeClass('head-white-bg');
		  $('.navbar-fixed-top').removeClass('dropshadow');
	   } else if ($(document).scrollTop() > headerScrollheight) {
		  $('body').removeClass('head-white-color');
		  $('body').addClass('head-black-color');
		  $('body').addClass('head-white-bg');
		  $('body').removeClass('head-transparent-bg');
		  $('.navbar-fixed-top').addClass('dropshadow');
		}
	 }
	 addHeaderBG();

	 $(document).scroll(function(){
	     addHeaderBG();
	 });
	  
	 /*Slider*/
	 
	 /*Alle Bilder auf gleiche Höhe bringen (nach der kleinsten Höhe)*/
	function carouselNormalization() {
        var items = jQuery('#slider .item'), //grab all slides
        width = [], //create empty array to store height values
        //shortest; //create variable to make note of the shortest slide
		Winheight,
		Winwidth;

        if (items.length) {
			function Imagesize(el) {
				aspectRatio = $(el).width() / $(el).height();
				return aspectRatio;
			}
			
            function normalizeHeights() {
				var Winheight = $( window ).height();
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
	
	
	 
	 
	 function loadWinHeight() {
	     var Winheight = $( window ).height();
		 //$('#slider .item img').css('height',Winheight);
		 //$('#slider .item img').css('width','auto'); 
		 //$('#slider').height(Winheight);                     
	 }
	 //loadWinHeight();
	 
	 function resizeWinHeight() {
		 //$('#slider .item img').css('height','auto');
		 //$('#slider .item img').css('width','100%');
	 }
	 $(window).on("resize", function(){
        //resizeWinHeight();
		//console.log('bis heir');
    });
	 
});
