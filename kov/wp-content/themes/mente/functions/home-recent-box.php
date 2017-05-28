<?php
function get_home_recent( $cat_data ){

	$exclude ='';
	if( !empty( $cat_data['exclude'] ) )
		$exclude = $cat_data['exclude'];
		
	$Posts = $cat_data['number'];
	$Box_Title = $cat_data['title'];
	$display = $cat_data['display'];
	
	$cat_query = new WP_Query(array ( 'posts_per_page' => $Posts , 'category__not_in' => $exclude)); 
?>
		<section class="cat-box recent-box">
			<div class="cat-box-title">
				<h2><?php echo $Box_Title ; ?></h2>
				<div class="stripe-line"></div>
			</div><!-- post-thumbnail /-->
			<div class="cat-box-content">
			
				<?php if($cat_query->have_posts()): ?>

				<?php while ( $cat_query->have_posts() ) : $cat_query->the_post()?>
				<?php if( $display == 'blog' ): ?>
					<article class="item-list">
						<h2 class="post-box-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tie' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
						<p class="post-meta">
							<?php tie_get_score(); ?>
							<?php tie_get_time() ?>
							<?php comments_popup_link( __( 'Leave a comment', 'tie' ), __( '1 Comment', 'tie' ), __( '% Comments', 'tie' ) ); ?>
						</p>
						
						<?php if( tie_get_option( 'blog_display' ) == 'content' ): ?>
						<div class="entry">
							<?php the_content( __( 'Read More &raquo;', 'tie' ) ); ?>
						</div>
						
						<?php else: ?>
						
							<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>			
						<div class="post-thumbnail">
							<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tie' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
								<?php $image_id = get_post_thumbnail_id($post->ID);  
						echo $image_url = wp_get_attachment_image($image_id, 'thumbnail' );   ?>
							</a>
						</div><!-- post-thumbnail /-->
							<?php endif; ?>
									
						<div class="entry">
							<p><?php tie_excerpt() ?>
							<a class="more-link" href="<?php the_permalink() ?>"><?php _e( 'Read More &raquo;', 'tie' ) ?></a></p>
						</div>
						<?php endif; ?>
						
						<?php tie_include( 'post-share' ); // Get Share Button template ?>	
					</article><!-- .item-list -->
				<?php else: ?>
					<div class="recent-item">
						<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>			
							<div class="post-thumbnail">
								<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tie' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
									<?php tie_thumb('', 272 ,125); ?>
									<span class="overlay-icon"></span>
								</a>
							</div><!-- post-thumbnail /-->
						<?php endif; ?>			
						<h3 class="post-box-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tie' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
						<p class="post-meta">
							<?php tie_get_score(); ?><?php tie_get_time() ?>
						</p>
					</div>
				<?php endif; ?>
				<?php endwhile;?>
				<div class="clear"></div>

			<?php endif; ?>
			</div><!-- .cat-box-content /-->
		</section>
		<div class="clear"></div>


<?php
}
?>