<?php
add_action("admin_init", "posts_init");
function posts_init(){
	add_meta_box("post_options", theme_name ." - Post Options", "post_options", "post", "normal", "high");
	add_meta_box("page_options", theme_name ." - Page Options", "page_options", "page", "normal", "high");
}

function post_options(){
	global $post ;
	$get_meta = get_post_custom($post->ID);
	$tie_sidebar_pos = $get_meta["tie_sidebar_pos"][0];
	$tie_review_criteria = unserialize($get_meta["tie_review_criteria"][0]);
	wp_enqueue_script( 'tie-admin-slider' );  

?>
		<div class="tiepanel-item">
			<h3>Review Post Options</h3>
			<?php	

			tie_post_options(				
				array(	"name" => "Review Box Position",
						"id" => "tie_review_position",
						"type" => "select",
						"options" => array( "" => "Disable" ,
											"top" => "Top of the post" ,
											"bottom" => "Bottom of the post",
											"both" => "Top and Bottom of the post",
											"custom" => "Custom position")));
			?>
			<div id="reviews-options">
			<?php
			tie_post_options(				
				array(	"name" => "Review Style",
						"id" => "tie_review_style",
						"type" => "select",
						"options" => array( "stars" => "Stars" ,
											"percentage" => "Percentage",
											"points" => "Points")));
											
			tie_post_options(				
				array(	"name" => "Review Summary",
						"id" => "tie_review_summary",
						"type" => "textarea"));

			tie_post_options(				
				array(	"name" => "Text appears under the total score",
						"id" => "tie_review_total",
						"type" => "text"));

			?>
			<?php  for($i=1 ; $i<=10 ; $i++ ){ ?>

			<div class="option-item review-item">				
				<span class="label">Review Criteria <?php echo $i ?></span>
				<input name="tie_review_criteria[<?php echo $i ?>][name]" type="text" value="<?php echo $tie_review_criteria[$i]['name'] ?>" />
				<div class="clear"></div>
				<span class="label">Criteria Score <?php echo $i ?></span>
				<div id="criteria<?php echo $i ?>-slider"></div>
				<input type="text" id="criteria<?php echo $i ?>" value="<?php if( $tie_review_criteria[$i]['score'] ) echo $tie_review_criteria[$i]['score']; else echo 0; ?>" name="tie_review_criteria[<?php echo $i ?>][score]" style="width:50px; opacity: 0.7;" />
				<script>
				  jQuery(document).ready(function() {
					jQuery("#criteria<?php echo $i ?>-slider").slider({
						range: "min",
						min: 0,
						max: 100,
						value: <?php if( $tie_review_criteria[$i]['score'] ) echo $tie_review_criteria[$i]['score']; else echo 0; ?>,
						slide: function(event, ui) {
							jQuery('#criteria<?php echo $i ?>').attr('value', ui.value );
						}
						});
					});
				</script>
			</div>	

				<?php
			}
			?>
			</div>
		</div>
		
		<div class="tiepanel-item">
			<h3>General Options</h3>
			<?php	

			tie_post_options(				
				array(	"name" => "Hide Post Meta",
						"id" => "tie_hide_meta",
						"type" => "select",
						"options" => array( "" => "" ,
											"yes" => "Yes" ,
											"no" => "No")));

			tie_post_options(				
				array(	"name" => "Hide Author Information",
						"id" => "tie_hide_author",
						"type" => "select",
						"options" => array( "" => "" ,
											"yes" => "Yes" ,
											"no" => "No")));
											
			tie_post_options(				
				array(	"name" => "Hide Share Buttons",
						"id" => "tie_hide_share",
						"type" => "select",
						"options" => array( "" => "" ,
											"yes" => "Yes" ,
											"no" => "No")));
											
			tie_post_options(				
				array(	"name" => "Hide Related Posts",
						"id" => "tie_hide_related",
						"type" => "select",
						"options" => array( "" => "" ,
											"yes" => "Yes" ,
											"no" => "No")));
			?>
		</div>
		
		<div class="tiepanel-item">
			<h3>Sidebar Options</h3>
			<div class="option-item">
				<?php
					$checked = 'checked="checked"';
				?>
				<ul id="sidebar-position-options" class="tie-options">
					<li>
						<input id="tie_sidebar_pos"  name="tie_sidebar_pos" type="radio" value="default" <?php if($tie_sidebar_pos == 'default' || !$tie_sidebar_pos ) echo $checked; ?> />
						<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/sidebar-default.png" /></a>
					</li>						<li>
						<input id="tie_sidebar_pos"  name="tie_sidebar_pos" type="radio" value="right" <?php if($tie_sidebar_pos == 'right' ) echo $checked; ?> />
						<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/sidebar-right.png" /></a>
					</li>
					<li>
						<input id="tie_sidebar_pos"  name="tie_sidebar_pos" type="radio" value="left" <?php if($tie_sidebar_pos == 'left') echo $checked; ?> />
						<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/sidebar-left.png" /></a>
					</li>
					<li>
						<input id="tie_sidebar_pos"  name="tie_sidebar_pos" type="radio" value="full" <?php if($tie_sidebar_pos == 'full') echo $checked; ?> />
						<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/sidebar-no.png" /></a>
					</li>
				</ul>
			</div>
			<?php
			$sidebars = tie_get_option( 'sidebars' ) ;
			$new_sidebars = array(''=> 'Default');
			if($sidebars){
				foreach ($sidebars as $sidebar) {
					$new_sidebars[$sidebar] = $sidebar;
				}
			}
					
			tie_post_options(				
				array(	"name" => "Choose Sidebar",
						"id" => "tie_sidebar_post",
						"type" => "select",
						"options" => $new_sidebars ));
			?>
		</div>
		
		
		<div class="tiepanel-item">
			<h3>Post Head Options</h3>
			<?php	

			tie_post_options(				
				array(	"name" => "Display",
						"id" => "tie_post_head",
						"type" => "select",
						"options" => array(
							''=> 'Default',
							'none'=> 'None',
							'video'=> 'Video',
							'audio'=> 'Audio - Self Hosted',
							'soundcloud'=> 'Audio - SoundCloud',
							'slider'=> 'Slider',
							'map'=> 'Google Map',
							'thumb'=> 'Featured Image',
							'lightbox'=> 'Featured Image + lightbox'
						)));


			tie_post_options(				
				array(	"name" => "Embed Code",
						"id" => "tie_embed_code",
						"type" => "textarea"));

			tie_post_options(				
				array(	"name" => "Youtube / Vimeo / Dailymotion Video Url",
						"id" => "tie_video_url",
						"type" => "text"));

			tie_post_options(				
				array(	"name" => "SoundCloud URL",
						"id" => "tie_audio_soundcloud",
						"type" => "text"));
						
			tie_post_options(				
				array(	"name" => "Auto Play",
						"id" => "tie_audio_soundcloud_play",
						"type" => "checkbox"));
						
			tie_post_options(				
				array(	"name" => "Mp3 file Url <strong><small>(required)</small></strong>",
						"id" => "tie_audio_mp3",
						"type" => "text"));

			tie_post_options(				
				array(	"name" => "M4A file Url",
						"id" => "tie_audio_m4a",
						"type" => "text"));
						
			tie_post_options(				
				array(	"name" => "OGA file Url <strong><small>(required)</small></strong>",
						"id" => "tie_audio_oga",
						"type" => "text"));	
						
			global $post;
			$orig_post = $post;
			
			$sliders = array();
			$custom_slider = new WP_Query( array( 'post_type' => 'tie_slider', 'posts_per_page' => -1 ) );
			while ( $custom_slider->have_posts() ) {
				$custom_slider->the_post();
				$sliders[get_the_ID()] = get_the_title();
			}
			$post = $orig_post;
			wp_reset_query();
	
			tie_post_options(				
				array(	"name" => "Custom Slider",
						"id" => "tie_post_slider",
						"type" => "select",
						"options" => $sliders ));

			tie_post_options(				
				array(	"name" => "Google Map Url",
						"id" => "tie_googlemap_url",
						"type" => "text"));

			?>
		</div>

		<div class="tiepanel-item">
			<h3>Banners Options</h3>
			<?php	
			tie_post_options(				
				array(	"name" => "Hide Above Banner",
						"id" => "tie_hide_above",
						"type" => "checkbox"));

			tie_post_options(				
				array(	"name" => "Custom Above Banner",
						"id" => "tie_banner_above",
						"type" => "textarea"));

			tie_post_options(				
				array(	"name" => "Hide Below Banner",
						"id" => "tie_hide_below",
						"type" => "checkbox"));

			tie_post_options(				
				array(	"name" => "Custom Below Banner",
						"id" => "tie_banner_below",
						"type" => "textarea"));
			?>
		</div>
  <?php
}

/*********************************************************************************************/

function page_options(){
	global $post ;
	$get_meta = get_post_custom($post->ID);
	$tie_sidebar_pos = $get_meta["tie_sidebar_pos"][0];
	
	$categories_obj = get_categories();
	$categories = array();
	$categories = array(''=> 'All Categories');
	foreach ($categories_obj as $pn_cat) {
		$categories[$pn_cat->cat_ID] = $pn_cat->cat_name;
	}
	
?>
		
				
		<div class="tiepanel-item">
			<h3>Sidebar Options</h3>
			<div class="option-item">
				<?php
					$checked = 'checked="checked"';
				?>
				<ul id="sidebar-position-options" class="tie-options">
					<li>
						<input id="tie_sidebar_pos"  name="tie_sidebar_pos" type="radio" value="default" <?php if($tie_sidebar_pos == 'default' || !$tie_sidebar_pos ) echo $checked; ?> />
						<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/sidebar-default.png" /></a>
					</li>						<li>
						<input id="tie_sidebar_pos"  name="tie_sidebar_pos" type="radio" value="right" <?php if($tie_sidebar_pos == 'right' ) echo $checked; ?> />
						<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/sidebar-right.png" /></a>
					</li>
					<li>
						<input id="tie_sidebar_pos"  name="tie_sidebar_pos" type="radio" value="left" <?php if($tie_sidebar_pos == 'left') echo $checked; ?> />
						<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/sidebar-left.png" /></a>
					</li>
					<li>
						<input id="tie_sidebar_pos"  name="tie_sidebar_pos" type="radio" value="full" <?php if($tie_sidebar_pos == 'full') echo $checked; ?> />
						<a class="checkbox-select" href="#"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/sidebar-no.png" /></a>
					</li>
				</ul>
			</div>
			<?php
			$sidebars = tie_get_option( 'sidebars' ) ;
			$new_sidebars = array(''=> 'Default');
			if($sidebars){
				foreach ($sidebars as $sidebar) {
					$new_sidebars[$sidebar] = $sidebar;
				}
			}
					
			tie_post_options(				
				array(	"name" => "Choose Sidebar",
						"id" => "tie_sidebar_post",
						"type" => "select",
						"options" => $new_sidebars ));
			?>
		</div>
		
		<div class="tiepanel-item">
			<h3>Post Head Options</h3>
			<?php	

			tie_post_options(				
				array(	"name" => "Display",
						"id" => "tie_post_head",
						"type" => "select",
						"options" => array(
							''=> 'Default',
							'none'=> 'None',
							'video'=> 'Video',
							'audio'=> 'Audio - Self Hosted',
							'soundcloud'=> 'Audio - SoundCloud',
							'slider'=> 'Slider',
							'map'=> 'Google Map',
							'thumb'=> 'Featured Image',
							'lightbox'=> 'Featured Image + lightbox'
						)));


			tie_post_options(				
				array(	"name" => "Embed Code",
						"id" => "tie_embed_code",
						"type" => "textarea"));

			tie_post_options(				
				array(	"name" => "Youtube / Vimeo Video Url",
						"id" => "tie_video_url",
						"type" => "text"));
						
			tie_post_options(				
				array(	"name" => "SoundCloud URL",
						"id" => "tie_audio_soundcloud",
						"type" => "text"));
						
			tie_post_options(				
				array(	"name" => "Auto Play",
						"id" => "tie_audio_soundcloud_play",
						"type" => "checkbox"));
						
			tie_post_options(				
				array(	"name" => "Mp3 file Url <strong><small>(required)</small></strong>",
						"id" => "tie_audio_mp3",
						"type" => "text"));

			tie_post_options(				
				array(	"name" => "M4A file Url",
						"id" => "tie_audio_m4a",
						"type" => "text"));
					
			tie_post_options(				
				array(	"name" => "OGA file Url <strong><small>(required)</small></strong>",
						"id" => "tie_audio_oga",
						"type" => "text"));			

						
			global $post;
			$orig_post = $post;
			
			$sliders = array();
			$custom_slider = new WP_Query( array( 'post_type' => 'tie_slider', 'posts_per_page' => -1 ) );
			while ( $custom_slider->have_posts() ) {
				$custom_slider->the_post();
				$sliders[get_the_ID()] = get_the_title();
			}
			$post = $orig_post;
			wp_reset_query();
	
			tie_post_options(				
				array(	"name" => "Custom Slider",
						"id" => "tie_post_slider",
						"type" => "select",
						"options" => $sliders ));
						
						
			tie_post_options(				
				array(	"name" => "Google Map Url",
						"id" => "tie_googlemap_url",
						"type" => "text"));
			?>
		</div>
		
		<div class="tiepanel-item">
			<h3>Banners Options</h3>
			<?php	
			tie_post_options(				
				array(	"name" => "Hide Above Banner",
						"id" => "tie_hide_above",
						"type" => "checkbox"));

			tie_post_options(				
				array(	"name" => "Custom Above Banner",
						"id" => "tie_banner_above",
						"type" => "textarea"));

			tie_post_options(				
				array(	"name" => "Hide Below Banner",
						"id" => "tie_hide_below",
						"type" => "checkbox"));

			tie_post_options(				
				array(	"name" => "Custom Below Banner",
						"id" => "tie_banner_below",
						"type" => "textarea"));
			?>
		</div>

		
		<div class="tiepanel-item">
			<h3>Display Feed template Options</h3>
			<?php	
			tie_post_options(				
				array(	"name" => "URI of the RSS feed",
						"id" => "tie_rss_feed",
						"type" => "text"));
			?>
		</div>

		<div class="tiepanel-item">
			<h3>Blog List template Options</h3>
			<?php	
			tie_post_options(				
				array(	"name" => "Categories",
						"id" => "tie_blog_cats",
						"type" => "select",
						"options" => $categories ));
			?>
		</div>
  <?php
}

add_action('save_post', 'save_post');
function save_post(){
	global $post;
	
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return $post_id;

 	$custom_meta_fields = array(
		'tie_rss_feed',
		'tie_blog_cats',
		'tie_hide_meta',
		'tie_hide_author',
		'tie_hide_share',
		'tie_hide_related',
		'tie_sidebar_pos',
		'tie_sidebar_post',
		'tie_post_head',
		'tie_post_slider',
		'tie_googlemap_url',
		'tie_video_url',
		'tie_embed_code',
		'tie_audio_m4a',
		'tie_audio_mp3',
		'tie_audio_oga',
		'tie_audio_soundcloud',
		'tie_audio_soundcloud_play',
		'tie_hide_above',
		'tie_banner_above',
		'tie_hide_below',
		'tie_banner_below',
		'tie_review_position',
		'tie_review_style',
		'tie_review_summary',
		'tie_review_total');
		
	foreach( $custom_meta_fields as $custom_meta_field ){
		if(isset($_POST[$custom_meta_field]) )
			update_post_meta($post->ID, $custom_meta_field, htmlspecialchars(stripslashes($_POST[$custom_meta_field])) );
		else
			delete_post_meta($post->ID, $custom_meta_field);
	}
	
	update_post_meta($post->ID, 'tie_review_criteria', $_POST['tie_review_criteria']);
	tie_update_score();
}




/*********************************************************/

function tie_post_options($value){
	global $post;
?>

	<div class="option-item" id="<?php echo $value['id'] ?>-item">
		<span class="label"><?php  echo $value['name']; ?></span>
	<?php
		$id = $value['id'];
		$get_meta = get_post_custom($post->ID);
		
		if( isset( $get_meta[$id][0] ) )
			$current_value = $get_meta[$id][0];
			
	switch ( $value['type'] ) {
	
		case 'text': ?>
			<input  name="<?php echo $value['id']; ?>" id="<?php  echo $value['id']; ?>" type="text" value="<?php echo $current_value ?>" />
		<?php 
		break;

		case 'checkbox':
			if( !empty( $current_value ) ){$checked = "checked=\"checked\"";  } else{$checked = "";} ?>
				<input type="checkbox" name="<?php echo $value['id'] ?>" id="<?php echo $value['id'] ?>" value="true" <?php echo $checked; ?> />			
		<?php	
		break;
		
		case 'select':
		?>
			<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
				<?php foreach ($value['options'] as $key => $option) { ?>
				<option value="<?php echo $key ?>" <?php if ( $current_value == $key) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
				<?php } ?>
			</select>
		<?php
		break;
		
		case 'textarea':
		?>
			<textarea style="direction:ltr; text-align:left; width:430px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="textarea" cols="100%" rows="3" tabindex="4"><?php echo $current_value  ?></textarea>
		<?php
		break;
	} ?>
	</div>
<?php
}
?>