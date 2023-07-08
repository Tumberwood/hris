<?php
	if(file_exists("install/index.php")){
		//perform redirect if installer files exist
		//this if{} block may be deleted once installed
		header("Location: install/index.php");
	}

	require_once 'users/init.php';
	require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
	if(isset($user) && $user->isLoggedIn()){
		header("Location: usersc/applications/views/core/dashboard.php");
	}else{
		header("Location: usersc/applications/views/core/login.php");
	}
?>

<?php  languageSwitcher();?>

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>

<!-- Place any per-page javascript here -->
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>