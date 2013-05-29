<?php 

function add_user_social_media($user_contact) {
	/* Add user contact methods */
	$user_contact['facebook'] = __('Facebook'); 
	$user_contact['twitter'] = __('Twitter'); 
	$user_contact['pinterest'] = __('Pinterest'); 
	$user_contact['hidden_email'] = __('Hidden email'); 

	unset($user_contact['aim']);
	unset($user_contact['yim']);
	unset($user_contact['jabber']);

	return $user_contact;
}

add_filter('user_contactmethods', 'add_user_social_media');