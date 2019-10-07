<?php


/*
$time = strtotime('2010-04-28 17:25:43');

echo 'event happened '.humanTiming($time).' ago';
*/
function lis_humanTiming ($time)
{

    $time = time() - $time; // to get the time since that moment

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }
}


/*
Get User Role
*/
function lis_get_user_role($id)
{
    $user = new WP_User($id);
    return array_shift($user->roles);
}


/*
get Theme Options value
*/
function lis_get_theme_option ( $option, $suboption=null )
{
	global $lis_theme_options;

	if ( $lis_theme_options[$option] ) {
		if ( $suboption ) {
			if ( isset($lis_theme_options[$option][$suboption]) ) {
				return $lis_theme_options[$option][$suboption];
			} else {
				return $lis_theme_options[$option];
			}
		} else {
			return $lis_theme_options[$option];
		}
	} else {
		return false;
	}
}


/*
get Theme Options values
*/
function lis_get_theme_options ( )
{
	global $lis_theme_options;

	if ( $lis_theme_options ) {
		return $lis_theme_options;
	} else {
		return false;
	}
}


/*
get Theme Options values
*/
function lis_css_style_option ($propriety='', $value='')
{
	if ($value)
	{
		return $propriety . ': ' . $value . ';';
	}
	else
	{
		return FALSE;
	}
}



/*
Get the logo from Theme Options
*/
function lis_get_logo()
{
	$html = '<a href="' . home_url('/') . '" class="navbar-brand header-center">';

	if ( lis_get_theme_option('logo', 'url') ) {
		$html .= '<img class="noretina" src="'. lis_get_theme_option('logo', 'url') .'" alt="'. get_bloginfo('name') .'" title="'. get_bloginfo('name') .'">';
	}
	if ( lis_get_theme_option('logo_retina', 'url') ) {
		$html .= '<img class="retina true" src="'. lis_get_theme_option('logo_retina', 'url') .'" alt="'. get_bloginfo('name') .'" title="'. get_bloginfo('name') .'">';
	}
	if ( lis_get_theme_option('logo', 'url') && !lis_get_theme_option('logo_retina', 'url') ) {
		$html .= '<img class="retina" src="'. lis_get_theme_option('logo', 'url') .'" alt="'. get_bloginfo('name') .'" title="'. get_bloginfo('name') .'">';
	}
	if ( !lis_get_theme_option('logo', 'url') && lis_get_theme_option('logo_retina', 'url') ) {
		$html .= '<img class="noretina" src="'. lis_get_theme_option('logo_retina', 'url') .'" alt="'. get_bloginfo('name') .'" title="'. get_bloginfo('name') .'">';
	}
	if ( !lis_get_theme_option('logo', 'url') && !lis_get_theme_option('logo_retina', 'url') ) {
		$html .= '<span class="h3-size">' . get_bloginfo('name') . '</span>';
	}

	$html .= '</a>';

	return $html;
}



/*
Get page options
*/
function lis_get_page_options($id=NULL)
{
	if ( $page_data = get_post($id) ) {
	
		$page_html = '';
		$page_style = '';
		$page_inline_style = '';
		$page_content_inline_style = '';
		$page_css = '';
		$page_attr = '';
		$page_options = '';
		$show_page = '';
		$page_raw = '';

		$page_id = $page_data->ID;
		$page_slug = $page_data->post_name;
		$page_content = apply_filters('the_content', $page_data->post_content);
		$page_class = get_post_class('', $page_id);

		if ( function_exists( 'get_fields' ) ) {
			$page_options = get_fields($page_id);
			
			$page_title = ( isset($page_options['show_page_title']) && $page_options['show_page_title'] ? $page_data->post_title : NULL);
			$show_page = ( isset($page_options['show_page']) && $page_options['show_page'] ? TRUE : NULL);

			$page_raw = $page_options;

		} else {
			$page_title = $page_data->post_title;
			$show_page = TRUE;

			$page_raw = false;
		}
		
		$page_content_inline_style .= (isset($page_options['text_color']) && $page_options['text_color'] ? lis_css_style_option('color', $page_options['text_color']) : '');
		$page_content_inline_style .= (isset($page_options['bg_color']) && $page_options['bg_color'] ? lis_css_style_option('background-color', $page_options['bg_color']) : '');
		$page_content_inline_style .= (isset($page_options['bg_color']) && $page_options['bg_color'] ? lis_css_style_option('background-color', 'rgba(' . hex2rgb($page_options['bg_color']) . ', ' . ($page_options['bg_opacity']/100) . ')') : '');
		$page_content_inline_style .= (isset($page_options['margin_top']) && $page_options['margin_top'] ? 'padding-top: ' . (int)$page_options['margin_top'] . 'px;' : NULL);
		$page_content_inline_style .= (isset($page_options['margin_bottom']) && $page_options['margin_bottom'] ? 'padding-bottom: ' . (int)$page_options['margin_bottom'] . 'px;' : NULL);

		if (isset($page_options['bg_images'][0]) && count($page_options['bg_images']) == 1)
		{
			$page_inline_style .= (($bg_image = $page_options['bg_images'][0]) ? 'background-image: url(' . $bg_image['url'] . ');' : NULL);
			$page_class[] = ($page_options['parallax'] ? 'parallax' : NULL);
			$page_attr .= ($page_options['parallax'] ? ' data-stellar-background-ratio="0.5" ' : NULL);
			$page_class[] = ($page_options['bg_cover'] ? NULL : 'cover');
		}

		$page_class[] = (isset($page_options['full_height']) && $page_options['full_height'] ? 'full-height' : NULL);


		if (empty($page_inline_style)) {
			$page_inline_style = "background-color: #fff;";
		}

		$data = array(
			'id' => $page_id,
			'post_name' => $page_slug,
			'class' => implode(' ', $page_class),
			'style' => $page_inline_style,
			'content_style' => $page_content_inline_style,
			'page_title' => $page_title,
			'page_content' => $page_content,
			'template' => get_page_template_slug($page_id),
			'attr' => $page_attr,
			'show_page' => $show_page,
			'raw' => $page_raw
		);

		return $data;
	}
}


/*

*/
function lis_get_key_by_value($array=array(), $field='', $input='')
{
	foreach ($array as $key => $value)
	{
		if ($value->$field == $input)
		{
			return $key;
		}
	}
}


/*
Converts hex to rgb
*/
function hex2rgb($hex) {
	$hex = str_replace("#", "", $hex);

	if(strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	$rgb = array($r, $g, $b);
	return implode(", ", $rgb); // returns the rgb values separated by commas
	//return $rgb; // returns an array with the rgb values
}


/*
Converts to bool
*/
function lis_bool( $string )
{
	return filter_var($string, FILTER_VALIDATE_BOOLEAN);
}


/* Parse Video Thumbs */
/*
http://stackoverflow.com/questions/2068344/how-to-get-thumbnail-of-youtube-video-link-using-youtube-api
*/
function lis_video_thumbs($url='')
{
	$thumb = '';

	if (strpos($url,'vimeo') !== false)
	{
		$result = preg_match('/(\d+)/', $url, $matches);

		if ($result)
		{
			$id = unserialize(@file_get_contents("http://vimeo.com/api/v2/video/" . $matches[0] . ".php"));
			$thumb = $id[0]['thumbnail_large'];
		}

	}

	if (strpos($url,'youtube') !== false)
	{
		parse_str( parse_url( $url, PHP_URL_QUERY ), $matches );
		$thumb = 'http://img.youtube.com/vi/' . $matches['v'] . '/hqdefault.jpg';
	}

	return $thumb;
}


/* 
Delete in Array
*/
function lis_delete_from_array($key, $value, $array)
{
	if (!is_array($array))
	{
		return FALSE;
	}

	foreach ($array as $k => $v)
	{
		if ($v[$key] == $value)
		{ 
			unset($array[$k]);
		}
	}

	return $array;
}


/*
Find in Array
*/
function lis_aasort (&$array, $key)
{
	$sorter=array();
	$ret=array();

	if (!is_array($array))
	{
		return FALSE;
	}

	reset($array);

	foreach ($array as $ii => $va)
	{
		$sorter[$ii]=$va[$key];
	}

	asort($sorter);

	foreach ($sorter as $ii => $va)
	{
		$ret[$ii]=$array[$ii];
	}

	$array=$ret;
}


/*
Ajax request
*/
function lis_is_ajax_request()
{
	return ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHttpRequest') );
}


/*
Get the Widget (wrapper for the_widget). http://codex.wordpress.org/Function_Reference/the_widget
*/
if( !function_exists('get_the_widget') ){
	
	function get_the_widget( $widget, $instance = '', $args = '' ){
		ob_start();
		the_widget($widget, $instance, $args);
		return ob_get_clean();
	}
}


/*
get page by template
*/
function lis_get_page_by_template($template_name='')
{
	$pages = get_pages(array(
		'meta_key' => '_wp_page_template',
		'meta_value' => $template_name
	));

	/* removes duplicates due imports */
	// if ( is_array($pages) ) {
	// 	$unique_pages = array();
	// 	$unique_pages[] = $pages[0];

	// 	foreach ($pages as $key => $value) {

	// 		$is_unique = TRUE;

	// 		foreach ($unique_pages as $k => $v) {

	// 			if ( $value->ID === $v->ID ) {
	// 				$is_unique = FALSE;
	// 			} 
	// 		}
	// 		if ($is_unique) {
	// 			$unique_pages[] = $value;
	// 		}
	// 	}
	// }

	//return $unique_pages;
	return $pages;
}


/* Woocommerce */
//Detect if woocommerce is activated
if ( ! function_exists( 'is_woocommerce_activated' ) )
{
	function is_woocommerce_activated()
	{
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}


//Count widgets on a given sidebar
function count_sidebar_class( $sidebar_name ) {
	global $sidebars_widgets;
	$count = count ($sidebars_widgets[$sidebar_name]);
	return $count;
}
