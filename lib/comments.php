<?php
/* this file is about operation on comment */

//read feed address
function read_comment_author_feed()
{
    //  global $wpdb;
    //$myrows = $wpdb->get_results('SELECT * FROM wp_comments where comment_author_url ="http://k.com" limit 1');
}

// Function adds a field for adding feed address by the commentator
function add_comment_ajax_comment_form()
{
    global $options;
    // show only if
    if ($options['cj_disable_cj_all'] == false) {
        $ajax_gif = CJ_IMAGE_URL . 'ajax.gif';
        $options = get_option('cj-options');
        $show_link = $options['cj_field_allowlink'];
        $attrib_text = '';
        if ($show_link == false)
            $attrib_text = "Comment Juice: Get some juice with Pizza";
        else
            $attrib_text = '<a href="http://www.webtecho.com/comment-juice-plugin/">Comment Juice </a>: Get some juice with Pizza';
?>
	<div id = "feedform">
	<form>
	<p> Enter your feed Address in the field below</p>
	<input id="feed_url" name="feed_url" value = "" type="text" size ="40"/>
	<input id="feed_submit" value = "Get Feed!!!" type="button" onClick="submit_me();" />
	<div id="feedgif" style="display:none"><span class="loading-text">Loading feed...</span><img src="<?php echo
        $ajax_gif ?>"></div>
	<p><?php echo $attrib_text ?></p>
	</form>
	</div>
	<?php
    }
}
//pressing the submit button above calls AJAX: response is the select and text box from load_feed() function

function get_feed_url_from_db($url)
{
    global $wpdb;
    $sql = "SELECT * from wp_comment_juice where url='$url' LIMIT 1";
    $result = $wpdb->get_row($sql, ARRAY_A);
    return $result['feed'];
}

function feed_ajax_hook()
{
    global $wpdb; // this is how you get access to the database
    //get the feed url from post data
    $feed_url = isset($_POST['data']['feed_url']) ? wp_filter_nohtml_kses($_POST['data']['feed_url']) :
        '';
    $url = isset($_POST['data']['url']) ? wp_filter_nohtml_kses($_POST['data']['url']) :
        '';

    if (is_user_logged_in() == true) {
        $feed_url = get_bloginfo('rss2_url');

    }
    //if no feed url has been found check in the db
    if (empty($feed_url)) {

        $feed_url = get_feed_url_from_db($url);

        //if it is still empty
        if (empty($feed_url)) {
            // if no feed url has been entered check at the site if
            //comment juice is installed on the site
            //take it from there
            $new_url = prepare_url_for_query($url);
            $feed_url = get_feed_url_from_site($new_url);

            // if still cannot be determined
            if (empty($feed_url)) {
                //try to get it from meta
                $feed_url = find_feed_from_meta($url);
            }

        }

    }

    if (!empty($feed_url)) //echo to browser making ajax call

        echo load_feed($feed_url);
    // this is required to return a proper result always
    die();
}

function get_feed_url_from_site($url)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    $awel = curl_exec($ch);
    if (curl_errno($ch)) {
        return;
    }
    //echo $awel;
    curl_close($ch);
    $array = esc_url($awel);

    return $array;

}
/* this function is used to only get the feed address stored in the
commentator's  site */
function prepare_url_for_query($url)
{
    return $url . GET_PARAM_FEED;

}

//user selects the right link ,adds comment and press the comment submit
//if everything is okay , additional fields are added to the wp_commentsmeta table
function add_url_and_text_as_comment_meta($comment_id)
{
    global $wpdb;
    $value = array();
    if (isset($_POST['post_url']) && isset($_POST['post_title'])) {
        //eliminate all traces of html and javacript filters
        $value['post_url'] = wp_filter_nohtml_kses($_POST['post_url']);
        $value['post_title'] = wp_filter_nohtml_kses($_POST['post_title']);
        //$value is automatically serialized by the function below
        add_comment_meta($comment_id, 'cj_sel_arr', $value, false);

        //now the url and title are part of the comment
        // time to save the url and feed entry into the comment_juice table
        $url = wp_filter_nohtml_kses($_POST['url']);
        $feed_url = wp_filter_nohtml_kses($_POST['feed_url']);
        $sql = "SELECT * from wp_comment_juice where url='$url'";
        $result = $wpdb->query($sql);
        if (empty($result)) {
            //if the entry is not found
            $sql = "INSERT INTO wp_comment_juice(url,feed,create_date,lastupdate_date) VALUES('$url','$feed_url',now(),now())";
            $result = $wpdb->query($sql);
        } else {
            $sql = "UPDATE wp_comment_juice SET feed = '$feed_url',lastupdate_date = now() WHERE url = '$url' ";
            $result = $wpdb->query($sql);
        }
    }
}
/* compatibility with thesis */
add_filter('thesis_comment_text', 'append_link_to_comment_text_thesis');
function append_link_to_comment_text_thesis($comment_text)
{
    $comment_text = append_link_to_comment_text($comment_text);
    return $comment_text;
}

/* the additional fields become a part of comment text and
are displayed in the comments if they are available */

function append_link_to_comment_text($comment_text)
{
    global $comment, $like_enabled, $options;
    //comment id is null if the comment is not saved. This is called two times
    //get the value for comment text
    // if commentjuice is disabled;
    $text_on_comment = $options['cj_field_text_on_comment'];
    if ($text_on_comment == '')
        $text_on_comment = "'s latest article:";
    if (empty($comment))
        return;

    // get the meta data from wp_comment_juice
    $value = array();
    $value = maybe_unserialize(get_comment_meta(get_comment_ID(), 'cj_sel_arr', true));
    if (isset($value['post_url']) && isset($value['post_title'])) {
        $final_comment_text = $comment_text . "<p>" . get_comment_author() . $text_on_comment .
            '<a href="' . $value['post_url'] . '" target="_blank">' . $value['post_title'] .
            '<a></p>';
        //  if ($like_enabled == true)
        //    $final_comment_text .= add_facebook_button_on_comments();
        return $final_comment_text;
    } else {
        $final_comment_text = $comment_text;
        //if ($like_enabled == true)
        //  $final_comment_text .= add_facebook_button_on_comments();
        return $final_comment_text;
    }
}

?>