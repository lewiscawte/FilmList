<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 24/02/15
 * Time: 20:26
 */

class AdminAddFilm extends AdminPage {
	private $thispage = 'admin/addfilm';

	public function execute( $page ) {
		$template = $this->getTwig()->loadTemplate( 'admin-addfilm.twig' );
		$template->display( array(
			'baseurl' => $baseURL = $this->getConfig()->getSetting( 'BaseURL' )['config_value'],
			'pagetitle' => $this->getTitle( $this->thispage ),
		) );

	}
}