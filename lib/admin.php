<?php
add_action('admin_head', 'cj_head');
add_action('admin_menu', 'cj_add_to_menu');
add_action('admin_init', 'cj_options_initialize');
add_action('admin_print_styles', 'admin_css');

function cj_head()
{

}
function admin_css()
{
    //wp_enqueue_style('css-handle', plugins_url() . '/' . KEYWORD_DIR_NAME . '/' .'style.css');
}


function cj_add_to_menu()
{
    add_options_page('Comment Juice', 'Comment Juice', 'manage_options',
        'comment_juice', 'show_cj_options_fn');
}

function cj_options_initialize()
{
    register_setting('cj-options', 'cj-options', 'cj_options_validate_fn');
    //add_settings_section('main','Main','cj_section_main_fn','comment_juice');
    //add_settings_field('cj_field_allowlink','Allow link on Comment Form. Please consider tweeting about plugin before you uncheck the box','cj_field_allow_link_fn','comment_juice','main',array( 'label_for' => 'cj_field_allowlink' ) );
}

function cj_section_main_fn()
{
    echo '<p>Comment Juice Main options</p>';
}


function show_cj_options_fn()
{
    $options = get_option('cj-options');
    $is_checked = isset($options['cj_field_allowlink']) && $options['cj_field_allowlink'] == true ? "CHECKED" : '';
    //$feed_address = empty($options['cj_my_feed_address'])? get_bloginfo('rss2_url'):$options['cj_my_feed_address'];




?>
    <div class='wrap'>
    <div id="icon-options-general" class="icon32">
<br>
</div>
    <h2>Comment Juice options</h2>
    <h3>Main options</h3>
    
	<form action="options.php" method="post">
	<?php settings_fields('cj-options'); ?>
    
    <table class="form-table">
    <tbody>
    <tr>
    <th><label for id="cj_disable_cj_all">Disable Comment Juice on this blog</label></th>
    <td><input id='cj_disable_cj_all' name='cj-options[cj_disable_cj_all]' type='checkbox'  
    <?php echo isset($options['cj_disable_cj_all']) && $options['cj_disable_cj_all'] == true ? "CHECKED" : ''; ?>/></td>
    </tr>
    <th><label for id="cj_show_feed_to_external_query">But always return feed below to external query</label></th>
    <td><input id='cj_show_feed_to_external_query' name='cj-options[cj_show_feed_to_external_query]' type='checkbox'  
    <?php echo isset($options['cj_show_feed_to_external_query']) && $options['cj_show_feed_to_external_query'] == true ? "CHECKED" :
    ''; ?>/></td>
    </tr>
    <tr><th><label for id="cj_my_feed_address">Your feed address(defaults to wordpress RSS if left blank)</label></th>
    <td><input id='cj_my_feed_address' name='cj-options[cj_my_feed_address]' type='text'  class="regular-text code" size="60" value="<? if(isset( $options['cj_my_feed_address'])) echo $options['cj_my_feed_address']; ?>"/></td></tr>
    
    </tbody>
    </table>
    <p>
    <b>Please consider <a href="http://www.webtecho.com/comment-juice-plugin/" target="_blank">like or share</a> about the plugin before unchecking the box below</b></p>
    <table class="form-table">
    <tbody>
    <tr>
    <th><label for id="cj_field_allowlink">Allow link on Comment Form</label></th>
    <td><input id='cj_field_allowlink' name='cj-options[cj_field_allowlink]' type='checkbox'  <?php echo
    $is_checked; ?>/></td>
    </tr>
    <tr><th><label for id="cj_field_text_on_comment">Text to be shown on comment</label></th>
    <td><input id='cj_field_text_on_comment' name='cj-options[cj_field_text_on_comment]' type='text' class="regular-text code"
	size ="40" value="<?php if(isset( $options['cj_field_text_on_comment'])) echo  $options['cj_field_text_on_comment']; ?>" />
	</td></tr>
    
    </tbody>
    </table>
  
    <table>
    <tbody>
    
    <tr><th></th><td></td></tr>
    </tbody></table>
	
     <p class="submit">
	<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" class="button-primary" />
	</p>
    </form>
    </div>
	<?php

    /*     <!--     <div class="cj-section">
    <p>
    <label for id="cj_enable_like_on_comments">Enable like on comments 
    </label>
    <input id='cj_enable_like_on_comments' name='cj-options[cj_enable_like_on_comments]' type='checkbox'  
    <?php //echo ($options['cj_enable_like_on_comments'] == TRUE)?"CHECKED":''; ?>/>
    </p>   
    <p>
    <label for id="cj_redirect_for_facebook_url">Redirect base URL for facebook </label>
    <input id='cj_redirect_for_facebook_url' name='cj-options[cj_redirect_for_facebook_url]' type='text'  size="60" value="<?echo $options['cj_redirect_for_facebook_url'];?>"/>
    </p>
    -->
    */
}

function cj_options_validate_fn($input)
{
    $options = get_option('cj-options');
    $options['cj_field_allowlink'] = isset($input['cj_field_allowlink']) ? true : false;
    $options['cj_disable_cj_all'] = isset($input['cj_disable_cj_all']) ? true : false;
    $options['cj_show_feed_to_external_query'] = isset($input['cj_show_feed_to_external_query']) ? true : false;
    $options['cj_enable_like_on_comments'] = isset($input['cj_enable_like_on_comments']) ? true : false;

    //cj_field_text_on_comment
    if (empty($input['cj_field_text_on_comment']))
        $options['cj_field_text_on_comment'] = "'s latest article\t";
    else
        $options['cj_field_text_on_comment'] = $input['cj_field_text_on_comment'];
    $options['cj_my_feed_address'] = empty($input['cj_my_feed_address']) ?
        get_bloginfo('rss2_url') : $input['cj_my_feed_address'];
    ;
    $options['cj_redirect_for_facebook_url'] = $input['cj_redirect_for_facebook_url'];
    return $options;
}
?>