<?php get_header(); ?>
    <?php 

        // Get user informations.
        $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
        get_currentuserinfo();


        if($_POST['action'] == 'edit_profile_completed_action') {
            
            // Nonce and current user check
            $nonce = $_REQUEST['edit_profile_completed_nonce'];
            if ( !wp_verify_nonce( $nonce, 'edit_profile_completed_nonce') && !($current_user->ID == $curauth->ID)) 
                die('Security check');

            // Get all data 
            // Name
            $first_name = $_POST['profile-firstname'] != "" ? $_POST['profile-firstname'] : "";
            if(strlen($first_name) > 20) {
                $errors[] = "Your firstname shouldn't be longer than 20 charcters";
            }
            $last_name = $_POST['profile-lastname'] != "" ? $_POST['profile-lastname'] : "";
            if(strlen($last_name) > 30) {
                $errors[] = "Your lastname shouldn't be longer than 30 charcters";
            }

            // Contact information
            $email = $_POST['profile-email'] != "" ? $_POST['profile-email'] : $curauth->user_email;
            if(strlen($email) > 60) {
                $errors[] = "Your email shouldn't be longer than 60 charcters";
            }
            $hidden_email = $_POST['profile-email-hidden'] != "" ? $_POST['profile-email-hidden'] : "false";
            $website = $_POST['profile-website'] != "" ? $_POST['profile-website'] : "";
            if(strlen($website) > 60) {
                $errors[] = "Your website shouldn't be longer than 60 charcters";
            }
            $facebook = $_POST['profile-facebook'] != "" ? $_POST['profile-facebook'] : "";
            if(strlen($facebook) > 30) {
                $errors[] = "Your facebook shouldn't be longer than 30 charcters";
            }
            $twitter = $_POST['profile-twitter'] != "" ? $_POST['profile-twitter'] : "";
            if(strlen($twitter) > 30) {
                $errors[] = "Your twitter shouldn't be longer than 30 charcters";
            }
            $pinterest = $_POST['profile-pinterest'] != "" ? $_POST['profile-pinterest'] : "";
            if(strlen($pinterest) > 30) {
                $errors[] = "Your pinterest shouldn't be longer than 30 charcters";
            }

            // Password
            $password = $_POST['profile-password'] != "" ? $_POST['profile-password'] : "";
            $password_repeat = $_POST['profile-password-repeat'] != "" ? $_POST['profile-password-repeat'] : "";

            if($password !== $password_repeat) {
                $errors[] = "The new password doesn't match the repeated one";
            }

            // Description 
            $description = $_POST['profile-description'] != "" ? $_POST['profile-description'] : $curauth->description;

            // Password Check 
            $old_password = $_POST['profile-old-password'] != "" ? $_POST['profile-old-password'] : "";

            if( !wp_check_password($old_password, $curauth->data->user_pass, $curauth->ID) ) {
                $errors[] = "The password doesn't match your old one!";
            }

            if(sizeof($errors) == 0 ) {
                $userdata = array(
                    'ID' => $curauth->ID,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'user_email' => $email,
                    'user_url' => $website,
                    'facebook' => $facebook,
                    'twitter' => $twitter,
                    'pinterest' => $pinterest,
                    'description' => $description,
                    'hidden_email' => $hidden_email,
                );
                wp_update_user($userdata);
                if($password != "") {
                    wp_set_password( $password, $curauth->ID );
                    $success[] = "You can now login with your new password";
                }

                $success[] = "Your user profile was updated";
                $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
            }
        }

        $args = array(
            'post_type' => 'memecake',
            'author' => $curauth->ID
        );

        $memecakes_by_author = get_posts($args);
    ?>
    <?php if(sizeof($errors) > 0) : ?>
        <div class="row-fluid">
            <ul class="span4 memecake-edit-profile-errors">
                <?php foreach($errors as $error) : ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if(sizeof($success) > 0) : ?>
        <div class="row-fluid">
            <ul class="span4 memecake-edit-profile-success">
                <?php foreach($success as $success_message) : ?>
                    <li><?php echo $success_message; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <div class="row-fluid">
        <div class="span4 memecake-profile">
            <div class="row-fluid">
                <div class="span4 author-avatar-info">
                    <?php echo get_avatar( $curauth->user_email, 120 ); ?>
                    <a class="small" href="https://www.gravatar.com/">Avatar from Gravatar</a>
                </div>
                <div class="span8 memecake-profile-names">
                    <h4><?php echo $curauth->nickname; ?></h4>
                    <h5><?php echo $curauth->first_name; ?> <?php echo $curauth->last_name; ?></h5>
                </div> 
                <p class="span8">
                    <?php echo $curauth->description; ?>
                </p>
            </div>
            <div class="row-fluid">
                <dl class="span 12 memecake-detailed-information">
                        <?php if($curauth->hidden_email != 'true') : ?>
                            <dt class="icon-envelope-alt"></dt>
                            <dd><?php echo $curauth->data->user_email; ?></dd>
                        <?php endif; ?>
                        <?php if($curauth->facebook != "" ) : ?>
                            <dt class="icon-facebook-sign"></dt>
                            <dd><a href="<?php echo "http://facebook.com/$curauth->facebook"; ?>"><?php echo $curauth->facebook; ?></a></dd>
                        <?php endif; ?>
                        <?php if($curauth->twitter != "" ) : ?>
                            <dt class="icon-twitter"></dt>
                            <dd><a href="<?php echo "http://twitter.com/$curauth->twitter"; ?>"><?php echo $curauth->twitter; ?></a></dd>
                        <?php endif; ?>
                        <?php if($curauth->pinterest != "" ) : ?>
                            <dt class="icon-pinterest"></dt>
                            <dd><a href="<?php echo "http://pinterest.com/$curauth->pinterest"; ?>"><?php echo $curauth->pinterest; ?></a></dd>
                        <?php endif; ?>
                        <?php if($curauth->user_url != "" ) : ?>
                            <dt class="icon-home"></dt>
                            <dd><a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->data->user_url; ?></a></dd>
                        <?php endif; ?>
                </dl>
            </div>
        </div>
        <?php if($_POST['action'] == 'edit_profile_action' && $current_user->ID == $curauth->ID || $_POST['action'] == 'edit_profile_completed_action') : ?>
            <div class="span8 memecake-edit-profile">
                <form id="edit-profile-form" method="post">
                    <div class="row-fluid">                    
                        <h2 class="span12">Change your name.</h2>
                        <p class="span12 whisper no-margin-left">Pssst! It will not change in real life, only on memecak.es.</p>
                    </div>                    
                    <div class="row-fluid new-edit-profile-section">
                        <div class="span6">
                            <label class="span12" for="firstname">First name</label>
                            <input class="span12 no-margin-left" type="text" id="firstname" name="profile-firstname" value="<?php echo $curauth->first_name; ?>" />
                        </div>
                        <div class="span6">
                            <label class="span12" for="lastname">Last name</label>
                            <input class="span12 no-margin-left" type="text" id="lastname" name="profile-lastname" value="<?php echo $curauth->last_name; ?>" />
                        </div>
                    </div>
                    <div class="row-fluid">
                        <h2 class="span12">Change your information</h2>
                        <p class="span12 whisper no-margin-left">Stuff like E-mail, Twitter, Facebook, and of course your awesome presentation of yourself.</p>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">                        
                            <label class="span12" for="email">Email</label>
                            <input class="span12 no-margin-left" type="email" id="email" name="profile-email" value="<?php echo $curauth->user_email; ?>" />
                            <input class="span1 no-margin-left" type="checkbox" id="email-hidden" name="profile-email-hidden" <?php echo $curauth->hidden_email == "true" ? "checked" : "" ?> value="true" />
                            <label class="label-email-hidden span11" for="email-hidden">I don't want to display my Email.</label>                       
                        </div>
                        <div class="span6">
                            <label class="span12" for="description">Your message to the world</label>
                            <textarea class="span12 no-margin-left" name="profile-description" id="description" /><?php echo $curauth->description; ?></textarea>
                        </div>
                    </div>
                  
                    <div class="row-fluid">
                        <div class="span6">
                            <label class="span12" for="website">Website</label>
                            <input class="span12 no-margin-left" type="url" id="website" name="profile-website" value="<?php echo $curauth->user_url; ?>" />
                        </div>
                        <div class="span6">
                            <label class="span12" for="facebook">Facebook</label>
                            <input class="span12 no-margin-left" type="text" id="facebook" name="profile-facebook" value="<?php echo $curauth->facebook; ?>" />
                        </div>
                    </div>
                  
                    <div class="row-fluid new-edit-profile-section">
                        <div class="span6">
                            <label class="span12" for="twitter">Twitter</label>
                            <input class="span12 no-margin-left" type="text" id="twitter" name="profile-twitter" value="<?php echo $curauth->twitter; ?>" />
                        </div>                        
                        <div class="span6">                        
                            <label class="span12" for="pinterest">Pinterest</label>
                            <input class="span12 no-margin-left" type="text" id="pinterest" name="profile-pinterest" value="<?php echo $curauth->pinterest; ?>" />
                        </div>                    
                    </div>
                   


                    <div class="row-fluid">
                        <h2 class="span12">Change your password</h2>
                        <p class="span12 whisper no-margin-left">This is where you change your password.</p>
                    </div>
                    <div class="row-fluid new-edit-profile-section">
                        <div class="span6">
                            <label class="span12" for="password">New Password</label>
                            <input class="span12 no-margin-left" type="password" id="password" name="profile-password" />
                        </div>
                        <div class="span6">
                            <label class="span12" for="password_repeat">New Password again</label>
                            <input class="span12 no-margin-left" type="password" id="password_repeat" name="profile-password-repeat" />
                        </div>
                    </div>
                    <div class="row-fluid">
                        <h2 class="span12">Confirm any made changes</h2>
                        <p class="span12 whisper no-margin-left">Just type in your old password, if you've changed it, and press the big ass green confirm button.</p>
                    </div>                    
                    <div class="row-fluid new-edit-profile-section">
                        <div class="span12">                            
                            <label class="span12" for="old_password">Old Password</label>
                            <input class="span6 no-margin-left" type="password" name="profile-old-password" id="old_password" />
                            <input type="submit" id="edit-profile-submit" class="span6 edit-profile-submit" name="edit-profile-submit" value="Confirm" />
                            <input type="hidden" name="action" value="edit_profile_completed_action" />
                            <input type="hidden" name="edit_profile_completed_nonce" value="<?php echo wp_create_nonce("edit_profile_completed_nonce"); ?>" />
                                         
                    </div>
                </form>
            </div>
        <?php else : ?>
            <?php if(sizeof($memecakes_by_author) > 0 ) : ?>
                <div class="span8 memecake-uploaded-memecakes">
                    <div class="row-fluid">
                        <?php
                            $columnOne = array();
                            $columnTwo = array();
                            foreach($memecakes_by_author as $memecake) {
                                $thumbnail = get_the_post_thumbnail($memecake->ID, 'full');
                                $author_name = get_the_author_meta('nickname', $memecake->post_author);
                                $author_url = get_the_author_meta('user_nicename', $memecake->post_author);
                                $home_url = home_url();
                                $memecake_link = get_permalink($memecake->ID);
                                $rating = do_shortcode('[ratings id='.$memecake->ID.']');
                                $counter++;
                                $meme = "
                                <div class='memecake'>
                                   <a href='$memecake_link'>
                                        $thumbnail
                                        <div class='add-this-buttons'>
                                            <div class='memecake-sharebuttons'>
                                                <a class='sharebuttons' target='_blank' href='http://www.facebook.com/sharer.php?u=$memecake_link'>
                                                    <span class='icon-facebook icon-1x'></span>
                                                </a>
                                                <a class='sharebuttons' target='_blank' href='http://twitter.com/share?text=$memecake_title&amp;url=$memecake_link'>
                                                    <span class='icon-twitter icon-1x'></span>
                                                </a>
                                                <a class='sharebuttons' target='_blank' href='https://plus.google.com/share?url=$memecake_link'>
                                                    <span class='icon-google-plus'></span>
                                                </a>
                                            </div>
                                        </div>
                                    </a>
                                    <div class='row-fluid'>
                                        <div class='span7 memecake-author'>
                                            <a href='$home_url/author/$author_url'>$author_name</a>
                                        </div>
                                        <div class='span4 memecake-rating'>
                                            $rating
                                        </div>
                                    </div>
                                </div>";
                                $column = ($counter % 2);
                                switch($column) { 
                                    case 1:
                                        $columnOne = AddMemecakeToColumn($columnOne, $meme);
                                        break;
                                    case 0: 
                                        $columnTwo = AddMemecakeToColumn($columnTwo, $meme);
                                        break;
                                }
                            }
                        ?>
                        <div class="span6">
                            <?php foreach($columnOne as $memecake) : ?>
                                <?php echo $memecake; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="span6">
                            <?php foreach($columnTwo as $memecake) : ?>
                                <?php echo $memecake; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <?php if($curauth->ID == $current_user->ID) : ?>
                    <div class="span8 memecake-no-uploaded">
                        <div class="memecake-no-uploaded-blackbox">
                            <p class="ohno">OH NOES!</p>
                            <p class="memecake-no-uploaded-blackbox-text">You haven't uploaded any cakes.</p>
                        </div>
                        <a href="<?php echo home_url(); ?>/upload" class="icon-upload icon-8x memecake-no-uploaded-button">
                            <p class="memecake-no-uploaded-button-text">Upload now</p>
                        </a>
                    </div>
                <?php else : ?>
                 <div class="span6 memecake-no-uploaded-no-user-text">
                    <p>This user haven't uploaded any meme cakes. So we put this lolcat here instead.</p>
                </div>
                <div class="span2 memecake-no-uploaded-no-user-img">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/can_i_has_memecake.jpg" />
                </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php get_footer(); ?>