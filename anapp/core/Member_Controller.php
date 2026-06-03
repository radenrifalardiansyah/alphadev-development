<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Member_Controller Class
 */
class Member_Controller extends AN_Controller {
	
	function __construct() {
		parent::__construct();
		$this->auth();
	}
}
