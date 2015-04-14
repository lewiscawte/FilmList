<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 11/01/15
 * Time: 11:31
 */

class AdminEditFilm extends AdminPage {
	private $thisfilm = 0, $thispage = 'admin/editfilm';

	public function __construct() {
		// Placeholder
	}

	public function execute( $page ) {
		// $this->doPageCheck();

		// Load our template and pass the basic data to it.
		$template = $this->getTwig()->loadTemplate( 'admin-index.twig' );
		$template->display( array(
			'baseurl' => $baseURL = $this->getConfig()->getSetting( 'BaseURL' )['config_value'],
			'pagetitle' => $this->getTitle( $this->thispage ),
		) );
	}
}