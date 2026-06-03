<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Language switcher
 */
function switch_lang() {
	$CI =& get_instance();

	if ($lang = $CI->input->post('lang-select')) {
		$url = $CI->uri->uri_string();
		// drop cookie
		$cookie = array(
		    'name'   => 'lang',
		    'value'  => $lang,
		    'expire' => 365*24*60*60, // 1 year cookie
		    'domain' => '.' . $_SERVER['SERVER_NAME'],
		    'path'   => '/',
		    'prefix' => 'an_',
		    'secure' => FALSE,
		);
		$CI->input->set_cookie($cookie);
        echo $url;
        die();
	}
}

/**
 * Load language files hook
 * @author	Ahmad
 */
function load_lang() {
	$CI =& get_instance();
	
	if (!$language = $CI->input->cookie('an_lang')) {
		$language = config_item('an_lang'); // retrieve default language
	}
    
	// set the global language variable here
	$CI->language = $language;

	$CI->load->helper('directory');
	if (!$map = directory_map('./' . APP_FOLDER . '/language/' . $language . '/')) return FALSE;

	foreach($map as $file) {
		if (strpos($file, '_lang.php') === false) continue;
		$CI->lang->load(str_replace('_lang.php', '', $file), $language);
	}
	
	return TRUE;
}