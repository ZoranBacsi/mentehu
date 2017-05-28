<?php
add_action( 'widgets_init', 'Author_Bio_widget' );
function Author_Bio_widget() {
	register_widget( 'Author_Bio' );
}
class Author_Bio extends WP_Widget {

	function Author_Bio() {
		$widget_ops = array( 'classname' => 'Author-Bio' );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'author-bio-widget' );
		$this->WP_Widget( 'author-bio-widget',theme_name .' - Custom Author Bio', $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['Author_Bio_title'] );
		$img = $instance['img'];
		$text_code = $instance['text_code'];

		
		
			echo $before_widget;
			echo $before_title;
			echo $title ; 
			echo $after_title; ?>
			<div class="author-avatar">
				<img alt="" src="<?php echo $img; ?>">
			</div>
			<div class="author-description">
			<?php
			echo do_shortcode( $text_code ); ?>
			</div><div class="clear"></div>
			<?php
			echo $after_widget;
		
		
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['Author_Bio_title'] = strip_tags( $new_instance['Author_Bio_title'] );
		$instance['img'] = $new_instance['img'] ;
		$instance['text_code'] = $new_instance['text_code'] ;
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'Author_Bio_title' =>__( 'About Author' , 'tie') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'Author_Bio_title' ); ?>">Title : </label>
			<input id="<?php echo $this->get_field_id( 'Author_Bio_title' ); ?>" name="<?php echo $this->get_field_name( 'Author_Bio_title' ); ?>" value="<?php echo $instance['Author_Bio_title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img' ); ?>">Avatar : </label>
			<input id="<?php echo $this->get_field_id( 'img' ); ?>" name="<?php echo $this->get_field_name( 'img' ); ?>" value="<?php echo $instance['img']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'text_code' ); ?>">About : <i>You can use Shortcodes</i></label>
			<textarea rows="15" id="<?php echo $this->get_field_id( 'text_code' ); ?>" name="<?php echo $this->get_field_name( 'text_code' ); ?>" class="widefat" ><?php echo $instance['text_code']; ?></textarea>
		</p>
		


	<?php
	}
}
?>