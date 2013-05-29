<?php get_header(); ?>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php if($post->post_status == "pending") : ?>
				<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<p>Your image is <strong>pending</strong> right now and not visible for people without this link.</p>
				</div>
			<?php endif; ?>
			<div class="row">
				<div class="span8 memecake-single">
					<div id="memecake-image" class="memecake-image">
					<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'memecake' ); } ?>
					</div>
					<div class="white-container memecake-comments">
						<?php disqus_embed('memecakes'); ?>
					</div>
				</div>
				<div class="span4 memecake-single-info">
					<div class="white-container memecake-info">
						
						<div class="row-fluid">	
							<div class="span12 less-bottom-margin">
								<p class="username-single-memecake"><span class="icon-user memecake-user-icon"></span><?php the_author_posts_link(); ?></p>
							</div>
						</div>
						<div class="row-fluid">					
							<div class="span12 memecake-rating">
								<?php echo do_shortcode('[ratings id='.$post->ID.']'); ?>
							</div>
														
						</div>
						
						<div class="row-fluid">
							<h2 class="span12">
								<?php echo the_title(); ?>
							</h2>
						</div>

						<div class="row-fluid">
							<?php the_content(); ?>	
						</div>
						
						<div class="row-fluid memecake-share">
							<p class="margins">Everybody love cakes.</p>
							<a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>">
								<span class="icon-facebook-sign icon-2x green"></span>
							</a>
							<a target="_blank" href="http://twitter.com/share?text=<?php the_title();?>&amp;url=<?php the_permalink();?>">
								<span class="icon-twitter-sign icon-2x green"></span>
							</a>
							<a target="_blank" href="https://plus.google.com/share?url=<?php the_permalink();?>">
								<span class="icon-google-plus-sign icon-2x green"></span>
							</a>
						</div>

						<div class="row-fluid memecake-navigation">
							<span class="span6"><?php echo previous_post_link('%link', '<span class="icon-caret-left"></span> Previous'); ?></span>
							<span class="span6 alignright"><?php echo next_post_link('%link', 'Next <span class="icon-caret-right"></span>'); ?></span>
						</div>
					</div>
					<div class="white-container memecake-ads">
						<script type="text/javascript">
							google_ad_client = "ca-pub-8062133755862850";
							/* Memecakes-sidebar */
							google_ad_slot = "2809717685";
							google_ad_width = 300;
							google_ad_height = 250;
						</script>
						<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
					</div>
				</div>				
			</div>
		<?php endwhile; ?>
	<?php endif; ?>
<?php get_footer(); ?>