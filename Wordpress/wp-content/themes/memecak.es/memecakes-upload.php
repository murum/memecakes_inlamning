<?php
/*
	Template name: Memecake upload
*/
?>
<?php if(is_user_logged_in()) : ?>
<?php
	$errors = array();
	/*
	 * Take care of the Ajax Request happening when a user search in the select meme part.
	 */
	if(isset($_POST['meme']) && !isset($_POST['upload']) && !isset($_POST['memecake-upload-form-submit'])) {
		$memes = get_terms("meme", array('search' => $_POST['meme'], 'hide_empty' => false));
		echo '<div class="span6 memecake-upload-meme-memeimgs">';
		foreach($memes as $meme) {
			echo '<div meme="'.$meme->term_id.'" class="memecake-upload-meme-img memecake-meme">';
			echo s8_get_taxonomy_image($meme);
			echo '</div>';
		}
		echo '</div>';
		echo '<div class="span6 memecake-upload-memetext">';
		foreach ($memes as $meme) {
			echo '<a href="#" class="memecake-meme" meme="'.$meme->term_id.'">'.$meme->name.'</a>, ';
		}
		echo '</div>';
		die();
	} 
	/**
	 * If the meme was set and a upload error appeared
	 **/
	else if (isset($_POST['memes-meme'])) {
		$memecake_meme = $_POST['memes-meme'] != null ? $_POST['memes-meme'] : null;
	} 
	/**
	 * Takeing care of the image uploader ajax post.
	 **/
	else if(isset($_POST['upload'])) {
		
		if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
		$uploadedfile = $_FILES['memecake-img'];
		$uploadedfile['name'] = sanitize_file_name($uploadedfile['name']);
		$upload_overrides = array( 'test_form' => false );
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
		if ( $movefile ) {
			$filename = basename($uploadedfile['name']);
			$wp_filetype = wp_check_filetype(basename($uploadedfile['name']), null );
			$wp_upload_dir = wp_upload_dir();
			$attachment = array(
				'guid' => $wp_upload_dir['url'] . '/' . basename($movefile['url']),
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
				'post_status' => 'publish'
			);
			$attach_id = wp_insert_attachment( $attachment );

			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata( $attach_id, $movefile['file']);
			wp_update_attachment_metadata( $attach_id, $attach_data );
			update_post_meta( $attach_id,'_wp_attached_file', $movefile['file']);
			echo $attach_id;
			$_POST['upload'] = false;
			die();
		} else {
		    echo "Possible file upload attack!\n";
		    die();
		}
	} 
	/**
	 * If a the uploadbutton got clicked.
	 * A memecake is getting uploaded if no errors appears.
	 **/
	else if (isset($_POST['memecake-upload-form-submit'])) {
		$memecake_title = $_POST['memecake-title'] != null ? esc_html($_POST['memecake-title']) : null;
		$memecake_description = $_POST['memecake-description'] != null ? esc_html($_POST['memecake-description']) : null;
		$memecake_attachment_id = $_POST['memecake-attach-id'] != null ? esc_html($_POST['memecake-attach-id']) : null;
		$memecake_meme = $_POST['memecake-meme'] != null ? esc_html($_POST['memecake-meme']) : null;

		$errors = array();

		// Possible errors.
		if($memecake_title == "What would you like to call your memecake?") {
			$errors[] = "Please change the memecake description from the default one";
		}

		if(strlen($memecake_title) > 45) {
			$errors[] = "A memecake title isn't supposed to be 45characters+";
		}

		if($memecake_description == "Why and how did you bake this cake?") {
			$errors[] = "Please change the memecake description from the default one";
		}
		if(strlen($memecake_description) > 3500) {
			$errors[] = "A memecake description isn't supposed to be 3500characters+";
		}

		if($memecake_attachment_id == null) { 
			$errors[] = "A photo of your memecake is required, please drop a photo in the dropzone.";
		} else {
			$memecake_attachment_url = wp_get_attachment_url($memecake_attachment_id);
		}

		if($memecake_meme == null) { 
			$errors[] = "Please choose a related meme -->";
		}

		// Make a memecake post.
		if(sizeof($errors) == 0) {
			//  Creating the post
			$new_memecake_post = array(
				'post_title'    => $memecake_title,
				'post_status'   => 'pending',
				'post_type'     => 'memecake',
				'post_author'	=> $current_user->ID,
				'post_content' 	=> $memecake_description
			);
			$post_id = wp_insert_post($new_memecake_post);

			update_post_meta( $post_id,'_thumbnail_id',$memecake_attachment_id);

			// Adds the meme category to the memecake
			$meme_to_add = get_term((int)$memecake_meme, 'meme');
			if(term_exists($meme_to_add->name, 'meme')) {
				wp_set_post_terms( $post_id, $meme_to_add->term_id, 'meme', false);
			} else {
				$errors[] = "The meme you choosed doesn't exist";
			}

			wp_safe_redirect( get_permalink( $post_id ) );
		}
	}
?>
	<?php $memes = get_terms("meme", array('hide_empty' => false)); ?>
	<?php get_header(); ?>
		<div class="row-fluid">
			<div class="span4 memecake-upload-memecake">
				<h1 class="span12">Upload new cake</h1>
				<div class="memecake-upload-errors-container">
					<?php if(isset($errors) && sizeof($errors) > 0) : ?>
						<div class="memecake-upload-errors">
						<?php foreach ($errors as $error) : ?>
							<p><?php echo $error; ?></p>
						<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<form method="post" id="memecake-upload-form" name="memecake-upload-form" enctype="multipart/form-data">
					<div id="memecake-upload-dropzone">
						<div class="memecake-upload-dropzone-message message">
							Drag and drop the image here,
						</div>
						<div class="ajaxLoading">
							<i class="icon-spinner icon-4x icon-spin"></i>
							<p>Your image is being uploaded, while waiting feel free to complete the form.</p>
						</div>
						<div id="memecake-upload-img-preview">
							<?php if(isset($memecake_attachment_url)) : ?>
								<img class="obj" src="<?php echo $memecake_attachment_url; ?>">
							<?php endif; ?>
						</div>
						<div class="memecake-upload-browse-image-text">Or just</div>
						<div class="memecake-upload-browse-image-button">Browse your computer</div>
						<input type='file' class="memecake-upload-browse-image" name='memecake-image' id='memecake-image' />
					</div>
					<div id="memecake-upload-fields">
						<input type="text" 
							name="memecake-title" 
							id="memecake-title" 
							<?php if(isset($memecake_title) && $memecake_title != "") : ?>
								value="<?php echo $memecake_title; ?>"
							<?php else : ?>
								value="What would you like to call your memecake?"
							<?php endif; ?>
							/>
						<div class="memecake-title-counter">You've entered <span class="memecake-title-counter-chars">42</span> characters and max characters is 45.</div>
						<textarea name="memecake-description" id="memecake-description"><?php echo isset($memecake_description) ? $memecake_description : "Why and how did you bake this cake?" ?></textarea>
						<div class="memecake-description-counter">You've entered <span class="memecake-description-counter-chars">42</span> characters and max characters is 3500.</div>
						<?php if(isset($memecake_attachment_id)) : ?>
							<input type="hidden" name="memecake-attach-id" value="<?php echo $memecake_attachment_id; ?>">
						<?php endif; ?>
						<?php if(isset($memecake_meme)) : ?>
							<input type="hidden" name="memecake-meme" id="memecake-meme" value="<?php echo $memecake_meme; ?>">
						<?php endif; ?>
						<input type="submit" id="memecake-submit" name="memecake-upload-form-submit" value="Upload" />
					</div>
				</form>
			</div>
			<div class="span8 memecake-upload-memeimgs">
				<form method="get" name="memecake-upload-meme-search-form" id="memecake-upload-meme-search-form">
					<label for="meme">What meme is this cake based on?</label>
					<div class="memecake-upload-meme-form-inputs">
						<input type="text" id="meme" name="meme" value="<?php echo isset($_GET['meme']) ? $_GET['meme'] : null ; ?>" placeholder="e.g Troll guy or Nyan cat" />
						<i class="memecake-upload-search-meme-loader icon-spinner icon-spin"></i>
					</div>
				</form>
				<div class="row-fluid memecake-meme-not-choosed">
					<div class="span6 memecake-upload-meme-memeimgs">
						<?php foreach ($memes as $meme) : ?>
							<div meme="<?php echo $meme->term_id; ?>" class="memecake-upload-meme-img memecake-meme">
								<?php echo s8_get_taxonomy_image($meme); ?>
							</div>
						<?php endforeach; ?>
						<div class="clearfix"></div>
					</div>
					<div class="span6 memecake-upload-memetext">
						<?php foreach ($memes as $meme) : ?>
							<a href="#" class="memecake-meme" meme="<?php echo $meme->term_id; ?>"><?php echo $meme->name; ?></a>,
						<?php endforeach; ?>
					</div>
				</div>
				<div class="row-fluid memecake-meme-choosed hidden">
					<!-- Using javascript to add meme to this div -->
					<div class="span6 memecake-choosen-meme"></div>
					<div class="span6 memecake-upload-memetext">
						<div class="memecake-upload-submit-button">Upload</div>
					</div>
				</div>
			</div>
		</div>
	<?php get_footer(); ?>
<?php else : ?>
	<?php // If the user aint logged in; ?>
	<?php get_header(); ?>
		<?php include('templates/not-logged-in.php'); ?>
	<?php get_footer(); ?>
<?php endif; ?>
<script src="<?php echo get_template_directory_uri(); ?>/js/upload.js" type="text/javascript"></script>