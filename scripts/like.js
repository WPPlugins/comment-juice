jQuery(document).ready(function()
{
    alert( $.cookie("example") );
	jQuery(".comment-like").each(function()
	{
		jQuery(this).bind('click', function()
		{
			set_ratings(this);
		}
		);
	}
	);
}
);

function set_ratings(but)
{
  var id_button = jQuery(but).attr('id');
	jQuery.post(
	the_ajax_script.ajaxurl,{
		action:'set_ratings',
		data: {'id':id_button }
		},
	
	function(response_from_the_action_function)
	{
		if(response_from_the_action_function!="")
		{
			alert(response_from_the_action_function);
		}
	}	
	);
}

function like_button_pressed()
{
	/*jQuery.post(
	the_ajax_script.ajaxurl,{
		action:'the_like_button_pressed',
	data : {'feed_url':feed_url,'url':url}
		},
	
	function(response_from_the_action_function)
	{
		});*/
	/*
	*/
}