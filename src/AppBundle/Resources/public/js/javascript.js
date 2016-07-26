// JavaScript Document
$(document).ready(function(){
	
	/* Headerbackground */
	var headerScrollheight = $( window ).height()/2;
	var headerHeight;
	var Winwidth = $( window ).width();
	function Sizes() {
		if (Winwidth >= 768) {
	        headerHeight = 180;
	    } else {
		    headerHeight = 50;
	    }
	}
	Sizes();
	
	//Menü

	function addHeaderBG() {
	 // if ($('body').hasClass('fullscreen-images') || $('body').hasClass('fullscreen-background')) {
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
	     /*}else {
			if($(document).scrollTop() <= headerScrollheight) {
				$('.navbar-fixed-top').removeClass('dropshadow');
		    } else if ($(document).scrollTop() > headerScrollheight) {
				$('.navbar-fixed-top').addClass('dropshadow');
			}
		}*/
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
		 console.log(Winwidth);
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
	 
	//Sharing Tabs:

	$('.series #ButtonShare').click(function() {
		var offsetShare = $('.episode-description').offset();
		console.log(offsetShare);
		$("html, body").animate({scrollTop : offsetShare.top - headerHeight + "px"}, Ts_duration);
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

	/*Share Url*/
	$('#sharingurl').val(window.location.href);
	
	
	/* Tooltips: */
	    /*if (
	        $('body').hasClass('oktothek') ||
		    $('body').hasClass('episode') ||
	        $('body').hasClass('series')) {
	        $('[data-toggle="tooltip"]').tooltip({'placement': 'auto right'});
	    }*/


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

	//Academy
	
	if ($('body').hasClass('academy')) {
		
		var cur_Id = null;
		
		function closeCoursePreview(openEl, targetEl) {//schließt alle offenen Kursdetails
			if (openEl.length > 0) {
				for (z=0; z<openEl.length; z++) {
					targetEl_hide_Id = $(openEl[z]).attr('id');
					targetButtonEl = $( "a[data-target='#" + targetEl_hide_Id + "']" ).parents('article.pin');
					
					if (targetEl != null) 
					    if(targetEl == "#" + targetEl_hide_Id) 
						    cur_Id = targetEl; 
					
					$(openEl[z]).slideUp('fast','linear',function(){
						$('section article.pin').removeAttr('style');
						$(openEl[z]).removeClass('in');
						$("#" + targetEl_hide_Id).appendTo($(targetButtonEl));
					});
				}
			}
		}
		
	    $('figure.pin .preview-button a').click(function() {
			
			var cur_section = $(this).parents('section'); 
			var targetEl = $(this).attr('href');
		
		    var openEl = $(cur_section).find('div.in');
			
			closeCoursePreview(openEl, targetEl); //schließt alle offenen Kursdetails
			
		    if (cur_Id == null) { //öffnet neues Kursdetails, wenn es nicht soeben geöffnet war
				var Pins = $(cur_section).find('article.pin');
			    var indexPin = ($(Pins).index($(this).parents('article.pin')));
				var winwidth = $( window ).width();
				
				if (winwidth < 580) { //legt fest nach wieviel Bildern die neue Reihe beginnt.
					var divisor = 1;
					var pin_width = 100;
				} else if (winwidth < 992) {
					var divisor = 2;
					var pin_width = 50;
				} else {
					var divisor = 4;
					var pin_width = 25;
				}
				/*Reihe ausrechnen*/
				var _rowPosition = Math.floor(indexPin/divisor);
				var rowPosition = (_rowPosition + 1)*divisor - 1;
				if (rowPosition > Pins.length-1) rowPosition = Pins.length-1;
				
				/*Position Sprechblase ausrechnen*/
				var bubble = indexPin - (_rowPosition * divisor) + 1;
				var bubblepos = bubble * pin_width - pin_width / 2;
				$('.collapse-header').css('left', bubblepos + '%');
				$('.collapse-header').css('margin-left', '-' + ($('.collapse-header').width() / 2) + "px");
				console.log($('.collapse-header').width());
				
		        $(Pins).eq(rowPosition).after($(targetEl));
				$(targetEl).slideDown('fast','linear',function(){$(targetEl).addClass('in');});
				
				if (winwidth >= 580 && winwidth <= 992) {

					for (j=rowPosition+1; j<$(Pins).length; j++) {
						$(Pins).eq(j).css('clear','none').css('float','left');
					}
					
				}
		
		    }
			cur_Id = null;
			
	    });
		$( window ).resize(function() {
  			var winwidth = $( window ).width();
			var openEl = $('section div.in');
			closeCoursePreview(openEl, null);//schließt alle offenen Kursdetails
		});
		
		$('.preview-icon').mouseover(function(){
			$(this).parents('.preview-button').find('.preview-content').css('display','block');
	    });
		$('.preview-content a').mouseleave(function(){
			$(this).parents('.preview-content').css('display','none');
		});
	}
	
	/* Kommentare */
	
	if ($('section').hasClass('comments')) {
		$('.comments button').css('display','none');
		$('textarea').focus(function() {
			el = $(this).parents('fieldset').find('button').css('display', 'block');
		});
	}
	
	function randomNumber() {
		var rand = Math.floor((Math.random() * avatar.length) + 1); 
		return rand;
	}
	
	if ($('section').hasClass('comments') || $('section').hasClass('blog')) {
		var avatar = new Array('avatar1', 'avatar2', 'avatar3', 'avatar4');
		for (i=0; i<$('.avatar-container').length; i++)
		{
			rand = randomNumber();
			$('.avatar-container').eq(i).find('div').addClass(avatar[rand]);
		}
	}
	
	
	
	/*bottom: 100px;
    left: 50%;
    margin: 0 0 0 -325px;
    padding-bottom: 0;
    position: absolute;
    right: 0;
    text-align: left;
    width: 650px;*/


});
