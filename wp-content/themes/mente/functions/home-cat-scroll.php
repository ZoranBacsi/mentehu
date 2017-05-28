<?php
function get_home_scroll( $cat_data ){ ?>
	
<?php
    wp_enqueue_script( 'tie-cycle' );

	$Cat_ID = $cat_data['id'];
	$Posts = $cat_data['number'];
	$Box_Title = $cat_data['title'];
	
	$cat_query = new WP_Query('cat='.$Cat_ID.'&posts_per_page='.$Posts); 
?>
		<section class="cat-box scroll-box">
			<div class="cat-box-title">
				<h2><?php echo $Box_Title ; ?></h2>
				<div class="stripe-line"></div>
			</div><!-- post-thumbnail /-->
			<div class="cat-box-content">
			
				<?php if($cat_query->have_posts()): ?>
				<div  id="slideshow<?php echo $Cat_ID ?>">
				<?php while ( $cat_query->have_posts() ) : $cat_query->the_post()?>
					<div class="scroll-item">
						<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>			
							<div class="post-thumbnail">
								<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tie' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
									<?php tie_thumb('',272,125); ?>
								</a>
							</div><!-- post-thumbnail /-->
						<?php endif; ?>			
						<h3><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'tie' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
						<p class="post-meta">
							<?php tie_get_time() ?>
							<?php tie_get_score(); ?>
						</p>
					</div>
				<?php endwhile;?>
				<div class="clear"></div>
				</div>
				<div id="nav<?php echo $Cat_ID ?>" class="scroll-nav"></div>


					<?php endif; ?>
			</div><!-- .cat-box-content /-->
		</section>
		<div class="clear"></div>


<script type="text/javascript">
	jQuery(document).ready(function() {
		var vids = jQuery("#slideshow<?php echo $Cat_ID ?> .scroll-item");
		for(var i = 0; i < vids.length; i+=4) {
		  vids.slice(i, i+4).wrapAll('<div class="group_items"></div>');
		}
		jQuery(function() {
			jQuery('#slideshow<?php echo $Cat_ID ?>').cycle({
				fx:     'scrollHorz',
				timeout: 3000,
				pager:  '#nav<?php echo $Cat_ID ?>',
				slideExpr: '.group_items',
				speed: 300,
				pause: true
			});
		});
  });
</script>
	
<?php } ?>