// JavaScript Document
$(document).ready(function(){
	/* Sollte in ein allgemeines JavaScriptfile verschoben werden, Anfang */
	function addHeaderBG() {
	  if($(document).scrollTop() == 0) {
		  $('body').addClass('white-color');
		  $('body').removeClass('black-color');
	   } else {
		  $('body').removeClass('white-color');
		  $('body').addClass('black-color');
		}
	 }
	 addHeaderBG();

	 $(document).scroll(function(){
	   addHeaderBG();
	  });
});
