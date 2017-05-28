<?php
$category_id = get_query_var('cat') ; 
if( tie_get_option( 'slider_cat_'.$category_id ) ):

	global $post;
	$orig_post = $post;
		
	$width = 660 ;
	$height = 330 ;
	
	$number = tie_get_option( 'slider_number' );
	$slider_query = tie_get_option( 'slider_cat_'.$category_id );
	
	if( tie_get_option('slider_type') == 'elastic' ){
				
		$effect = tie_get_option( 'elastic_slider_effect' );
		$autoplay = tie_get_option( 'elastic_slider_autoplay' );
		$speed = tie_get_option( 'elastic_slider_speed' );
		$interval = tie_get_option( 'elastic_slider_interval' );
		
		if( !$speed || $speed == ' ' || !is_numeric($speed))	$speed = 800 ;
		if( !$interval || $interval == ' ' || !is_numeric($interval))	$interval = 3000;
		
		if( $effect == 'sides' ) $effect = 'sides';
		else $effect = 'center';

		if( $autoplay ) $autoplay = 'true';
		else $autoplay = 'false'; ?>
		
		<script type="text/javascript">
            jQuery(function() {
                jQuery('#ei-slider').eislideshow({
					animation			: '<?php echo $effect ?>',
					autoplay			: <?php echo $autoplay ?>,
					slideshow_interval	: <?php echo $interval ?>,
					speed          		: <?php echo $speed ?>,
					titlesFactor		: 0.60,
					titlespeed          : 1000,
					thumbMaxWidth       : 20
                });
            });
        </script>
		
	<?php
	}else{
	
		
		$effect = tie_get_option( 'flexi_slider_effect' );
		$speed = tie_get_option( 'flexi_slider_speed' );
		$time = tie_get_option( 'flexi_slider_time' );
		
		if( !$speed || $speed == ' ' || !is_numeric($speed))	$speed = 7000 ;
		if( !$time || $time == ' ' || !is_numeric($time))	$time = 600;
		
		if( $effect == 'slideV' )
				$effect = 'animation: "slide",
						  direction: "vertical",';
		elseif( $effect == 'slideH' )
					$effect = 'animation: "slide",';
		else
			$effect = 'animation: "fade",';
		?>
		
		<script>
			jQuery(window).load(function() {
			  jQuery('.flexslider').flexslider({
				<?php echo $effect  ?>
				slideshowSpeed: <?php echo $speed ?>,
				animationSpeed: <?php echo $time ?>,
				randomize: false,
				pauseOnHover: true,
				start: function(slider) {
						var slide_control_width = 100/<?php echo $number; ?>;
						jQuery('.flex-control-nav li').css('width', slide_control_width+'%');
					}
			  });
			});
		</script>
	<?php
	}
	
	
	if( $slider_query == 'recent' || $slider_query == 'random' ){
	
		if( $slider_query == 'random' )
			$args= array('posts_per_page'=> $number , 'cat' => $category_id , 'orderby' => 'rand' );
		else
			$args= array('posts_per_page'=> $number , 'cat' => $category_id );

		$featured_query = new wp_query( $args );
	?>
	
<?php if( tie_get_option('slider_type') == 'elastic' ): ?>

	<?php if( $featured_query->have_posts() ) : ?>
	<div id="ei-slider" class="ei-slider">
		<ul class="ei-slider-large">
		<?php $i= 0;
			while ( $featured_query->have_posts() ) : $featured_query->the_post(); $i++; ?>
			<li>
			<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>
				<img src="<?php echo tie_thumb_src('', $width , $height ); ?>"  alt="thumb<?php echo $i; ?>" />
			<?php endif; ?>
				<div class="ei-title">
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<h3><?php echo tie_content_limit( get_the_excerpt() , 100 ) ?></h3>
				</div>
			</li>
		<?php endwhile;?>
		</ul>
		 <ul class="ei-slider-thumbs">
			<li class="ei-slider-element">Current</li>
		<?php $i= 0;
			while ( $featured_query->have_posts() ) : $featured_query->the_post(); $i++; ?>
			<li><a href="#">Slide <?php echo $i; ?></a><img src="<?php echo tie_thumb_src('',272,125); ?>" alt="thumb<?php echo $i; ?>" /></li>
    		<?php endwhile;?>
		</ul><!-- ei-slider-thumbs -->
	</div>
	<?php endif; ?>
	
	
<?php else: ?>
	
	
	<?php if( $featured_query->have_posts() ) : ?>
	<div class="flexslider">
		<ul class="slides">
		<?php while ( $featured_query->have_posts() ) : $featured_query->the_post()?>
			<li>
			<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>			
				<a href="<?php the_permalink(); ?>">
				<?php tie_thumb('', $width , $height ); ?>
				</a>
			<?php endif; ?>
				<div class="slider-caption">
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p><?php echo tie_content_limit( get_the_excerpt() , 100 ) ?></p>
				</div>
			</li>
		<?php endwhile;?>
		</ul>
	</div>
	<?php endif; ?>
	
<?php endif; ?>
	
	
	<?php
	
	
	}else{
		$custom_slider_args = array( 'post_type' => 'tie_slider', 'p' => $slider_query );
		$custom_slider = new WP_Query( $custom_slider_args );
	?>
	
<?php if( tie_get_option('slider_type') == 'elastic' ): ?>

	<div id="ei-slider" class="ei-slider">
		<ul class="ei-slider-large">
		<?php $i= 0;
		
			while ( $custom_slider->have_posts() ) : $custom_slider->the_post(); $i++; 
			$custom = get_post_custom($post->ID);
			$slider = unserialize( $custom["custom_slider"][0] );
			$number = count($slider);
				
			if( $slider ){
			foreach( $slider as $slide ): ?>	
			<li>
				<img src="<?php echo tie_slider_img_src( $slide['id'] , $width , $height ) ?>" alt="thumb<?php echo $i; ?>" />

				<?php if( !empty( $slide['title'] ) || !empty( $slide['caption'] ) ) :?>
				<div class="ei-title">
					<?php if( !empty( $slide['title'] ) ):?>
					<h2><?php if( !empty( $slide['link'] ) ):?><a href="<?php  echo stripslashes( $slide['link'] )  ?>"><?php endif; ?>
						<?php  echo stripslashes( $slide['title'] )  ?>
						<?php if( !empty( $slide['link'] ) ):?></a><?php endif; ?>
					</h2>
					<?php endif; ?>
					<?php if( !empty( $slide['caption'] ) ):?><h3><?php echo stripslashes($slide['caption']) ; ?></h3><?php endif; ?>
				</div>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
			
		</ul>
		
		 <ul class="ei-slider-thumbs">
			<li class="ei-slider-element">Current</li>
			<?php $i= 0; foreach( $slider as $slide ): $i++; ?>	
			<li><a href="#">Slide <?php echo $i; ?></a><img src="<?php echo tie_slider_img_src($slide['id'] ,272,125); ?>" alt="thumb<?php echo $i; ?>" /></li>
			<?php endforeach; ?>
			
		</ul><!-- ei-slider-thumbs -->
	
	<?php
		}?>
		<?php endwhile;?>
	</div>
	
	
<?php else: ?>
	
	
	<div class="flexslider">
		<ul class="slides">
		<?php while ( $custom_slider->have_posts() ) : $custom_slider->the_post();
			$custom = get_post_custom($post->ID);
			$slider = unserialize( $custom["custom_slider"][0] );
			$number = count($slider);
				
			if( $slider ){
			foreach( $slider as $slide ): ?>	
			<li>
				<?php if( !empty( $slide['link'] ) ):?><a href="<?php  echo stripslashes( $slide['link'] )  ?>"><?php endif; ?>
				<img src="<?php echo tie_slider_img_src( $slide['id'] , $width , $height ) ?>" alt="" />
				<?php if( !empty( $slide['link'] ) ):?></a><?php endif; ?>
				<?php if( !empty( $slide['title'] ) || !empty( $slide['caption'] ) ) :?>
				<div class="slider-caption">
					<?php if( !empty( $slide['title'] ) ):?><h2><?php if( !empty( $slide['link'] ) ):?><a href="<?php  echo stripslashes( $slide['link'] )  ?>"><?php endif; ?><?php  echo stripslashes( $slide['title'] )  ?><?php if( !empty( $slide['link'] ) ):?></a><?php endif; ?></h2><?php endif; ?>
					<?php if( !empty( $slide['caption'] ) ):?><p><?php echo stripslashes($slide['caption']) ; ?></p><?php endif; ?>
				</div>
				<?php endif; ?>
			</li>
			<?php endforeach; 
			}?>
		<?php endwhile;?>
		</ul>
	</div>
	
<?php endif; ?>

	<?php } ?>



	<?php
		$post = $orig_post;
		wp_reset_query();
	?>
<?php endif; ?>