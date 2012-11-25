/* FUNCTIONS
--------------------------------------------------------------------------------------------------------------------------------------*/

var zf = zf || {};

zf.addFavorite = function($this) {
	$.ajax({
		url: 'requests/addFavorite.php',
		type: 'post',
		data: {id : $this.data('id')},
		success: function(resp) {
			resp = JSON.parse(resp);
			if (resp.success) {
				$this.html('Retirer des favoris').removeClass('favorite_link').addClass('unfavorite_link');
			} else {

			}
		}
	});
};

zf.deleteFavorite = function($this) {
	$.ajax({
		url: 'requests/deleteFavorite.php',
		type: 'post',
		data: {id : $this.data('id')},
		success: function(resp) {
			resp = JSON.parse(resp);
			if (resp.success) {
				$this.html('Ajouter aux favoris').removeClass('unfavorite_link').addClass('favorite_link');
			} else {

			}
		}
	});
};

zf.seeMore = function($this) {
	$this.parents('.preview').siblings('.more').stop(true,true).slideToggle(function() {
		if ($(this).css('display')=='none') {
			$this.html('Voir plus');
		} else {
			$this.html('Voir moins');
		}
	});
}

zf.seeAll = function() {
	zf.$projectsList.fadeOut(300,function() {
		$(this).children().remove().end().show();
		var $newProject = $('<div/>');
		$newProject.load('index.php #projects',function(resp) {
			var $this=$(this);
			$this.find('.project').each(function(i) {
				var $this=$(this);
				setTimeout(function() {
					zf.$projectsList.append($this.hide().fadeIn(500));
				},i*300);
			});
			// zf.$projectsList.delay(($this.find('.project').length)*300).append($this.find('.btn-more-projects'));
			setTimeout(function() {
				zf.$projectsList.append($this.find('.btn-more-projects'));
			},$this.find('.project').length*300)
		});
	});
};

zf.seeMine = function($_this) {
	var url = $_this.attr('href');
	zf.$projectsList.fadeOut(300,function() {
		$(this).children().remove().end().show();
		var $newProject = $('<div/>');
		$newProject.load(url+' #projects',function(resp) {
			var $this=$(this);
			$this.find('.project').each(function(i) {
				var $this=$(this);
				setTimeout(function() {
					zf.$projectsList.append($this.hide().fadeIn(500));
				},i*300);
			});
			// zf.$projectsList.delay(($this.find('.project').length)*300).append($this.find('.btn-more-projects'));
			setTimeout(function() {
				zf.$projectsList.append($this.find('.btn-more-projects'));
			},$this.find('.project').length*300)
		});
	});
};

zf.getMoreProjects = function($this) {
	console.log($this[0])
	zf.projectCurrentPage++;
	var $newProject = $('<div/>');
	$newProject.load('index.php?page='+zf.projectCurrentPage+' #projects',function(resp) {
		var $this=$(this);
		$this.find('.project').each(function(i) {
			var $this=$(this);
			setTimeout(function() {
				zf.$projectsList.find('.btn-more-projects').before($this.hide().fadeIn(500));
			},i*300);
		})
		if (zf.projectCurrentPage >= zf.maxPages) {
			zf.$projectsList.find('.btn-more-projects').delay(($this.find('.project').length)*300).animate({opacity:0},300,function() {
				$(this).remove()
			});
		};
	});
}

zf.getOneProject = function(id) {
	var $newProject = $('<div/>');
	$newProject.load('index.php?id_project='+id+' #projects',function(resp) {
		$newProject.find('.project').each(function(i) {
			var $this=$(this);
			setTimeout(function() {
				zf.$projectsList.find('.project').eq(0).before($this.hide().fadeIn(1000));
			},1000);
		})
	});
}

zf.initAddProject = function() {
	zf.$newProject = $('#newProject');
	
	// UP NUMBER CHAR LEFT FOR TITLE
	zf.$newProject.on('keyup','#title',function(event) {
		event.preventDefault();
		var $this = $(this);
		var charLength = $this.val().length;
		var $lengthLeft = $this.siblings('em').find('span');
		var lengthLeft = $this.siblings('em').find('span').data('length');
		$lengthLeft.html(lengthLeft - charLength);
	});

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
			success: function(resp) {
				resp = JSON.parse(resp);
				$.fancybox.close();
				zf.getOneProject(resp.id)
				// location.reload();
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
	zf.$projectsList = zf.$page.find('#projects');
	
	zf.$page.find(".addProject a").fancybox({
		afterShow: zf.initAddProject,
		closeClick  : false,
		helpers   : { 
			overlay : {closeClick: false}
		}
	});
	
	zf.$page.on('click','#see-mine',function(event) { // a protéger avec un .queue() (spamclick)
		event.preventDefault();
		var $this=$(this);
		$this.attr('id','see-all').html('Voir toutes les annonces');
		zf.seeMine($this);
	});

	zf.$page.on('click','.favorite_link',function(event) {
		event.preventDefault();
		zf.addFavorite($(this));
	});

	zf.$page.on('click','.unfavorite_link',function(event) {
		event.preventDefault();
		zf.deleteFavorite($(this));
	});
	
	zf.$projectsList.on('click','.see-more',function(event) {
		event.preventDefault();
		zf.seeMore($(this));
	});

	zf.$page.on('click','#see-all',function() {
		event.preventDefault();
		$(this).attr('id','see-mine').html('Mes annonces');
		zf.seeAll();
	});

	// More projects
	zf.projectCurrentPage = parseInt(zf.$projectsList.find('.btn-more-projects a').data('nav'),10) || 0;
	zf.$projectsList.on('click','.btn-more-projects a',function(event) {
		event.preventDefault();
		zf.getMoreProjects($(this));
	});
};

/* DOM READY
--------------------------------------------------------------------------------------------------------------------------------------*/

$(document).ready(zf.init);