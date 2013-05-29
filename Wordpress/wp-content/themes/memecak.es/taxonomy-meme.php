<?php
$term = get_queried_object();

$sort = isset($_POST['sort-memecakes']) ? $_POST['sort-memecakes'] : null;
$sort_by = null;
$order_by = null;
$order = null;
$args = null;

switch($sort) {
	case 'atoz':
		$default_sort = 'atoz';
		$args = array(
			'post_type' 		=> 'memecake', 
			'meme' 				=> $term->slug, 
			'post_status' 		=> 'publish', 
			'posts_per_page'	=> -1,
			'order'				=> 'asc',
			'orderby'			=> 'title'
		);
		$memecakes = get_posts($args);
		break;
	case 'ztoa':
		$default_sort = 'ztoa';
		$args = array(
				'post_type' 		=> 'memecake', 
				'meme' 				=> $term->slug, 
				'post_status' 		=> 'publish', 
				'posts_per_page'	=> -1,
				'order'				=> 'desc',
				'orderby'			=> 'title'
			);
		$memecakes = get_posts($args);
		break;
	case 'highestrated':
		$default_sort = 'highestrated';
		$args = array(
				'post_type' 		=> 'memecake', 
				'meme' 				=> $term->slug, 
				'post_status' 		=> 'publish', 
				'posts_per_page'	=> -1,
				'r_sortby' 			=> 'highest_rated',
				'r_orderby'			=> 'asc',
			);
		$the_query = new WP_Query($args);
		$memecakes = $the_query->get_posts();
		break;
	case 'lowestrated':
		$default_sort = 'lowestrated';
		$args = array(
				'post_type' 		=> 'memecake', 
				'meme' 				=> $term->slug, 
				'post_status' 		=> 'publish', 
				'posts_per_page'	=> -1,
				'r_sortby' 			=> 'highest_rated',
				'r_orderby'			=> 'desc',
			);
		$the_query = new WP_Query($args);
		$memecakes = $the_query->get_posts();
		break;
	case 'latestupload':
		$default_sort = 'latestupload';
		$args = array(
				'post_type' 		=> 'memecake', 
				'meme' 				=> $term->slug, 
				'post_status' 		=> 'publish', 
				'posts_per_page'	=> -1,
				'order'				=> 'desc',
				'orderby'			=> 'post_date'
			);
		$memecakes = get_posts($args);
		break;
	default:
		$args = array(
				'post_type' 		=> 'memecake', 
				'meme' 				=> $term->slug, 
				'post_status' 		=> 'publish', 
				'posts_per_page'	=> -1,
				'order'				=> 'desc',
				'orderby'			=> 'post_date'
			);
		$memecakes = get_posts($args);
		break;
}

//Counter to identify if there should be a new row
$counter = 0;
$columnOne = array();
$columnTwo = array();
$columnThree = array();
foreach($memecakes as $memecake) {
	$counter++;
		$thumbnail = get_the_post_thumbnail($memecake->ID, 'memecake_feed');
		$author_url = get_the_author_meta('user_nicename', $memecake->post_author);
		$author_name = get_the_author_meta('display_name', $memecake->post_author);
		$memecake_link = get_the_title($memecake->ID);
		$memecake_link = get_permalink($memecake->ID);
		$rating = do_shortcode('[ratings id='.$memecake->ID.']');
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
	    </div>
		";

	// Add the memecakebox to the column it should be.
	$column = ($counter % 3);
	switch($column) { 
		case 1:
			$columnOne = AddMemecakeToColumn($columnOne, $meme);
			break;
		case 2:
			$columnTwo = AddMemecakeToColumn($columnTwo, $meme);
			break;
		case 0: 
			$columnThree = AddMemecakeToColumn($columnThree, $meme);
			break;
	}
}
?>


<?php get_header(); ?>
<?php if($default_sort == 'lowestrated' || $default_sort == 'highestrated') : ?>
	<h2>When you're sorting after rates you'll only see the rated cakes.</h2>
<?php endif; ?>
<?php if(count($memecakes) > 0) : ?>
	<div class="row-fluid">
		<form method="post" id="meme-memecakes-sort">
			<div class="span4 meme-memecakes-sort">
				<select name="sort-memecakes" id="sort-memecakes" form="meme-memecakes-sort">
					<option <?php echo ($default_sort == "latestupload") ? 'selected="selected"' : ""; ?> value="latestupload">Newest Upload</option>
					<option <?php echo ($default_sort == "atoz") ? 'selected="selected"' : ""; ?> value="atoz">A-Z</option>
					<option <?php echo ($default_sort == "ztoa") ? 'selected="selected"' : ""; ?> value="ztoa">Z-A</option>
					<option <?php echo ($default_sort == "highestrated") ? 'selected="selected"' : ""; ?> value="highestrated">Highest Rated</option>
					<option <?php echo ($default_sort == "lowestrated") ? 'selected="selected"' : ""; ?> value="lowestrated">Lowest Rated</option>
				</select>
				<button class="span4 hidden" id="sort-meme" type="submit" form="meme-memecakes-sort">Sort</button>
			</div>
		</form>
	</div>
	<div class="row-fluid">
		<div class="span4">
			<?php foreach($columnOne as $memecake) : ?>
				<?php echo $memecake; ?>
			<?php endforeach; ?>
		</div>
		<div class="span4">
			<?php foreach($columnTwo as $memecake) : ?>
				<?php echo $memecake; ?>
			<?php endforeach; ?>
		</div>
		<div class="span4">
			<?php foreach($columnThree as $memecake) : ?>
				<?php echo $memecake; ?>
			<?php endforeach; ?>
		</div>
	</div>
<?php else : ?>
	<div class="row-fluid">
		<h2 class="span12">There are no memecakes in this meme category</h2>
	</div>
<?php endif; ?>
<?php get_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/js/meme_sort_memecakes.js" type="text/javascript"></script>