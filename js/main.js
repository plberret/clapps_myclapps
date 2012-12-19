/* FUNCTIONS
--------------------------------------------------------------------------------------------------------------------------------------*/

var zf = zf || {};

zf.isBlank = function(str) {
	return (!str || /^\s*$/.test(str));
};

zf.isOkKey = function(event) {
	if (event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40) {
		return false
	} else {
		return true
	}
}

zf.addFavorite = function($this) {
	$.ajax({
		url: 'requests/addFavorite.php',
		type: 'post',
		data: {id : $this.data('id')},
		success: function(resp) {
			// resp = JSON.parse(resp);
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
			// resp = JSON.parse(resp);
			if (resp.success) {
				$this.html('Ajouter aux favoris').removeClass('unfavorite_link').addClass('favorite_link');
			} else {

			}
		}
	});
};

zf.deleteProject = function($this) {
	$.ajax({
		url: 'requests/deleteProject.php',
		type: 'post',
		data: {id : $this.data('id')},
		success: function(resp) {
			// resp = JSON.parse(resp);
			if (resp.success) {
				$this.parents('article.project').fadeOut();
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
					// zf.$projectsList.append($this.hide().fadeIn(500));
					zf.$projectsList.append($this.css({position:'relative',opacity:0,left:'500px'}).animate({left:'0',opacity:1},500));
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
					// zf.$projectsList.append($this.hide().fadeIn(500));
					zf.$projectsList.append($this.css({position:'relative',opacity:0,left:'500px'}).animate({left:'0',opacity:1},500));
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
				// zf.$projectsList.find('.btn-more-projects').before($this.hide().fadeIn(500));
				zf.$projectsList.append($this.css({position:'relative',opacity:0,left:'500px'}).animate({left:'0',opacity:1},500));
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

zf.getFilteredProjects = function($this){
	$.ajax({
		url: 'index.php',
		data: $this.serialize(),
		success: function(resp) { 
			console.log($this.serialize());
		}
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
				// resp = JSON.parse(resp);
				$.fancybox.close();
				zf.getOneProject(resp.id)
				// location.reload();
			}
		});
	})
};


zf.jsonCitiesA = function($this){
	var value = $this.val();
	if(!zf.isBlank(value) && value.length>2){
		$.getJSON('requests/citiesJson.php',{ville:value.trim()},function(resp){
		//	console.log(resp)
			if (resp) {
				var $ul = $('<ul/>',{id:'autocompletion'});
				var $li;
				var respL = resp.length;
				for(i=0;i<respL;i++){
					if (resp[i]) {
						if (!resp[i]['cp']){resp[i]['cp']=""}
						$li = $('<li/>',{
						html :'<a href="#" data-id="'+resp[i]['id_ville']+'" data-type="'+resp[i]["type"]+'">'+resp[i]['cp']+' <span>'+resp[i]['nom']+'</span></a>'
						});
						$ul.append($li)
					};
				}
				$('#col3').find('ul#autocompletion').remove();
			//	console.log($('#col3').find('ul'))
				if (respL>0) {
					$('#col3').append($ul); // hide autoc if no result
				}
			};
		});
	}
}

zf.jsonCitiesDown = function($this) {
	$ul = zf.$filtre.find('#col3 ul')
	if ($ul.length) { // if ul contains li
		$curr = $ul.find('.current') // define current
		if ($curr.length && $curr.removeClass('current') && $ul.children().length>1) { // if current exist, remove class and if there is more than 1 result.
			$next = $curr.next() // define next()
			if ($next.length) { // if next() exist
				$curr.next().addClass('current'); // add class to next()
			} else { // else
				$next = $ul.children().eq(0)
				$next.addClass('current'); // add class to first one
			}
			$this.val($next.find('span').text());
		} else { // if current doesn't exist or if there is 1 result
			$first = $ul.find('li:first-child') // define first
			if ($this.val().trim()!=$first.find('span').text()) { // if current doesn't exist
				$first.addClass('current'); // define first child as current
				$this.val($first.find('span').text());
				console.log('kk')
			};
		}
	}; // /if ul contains li
};

zf.jsonCitiesUp = function($this) {
	$ul = zf.$filtre.find('#col3 ul')
	if ($ul.length) { // if ul contains li
		$curr = $ul.find('.current') // define current
		if ($curr.length && $curr.removeClass('current') && $ul.children().length>1) { // if current exist, remove class and if there is more than 1 result.
			$prev = $curr.prev() // define prev()
			if ($prev.length) { // if prev() exist
				$curr.prev().addClass('current'); // add class to prev()
			} else { // else
				$prev = $ul.children().last();
				$prev.addClass('current'); // add class to first one
			}
			$this.val($prev.find('span').text());
		} else { // if current doesn't exist or if there is 1 result
			$last = $ul.find('li:last-child') // define last
			if ($this.val().trim()!=$last.find('span').text()) { // if current doesn't exist
				$last.addClass('current'); // define last child as current
				$this.val($last.find('span').text());
				console.log('kk')
			};
		}
	}; // /if ul contains li
};

zf.updateFilter = function($this){
	if (!$this.hasClass('current')) {
		zf.$filtre.find('#distance').val($this.find('.number').text().trim())
		console.log(zf.$filtre.find('#distance').val())
		zf.$filtre.find('#distance .current').removeClass('current');
		$this.addClass('current');
	};
}

zf.init = function(){
	$('body').addClass('has-js');
	// console.log('ok');
	
	// Blank links
	$('a[rel=external]').click(function(){
		window.open($(this).attr('href'));
		return false;
	});

	zf.$page = $('#page');
	zf.$filtre = zf.$page.find('#block_filters');
	zf.$projectsList = zf.$page.find('#projects');
	
	zf.$page.find(".addProject a").fancybox({
		afterShow: zf.initAddProject,
		closeClick  : false,
		helpers   : { 
			overlay : {closeClick: false}
		}
	});
	
	zf.$filtre.on('submit', function(event){
		event.preventDefault();
		zf.getFilteredProjects($(this));
	});

	zf.$filtre.find('#distance li a').click(function(event) {
		event.preventDefault();
		zf.updateFilter($(this));
	})

	zf.$filtre.find('.field input[type="text"]').keyup(function(event){
		event.preventDefault();
		var $this=$(this);
		if (event.keyCode == 13 && !zf.isBlank($this.val())){
			// TRIGGER CLICK ON CURRENT DISTANCE MOTHER FUCKER
		} else if (zf.isOkKey(event)) {
			zf.jsonCitiesA($this);
		};
	}).keydown(function(event){
		switch (event.keyCode) {
			case 38: zf.jsonCitiesUp($(this)); break;
			case 40: zf.jsonCitiesDown($(this)); break;
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

	zf.$page.on('click','.deleteProject',function(event) {
		event.preventDefault();
		zf.deleteProject($(this));
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