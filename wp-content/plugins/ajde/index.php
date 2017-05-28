<?php 

/*
Plugin Name: AJDE EvCal
Description: A simple event calendar plugin for wordpress that utilizes WP's custom post type. This plugin will add an AJAX driven calendar with month-view of events to front-end of your website. Events on front-end can be sorted by date or title. You can easily add events with multiple attributes and customize the calendar layout or build your own calendar using event post meta data. (directions to build your custom calendar in documentation) 
Version: 1.6
Author: Ashan Jay
Author URI: http://ashanjay.com
Text Domain: furnine_i
*/

// The Primary Event calendar class
class AJDE_ev_cal{
	var $plugin_dir;
	var $plugin_url;
	function __construct(){
		$this->plugin_dir = WP_PLUGIN_DIR;
		$this->plugin_url= path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)));		
		
		add_action('init',array($this,'ajde_post_register')); 
		add_action('add_meta_boxes',array($this,'ajde_declare_metabox'));
		add_action('save_post','evcal_save_meta_data');
		add_action('admin_menu', array($this,'create_evcal_menu'));// + Settings page to menu
		add_action( 'admin_init', 'ajde_evcal_register_settings' ); 
		add_action( 'admin_head', array($this,'cpt_icons' ));		
		
		add_action('admin_print_scripts-post.php', array($this,'load_evcal_back_end_scripts'));	
		add_action('admin_print_scripts-post-new.php', array($this,'load_evcal_back_end_scripts'));	
		add_action('admin_init', array($this,'load_evcal_back_end_styles'));	
		
		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", 'ajde_evcal_plugin_settings_link' );
		add_shortcode('add_ajde_evcal','ajde_evcal_calendar_shortcode');		
		
		add_action('init',array($this,'evcal_frontend_scripts'));
		
		
		//Add custom columns to events list on WP back-end
		add_filter("manage_ajde_events_posts_columns", array($this,"ajde_events_edit_columns"));
		add_action("manage_ajde_events_posts_custom_column",  array($this,"ajde_events_custom_columns"));
		
		//internationalization
		add_action( 'init', array($this,'myPluginInit' ));
		
		//plugin uninstall cleanup
		register_uninstall_hook( __FILE__, array($this,'plugin_deactivate' ));
	}
	
	//Load back-end scripts only for post.php page	
	function load_evcal_back_end_scripts(){
		wp_enqueue_script('jquery-ui-datepicker',  $this->plugin_url.'/js/jquery.ui.datepicker.js', array('jquery','jquery-ui-core') );
		//wp_enqueue_script('script',$this->plugin_url.'/js/ajde_evcal_script.js');		
	}
	
	//load front-end script 
	function evcal_frontend_scripts(){
		wp_enqueue_script( 'my-ajax-handle', $this->plugin_url. '/js/ajax-script.js', array('jquery') );
		wp_localize_script( 'my-ajax-handle', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
	}
		
	// back-end styles
	function load_evcal_back_end_styles(){
		$calendar_ui_style_src = $this->plugin_url.'/js/jquery-ui-1.8.16.custom.css';
		wp_register_style('ajde_evcal_backend_style',$calendar_ui_style_src);		
		wp_enqueue_style( 'ajde_evcal_backend_style');
	}
		
	
	//Create a custom meta box for single events post page.
	function ajde_declare_metabox(){		
		add_meta_box('ajdeevcal_mb2','Event Color', 'ajde_evcal_show_box_2','ajde_events', 'normal', 'high');
		add_meta_box('ajdeevcal_mb1','Event Settings', 'ajde_evcal_show_box','ajde_events', 'normal', 'high');
	}
	
	//Custom icon for the events posts
	function cpt_icons() {
		global $post;?>
		<style type="text/css" media="screen">
		#menu-posts-ajde_events .wp-menu-image {background: url(<?php echo $this->plugin_url?>/assets/calendar-day.png) no-repeat 6px -17px !important;}
		#menu-posts-ajde_events:hover .wp-menu-image, #menu-posts-ajde_events.wp-has-current-submenu .wp-menu-image {background-position:6px 7px!important;}
		</style>
	<?php } 
	
	function create_evcal_menu(){	
		add_options_page('EvCal Settings','EvCal Settings','administrator','ajde_evcal_page_content','ajde_evcal_page_content');								
	}
	
	
	//register ajde_events post type
	public function ajde_post_register(){
		$labels = aj_get_proper_labels('Event','Events');
		register_post_type('ajde_events', array(
			'labels' => $labels,	
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => array('slug'=>'events'),
			'query_var' => false,
			'supports' => array('title','editor'),
			'taxonomies'=>array(),
			'menu_position' => 5, 'has_archive' => true
		));	

		//event category type
		register_taxonomy( 
			'event_type', 
			array('ajde_events'), 
			array( 
				'hierarchical' => true, 
				'label' => 'Event Type', 
				'show_ui' => true,
				'query_var' => true, 
				'rewrite' => array( 'slug' => 'event-type' ) 
				) 
		); 
	}
	
	
	function get_plugin_version(){
		$plugin_data = get_plugin_data( __FILE__ );
		$plugin_version = $plugin_data['Version'];
		echo  $plugin_version;
	}
	
	// Custom columns for events post list
	function ajde_events_edit_columns($columns){
		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Event Name",		
			"evsd" => "Event Start Date",
			"eved" => "Event End Date",
			'date'=>'Date'
		);		return $columns;
	}

	function ajde_events_custom_columns($column){
		global $post;
		switch ($column){		
			case "evsd":	echo get_post_meta($post->ID, 'evcal_start_date', true);		break;
			case "eved":	echo get_post_meta($post->ID, 'evcal_end_date', true);		break;
		}
	}
	
	// Initialize this plugin. Called by 'init' hook.
	function myPluginInit() {
		load_plugin_textdomain('furnine_i',false,dirname(plugin_basename(__FILE__)).'/languages');
	}
	
	//deactivate plugin cleanup
	function plugin_deactivate(){
		global $evCal_options;
		foreach( $evCal_options as $okey){
			delete_option($okey);
		}
	}
}


// Create the event calendar object
$AJDE_ev_cal = new AJDE_ev_cal;
	/*
	* The Front-end Event Calendar Code DISPLAY
	*/
	function ajde_evcal_calendar_shortcode( $atts ){
		extract ( shortcode_atts( array(
			'event_type'=> 'all',
			'cal_id'=>'1'
		), $atts ) );		
		
		$content =ajde_evcal_calendar_content($event_type, $cal_id);		
		return $content;
	}	
	function ajde_evcal_calendar($cal_id = 1, $event_type='all'){
		$content =ajde_evcal_calendar_content($event_type, $cal_id);		echo $content;
	}
	
	function ajde_evcal_calendar_content($event_type='', $cal_id=''){
		if(get_option('evcal_cal_hide')=='no'||get_option('evcal_cal_hide')==''):
			
			global $AJDE_ev_cal;
			global $wpdb;			
			$evcal_plugin_url= path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)));
			
			$content = $content_li='';	
			$focused_month_num = date('n', current_time('timestamp'));			
			$focus_month_name = returnmonth_name_by_num($focused_month_num);
			$focused_year = date('Y');
			
			$cal_width = (get_option('evcal_cal_width')!='')?get_option('evcal_cal_width'):640;
			$cal_detail_width = ((int)$cal_width)-85;
			
			//base settings
				$skin  = (get_option('evcal_skin') != '')? get_option('evcal_skin') : 'default';
				
				/* -------------------------------------
					LOAD theme files based on the skin
				*/				
				wp_register_style('evcal_ftheme_style',$AJDE_ev_cal->plugin_url.'/themes/'.$skin.'/style.css');		
				wp_enqueue_style( 'evcal_ftheme_style');				
					
				require_once('themes/'.$skin.'/skin.php');
				require_once('content/content-front_end_styles.php');
			
			$content.="
			<div id='evcal_calendar_".$cal_id."' class='ajde_evcal_calendar'>
				<p id='evcal_loader' class='evcal_loader' style='display:none'></p>
				<div id='evcal_head' class='calendar_header' cur_m='".$focused_month_num."' cur_y='".$focused_year."' ev_type='".$event_type."'>
					<a id='evcal_prev' class='evcal_arrows evcal_btn_prev'></a>
					<p id='evcal_cur'> ".$focus_month_name.", ".$focused_year."</p>
					<a id='evcal_next' class='evcal_arrows evcal_btn_next'></a>					
					<div class='clear'></div>
				</div>
				<div id='evcal_sort_bar' class='evcal_sort_bar' sort_by='sort_date'>
					<ul>
						<li><p class='sort_title'>".((get_option('evcal_lang_sort')=='')?'Sort By':get_option('evcal_lang_sort'))."</p></li>
						<li><p><a id='sort_date' class='cur_sort'>".((get_option('evcal_lang_sdate')=='')?'Date':get_option('evcal_lang_sdate'))."</a> </p></li>
						<li><p><a id='sort_title'>".((get_option('evcal_lang_stitle')=='')?'Title':get_option('evcal_lang_stitle'))."</a></p></li>";
			if(get_option('evcal_cal_fesortcolor')!='' & get_option('evcal_cal_fesortcolor')=='yes'){
				$content.="<li><p><a id='sort_color'>".((get_option('evcal_lang_scolor')=='')?'Color':get_option('evcal_lang_scolor'))."</a></p></li>";}
			$content.="</u></div><p class='evcal_head_shadow'></p>";
			
		        $no_event_text = (get_option('evcal_lang_noeve')!='')? get_option('evcal_lang_noeve'):"No Events"; 
			
				$sort_by ='title';
				$content.="<ul id='evcal_list' class='evcal_events_list'>";
				$content_li = evcal_generate_events($focus_month_name, $focused_month_num, $focused_year, 'sort_date',$event_type);				
				
				$content_li = (!empty($content_li))?$content_li:"<li><p class='no_events'>".$no_event_text."</p></li>";
				$content.=$content_li."</ul>"; 
				
				$content.="<div class='clear'></div></div>".'<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>';
				
			return  $content;			
		
		endif;
	}
	
	
	// Trigger AJAX for loading event lists for months into calebdar front-end view
	add_action('wp_ajax_the_ajax_hook', 'evcal_ajax_callback');
	add_action('wp_ajax_nopriv_the_ajax_hook', 'evcal_ajax_callback');
	function evcal_ajax_callback(){
		$no_event_text = (get_option('evcal_lang_noeve')!='')? get_option('evcal_lang_noeve'):"No Events";
		
		$focused_month_num = (int)($_POST['next_m']);
		//$focused_month = returnmonth($focused_month_num);	
		$focused_year = $_POST['next_y'];		
		$focus_month_name = returnmonth_name_by_num($focused_month_num);		
		$content_li = evcal_generate_events($focus_month_name, $focused_month_num, $focused_year, $_POST['sort_by'], $_POST['event_type'] );
		
		$content_li=(empty ($content_li) )?"<li><p class='no_events'>".$no_event_text."</p></li>":$content_li;
		$return_content = array(
			'content'=>$content_li,
			'new_month_year'=>$focus_month_name);			
		echo json_encode($return_content);		
		exit;
	}	
	
	/*
		------------------------------------------------------------
		//Main function to generate events list for a given month
		------------------------------------------------------------
	*/	
	function evcal_generate_events($focus_month_name, $focused_month_num, $focused_year, $sort_val='sort_date', $event_type){
		
		$sort_array = array(
			'sort_date'=>array(	'orderby'=>'meta_value', 'meta_key'=>'evcal_srow'),
			'sort_title'=>array(	'orderby'=>'title', 'meta_key'=>''),
			'sort_color'=>array(	'orderby'=>'meta_value', 'meta_key'=>'evcal_event_color_n')
		);
		
		
		$focus_month_beg_range = mktime(0,0,0,$focused_month_num,1,$focused_year);
		$focus_month_end_range = mktime(0,0,0,$focused_month_num,(date('t',(strtotime("$focused_year-$focused_month_num-1") ))), $focused_year);
		
		$default_event_color = (get_option('evcal_hexcode')!='')?get_option('evcal_hexcode'):'#ffa800';
		
		//event day array
		$custom_day_names = get_option('evcal_cal_day_cus');			
		if($custom_day_names == '' || $custom_day_names=='no'){
			$evcal_day_is= array(1=>'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
		}else{
			for($x=1; $x<8; $x++){
				$evcal_day_is[$x] = get_option('evcal_lang_day'.$x);
			}
		}

		//event type
		if($event_type !='all'){
			$event_type = explode(',', $event_type);
			$ev_type_ar = array('tax_query'=>array( array('taxonomy'=>'event_type','field'=>'id','terms'=>$event_type) )	);
		}else{ $ev_type_ar = '';}
		
		
		
		$args = array (
			'post_type' => 'ajde_events' ,'posts_per_page'=>-1 ,
			'order'=>'ASC','orderby'=>$sort_array[$sort_val]['orderby'],
			'meta_key'=>$sort_array[$sort_val]['meta_key']
			//'meta_key'=>'evcal_start_month', 'meta_value'=>$focused_month,'meta_compare'=>'=='
			 );
		if( is_array( $ev_type_ar) ){ $args = array_merge($args, $ev_type_ar); }
		
		// WP QUERY
		$events = new WP_Query( $args);
		if ( $events->have_posts() ) :
			while( $events->have_posts()): $events->the_post();		
				include('content/content-event_list.php');
			endwhile; 	
		endif;
		wp_reset_query();
		return $content_li;		
	}
	

	/*
		* Events Post settings Meta Box *
	*/
	function ajde_evcal_show_box_2(){
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename_2' );
		$p_id = get_the_ID();
		$ev_vals = get_post_custom($p_id);
		$color_array=array(1=>'#5484ED','#46D6DB','#51B749','#fbc406','#ff690f','#ed0f16','#b6b6b6');
		?>
		<style>
			.evcal_color_box{cursor:pointer;height:20px; width:20px; margin:3px; float:left;border:2px solid #f6f6f6}
			.evcal_color_box:hover{border:2px solid #8f8f8f}
			.evcal_color_box.selected{border:2px solid #6d6d6d !important}
		</style>
		<table id="meta_tb2" class="form-table meta_tb" >
		<tr>
			<td>
			<div id='evcal_colors'>
				<?php foreach($color_array as $cf=>$color){
					$selected_color = ($ev_vals["evcal_event_color"][0] == $color)? ' selected':null;
					echo "<div class='evcal_color_box".$selected_color."' style='background-color:".$color."' 
					color_n='".$cf."' color='".$color."'></div>";
				}?>				
			</div>
			<p><i>Note: If an event color is not selected, the event color will be set to default color in <a href='<?php bloginfo('url')?>/wp-admin/options-general.php?page=uct_page_content'>Settings</a></i></p>
			<input id='evcal_event_color' type='hidden' name='evcal_event_color' 
				value='<?php echo $ev_vals["evcal_event_color"][0]?>'/>
			<input id='evcal_event_color_n' type='hidden' name='evcal_event_color_n' 
				value='<?php echo $ev_vals["evcal_event_color_n"][0]?>'/>
			</td>
		</tr></table>
	<?php }
	
	
	function ajde_evcal_show_box(){
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );
		
		// The actual fields for data entry
		$p_id = get_the_ID();
		$ev_vals = get_post_custom($p_id);
		
			$show_style_code = ($ev_vals["evcal_allday"][0]=='yes') ? "style='display:none'":null;
			$start_date_text = ($ev_vals["evcal_allday"][0]=='yes') ? "Event Date":"Event Start Date";
			$evcal_end_date_value = ($ev_vals["evcal_allday"][0]=='yes') ? "":$ev_vals["evcal_end_date"][0];

		?>
		<table id="meta_tb" class="form-table meta_tb" >
		<tr>
			<td><label for=''><?php _e('All day event?'); ?></label></td><td>
			<input class='evcal_allday_select' type='radio' name='evcal_allday' value='yes' <?php echo ($ev_vals["evcal_allday"][0]=='yes')?'checked="checked"':null?>/> <?php _e('Yes')?>
			<input class='evcal_allday_select' type='radio' name='evcal_allday' value='no' <?php echo ($ev_vals["evcal_allday"][0]=='no'||$ev_vals["evcal_allday"][0]=='')?'checked="checked"':null?>/> <?php _e('No')?>
			</td>
		</tr><tr><td colspan='2'><hr/></td></tr><tr>
			<td><label id='evcal_start_date_label' for='evcal_start_date'><?php _e($start_date_text)?></label></td><td>
			<input type='text' id='evcal_start_date' class='datapicker_on' name='evcal_start_date' value='<?php echo $ev_vals["evcal_start_date"][0]?>' style='width:100%'/></td>
		</tr><tr  class='evcal_nad' <?php echo $show_style_code?>>
			<td><label for='evcal_start_time_hour'><?php _e('Event Start Time')?></label></td><td>
				<select name='evcal_start_time_hour'>
					<?php
						echo "<option value=''>--</option>";
						$start_time_h = $ev_vals['evcal_start_time_hour'][0];						
					for($x=1; $x<13;$x++){
						echo "<option value='$x'".(($start_time_h==$x)?'selected="selected"':'').">$x</option>";
					}?>
				</select><select name='evcal_start_time_min'>
					<?php	echo "<option value=''>--</option>";
						$start_time_m = $ev_vals['evcal_start_time_min'][0];
					for($x=0; $x<13;$x++){
						$min = ($x<2)?('0'.$x*5):$x*5;
						echo "<option value='$min'".(($start_time_m==$min)?'selected="selected"':'').">$min</option>";
					}?>
				</select>
				<select name='evcal_st_ampm'><?php $st_ampm = $ev_vals['evcal_st_ampm'][0]?>
					<option value='AM' <?php echo ($st_ampm=='AM')?'selected="selected"':''?>>AM</option>
					<option value='PM' <?php echo ($st_ampm=='PM')?'selected="selected"':''?>>PM</option></select>
			</td>
		</tr><tr class='evcal_nad' <?php echo $show_style_code?>>
			<td><label for='evcal_end_date'><?php _e('Event End Date')?></label></td><td>
			<input type='text' id='evcal_end_date' class='datapicker_on' name='evcal_end_date' value='<?php echo $evcal_end_date_value?>' style='width:100%'/></td>
		</tr><tr class='evcal_nad' <?php echo $show_style_code?>>
			<td><label for=''><?php _e('Event End Time')?></label></td><td>
				<select name='evcal_end_time_hour'>
					<?php	echo "<option value=''>--</option>";
						$end_time_h = $ev_vals['evcal_end_time_hour'][0];
					for($x=1; $x<13;$x++){
						echo "<option value='$x'".(($end_time_h==$x)?'selected="selected"':'').">$x</option>";
					}?>
				</select><select name='evcal_end_time_min'>
					<?php	echo "<option value=''>--</option>";
						$end_time_m = $ev_vals['evcal_end_time_min'][0];
					for($x=0; $x<13;$x++){
						$min = ($x<2)?('0'.$x*5):$x*5;
						echo "<option value='$min'".(($end_time_m==$min)?'selected="selected"':'').">$min</option>";
					}?>
				</select>
				<select name='evcal_et_ampm'><?php $et_ampm = $ev_vals['evcal_et_ampm'][0]?>
					<option value='AM' <?php echo ($et_ampm=='AM')?'selected="selected"':''?>>AM</option>
					<option value='PM' <?php echo ($et_ampm=='PM')?'selected="selected"':''?>>PM</option></select>
			</td>
		</tr><tr><td colspan='2'><hr/></td></tr><tr>
			<td colspan='2'><label for=''><?php _e('Event Location')?></label></td></tr><tr><td colspan='2'>
			<input type='text' id='evcal_location' name='evcal_location' value='<?php echo $ev_vals["evcal_location"][0]?>' style='width:100%'/></td>
		</tr><tr><td colspan='2'><hr/></td></tr><tr>
			<td><label for=''><?php _e('Generate Google Map from the Event location address?')?></label></td><td>
			<input type='radio' name='evcal_gmap_gen' value='yes' <?php echo ($ev_vals["evcal_gmap_gen"][0]=='yes')?'checked="checked"':null?>/> <?php _e('Yes')?>
			<input type='radio' name='evcal_gmap_gen' value='no' <?php echo ($ev_vals["evcal_gmap_gen"][0]=='no'||$ev_vals["evcal_gmap_gen"][0]=='')?'checked="checked"':null?>/> <?php _e('No')?>
			</td>
		</tr>
		<?php if(get_option('evcal_evb_events')=='yes'):?>
		<tr>
			<td><label for=''><?php _e('EventBrite Event ID')?></label></td><td>
			<input type='text' id='evcal_evb_id' name='evcal_evb_id' value='<?php echo $ev_vals["evcal_evb_id"][0]?>' style='width:100%'/></td>
		</tr><tr>
			<td><label for=''><?php _e('Show EventBrite "Buy a ticket" link')?></label></td><td>
			<input type='radio' name='evcal_evb_buy_tix' value='yes' <?php echo ($ev_vals["evcal_evb_buy_tix"][0]=='yes')?'checked="checked"':null?>/> <?php _e('Yes')?>
			<input type='radio' name='evcal_evb_buy_tix' value='no' <?php echo ($ev_vals["evcal_evb_buy_tix"][0]=='no'||$ev_vals["evcal_evb_buy_tix"][0]=='')?'checked="checked"':null?>/> <?php _e('No')?>
			</td>
		</tr><tr>
			<td><label for=''><?php _e('Open "Buy a ticket" in new tab/window?')?></label></td><td>
			<input type='radio' name='evcal_evb_buy_tix_win' value='yes' <?php echo ($ev_vals["evcal_evb_buy_tix_win"][0]=='yes')?'checked="checked"':null?>/> <?php _e('Yes')?>
			<input type='radio' name='evcal_evb_buy_tix_win' value='no' <?php echo ($ev_vals["evcal_evb_buy_tix_win"][0]=='no'||$ev_vals["evcal_evb_buy_tix_win"][0]=='')?'checked="checked"':null?>/> <?php _e('No')?>
			</td>
		</tr><?php endif;?>
		</table>
<script type='text/javascript'>
jQuery(document).ready(function($){
	$(".evcal_allday_select").click(function(){		
		if($(this).val() == 'yes'){
			$('.evcal_nad').hide();
			$('#evcal_start_date_label').html("Event Date");
			$('#evcal_end_date').val('');
		}else{
			$('.evcal_nad').show(); $('#evcal_start_date_label').html("Event Start Date");
		}
	});
	
	//event color
	$('.evcal_color_box').click(function(){
		$('.evcal_color_box').removeClass('selected');
		$(this).addClass('selected');
		$('#evcal_event_color').val( $(this).attr('color') );
		$('#evcal_event_color_n').val( $(this).attr('color_n') );
	});
	
	//date picker on 
	$('.datapicker_on').datepicker();	
});
</script>
	<?php }
	
	
	// Save events custom meta box values
	function evcal_save_meta_data($post_id){
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
			return;
		// Check permissions
		if ( !current_user_can( 'edit_post', $post_id ) )
			return;		
		//save the post meta values
		$fields_ar = array('evcal_allday','evcal_start_date','evcal_end_date', 'evcal_event_color','evcal_event_color_n',
			'evcal_start_time_hour','evcal_start_time_min','evcal_st_ampm','evcal_end_time_hour','evcal_end_time_min',
			'evcal_et_ampm','evcal_location','evcal_evb_id','evcal_evb_buy_tix','evcal_evb_buy_tix_win','evcal_gmap_gen');
		
		foreach($fields_ar as $f_val){
			global $AJDE_ev_cal;
			if(!empty ($_POST[$f_val])){
				if($f_val=='evcal_start_date'){
					$evcal_start_month = ajde_evcal_formate_date($_POST['evcal_start_date'],'F');
					$evcal_start_month_n = ajde_evcal_formate_date($_POST['evcal_start_date'],'n');
					$evcal_start_day_num = ajde_evcal_formate_date($_POST['evcal_start_date'],'j');
					$evcal_start_day = ajde_evcal_formate_date($_POST['evcal_start_date'],'N');
					update_post_meta( $post_id, 'evcal_start_month_s',$evcal_start_month);
					update_post_meta( $post_id, 'evcal_start_month_n',$evcal_start_month_n);
					update_post_meta( $post_id, 'evcal_start_day_num',$evcal_start_day_num);
					update_post_meta( $post_id, 'evcal_start_day',$evcal_start_day);
					
				}elseif($f_val=='evcal_end_date' ){
					$evcal_end_month = ajde_evcal_formate_date($_POST['evcal_end_date'],'F');
					$evcal_end_month_n = ajde_evcal_formate_date($_POST['evcal_end_date'],'n');
					$evcal_end_day_num = ajde_evcal_formate_date($_POST['evcal_end_date'],'j');
					$evcal_end_day = ajde_evcal_formate_date($_POST['evcal_end_date'],'N');
					update_post_meta( $post_id, 'evcal_end_month_s',$evcal_end_month);
					update_post_meta( $post_id, 'evcal_end_day',$evcal_end_day);
					update_post_meta( $post_id, 'evcal_end_month_n',$evcal_end_month_n);
					update_post_meta( $post_id, 'evcal_end_day_num',$evcal_end_day_num);
				}				
				update_post_meta( $post_id, $f_val, $_POST[$f_val]);
			}			
		}
		//delete other custom meta values 
		if(empty($_POST['evcal_start_date']) ){
			delete_post_meta($post_id, 'evcal_start_month_s');
			delete_post_meta($post_id, 'evcal_start_month_n');
			delete_post_meta($post_id, 'evcal_start_day_num');
		}elseif( empty( $_POST['evcal_end_date'] )  && !empty($_POST['evcal_start_date']) ){
			$evcal_start_month = ajde_evcal_formate_date($_POST['evcal_start_date'],'F');
			$evcal_start_month_n = ajde_evcal_formate_date($_POST['evcal_start_date'],'n');
			$evcal_start_day_num = ajde_evcal_formate_date($_POST['evcal_start_date'],'j');
			update_post_meta( $post_id, 'evcal_end_month_s',$evcal_start_month);
			update_post_meta( $post_id, 'evcal_end_month_n',$evcal_start_month_n);
			update_post_meta( $post_id, 'evcal_end_day_num',$evcal_start_day_num);
		}

		//store unix time stamp
		$st_date_val =$_POST['evcal_start_date'];
		$et_date_val =$_POST['evcal_end_date'];		
		$et_date_val =(isset($et_date_val))? $et_date_val :$st_date_val; // if end date is empty
		$row_st_date =strtotime($st_date_val);
		$row_et_date =($et_date_val!='')?strtotime($et_date_val):$row_st_date;	
		
		update_post_meta( $post_id, 'evcal_srow', $row_st_date);
		update_post_meta( $post_id, 'evcal_erow', $row_et_date);
		
		//set event color code to 1 for none select colors
		if(empty($_POST['evcal_event_color_n'])){
			update_post_meta( $post_id, 'evcal_event_color_n',1);
		}
	}
	
	
	

/*
	The Back-end settings page for Event Calendar
*/
function ajde_evcal_page_content(){
	global $AJDE_ev_cal;
	$plugin_url = $AJDE_ev_cal->plugin_url;
	$plugin_dir = $AJDE_ev_cal->plugin_dir;
	
	//browse for skins
	$path = $plugin_dir.'/ajde/themes';	
	$skin_dirs = scandir($path);
	foreach ($skin_dirs as $skin_dir) {
		if ($skin_dir === '.' or $skin_dir === '..') continue;
		if (is_dir($path . '/' . $skin_dir)) {
			$evcal_skins[]=  $skin_dir;
		}
	}
?>
<style>
#meta_tabs a{cursor:pointer}
.evcal_admin_meta{display:none}
.evcal_focus{display:block}
.postbox hr{background-color:#DFDFDF;border:none;height:1px;border-bottom:1px solid #fff}
</style>
<div class="wrap" id='evcal_settings'>
	<div id="icon-themes" class="icon32"></div>
	<h2>AJDE <?php _e('Event Calendar Settings')?> (ver <?php $AJDE_ev_cal->get_plugin_version();?>)</h2>
	<h2 class='nav-tab-wrapper' id='meta_tabs'>
		<a class='nav-tab nav-tab-active' evcal_meta='evcal_1'><?php _e('Settings')?></a>
		<a class='nav-tab ' evcal_meta='evcal_2'><?php  _e('Language')?></a>
		<a class='nav-tab ' evcal_meta='evcal_3'><?php _e('Styles')?></a>
		<a class='nav-tab ' evcal_meta='evcal_4'><?php _e('Adding Event Calendar to site')?></a>
	</h2>
	<form method="post" action="options.php"><?php settings_fields('evcal_field_group'); ?>
<div class='metabox-holder'>	
	<div id="evcal_1" class=" evcal_admin_meta evcal_focus">	
		<div class='postbox'>
		<h3 class="hndle"><span><?php _e('Event Calendar Settings')?></span></h3>
		<div class="inside">
			<table width='100%'><tr>
				<td width='195px'><p><?php _e('Hide Event Calendar')?><br/>from front-end:</p></td><td>				
					<input type='radio' name='evcal_cal_hide' value='yes' <?php echo (get_option('evcal_cal_hide')=='yes')?'checked="checked"':null?>/> <?php _e('Yes')?>
					<input type='radio' name='evcal_cal_hide' value='no' <?php echo (get_option('evcal_cal_hide')=='no'||get_option('evcal_cal_hide')=='')?'checked="checked"':null?>/> <?php _e('No')?>
				</td>
			</tr><tr>
				<td width='195px'><p><?php _e('Show past events in the calendar')?>:</p></td><td>				
					<input type='radio' name='evcal_cal_show_past' value='yes' <?php echo (get_option('evcal_cal_show_past')=='yes'||get_option('evcal_cal_show_past')=='')?'checked="checked"':null?>/> <?php _e('Yes')?>
					<input type='radio' name='evcal_cal_show_past' value='no' <?php echo (get_option('evcal_cal_show_past')=='no')?'checked="checked"':null?>/> <?php _e('No')?>
					
				</td>
			</tr><tr>
				<td width='195px'><p><?php _e('Enable "Sort events by Color" on the front-end calendar')?>:</p></td><td>
					<input type='radio' name='evcal_cal_fesortcolor' value='yes' <?php echo (get_option('evcal_cal_fesortcolor')=='yes')?'checked="checked"':null?>/> <?php _e('Yes')?>
					<input type='radio' name='evcal_cal_fesortcolor' value='no' <?php echo (get_option('evcal_cal_fesortcolor')=='no'||get_option('evcal_cal_fesortcolor')=='')?'checked="checked"':null?>/> <?php _e('No')?>
					
					
				</td>
			</tr>
			<tr><td colspan='2'><hr/></td></tr>
					<tr><td ><p class='ffgi padt3'>Select the Calendar Skin</p></td>
						<td><select name='evcal_skin'>
						<?php
							$evcal_skin = get_option('evcal_skin');
							foreach($evcal_skins as $sa){
								$selected = ($evcal_skin == $sa)?"selected='selected'":null;
								echo "<option value='$sa' ".$selected.">$sa</option>";
							}
						?></select></td>
					</tr>
			
			</table>			
		</div>
		</div><div class='postbox'>
		<h3 class="hndle"><span><?php _e('EventBrite Settings')?></span></h3>
		<div class="inside">
			<table width='100%'><tr>
				<tr><td width='195px' valign='top'><p style='margin-top:0'><?php _e('Enable EventBrite data fetching for calendar events')?>:</p></td><td>				
					<input type='radio' name='evcal_evb_events' value='yes' <?php echo (get_option('evcal_evb_events')=='yes')?'checked="checked"':null?>/> <?php _e('Yes')?>
					<input type='radio' name='evcal_evb_events' value='no' <?php echo (get_option('evcal_evb_events')=='no'||get_option('evcal_evb_events')=='')?'checked="checked"':null?>/> <?php _e('No')?> <br/>
					<p><i>(Once enabled, you will be able to use EventBrite to add features such as sell tickets for events, collect payments, add limited capacity for events etc. More details in Documentation)</i></p>
				</tr><tr><td width='195px' valign='top'><p style='margin-top:0'>EventBrite API Key:</p></td><td>
					<input type='text' name='evcal_evb_api' style='width:100%' 
					value='<?php echo get_option('evcal_evb_api')?>'/><br/>
					<p><i>(In order to get your eventbrite API key <a href='https://www.eventbrite.com/api/key/' target='_blank'>open this</a> and login to your eventbrite account, fill in the required information in this page and click "create key". Once approved, you will receive the API key.)</i></p>
				</tr>
			</table>			
		</div></div>
		
	</div><div id="evcal_2" class="postbox evcal_admin_meta">	
		<h3 class="hndle"><span><?php _e('Language Customization')?></span></h3>
		<div class="inside">
			<p><i>Use the below fields to populate custom language names for calendar text. If left blank, text will be default to english.</i></p>
			<table width='100%'>
				<tr><td width='195px'><p>January (1)</p></td><td><input type='text' name='evcal_lang_jan' style='width:100%' value='<?php echo get_option('evcal_lang_jan')?>'/></td></tr>	
				<tr><td width='195px'><p>February (2)</p></td><td><input type='text' name='evcal_lang_feb' style='width:100%' value='<?php echo get_option('evcal_lang_feb')?>'/></td></tr>		
				<tr><td width='195px'><p>March (3)</p></td><td><input type='text' name='evcal_lang_mar' style='width:100%' value='<?php echo get_option('evcal_lang_mar')?>'/></td></tr>		
				<tr><td width='195px'><p>April (4)</p></td><td><input type='text' name='evcal_lang_apr' style='width:100%' value='<?php echo get_option('evcal_lang_apr')?>'/></td></tr>		
				<tr><td width='195px'><p>May (5)</p></td><td><input type='text' name='evcal_lang_may' style='width:100%' value='<?php echo get_option('evcal_lang_may')?>'/></td></tr>		
				<tr><td width='195px'><p>June (6)</p></td><td><input type='text' name='evcal_lang_jun' style='width:100%' value='<?php echo get_option('evcal_lang_jun')?>'/></td></tr>		
				<tr><td width='195px'><p>July (7)</p></td><td><input type='text' name='evcal_lang_jul' style='width:100%' value='<?php echo get_option('evcal_lang_jul')?>'/></td></tr>		
				<tr><td width='195px'><p>August (8)</p></td><td><input type='text' name='evcal_lang_aug' style='width:100%' value='<?php echo get_option('evcal_lang_aug')?>'/></td></tr>		
				<tr><td width='195px'><p>September (9)</p></td><td><input type='text' name='evcal_lang_sep' style='width:100%' value='<?php echo get_option('evcal_lang_sep')?>'/></td></tr>		
				<tr><td width='195px'><p>October (10)</p></td><td><input type='text' name='evcal_lang_oct' style='width:100%' value='<?php echo get_option('evcal_lang_oct')?>'/></td></tr>		
				<tr><td width='195px'><p>November (11)</p></td><td><input type='text' name='evcal_lang_nov' style='width:100%' value='<?php echo get_option('evcal_lang_nov')?>'/></td></tr>		
				<tr><td width='195px'><p>December (12)</p></td><td><input type='text' name='evcal_lang_dec' style='width:100%' value='<?php echo get_option('evcal_lang_dec')?>'/></td></tr>
				<tr><td width='195px'><p>Sort By</p></td><td><input type='text' name='evcal_lang_sort' style='width:100%' value='<?php echo get_option('evcal_lang_sort')?>'/></td></tr>
				<tr><td width='195px'><p>Date</p></td><td><input type='text' name='evcal_lang_sdate' style='width:100%' value='<?php echo get_option('evcal_lang_sdate')?>'/></td></tr>
				<tr><td width='195px'><p>Title</p></td><td><input type='text' name='evcal_lang_stitle' style='width:100%' value='<?php echo get_option('evcal_lang_stitle')?>'/></td></tr>
				<tr><td width='195px'><p>Color</p></td><td><input type='text' name='evcal_lang_scolor' style='width:100%' value='<?php echo get_option('evcal_lang_scolor')?>'/></td></tr>
				<tr><td colspan ='2'><hr/></td></tr>
				<tr><td width='195px'><p>Enable custom day names?</p></td>
				<td><input type='radio' name='evcal_cal_day_cus' value='yes' <?php echo (get_option('evcal_cal_day_cus')=='yes')?'checked="checked"':null?>/> <?php _e('Yes')?>
					<input type='radio' name='evcal_cal_day_cus' value='no' <?php echo (get_option('evcal_cal_day_cus')=='no' || get_option('evcal_cal_day_cus') =='')?'checked="checked"':null?>/> <?php _e('No')?>
					<p><i>(If you select "yes" to this, you MUST fill in the names of the days below)</i></p>
				</td></tr>
				<tr><td width='195px'><p>Monday</p></td><td><input type='text' name='evcal_lang_day1' style='width:100%' value='<?php echo get_option('evcal_lang_day1')?>'/></td></tr>
				<tr><td width='195px'><p>Tuesday</p></td><td><input type='text' name='evcal_lang_day2' style='width:100%' value='<?php echo get_option('evcal_lang_day2')?>'/></td></tr>
				<tr><td width='195px'><p>Wednesday</p></td><td><input type='text' name='evcal_lang_day3' style='width:100%' value='<?php echo get_option('evcal_lang_day3')?>'/></td></tr>
				<tr><td width='195px'><p>Thursday</p></td><td><input type='text' name='evcal_lang_day4' style='width:100%' value='<?php echo get_option('evcal_lang_day4')?>'/></td></tr>
				<tr><td width='195px'><p>Friday</p></td><td><input type='text' name='evcal_lang_day5' style='width:100%' value='<?php echo get_option('evcal_lang_day5')?>'/></td></tr>
				<tr><td width='195px'><p>Saturday</p></td><td><input type='text' name='evcal_lang_day6' style='width:100%' value='<?php echo get_option('evcal_lang_day6')?>'/></td></tr>
				<tr><td width='195px'><p>Sunday</p></td><td><input type='text' name='evcal_lang_day7' style='width:100%' value='<?php echo get_option('evcal_lang_day7')?>'/></td></tr>
				<tr><td colspan ='2'><hr/></td></tr>
				
				<tr><td width='195px'><p>No Events</p></td><td><input type='text' name='evcal_lang_noeve' style='width:100%' value='<?php echo get_option('evcal_lang_noeve')?>'/></td></tr>
			</table>			
		</div>
	</div><div id="evcal_3" class="postbox evcal_admin_meta">	
		<h3 class="hndle"><span><?php _e('Event Calendar Styles')?></span></h3>
		<div class="inside">
			<table width='100%'><tr><td width='195px'><p><?php _e('Calendar Width')?> (px):</p></td><td>
				<input type='text' name='evcal_cal_width' style='width:100%' 
				value='<?php echo (get_option('evcal_cal_width')=='')?'640':get_option('evcal_cal_width')?>'/>
			</tr><tr><td width='195px'><p><?php _e('Event Date Background color')?> <br/>(HEX Code):</p></td><td>
				<input type='text' name='evcal_hexcode' style='width:100%' 
					value='<?php echo (get_option('evcal_hexcode')=='')?'#FFA800':get_option('evcal_hexcode')?>'/>
			</tr><tr><td width='195px'><p><?php _e('Write your custom Styles for calendar')?><br/><i>(These will be append to current styles)</i>:</p></td><td>
				<textarea style='width:100%; height:150px'name='evcal_styles'><?php echo get_option('evcal_styles')?></textarea>				
			</tr>		
			</table>			
		</div>
	</div>
	<div id="evcal_4" class="postbox evcal_admin_meta">	
		<h3 class="hndle"><span><?php _e('Adding Calendar to front-end')?></span></h3>
		<div class="inside">
		<p><b>1.0  <?php _e('Via Shortcode')?>:</b><br/>In the Post or Page editor add this shortcode "<b>[add_ajde_evcal]</b>" where ever you need the
		calendar to show up. Switch from visual to the HTML editor before pasting in the shortcode to avoid wrapping extra code around it.<p>
		
		<p><b>1.1 Certain event types only with short code</b><br/> In this case you <b>MUST</b> pass on 2 variable values in the short code which are "cal_id" and "event_type".<br/>
		cal_id = a unique id for this calendar.<br/>
		event_type = separated by commas, list of event type tag <b>id</b> from event types. eg. 4,5,32,48 (no spaces)<br/><br/>
		example of use: <b>[add_ajde_evcal cal_id='1' event_type='3,47,21']</b>
	
		<p><b>2.0  <?php _e('Via Template Tag')?>:</b><br/>Use this template tag in a theme file, such as a page template. 		
			<b>&lt;? if( function_exists('ajde_evcal_calendar')) { ajde_evcal_calendar(); }?&gt;</b></p>
		<p><b>2.1 Template Tag with only certain event types</b><br/>
		You MUST pass 2 variable values, "cal_id" and "event_type"<br/>
		cal_id = a unique id for this calendar.<br/>
		event_type = separated by commas, list of event type tag <b>id</b> from event types. eg. 4,5,32,48 (no spaces)<br/><br/>
		example use : <b>&lt;? if( function_exists('ajde_evcal_calendar')) { ajde_evcal_calendar('3', '4,23,12,5'); }?&gt;</b>
		</p>
		</div>
	</div>
	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</div></form>
<script type='text/javascript'>
jQuery(document).ready(function($){
	$('#meta_tabs').delegate('a','click',function(){
		var evcal_meta = $(this).attr('evcal_meta');
		$('.evcal_admin_meta').hide();
		$('#'+evcal_meta).show();
		$('#meta_tabs').find('a').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		return false;
	});
});
</script>
<?php }

global $evCal_options;
$evCal_options = array('evcal_lang_noeve','evcal_cal_fesortcolor','evcal_lang_scolor','evcal_cal_day_cus','evcal_lang_day1','evcal_lang_day2','evcal_lang_day3','evcal_lang_day4','evcal_lang_day5','evcal_lang_day6',
	'evcal_lang_day7','evcal_skin','evcal_cal_show_past','evcal_hexcode','evcal_styles','evcal_cal_width','evcal_cal_hide','evcal_evb_api','evcal_evb_events','evcal_lang_jan','evcal_lang_feb',
	'evcal_lang_mar','evcal_lang_apr','evcal_lang_may','evcal_lang_jun','evcal_lang_jul','evcal_lang_aug','evcal_lang_sep','evcal_lang_oct','evcal_lang_nov',
	'evcal_lang_dec','evcal_lang_sort','evcal_lang_sdate','evcal_lang_stitle');

//store the values from evcal settings page fields
function ajde_evcal_register_settings(){
	global $evCal_options;
	foreach($evCal_options as $meta_key){
		register_setting('evcal_field_group',$meta_key);
	}
}


// Add settings link on plugin page
function ajde_evcal_plugin_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=ajde_evcal_page_content">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
} 

/**
	* Genaral Functions *
**/

// Returns a proper form of labeling for custom post type
function aj_get_proper_labels($sin, $plu){
	return array(
    'name' => _x($plu, 'post type general name'),
    'singular_name' => _x($sin, 'post type singular name'),
    'add_new' => _x('Add New', $sin),
    'add_new_item' => __('Add New '.$sin),
    'edit_item' => __('Edit '.$sin),
    'new_item' => __('New '.$sin),
    'all_items' => __('All '.$plu),
    'view_item' => __('View '.$sin),
    'search_items' => __('Search '.$plu),
    'not_found' =>  __('No '.$plu.' found'),
    'not_found_in_trash' => __('No '.$plu.' found in Trash'), 
    'parent_item_colon' => '',
    'menu_name' => $plu
  );
}

// Return formatted time 
function ajde_evcal_formate_date($date,$return_var){	
	$srt = strtotime($date);
	$f_date = date($return_var,$srt);
	return $f_date;
}

function returnmonth($n){
	$timestamp = mktime(0,0,0,$n,1,2012);
	return date('F',$timestamp);
}
function returnmonth_name_by_num($n){
	//get custom month names
	$default_month_names = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
	$month_field_ar = array(1=>'evcal_lang_jan',2=>'evcal_lang_feb',3=>'evcal_lang_mar',4=>'evcal_lang_apr',5=>'evcal_lang_may',6=>'evcal_lang_jun',7=>'evcal_lang_jul',8=>'evcal_lang_aug',9=>'evcal_lang_sep',10=>'evcal_lang_oct',11=>'evcal_lang_nov',12=>'evcal_lang_dec');
	
	$cus_month_name = get_option($month_field_ar[$n]);
	$cus_month_name =($cus_month_name!='')?$cus_month_name: $default_month_names[$n];
	
	return $cus_month_name;
}