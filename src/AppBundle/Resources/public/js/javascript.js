// JavaScript Document
$(document).ready(function(){
	/* Sollte in ein allgemeines JavaScriptfile verschoben werden, Anfang */
	function addHeaderBG() {
	  if($(document).scrollTop() == 0) {
		  $('body').addClass('head-white-color');
		  $('body').removeClass('head-black-color');
		  $('body').addClass('head-transparent-bg');
		  $('body').removeClass('head-white-bg');
		  $('.navbar-fixed-top').removeClass('dropshadow');
	   } else {
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
});
