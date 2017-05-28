<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold' rel='stylesheet' type='text/css'/>
<?php wp_head(); ?>
</head>
<?php global $is_IE ?>
<body id="top" <?php body_class(); ?>>
	<div class="background-cover"></div>
	<div class="wrapper">
		<header>
			<?php if(!tie_get_option( 'top_menu' )): ?>
			<div class="top-nav">
			<?php if(tie_get_option( 'top_date' )):
				if( tie_get_option('todaydate_format') ) $date_format = tie_get_option('todaydate_format');
				else $date_format = 'l ,  j  F Y';
			?>
				<span class="today-date"><?php  echo date_i18n( $date_format , current_time( 'timestamp' ) ); ?></span><?php endif; ?>
					
				<?php wp_nav_menu( array( 'container_class' => 'top-menu', 'theme_location' => 'top-menu', 'fallback_cb' => 'tie_nav_fallback'  ) ); ?>

	<?php if(tie_get_option( 'top_right' ) == 'search'): ?>
					<div class="search-block">
						<form method="get" id="searchform" action="<?php echo home_url(); ?>/">
							<input class="search-button" type="submit" value="<?php if( !$is_IE ) _e( 'Search' , 'tie' ) ?>" />	
							<input type="text" id="s" name="s" value="<?php _e( 'Search...' , 'tie' ) ?>" onfocus="if (this.value == '<?php _e( 'Search...' , 'tie' ) ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'Search...' , 'tie' ) ?>';}"  />
						</form>
					</div><!-- .search-block /-->
	<?php elseif( tie_get_option('top_right') == 'social' ):
		tie_get_social( 'yes' , 16 , 'tooldown' ); ?>
	<?php endif; ?>
			</div><!-- .top-menu /-->
			<?php endif; ?>

		<div class="header-content">
<?php $logo_margin =''; if( tie_get_option( 'logo_margin' )) $logo_margin = ' style="margin-top:'.tie_get_option( 'logo_margin' ).'px"';  ?>
			<div class="logo"<?php echo $logo_margin ?>>
			<?php if( !is_singular() ) echo '<h1>'; else echo '<h2>'; ?>
<?php if( tie_get_option('logo_setting') == 'title' ): ?>
				<a  href="<?php echo home_url() ?>/"><?php bloginfo('name'); ?></a>
				<span><?php bloginfo( 'description' ); ?></span>
				<?php else : ?>
				<?php if( tie_get_option( 'logo' ) ) $logo = tie_get_option( 'logo' );
						else $logo = get_stylesheet_directory_uri().'/images/logo.png';
				?>
				<a title="<?php bloginfo('name'); ?>" href="<?php echo home_url(); ?>/">
					<img src="<?php echo $logo ; ?>" alt="<?php bloginfo('name'); ?>" /><strong><?php bloginfo('name'); ?> <?php bloginfo( 'description' ); ?></strong>
				</a>
<?php endif; ?>
			<?php if( !is_singular() ) echo '</h1>'; else echo '</h2>'; ?>
			</div><!-- .logo /-->
			<?php tie_banner('banner_top' , '<div class="ads-top">' , '</div>' ); ?>
			<div class="clear"></div>
		</div>	
		<?php $stick = ''; ?>
		<?php if( tie_get_option( 'stick_nav' ) ) $stick = ' class="fixed-enabled"' ?>
			<nav id="main-nav"<?php echo $stick; ?>>
				<?php wp_nav_menu( array( 'container_class' => 'main-menu', 'theme_location' => 'primary' ,'fallback_cb' => 'tie_nav_fallback'  ) ); ?>
				<?php if(tie_get_option( 'random_article' )): ?>
				<a href="<?php echo home_url(); ?>/?tierand" class="random-article ttip" title="<?php _e( 'Random Article' , 'tie' ) ?>"><?php _e( 'Random Article' , 'tie' ) ?></a>
				<?php endif ?>
			</nav><!-- .main-nav /-->
	
		</header><!-- #header /-->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/hu_HU/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    
    
	<?php tie_include( 'breaking-news' ); // Get Breaking News template ?>	
<?php 
$sidebar = '';
if( tie_get_option( 'sidebar_pos' ) == 'left' ) $sidebar = ' sidebar-left';
if( is_singular() ){
	$get_meta = get_post_custom($post->ID);
	
	if( !empty($get_meta["tie_sidebar_pos"][0]) ){
		$sidebar_pos = $get_meta["tie_sidebar_pos"][0];

		if( $sidebar_pos == 'left' ) $sidebar = ' sidebar-left';
		elseif( $sidebar_pos == 'full' ) $sidebar = ' full-width';
		elseif( $sidebar_pos == 'right' ) $sidebar = ' sidebar-right';
	}
}
?>
	<div id="main-content" class="container<?php echo $sidebar ; ?>">