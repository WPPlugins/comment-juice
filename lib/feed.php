<?php

function get_feed($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);        
	$awel = curl_exec($ch);
	if(curl_errno($ch))
	{
		return;
	}
	//echo $awel;
	curl_close($ch);
	@$xml = simplexml_load_string($awel);
	if(empty($xml))
		return;
	$json = json_encode($xml);
	$array = json_decode($json,TRUE);
	return $array;
}

function load_feed($feed_address)
{
	$ret='';
	$feed_address = trim($feed_address);
	if($feed_address== null)
		return ;
	$xml_arr = get_feed($feed_address);	
	if(empty($xml_arr))
		return;
	//if the feed does not have any item	
	if(!isset($xml_arr['channel']['item']))
	{
		return;
	}
	$len = count($xml_arr['channel']['item']);
	$items = $xml_arr['channel']['item'];

	
	//create html for select and hidden field
	$ret.='<div id="cj_rss">';
	$ret .="<p>";
	$ret.='<label for ="post_url">Choose your post   :</label>';
	$ret .='<select id="post_url" name="post_url">';
	$val = '';
	foreach($items as $item)
	{
		$item_title=$item['title'];
		$item_link=$item['link'];
		$ret .= "<option value=".rtrim($item_link,'/').">".$item_title ;
		$ret .= "</option>";
	}
	$ret .="</select>";
	$ret .="</p>";
	$ret .="<p>";
	$ret.='<label for ="post_title">Edit title    :</label>';
	$ret .='<input type="text" id="post_title" name="post_title" />';
	$ret .="</p>";
	$ret .="<p>";
	$ret .='<input type="hidden" id="rss_url_from_db" name="rss_url_from_db" value="'.$feed_address.'"/>';
	$ret .="</p>";
	$ret.='</div>';
	return $ret;
}
/*

function load_feed($xml)
{
	$ret='';
	if($xml== '')
		return '';
	$xml_doc = new DOMDocument();
	@$xml_doc->load($xml) or die(0); //@ is needed here still need to know why
	;
	//if (!$xml_doc->validate())
	{
		//die();
	//}
	//get elements from "<channel>"
	$channel=$xml_doc->getElementsByTagName('channel')->item(0);
	//get and output "<item>" elements
	$x=$xml_doc->getElementsByTagName('item');
	//this is the number of items allowed by feed 
	$len = $x->length;
	//create html for select and hidden field
	$ret.='<div id="cj_rss">';
	$ret .="<p>";
	$ret.='<label for ="post_url">Choose your post   :</label>';
	$ret .='<select id="post_url" name="post_url">';
	$val = '';
	for ($i=0; $i<$len; $i++)
	{
		$item_title=$x->item($i)->getElementsByTagName('title')
			->item(0)->childNodes->item(0)->nodeValue;
		$item_link=$x->item($i)->getElementsByTagName('link')
			->item(0)->childNodes->item(0)->nodeValue;
		$item_desc=$x->item($i)->getElementsByTagName('description')
			->item(0)->childNodes->item(0)->nodeValue;
		/*$ret .= "<option><a href='" . $item_link
		. "'>" . $item_title . "</a>";
		$ret .= "<option value=".rtrim($item_link,'/').">".$item_title ;
		$ret .= "</option>";
	}
	$ret .="</select>";
	$ret .="</p>";
	$ret .="<p>";
	$ret.='<label for ="post_title">Edit title    :</label>';
	$ret .='<input type="text" id="post_title" name="post_title" />';
	$ret .="</p>";
	$ret.='</div>';
	return $ret;
}
*/
?>