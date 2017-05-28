<?php

$themename = "kovaszolo";
$themefolder = "kovaszolo";

define ('theme_name', $themename );
define ('theme_ver' , 3 );

// Notifier Info
$notifier_name = $themename;
$notifier_url = "#".$themefolder.".xml";

//Docs Url
$docs_url = "#".$themefolder;

// Constants for the theme name, folder and remote XML url
define( 'MTHEME_NOTIFIER_THEME_NAME', $themename );
define( 'MTHEME_NOTIFIER_THEME_FOLDER_NAME', $themefolder );
define( 'MTHEME_NOTIFIER_XML_FILE', $notifier_url );
define( 'MTHEME_NOTIFIER_CACHE_INTERVAL', 1 );


// Custom Functions 
include (TEMPLATEPATH . '/custom-functions.php');

// Get Functions
include (TEMPLATEPATH . '/functions/home-cats.php');
include (TEMPLATEPATH . '/functions/home-cat-tabs.php');
include (TEMPLATEPATH . '/functions/home-cat-scroll.php');
include (TEMPLATEPATH . '/functions/home-cat-pic.php');
include (TEMPLATEPATH . '/functions/home-recent-box.php');
include (TEMPLATEPATH . '/functions/theme-functions.php');
include (TEMPLATEPATH . '/functions/common-scripts.php');
include (TEMPLATEPATH . '/functions/banners.php');
include (TEMPLATEPATH . '/functions/widgetize-theme.php');
include (TEMPLATEPATH . '/functions/default-options.php');
include (TEMPLATEPATH . '/functions/updates.php');

// tie-Panel
include (TEMPLATEPATH . '/panel/mpanel-ui.php');
include (TEMPLATEPATH . '/panel/mpanel-functions.php');
include (TEMPLATEPATH . '/panel/shortcodes/shortcode.php');
include (TEMPLATEPATH . '/panel/post-options.php');
include (TEMPLATEPATH . '/panel/custom-slider.php');
include (TEMPLATEPATH . '/panel/notifier/update-notifier.php');

// 
include (TEMPLATEPATH . '/includes/pagenavi.php');
include (TEMPLATEPATH . '/includes/breadcrumbs.php');
include (TEMPLATEPATH . '/includes/wp_list_comments.php');
include (TEMPLATEPATH . '/includes/widgets.php');

if(!class_exists('TwitterOAuth',false)) {
	include (TEMPLATEPATH . '/includes/twitteroauth/twitteroauth.php');
}


if ( ! isset( $content_width ) ) $content_width = 620;


// with activate istall option
if ( is_admin() && isset($_GET['activated'] ) && $pagenow == 'themes.php' ) {

	if( !get_option('tie_active') ){
		tie_save_settings( $default_data );
		update_option( 'tie_active' , theme_ver );
	}
   //header("Location: admin.php?page=panel");
   
}

?>