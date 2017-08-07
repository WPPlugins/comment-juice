<?php
$ajax_functions= array(
array('handle' => 'wp_ajax_feed_ajax_hook','function'=> 'feed_ajax_hook'),   	// Ajax action . note:wp_ajax_the_ajax_hook is the filter and the_ajax_hook is the function :IMPORTANT 
array('handle' => 'wp_ajax_nopriv_feed_ajax_hook','function'=> 'feed_ajax_hook')
//,	// need this to serve non logged in users
//array('handle' => 'wp_ajax_set_like','function'=> 'set_like'),
//array('handle' => 'wp_ajax_nopriv_set_like','function'=> 'set_like')
)
;
define('KEYWORD_DIR_NAME', 'comment-juice');
define('GET_PARAM_FEED', '?cj_get_feed_address=1');
?>