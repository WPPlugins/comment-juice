<?php

function add_all_ajax_functions()
{
    $ajax_functions = array(array('handle' => 'wp_ajax_feed_ajax_hook', 'function' =>
        'feed_ajax_hook'), array('handle' => 'wp_ajax_nopriv_feed_ajax_hook', 'function' =>
        'feed_ajax_hook'));

    foreach ($ajax_functions as $ajax_function) {

        if (function_exists($ajax_function['function'])) {
            add_action($ajax_function['handle'], $ajax_function['function']);
        }

    }


}

function add_actions_and_filters()
{
    //function to add additional field in the form
    add_action('comment_form', 'add_comment_ajax_comment_form');
    //works on comment text to add the link and author name
    add_filter('comment_text', 'append_link_to_comment_text', 1000);
    //adds URL /text as comment meta information
    add_action('comment_post', 'add_url_and_text_as_comment_meta', 1);
    //enquue css files
    add_action('wp_print_styles', 'enqueue_css_file');
    
    add_action('wp_enqueue_scripts', 'add_scripts_and_styles');
}
function enqueue_css_file()
{
    wp_enqueue_style('css-handle', CJ_CSS_URL . 'style.css');
}

function add_scripts_and_styles()
{
    // enqueue and localise scripts: both of them are necessary
    //	wp_enqueue_script( 'ballon-disp', CJ_SCRIPTS_URL.'balloon.js', array( 'jquery' ) );
    // enqueue and localise scripts: both of them are necessary
    wp_enqueue_script('feed-handle', CJ_SCRIPTS_URL . 'feed.js', array('jquery'));
    //wp_enqueue_script('ratings-js-handle', CJ_SCRIPTS_URL . 'like.js', array('jquery'));

    wp_localize_script('feed-handle', 'the_ajax_script', array('ajaxurl' =>
        admin_url('admin-ajax.php')));
    //read_comment_author_rss();

}
function get_site_content_with_curl($url)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    $html = curl_exec($ch);
    return $html;
}

function find_feed_from_meta($url)
{
    $html = get_site_content_with_curl($url);

    $dom = new domDocument();
    @$dom->loadHTML($html);
    $header_links = $dom->getElementsByTagName('link');

    foreach ($header_links as $link) {
        //  $xml = $dom->saveXML($t);
        //var_dump($xml);
        if ('application/rss+xml' == $link->getAttribute('type')) {
            return $link->getAttribute('href');
        }
    }
}

?>