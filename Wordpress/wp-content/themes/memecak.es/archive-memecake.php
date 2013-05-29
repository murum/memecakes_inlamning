<?php get_header(); ?>
<?php 
	// Define the sort order.
	$sort_by = isset($_POST['sort-meme']) ? htmlentities($_POST['sort-meme']) : null;
	$order = null;
	$orderby = null;
	$default_sort = null;
	switch($sort_by) {
		case "sort-meme-count":
			$order = "DESC";
			$orderby = "count";
			$default_sort = "count";
			break;
		case "sort-meme-atoz":
			$order = "ASC";
			$orderby = "name";
			$default_sort = "atoz";
			break;
		case "sort-meme-ztoa":
			$order = "DESC";
			$orderby = "name";
			$default_sort = "ztoa";
			break;
		default:
			$order = "DESC";
			$orderby = "count";
			$default_sort = "count";
			break;
	}

	// Check for the search term
	$search = isset($_POST['search-meme']) ? htmlentities($_POST['search-meme']): null;
?>
	<div class="row-fluid">
		<form method="post" id="meme-search">
			<div class="span4 meme-search-input">
				<input class="span12" type="text" id="search-meme" name="search-meme" placeholder="Find a specific meme" <?php echo isset($_POST['search-meme']) ? 'value="'.htmlentities($_POST['search-meme']).'"': null; ?>/>
				<div class="icon-search meme-search-icon"></div>
				<button class="span4 hidden" id="search-meme-submit" type="submit" form="meme-search">Search</button>
			</div>
			<div class="span4 sort-meme">
				<select name="sort-meme" id="sort-meme" form="meme-search">
					<option <?php echo ($default_sort == "count") ? 'selected="selected"' : ""; ?> value="sort-meme-count">Memecakes count</option>
					<option <?php echo ($default_sort == "atoz") ? 'selected="selected"' : ""; ?> value="sort-meme-atoz">A-Z</option>
					<option <?php echo ($default_sort == "ztoa") ? 'selected="selected"' : ""; ?> value="sort-meme-ztoa">Z-A</option>
					<span class="icon-chevron-down meme-sort-icon"></span>
				</select>
				<button class="span4 hidden" id="sort-meme-submit" type="submit" form="meme-search">Sort</button>
			</div>
		</form>
	</div>
	<?php // Get all memes (categories) ?>
	<?php $counter = 0; ?>
	<?php $memes = get_terms('meme', array('hide_empty' => false, 'orderby' => $orderby, 'order' => $order, 'search' => $search )); ?>
	<?php foreach($memes as $meme) : ?>
		<?php $memecake_counter = 0; ?>
		<?php $counter++; ?>
		<?php $newRow = $counter % 3 == 1 ? true : false; ?>
		<?php $closeRow = $counter % 3 == 0 ? true : false; ?>
		<?php if($newRow) : ?>
			<div class="row-fluid">
		<?php endif; ?>
				<div class="span4 meme-memecakes">
					<?php // Get all memescakes á category ?>
					<?php 
						$the_query = new WP_Query(
							array(
								'post_type' => 'memecake',
								'meme' => $meme->name,
							)
						);
					?>
					<?php $posts_count = $the_query->post_count; ?>
					<a href="<?php echo home_url(); ?>/memecake?meme=<?php echo $meme->slug; ?>"><h2><?php echo $meme->name; ?></h2></a>
					<?php while ($the_query->have_posts()) : ?>
						<?php $the_query->the_post(); ?>
						
						<?php $memecake_counter++; ?>

						<?php //No more memecakes should be visible ?>
						<?php if($memecake_counter == 5) : ?>
							<?php continue; ?>
						<?php endif; ?>
						
						<?php $new_memecake_row = $memecake_counter % 2 == 1 ? true : false; ?>
						<?php if($new_memecake_row) : ?>
							<div class="row-fluid">
						<?php endif; ?>
						
						<div class="span6 meme-memecake">
							<a href="<?php echo the_permalink(); ?>"><?php echo get_the_post_thumbnail(get_the_ID(), 'memecake_thumbnail'); ?></a>
						</div>
						
						<?php if(!$new_memecake_row) : ?>
							</div>
						<?php endif; ?>
					<?php endwhile; ?>
					<?php switch($posts_count) : 
						case 0: ?>
							<div class="row-fluid">
								<div class="span6 meme-memecake">
									<?php echo s8_get_taxonomy_image($meme, 'memecake_thumbnail'); ?>
								</div>
							
								<p class="span6 meme-text">No <?php echo $meme->name; ?> cakes here. You better get started right away.</p>
							</div>
							<form method="post" action="<?php echo home_url(); ?>/upload/" class="row-fluid">
								<input class="span12 meme-upload meme-buttons" type="submit" value="Upload the first" name="meme-upload" />
								<input type="hidden" value="<?php echo $meme->term_id; ?>" name="memes-meme" />
							</form>
						<?php break; ?>

						<?php case 1: ?>
							<!-- end of the odd meme cake row-fluid -->
								<p class="span6 meme-text">There's only one <?php echo $meme->name; ?> cake here. Be a contributor now!</p>
							</div>
							<form method="post" action="<?php echo home_url(); ?>/upload/" class="row-fluid">
								<input class="span12 meme-upload meme-buttons" type="submit" value="Upload the second" name="meme-upload" />
								<input type="hidden" value="<?php echo $meme->term_id; ?>" name="memes-meme" />
							</form>
						<?php break; ?>

						<?php case 2: ?>
							<div class="row-fluid">
								<p class="span12 meme-text meme-buttons">It seems there's only two <?php echo $meme->name; ?> cakes in the world. Bake the third!</p>
							</div>
							<form method="post" action="<?php echo home_url(); ?>/upload/" class="row-fluid">
								<input class="span12 meme-upload meme-buttons" type="submit" value="Upload the third" name="meme-upload" />
								<input type="hidden" value="<?php echo $meme->term_id; ?>" name="memes-meme" />
							</form>
						<?php break; ?>

						<?php case 3: ?>
								<p class="span6 meme-text">Only three? That's lame. Upload your <?php echo $meme->name; ?> cake and fill this space.</p>
							</div> <!-- end of the odd meme cake row-fluid -->
							<form method="post" action="<?php echo home_url(); ?>/upload/" class="row-fluid">
								<input class="span12 meme-upload meme-buttons" type="submit" value="Upload the fourth" name="meme-upload" />
								<input type="hidden" value="<?php echo $meme->term_id; ?>" name="memes-meme" />
							</form>
						<?php break; ?>

						<?php case 4: ?>
							<div class="row-fluid">
								<a href="<?php echo home_url() ?>/memecake/?meme=<?php echo $meme->slug ?>" class="span6 meme-button-small blackback">No more!</a>
								<form method="post" action="<?php echo home_url(); ?>/upload/" class="row-fluid">
									<input type="hidden" value="<?php echo $meme->term_id; ?>" name="memes-meme" />
									<input class="span6 meme-upload-small" type="submit" value="Upload new" name="meme-upload" />
								</form>
							</div>
						<?php break; ?>

						<?php default: ?>
							<div class="row-fluid">
								<a href="<?php echo home_url() ?>/memecake/?meme=<?php echo $meme->slug ?>" class="span6 meme-button-small blackback">Gimme more!</a>
								<form method="post" action="<?php echo home_url(); ?>/upload/" class="row-fluid">
									<input type="hidden" value="<?php echo $meme->term_id; ?>" name="memes-meme" />
									<input class="span6 meme-upload-small" type="submit" value="Upload new" name="meme-upload" />
								</form>
							</div>
						<?php break; ?>

					<?php endswitch; ?>
				</div>
		<?php if($closeRow) : ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php get_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/js/all_memes.js" type="text/javascript"></script>