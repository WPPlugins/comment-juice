var submit_button_pressed = false;
jQuery(document).ready(function()
{
    //ready is called only once
    
    //if url is filled
    if(jQuery("#url").val()!="" )
    {
        fetch_feed();
        
    }
	//if tabbed out
	jQuery("#url").blur(function()
	{
		// on moving out from url
		//dont do anything if the feed_url is filled
		if(jQuery("#url").val()!="" )
			//&& jQuery("#feed_url").val()=="")
		{
			fetch_feed();
		}
	}
	);
    
	/*jQuery("#feed_url").balloon({
		contents: 'Click '+ '<a id="reload" href="#">here</a>'
		+'to reload feed from your last comment',
		position:'top',  tipSize: 24,
		css: {
			border: 'solid 4px #5baec0',
			padding: '10px',
			fontSize: '12px',
			lineHeight: '3',
			backgroundColor: '#666',
			color: '#fff',
			opacity: 1
		}
		});
        */
}
);
jQuery("#reload").live('click',function(event)
{
	event.preventDefault();
	if(jQuery("#url").val()!="" )
		//&& jQuery("#feed_url").val()=="")
	{
		jQuery("#feed_url").hideBalloon();
		fetch_feed();
	}
	});

function submit_me()
{
	submit_button_pressed = true;
	// get name from form input
	var thename = jQuery("input#name").val();
	var feed_url = jQuery("#feed_url").val();
	var url = jQuery("#url").val();
	if(validate_feed(feed_url))
	{
		fetch_feed();
	}
	else
	{
		irritate();
	}
}

function fetch_feed()
{
	var feed_url = jQuery("#feed_url").val();
	var url = jQuery("#url").val();
	// Put an animated GIF image insight of content
	jQuery("#feedgif").show();
	jQuery("#feed_submit").hide();
	jQuery.post(
	the_ajax_script.ajaxurl,{
		action:'feed_ajax_hook',
	data : {'feed_url':feed_url,'url':url}
		},
	
	function(response_from_the_action_function)
	{
		jQuery("#feedgif").hide();
		jQuery("#feed_submit").show();
		if(response_from_the_action_function=="")
		{
			if(submit_button_pressed == true)
			{
				//show alert
				alert('Feed could not be read. Please check the feed address');
				return;
			}
			else 
			{
				//just return
				return;
			}
		}
		if(jQuery("#post_url").exists())
		{
			//jQuery("#post_url").remove();
			//jQuery("#post_title").remove();
			jQuery("#cj_rss").remove();
		}
		//jQuery(".comment-form-comment").append(response_from_the_action_function);
		//jQuery(".comment_box").append(response_from_the_action_function);
		//jQuery("#comment").append(response_from_the_action_function);
		var p = jQuery("#comment").parent("p");
		p.after(response_from_the_action_function);
		var title = jQuery("#post_url option:selected").text();
		jQuery("#post_title").val(title);
		//fill in the feed title with sent from server
		var rss_url_from_db = jQuery("#rss_url_from_db").val();
		jQuery("#feed_url").val(rss_url_from_db);
		jQuery("#post_url").change(function()//click does not work in chrome :(
		{
			var title = jQuery("#post_url option:selected").text();
			jQuery("#post_title").val(title);
		}
		);
	}
	);
}

function validate_feed(feed_url)
{
	if(feed_url == "")
	{
		return;
	}
	else 
	{
		return true;
	}
}

function irritate()
{
	//;)
		//jQuery("#feed_url").css('background','red');	
	alert('Fill in the feed address');
}
jQuery.fn.exists = function()
{
	return jQuery(this).length>0;
}

