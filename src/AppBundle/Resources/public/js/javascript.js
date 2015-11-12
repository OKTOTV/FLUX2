// JavaScript Document
$(document).ready(function(){
	function addHeaderBG() {
		if($(document).scrollTop() == 0) {
    		$('header .navbar-fixed-top').removeClass('black');
    	} else {
    		$('header .navbar-fixed-top').addClass('black');
    	}
	}
	addHeaderBG();
        
	$(document).scroll(function(){
		addHeaderBG();
    });
});