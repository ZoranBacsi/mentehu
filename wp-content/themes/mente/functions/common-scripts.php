<?php
/*-----------------------------------------------------------------------------------*/
# Register main Scripts and Styles
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_enqueue_scripts', 'tie_register' ); 
function tie_register() {

	## Register All Scripts
    wp_register_script( 'tie-scripts', get_template_directory_uri() . '/js/tie-scripts.js', array( 'jquery' ) );  
    wp_register_script( 'tie-tabs', get_template_directory_uri() . '/js/tabs.min.js', array( 'jquery' ) );  
    wp_register_script( 'tie-cycle', get_template_directory_uri() . '/js/jquery.cycle.all.js', array( 'jquery' ) );
    wp_register_script( 'tie-validation', get_template_directory_uri() . '/js/validation.js', array( 'jquery' ) );  
    wp_register_script( 'tie-flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array( 'jquery' ) );
    wp_register_script( 'tie-jplayer', get_template_directory_uri() . '/js/jquery.jplayer.min.js', array( 'jquery' ) );

	## Get Global Scripts
    wp_enqueue_script( 'tie-scripts' );
		
	## Get Validation Script
	if( tie_get_option('comment_validation') && ( is_page() || is_single() ) && comments_open() )
		wp_enqueue_script( 'tie-validation' );
	
	## For facebook & Google + share
	if(  is_page() || is_single() )	tie_og_image();  ?>
 
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri() ?>/js/html5.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/js/selectivizr-min.js"></script>
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri() ?>/css/ie9.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri() ?>/css/ie8.css" />
<![endif]-->
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri() ?>/css/ie7.css" />
<![endif]-->
<script type='text/javascript'>
	/* <![CDATA[ */
	var tievar = {'go_to' : '<?php _e('Go to...', 'tie') ?>'};
	/* ]]> */
</script>

<?php
}


/*-----------------------------------------------------------------------------------*/
# Enqueue Fonts From Google
/*-----------------------------------------------------------------------------------*/
function tie_enqueue_font ( $got_font) {
	if ($got_font) {
	
		if( tie_get_option('typography_latin_extended') || tie_get_option('typography_cyrillic') ||
		tie_get_option('typography_cyrillic_extended') || tie_get_option('typography_greek') ||
		tie_get_option('typography_greek_extended') ){
		
			$char_set = '&subset=latin';
			if( tie_get_option('typography_latin_extended') ) 
				$char_set .= ',latin-ext';
			if( tie_get_option('typography_cyrillic') )
				$char_set .= ',cyrillic';
			if( tie_get_option('typography_cyrillic_extended') )
				$char_set .= ',cyrillic-ext';
			if( tie_get_option('typography_greek') )
				$char_set .= ',greek';
			if( tie_get_option('typography_greek_extended') )
				$char_set .= ',greek-ext';
		}
		
		$font_pieces = explode(":", $got_font);
			
		$font_name = $font_pieces[0];
		$font_name = str_replace (" ","+", $font_pieces[0] );
				
		$font_variants = $font_pieces[1];
		$font_variants = str_replace ("|",",", $font_pieces[1] );
				
		wp_enqueue_style( $font_name , 'http://fonts.googleapis.com/css?family='.$font_name . ':' . $font_variants.$char_set );
	}
}


/*-----------------------------------------------------------------------------------*/
# Get Font Name
/*-----------------------------------------------------------------------------------*/
function tie_get_font ( $got_font ) {
	if ($got_font) {
		$font_pieces = explode(":", $got_font);
		$font_name = $font_pieces[0];
		return $font_name;
	}
}


/*-----------------------------------------------------------------------------------*/
# Typography Elements Array
/*-----------------------------------------------------------------------------------*/
$custom_typography = array(
	"body"								=>		"typography_general",
	".top-nav, .top-nav ul li a "		=>		"typography_top_menu",
	"#main-nav, #main-nav ul li a"		=>		"typography_main_nav",
	".page-title"						=>		"typography_page_title",
	".post-title, .ei-title h2 , .slider-caption h2 a "						=> 		"typography_post_title",
	"p.post-meta, p.post-meta a"		=> 		"typography_post_meta",
	"body.single .entry, body.page .entry"					=> 		"typography_post_entry",
	".cat-box-title h2, .cat-box-title h2 a, .block-head h3, #respond h3, #comments-title, h2.review-box-header  "			=> 		"typography_boxes_title",
	".widget-top h4, .widget-top h4 a"					=> 		"typography_widgets_title",
	".footer-widget-top h4, .footer-widget-top h4 a"	=> 		"typography_footer_widgets_title",
	".entry h1"				=> 		"typography_post_h1",
	".entry h2"				=> 		"typography_post_h2",
	".entry h3"				=> 		"typography_post_h3",
	".entry h4"				=> 		"typography_post_h4",
	".entry h5"				=> 		"typography_post_h5",
	".entry h6"				=> 		"typography_post_h6"
);
	
	
/*-----------------------------------------------------------------------------------*/
# Get Custom Typography
/*-----------------------------------------------------------------------------------*/
add_action('wp_enqueue_scripts', 'tie_typography');
function tie_typography(){
	global $custom_typography;

	foreach( $custom_typography as $selector => $value){
		$option = tie_get_option( $value );
		tie_enqueue_font( $option['font'] ) ;
	}
}


/*-----------------------------------------------------------------------------------*/
# Tie Wp Head
/*-----------------------------------------------------------------------------------*/
add_action('wp_head', 'tie_wp_head');
function tie_wp_head() {
	global $custom_typography; 
	
	if( tie_get_option( 'disable_responsive' ) ){?>
	
<meta name="viewport" content="width=1045" />
	<?php }else{ ?>
	
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<?php
	}
	if(tie_get_option('theme_skin')): 	?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/style-<?php echo tie_get_option('theme_skin') ?>.css" />
<?php endif; ?>
<?php echo "\n"; ?>
<style type="text/css" media="screen"> 
<?php echo "\n"; ?>
<?php if( tie_get_option('background_type') == 'pattern' ):
	if(tie_get_option('background_pattern') ): ?>
body {background: <?php echo tie_get_option('background_pattern_color') ?> url(<?php echo get_template_directory_uri(); ?>/images/patterns/<?php echo tie_get_option('background_pattern') ?>.png) center;}
	<?php endif; ?>
<?php elseif( tie_get_option('background_type') == 'custom' ):
	$bg = tie_get_option( 'background' ); 
	if( tie_get_option('background_full') ): ?>
.background-cover{<?php echo "\n"; ?>
	background-color:<?php echo $bg['color'] ?>;
	background-image : url('<?php echo $bg['img'] ?>') ;<?php echo "\n"; ?>
	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $bg['img'] ?>',sizingMethod='scale');<?php echo "\n"; ?>
	-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $bg['img'] ?>',sizingMethod='scale')";<?php echo "\n"; ?>
}
<?php else: ?>
body{background:<?php echo $bg['color'] ?> url('<?php echo $bg['img'] ?>') <?php echo $bg['repeat'] ?> <?php echo $bg['attachment'] ?> <?php echo $bg['hor'] ?> <?php echo $bg['ver'] ?>;}<?php echo "\n"; ?>
<?php endif; ?>
<?php endif; ?>
<?php
foreach( $custom_typography as $selector => $value){
$option = tie_get_option( $value );
if( $option['font'] || $option['color'] || $option['size'] || $option['weight'] || $option['style'] ):
echo "\n".$selector."{\n"; ?>
<?php if($option['font'] )
	echo "	font-family: '". tie_get_font( $option['font']  )."';\n"?>
<?php if($option['color'] )
	echo "	color :". $option['color'].";\n"?>
<?php if($option['size'] )
	echo "	font-size : ".$option['size']."px;\n"?>
<?php if($option['weight'] )
	echo "	font-weight: ".$option['weight'].";\n"?>
<?php if($option['style'] )
	echo "	font-style: ". $option['style'].";\n"?>
}
<?php endif;
} ?>
<?php if( tie_get_option( 'global_color' ) ): ?>
#main-nav,.cat-box-content,#sidebar .widget-container,.post-listing {border-bottom-color: <?php echo tie_get_option( 'global_color' );?>;}
.search-block .search-button,
#topcontrol,
#main-nav ul li.current-menu-item a,
#main-nav ul li.current-menu-item a:hover,
#main-nav ul li.current-menu-parent a,
#main-nav ul li.current-menu-parent a:hover,
#main-nav ul li.current-page-ancestor a,
#main-nav ul li.current-page-ancestor a:hover,
.pagination span.current,
.share-post span.share-text,
.flex-control-paging li a.flex-active,
.ei-slider-thumbs li.ei-slider-element,
.review-percentage .review-item span span,.review-final-score   {
	background-color:<?php echo tie_get_option( 'global_color' );?> !important;
}
::-webkit-scrollbar-thumb{background-color:<?php echo tie_get_option( 'global_color' );?> !important;}
footer, .top-nav, .top-nav ul li.current-menu-item:after  {border-top-color: <?php echo tie_get_option( 'global_color' );?>;}
.search-block:after {border-right-color:<?php echo tie_get_option( 'global_color' );?>;}
<?php endif; ?>
<?php if( tie_get_option( 'links_color' ) || tie_get_option( 'links_decoration' )  ): ?>
a {
	<?php if( tie_get_option( 'links_color' ) ) echo 'color: '.tie_get_option( 'links_color' ).';'; ?>
	<?php if( tie_get_option( 'links_decoration' ) ) echo 'text-decoration: '.tie_get_option( 'links_decoration' ).';'; ?>
}
<?php endif; ?>
<?php if( tie_get_option( 'links_color_hover' ) || tie_get_option( 'links_decoration_hover' )  ): ?>
a:hover {
	<?php if( tie_get_option( 'links_color_hover' ) ) echo 'color: '.tie_get_option( 'links_color_hover' ).';'; ?>
	<?php if( tie_get_option( 'links_decoration_hover' ) ) echo 'text-decoration: '.tie_get_option( 'links_decoration_hover' ).';'; ?>
}
<?php endif; ?>
<?php 
$topbar_bg = tie_get_option( 'topbar_background' ); 
if( !empty( $topbar_bg['img']) || !empty( $topbar_bg['color'] ) ): ?>
.top-nav, .top-nav ul ul {background:<?php echo $topbar_bg['color'] ?> url('<?php echo $topbar_bg['img'] ?>') <?php echo $topbar_bg['repeat'] ?> <?php echo $topbar_bg['attachment'] ?> <?php echo $topbar_bg['hor'] ?> <?php echo $topbar_bg['ver'] ?>;}<?php echo "\n"; ?>
<?php endif; ?>
<?php if( tie_get_option( 'topbar_links_color' ) ): ?>
.top-nav ul li a , .top-nav ul ul a {
	<?php if( tie_get_option( 'topbar_links_color' ) ) echo 'color: '.tie_get_option( 'topbar_links_color' ).' !important;'; ?>
}
<?php endif; ?>
<?php if( tie_get_option( 'topbar_links_color_hover' ) ): ?>
.top-nav ul li a:hover, .top-nav ul li:hover > a, .top-nav ul :hover > a , .top-nav ul li.current-menu-item a  {
	<?php if( tie_get_option( 'topbar_links_color_hover' ) ) echo 'color: '.tie_get_option( 'topbar_links_color_hover' ).' !important;'; ?>
}
<?php endif; ?>
<?php if( tie_get_option( 'todaydate_background' ) || tie_get_option( 'todaydate_color' )  ): ?>
.today-date {
	<?php if( tie_get_option( 'todaydate_background' ) ) echo 'background: '.tie_get_option( 'todaydate_background' ).';'; ?>
	<?php if( tie_get_option( 'todaydate_color' ) ) echo 'color: '.tie_get_option( 'todaydate_color' ).';'; ?>
}
<?php endif; ?>
<?php $header_bg = tie_get_option( 'header_background' ); 
if( !empty( $header_bg['img']) || !empty( $header_bg['color'] ) ): ?>
header {background:<?php echo $header_bg['color'] ?> url('<?php echo $header_bg['img'] ?>') <?php echo $header_bg['repeat'] ?> <?php echo $header_bg['attachment'] ?> <?php echo $header_bg['hor'] ?> <?php echo $header_bg['ver'] ?>;}<?php echo "\n"; ?>
<?php endif; ?>
<?php $content_bg = tie_get_option( 'main_content_bg' ); 
if( !empty( $content_bg['img']) || !empty( $content_bg['color'] ) ): ?>
#main-content {background:<?php echo $content_bg['color'] ?> url('<?php echo $content_bg['img'] ?>') <?php echo $content_bg['repeat'] ?> <?php echo $content_bg['attachment'] ?> <?php echo $content_bg['hor'] ?> <?php echo $content_bg['ver'] ?>;}<?php echo "\n"; ?>
<?php endif; ?>
<?php $boxes_bg = tie_get_option( 'boxes_bg' ); 
if( !empty( $boxes_bg['img']) || !empty( $boxes_bg['color'] ) ): ?>
.cat-box-content, #sidebar .widget-container, .post-listing, .column2 li.first-news, .wide-box li.first-news {background:<?php echo $boxes_bg['color'] ?> url('<?php echo $boxes_bg['img'] ?>') <?php echo $boxes_bg['repeat'] ?> <?php echo $boxes_bg['attachment'] ?> <?php echo $boxes_bg['hor'] ?> <?php echo $boxes_bg['ver'] ?> !important;}<?php echo "\n"; ?>
<?php endif; ?>
<?php if( tie_get_option( 'breaking_title_bg' ) ): ?>
.breaking-news span {<?php if( tie_get_option( 'breaking_title_bg' ) ) echo 'background: '.tie_get_option( 'breaking_title_bg' ).';'; ?>}
<?php endif; ?>
<?php if( tie_get_option( 'post_links_color' ) || tie_get_option( 'post_links_decoration' )  ): ?>
body.single .post .entry a, body.page .post .entry a {
	<?php if( tie_get_option( 'post_links_color' ) ) echo 'color: '.tie_get_option( 'post_links_color' ).';'; ?>
	<?php if( tie_get_option( 'post_links_decoration' ) ) echo 'text-decoration: '.tie_get_option( 'post_links_decoration' ).';'; ?>
}
<?php endif; ?>
<?php if( tie_get_option( 'post_links_color_hover' ) || tie_get_option( 'post_links_decoration_hover' )  ): ?>
body.single .post .entry a:hover, body.page .post .entry a:hover {
	<?php if( tie_get_option( 'post_links_color_hover' ) ) echo 'color: '.tie_get_option( 'post_links_color_hover' ).';'; ?>
	<?php if( tie_get_option( 'post_links_decoration_hover' ) ) echo 'text-decoration: '.tie_get_option( 'post_links_decoration_hover' ).';'; ?>
}
<?php endif; ?>
<?php $footer_bg = tie_get_option( 'footer_background' ); 
if( !empty( $footer_bg['img']) || !empty( $footer_bg['color'] ) ): ?>
footer {background:<?php echo $footer_bg['color'] ?> url('<?php echo $footer_bg['img'] ?>') <?php echo $footer_bg['repeat'] ?> <?php echo $footer_bg['attachment'] ?> <?php echo $footer_bg['hor'] ?> <?php echo $footer_bg['ver'] ?>;}<?php echo "\n"; ?>
<?php endif; ?>
<?php if( tie_get_option( 'footer_title_color' ) ): ?>
.footer-widget-top h3 {	<?php if( tie_get_option( 'footer_title_color' ) ) echo 'color: '.tie_get_option( 'footer_title_color' ).';'; ?>
}
<?php endif; ?>
<?php if( tie_get_option( 'footer_links_color' ) ): ?>
footer a  {	<?php if( tie_get_option( 'footer_links_color' ) ) echo 'color: '.tie_get_option( 'footer_links_color' ).' !important;'; ?>
}
<?php endif; ?>
<?php if( tie_get_option( 'footer_links_color_hover' ) ): ?>
footer a:hover {<?php if( tie_get_option( 'footer_links_color_hover' ) ) echo 'color: '.tie_get_option( 'footer_links_color_hover' ).' !important;'; ?>
}
<?php endif; ?>
<?php global $post ;
if( is_category() || is_single() ): 
	if( is_category() ) $category_id = get_query_var('cat') ;
	if( is_single() ){ 
		$categories = get_the_category( $post->ID );
		$category_id = $categories[0]->term_id ;
	}
$cat_bg = tie_get_option("cat".$category_id."_background");
if( $cat_bg['color'] || $cat_bg['img']):
	if( tie_get_option("cat".$category_id."_background_full") ): ?>
.background-cover{<?php echo "\n"; ?>
	background-color:<?php echo $cat_bg['color'] ?>;
	background-image : url('<?php echo $cat_bg['img'] ?>') ;<?php echo "\n"; ?>
	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $cat_bg['img'] ?>',sizingMethod='scale');<?php echo "\n"; ?>
	-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $cat_bg['img'] ?>',sizingMethod='scale')";<?php echo "\n"; ?>
}
<?php else: ?>
body{background:<?php echo $cat_bg['color'] ?> url('<?php echo $cat_bg['img'] ?>') <?php echo $cat_bg['repeat'] ?> <?php echo $cat_bg['attachment'] ?> <?php echo $cat_bg['hor'] ?> <?php echo $cat_bg['ver'] ?>;}<?php echo "\n"; ?>
<?php endif;
endif; 
if( tie_get_option("cat_".$category_id."_color") ): ?>
#main-nav,.cat-box-content,#sidebar .widget-container,.post-listing {border-bottom-color: <?php echo tie_get_option("cat_".$category_id."_color");?>;}
.search-block .search-button,
#topcontrol,
#main-nav ul li.current-menu-item a,
#main-nav ul li.current-menu-item a:hover,
#main-nav ul li.current-menu-parent a,
#main-nav ul li.current-menu-parent a:hover,
#main-nav ul li.current-page-ancestor a,
#main-nav ul li.current-page-ancestor a:hover,
.pagination span.current,
.share-post span.share-text,
.flex-control-paging li a.flex-active,
.ei-slider-thumbs li.ei-slider-element,
.review-percentage .review-item span span,.review-final-score   {
	background-color:<?php echo  tie_get_option("cat_".$category_id."_color");?> !important;
}
::-webkit-scrollbar-thumb{background-color:<?php echo  tie_get_option("cat_".$category_id."_color");?> !important;}
footer, .top-nav, .top-nav ul li.current-menu-item:after  {border-top-color: <?php echo tie_get_option("cat_".$category_id."_color"); ?>}
.search-block:after {border-right-color:<?php echo tie_get_option("cat_".$category_id."_color"); ?>;}
	<?php
	endif;  
	?>
<?php endif; ?>
<?php echo htmlspecialchars_decode( tie_get_option('css') ) , "\n";?>
<?php if( tie_get_option('css_tablets') ) : ?>
@media only screen and (max-width: 985px) and (min-width: 768px){
<?php echo htmlspecialchars_decode( tie_get_option('css_tablets') ) , "\n";?>
}
<?php endif; ?>
<?php if( tie_get_option('css_wide_phones') ) : ?>
@media only screen and (max-width: 767px) and (min-width: 480px){
<?php echo htmlspecialchars_decode( tie_get_option('css_wide_phones') ) , "\n";?>
}
<?php endif; ?>
<?php if( tie_get_option('css_phones') ) : ?>
@media only screen and (max-width: 479px) and (min-width: 320px){
<?php echo htmlspecialchars_decode( tie_get_option('css_phones') ) , "\n";?>
}
<?php endif; ?>
</style> 

<?php
echo htmlspecialchars_decode( tie_get_option('header_code') ) , "\n";
}
?>