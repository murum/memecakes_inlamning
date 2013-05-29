<?php

// add the memecake post type & taxonomies.
require_once('inc/memecake_posttype.php');
require_once('inc/memecake_taxonomies.php');
require_once('inc/memecakes_auth.php');

// Add social media user meta 
require_once('inc/memecakes_social_contact.php');

// add theme Thumbnail support in posts
add_theme_support( 'post-thumbnails' );

// hook onto dashboard and redirect all non admin to home
function redirect_nonadmin_fromdash() {
	if (!current_user_can('editor') && !current_user_can('administrator')) {
        wp_redirect( home_url() );
    }
}
add_action('admin_init','redirect_nonadmin_fromdash');


// hides the admin bar for users who shouldn't have it
function memecakes_show_admin_bar() {
    return current_user_can('manage_options');
}
add_filter( 'show_admin_bar', 'memecakes_show_admin_bar' );

// Add support for theme menu
function register_my_menu() {
  register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'register_my_menu' );

// Fixing the vote_ajax_fix_for_not_logged_users
function vote_ajax_fix_for_not_logged_users() {
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='postratings'){
		do_action( 'wp_ajax_' . $_REQUEST['action'] );
		do_action( 'wp_ajax_nopriv_' . $_REQUEST['action'] );
	}
}
add_action('init', 'vote_ajax_fix_for_not_logged_users');

// Add the memecake image size
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size("memecake", 755, 9999);
	add_image_size("memecake_thumbnail", 200,200,true);
	add_image_size("memecake_feed", 370, 9999);
}	

// set post thumbnail size
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 755, 755, true);
}

// Adds a memecake to column to display it as a nice feed.
function AddMemecakeToColumn($column, $meme) {
	$column[] = $meme;
	return $column;
}

// add disqus embed
function disqus_embed($disqus_shortname) {
    global $post;
    wp_enqueue_script('disqus_embed','http://'.$disqus_shortname.'.disqus.com/embed.js');
    echo '<div id="disqus_thread"></div>
    <script type="text/javascript">
        var disqus_shortname = "'.$disqus_shortname.'";
        var disqus_title = "'.$post->post_title.'";
        var disqus_url = "'.get_permalink($post->ID).'";
        var disqus_identifier = "'.$disqus_shortname.'-'.$post->ID.'";
    </script>';
}

// add share buttons
function addthis_embed() {
	echo "
		<!-- AddThis Button BEGIN -->
		<div class='addthis_toolbox addthis_default_style addthis_32x32_style'>
			<a class='addthis_button_preferred_1'></a>
			<a class='addthis_button_preferred_2'></a>
			<a class='addthis_button_preferred_3'></a>
			<a class='addthis_button_preferred_4'></a>
			<a class='addthis_button_compact'></a>
			<a class='addthis_counter addthis_bubble_style'></a>
		</div>
		<script type='text/javascript'>var addthis_config = {'data_track_addressbar':true};</script>
		<script type='text/javascript' src='//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-515e897b4ca2bd50'></script>
		<!-- AddThis Button END -->
	";
}

// Login fix with the redirect
add_filter('site_url',  'wplogin_filter', 10, 3);
function wplogin_filter( $url, $path, $orig_scheme ) {
	$old  = array( "/(wp-login\.php)/");
	$new  = array( "log-in");
	return preg_replace( $old, $new, $url, 1);
}

// Change the login logo
function custom_login_logo() {
	$template_dir = get_template_directory_uri();
    echo '
    	<link rel="stylesheet" href="'.$template_dir.'/css/style-login.css" type="text/css" media="all" />
    ';
}
add_action('login_head', 'custom_login_logo');


// Remove Unwanted Admin Menu Items

function remove_admin_menu_items() {
	$remove_menu_items = array(__('Posts'));
	global $menu;
	end ($menu);
	while (prev($menu)){
		$item = explode(' ',$menu[key($menu)][0]);
		if(in_array($item[0] != NULL?$item[0]:"" , $remove_menu_items)){
		unset($menu[key($menu)]);}
	}
}

add_action('admin_menu', 'remove_admin_menu_items');