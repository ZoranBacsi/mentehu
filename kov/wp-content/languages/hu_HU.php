<?php
add_action('upgrader_post_install','magyar_dashboard');
add_action('wp_dashboard_setup','magyar_dashboard');

function magyar_dashboard()
{  
  $widget_options = get_option('dashboard_widget_options');
  
  //Ha az alap fejlesztői blog van, akkor átírjuk
  if (empty($widget_options['dashboard_primary']) || $widget_options['dashboard_primary']['link'] == 'http://wordpress.org/development/')
  {
    $widget_options['dashboard_primary'] = array(
    	'link' => 'http://napsugar.net/',
    	'url' => 'http://feeds.feedburner.com/idezetek/',
    	'title' => 'A napi lélekmelegítőd',
    	'items' => 1,
    	'show_summary' => 1,
    	'show_author' => 1,
    	'show_date' => 1
    );
  }
  
  //Ha az alap wordpress planet van, akkor is átírjuk
  if (empty($widget_options['dashboard_secondary']) || $widget_options['dashboard_secondary']['link'] == 'http://planet.wordpress.org/')
  {
    $widget_options['dashboard_secondary'] = array(
    	'link' => 'http://word-press.hu/',
    	'url' => 'http://word-press.hu/feed/',
    	'title' => 'Magyar WordPress Hírek',
    	'items' => 4,
    	'show_summary' => 1,
    	'show_author' => 1,
    	'show_date' => 1
    );
  }
  
  update_option('dashboard_widget_options', $widget_options);
}

//$wp_default_secret_key = 'írj ide valami nagyon bonyolultat';
?>