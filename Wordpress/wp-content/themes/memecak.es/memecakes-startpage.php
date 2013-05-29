<?php
/*
	Template name: Startpage
*/
?>

<?php
	$memecakes = get_posts(array('post_type' => 'memecake', 'numberposts' => -1));
	//Counter to identify if there should be a new row
	$counter = 0;
	$columnOne = array();
	$columnTwo = array();
	$columnThree = array();
	foreach($memecakes as $memecake) {
		$counter++;
		$memecake_title = get_the_title($memecake->ID);
		$thumbnail = get_the_post_thumbnail($memecake->ID, 'memecake_feed');
		$author_name = get_the_author_meta('nickname', $memecake->post_author);
		$author_url = get_the_author_meta('user_nicename', $memecake->post_author);
		$home_url = home_url();
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
	<div class="row">
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
<?php get_footer(); ?>