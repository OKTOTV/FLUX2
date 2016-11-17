// JavaScript Document

var headerScrollheight;
var headerHeight;
var Winwidth;

//Zufallsgenerator
function randomNumber(arr) {
    var rand = Math.floor((Math.random() * arr.length) + 1); 
    return rand;
}
function Sizes() {
    if (Winwidth >= 768) {
        headerHeight = 180;
    } else {
        headerHeight = 50;
    }
}
function scrollToAnchor(el) {
		 var offset = $(el).offset();
		 var Ts_duration = 300;
		 $("html, body").animate({scrollTop : offset.top - headerHeight + "px"}, Ts_duration);
	 }
	
$(document).ready(function(){
	
	/* Headerbackground */
	headerScrollheight = $( window ).height()/2;
	headerHeight;
	Winwidth = $( window ).width();
	
	Sizes();

	//Menü

	function addHeaderBG() {
	      if($(document).scrollTop() <= headerScrollheight) {
			  //Header transparent setzen und Schlagschatten entfernen
	          $('body').addClass('head-transparent-bg')
				       .removeClass('head-black-bg');

	      } else if ($(document).scrollTop() > headerScrollheight) {
			  //Header schwarz setzen und Schlagschatten hinzufügen
		      $('body').addClass('head-black-bg')
					  .removeClass('head-transparent-bg');
		   }
	 }

	 addHeaderBG();

	 $(document).scroll(function(){
	     addHeaderBG();
	 });
	 
	 //Responsive Menü
	 function Customize_responsiveMenu() {
		 Winheight = $( window ).height();
		 $('header #mainmenu-container').css('max-height',Winheight-headerHeight);
		 $('header #mainmenu-container').mouseover(function() {
			 $('header #mainmenu-container').css('overflow','auto');
		 });
		 $('header #mainmenu-container').mouseleave(function() {
			 $('header #mainmenu-container').css('overflow','hidden');
		 });
	 }
	 
	 $(window).on("resize orientationchange", function(){
		 Winwidth = $( window ).width();
		 //console.log(Winwidth);
		 Sizes();
		 if (Winwidth < 768) {
		     Customize_responsiveMenu();
		 }
	 });
	 if ($( window ).width() < 768) {
	     Customize_responsiveMenu();
	 }
	 
	 function openIcon(el, icon, status) {
		 if (status == "open") {
		     $(el).find('span.icon-close').css('display','block');
		     $(el).find(icon).css('display','none');
		 } else {
			 $(el).find(icon).css('display','block');
		     $(el).find('span.icon-close').css('display','none');
		 }
	 }
	 
	 $('header button.search-button').click(function() {
	     if ($(this).hasClass('collapsed'))
			 openIcon($(this), 'span.glyphicon-search', 'open');
	     else
			 openIcon($(this), 'span.glyphicon-search', 'close');
	    
		if(!$('header button.menu-button').hasClass('collapsed'))
		    openIcon($('header button.menu-button'), 'span.icon-bar','close');
	 });
	 $('header button.menu-button').click(function() {
	     if ($(this).hasClass('collapsed'))
			 openIcon($(this), 'span.icon-bar','open');
	     else
			 openIcon($(this), 'span.icon-bar','close');
			 
	     if(!$('header button.search-button').hasClass('collapsed'))
		     openIcon($('header button.search-button'), 'span.glyphicon-search', 'close');
	     
		 /**/
	 });
	 $('#search-container.navbar-collapse').on('show.bs.collapse', function(e) {
         $('#mainmenu-container.navbar-collapse').collapse('hide');
    });
	 $('#mainmenu-container.navbar-collapse').on('show.bs.collapse', function(e) {
         $('#search-container.navbar-collapse').collapse('hide');
     });


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
	
	/* Kommentare */
	
	if ($('section').hasClass('comments')) {
		$('.comments button').css('display','none');
		$('textarea').focus(function() {
			el = $(this).parents('fieldset').find('button').css('display', 'block');
		});
	}
	
	if ($('section').hasClass('comments') || $('section').hasClass('blog')) {
		var avatar = new Array('avatar1', 'avatar2', 'avatar3', 'avatar4');
		for (i=0; i<$('.avatar-container').length; i++)
		{
			rand = randomNumber(avatar)-1;
			$('.avatar-container').eq(i).find('div').addClass(avatar[rand]);
		}
	}
});
