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
	
	// UP NUMBER VALUES FOR POST
	zf.$newProject.on('click','.profiles p .number_control',function(event) {
		var $this=$(this);
		zf.$number = $this.siblings('.number')
		if($this.hasClass('more')){
			zf.$number.val(parseInt(zf.$number.val())+1)
		}
		if($this.hasClass('less')){
			if (zf.$number.val()!="0") {
				zf.$number.val(parseInt(zf.$number.val())-1)
			}
		}
	});
	
	// ADD POST
	zf.$newProject.on('click','#add-post',function(event) {
		// if (zf.$newProject.find('.profiles p:last .entitled').val()!='') { // if last post isn't empty
			var newPost = zf.$newProject.find('.profiles p:last').clone();
			$(this).attr('id','').removeClass('add-post').addClass('delete').html('-')
			zf.$newProject.find('.profiles').append(newPost);
		// };
	});
	
	// DELETE POST
	zf.$newProject.on('click','.delete',function(event) {
		var $this=$(this);
		if ($this.parent('p').find('.entitled').val()!='') { // if last post isn't empty
			var oldPost = $this.parent('p').clone().end().remove();
		} else {
			$this.parent('p').remove();
		}
	});
	
	// SEND PROJECT
	zf.$newProject.on('submit', function(event) {
		event.preventDefault();
		$.ajax({
			url: $(this).attr('action'),
			type: $(this).attr('method'),
			data: $(this).serialize(),
			success: function(html) {
				// console.log(html);
				$.fancybox.close();
				location.reload();
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
		afterShow: zf.initAddProject,
		closeClick  : false,
		helpers   : { 
			overlay : {closeClick: false}
		}
	});
	
	
	zf.$page.find('.myProject').click(function(event) {
		event.preventDefault();
	});
	
	zf.$projects.find('.see-more').click(function(event) {
		event.preventDefault();
		zf.seeMore($(this));
	});
};

/* DOM READY
--------------------------------------------------------------------------------------------------------------------------------------*/

$(document).ready(zf.init);