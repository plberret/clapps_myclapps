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

zf.getMoreProjects = function($this) {
	zf.projectCurrentPage++;
	var $newProject = $('<div/>');
	$newProject.load('index.php?page='+zf.projectCurrentPage+' #projects',function(resp) {
		$newProject.find('.project').each(function() {
			var $this=$(this);
			setTimeout(function() {
				zf.$projectsList.find('.btn-more-projects').before($this.hide().fadeIn(500));
			},$this.index*300);
		})
	});
}

zf.initAddProject = function() {
	zf.$newProject = $('#newProject');
	
	// UP NUMBER VALUES FOR POST
	zf.$newProject.on('click','.profiles p .number_control',function(event) {
		event.preventDefault();
		var $this=$(this);
		zf.$number = $this.siblings('.number')
		if($this.hasClass('more')){
			zf.$number.val(parseInt(zf.$number.val())+1)
		}
		if($this.hasClass('less')){
			if (zf.$number.val()!="1") {
				zf.$number.val(parseInt(zf.$number.val())-1)
			}
		}
	});
	
	// ADD POST
	zf.$newProject.on('click','#add-post',function(event) {
		event.preventDefault();
		// if (zf.$newProject.find('.profiles p:last .entitled').val()!='') { // if last post isn't empty
			var newPost = zf.$newProject.find('.profiles p:last').clone().find('.entitled').val('').siblings('.number').val('1').end().end();
			$(this).attr('id','').removeClass('add-post').addClass('delete').html('-')
			zf.$newProject.find('.profiles').append(newPost);
		// };
	});
	
	// DELETE POST
	zf.$newProject.on('click','.delete',function(event) {
		event.preventDefault();
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
				console.log(html);
				$.fancybox.close();
				location.reload();
			}
		});
	})
};

zf.init = function(){
	$('body').addClass('has-js');
	// console.log('ok');
	
	// Blank links
	$('a[rel=external]').click(function(){
		window.open($(this).attr('href'));
		return false;
	});

	zf.$page = $('#page');
	zf.$projects = zf.$page.find('.project');
	zf.$projectsList = zf.$page.find('#projects');
	
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

	// More projects
	zf.projectCurrentPage = parseInt(zf.$projectsList.find('.btn-more-projects a').data('nav'),10);
	zf.$projectsList.find('.btn-more-projects a').click(function(event) {
		event.preventDefault();
		zf.getMoreProjects($(this));
	});
};

/* DOM READY
--------------------------------------------------------------------------------------------------------------------------------------*/

$(document).ready(zf.init);