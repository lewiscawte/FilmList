<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 11/01/15
 * Time: 11:31
 */

class AdminIndex extends AdminPage {
	private $thispage = 'admin/index';

	public function __construct() {
	}

	public function execute( $page ) {
		// $this->doPageCheck();

		// Load our template and pass it some basic data.
		$template = $this->getTwig()->loadTemplate( 'admin-index.twig' );
		$template->display( array(
			'baseurl' => $baseURL = $this->getConfig()->getSetting( 'BaseURL' )['config_value'],
			'pagetitle' => $this->getTitle( $this->thispage ),
		) );
	}
}