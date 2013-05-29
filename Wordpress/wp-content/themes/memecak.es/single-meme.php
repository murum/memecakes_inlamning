<?php get_header(); ?>
	<h1>Test</h1>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php echo the_content(); ?>
		<?php endwhile; ?>
	<?php endif; ?>
<?php get_footer(); ?>