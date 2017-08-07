<?php

if (isset($_GET['cj_post_id']) && isset($_GET['cj_comment_id']))
{

    add_action('wp_head', 'cj_add_fb_meta');
}


//add_action('wp_head', 'cj_add_fb_meta');
function cj_add_fb_meta()
{
    $cj_comment_id = $_GET['cj_comment_id'];
?>
<meta property="og:title" content="insert your title here"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="<?php echo esc_url(get_comment_link($cj_comment_id))?>"/>
<meta property="og:image" content="http://aks-blog.com/blog/wp-content/themes/thesis_18/custom/images/logo.png"/>
<meta property="og:site_name" content="<?php echo get_bloginfo('url')?>">
<meta property="og:description" content="<? echo get_comment_text($cj_comment_id)?>">
<?    
}

function add_facebook_button_on_comments()
{
    global $comment;
    global $post;
    //$comment_id = 'comment-like-' . $comment->comment_ID;
    $comment_url = esc_url(get_comment_link($cj_comment_id));
    
    //$comment_url = get_permalink().'comment-juice'.'/'.$post->ID.'/'.$comment->comment_ID;
    $button_text = '<iframe src="http://www.facebook.com/plugins/like.php?href=' . $comment_url .
        '&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;
                   action=like&amp;colorscheme=light" scrolling="no" frameborder="0" 
                   allowTransparency="true" style="border:none; overflow:hidden; 
                   width:450px; height:px"></iframe>';
    return $button_text;

}

/* good piece of code
DO NOT DELETE

function set_ratings()
{

global $wpdb;


$id = wp_filter_nohtml_kses($_POST['data']['id']);
$comments_arr = explode('-', $id);

$sql = "SELECT * FROM wp_commentmeta WHERE comment_id = $comments_arr[2]";
$result = $wpdb->get_row($sql, ARRAY_A);
//$cj_arr = unserialize($result['meta_value']);

$cj_arr = maybe_unserialize(get_comment_meta($comments_arr[2], 'cj_sel_arr', true));
if (isset($cj_arr['likes'])) {
$cj_arr['likes'] = $cj_arr['likes'] + 1;
} else {
$cj_arr['likes'] = 1;
}


//$cj_str = serialize($cj_arr);
//$sql = "UPDATE wp_commentmeta set meta_value = '$cj_str' WHERE comment_id = $comments_arr[2]";

//	$result = $wpdb->query($sql);
update_comment_meta($comments_arr[2], 'cj_sel_arr', $cj_arr);
echo $cj_arr['likes'];
die(0);
}
*/
?>