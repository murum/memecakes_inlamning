<?php get_header(); ?>
	<div class="row">
		<div class="span8">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php echo the_content(); ?>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>