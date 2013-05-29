<?php get_header(); ?>
	<?php var_dump(get_currentuserinfo()); ?>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php echo the_content(); ?>
		<?php endwhile; ?>
	<?php endif; ?>
<?php get_footer(); ?>