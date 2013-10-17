<?php 
	require_once './inc/init.php';
	//require_once './inc/initFb.php';
	require_once './inc/settings.php';
	require_once './inc/functions.php';
	$page=$_GET['page'];
	if(!isset($page)){$page=1;}
	$userFilter = getUserFilter();
	parse_str($userFilter['filter'], $userFilterArray);

	// get projects
	if (isset($_GET['id_project'])) : // one project
		$getProjects=getProject($_GET['id_project']);
		$nbProject = getProject($_GET['id_project'],true);
	elseif ($_GET['filter']): // filtre on
		$getProjects=getProjectsByFilters($page,$_GET);
		$nbProject = getProjectsByFilters($page,$_GET,true);
	elseif ($userFilter['filter'] && !$_GET['user_fb'] && !$_GET['favorite']): // user got default filter but not in his projects
		$getProjects=getProjectsByFilters($page,$userFilterArray);
		$nbProject = getProjectsByFilters($page,$userFilterArray,true);
	elseif ($_GET['favorite']):
		$mine = true;
		$fav = true;
		$getProjects=getProjects($page,$_GET['user_fb'],true);
		$nbProject = getProjects($page,$_GET['user_fb'],true,true);
	else:
		if ($_GET['user_fb']){
			$mine = true;
		}
		$getProjects=getProjects($page,$_GET['user_fb']);
		$nbProject = getProjects($page,$_GET['user_fb'],false,true);
	endif;
	$nbProject = intval($nbProject[0]['count']);
?>

<!doctype html>
<html lang="fr">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# myclapps: http://ogp.me/ns/fb/myclapps#">
	<meta charset="utf-8">
	<?php if ($_GET['id_project'] || $_GET['app_data']): ?>
		<meta property="fb:app_id" content="112197008935023" />
		<?php if ($_GET['person']==true): ?>
			<meta property="og:type" content="myclapps:person" /> 
		<?php else: ?>
			<meta property="og:type" content="myclapps:announce" /> 
		<?php endif ?>
		<meta property="og:url" content="http://www.my.clapps.fr?id_project=<?php echo $_GET['id_project']; ?>" /> 
		<meta property="og:title" content="<?php echo $getProjects[0]['title']; ?>" />
		<meta property="og:description" content="<?php echo $getProjects[0]['description']; ?>" />
		<meta property="og:image" content="http://backup.clapps.fr/img/logo_clapps.png" />
	<?php else: ?>
		<meta property="og:title" content="My Clapps" />
		<meta property="og:description" content="L'application des professionnels du cinema" />
		<meta property="og:url" content="http://www.my.clapps.fr" /> 
		<meta property="og:image" content="http://backup.clapps.fr/img/logo_clapps.png" />
	<?php endif; ?>
	<title>My clapps</title>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">
	<script src="http://vjs.zencdn.net/c/video.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="./css/style.css">
	<link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" title="no title">
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
</head>

<body>	
		<div id="tuto" class="intro">
			<div id="light_header_tuto"></div>
			<div id="content_tuto">
				<div id="block_logo_tuto">
					<p>My clapps</p>
				</div>
				<div id="block_button_tuto" class="clearfix">
					<div class="desc">
						<h2>Pour cause de mise à jour,<br>My Clapps est désormais indisponible.</h2>
						<p>
							Nous vous <strong><a href="https://www.facebook.com/Clapps.Network">conseillons de suivre l'actualité</a></strong> pour être tenu<br>
							informé de la sortie de votre prochaine plate-forme. <br>
							<br>
							Merci de votre confiance, <br>
							<br>
							<strong>L'équipe Clapps</strong>.
						</p>
					</div>
				</div>
				<div class="share">
					<div class="fb"><a href="https://www.facebook.com/Clapps.Network">Rejoignez-nous</a></div>
					<div class="tw"><a href="https://twitter.com/Clapps_fr">Suivez-nous</a></div>
				</div>
			</div>
			<div id="curtain_tuto"></div>
			<div id="shadow_curtain"></div>
		</div><!-- fin tuto-->
		
	</div> <!-- fin page-->
	
	<div id="fb-root"></div>
	
	<script src="js/libs/jquery-1.8.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-36398282-2']); _gaq.push(['_trackPageview']); (function() { var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })();
	</script>
	
	<?php
		echo '<script>
			  window.fbAsyncInit = function() {
			    FB.init({
			      appId  : '.APP_ID.',
			      status : true, // check login status
			      cookie : true, // enable cookies to allow the server to access the session
			      xfbml  : true  // parse XFBML
			    });
				FB.Canvas.setAutoGrow();
			  };

			  (function() {
			    var e = document.createElement("script");
			    e.src = document.location.protocol + "//connect.facebook.net/fr_FR/all.js";
			    e.async = true;
			    document.getElementById("fb-root").appendChild(e);
			  }());
		</script>';
 	?>

</body>
</html>