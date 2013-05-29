<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<!-- VIEWPORT -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- FAVICON -->
	<link rel="SHORTCUT ICON" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" />
	<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/images/favicons/touch-icon-iphone.png" /> <!-- 57x57 px -->
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/images/favicons/touch-icon-ipad.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/images/favicons/touch-icon-iphone4.png" />
	<link rel="apple-touch-startup-image" href="<?php echo get_template_directory_uri(); ?>/images/favicons/startup.png"> <!-- 320x460 px -->

	<!-- BOOTSTRAP -->
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/bootstrap/css/bootstrap.min.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/bootstrap/css/bootstrap-responsive.min.css" type="text/css" media="all" />

	<!-- FontAwesome -->
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.css" type="text/css" media="all" />
	<!--[if IE 7]>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/font-awesome-ie7.min.css" type="text/css" media="all" />
	<![endif]-->

	<!-- STYLESHEETS -->
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/max.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/typography.css" type="text/css" media="all" />

	<!-- WORDPRESS GENERERAD HEAD -->
	<?php wp_head(); ?>
	<!-- SLUT WORDPRESS GENERERAD HEAD -->
</head>

<body <?php body_class(); ?>>
	<div class="wrapper">
		<div class="navbar navbar-inverse navbar-static-top">
			<div class="navbar-inner">
				<div class="container">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="brand" href="<?php echo home_url(); ?>"><img class="logotype" src="<?php echo get_template_directory_uri(); ?>/images/memecakes_vit.png" alt="Memecakes for everyone" /></a>
					<div class="nav-collapse collapse">
						<?php wp_nav_menu(array('container' => '', 'before' => '', 'items_wrap' => '<ul class="main-nav">%3$s</ul>')); ?>	

						<p class="navbar-text pull-right">
							<ul class="nav-right">
							<?php if(!is_user_logged_in()) : ?>
								<li class="register align-right">
									<span class="register-button">Register</span>
									<div class="register-box">
										<h4>Connect with twitter or facebook</h4>
										<?php do_shortcode("[wordpress_social_login]"); ?>
										<h3>Register a new account</h3>
										<form method="post" id="register-form">
											<div class="register-message"></div>
											<label for="username">Username</label>
											<input type="text" class="register-input" id="username" name="username" placeholder="Type a cool username here..." />
											<p class="register-username-help">The username will be your identity on memecak.es. Choose it wisely.</p>
											<label for="email">Email</label>
											<input type="email" class="register-input" id="email" name="email" placeholder="Replace this text with your email" />
											<p class="register-email-help">We will bake you a nice password and then send it to this email.</p>
										<input type="hidden" name="action" value="register_action" />
										<input class="register-input register-submit" type="submit" id="register-submit-button" value="Register your account" />
										</form>
									</div>
								</li>
								<li class="login align-right">
									<span class="login-button">Log in</span>
									<div class="login-box">
										<h4>Connect with twitter or facebook</h4>
										<?php do_shortcode("[wordpress_social_login]"); ?>
										<div class="login-message"></div>
										<form method="post" id="login-form">
											<input class="login-input" name="log" id="login-name" placeholder="We want your username in this field..." type="text" />
											<input class="login-input" name="pwd" placeholder="...And your password in this" type="password" />
											<input class="login-input login-remember" type="checkbox" id="rememberme" name="rememberme" /><label class="login-remember-label" for="rememberme">Remember me, I love cakes</label>
											<input class="login-input login-submit" id="login-submit-button" type="submit" value="Login" />
											<input type="hidden" name="action" value="login_action" />
										</form>
										<a href="#" class="login-link" id="login-link-forgot-pass">oh shit, I forgot my password</a>
										<a href="#" class="login-link login-link-last" id="login-link-register">Wait a second, I'm not even registered</a>
									</div>
									<div class="forgot-pass-box">
										<div class="forgot-pass-message"></div>
										<p class="forgot-pass-text">We totally understand that your password was hard to remember.</p>
										<p class="forgot-pass-text">But just fill in your email or username and we will send you a new one right away.</p>
										<form id="forgot-pass-form"  method="post">			
											<input class="forgot-pass-input text" type="text" name="user_input" placeholder="We want your username or email in this field..." />
											<input type="hidden" name="action" value="forgot_pass_action" />
											<input type="hidden" name="memecakes_forgot_pass_nonce" value="<?php echo wp_create_nonce("memecakes_forgot_pass_nonce"); ?>" />
											<input type="submit" id="forgot-pass-submit-button" class="forgot-pass-input forgot-pass-submit" name="submit" value="Get me a new password" />
										</form>
										<a href="#" class="forgot-pass-link" id="forgot-pass-link-login">I just remembered it, let me login</a>
										<a href="#" class="forgot-pass-link forgot-pass-link-last" id="forgot-pass-link-register">Wait a second, I'm not even registered</a>
									</div>
								</li>
								<?php else : ?>
									<?php $current_user = wp_get_current_user(); ?>
										<?php if (!($current_user instanceof WP_User)) : return; endif; ?>
									<li class="memecake-auth-name"><a href="<?php echo home_url(); ?>/author/<?php echo $current_user->user_nicename; ?>"><?php echo $current_user->user_login; ?></a></li>
									<li class="memecake-logout-menuitem">
										<div class="logout-message"></div>
										<form id="logout-form" method="post">
											<input type="hidden" name="action" value="logout_action" />
											<input type="hidden" name="memecakes_logout_nonce" value="<?php echo wp_create_nonce("memecakes_logout_nonce"); ?>" />
											<span class="icon-signout memecake-logout-submit"></span>
										</form>
									</li>
									<li class="memecake-config-menuitem">
										<form id="config-profile-form" action="<?php echo home_url(); ?>/author/<?php echo $current_user->user_nicename; ?>" method="post">
											<input type="hidden" name="action" value="edit_profile_action" />
											<input type="hidden" name="memecakes_edit_nonce" value="<?php echo wp_create_nonce("memecakes_edit_nonce"); ?>" />
											<a href="#" class="icon-cog memecake-profile-config-button"></a>
											<input type="submit" id="config-button" class="logout-submit" name="submit" />
										</form>
									</li>
								<?php endif; ?>
								<li class="memecake-upload-menuitem">
									<a href="<?php echo home_url(); ?>/upload">Upload</a>
								</li>
							</ul>
						</p>

					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>
		<div id="content">
			<div class="container">
				
	