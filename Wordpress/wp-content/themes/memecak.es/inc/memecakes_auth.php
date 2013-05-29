<?php 

add_action('init', 'do_register_login_check');
function do_register_login_check() {
	global $wpdb;

	if(isset($_GET['key']) && $_GET['action'] == "reset_pwd") {

		$reset_key = $_GET['key'];
		$user_login = $_GET['login'];
		$user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));
		
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		
		if(!empty($reset_key) && !empty($user_data)) {
			$new_password = wp_generate_password(7, false);
			wp_set_password( $new_password, $user_data->ID );
			//mailing reset details to the user
			$message = __('Your new password for the account at:') . "\r\n\r\n";
			$message .= get_option('siteurl') . "\r\n\r\n";
			$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
			$message .= sprintf(__('Password: %s'), $new_password) . "\r\n\r\n";
			$message .= __('You can now login with your new password at: ') . get_option('siteurl')."/login" . "\r\n\r\n";
			
			if ( $message && !wp_mail($user_email, 'Password Reset Request', $message) ) {
				wp_die("Email failed to send for some unknown reason");
				exit();
			}
			else {
				wp_die("You've got a new mail with a new password!");
				exit();
			}
		} 
		else exit('Not a Valid Key.');
	}

	// not the login request?
	if(!isset($_POST['action'])) {
		return;
	}

	if ($_POST['action'] === 'login_action') {
		// see the codex for wp_signon()
		$result = wp_signon();

		if(is_wp_error($result)) {
			echo json_encode(array('success' => false, 'message' => 'Login failed. Wrong password or username'));
		}
		else {
			// redirect back to the requested page if login was successful    
			echo json_encode(array('success' => true));
		}
		exit;
	} else if($_POST['action'] === 'register_action') {

		//We shall SQL escape all inputs  
	    $username = $wpdb->escape($_REQUEST['username']);  
	    if(empty($username)) {  
	        echo json_encode(array('success' => false, 'message' => "Username should not be empty."));
	        exit();  
	    }  
	    $email = $wpdb->escape($_REQUEST['email']);  
	    if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email)) {  
	        echo json_encode(array('success' => false, 'message' => "Please enter a valid email."));
	        exit();  
	    }  
	  
	    $random_password = wp_generate_password( 12, false );  
	    $status = wp_create_user( $username, $random_password, $email );  
	    if ( is_wp_error($status) )  
	        echo json_encode(array('success' => false, 'message' => "Username or Email already exists. Please try another one."));
	    else {  
	        $from = get_option('admin_email');  
	        $headers = 'From: '.$from . "\r\n";
	        $subject = "Registration successful";
	        $msg = "Registration successful.\nYour login details\nUsername: $username\nPassword: $random_password";  
	        wp_mail( $email, $subject, $msg, $headers );  
	        
	        echo json_encode(array('success' => true));  
	    }
	    exit();
	} else if($_POST['action'] == "forgot_pass_action") {
		
		if ( !wp_verify_nonce( $_POST['memecakes_forgot_pass_nonce'], "memecakes_forgot_pass_nonce")) {
			exit("No trick please");
		}
		
		if(empty($_POST['user_input'])) {
			echo json_encode(array('success' => false, 'message' => 'Please enter your Username or E-mail address'));
			exit();
		}
		//We shall SQL escape the input
		$user_input = $wpdb->escape(trim($_POST['user_input']));
		
		if ( strpos($user_input, '@') ) {
			$user_data = get_user_by_email($user_input);
			if(empty($user_data)) { 
				echo json_encode(array('success' => false, 'message' => 'Invalid emailadress!'));
				exit();
			}
		}
		else {
			$user_data = get_userdatabylogin($user_input);
			if(empty($user_data)) { 
				echo json_encode(array('success' => false, 'message' => 'Invalid username!'));
				exit();
			}
		}
		
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		
		$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
		if(empty($key)) {
			//generate reset key
			$key = wp_generate_password(20, false);
			$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));	
		}
		
		//mailing reset details to the user
		$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
		$message .= get_option('siteurl') . "\r\n\r\n";
		$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
		$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
		$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
		$message .= home_url() . "?action=reset_pwd&key=$key&login=" . rawurlencode($user_login) . "\r\n";
		
		if ( $message && !wp_mail($user_email, 'Password Reset Request', $message) ) {
			echo json_encode(array('success' => false, 'message' => 'Email failed to send for some unknown reason.'));
			exit();
		}
		else {
			echo json_encode(array('success' => true));
			exit();
		}
	} else if($_POST['action'] == "logout_action") {
		
		if ( !wp_verify_nonce( $_POST['memecakes_logout_nonce'], "memecakes_logout_nonce")) {
			exit("No trick please");
		}

		wp_logout();
		echo json_encode(array('success' => true));
		exit();
	}

}