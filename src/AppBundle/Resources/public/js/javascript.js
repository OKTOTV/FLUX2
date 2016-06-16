// JavaScript Document
$(document).ready(function(){
	
	/* Headerbackground */
	var headerScrollheight = $(window).height()/2;
	var headerHeight = 180;

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
				} else if (winwidth < 992) {
					var divisor = 2;
				} else {
					var divisor = 4;
				}
				var rowPosition = (Math.floor(indexPin/divisor) + 1)*divisor - 1;
				if (rowPosition > Pins.length-1) rowPosition = Pins.length-1;
				
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
	
	/* Serien */
	if ($('body').hasClass('series')) {
		var descriptionString = $('figcaption .description').text();
		$('figcaption .description').html(descriptionString.substr(0,200) + '...');
		var layer = false;
	}
	$('figcaption.description-wrapper a.more').click(function(){
		console.log($('.fs-image-ident .description-wrapper').width() > '650');
		
		if (layer === false) {
			origHeight = $('.fs-image-ident .description-wrapper').css('height');
		    $('.fs-image-ident .description-wrapper').animate(
		    {
                bottom: '0px',
                left: '0px',
			    marginLeft:'0px',
			    marginRight:'0px',
			    width: '100%',
			    height: '100%',
			    paddingLeft:'0px',
			    paddingRight:'0px'
            }, 'fast');
		    $('.bg-opacity').animate(
		    {
			    marginRight:'0px'
            }, 'fast');
		    $(this).parents('.content-opacity').animate(
		    {
			    marginTop: headerHeight + 20,
			    paddingLeft:'60px',
			    paddingRight:'60px'
		    }, 'fast', function() {
			    $('figcaption .description').html(descriptionString);
			});
			$(this).text('zurück');
			layer = true;
		} else {
		    $('.fs-image-ident .description-wrapper').animate(
		    {
                bottom: '100px',
                left: '50%',
			    marginLeft:'-325px',
			    marginRight:'0px',
			    width: '650px',
			    height: origHeight,
			    paddingLeft:'15px',
			    paddingRight:'15px'
            }, 'fast');
		    $('.bg-opacity').animate(
		    {
			    marginRight:'15px'
            }, 'fast');
		    $(this).parents('.content-opacity').animate(
		    {
			    marginTop: '0px',
			    paddingLeft:'15px',
			    paddingRight:'15px'
		    }, 'fast', function() {
			    $('figcaption .description').html(descriptionString.substr(0,200) + '...');
			    });
				$(this).text('weiterlesen');
			    layer = false;
		}
	});
	
	/*bottom: 100px;
    left: 50%;
    margin: 0 0 0 -325px;
    padding-bottom: 0;
    position: absolute;
    right: 0;
    text-align: left;
    width: 650px;*/


});
