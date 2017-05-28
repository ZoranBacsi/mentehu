<?php
## widget_tabs
add_action( 'widgets_init', 'widget_tabs_box' );
function widget_tabs_box(){
	register_widget( 'widget_tabs' );
}
class widget_tabs extends WP_Widget {
	function widget_tabs() {
		$widget_ops = array( 'description' => 'Most Popular, Recent, Comments, Tags'  );
		$this->WP_Widget( 'widget_tabs',theme_name .'- Tabbed  ', $widget_ops );
	}
	function widget( $args, $instance ) {
		?>
	<div class="widget" id="tabbed-widget">
		<div class="widget-container">
			<div class="widget-top">
				<ul class="tabs posts-taps">
					<li class="tabs"><a href="#tab1"><?php _e( 'Popular' , 'tie' ) ?></a></li>
					<li class="tabs"><a href="#tab2"><?php _e( 'Recent' , 'tie' ) ?></a></li>
					<li class="tabs"><a href="#tab3"><?php _e( 'Comments' , 'tie' ) ?></a></li>
					<li class="tabs" style="margin-left:0"><a href="#tab4"><?php _e( 'Tags' , 'tie' ) ?></a></li>
				</ul>
			</div>
			<div id="tab1" class="tabs-wrap">
				<ul>
					<?php wp_popular_posts() ?>	
				</ul>
			</div>
			<div id="tab2" class="tabs-wrap">
				<ul>
					<?php wp_last_posts()?>	
				</ul>
			</div>
			<div id="tab3" class="tabs-wrap">
				<ul>
					<?php most_commented();?>
				</ul>
			</div>
			<div id="tab4" class="tabs-wrap tagcloud">
				<?php wp_tag_cloud( $args = array('largest' => 8,'number' => 25,'orderby'=> 'count', 'order' => 'DESC' )); ?>
			</div>
		</div>
	</div><!-- .widget /-->
<?php
	}
}
?>
