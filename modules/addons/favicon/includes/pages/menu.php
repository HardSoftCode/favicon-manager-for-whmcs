<?php

echo '<link rel="stylesheet" href="../modules/addons/favicon/includes/html/css/sky-mega-menu.css">
    <link rel="stylesheet" href="../modules/addons/favicon/includes/html/css/sky-mega-menu-black.css">

		<!--[if lt IE 9]>
			<link rel="stylesheet" href="../modules/addons/favicon/includes/html/css/sky-mega-menu-ie8.css">
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!--[if lt IE 10]>
			<script src="../modules/addons/favicon/includes/html/js/jquery.placeholder.min.js"></script>
		<![endif]-->';

echo '<ul class="sky-mega-menu sky-mega-menu-anim-scale sky-mega-menu-response-to-icons">
				<li '; if($_REQUEST['a'] == '') { echo 'class="current"'; } echo '><a href="'.$modulelink.'"><i class="fas fa-home"></i>'.$LANG['home'].'</a></li>
				<li class="right"><a href="https://www.hardsoftcode.com/documentation.php?p=articles&a=favicon-manager" target="_blank"><i class="fas fa-question-circle"></i>'.$LANG['help'].'</a></li>
			</ul>
			<br>';
