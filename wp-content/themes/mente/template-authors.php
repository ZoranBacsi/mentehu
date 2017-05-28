<?php 
/*
Template Name: Authors List
*/
?>
<?php get_header(); ?>
	<div class="content">
		<?php tie_breadcrumbs() ?>
				
		<?php if ( ! have_posts() ) : ?>
			<div id="post-0" class="post not-found post-listing">
				<h1 class="post-title"><?php _e( 'Not Found', 'tie' ); ?></h1>
				<div class="entry">
					<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'tie' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			</div>
		<?php endif; ?>
		
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		
		<?php $get_meta = get_post_custom($post->ID);  ?>
		<?php //Above Post Banner
		if( empty( $get_meta["tie_hide_above"][0] ) ){
			if( !empty( $get_meta["tie_banner_above"][0] ) ) echo '<div class="ads-post">' .htmlspecialchars_decode($get_meta["tie_banner_above"][0]) .'</div>';
			else tie_banner('banner_above' , '<div class="ads-post">' , '</div>' );
		}
		?>
		
		<article class="post-listing post">
			<div class="post-inner">
				<h1 class="post-title"><?php the_title(); ?></h1>
				<p class="post-meta"></p>
				<div class="clear"></div>
				<div class="entry">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'tie' ), 'after' => '</div>' ) ); ?>

					<ul class="authors-wrap">				
					<?php
						$users = get_users('blog_id=1&orderby=post_count&order=DESC');
						foreach ($users as $user) {	?>
						<li>
							<div class="author-avatar">
								<?php echo get_avatar( get_the_author_meta( 'user_email' , $user->ID ), apply_filters( 'MFW_author_bio_avatar_size', 60 ) ); ?>
							</div><!-- #author-avatar -->
							<div class="author-description">
								<h3><a href="<?php echo get_author_posts_url( $user->ID ); ?>"><?php echo $user->display_name ?> </a></h3>
								<?php the_author_meta( 'description'  , $user->ID ); ?>
							</div><!-- #author-description -->

							<div class="author-social">
								<?php if ( get_the_author_meta( 'url' , $user->ID) ) : ?>
								<a class="tooltip" href="<?php the_author_meta( 'url' , $user->ID); ?>" title="<?php echo $user->display_name ?> <?php _e( " 's site", 'tie' ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/socialicons/site.png" alt="" /></a>
								<?php endif ?>	
								<?php if ( get_the_author_meta( 'twitter' , $user->ID) ) : ?>
								<a class="tooltip" href="http://twitter.com/<?php the_author_meta( 'twitter' , $user->ID ); ?>" title="<?php echo $user->display_name ?><?php _e( '  on Twitter', 'tie' ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/socialicons/twitter.png" alt="" /></a>
								<?php endif ?>	
								<?php if ( get_the_author_meta( 'facebook' , $user->ID) ) : ?>
								<a class="tooltip" href="<?php the_author_meta( 'facebook' , $user->ID ); ?>" title="<?php echo $user->display_name ?><?php _e( '  on Facebook', 'tie' ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/socialicons/facebook.png" alt="" /></a>
								<?php endif ?>
								<?php if ( get_the_author_meta( 'google' , $user->ID) ) : ?>
								<a class="tooltip" href="<?php the_author_meta( 'google' , $user->ID ); ?>" title="<?php echo $user->display_name ?><?php _e( '  on Google+', 'tie' ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/socialicons/google_plus.png" alt="" /></a>
								<?php endif ?>	
								<?php if ( get_the_author_meta( 'linkedin' , $user->ID) ) : ?>
								<a class="tooltip" href="<?php the_author_meta( 'linkedin' , $user->ID); ?>" title="<?php echo $user->display_name ?><?php _e( '  on Linkedin', 'tie' ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/socialicons/linkedin.png" alt="" /></a>
								<?php endif ?>				
								<?php if ( get_the_author_meta( 'flickr' , $user->ID) ) : ?>
								<a class="tooltip" href="<?php the_author_meta( 'flickr' , $user->ID); ?>" title="<?php echo $user->display_name ?><?php _e( '  on Flickr', 'tie' ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/socialicons/flickr.png" alt="" /></a>
								<?php endif ?>	
								<?php if ( get_the_author_meta( 'youtube' , $user->ID) ) : ?>
								<a class="tooltip" href="<?php the_author_meta( 'youtube' , $user->ID); ?>" title="<?php echo $user->display_name ?><?php _e( '  on YouTube', 'tie' ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/socialicons/youtube.png" alt="" /></a>
								<?php endif ?>
								<?php if ( get_the_author_meta( 'pinterest' , $user->ID) ) : ?>
								<a class="tooltip" href="<?php the_author_meta( 'pinterest' , $user->ID); ?>" title="<?php echo $user->display_name ?><?php _e( '  on Pinterest', 'tie' ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/socialicons/pinterest.png" alt="" /></a>
								<?php endif ?>	
							</div>

							<div class="clear"></div>
						</li>
					<?php } ?>
					</ul>

					<?php edit_post_link( __( 'Edit', 'tie' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .entry /-->	
			
			</div><!-- .post-inner -->
		</article><!-- .post-listing -->
		<?php endwhile; ?>
		
		<?php //Below Post Banner
		if( empty( $get_meta["tie_hide_below"][0] ) ){
			if( !empty( $get_meta["tie_banner_below"][0] ) ) echo '<div class="ads-post">' .htmlspecialchars_decode($get_meta["tie_banner_below"][0]) .'</div>';
			else tie_banner('banner_below' , '<div class="ads-post">' , '</div>' );
		}
		?>
		
		<?php comments_template( '', true ); ?>
	</div><!-- .content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>