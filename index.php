<?php
/*
Plugin Name: Comment Juice
Plugin URI: http://www.webtecho.com/comment-juice-plugin/
Description: Comment Juice.
Version: 1.0.5.4
Author:Ashvini Kumar Saxena
Author URI: http://www.webtecho.com/comment-juice-plugin/
License: GPL2
*/
?>
<?php
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php

//first step is to atcivate
register_activation_hook(__file__, 'cj_install');
// check if the options are set
$options = get_option('cj-options');

// function called on activation
function cj_install()
{
	// a few globals provided by wordpress
	global $wpdb;

	// this is to be used to store feeds for people who
	// do not have comment juice installed on their sites
	// it will also store feed of people who deactivated comment juice later

	// table with prefix from database
	// normally it will be wp_comment_juice
	$table_name = $wpdb->prefix . "comment_juice";
	$sql        = "CREATE  TABLE IF NOT EXISTS $table_name (`idcomment_juice` INT NOT NULL AUTO_INCREMENT ,`url` VARCHAR(200) NOT NULL ,`feed` VARCHAR(200) NOT NULL ,`create_date` DATETIME NOT NULL, `lastupdate_date` DATETIME NOT NULL,PRIMARY KEY (`idcomment_juice`) ,UNIQUE INDEX `url_UNIQUE` (`url` ASC) );
	";
	$result     = $wpdb->query($sql);
	$options    = get_option('cj-options');

	if(empty($options))     $options = array();

	// Allow link stays as default until user changes it
	if(!isset($options['cj_field_allowlink']))     $options['cj_field_allowlink'] = true;
	// text to be put in front of the link
	if(!isset($options['cj_field_text_on_comment']))     $options['cj_field_text_on_comment'] = "'s latest article :\t";

	// incase user just decides to activate comment juice for
	//external query

	if(!isset(    $options['cj_show_feed_to_external_query']))    $options['cj_show_feed_to_external_query'] = true;
	//add feed address
	if(!isset($options['cj_my_feed_address']))    $options['cj_my_feed_address'] = get_bloginfo('rss2_url');
	// set the disable option
	if(!isset($options['cj_disable_cj_all']))    $options['cj_disable_cj_all'] = false;


	//though add option should be used
	// but update_option is a better catch all
	update_option('cj-options', $options);


}


/*
This code cannot be used because of stupid FB
if (!is_admin()) {
if (preg_match('/comment-juice/', $_SERVER['REQUEST_URI'])) {
global $post;

$split = preg_split('/comment-juice/', $_SERVER['REQUEST_URI']);
//$new_url = 'Location: '.get_bloginfo('siteurl').$split[0];
$attrib = explode('/', ltrim($split[1], '/'));
$x = get_permalink($attrib[0]);
$url_redirect = $options['cj_redirect_for_facebook_url'];
//$new_url = 'Location: ' . $url_redirect . $split[0] . '?from_fb=1';
$new_url = 'Location: ' . $x . '?cj_post_id=' . $attrib[0] . '&cj_comment_id=' .
$attrib[1];
//$x = the_permalink();
header($new_url);
die(0);
}
}

*/

//start work
// include config
require_once dirname(__file__) . '/config.php';
$plugin_url = plugins_url() . '/comment-juice/';

// path to library
define('CJ_LIB_PATH', dirname(__file__) . '/lib/');
define('CJ_SCRIPTS_URL', $plugin_url . 'scripts/');
define('CJ_CSS_URL', $plugin_url . 'css/');
define('CJ_IMAGE_URL', $plugin_url . 'images/');
//define a few global vars
$like_enabled = isset($options['cj_enable_like_on_comments']) ? $options['cj_enable_like_on_comments'] : false;
//include all files


//file for admin
require_once CJ_LIB_PATH . 'admin.php';


if(isset($_GET['cj_get_feed_address']))
{
	// if it is available in called url's db
	if($options['cj_show_feed_to_external_query'] == true)
	{

		echo esc_url($options['cj_my_feed_address']);
		die(0);
	}
	die(0);
}

if(isset($options['cj_disable_cj_all']) && $options['cj_disable_cj_all'] == false)
{
	//file for feed functionality
	require_once CJ_LIB_PATH . 'feed.php';
	//file for all common functions
	require_once CJ_LIB_PATH . 'functions.php';
	//file for comments form
	require_once CJ_LIB_PATH . 'comments.php';
	// if needed, like.php
	//    if ($like_enabled == true)
	//       require_once CJ_LIB_PATH . 'like.php';

	add_all_ajax_functions();

	add_actions_and_filters();

	//add_scripts_and_style();

}

/*// if the plugin is disabled then
if ($options['cj_disable_cj_all'] == true) {

return;
}


*/


?>