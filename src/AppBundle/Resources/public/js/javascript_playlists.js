// JavaScript Document
$(document).ready(function(){
    //Ändert Status des Playicons, Thumbnail Playlist von Play in Pause und umgekehrt
    function change_Pin_Statusicon(status) {
        activeItem = jwplayer().getPlaylistIndex();
        activeNode = $("a.playme[data-list='" + activeItem + "']").parents('li').find('.mini-player');
        if (status == 'play') {
            $(activeNode).find('span').removeClass('glyphicon-play');
            $(activeNode).find('span').addClass('glyphicon-pause');
        } else {
            $(activeNode).find('span').removeClass('glyphicon-pause');
            $(activeNode).find('span').addClass('glyphicon-play');
        }
    }
    $('.playme').click(function(e){
        e.preventDefault();
        jwplayer('player').playlistItem($(this).data('list'));
    })
	//Ruft Statusänderung Playlisticons auf und füllt Beschreibungstext in .description-container
    jwplayer().on('playlistItem', function() {
        activeItem = jwplayer().getPlaylistIndex();
        activeNode = $("a.playme[data-list='" + activeItem + "']").parents('li');
        //Node active kennzeichnen
        $("a.playme").parents('li').removeClass('active');
        $(activeNode).addClass('active');
			//Beschreibung ausgeben
        $('.description-container article').replaceWith('<article><p class="series"><a href="' + $(activeNode).find('figcaption h3').attr('data-link') + '" title="' + $(activeNode).find('figcaption h3').text() + '">' + $(activeNode).find('figcaption h3').text() + '</a></p><h2>' + $(activeNode).find('figcaption p').text() + '</h2><time pubdate="pubdate">online seit <span class="date">' + $(activeNode).find('figcaption p').attr('data-date') + '</span></time><p class="description">' + $(activeNode).find('article').attr('title') + '</p><p><a href="' + $(activeNode).find('figcaption a.playme').attr('href') + '" class="episodelink">Folgendetails</a></p><article>');
    });
    //Ruft Statusänderung Playlisticons auf wenn Play aktiviert wird
    jwplayer().on('play', function(){
        allNodes = $('a.playme').parents('li').find('.mini-player');
        $(allNodes).find('span').removeClass('glyphicon-pause');
        $(allNodes).find('span').addClass('glyphicon-play');
        change_Pin_Statusicon('play');
    });
    //Ruft Statusänderung Playlisticons auf wenn Pause aktiviert wird
    jwplayer().on('pause', function(){
        change_Pin_Statusicon('stop');
    });

    //Scrollt zu Playliste wenn auf "nächstes Video" geklickt wird
    $('#button_down').click(function() {
        var offsetEl = $('section.playlist_container').offset();
        $("html, body").animate({scrollTop : (offsetEl.top - $('header .navbar').height()) + "px"}, "slow");
        $( this ).find('span').css('display','none');
    });
    //Button verschwindet ab einer gewissen Scrollhöhe
    function showButtonDown() {
        if($(document).scrollTop() <= headerScrollheight) {
            //Downbutton erscheinen lassen
            $('#button_down span').css('display','inline');
        } else if ($(document).scrollTop() > headerScrollheight) {
            //Downbutton ausblenden
            $('#button_down span').css('display','none');
        }
    }
    showButtonDown();
    $(document).scroll(function(){
        showButtonDown();
    });
		
    /* Minimiert Playlistenbeschreibung */
    function closePlaylistDescr(status) {
        if (status == 'min') {
            $('.playlists header.description-wrapper').css('display','none');
            $('.playlists .playlist-minimized').css('display','block');
            $('.playlists .jw-display-icon-container').css('display','table');
        } else {
            $('.playlists header.description-wrapper').css('display','block');
            $('.playlists .playlist-minimized').css('display','none');
            $('.playlists .jw-display-icon-container').css('display','none');
        }
    }
    /* Ändert Playlistenbeschreibungs Status*/
    $('#StartPlaylist').click(function(e){
        e.preventDefault();
        jwplayer('player').playlistItem($('.playme').eq(0).data('list'));
        closePlaylistDescr('min');
    });
    $('.playlists header .btn.transparent').click(function() {
        closePlaylistDescr('min');
    });
    $('.playlists .playlist-minimized').click(function() {
        closePlaylistDescr('max');
    });
    $('.playme').click(function(){
        closePlaylistDescr('min');
        scrollToAnchor($('.playlists .player-container'));
    })
	
    //Ändert die Farbe des aktiven Playlistitems
    if ($('section').hasClass('playlist_container')) {
        var playbutton = new Array('color1', 'color2', 'color3', 'color4');
        for (i=0; i<$('.list-group-item').length; i++) {
            rand = randomNumber(playbutton)-1;
            $('.list-group-item').eq(i).find('article').addClass(playbutton[rand]);
        }
    }
});