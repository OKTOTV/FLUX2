// JavaScript Document

$(document).ready(function(){
	//Sharing Tabs:

	$('.series #ButtonShare').click(function() {
		scrollToAnchor($('.episode-description'));
	});
	
	$('.playlists #ButtonShare').click(function() {
		scrollToAnchor($('.playlist_container'));
	});
	
	$('.misc button[data-toggle="collapse"]').click(function() {
	    $('article .collapse').collapse('hide');
	});

	/*Share Url*/
	$('#sharingurl').val(window.location.href);
	
    /*Sprechblasenpfeil unter Button verschieben*/
	function posBubbletop() {
		for (i=0; i<$('.collapseWindow').length; i++) {
		    var id = $('.collapseWindow').eq(i).attr('id');
            var el = $('button[data-target="#' + id + '"]');
			var pos = el.position();
			if (pos)
                $('.collapseWindow').eq(i).find('.collapse-header .triangle').css('margin-left', pos.left + el.width()/2 - $('.collapseWindow').eq(i).find('.collapse-header .triangle').width()/4 + 'px');
		}
	}
	if ($('body').hasClass('episode')) {
		posBubbletop();
	}
    
 // Show more Entries
if ($('body').hasClass('oktothek')) {
    $('a.more[data-toggle="collapse"]').click(function(e){
        e.preventDefault();
        $('.collapse').on('show.bs.collapse', function () {
            var newText = $(this).parent().find('a.more').attr('data-lesstrans');
            $(this).parent().find('a.more').empty();
            $(this).parent().find('a.more').append(newText);
        });
    });
    $('.row.collapse').on('hide.bs.collapse', function () {
            var newText = $(this).parent().find('a.more').attr('data-moretrans');
            $(this).parent().find('a.more').empty();
            $(this).parent().find('a.more').append(newText);
    })
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
});