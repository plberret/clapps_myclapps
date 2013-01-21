
// requestAnim shim layer by Paul Irish
var lastTime = 0;
var vendors = ['ms', 'moz', 'webkit', 'o'];
for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
	window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
	window.cancelAnimationFrame = 
	window[vendors[x]+'CancelAnimationFrame'] || window[vendors[x]+'CancelRequestAnimationFrame'];
}

if (!window.requestAnimationFrame)
window.requestAnimationFrame = function(callback, element) {
	var currTime = new Date().getTime();
	var timeToCall = Math.max(0, 16 - (currTime - lastTime));
	var id = window.setTimeout(function() { callback(currTime + timeToCall); }, 
	timeToCall);
	lastTime = currTime + timeToCall;
	return id;
};

if (!window.cancelAnimationFrame)
window.cancelAnimationFrame = function(id) {
	clearTimeout(id);
};


/* FUNCTIONS
--------------------------------------------------------------------------------------------------------------------------------------*/

var zf = zf || {};

zf.launchScrollEvent = function() {
//	zf.rAFLaunchAnim = requestAnimationFrame(zf.launchScrollEvent);
//	FB.Canvas.getPageInfo( function(info) {
	//	console.log( info.scrollTop );
	//	$('header').css({top: info.scrollTop});
//	}); 
};

function nl2br (str, is_xhtml) {   
var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

zf.isBlank = function(str) {
	return (!str || /^\s*$/.test(str));
};

zf.getVarUrl = function(url,param) {
	var params = {};
	var get = url.slice(url.indexOf('?') + 1).split('&');
	var getLength= get.length;
	for(var i = 0; i < getLength; i++)
	{
		Var = get[i].split('=');
		params[Var[0]] = Var[1];
	}
	if (param){
		return params[param];
	}
	else{
		return params;
	}
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
				$this.fadeOut(200, function(){
					$this.html('Retirer des favoris').removeClass('favorite_link').addClass('unfavorite_link').fadeIn(200);
				})
				zf.$page.find('#see-mine .number').text(parseInt(zf.$page.find('#see-mine .number').text())+1);
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
				$this.fadeOut(200, function(){
					$this.html('Ajouter aux favoris').removeClass('unfavorite_link').addClass('favorite_link').fadeIn(200);
				})
				zf.$page.find('#see-mine .number').text(parseInt(zf.$page.find('#see-mine .number').text())-1);
			}
		}
	});
};

zf.editProject = function($this) {
	$article=$this.parents('article');
	$article.find( ".datepicker" ).datepicker({ dateFormat: 'dd MM yy', minDate: 0});
	zf.$oldArticle = $article.clone(true,true);
	$article.removeClass('read').addClass('edition');
	// change display 
	$this.parent().addClass('hide');
	$this.parent().siblings('.manage-edition').removeClass('hide');
	// enable fields
	$article.find("form input").removeAttr("disabled");
	// display description
	$article.find('.preview .desc p').addClass('hide');
	$article.find('.preview .desc textarea').removeClass('hide').autosize();
	// display date, place
	$article.find('.preview .date p').addClass('hide');
	$article.find('.preview .date .datepicker').removeClass('hide');
	$article.find('.preview .place p').addClass('hide');
	$article.find('.preview .place .location').removeClass('hide');
	// change profiles
	$article.find('.block_read').addClass('hide');
	$article.find('.block_edition').removeClass('hide');
	$article.find('.more .add-line').removeClass('hide');
	// hide profiles found
	$article.find('.profileFound').addClass('hide');
	// disable button see-button
	$article.find('#block_see_button').fadeOut(250);
};

zf.cancelEditProject = function($this) {
	$this.parents('article').next().before(zf.$oldArticle).end().remove();
};


zf.editProjectPartTwo = function($this) {
	$article=$this.parents('article');
	$article.removeClass('edition').addClass('read');
	// change display 
	$this.find('.manage-edition').addClass('hide');
	$this.find('.manage-read').removeClass('hide');
	// enable fields
	$article.find("form input").attr("disabled","disabled");
	// display description
	$article.find('.preview .desc p').removeClass('hide').text($article.find('.preview .desc textarea').val());
	$article.find('.preview .desc textarea').addClass('hide');
	// display date, place
	$article.find('.preview .date .datepicker').addClass('hide');
	$article.find('.preview .date p').removeClass('hide');
	$article.find('.preview .place .location').addClass('hide');
	$article.find('.preview .place p').removeClass('hide');
	// change profiles
	$article.find('.block_read').removeClass('hide');
	$article.find('.block_edition').addClass('hide');
	$article.find('.more .add-line').addClass('hide');
	// hide profiles found
	$article.find('.profileFound').removeClass('hide');
	// enable button see-more
	$article.find('#block_see_button').fadeIn(250);
};

zf.updateProject = function($form) {
	$.ajax({
		url: 'requests/updateProject.php',
		type: 'post',
		data: $form.serialize(),
		success: function(resp) {
			if (resp.success) {
				// zf.editProjectPartTwo($this);
				zf.getOneProject(resp.id,function($this){
					// console.log($form)
					// $this.find('.see-more').trigger('click');
					// zf.$page.find('.project.edition').remove();
					$this.find('.more').show();
					$this.find('.see-more').removeClass('see-more').addClass('see-less').html('<span>Voir</span> moins').css({'display':'block'});
					$next = $form.parent().next();
					console.log($form);
					if ($next.length) {
						$form.parent().remove()
						$next.before($this);
					} else {
						$form.parent().after($this)
						$form.parent().remove()
					}
					$this.css('opacity',1)
					$this.find('.preview .desc p').removeClass('elips').dotdotdot();
				})
			} else {

			}
		}
	});
};

zf.deleteProject = function($this,callback) {
	$.ajax({
		url: 'requests/deleteProject.php',
		type: 'post',
		data: {id : $this.data('id')},
		success: function(resp) {
			// resp = JSON.parse(resp);
			if (resp.success) {
				$this.parents('article.project').fadeOut();
				zf.$page.find('#see-mine .number').text(parseInt(zf.$page.find('#see-mine .number').text())-1);
				callback();
			} else {

			}
		}
	});
};

zf.autocomplete = function($this) {
	$this.on('keyup', '.field .autocomplete', function(event){
		event.preventDefault();
		var $this=$(this);
		console.log(zf.$filtre.find('a.current'))
		if (zf.isBlank($this.val())) {
			zf.$filtre.find('a.current').removeClass('current')
		} else if (!zf.$filtre.find('a.current').length>0){
			zf.$filtre.find('a.100').addClass('current')
		}
		if (event.keyCode == 13 && !zf.isBlank($this.val())){

			// $this.blur();

		} else if (zf.isOkKey(event)) {
			if ($this.hasClass('job')) {
				zf.jsonJobs($this);
			}
			else if($this.hasClass('location')){
				zf.jsonCities($this);
			}
		};
	}).on('keydown', '.field .autocomplete', function(event){
		switch (event.keyCode) {
			case 38:
				event.preventDefault();
				zf.jsonListUp($(this));
			break;
			case 40: zf.jsonListDown($(this)); break;
		}
	}).on('mouseleave', 'ul.autocompletion li a', function(event){
		$(this).parent().removeClass('current');
		// console.log('rr')
	}).on('mouseenter', 'ul.autocompletion li a', function(event){
		event.preventDefault()
		var $this=$(this);
		$thisField = $this.parents('.autocompletion').siblings('.autocomplete')
		if (!zf.autocompletionHover) {
			zf.oldAutocomplete = $thisField.val();
			// console.log(zf.oldAutocomplete);
		}
		$thisField.val($this.text())
		$thisField.siblings('.id_place, .idjob').val($this.data("id"))
		$thisField.siblings('.type_place').val($this.data("type"))
		$this.parent().addClass('current');
		zf.autocompletionHover = true;
	}).on('mouseleave', 'ul.autocompletion', function(){
		var $this=$(this);
		$thisField = $this.siblings('.autocomplete')
		$thisField.val(zf.oldAutocomplete);
		zf.autocompletionHover = false;
	}).on('focusout', '.field .autocomplete', function(){
		// MFMFMF


		if (!zf.autocompletionHover) {
			var $thisField = $(this);
			$thisField.siblings('.id_place, .idjob').val($this.find('.autocompletion li.current a').data("id"))
			$thisField.siblings('.type_place').val($this.find('.autocompletion li.current a').data("type"))
			if ($this.find('.autocompletion li').length > 0) {
				console.log($thisField.siblings('.id_place')[0])
				console.log($this.find('.autocompletion li.current a').data("id"))
				if (!$this.find('.autocompletion li').hasClass('current')) {
					$(this).val($this.find('.autocompletion li:first-child a').text())
					$thisField.siblings('.id_place, .idjob').val($this.find('.autocompletion li:first-child a').data("id"))
					$thisField.siblings('.type_place').val($this.find('.autocompletion li:first-child a').data("type"))
				};
			};
		};

		$('.autocompletion').remove();
		zf.autocompletionHover = false;
	});
}

zf.initNotif = function($this) {
	
	// init
	$(this).$this;
	$current = $this.find('.current');
	
	// request
	if($current.hasClass('on')){
		var position = 32;
	}else{
		var position = 1;
	}
	
	// set position
	$this.find('.switch_button').css({ left: position });
}

zf.switchNotif = function($this) {
	
	// init
	$(this).$this;
	$current = $this.find('.current');
	$current.removeClass('current');
	
	// request
	if($current.hasClass('on')){
		$this.find('.off').addClass('current');
		var position = 1;
		$.ajax({
			url: 'requests/disableNotifFilter.php',
			type: 'post',
			success: function(resp) {
				// console.log(resp);
			}
		});
	}else{
		$this.find('.on').addClass('current');
		var position = 32;
		$.ajax({
			url: 'requests/enableNotifFilter.php',
			type: 'post',
			success: function(resp) {
				// console.log(resp);
			}
		});
	}
	
	// animation
	$this.find('.switch_button').stop(true,false).animate({
		left: position,
	}, 300, 'easeInOutQuint');
}

zf.addSubscribe = function($this){
	$.ajax({
		url: $this.attr('action'),
		type: 'post',
		data: $this.serialize(),
		success: function(resp) { 
			if(resp.success==true){
				window.open("http://clapps.fr");
			}else{
				window.open("http://google.fr");
			}
		//	var user=resp.result[0].merges;
		console.log(resp);
		}
	});
}

zf.seeMore = function($this) {
	var $txta = $this.parents('form').find('textarea');
	var txt = nl2br($txta.text());
	if($this.hasClass('see-less')){
		$this.parent().siblings('.preview').find('.desc p').addClass('elips').dotdotdot();
		console.log($this.parent().siblings('.preview').find('.desc p'), 'eifhezufh');
	}else{
		$this.parent().siblings('.preview').find('.desc p').text(txt).removeClass('elips').trigger("destroy");
	}
	// console.log($this.parent().siblings('.preview').find('.desc p')[0])
	$this.parent().siblings('.more').stop(true,true).slideToggle(function() {
		if ($(this).css('display')=='none') {
			$this.fadeOut(200, function() {
			    $this.removeClass('see-less').addClass('see-more').html('<span>Voir</span> plus').fadeIn(200);
			});
		} else {
			$this.fadeOut(200, function() {
			    $this.removeClass('see-more').addClass('see-less').html('<span>Voir</span> moins').fadeIn(200);
			});
		}
	});
}

zf.seeFiltered = function(url,event){
	zf.currentAnim = event;
	zf.$page.find('.btn-more-projects').remove()
	zf.$projectsList.fadeOut(300,function() {
		$(this).children().remove().end().show();
		var $newProject = $('<div/>');
		$newProject.load(url+' #page',function(resp) {
			var $this=$(this);
			zf.$page.find('#see-all').attr('id','see-mine').html($this.find('#see-mine').html());
			$projects = $this.find('.project');
			$projects.each(function(i) {
				var $this=$(this);
				setTimeout(function() {
					if (zf.currentAnim == event) {
						zf.$projectsList.append($this.css({position:'relative',opacity:0,left:'25px'}).animate({left:'0',opacity:1},500,'easeOutExpo'));
						$this.find(".preview .desc p").dotdotdot();
					} else {
						// console.log('blocked',event)
					}
				},i*300);
			});
			// zf.$projectsList.delay(($this.find('.project').length)*300).append($this.find('.btn-more-projects'));
			setTimeout(function() {
				if (zf.currentAnim == event) {
					console.log('ok')
					zf.$projectsList.append($this.find('.btn-more-projects'));
				}
			},$projects.length*300)
		});
	});
};

zf.seeAll = function($_this,event) {
	zf.currentAnim = event
	zf.$projectsList.fadeOut(300,function() {
		$(this).children().remove().end().show();
		var $newProject = $('<div/>');
		$newProject.load('index.php #page',function(resp) {
			var $this=$(this);
			$_this.attr('id','see-mine').html($this.find('#see-mine').html())
			$this.find('.project').each(function(i) {
				// console.log('i',i)
				var $this=$(this);
				setTimeout(function() {
					if (zf.currentAnim == event) {
						zf.$projectsList.append($this.css({position:'relative',opacity:0,left:'25px'}).animate({left:'0',opacity:1},500,'easeOutExpo'));
						$this.find(".preview .desc p").dotdotdot();
					} else {
						// console.log('blocked',event)
					}
					// zf.$projectsList.append($this.hide().fadeIn(500));
				},i*300);
			});
			// zf.$projectsList.delay(($this.find('.project').length)*300).append($this.find('.btn-more-projects'));
			setTimeout(function() {
				if (zf.currentAnim == event) {
					zf.$projectsList.append($this.find('.btn-more-projects'));
				}
			},$this.find('.project').length*300)
		});
	});
};

zf.seeMine = function($_this,event) {
	//resize of canvas
	zf.currentAnim = event
	var url = $_this.attr('href');
	zf.$projectsList.fadeOut(300,function() {
		$(this).children().remove().end().show();
		FB.Canvas.setSize({ width: 810, height: 700 });
		var $newProject = $('<div/>');
		$newProject.load(url+' #page',function(resp) {
			var $this=$(this);
			// $_this.attr('id','see-mine').html($this.find('#see-mine').html());
			$this.find('.project').each(function(i) {
				var $this=$(this);
				setTimeout(function() {
					if (zf.currentAnim == event) {
					// zf.$projectsList.append($this.hide().fadeIn(500));
						zf.$projectsList.append($this.css({position:'relative',opacity:0,left:'25px'}).animate({left:'0',opacity:1},500,'easeOutExpo'));
						$this.find(".preview .desc p").dotdotdot();
					} else {
						// console.log('blocked',event)
					}
				},i*300);
			});
			// zf.$projectsList.delay(($this.find('.project').length)*300).append($this.find('.btn-more-projects'));
			setTimeout(function() {
				if (zf.currentAnim == event) {
					zf.$projectsList.append($this.find('.btn-more-projects'));
				}
			},$this.find('.project').length*300)
		});
	});
};

zf.getMoreProjects = function($this,event) {
	zf.currentAnim = event
	zf.projectCurrentPage++;
	var $newProject = $('<div/>');
	// console.log($this.attr('href'))
	$newProject.load('index.php'+$this.attr('href')+' #projects',function(resp) {
		var $this=$(this);
		zf.$projectsList.find('.btn-more-projects').remove()
		// console.log()

		// eval($this.find('.maxPages'))
		// console.log($this.find('.maxPages'))
		// console.log(zf.maxPages);
		$projects = $this.find('.project');
		$projects.each(function(i) {
			var $this=$(this);
			setTimeout(function() {
				if (event == zf.currentAnim) {
					zf.$projectsList.children().last().before($this.css({position:'relative',opacity:0,left:'25px'}).animate({left:'0',opacity:1},500,'easeOutExpo'));
					$this.find(".preview .desc p").dotdotdot();
				}
				else {
					// console.log('bloked',event)
				}
				// zf.$projectsList.find('.btn-more-projects').before($this.hide().fadeIn(500)); // old
			},i*300);
		})
		setTimeout(function() {
			if (zf.currentAnim == event) {
				zf.$projectsList.append($this.find('.btn-more-projects'));
			}
		},$this.find('.project').length*300)
		// if (zf.projectCurrentPage >= zf.maxPages) {
			// zf.$projectsList.find('.btn-more-projects').delay(($this.find('.project').length)*300).animate({opacity:0},300,function() {
				// $(this).remove()
			// });
		// };
	});
}

zf.getOneProject = function(id,callback) {
	var $newProject = $('<div/>');
	$newProject.load('index.php?id_project='+id+' #projects',function(resp) {
		$newProject.find('.project').each(function(i) {
			var $this=$(this);
			if (callback) {
				callback($this);
			} else {
				setTimeout(function() {
					zf.$projectsList.find('.project').eq(0).before($this.css('opacity',1).hide().fadeIn());
					$this.find(".preview .desc p").dotdotdot();
				},1000);
			}
		})
	});
}

zf.getFilteredProjects = function($this,event){
	// $.ajax({
	// 	url: 'index.php',
	// 	data: $this.serialize(),
	// 	success: function(resp) { 
			// console.log($this.serialize());
	// 	}
	// });
	zf.seeFiltered('index.php?filter=true&'+$this.serialize(),event)

	// actualise current_filter block
	$currentFilter = zf.$page.find('#block_current_filter');
	if ($this.find('#profile').val().length == 0 && $this.find('#location').val().length == 0 && $this.find('#date_filter').val()=='all') {
		zf.$page.find('#block_current_filter p.none').removeClass('hide').siblings('p').addClass('hide');
	} else {
		zf.$page.find('#block_current_filter p.none').addClass('hide').siblings('p').removeClass('hide');
	}
	if ($this.find('.'+zf.$page.find('#date_filter').val()).text()!='Indifférent') {
		$currentFilter.find('.time').text($this.find('.'+zf.$page.find('#date_filter').val()).text())
	} else {
		$currentFilter.find('.time').text('')
	}
	$currentFilter.find('.work').text(' '+$this.find('#profile').val())
	$currentFilter.find('.location').text($this.find('#location').val())
	$currentFilter.find('.distance').text($this.find('#distance').val()+'km')
	if ($this.find('#location').val().trim().length==0) {
		$currentFilter.find('.opt').hide()
	} else {
		$currentFilter.find('.opt').show()
	}
}

zf.addAnonceFormOk = function($form){
	// console.log(zf.$newProject.find('.field input'))
	var t = true;
	// console.log($form.find('.required'))
	$form.find('.required').each(function(){
		if ($(this).val().trim().length == 0) {
			// console.log($(this))
			t = false;
			return false
		};
	})
	return t;
}

zf.numberControlProfile = function($this){
	zf.$number = $this.siblings('.number');
	if($this.hasClass('more_quantity')){
		zf.$number.val(parseInt(zf.$number.val())+1);
	}
	if($this.hasClass('less_quantity')){
		if (zf.$number.val()!="1"){
			zf.$number.val(parseInt(zf.$number.val())-1);
		}
	}
}

zf.addLineProfile = function($this){
	$projet=$this.parents('form');
	// if (zf.$newProject.find('.profiles p:last .entitled').val()!='') { // if last post isn't empty
		var newPost = $projet.find('.profiles ul li:last').clone().find('.plp').val('').siblings('.number').val('1').end().end();
		$this.attr('id','').removeClass('add-post').addClass('delete').html('-');
		$projet.find('.profiles ul').append(newPost);
	// };
	$this.parents('.profiles').find('.required').removeClass('required').end().find('li').eq(0).find('.plp').addClass('required')

}

zf.deleteLineProfile = function($this){
	var $tparent = $this.parents('.profiles');
	$this.parents('li').remove();
	$tparent.find('.required').removeClass('required').end().find('li').eq(0).find('.plp').addClass('required')
	//if ($this.parent('div').siblings('.entitled').val()!='') { // if last post isn't empty
	//	var oldPost = $this.parents('li').clone().end().remove(); // recovery oldPost mb
		// console.log('1');
	//} else {
		// console.log('2');
	//}
}

zf.deleteProfile = function($this){
	$profile=$this.parents('li');
	$profile.fadeOut(300, function(){
		$profile.remove();
	});
}

zf.jsonJobs = function($this){
	var value = $this.val();
	if(!zf.isBlank(value) && value.length>2){
		$.getJSON('requests/jobsJson.php',{job:value.trim()},function(resp){
		//	console.log(resp)
			if (resp) {
				var $ul = $('<ul/>',{id:'autocJobs', class:'autocompletion'});
				var $li;
				var respL = resp.length;
				for(i=0;i<6;i++){
					if (resp[i]) {
						// if (!resp[i]['cp']){resp[i]['cp']=""}
						$li = $('<li/>',{
							html :'<a href="#" data-id="'+resp[i]['id_job']+'" data-domain="'+resp[i]["domain"]+'"><span>'+resp[i]['name']+'</span></a>'
						});
						$ul.append($li)
					};
				}
				$('ul#autocJobs').remove();
			//	console.log($('#col3').find('ul'))
				if (respL>0) {
					$this.parent().append($ul)
					// $('#col3').append($ul); // hide autoc if no result
				}
			};
		});
	} else {
		$('ul#autocJobs').remove();
	}
}

zf.jsonCities = function($this){
	// console.log($this[0])
	var value = $this.val();
	var restricted = $this.data('restricted');
	if(!zf.isBlank(value) && value.length>2){
		$.getJSON('requests/citiesJson.php',{ville:value.trim(),restricted:restricted},function(resp){
		//	console.log(resp)
			if (resp) {
				var $ul = $('<ul/>',{id:'autocCities', class:'autocompletion'});
				var $li;
				var respL = resp.length;
				for(i=0;i<6;i++){
					if (resp[i]) {
						if (!resp[i]['cp']){resp[i]['cp']=""} else {resp[i]['cp'] = '('+resp[i]['cp']+')'}
						$li = $('<li/>',{
						html :'<a href="#" data-id="'+resp[i]['id']+'" data-type="'+resp[i]["type"]+'"><span>'+resp[i]['nom']+'</span> '+resp[i]['cp']+'</a>'
						});
						$ul.append($li)
					};
				}
				$('ul#autocCities').remove();
			//	console.log($('#col3').find('ul'))
				if (respL>0) {
					$this.parent().append($ul)
					// $('#col3').append($ul); // hide autoc if no result
				}
			};
		});
	} else {
		$('ul#autocCities').remove();
	}
}

zf.jsonListDown = function($this) {
	$ul = $('ul.autocompletion')
	if ($ul.length) { // if ul contains li
		$curr = $ul.find('.current') // define current
		if ($curr.length && $curr.removeClass('current') && $ul.children().length>1) { // if current exist, remove class and if there is more than 1 result.
			$next = $curr.next() // define next()
			if ($next.length) { // if next() exist
				$curr.next().addClass('current').find('a').trigger('mouseenter'); // add class to next()
			} else { // else
				$next = $ul.children().eq(0)
				$next.addClass('current').find('a').trigger('mouseenter'); // add class to first one
			}
			$this.val($next.text());
		} else { // if current doesn't exist or if there is 1 result
			$first = $ul.find('li:first-child') // define first
			// if ($this.val().trim()!=$first.find('span').text()) { // if current doesn't exist
				$first.addClass('current').find('a').trigger('mouseenter'); // define first child as current
				$this.val($first.text());
			// };
		}
	}; // /if ul contains li
};

zf.jsonListUp = function($this) {
	$ul = $('ul.autocompletion')
	if ($ul.length) { // if ul contains li
		$curr = $ul.find('.current') // define current
		console.log($curr[0]);
		if ($curr.length && $curr.removeClass('current') && $ul.children().length>1) { // if current exist, remove class and if there is more than 1 result.
			$prev = $curr.prev() // define prev()
			if ($prev.length) { // if prev() exist
				$curr.prev().addClass('current').find('a').trigger('mouseenter'); // add class to prev()
			} else { // else
				$prev = $ul.children().last();
				$prev.addClass('current').find('a').trigger('mouseenter'); // add class to first one
			}
			$this.val($prev.text());
		} else { // if current doesn't exist or if there is 1 result
			$last = $ul.find('li:last-child') // define last
			if ($this.val().trim()!=$last.find('span').text()) { // if current doesn't exist
				$last.addClass('current').find('a').trigger('mouseenter'); // define last child as current
				$this.val($last.text());
			};
		}
	}; // /if ul contains li
};

zf.updateFilter = function($this){
	if (!$this.hasClass('current')) {
		zf.$filtre.find('#distance').val($this.find('.number').text().trim())
		zf.$filtre.find('#distances .current').removeClass('current');
		$this.addClass('current');
	};
}

// Filter
zf.filter = function(){
	
	// var
	oldValue = "";
	if (zf.$filtre.hasClass('less')) {
		zf.filterOpen= false;
	} else {
		zf.filterOpen= true;
	}
	zf.advancedFilterOpen= false;
	$filter = zf.$page.find('#block_filters');
	advancedFilter = zf.$page.find('#filter_advanced');
	$filter.find('input').click(function(event) {
		event.preventDefault();
		oldValue = $(this).val()
	}).focusout(function(event){
		if (oldValue != $(this).val()) {
			$filter.trigger('submit');
		};
	}).end().find('#date_filter').change(function(){
		$filter.trigger('submit');
	})

/*	$(document).bind('mousewheel',function(event){
		if ($(document).scrollTop()<=128 && !zf.filterOpen && event.originalEvent.wheelDelta > 0) {
			zf.$projectsList.css({paddingTop:"180px"});
			$(document).scrollTop(0);
		} else if ($(document).scrollTop()<=128 && zf.filterOpen && event.originalEvent.wheelDelta > 0) {
			zf.$projectsList.animate({paddingTop:"300px"}); // ça passe crème
		};
	}) */

	$filter.find('#refresh_button').click(function(event) {
		// init filter
		event.preventDefault();
		$filter.find('input[type=text]').val('');
		var defaultDate = $filter.find('#selector_date ul li').eq(0)
		$filter.find('#date_filter_selected').text(defaultDate.text());
		$filter.find('#date_filter').val(defaultDate.attr('class'));
		var $dist = $filter.find('#distances li a.current');
		if (!$dist.hasClass('100')) {
			$dist.removeClass('current');
			$filter.find('#distance').val('100');
			$filter.find('#distances li a.100').addClass('current');
		};

		zf.$page.find('#block_current_filter p.none').removeClass('hide').siblings('p').addClass('hide');
		zf.seeFiltered($(this).attr('href'),event);
	})

	zf.$page.find('#searchButton, .open_filtre').click(function(event){
		event.preventDefault();
		if(zf.filterOpen==true){
			$height= "-135";
			$pdtop = "180px"
			zf.filterOpen=false;
		} else {
			$height= "58";
			$pdtop = "300px"
			zf.filterOpen=true;
		}
		$filter.removeClass('hide').stop(true,false).animate({
			top: $height,
		}, 1000, 'easeInOutExpo', function() {
			// Animation complete.
			//alert('oui'); 
		});
		if ($(document).scrollTop()<=40) {
			zf.$projectsList.stop(true,false).animate({paddingTop:$pdtop}, 1000, 'easeInOutExpo', function() {
				// Animation complete.
				//alert('oui'); 
			});
		};
		//return false;
	}); 
	
	advancedFilter.find('.nav a').click(function(event){
		event.preventDefault();
		// hide help info 
		$filter.find('.help_info').hide();
		// animation
		advancedFilter.stop().animate({
			width: '590',
		}, 600, 'easeInOutExpo', function() {
			zf.advancedFilterOpen= true;
			// Animation complete.
			//alert('oui'); 
		});
		// return false;
	});
	
	advancedFilter.find('a.close').click(function(event){
		event.preventDefault();
		// hide help info 
		$filter.find('.help_info').show();
		// animation
		advancedFilter.stop().animate({
			width: '50',
		}, 600, 'easeInOutExpo', function() {
			zf.advancedFilterOpen= false;
			// Animation complete.
			//alert('oui'); 
		});
		return false;
	});
	
	advancedFilter.find('a.valid_button.load').click(function(event){
		event.preventDefault();
		var $this=$(this);
		$.ajax({
			url: 'requests/getFilter.php',
			success: function(resp) {
				// console.log(resp); // CI GIT UN TABLEAU DONT LES VALEURS DOIVENT ETRE MISE SUR LE FILTRE
				var filter = zf.getVarUrl(resp.filter);
				$.each(filter, function(key, value) {
					$filter.find('#'+key).val(value)
				})
				$filter.find('#selector_date .'+filter.date_filter).trigger('click');
				$filter.find('#distances a.'+filter.distance).trigger('click');
				
				$this.parents("#tab2").find('.choice').hide().siblings('.message').fadeIn(200, function(){
					setTimeout(function(){
						advancedFilter.find('a.close').trigger('click');
						setTimeout(function(){
							$this.parents("#tab2 .choice").show().siblings('.message').hide();
						},500);
					},1500);
				})
			}
		});

	});

	advancedFilter.find('a.valid_button.delete').click(function(event){
		event.preventDefault();
		var $this=$(this);
		$.ajax({
			url: 'requests/addFilter.php',
			type: 'post',
			data: {filter : ''},
			success: function(resp) {
				// console.log(resp);
				$this.parents("#tab3").find('.choice').hide().siblings('.message').fadeIn(200, function(){
					setTimeout(function(){
						advancedFilter.find('a.close').trigger('click');
						setTimeout(function(){
							$this.parents("#tab3 .choice").show().siblings('.message').hide();
						},500);
					},1500);
				})
			}
		});
	});

	advancedFilter.find('input.valid_button').click(function(event){ // a fix pour enregistrer le filtre
		event.stopPropagation();
		event.preventDefault();
		var $this=$(this).parents('form');
		// ajax add filter
		$.ajax({
			url: 'requests/addFilter.php',
			type: 'post',
			data: {filter : $this.serialize()},
			success: function(resp) {
				$this.find("#tab1").find('.choice').hide().siblings('.message').fadeIn(200, function(){
					// animation
					advancedFilter.stop().delay(1500).animate({
						width: '50',
					}, 600, 'easeInOutExpo', function() {
						zf.advancedFilterOpen= false;
						$filter.find('.help_info').show();
						$this.find("#tab1 .choice").show().siblings('.message').hide();
					});
				})
			}
		});
	});
	
};

zf.customFields = function($conteneur){
	// custom select
	$conteneur.find(".selector .value, .selector .button ").click(function(){
		$(this).parent().siblings('ul').toggle();
	});
	
	$conteneur.find(".selector ul li").click(function(){
		var $this = $(this);
		$this.parent('ul').hide();
		$this.parent('ul').siblings('div').find('.value').html($this.html());
		if ($this.parent('ul').siblings('input').val() != $this.attr('class')) {
			$this.parent('ul').siblings('input').val($this.attr('class')).trigger('change');
		};
	});
	
};

zf.getPlacePosition = function(lieu, callback){
	/* Appel au service de geocodage avec l'adresse en paramètre */
	zf.geocoder.geocode( { 'address': lieu}, function(results, status) {
		/* Si l'adresse a pu être géolocalisée */
		if (status == google.maps.GeocoderStatus.OK) {
			var type = results[0].address_components[0].types[0];
			if(type=="locality"){
				var zipCode = results[0].address_components[1].short_name;
				var lieu = results[0].address_components[0].long_name;
				var lat = results[0].geometry.location.Ya;
				var lng = results[0].geometry.location.Za;
				$.ajax({
					url: 'requests/addCity.php',
					type: 'post',
					data: {
						place: lieu,
						zip: zipCode,
						latitude: lat,
						longitude: lng
					},success: function(rep) {
						var donnees=new Array(); 
						donnees["success"] = true;
						donnees["id"]= rep['id'];
						donnees["type"]="villes";
						console.log("je passe en success")
						callback(donnees);
					},error: function(jqXHR, textStatus, errorThrown) {
						callback('notFound');
					}
				}); 
			}
		} else {
			callback('notFound');
		}
		
	});
	
};

zf.initSeeProject = function() {
	
	
	zf.$page.find(".preview .desc p").dotdotdot();

	// console.log(zf.$page.find('.preview .desc p'))
	
	// add to favorite
	zf.$page.on('click','.favorite_link',function(event) {
		event.preventDefault();
		zf.addFavorite($(this));
	});
	
	// remove from favorite
	zf.$page.on('click','.unfavorite_link',function(event) {
		event.preventDefault();
		zf.deleteFavorite($(this));
	});
	
	// see more/less of project
	zf.$projectsList.on('click','.see-button',function(event) {
		event.preventDefault();
		$this=$(this);
		if(!$this.hasClass('hide')){
			zf.seeMore($this);
		}
	});
	
	// custom size of fields 
	zf.$projectsList.find('.project .title input').each(function(){
		
	})
	
	// custom size of fields 
	zf.$projectsList.find('.project.read .profiles textarea').each(function(){
		// console.log($(this).height());
	})
	
};

zf.FBShare = function(title, desc, project) {
	FB.ui(
		{
			method: 'feed',
			name: title,
			link: project,
			picture: 'http://fbrell.com/f8.jpg',
			description: desc
		}
	);
};

zf.FBExemple = function(title, desc, project) {
/*	FB.api(
		'/me/[YOUR_APP_NAMESPACE]:cook',
		'post',
		{ recipe: 'http://fbwerks.com:8000/zhen/cookie.html' },
		function(response) {
			if (!response || response.error) {
				alert('Error occured');
			} else {
				alert('Cook was successful! Action ID: ' + response.id);
			}
		}); */
};


zf.FBSend = function(title, recipient, project) {
	FB.ui({
		method: 'send',
		name: '[Candidature] - '+title,
		to: recipient, 
		link: project, 
	});
};

zf.FBNotifications= function() {
	//console.log('yes');
};

zf.initFb = function() {

	// share fb 
	zf.$page.find('.share_link').click(function(event) {
		event.preventDefault();
		zf.FBShare("mon titre", "ma desc", "http://clapps.fr");
	})
	
	// Postuler 
	zf.$page.find('.apply_button').click(function(event) {
		event.preventDefault();
		console.log('postuler');
		zf.FBSend("mon titre", 100005028189455, "http://clapps.fr");
	})
	
};

zf.initAddProject = function() {

	zf.$newProject = $('#newProject');

	zf.$newProject.on('keypress','input',function(event) {
		if(event.keyCode == 13){
			event.preventDefault();
		}
	})

	zf.autocomplete(zf.$newProject);
	zf.$newProject.find('textarea').autosize();

	// date picker 
	zf.$newProject.find( ".datepicker" ).datepicker({ dateFormat: 'dd/mm/yy', minDate: 0 });
	
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
	zf.$newProject.on('click','.profiles ul li .number_control',function(event) {
		event.preventDefault();
		zf.numberControlProfile($(this));
	});
	
	// ADD POST
	zf.$newProject.on('click','.profiles #add-post',function(event) {
		event.preventDefault();
		zf.addLineProfile($(this));
	});
	
	// DELETE POST
	zf.$newProject.on('click','.profiles .delete',function(event) {
		event.preventDefault();
		zf.deleteLineProfile($(this));
	});
	
	// SEND PROJECT
	zf.$newProject.on('submit', function(event) {		
		event.preventDefault();
		var $this=$(this);
		$this.find('#add-project').attr('disabled','disabled')
		$('.required').removeClass('empty');
		if (zf.addAnonceFormOk($this)) {
			// test if value of place is empty
			$value = zf.$newProject.find('#block_place .id_place');
			if($value.val().trim().length == 0){
				zf.getPlacePosition($value.siblings('.location').val(), function(request){
					if(request=='notFound'){
						$('.message.error').fadeIn().find('p span').html('Cette destination est introuvable.');
						$this.find('#add-project').removeAttr('disabled');
						$value.siblings('.location').addClass('empty');
						return false;
					}
					if(request['success'] == true){
						$value.val(request['id']);
						$value.siblings('.type_place').val(request['type']);
						// requete ajax
						$.ajax({
							url: $this.attr('action'),
							type: $this.attr('method'),
							data: $this.serialize(),
							success: function(resp) {
								if (resp.success) {
									zf.getOneProject(resp.id);
									$('#successAddProject').fadeIn();
									$.fancybox.close();
									FB.Canvas.scrollTo(0,0);
									setTimeout(function(){
										$('#successAddProject').fadeOut();
									},5000);
									zf.$page.find('#see-mine .number').text(parseInt(zf.$page.find('#see-mine .number').text())+1)
								} else {
									$('.message.error').fadeIn().find('p span').html('Une erreur est survenue, veuillez réessayer');
									$this.find('#add-project').removeAttr('disabled');
								}
							}
						});
						$('.message.error').fadeOut();
					}
				});
			}else{
				$.ajax({
					url: $this.attr('action'),
					type: $this.attr('method'),
					data: $this.serialize(),
					success: function(resp) {
						if (resp.success) {
							zf.getOneProject(resp.id,function($thiz) {
								$thiz.css('opacity',1);
								$('#successAddProject').fadeIn();
								$('#successAddProject').after($thiz);
							});
							$.fancybox.close();
							FB.Canvas.scrollTo(0,0);
							setTimeout(function(){
								$('#successAddProject').fadeOut();
							},5000);
							zf.$page.find('#see-mine .number').text(parseInt(zf.$page.find('#see-mine .number').text())+1)
						} else {
							$('.message.error').fadeIn().find('p span').html('Une erreur est survenue, veuillez réessayer');
							$this.find('#add-project').removeAttr('disabled');
						}
					}
				});
				$('.message.error').fadeOut();
			}
			
		} else {
			$('.required[value=]').addClass('empty');
			// $('.required[value=]').css('border','1px solid red');
			$('.message.error').fadeIn().find('p span').html('Veuillez remplir tous les champs.');
			$this.find('#add-project').removeAttr('disabled')
		}
	})
};

zf.initEditProject = function() {
		
	// change display to edit project
	zf.$page.on('click','.editProject',function(event) {
		event.preventDefault();
		zf.editProject($(this));
		// date picker 
	});
	
	// valid update of project
	zf.$page.on('submit','.project form',function(event) {
		event.preventDefault();
		zf.updateProject($(this));
	});
	
	// cancel display of editing project
	zf.$page.on('click','.cancelEditProject',function(event) {
		event.preventDefault();
		zf.cancelEditProject($(this));
	});
	
	// button delete project
	zf.$page.on('click','.block_delete_project .button_delete_project',function(event) {
		event.preventDefault();
		$(this).siblings('.confirm').fadeIn(150);
	});
	
	// button cancel delete project
	zf.$page.on('click','.block_delete_project .cancel_delete_project',function(event) {
		event.preventDefault();
		$(this).parents('.confirm').fadeOut(150);
	});
	
	// button valid delete project
	zf.$page.on('click','.block_delete_project .valid_delete_project',function(event) {
		event.preventDefault();
		var $this=$(this);
		zf.deleteProject($(this), function(){
			// lancer la fancybox
			$.fancybox.open($this,{
				afterShow: zf.initDeleteProject,
				closeClick  : false,
				helpers   : { 
					overlay : {closeClick: false}
				},
				scrolling : 'no',
				topRatio : 0
			})
			// scrollTop 
			FB.Canvas.scrollTo(0,0);
		});
		$(this).parents('.confirm').fadeOut(150);
	});
	
	// number control
	zf.$projectsList.on('click','.profiles .quantity .number_control',function(event) {
		event.preventDefault();
		zf.numberControlProfile($(this));
	});
	
	// add profile line
	zf.$projectsList.on('click','.profiles .line_control .add-post',function(event) {
		event.preventDefault();
		zf.addLineProfile($(this));
	});
	
	// delete profile line 
	zf.$projectsList.on('click','.profiles .line_control .delete',function(event) {
		event.preventDefault();
		zf.deleteLineProfile($(this));
	});
	
	// button delete existing line
	zf.$projectsList.on('click','.profiles .edit .deleteButton a',function(event) {
		event.preventDefault();
		$(this).siblings('.confirm').fadeIn(150);
	});
	
	//  cancel delete existing line
	zf.$projectsList.on('click','.profiles .edit .cancel_delete_profile',function(event) {
		event.preventDefault();
		$(this).parents('.confirm').fadeOut(150);
	});
	//  confirm delete existing line
	zf.$projectsList.on('click','.profiles .edit .confirm_delete_profile',function(event) {
		event.preventDefault();
		$(this).parents('.confirm').fadeOut(150);
		zf.deleteProfile($(this));
	});
	
};

zf.initDeleteProject = function() {
	var $thisFancy=$(this);
	// init selector
	zf.customFields($('#blocDelete'));
	zf.$customFields = $('#blocDelete');
	
	// hide 'precisez' field
	$value=zf.$customFields.find('.selector .reason');
	$value.change(function() {
		if(($value.attr('value')=="autre_service")||($value.attr('value')=="autre")){
			zf.$customFields.find('.precise').fadeIn(300);
		}else{
			zf.$customFields.find('.precise').fadeOut(300);
		}
	});

	zf.$customFields.on('submit', 'form',function(event) {
		event.preventDefault();
		$.ajax({
				url: 'requests/deleteProjectWhy.php',
				type: 'post',
				type: 'post',
				data: $(this).serialize()+'&id='+$thisFancy[0].element.data('id'),
				success: function(resp) {
					// $('.message.success').fadeIn();
					setTimeout(function(){
						$.fancybox.close();
					},200);
				}
			});
	})
};

zf.init = function(){
	// init js
	$('body').addClass('has-js');
	
	// variables
	zf.$page = $('#page');
	zf.$filtre = zf.$page.find('#block_filters');
	zf.$projectsList = zf.$page.find('#projects');

	// show content 
	zf.$projectsList.hide();
	zf.$page.fadeIn();

	zf.$projectsList.find('.project').each(function(i) {
		var $this=$(this);
		setTimeout(function() {
			if (zf.currentAnim == event) {
				if (i < 1) {
					$this.css('opacity',1);
				} else {
					$this.css({position:'relative',opacity:0,left:'25px'}).animate({left:'0',opacity:1},500,'easeOutExpo');
				}
				$this.find(".preview .desc p").dotdotdot();
			}
		},i*300);
	});
	
	// init elements
	zf.filter();
	zf.initSeeProject();
	zf.initEditProject();
	zf.customFields(zf.$page);
	zf.autocomplete(zf.$page);
	zf.initFb();
	
	// Blank links
	$('a[rel=external]').click(function(){
		window.open($(this).attr('href'));
		return false;
	});
	
	// hide tuto first time
	zf.$page.find("#block_button_tuto a").click(function(event) {
		zf.$page.find("#tuto video").hide();
		zf.$page.find("#tuto").fadeOut(800, function(){
			zf.$page.find("#tuto").removeClass('intro').addClass('help');
		});
		zf.$projectsList.fadeIn(800);
		// enable filter
	});
	
	// hide tuto other time
	zf.$page.find("#block_help_tuto a").click(function(event) {
		zf.$page.find("#tuto video").hide();
		zf.$page.find("#tuto").fadeOut(800);
		zf.$projectsList.fadeIn(800);
		// enable filter
	});
	
	// show tuto
	zf.$page.find("#infoButton a").click(function(event) {
		zf.$page.find("#tuto").fadeIn(800);
		// close en disable filter
	});
	
	// close select & autocompletion
	$(window).click(function(event) {
		// event.preventDefault();
		if (event.target.localName != 'span' && event.target.className!='button') {
			$('#col2 .field .selector ul').hide()
		};
	})
	
	// extendProject
	zf.$projectsList.find('.extendProject').on('click',function(event) {
		event.preventDefault();
		var $this=$(this);
		$.ajax({
			url: 'requests/activateProject.php',
			type: 'post',
			data: {id:$this.data('id')},
			success: function(resp) {
				// console.log(resp);
			}
		});
	});

	// profileFound
	zf.$page.on('click','.profile_found',function(event) {
		event.preventDefault();
		var $this=$(this);
		$.ajax({
			url: 'requests/profileFound.php',
			type: 'post',
			data: {id_project:$this.data('id'),id_profile:$this.data('idprofile')},
			success: function(resp) {
				// console.log(resp);
				var $icon = $this.parent().siblings('.icon')
				if($icon.hasClass('iconTechnician')){
					var t = parseInt($this.parents('article').find('.technicians').find('span').text());
					var icon = parseInt($icon.find('span').text())
					$this.parents('article').find('.technicians').find('span').text(t-icon)
				}

				$icon.find('span').fadeOut();
				$this.parents('li.profile').addClass('profileFound');
				$this.parent().addClass('applyFound').html('Candidat trouvé');
			}
		});
	});

	// date picker 
	zf.$page.find( ".datepicker" ).each(function() {
  		$(this).datepicker({
    		altField: $(this).siblings('.date_filter'),
			dateFormat: 'dd MM yy', minDate: 0 ,altFormat : 'yy-mm-dd'
  		})
	});
	
	// init switch
	zf.$page.find( ".switch" ).each(function(){
		event.preventDefault();
		zf.initNotif($(this));
	})
	
	// change switch position
	zf.$page.find( ".switch" ).click(function(event) {
		event.preventDefault();
		zf.switchNotif($(this));
	})
	
	// show help filter
	zf.$page.find("#filter_advanced .nav a").hover(function(){
		if(zf.advancedFilterOpen==false){
			$actif = $(this).parent().attr('class');
			zf.$page.find("#block_filters .help_info ."+$actif).show();
		}
	},function(){
	    zf.$page.find("#block_filters .help_info li").hide();
	});
	
	// fancybox add project
	zf.$page.find(".addProject a").fancybox({
		afterShow: zf.initAddProject,
		closeClick  : false,
		helpers   : { 
			overlay : {closeClick: false},
		},
		topRatio : 0
	});
	
	// init page tab of filter
	zf.$filtre.find('.nav a').click(function(event) {
		$this=$(this);
		zf.$filtre.find('.nav a').removeClass('current');
		$this.addClass('current');
		var link= $this.attr('href');
		$this.parents('#filter_advanced').find('.tab.active').removeClass('active').hide();
		$this.parents('#filter_advanced').find(link).addClass('active').fadeIn(500);
	})
	
	// filter project
	zf.$filtre.on('submit', function(event){
		$thisField = $(this).find('.autocomplete.place')
		event.preventDefault();
		zf.getFilteredProjects($(this),event);
	});
	
	// filter project
	zf.$page.on('submit', '#addSubscribe', function(event) {
		event.preventDefault();
		zf.addSubscribe($(this));
	});
	
	// update projects list by distance
	zf.$filtre.find('#distances li a').click(function(event) {
		event.preventDefault();
		zf.updateFilter($(this));
	})
	
	// see mine projects
	zf.$page.on('click','#see-mine',function(event) { // a protéger avec un .queue() (spamclick)
		event.preventDefault();
		var $this=$(this);
		$this.attr('id','see-all').html('Voir toutes les annonces');
		zf.seeMine($this,event);
	});
	
	// see all projects
	zf.$page.on('click','#see-all',function(event) {
		event.preventDefault();
		var $this=$(this);
		//$this.attr('id','see-mine');
		zf.seeAll($this,event);
	});
	
	// More projects
	zf.projectCurrentPage = parseInt(zf.$projectsList.find('.btn-more-projects a').data('nav'),10) || 0;
	zf.$projectsList.on('click','.btn-more-projects a',function(event) {
		event.preventDefault();
		zf.getMoreProjects($(this),event);
	});
	
	// init geocoder google map 
	zf.geocoder = new google.maps.Geocoder();
	
	// init frame
	zf.launchScrollEvent();
	
};

/* DOM READY
--------------------------------------------------------------------------------------------------------------------------------------*/

$(document).ready(zf.init);