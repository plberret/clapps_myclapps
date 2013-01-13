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
				zf.$page.find('#see-mine .number').text(parseInt(zf.$page.find('#see-mine .number').text())+1)
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
				zf.$page.find('#see-mine .number').text(parseInt(zf.$page.find('#see-mine .number').text())-1)
			} else {

			}
		}
	});
};

zf.editProject = function($this) {
	$article=$this.parent().parent().parent().parent(); // best practise ????????
	$article.removeClass('read').addClass('edition');
	// change display 
	$this.parent().hide();
	$this.parent().siblings('.manage-edition').show();
	// enable fields
	$article.find("form input").removeAttr("disabled");
	$article.find("form textarea").removeAttr("disabled");
	// change profiles 
	$article.find('.more .add-line').show();
	$article.find('.more .desc').removeClass('desc').addClass('edit_desc');
	$article.find('.more .apply').hide();
	$article.find('.more .edit').show();
};

zf.updateProject = function($this) {
	zf.cancelEditProject();
};

zf.cancelEditProject = function($this) {
	$article=$this.parent().parent().parent().parent(); // best practise ????????
	$article.removeClass('edition').addClass('read');
	// change display 
	$this.parent().hide();
	$this.parent().siblings('.manage-read').show();
	// disable fields
	$article.find("form input").attr("disabled", "disabled");
	$article.find("form textarea").attr("disabled", "disabled");
	// change profiles 
	$article.find('.more .add-line').hide();
	$article.find('.more .edit_desc').addClass('desc').removeClass('edit_desc');
	$article.find('.more .edit').hide();
	$article.find('.more .apply').show();
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

zf.autocomplete = function($this) {
	$this.on('keyup', '.field .autocomplete', function(event){
	// zf.$page.find('.field .autocomplete').keyup(function(event){
		event.preventDefault();
		var $this=$(this);
		if (event.keyCode == 13 && !zf.isBlank($this.val())){
			// TRIGGER CLICK ON CURRENT DISTANCE <- old // hit enter so nothing to do...

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
	}).on('click', 'ul.autocompletion li a', function(){
		var $this=$(this);
		$this.parents('.autocompletion').siblings('.autocomplete').val($this.find('span').text())
	}).on('focusout', '.field .autocomplete', function(){
		// MFMFMF
		if (!$this.find('.autocompletion li').hasClass('current')) {
			$(this).val($this.find('.autocompletion li:first-child a').text())
		};
		$('.autocompletion').remove();
	});
}

zf.switchEvent = function($this) {
	$(this).$this;
	$current = $this.find('.current');
	$current.removeClass('current');
	if($current.hasClass('on')){
		$this.find('.off').addClass('current');
		var position = 1;
	}else{
		$this.find('.on').addClass('current');
		var position = 32;
	}
	$this.find('.switch_button').stop(true,false).animate({
		left: position,
	}, 300, 'easeInOutQuint');
}

zf.seeMore = function($this) {
	$this.parent().siblings('.more').stop(true,true).slideToggle(function() {
		if ($(this).css('display')=='none') {
			$this.fadeOut(500, function() {
			    $this.html('Voir plus').fadeIn(500);
			});
		} else {
			$this.fadeOut(500, function() {
			    $this.html('Voir moins').fadeIn(500);
			});
		}
	});
}

zf.seeFiltered = function(url,event){
	zf.currentAnim = event
	zf.$projectsList.fadeOut(300,function() {
		$(this).children().remove().end().show();
		var $newProject = $('<div/>');
		$newProject.load(url+' #page',function(resp) {
			var $this=$(this);
			zf.$page.find('#see-all').attr('id','see-mine').html($this.find('#see-mine').html());
			$this.find('.project').each(function(i) {
				var $this=$(this);
				setTimeout(function() {
					if (zf.currentAnim == event) {
						zf.$projectsList.append($this.css({position:'relative',opacity:0,left:'500px'}).animate({left:'0',opacity:1},500));
					} else {
						console.log('blocked',event)
					}
				},i*300);
			});
			// zf.$projectsList.delay(($this.find('.project').length)*300).append($this.find('.btn-more-projects'));
			setTimeout(function() {
				zf.$projectsList.append($this.find('.btn-more-projects'));
			},$this.find('.project').length*300)
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
						zf.$projectsList.append($this.css({position:'relative',opacity:0,left:'500px'}).animate({left:'0',opacity:1},500));
					} else {
						console.log('blocked',event)
					}
					// zf.$projectsList.append($this.hide().fadeIn(500));
				},i*300);
			});
			// zf.$projectsList.delay(($this.find('.project').length)*300).append($this.find('.btn-more-projects'));
			setTimeout(function() {
				zf.$projectsList.append($this.find('.btn-more-projects'));
			},$this.find('.project').length*300)
		});
	});
};

zf.seeMine = function($_this,event) {
	zf.currentAnim = event
	var url = $_this.attr('href');
	zf.$projectsList.fadeOut(300,function() {
		$(this).children().remove().end().show();
		var $newProject = $('<div/>');
		$newProject.load(url+' #page',function(resp) {
			var $this=$(this);
			// $_this.attr('id','see-mine').html($this.find('#see-mine').html());
			$this.find('.project').each(function(i) {
				var $this=$(this);
				setTimeout(function() {
					if (zf.currentAnim == event) {
					// zf.$projectsList.append($this.hide().fadeIn(500));
						zf.$projectsList.append($this.css({position:'relative',opacity:0,left:'500px'}).animate({left:'0',opacity:1},500));
					} else {
						console.log('blocked',event)
					}
				},i*300);
			});
			// zf.$projectsList.delay(($this.find('.project').length)*300).append($this.find('.btn-more-projects'));
			setTimeout(function() {
				zf.$projectsList.append($this.find('.btn-more-projects'));
			},$this.find('.project').length*300)
		});
	});
};

zf.getMoreProjects = function($this,event) {
	zf.currentAnim = event
	zf.projectCurrentPage++;
	var $newProject = $('<div/>');
	$newProject.load('index.php?page='+zf.projectCurrentPage+' #projects',function(resp) {
		var $this=$(this);
		$this.find('.project').each(function(i) {
			var $this=$(this);

			setTimeout(function() {
				if (event == zf.currentAnim) {
					zf.$projectsList.find('.btn-more-projects').before($this.css({position:'relative',opacity:0,left:'500px'}).animate({left:'0',opacity:1},500));
				}
				else {
					console.log('bloked',event)
				}
				// zf.$projectsList.find('.btn-more-projects').before($this.hide().fadeIn(500)); // old
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
	$currentFilter.show();
	zf.$page.find('#block_current_filter.none').hide();
	$currentFilter.find('.time').text($this.find('.'+zf.$page.find('#date_filter').val()).text())
	$currentFilter.find('.work').text(' '+$this.find('#profile').val())
	$currentFilter.find('.location').text($this.find('#location').val())
	$currentFilter.find('.distance').text($this.find('#distance').val()+'km')
	if ($this.find('#location').val().trim().length==0) {
		$currentFilter.find('.opt').hide()
	} else {
		$currentFilter.find('.opt').show()
	}
}

zf.addAnonceFormOk = function(form){
	// console.log(zf.$newProject.find('.field input'))
	zf.$newProject.find('.required').each(function(){
		if ($(this).val().trim().length == 0) {
			// console.log($(this))
			return false
		};
	})
	return false
}

zf.initAddProject = function() {
	
	zf.$newProject = $('#newProject');

	zf.autocomplete(zf.$newProject);

	// date picker 
	zf.$newProject.find( ".datepicker" ).datepicker();
	
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
	zf.$newProject.on('click','.profiles div .number_control',function(event) {
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
		var $this=$(this);
		// if (zf.$newProject.find('.profiles p:last .entitled').val()!='') { // if last post isn't empty
			var newPost = zf.$newProject.find('.profiles > div:last').clone().find('.entitled').val('').siblings('.number').val('1').end().end();
			$this.attr('id','').removeClass('add-post').addClass('delete').html('-');
			zf.$newProject.find('.profiles').append(newPost);
		// };
		$this.parents('.profiles').find('.required').removeClass('required').end().children('div').eq(0).find('input[type=text]').addClass('required');
	});
	
	// DELETE POST
	zf.$newProject.on('click','.delete',function(event) {
		event.preventDefault();
		var $this=$(this);
		var $tparent = $this.parents('.profiles')
		if ($this.parent('div').siblings('.entitled').val()!='') { // if last post isn't empty
			var oldPost = $this.parent('div').parent('div').clone().end().remove(); // recovery oldPost mb
		} else {
			$this.parent('div').parent('div').remove();
		}
		$tparent.find('.required').removeClass('required').end().children('div').eq(0).find('input[type=text]').addClass('required');
	});
	
	// SEND PROJECT
	zf.$newProject.on('submit', function(event) {
		event.preventDefault();
		$('.required').removeClass('empty');
		if (zf.addAnonceFormOk($(this).serialize())) {
			$.ajax({
				url: $(this).attr('action'),
				type: $(this).attr('method'),
				data: $(this).serialize(),
				success: function(resp) {
					// resp = JSON.parse(resp);
					$.fancybox.close();
					zf.getOneProject(resp.id);
					$('.message.success').fadeIn();
					// location.reload();
				}
			});
			$('.message.error').fadeOut();
		} else {
			$('.required[value=]').addClass('empty');
			// $('.required[value=]').css('border','1px solid red');
			$('.message.error').fadeIn();
		}
		
	})
};

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
	console.log($this[0])
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
						html :'<a href="#" data-id="'+resp[i]['id_ville']+'" data-type="'+resp[i]["type"]+'"><span>'+resp[i]['nom']+'</span> '+resp[i]['cp']+'</a>'
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
				$curr.next().addClass('current'); // add class to next()
			} else { // else
				$next = $ul.children().eq(0)
				$next.addClass('current'); // add class to first one
			}
			$this.val($next.text());
		} else { // if current doesn't exist or if there is 1 result
			$first = $ul.find('li:first-child') // define first
			if ($this.val().trim()!=$first.find('span').text()) { // if current doesn't exist
				$first.addClass('current'); // define first child as current
				$this.val($first.text());
			};
		}
	}; // /if ul contains li
};

zf.jsonListUp = function($this) {
	$ul = $('ul.autocompletion')
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
			$this.val($prev.text());
		} else { // if current doesn't exist or if there is 1 result
			$last = $ul.find('li:last-child') // define last
			if ($this.val().trim()!=$last.find('span').text()) { // if current doesn't exist
				$last.addClass('current'); // define last child as current
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
	
	//var
	zf.filterOpen= true;
	zf.advancedFilterOpen= false;
	$filter = zf.$page.find('#block_filters');
	advancedFilter = zf.$page.find('#filter_advanced');
	
	$(document).bind('mousewheel',function(event){
		if ($(document).scrollTop()<=128 && !zf.filterOpen && event.originalEvent.wheelDelta > 0) {
			zf.$projectsList.css({paddingTop:"180px"});
			$(document).scrollTop(0);
		} else if ($(document).scrollTop()<=128 && zf.filterOpen && event.originalEvent.wheelDelta > 0) {
			zf.$projectsList.animate({paddingTop:"300px"}); // ça passe crème
		};
	})
	

	zf.$page.find('#searchButton, .open_filtre').click(function(event){
		event.preventDefault();
		if(zf.filterOpen==true){
			$height= "-135";
			$top = "180px"
			zf.filterOpen=false;
		} else {
			$height= "58";
			$top = "300px"
			zf.filterOpen=true;
		}
		$filter.stop(true,false).animate({
			top: $height,
		}, 1000, 'easeInOutExpo', function() {
			// Animation complete.
			//alert('oui'); 
		});
		if ($(document).scrollTop()<=40) {
			zf.$projectsList.stop(true,false).animate({paddingTop:$top}, 1000, 'easeInOutExpo', function() {
				// Animation complete.
				//alert('oui'); 
			});
		};
		//return false;
	});
	
	advancedFilter.find('.nav a').click(function(event){
		event.preventDefault();
		advancedFilter.find('.nav a').parent().removeClass('current');
		$(this).parent().addClass('current');
		advancedFilter.stop().animate({
			width: '590',
		}, 600, 'easeInOutExpo', function() {
			// Animation complete.
			//alert('oui'); 
		});
		// return false;
	});
	advancedFilter.find('a.close').click(function(event){
		event.preventDefault();
		advancedFilter.find('.nav a').parent().removeClass('current');
		advancedFilter.stop().animate({
			width: '50',
		}, 600, 'easeInOutExpo', function() {
			// Animation complete.
			//alert('oui'); 
		});
		return false;
	});
	
	advancedFilter.find('input.valid_button').click(function(event){ // a fix pour enregistrer le filtre
		event.stopPropagation();
		advancedFilter.find('.nav a').parent().removeClass('current');
		advancedFilter.stop().animate({
			width: '50',
		}, 600, 'easeInOutExpo', function() {
			// Animation complete.
			//alert('oui'); 
		});
		// return false;
	});
	
};

zf.customFields = function(){
	
	// custom select
	zf.$page.find(".selector .value, .selector .button ").click(function(){
		var $this = $(this);
		$(this).parent().siblings('ul').show();
		console.log($(this).parent().siblings('ul')[0])
	});
	
	zf.$page.find(".selector ul li").click(function(){
		var $this = $(this);
		$this.parent('ul').hide();
		$this.parent('ul').siblings('div').find('.value').html($this.html());
		$this.parent('ul').siblings('input').attr('value', $this.attr('class'));
	});
	
};

zf.init = function(){
	
	// init js
	$('body').addClass('has-js');
	
	// variables
	zf.$page = $('#page');
	zf.$filtre = zf.$page.find('#block_filters');
	zf.$projectsList = zf.$page.find('#projects');
	
	// init elements
	zf.filter();
	zf.customFields();
	zf.autocomplete(zf.$page);
	
	// Blank links
	$('a[rel=external]').click(function(){
		window.open($(this).attr('href'));
		return false;
	});
	
	// hide tuto
	zf.$page.find("#block_button_tuto a").click(function(event) {
		zf.$page.find("#tuto").hide();
		zf.$page.find("header").show();
		zf.$projectsList.show();
	});
	
	// show tuto
	zf.$page.find("#infoButton a").click(function(event) {
		zf.$page.find("#tuto").show().css({'top': '58px'});
	});
	
	// fixe header 
//	$(window).scroll(function(){
//		$('header').css('top', $(this).scrollTop() + "px");
//	});
	
	// close select & autocompletion
	$(window).click(function(event) {
		event.preventDefault();
		if (event.target.localName != 'span' && event.target.className!='button') {
			$('#col2 .field .selector ul').hide()
		};
		$('.autocompletion').remove()
	})
	
	// date picker 
	zf.$page.find( ".datepicker" ).datepicker();
	
	// switch
	zf.$page.find( ".switch" ).click(function(event) {
		event.preventDefault();
		zf.switchEvent($(this));
	})
	
	// fancybox add project
	zf.$page.find(".addProject a").fancybox({
		afterShow: zf.initAddProject,
		closeClick  : false,
		helpers   : { 
			overlay : {closeClick: false}
		}
	});
	
	// fancybox delete project 
	zf.$page.find("a.deleteProject").fancybox({
		afterShow: zf.initDeleteProject,
		closeClick  : false,
		helpers   : { 
			overlay : {closeClick: false}
		}
	});
	
	// init page tab of lifter
	zf.$filtre.find('.nav a').click(function(event) {
		$this=$(this);
		var link= $this.attr('href');
		console.log(link);
		$this.parents('#filter_advanced').find('.tab.active').removeClass('active').hide();
		$this.parents('#filter_advanced').find(link).addClass('active').fadeIn(500);
		
	})
	
	// filter project
	zf.$filtre.on('submit', function(event){
		event.preventDefault();
		zf.getFilteredProjects($(this),event);
	});
	
	// update projects list by distance
	zf.$filtre.find('#distances li a').click(function(event) {
		event.preventDefault();
		zf.updateFilter($(this));
	})
	
	// add favorite
	zf.$page.on('click','.favorite_link',function(event) {
		event.preventDefault();
		zf.addFavorite($(this));
	});
	
	// remove from favorite
	zf.$page.on('click','.unfavorite_link',function(event) {
		event.preventDefault();
		zf.deleteFavorite($(this));
	});
	
	// change display to edit project
	zf.$page.on('click','.editProject',function(event) {
		event.preventDefault();
		zf.editProject($(this));
	});
	
	// update content of project
	zf.$page.on('submit','.project form',function(event) {
		alert('oui'); 
		event.preventDefault();
		zf.updateProject($(this));
	});
	
	// cancel display of editing project
	zf.$page.on('click','.cancelEditProject',function(event) {
		event.preventDefault();
		zf.cancelEditProject($(this));
	});
	
	// delete project
	zf.$page.on('click','.deleteProject',function(event) {
		event.preventDefault();
		zf.deleteProject($(this));
	});
	
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
	
	// see more/less of project
	zf.$projectsList.on('click','.see-more',function(event) {
		event.preventDefault();
		zf.seeMore($(this));
	});
	
	// More projects
	zf.projectCurrentPage = parseInt(zf.$projectsList.find('.btn-more-projects a').data('nav'),10) || 0;
	zf.$projectsList.on('click','.btn-more-projects a',function(event) {
		event.preventDefault();
		zf.getMoreProjects($(this),event);
	});
	
};

/* DOM READY
--------------------------------------------------------------------------------------------------------------------------------------*/

$(document).ready(zf.init);