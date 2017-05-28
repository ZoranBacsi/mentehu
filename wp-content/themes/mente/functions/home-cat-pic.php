<?php 
function get_home_news_pic( $cat_data ){ ?>

<?php
	$Cat_ID = $cat_data['id'];
	$Posts = 13;
	$Box_Title = $cat_data['title'];
	$Box_style = $cat_data['style'];
	if($Box_style == 'row') $Posts = 16;
	
	$cat_query = new WP_Query('cat='.$Cat_ID.'&posts_per_page='.$Posts); 
?>
		<section class="cat-box pic-box clear">
			<div class="cat-box-title">
				<h2><?php echo $Box_Title ; ?></h2>
				<div class="stripe-line"></div>
			</div><!-- post-thumbnail /-->
			<div class="cat-box-content">
			
				<?php if($cat_query->have_posts()): $count=0; ?>
				<ul>
				<?php while ( $cat_query->have_posts() ) : $cat_query->the_post(); $count ++ ;?>
				<?php if($count == 1 && $Box_style != 'row') : ?>
					<li class="first-pic">
						<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>			
							<div class="post-thumbnail">
								<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tie' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php tie_thumb('',290,195); ?>
								<h2><?php the_title(); ?></h2></a>
							</div><!-- post-thumbnail /-->
						<?php endif; ?>
					
					</li><!-- .first-pic -->
					<?php else: ?>
					<li>
						<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>			
							<div class="post-thumbnail">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="ttip"><?php tie_thumb('',55,55); ?></a>
							</div><!-- post-thumbnail /-->
						<?php endif; ?>			
					</li>
					<?php endif; ?>
				<?php endwhile;?>
				</ul>
				<div class="clear"></div>

					<?php endif; ?>
			</div><!-- .cat-box-content /-->
		</section>

	
<?php } ?>