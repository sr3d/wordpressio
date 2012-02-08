<?php
/*
Plugin Name: nikio
Plugin URI: http://nik.io/
Description: A WordPress plugin to deal with the content discover
Author: wordnik
Version: 0.1
Author URI: http://www.wordnik.com
*/

$itemRecommendationsPerPage = 5;

$nikio_plugin_version = "0.1";

// add admin options page
function nikio_add_options_page(){
	add_options_page('Nikio options', 'NIkio Options', 8, basename(__FILE__), 'nikio_options_form');
}

function nikio_globals_init(){
	if ( ! defined( 'WP_CONTENT_URL' ) )
		define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	if ( ! defined( 'WP_CONTENT_DIR' ) )
		define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	if ( ! defined( 'WP_PLUGIN_URL' ) )
		define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
	if ( ! defined( 'WP_PLUGIN_DIR' ) )
		define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR. '/plugins' );
}

// Add settings link on plugin page
function nikio_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=nikio.php">Settings</a>';
  array_unshift($links, $settings_link); 
  return $links; 
}
 
function nikio_options_form() {
	global $itemRecommendationsPerPage;
}

function getNikioVersion(){
	 global $nikio_plugin_version;
	 return $nikio_plugin_version;
}

// display the plugin
function nikio_display ($content)
{
	global $post_ID, $nikio_plugin_version, $itemRecommendationsPerPage;
	$content .= '<script type=\'text/javascript\'>
			(function() {    var script = document.createElement(\'script\'); script.type = \'text/javascript\'; script.async = true;   
			script.src = "http://stage.nik.io/v1/nikio.js?unSafeMode=true";    
			var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(script, s);  })();
		    </script>
		    <div> <b>Interesting words</b>	
		    <div itemscope="" itemtype="http://nik.io/v1/schema/Glossary">
     			<meta itemprop="itemCount" content="10">
     			<meta itemprop="glossaryPermalink" content="wsj-smartmoney-glossary">
     			<meta itemprop="articlePermalink" content="http://www.s.dev.smartmoney.com/spend/technology/a-sixfigure-shave-1320784815379/">
     			<meta itemprop="baseUrlForGlossaryWord" content="http://smartmoney.com/glossary/">
		    </div></div>
		   ';
	return $content;
}

function nikio_get_plugin_admin_path(){
	$site_url = get_option("siteurl");
	// make sure the url ends with /
	$last = substr($site_url, strlen( $site_url ) - 1 );
	if ($last != "/") $site_url .= "/";
	// calculate base url based on current directory.
	$base_len = strlen(ABSPATH);
	$suffix = substr(dirname(__FILE__),$base_len)."/";
	// fix windows path sperator to url path seperator.
	$suffix = str_replace("\\","/",$suffix);
	$base_url = $site_url . $suffix;

	return $base_url;
}

function nikio_get_plugin_place(){
	$ref = dirname(__FILE__);
	return $ref;
}

function nikio_admin_script(){
	global $ob_pi_directory;
	if ((strpos($_SERVER['QUERY_STRING'],'nikio.php') == false) ){
		return;
	}
}

function add_canonical_link() {
     echo '<link rel="canonical" href="http://www.smartmoney.com/spend/technology/a-sixfigure-shave-1320784815379/">';
}

nikio_globals_init();
// add filters

$nikio_plugin = plugin_basename(__FILE__); 

add_filter('the_content', 'nikio_display');
add_action('wp_head', 'add_canonical_link',1);

?>
