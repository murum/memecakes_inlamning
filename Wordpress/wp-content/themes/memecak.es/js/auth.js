$(function() {
	function hideAllMessages() {
		$('.register-message').hide();
		$('.login-message').hide();
		$('.forgot-pass-message').hide();
	};

	$('.login-box').hide();
	$('.register-box').hide();
	$('.forgot-pass-box').hide();
	$('.register-message').hide();
	$('.login-message').hide();
	$('.forgot-pass-message').hide();
	
	$('#login-link-register').on('click', function(e) {
		e.preventDefault();
		$('.login-box').fadeOut(200);
		$('.register-box').toggle(400);
		hideAllMessages();
	});

	$('#login-link-forgot-pass').on('click', function(e) {
		e.preventDefault();
		$('.login-box').fadeOut(200, function() {
			$('.forgot-pass-box').toggle(400);
		});
		hideAllMessages();
	});

	$('#forgot-pass-link-login').on('click', function(e) {
		e.preventDefault();
		$('.forgot-pass-box').fadeOut(200, function() {
			$('.login-box').toggle(400);
		});
		hideAllMessages();
	});

	$('#forgot-pass-link-register').on('click', function(e) {
		e.preventDefault();
		$('.forgot-pass-box').fadeOut(200, function() {
			$('.register-box').toggle(400);
		});
		hideAllMessages();
	});

	$('.login-button').on('click', function(e) {
		e.preventDefault();
		$('.register-box').fadeOut(200);
		$('.forgot-pass-box').fadeOut(200);
		$('.login-box').toggle(400);
		$('.login-input[name="log"]').focus();
		hideAllMessages();
	});

	$('.register-button').on('click', function(e) {
		e.preventDefault();
		$('.login-box').fadeOut(200);
		$('.forgot-pass-box').fadeOut(200);
		$('.register-box').toggle(400);
		$('.register-input[name="username"]').focus();
		hideAllMessages();
	});

	$('.wsl_connect_with_provider').children().hide();
	$('.wsl_connect_with_provider[title="Connect with Facebook"]').append('<span class="icon-facebook icon-2x"></span>');
	$('.wsl_connect_with_provider[title="Connect with Twitter"]').append('<span class="icon-twitter icon-2x"></span>');


	$('#register-submit-button').on('click', function(e) {
		var input_data = $('#register-form').serialize();
		$.ajax({ 
			type: "POST", 
			url:  "", 
			dataType: "json",
			data: input_data, 
			success: function(data){ 
				$('.register-message').empty();
				$('.register-message').fadeIn(100);
				if(data.success) {
					$('.register-message').append('Register success. Please check your email for login details.');
					$('.register-box').fadeOut(5000, function() {
						$('.login-box').toggle(400);
					});
				} else {
					$('.register-message').append(data.message);
				}
			} 
		}); 
		return false;
	});

	$('#login-submit-button').on('click', function(e) {
		var input_data = $('#login-form').serialize();
		$.ajax({ 
			type: "POST", 
			url:  "",
			dataType: "json",
			data: input_data, 
			success: function(data){ 
				$('.login-message').empty();
				$('.login-message').fadeIn(100);
				if(data.success) {
					$('.login-message').append("Login success!");
					window.location.replace(window.location.pathname);
				} else {
					$('.login-message').append(data.message);
				}
			} 
		}); 
		return false;
	});

	$('#forgot-pass-submit-button').on('click', function(e) {
		var input_data = $('#forgot-pass-form').serialize();
		$.ajax({ 
			type: "POST", 
			url:  "",
			dataType: "json",
			data: input_data, 
			success: function(data){ 
				$('.forgot-pass-message').empty();
				$('.forgot-pass-message').fadeIn(100);
				if(data.success) {
					$('.forgot-pass-message').append("We have just sent you an email with Password reset instructions.");
				} else {
					$('.forgot-pass-message').append(data.message);
				}
			} 
		}); 
		return false;
	});

	$('.memecake-logout-submit').on('click', function(e) {
		if(confirm("Are you sure you want to logout?")) {
			var input_data = $('#logout-form').serialize();
			$.ajax({ 
				type: "POST", 
				url:  "",
				dataType: "json",
				data: input_data, 
				success: function(data){
					$('.logout-message').empty();
					if(data.success) {
						$('.logout-message').append("You were successfully logged out");
						$('.logout-message').fadeIn(500, function() {
							window.location.replace(window.location.pathname);
						});
					}
				}
			}); 
			return false;
		}
	});

	$('.memecake-profile-config-button').on('click', function(e) {
		e.preventDefault();
		$('#config-button').trigger('click');
	});
});