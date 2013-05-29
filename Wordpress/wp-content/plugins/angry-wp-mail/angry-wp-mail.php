<?php
/*
Plugin Name: Angry WP-Mail
Description: A wordpress e-mail plugin with dynamic templates by Angry Creative
Version: 0.1 Beta
Author: AngryCreative
Author URI: http://www.angrycreative.se
*/

// First, let's set up a few default settings
$acwpm_default_options = array('mail_content_type' => 'text/plain',
                               'mail_from' => get_bloginfo('admin_email'),
                               'mail_from_name' => get_bloginfo('name'));

$acwpm_required_options = array('mail_content_type',
                                'mail_from',
                                'mail_from_name');

$acwpm_default_templates = array('wp_new_user_notification',
                                 'wp_retrieve_password',
                                 'wp_notify_postauthor',
                                 'wp_notify_moderator',
                                 'wp_password_change_notification');

// Now override the defaults with possible user defined settings
$acwpm_user_options = get_option('acwpm_options');
foreach($acwpm_default_options as $default_key => $default_value) {
    if (!isset($acwpm_user_options[$default_key])) {
        $acwpm_user_options[$default_key] = $acwpm_default_options[$default_key];
    } else if (empty($acwpm_user_options[$default_key]) && in_array($default_key, $acwpm_required_options)) {
        $acwpm_user_options[$default_key] = $acwpm_default_options[$default_key];
    }
}

update_option('acwpm_options', $acwpm_user_options);

// Then we add some filters to apply our settings
add_filter ("wp_mail_content_type", "acwpm_get_option_mail_content_type");
function acwpm_get_option_mail_content_type() {
        $options = get_option('acwpm_options');
	return $options['mail_content_type'];
}
	
add_filter ("wp_mail_from", "acwpm_get_option_mail_from");
function acwpm_get_option_mail_from() {
	$options = get_option('acwpm_options');
	return $options['mail_from'];
}
	
add_filter ("wp_mail_from_name", "acwpm_get_option_mail_from_name");
function acwpm_get_option_mail_from_name() {
	$options = get_option('acwpm_options');
	return $options['mail_from_name'];
}

// Function called on post actions that mails possible post action mail template
function acwpm_post_notification($post_id) {
    
    //echo "<pre>Post notification was called!</pre>"; 
    
    global $post;
    if (!isset($post) || !isset($post->ID) || $post->ID != $post_id) {
	$post = get_post($post_id);
    }
    foreach($post as $post_key => $post_value) {
	$post_key = preg_replace("/^post_/", "", $post_key);
	// echo "<pre>Adding shortcode: post_$post_key, Current value: '$post_value'</pre>"; 
	add_shortcode("post_".$post_key, 'acwpm_post_shortcodes_handler');
    }
    
    $post_status = get_post_status( $post_id );
    $post_type = get_post_type( $post_id );
    $acwpm_options = get_option('acwpm_options');
    if (is_array($acwpm_options['post_templates']) && !empty($acwpm_options['post_templates'])) {
        
	global $wp_roles;
	global $current_user;
	get_currentuserinfo();
	$template_keys = array();
	foreach ( $wp_roles->role_names as $role => $name ) {
		if ( current_user_can( $role ) ) {
			$template_keys[] = $role . "_" . $post_status . "_" . $post_type;
		}
	}
	if (!empty($template_keys)) {
	    $template_keys[] = "loggedin";
	}
	$template_keys[] = "anyone";
	
	foreach($template_keys as $template_key) {
	    
	    if (!isset($acwpm_options['post_templates'][$template_key]) || empty($acwpm_options['post_templates'][$template_key])) {
		continue;
	    }
	
	    //echo "<pre>Template key: $template_key</pre>"; 
	    
	    switch ($acwpm_options['post_templates'][$template_key]['mail_recipient']) {
		case "site_admin":
		    $recipient_mail = get_bloginfo('admin_email');
		    break;
		case "post_author":
		    $recipient_mail = get_the_author_meta( 'user_email', $post->post_author );
		    break;
		case "email":
		    $recipient_mail = $acwpm_options['post_templates'][$template_key]['mail_recipient_arg'];
		    break;
		case "meta_value":
		    $meta_key = $acwpm_options['post_templates'][$template_key]['mail_recipient_arg'];
		    $recipient_mail = get_post_meta($post_id, $meta_key, true);
		    if (!$recipient) {
			$recipient_mail = get_option($meta_key);
		    }
		    break;
		default:
		    break;
	    }
	    //echo "<pre>Recipient: $recipient_mail</pre>"; 
	    if (!$recipient_mail) {
		return false;
	    }
	    
	    // Sorry for global...
	    global $acwpm_recipient_object;
	    $acwpm_recipient_object = get_user_by('email', $recipient_mail);
	    if (is_object($acwpm_recipient_object)) {
		foreach($acwpm_recipient_object as $user_key => $user_value) {
		    //echo "<pre>Adding shortcode: user_$user_key</pre>"; 
		    add_shortcode("user_".$user_key, 'acwpm_recipient_shortcodes_handler');
		}
		$usermeta = get_user_meta($acwpm_recipient_object->ID);
		foreach($usermeta as $user_key => $user_value) {
		    //echo "<pre>Adding shortcode: user_$user_key</pre>"; 
		    add_shortcode("user_".$user_key, 'acwpm_recipient_shortcodes_handler');
		}
		//echo "<pre>Adding shortcode: user_display_name</pre>";
		add_shortcode("user_display_name", 'acwpm_recipient_shortcodes_handler');
	    }
	    
	    //global $shortcode_tags;
	    //echo "<pre>" . print_r($shortcode_tags, true) . "</pre>";
	    
	    if (is_array($acwpm_options['post_templates'][$template_key]) && !empty($acwpm_options['post_templates'][$template_key]) && !empty($acwpm_options['post_templates'][$template_key]['content'])) {
		$message = do_shortcode($acwpm_options['post_templates'][$template_key]['content']);
		// echo "<pre>" . print_r($message, true) . "</pre>";
		wp_mail($recipient_mail, $acwpm_options['post_templates'][$template_key]['subject'], $message);
	    }
	}
	foreach($post as $post_key => $post_value) {
	    $post_key = preg_replace("/^post_/", "", $post_key);
	    remove_shortcode("post_".$post_key);
	}
	foreach($acwpm_recipient_object as $user_key => $user_value) {
	    $user_key = preg_replace("/^user_/", "", $user_key);
	    remove_shortcode("user_".$user_key);
	}
	foreach($usermeta as $user_key => $user_value) {
	    $user_key = preg_replace("/^user_/", "", $user_key);
	    remove_shortcode("user_".$user_key);
	}
	remove_shortcode("user_display_name");
    }
}
add_action("save_post", "acwpm_post_notification");

function acwpm_get_template_key($role = "subscriber", $post_status = "publish", $post_type = "post") {
    if ($role != "anyone" && $role != "loggedin" && !get_role($role)) {
	return false;
    }
    $post_statuses = array( 'publish',
			    'future',
			    'draft',
			    'pending',
			    'private',
			    'trash',
			    'auto-draft',
			    'inherit');
    if (!in_array($post_status, $post_statuses)) {
	return false;
    }
    $post_types = get_post_types();
    if (!in_array($post_type, $post_types)) {
	return false;
    }
    return $role . "_" . $post_status . "_" . $post_type;
}

function acwpm_set_mail_recipient($email, $role = "author", $post_status = "publish", $post_type = "post") {
    $options = get_option('acwpm_options');
    $template_key = acwpm_get_template_key($role, $post_status, $post_type);
    if (!$template_key) {
	return false;
    }
    $options['post_templates'][$template_key]['mail_recipient'] = "email";
    $options['post_templates'][$template_key]['mail_recipient_arg'] = $email;
    return update_option('acwpm_options', $options);
}

// Post Shortcodes
function acwpm_post_shortcodes_handler($atts, $content = null, $tag) {
    global $post;
    $matched_key = ($atts[0]) ? $atts[0] : $tag;
    $patched_key = preg_replace("/^post_/", "", $matched_key);
    if (is_object($post) && !empty($post->{$matched_key})) {
	return $post->{$matched_key};
    } else if (is_object($post) && !empty($post->{$patched_key})) {
	return $post->{$patched_key};
    } else {
	return "[".$matched_key."]";
    }
}

// Recipient Shortcodes
function acwpm_recipient_shortcodes_handler($atts, $content = null, $tag) {
    global $acwpm_recipient_object;
    $matched_key = ($atts[0]) ? $atts[0] : $tag;
    $matched_key = preg_replace("/^user_/", "", $matched_key);
    if (is_object($acwpm_recipient_object) && !empty($acwpm_recipient_object->{$matched_key})) {
	return $acwpm_recipient_object->{$matched_key};
    } else if (is_object($acwpm_recipient_object) && $matched_key == "display_name") {
	    return $acwpm_recipient_object->display_name;
    } else if (is_object($acwpm_recipient_object) && !empty($acwpm_recipient_object->ID)) {
	$usermeta = get_user_meta($acwpm_recipient_object->ID);
	if (is_array($usermeta) && !empty($usermeta[$matched_key])) {
	    return $usermeta[$matched_key];
	}
    } else {
	return "[".$matched_key."]";
    }
}

// Admin init
add_action('admin_init', 'acwpm_admin_init' );
add_action('admin_menu', 'acwpm_admin_add_page');

// Init plugin options to white list our options
function acwpm_admin_init(){
        wp_enqueue_script('common');
        wp_enqueue_script('wp-lists');
        wp_enqueue_script('postbox');
	register_setting( 'acwpm_admin', 'acwpm_options', 'acwpm_options_validate' );
}

// Add menu page
function acwpm_admin_add_page() {
	add_options_page('Angry WP-Mail Options', 'Angry WP-Mail', 'manage_options', 'acwpm_options', 'acwpm_options_do_page');
}

function acwpm_post_template_box($post, $template) {
    $post_types = get_post_types();
    $post_statuses = array( 'publish',
			    'future',
			    'draft',
			    'pending',
			    'private',
			    'trash',
			    'auto-draft',
			    'inherit');
    $template_key = $template['args']['user_role']."_".$template['args']['post_status']."_".$template['args']['post_type'];
    ?>
    <table class="form-table">
	<tr valign="top">
	    <th scope="row">User role</th>
	    <td>
		<select name="acwpm_options[post_templates][<?php echo $template_key; ?>][user_role]">
		    <option value="anyone"<?php echo ($template['args']['user_role'] == "anyone") ? " selected" : ""; ?>>Anyone</option>
		    <option value="loggedin"<?php echo ($template['args']['user_role'] == "loggedin") ? " selected" : ""; ?>>Logged in</option>
		    <?php wp_dropdown_roles($template['args']['user_role']); ?>
		</select>
	    </td>
	</tr>
	<tr valign="top">
	    <th scope="row">Post status</th>
	    <td>
		<select name="acwpm_options[post_templates][<?php echo $template_key; ?>][post_status]">
		    <?php foreach($post_statuses as $post_status) { ?>
			<option value="<?php echo $post_status; ?>"<?php echo ($template['args']['post_status'] == $post_status) ? " selected" : ""; ?>><?php echo ucfirst($post_status); ?></option>
		    <?php } ?>
		</select>
	    </td>
	</tr>
	<tr valign="top">
	    <th scope="row">Post type</th>
	    <td>
		<select name="acwpm_options[post_templates][<?php echo $template_key; ?>][post_type]">
		    <?php foreach($post_types as $post_type) { ?>
			<option value="<?php echo $post_type; ?>"<?php echo ($template['args']['post_type'] == $post_type) ? " selected" : ""; ?>><?php echo ucfirst($post_type); ?></option>
		    <?php } ?>
		</select>
	    </td>
	</tr>
	<tr valign="top">
	    <th scope="row">Mail recipient</th>
	    <td>
		<select name="acwpm_options[post_templates][<?php echo $template_key; ?>][mail_recipient]">
		    <option value="site_admin"<?php echo ($template['args']['mail_recipient'] == "site_admin") ? " selected": ""; ?>>Site Admin</option>
		    <option value="post_author"<?php echo ($template['args']['mail_recipient'] == "post_author") ? " selected": ""; ?>>Post Author</option>
		    <option value="email"<?php echo ($template['args']['mail_recipient'] == "email") ? " selected": ""; ?>>Specified E-mail</option>
		    <option value="meta_value"<?php echo ($template['args']['mail_recipient'] == "meta_value") ? " selected": ""; ?>>Specified Meta Value</option>
		</select><br />
		If applicable, enter additional argument:<br />
		<input type="text" name="acwpm_options[post_templates][<?php echo $template_key; ?>][mail_recipient_arg]" value="<?php echo $template['args']['mail_recipient_arg']; ?>" />
	    </td>
	</tr>
	<tr valign="top">
	    <th scope="row">Mail subject</th>
	    <td><input type="text" name="acwpm_options[post_templates][<?php echo $template_key; ?>][subject]" value="<?php echo $template['args']['subject']; ?>" /></td>
	</tr>
	<tr valign="top">
	    <th scope="row">Mail content</th>
	    <td><textarea rows="10" cols="50" name="acwpm_options[post_templates][<?php echo $template_key; ?>][content]" class="large-text code"><?php echo $template['args']['content']; ?></textarea></td>
	</tr>
	<tr valign="top">
	    <th scope="row">Delete template</th>
	    <td><input type="checkbox" name="acwpm_options[post_templates][<?php echo $template_key; ?>][delete]" /> Yes I'm sure, delete this template.</td>
	</tr>
    </table>
    <?php
}

// Draw the menu page itself
function acwpm_options_do_page() {
    $post_types = get_post_types();
    $post_statuses = array( 'publish',
			    'future',
			    'draft',
			    'pending',
			    'private',
			    'trash',
			    'auto-draft',
			    'inherit');
    global $wp_roles;
    ?>
    <div class="wrap">
	    <h2>Angry WP-Mail Options</h2>
	    <form method="post" action="options.php">
		    <?php settings_fields('acwpm_admin'); ?>
		    <?php $options = get_option('acwpm_options'); ?>
		    <h3>General Settings</h3>
		    <table class="form-table">
			    <tr valign="top"><th scope="row">Mail Content Type</th>
				    <td><select name="acwpm_options[mail_content_type]">
					<option value="text/html"<?php echo ($options['mail_content_type'] == "text/html") ? " selected" : ""; ?>>HTML</option>
					<option value="text/plain"<?php echo ($options['mail_content_type'] == "text/plain") ? " selected" : ""; ?>>Plain text</option>
				    </td>
			    </tr>
			    <tr valign="top"><th scope="row">Mail From Address</th>
				    <td><input type="text" name="acwpm_options[mail_from]" value="<?php echo $options['mail_from']; ?>" /></td>
			    </tr>
			    <tr valign="top"><th scope="row">Mail From Name</th>
				    <td><input type="text" name="acwpm_options[mail_from_name]" value="<?php echo $options['mail_from_name']; ?>" /></td>
			    </tr>
		    </table>
		    <?php if (is_array($options['post_templates']) && !empty($options['post_templates']) ) { ?>
			<h3>Post Action Templates</h3>
			<div id="poststuff" class="metabox-holder">
			<?php foreach($options['post_templates'] as $i => $template) { ?>
			    <?php
			    $template_key = $template['user_role']."_".$template['post_status']."_".$template['post_type'];
			    add_meta_box('post_template_' . $template_key, "When ". ucfirst($template['user_role'])." Makes " . ucfirst($template['post_type']) . " " . ucfirst($template['post_status']), 'acwpm_post_template_box', 'acwpm_template', 'normal', 'high', $template);
			    ?>
			<?php } ?>
			<?php do_meta_boxes('acwpm_template','normal',null); ?>
			</div>
		    <?php } ?>
		    <h3>New Post Action Template</h3>
		    <table class="form-table">
			<tr valign="top">
			    <th scope="row">User role</th>
			    <td>
				<select name="acwpm_options[new_post_template][user_role]">
				    <option value="anyone">Anyone</option>
				    <option value="loggedin">Logged in</option>
				    <?php wp_dropdown_roles(); ?>
				</select>
			    </td>
			</tr>
			<tr valign="top">
			    <th scope="row">Post status</th>
			    <td>
				<select name="acwpm_options[new_post_template][post_status]">
				    <?php foreach($post_statuses as $post_status) { ?>
					<option value="<?php echo $post_status; ?>"><?php echo ucfirst($post_status); ?></option>
				    <?php } ?>
				</select>
			    </td>
			</tr>
			<tr valign="top">
			    <th scope="row">Post type</th>
			    <td>
				<select name="acwpm_options[new_post_template][post_type]">
				    <?php foreach($post_types as $post_type) { ?>
					<option value="<?php echo $post_type; ?>"><?php echo ucfirst($post_type); ?></option>
				    <?php } ?>
				</select>
			    </td>
			</tr>
			<tr valign="top">
			    <th scope="row">Mail recipient</th>
			    <td>
				<select name="acwpm_options[new_post_template][mail_recipient]">
				    <option value="site_admin">Site Admin</option>
				    <option value="post_author">Post Author</option>
				    <option value="email">Specified E-mail</option>
				    <option value="meta_value">Specified Meta Key</option>
				</select><br />
				If applicable, enter additional argument:<br />
				<input type="text" name="acwpm_options[new_post_template][mail_recipient_arg]" />
			    </td>
			</tr>
			<tr valign="top">
			    <th scope="row">Mail Subject</th>
			    <td><input type="text" name="acwpm_options[new_post_template][subject]" value="" /></td>
			</tr>
			<tr valign="top">
			    <th scope="row">Mail Content</th>
			    <td><textarea rows="10" cols="50" name="acwpm_options[new_post_template][content]" class="large-text code"></textarea></td>
			</tr>
		    </table>
		    <p class="submit">
		    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		    </p>
		    <?php
			wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
			wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
		    ?>
	    </form>
    </div>
    <script type="text/javascript">
    jQuery(document).ready( function($) {
	    // close all postboxes
	    jQuery('.postbox').addClass('closed');
	    postboxes.add_postbox_toggles('acwpm_options');             
    });
    </script>
    <?php	
}

// Sanitize and validate input.
function acwpm_options_validate($input) {
        $invalid = array(); // invalid input keys 
        $must_validate = array(); // input keys that must validate
        $error_messages = array(); // error messages by input key
        $valid = array(); // validated values to return
        $old_options = get_option('acwpm_options');
    
	// Mail content type must be either text/html or text/plain
	$valid['mail_content_type'] = ( $input['mail_content_type'] == "text/html" ? "text/html" : "text/plain" );
        
        // Mail from name must be safe text with no HTML tags
	$valid['mail_from_name'] =  wp_filter_nohtml_kses($input['mail_from_name']);
	
	// Mail from must be a valid email address
	$valid['mail_from'] =  sanitize_email( $input['mail_from']);
        $must_validate[] = 'mail_from';
        $error_messages['mail_from'] = 'Invalid email address for the mail from address field.';
        
        $valid['post_templates'] = $old_options['post_templates'];
        if (isset($input['post_templates']) && is_array($input['post_templates'])) {
            foreach($input['post_templates'] as $i => $post_template) {
		if (!$input['post_templates'][$i]['delete']) {
		    $template_key = $post_template['user_role'] . "_" . $post_template['post_status'] . "_" . $post_template['post_type'];
		    if ($template_key != $i) {
			unset($valid['post_templates'][$i]);
		    }
		    foreach($post_template as $key => $val) {
			$valid['post_templates'][$template_key][$key] = $val;
		    }
		} else {
		    unset($valid['post_templates'][$i]);
		}
            }
        }
	
	// Validate keys
	foreach($valid['post_templates'] as $template_key => $post_template) {
	    $valid_key = $post_template['user_role'] . "_" . $post_template['post_status'] . "_" . $post_template['post_type'];
	    if ($template_key != $valid_key) {
		if (!isset($valid['post_templates'][$valid_key]) || empty($valid['post_templates'][$valid_key])) {
		    $valid['post_templates'][$valid_key] = $valid['post_templates'][$template_key];
		}
		unset($valid['post_templates'][$template_key]);
	    }
	}
        
        if (is_array($input['new_post_template'])
	    && !empty($input['new_post_template'])
	    && !empty($input['new_post_template']['user_role'])
	    && !empty($input['new_post_template']['post_status'])
	    && !empty($input['new_post_template']['post_type'])
	    && !empty($input['new_post_template']['mail_recipient'])
	    && !empty($input['new_post_template']['subject'])
	    && !empty($input['new_post_template']['content'])) {
	    
	    $new_template_key = $input['new_post_template']['user_role'] . "_" . $input['new_post_template']['post_status'] . "_" . $input['new_post_template']['post_type'];
	    
            foreach($input['new_post_template'] as $key => $val) {
                $valid['post_templates'][$new_template_key][$key] = $val;
            }
        }
        
	// Set error message for fields that must validate
        if (!empty($must_validate)) {
            foreach($must_validate as $key) {
                if( $valid[$key] != $input[$key] ) {
                        $invalid[] = $key;
                        if (!isset($error_messages[$key])) {
                            $error_messages[$key] = "One or more settings was invalid, please review your settings and try again.";
                        }
                        add_settings_error(
                                'acwpm_options_'.$key,      // setting title
                                'acwpm_options_texterror',  // error ID
                                $error_messages[$key],      // error message
                                'error'                     // type of message
                        );		
                }
            }
        }
        
        // Reset invalid values to previously saved value
        if (!empty($invalid)) {
            foreach($invalid as $key) {
                $valid[$key] = $old_options[$key];
            }
        }
	
	return $valid;
}

?>