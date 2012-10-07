/* FUNCTIONS
--------------------------------------------------------------------------------------------------------------------------------------*/

var zf = zf || {};

zf.seeMore = function($this) {
	$this.hide().parents('.preview').siblings('.more').slideDown();
	console.log($this.siblings('.more'));
}


zf.init = function(){
	$('body').addClass('has-js');
	// console.log('ok');
	
	// Reset form focus
	$('.reset-focus').focus(function(){
		if($(this).attr('value') == this.defaultValue) $(this).attr('value', '');
	}).blur(function(){
		if($.trim(this.value) == '') this.value = (this.defaultValue ? this.defaultValue : '');
	});
	
	// Blank links
	$('a[rel=external]').click(function(){
		window.open($(this).attr('href'));
		return false;
	});
	
	$('.see-more').click(function(event) {
		event.preventDefault();
		zf.seeMore($(this));
	})
};

/* DOM READY
--------------------------------------------------------------------------------------------------------------------------------------*/

$(document).ready(zf.init);