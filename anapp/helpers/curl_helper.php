<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Load CURL library
 * 
 * @since 1.0.0
 * @link https://github.com/php-curl-class/php-curl-class
 */
require APPPATH . 'libraries/curl/vendor/autoload.php';
use \Curl\Curl;

/**
 * 
 */
function an_curl() {
	return new Curl();
}

/**
 * 
 */
function an_curl_get( $url ) {
	$curl = new Curl();
	// set options
	$curl->setOpt( CURLOPT_SSL_VERIFYPEER, false );
	return $curl->get( $url );
}

/**
 * 
 */
function an_curl_post( $url, $param = array() ) {
	$curl = new Curl();
	// set options
	$curl->setOpt( CURLOPT_SSL_VERIFYPEER, false );
	return $curl->post( $url, $param );
}
