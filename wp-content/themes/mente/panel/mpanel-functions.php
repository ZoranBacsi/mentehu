<?php

function tie_get_more_themes_rss(){
	include_once(ABSPATH . WPINC . '/feed.php');
	$rss = fetch_feed('http://themes.tielabs.com/xml/themes.xml');
	if ( !is_wp_error( $rss ) ){
	    return $rss->get_items(); 
	}
	return false;
}

/*-----------------------------------------------------------------------------------*/
# Custom Admin Bar Menus
/*-----------------------------------------------------------------------------------*/
function tie_admin_bar() {
	global $wp_admin_bar;
	
	if ( current_user_can( 'switch_themes' ) ){
		$wp_admin_bar->add_menu( array(
			'parent' => 0,
			'id' => 'mpanel_page',
			'title' => theme_name ,
			'href' => admin_url( 'admin.php?page=panel')
		) );
	}
	
}
add_action( 'wp_before_admin_bar_render', 'tie_admin_bar' );


/*-----------------------------------------------------------------------------------*/
# Register main Scripts and Styles
/*-----------------------------------------------------------------------------------*/
function tie_admin_register() {
    wp_register_script( 'tie-admin-slider', get_template_directory_uri() . '/panel/js/jquery.ui.slider.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse', 'jquery-ui-sortable' ) , false , false );  
    wp_register_script( 'tie-admin-checkbox', get_template_directory_uri() . '/panel/js/checkbox.min.js', array( 'jquery' ) , false , false );  
    wp_register_script( 'tie-admin-main', get_template_directory_uri() . '/panel/js/tie.js', array( 'jquery' ) , false , false );  
    
	wp_register_script( 'tie-admin-colorpicker', get_template_directory_uri() . '/panel/js/colorpicker.js', array( 'jquery' ) , false , false );  
	wp_register_script( 'tie-admin-eye', get_template_directory_uri() . '/panel/js/eye.js', array( 'jquery' ) , false , false );  
	wp_register_script( 'tie-admin-utils', get_template_directory_uri() . '/panel/js/utils.js', array( 'jquery' ) , false , false );  
	wp_register_script( 'tie-admin-layout', get_template_directory_uri() . '/panel/js/layout.js', array( 'jquery' ) , false , false );  
	
	wp_register_style( 'tie-style', get_template_directory_uri().'/panel/style.css', array(), '20120208', 'all' ); 

	if ( isset( $_GET['page'] ) && $_GET['page'] == 'panel' ) {

		wp_enqueue_script( 'tie-admin-colorpicker');  
		wp_enqueue_script( 'tie-admin-eye' );  
		wp_enqueue_script( 'tie-admin-utils' );  
		wp_enqueue_script( 'tie-admin-layout' );
		wp_enqueue_script( 'tie-admin-slider' );  
		wp_enqueue_script( 'tie-admin-checkbox' ); 
	}
	wp_enqueue_script( 'tie-admin-main' );
	wp_enqueue_style( 'tie-style' );

}
add_action( 'admin_enqueue_scripts', 'tie_admin_register' ); 


/*-----------------------------------------------------------------------------------*/
# To change Insert into Post Text
/*-----------------------------------------------------------------------------------*/
function tie_options_setup() {
    global $pagenow;  
	
    if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow )
        add_filter( 'gettext', 'tie_replace_thickbox_text'  , 1, 3 ); 
} 
add_action( 'admin_init', 'tie_options_setup' ); 
  
function tie_replace_thickbox_text($translated_text, $text, $domain) { 
    if ('Insert into Post' == $text) { 
	
        $referer = strpos( wp_get_referer(), 'tie-settings' );
        if ( $referer != '' )
            return __('Use this image', 'tie' ); 
    }  
    return $translated_text;  
}  
	

/*-----------------------------------------------------------------------------------*/
# get Google Fonts
/*-----------------------------------------------------------------------------------*/
require ('google-fonts.php');
$google_font_array = json_decode ($google_api_output,true) ;
	
$items = $google_font_array['items'];
	
$options_fonts=array();
array_push($options_fonts, "Default Font");
$fontID = 0;
foreach ($items as $item) {
	$fontID++;
	$variants='';
	$variantCount=0;
	foreach ($item['variants'] as $variant) {
		$variantCount++;
		if ($variantCount>1) { $variants .= '|'; }
		$variants .= $variant;
	}
	$variantText = ' (' . $variantCount . ' Varaints' . ')';
	if ($variantCount <= 1) $variantText = '';
	$options_fonts[ $item['family'] . ':' . $variants ] = $item['family']. $variantText;
}


/*-----------------------------------------------------------------------------------*/
# Clean options before store it in DB
/*-----------------------------------------------------------------------------------*/
function tie_clean_options(&$value) {
  $value = htmlspecialchars(stripslashes($value));
}

	
/*-----------------------------------------------------------------------------------*/
# Options Array
/*-----------------------------------------------------------------------------------*/
$array_options = 
	array(
		"tie_home_cats",
		"tie_options"
	);
	
	
/*-----------------------------------------------------------------------------------*/
# Save Theme Settings
/*-----------------------------------------------------------------------------------*/	
function tie_save_settings ( $data , $refresh = 0 ) {
	global $array_options ;
		
	foreach( $array_options as $option ){
		if( isset( $data[$option] )){
			array_walk_recursive( $data[$option] , 'tie_clean_options');
			update_option( $option ,  $data[$option]   );
		}
		elseif( !isset( $data[$option] ) && $option != 'tie_options' ){
			delete_option($option);
		}		
	}
	
	if( $refresh == 2 )  die('2');
	elseif( $refresh == 1 )	die('1');
}
	
	
/*-----------------------------------------------------------------------------------*/
# Save Options
/*-----------------------------------------------------------------------------------*/
add_action('wp_ajax_test_theme_data_save', 'tie_save_ajax');
function tie_save_ajax() {
	
	check_ajax_referer('test-theme-data', 'security');
	$data = $_POST;
	$refresh = 1;

	if( $data['tie_import'] ){
		$refresh = 2;
		$data = unserialize(base64_decode( $data['tie_import'] ));
	}
	
	tie_save_settings ($data , $refresh );
	
}


/*-----------------------------------------------------------------------------------*/
# Add Panel Page
/*-----------------------------------------------------------------------------------*/
function tie_add_admin() {

	$current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';

	$icon = get_template_directory_uri().'/panel/images/general.png';
	add_menu_page(theme_name.' Settings', theme_name ,'install_themes', 'panel' , 'panel_options', $icon  );
	$theme_page = add_submenu_page('panel',theme_name.' Settings', theme_name.' Settings','install_themes', 'panel' , 'panel_options');
	add_submenu_page('panel',theme_name.' Documentation', 'Documentation','install_themes', 'docs' , 'redirect_docs');
	//add_submenu_page('panel','Support', 'Support','install_themes', 'support' , 'tie_get_support');


	function tie_get_support(){
		echo "<script type='text/javascript'>window.location='http://support.tielabs.com/';</script>";
	}
	
	function redirect_docs(){
		global $docs_url;
		echo "<script type='text/javascript'>window.location='".$docs_url."';</script>";
	}

	add_action( 'admin_head-'. $theme_page, 'tie_admin_head' );
	function tie_admin_head(){
	
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {

		  jQuery('.on-of').checkbox({empty:'<?php echo get_template_directory_uri(); ?>/panel/images/empty.png'});

		  jQuery('form#tie_form').submit(function() {
			  var data = jQuery(this).serialize();

			  jQuery.post(ajaxurl, data, function(response) {
				  if(response == 1) {
					  jQuery('#save-alert').addClass('save-done');
					  t = setTimeout('fade_message()', 1000);
				  }
				else if( response == 2 ){
					location.reload();
				}
				else {
					 jQuery('#save-alert').addClass('save-error');
					  t = setTimeout('fade_message()', 1000);
				  }
			  });
			  return false;
		  });
		  
		});
		
		function fade_message() {
			jQuery('#save-alert').fadeOut(function() {
				jQuery('#save-alert').removeClass('save-done');
			});
			clearTimeout(t);
		}
				
		jQuery(function() {
			jQuery( "#cat_sortable" ).sortable({placeholder: "ui-state-highlight"});
			jQuery( "#customList" ).sortable({placeholder: "ui-state-highlight"});
			jQuery( "#tabs_cats" ).sortable({placeholder: "ui-state-highlight"});
		});
	</script>
	<?php
		wp_print_scripts('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		do_action('admin_print_styles');
	}
	if( isset( $_REQUEST['action'] ) ){
		if( 'reset' == $_REQUEST['action']  && $current_page == 'panel' && check_admin_referer('reset-action-code' , 'resetnonce') ) {
			global $default_data;
			tie_save_settings( $default_data );
			header("Location: admin.php?page=panel&reset=true");
			die;
		}
	}
}


/*-----------------------------------------------------------------------------------*/
# Add Options
/*-----------------------------------------------------------------------------------*/
function tie_options($value){
	global $options_fonts;
?>
	<div class="option-item" id="<?php echo $value['id'] ?>-item">
		<span class="label"><?php  echo $value['name']; ?></span>
	<?php
	switch ( $value['type'] ) {
	
		case 'text': ?>
			<input  name="tie_options[<?php echo $value['id']; ?>]" id="<?php  echo $value['id']; ?>" type="text" value="<?php echo tie_get_option( $value['id'] ); ?>" />
			<?php if( isset( $value['extra_text'] ) ) : ?><span class="extra-text"><?php echo $value['extra_text'] ?></span><?php endif; ?>
			<?php
				if( $value['id']=="slider_tag" || $value['id']=="breaking_tag"){
				$tags = get_tags('orderby=count&order=desc&number=50'); ?>
				<a style="cursor:pointer" title="Choose from the most used tags" onclick="toggleVisibility('<?php echo $value['id']; ?>_tags');"><img src="<?php echo get_template_directory_uri(); ?>/panel/images/expand.png" alt="" /></a>
				<span class="tags-list" id="<?php echo $value['id']; ?>_tags">
					<?php foreach ($tags as $tag){?>
						<a style="cursor:pointer" onclick="if(<?php echo $value['id'] ?>.value != ''){ var sep = ' , '}else{var sep = ''} <?php echo $value['id'] ?>.value=<?php echo $value['id'] ?>.value+sep+(this.rel);" rel="<?php echo $tag->name ?>"><?php echo $tag->name ?></a>
					<?php } ?>
				</span>
			<?php } ?>		
		<?php 
		break;

		case 'arrayText':  $currentValue = tie_get_option( $value['id'] );?>
			<input  name="tie_options[<?php echo $value['id']; ?>][<?php echo $value['key']; ?>]" id="<?php  echo $value['id']; ?>[<?php echo $value['key']; ?>]" type="text" value="<?php echo $currentValue[$value['key']] ?>" />	
		<?php 
		break;

		case 'short-text': ?>
			<input style="width:50px" name="tie_options[<?php echo $value['id']; ?>]" id="<?php  echo $value['id']; ?>" type="text" value="<?php echo tie_get_option( $value['id'] ); ?>" />
		<?php 
		break;
		
		case 'checkbox':
			if(tie_get_option($value['id'])){$checked = "checked=\"checked\"";  } else{$checked = "";} ?>
				<input class="on-of" type="checkbox" name="tie_options[<?php echo $value['id'] ?>]" id="<?php echo $value['id'] ?>" value="true" <?php echo $checked; ?> />			
		<?php	
		break;


		case 'radio':
		?>
			<div style="float:left; width: 295px;">
				<?php foreach ($value['options'] as $key => $option) { ?>
				<label style="display:block; margin-bottom:8px;"><input name="tie_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" type="radio" value="<?php echo $key ?>" <?php if ( tie_get_option( $value['id'] ) == $key) { echo ' checked="checked"' ; } ?>> <?php echo $option; ?></label>
				<?php } ?>
			</div>
		<?php
		break;
		
		case 'select':
		?>
			<select name="tie_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>">
				<?php foreach ($value['options'] as $key => $option) { ?>
				<option value="<?php echo $key ?>" <?php if ( tie_get_option( $value['id'] ) == $key) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
				<?php } ?>
			</select>
		<?php
		break;
		
		case 'textarea':
		?>
			<textarea style="direction:ltr; text-align:left" name="tie_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" type="textarea" cols="100%" rows="3" tabindex="4"><?php echo tie_get_option( $value['id'] );  ?></textarea>
		<?php
		break;

		case 'upload':
		?>
				<input id="<?php echo $value['id']; ?>" class="img-path" type="text" size="56" style="direction:ltr; text-laign:left" name="tie_options[<?php echo $value['id']; ?>]" value="<?php echo tie_get_option($value['id']); ?>" />
				<input id="upload_<?php echo $value['id']; ?>_button" type="button" class="small_button" value="Upload" />
					
				<div id="<?php echo $value['id']; ?>-preview" class="img-preview" <?php if(!tie_get_option( $value['id'] )) echo 'style="display:none;"' ?>>
					<img src="<?php if(tie_get_option( $value['id'] )) echo tie_get_option( $value['id'] ); else echo get_template_directory_uri().'/panel/images/spacer.png'; ?>" alt="" />
					<a class="del-img" title="Delete"></a>
				</div>
		<?php
		break;

		case 'slider':
		?>
				<div id="<?php echo $value['id']; ?>-slider"></div>
				<input type="text" id="<?php echo $value['id']; ?>" value="<?php echo tie_get_option($value['id']); ?>" name="tie_options[<?php echo $value['id']; ?>]" style="width:50px;" /> <?php echo $value['unit']; ?>
				<script>
				  jQuery(document).ready(function() {
					jQuery("#<?php echo $value['id']; ?>-slider").slider({
						range: "min",
						min: <?php echo $value['min']; ?>,
						max: <?php echo $value['max']; ?>,
						value: <?php if( tie_get_option($value['id']) ) echo tie_get_option($value['id']); else echo 0; ?>,

						slide: function(event, ui) {
						jQuery('#<?php echo $value['id']; ?>').attr('value', ui.value );
						}
					});
				  });
				</script>
		<?php
		break;
		
		
		case 'background':
			$current_value = tie_get_option($value['id']);
		?>
				<input id="<?php echo $value['id']; ?>-img" class="img-path" type="text" size="56" style="direction:ltr; text-align:left" name="tie_options[<?php echo $value['id']; ?>][img]" value="<?php echo $current_value['img']; ?>" />
				<input id="upload_<?php echo $value['id']; ?>_button" type="button" class="small_button" value="Upload" />
					
				<div style="margin-top:15px; clear:both">
					<div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo $current_value['color'] ; ?>"></div></div>
					<input style="width:80px; margin-right:5px;"  name="tie_options[<?php echo $value['id']; ?>][color]" id="<?php  echo $value['id']; ?>color" type="text" value="<?php echo $current_value['color'] ; ?>" />
					
					<select name="tie_options[<?php echo $value['id']; ?>][repeat]" id="<?php echo $value['id']; ?>[repeat]" style="width:96px;">
						<option value="" <?php if ( !$current_value['repeat'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="repeat" <?php if ( $current_value['repeat']  == 'repeat' ) { echo ' selected="selected"' ; } ?>>repeat</option>
						<option value="no-repeat" <?php if ( $current_value['repeat']  == 'no-repeat') { echo ' selected="selected"' ; } ?>>no-repeat</option>
						<option value="repeat-x" <?php if ( $current_value['repeat'] == 'repeat-x') { echo ' selected="selected"' ; } ?>>repeat-x</option>
						<option value="repeat-y" <?php if ( $current_value['repeat'] == 'repeat-y') { echo ' selected="selected"' ; } ?>>repeat-y</option>
					</select>

					<select name="tie_options[<?php echo $value['id']; ?>][attachment]" id="<?php echo $value['id']; ?>[attachment]" style="width:96px;">
						<option value="" <?php if ( !$current_value['attachment'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="fixed" <?php if ( $current_value['attachment']  == 'fixed' ) { echo ' selected="selected"' ; } ?>>Fixed</option>
						<option value="scroll" <?php if ( $current_value['attachment']  == 'scroll') { echo ' selected="selected"' ; } ?>>scroll</option>
					</select>
					
					<select name="tie_options[<?php echo $value['id']; ?>][hor]" id="<?php echo $value['id']; ?>[hor]" style="width:96px;">
						<option value="" <?php if ( !$current_value['hor'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="left" <?php if ( $current_value['hor']  == 'left' ) { echo ' selected="selected"' ; } ?>>Left</option>
						<option value="right" <?php if ( $current_value['hor']  == 'right') { echo ' selected="selected"' ; } ?>>Right</option>
						<option value="center" <?php if ( $current_value['hor'] == 'center') { echo ' selected="selected"' ; } ?>>Center</option>
					</select>
					
					<select name="tie_options[<?php echo $value['id']; ?>][ver]" id="<?php echo $value['id']; ?>[ver]" style="width:100px;">
						<option value="" <?php if ( !$current_value['ver'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="top" <?php if ( $current_value['ver']  == 'top' ) { echo ' selected="selected"' ; } ?>>Top</option>
						<option value="center" <?php if ( $current_value['ver'] == 'center') { echo ' selected="selected"' ; } ?>>Center</option>
						<option value="bottom" <?php if ( $current_value['ver']  == 'bottom') { echo ' selected="selected"' ; } ?>>Bottom</option>

					</select>
				</div>
				<div id="<?php echo $value['id']; ?>-preview" class="img-preview" <?php if( !$current_value['img']  ) echo 'style="display:none;"' ?>>
					<img src="<?php if( $current_value['img'] ) echo $current_value['img'] ; else echo get_template_directory_uri().'/panel/images/spacer.png'; ?>" alt="" />
					<a class="del-img" title="Delete"></a>
				</div>
					
				<script>
				jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
					color: '<?php echo $current_value['color'] ; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#<?php echo $value['id']; ?>colorSelector div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $value['id']; ?>color').val('#'+hex);
					}
				});
				tie_styling_uploader('<?php echo $value['id']; ?>');
				</script>
		<?php
		break;
		
		
		case 'color':
		?>
			<div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo tie_get_option($value['id']) ; ?>"></div></div>
			<input style="width:80px; margin-right:5px;"  name="tie_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" type="text" value="<?php echo tie_get_option($value['id']) ; ?>" />
							
			<script>
				jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
					color: '<?php echo tie_get_option($value['id']) ; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#<?php echo $value['id']; ?>colorSelector div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $value['id']; ?>').val('#'+hex);
					}
				});
				</script>
		<?php
		break;

		
		case 'typography':
			$current_value = tie_get_option($value['id']);
		?>
				<div style="clear:both;"></div>
				<div style="clear:both; padding:10px 14px; margin:0 -15px;">
					<div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo $current_value['color'] ; ?>"></div></div>
					<input style="width:80px; margin-right:5px;"  name="tie_options[<?php echo $value['id']; ?>][color]" id="<?php  echo $value['id']; ?>color" type="text" value="<?php echo $current_value['color'] ; ?>" />
					
					<select name="tie_options[<?php echo $value['id']; ?>][size]" id="<?php echo $value['id']; ?>[size]" style="width:55px;">
						<option value="" <?php if (!$current_value['size'] ) { echo ' selected="selected"' ; } ?>></option>
					<?php for( $i=1 ; $i<101 ; $i++){ ?>
						<option value="<?php echo $i ?>" <?php if (( $current_value['size']  == $i ) ) { echo ' selected="selected"' ; } ?>><?php echo $i ?></option>
					<?php } ?>
					</select>

					<select name="tie_options[<?php echo $value['id']; ?>][font]" id="<?php echo $value['id']; ?>[font]" style="width:150px;">
					<?php foreach( $options_fonts as $font => $font_name ){ ?>
						<option value="<?php echo $font ?>" <?php if ( $current_value['font']  == $font ) { echo ' selected="selected"' ; } ?>><?php echo $font_name ?></option>
					<?php } ?>
					</select>
					
					<select name="tie_options[<?php echo $value['id']; ?>][weight]" id="<?php echo $value['id']; ?>[weight]" style="width:96px;">
						<option value="" <?php if ( !$current_value['weight'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="normal" <?php if ( $current_value['weight']  == 'normal' ) { echo ' selected="selected"' ; } ?>>Normal</option>
						<option value="bold" <?php if ( $current_value['weight']  == 'bold') { echo ' selected="selected"' ; } ?>>Bold</option>
						<option value="lighter" <?php if ( $current_value['weight'] == 'lighter') { echo ' selected="selected"' ; } ?>>Lighter</option>
						<option value="bolder" <?php if ( $current_value['weight'] == 'bolder') { echo ' selected="selected"' ; } ?>>Bolder</option>
						<option value="100" <?php if ( $current_value['weight'] == '100') { echo ' selected="selected"' ; } ?>>100</option>
						<option value="200" <?php if ( $current_value['weight'] == '200') { echo ' selected="selected"' ; } ?>>200</option>
						<option value="300" <?php if ( $current_value['weight'] == '300') { echo ' selected="selected"' ; } ?>>300</option>
						<option value="400" <?php if ( $current_value['weight'] == '400') { echo ' selected="selected"' ; } ?>>400</option>
						<option value="500" <?php if ( $current_value['weight'] == '500') { echo ' selected="selected"' ; } ?>>500</option>
						<option value="600" <?php if ( $current_value['weight'] == '600') { echo ' selected="selected"' ; } ?>>600</option>
						<option value="700" <?php if ( $current_value['weight'] == '700') { echo ' selected="selected"' ; } ?>>700</option>
						<option value="800" <?php if ( $current_value['weight'] == '800') { echo ' selected="selected"' ; } ?>>800</option>
						<option value="900" <?php if ( $current_value['weight'] == '900') { echo ' selected="selected"' ; } ?>>900</option>
					</select>
					
					<select name="tie_options[<?php echo $value['id']; ?>][style]" id="<?php echo $value['id']; ?>[style]" style="width:100px;">
						<option value="" <?php if ( !$current_value['style'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="normal" <?php if ( $current_value['style']  == 'normal' ) { echo ' selected="selected"' ; } ?>>Normal</option>
						<option value="italic" <?php if ( $current_value['style'] == 'italic') { echo ' selected="selected"' ; } ?>>Italic</option>
						<option value="oblique" <?php if ( $current_value['style']  == 'oblique') { echo ' selected="selected"' ; } ?>>oblique</option>
					</select>
				</div>

					
				<script>
				jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
					color: '#<?php echo $current_value['color'] ; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#<?php echo $value['id']; ?>colorSelector div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $value['id']; ?>color').val('#'+hex);
					}
				});
				</script>
		<?php
		break;
	
	}
	
	?>
	
	<?php if( isset( $value['help'] ) ) : ?>
		<a class="mo-help tooltip"  title="<?php echo $value['help'] ?>"></a>
		<?php endif; ?>
	</div>
			
<?php
}
add_action('admin_menu', 'tie_add_admin'); 

?>