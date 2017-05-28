<?php
function tie_home_tabs(){
	$home_tabs_active = tie_get_option('home_tabs_box');
	$home_tabs = tie_get_option('home_tabs');
	$Posts = 5;
	if( $home_tabs_active && $home_tabs ): ?>
	<div id="cats-tabs-box" class="cat-box-content clear cat-box">
		<div class="cat-tabs-header">
			<ul>
		<?php 		
			foreach ($home_tabs as $cat ) { ?>
				<li><a href="#catab<?php echo $cat; ?>"><?php echo get_the_category_by_ID($cat) ?></a></li>
			<?php } ?>
			</ul>
		</div>
		<?php	
			foreach ($home_tabs as $cat ) {
			$count = 0;
			$cat_query = new WP_Query('cat='.$cat.'&posts_per_page='.$Posts); ?>
			<div id="catab<?php echo $cat; ?>" class="cat-tabs-wrap">

			<?php if($cat_query->have_posts()): ?>
				<ul>
				<?php while ( $cat_query->have_posts() ) : $cat_query->the_post(); $count ++ ;?>
				<?php if($count == 1) : ?>
					<li class="first-news">
						<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>			
							<div class="post-thumbnail">
								<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tie' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
									<?php tie_thumb('',272,125); ?>
									<span class="overlay-icon"></span>
								</a>
							</div><!-- post-thumbnail /-->
						<?php endif; ?>
					
						<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tie' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
						
						<p class="post-meta">
							<?php tie_get_score(); ?>
							<?php tie_get_time() ?>
							<?php comments_popup_link( __( 'Leave a comment', 'tie' ), __( '1 Comment', 'tie' ), __( '% Comments', 'tie' ) ); ?>
						</p>
						<div class="entry">
							<?php tie_excerpt_home() ?>
							<a class="more-link" href="<?php the_permalink() ?>"><?php _e( ' Bővebben &raquo;', 'tie' ) ?></a>
						</div>
					</li><!-- .first-news -->
					<?php else: ?>
					<li>
						<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>			
							<div class="post-thumbnail">
								<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tie' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php tie_thumb('',60,50); ?></a>
							</div><!-- post-thumbnail /-->
						<?php endif; ?>			
						<h3><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tie' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
						<p class="post-meta">
							<?php tie_get_time() ?>
							<?php comments_popup_link( __( 'Leave a comment', 'tie' ), __( '1 Comment', 'tie' ), __( '% Comments', 'tie' ) ); ?>
							<?php tie_get_score(); ?>
						</p>
					</li>
					<?php endif; ?>
				<?php endwhile;?>
				</ul>
				<div class="clear"></div>
			<?php endif; ?>
			</div>
			<?php } ?>
	</div><!-- #cats-tabs-box /-->
<?php endif;
}
?>