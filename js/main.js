/* FUNCTIONS
--------------------------------------------------------------------------------------------------------------------------------------*/

var zf = zf || {};

zf.seeMore = function($this) {
	$this.parents('.preview').siblings('.more').stop(true,true).slideToggle(function() {
		if ($(this).css('display')=='none') {
			$this.html('Voir plus');
		} else {
			$this.html('Voir moins');
		}
	});
}

zf.initAddProject = function() {
	zf.$newProject = $('#newProject');
	zf.$newProject.on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: $(this).attr('action'),
			type: $(this).attr('method'),
			data: $(this).serialize(),
			success: function(html) {
				console.log(html);
			}
		});
	})
};

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
	zf.$page = $('#page');
	zf.$projects = zf.$page.find('.project');
	
	zf.$page.find(".addProject a").fancybox({
		afterShow: zf.initAddProject
	});
	
	
	zf.$page.find('.myProject').click(function() {
		
	});
	
	zf.$projects.find('.see-more').click(function(event) {
		event.preventDefault();
		zf.seeMore($(this));
	});
};

/* DOM READY
--------------------------------------------------------------------------------------------------------------------------------------*/

$(document).ready(zf.init);