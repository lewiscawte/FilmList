<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 19/11/14
 * Time: 14:38
 */

class IndexPage extends Page {
	private $thispage = 'index';

	public function execute( $page ) {
		$config = $this->getConfig();
		$session = $config->getSession();
		$template = $this->getTwig()->loadTemplate( 'index.twig' );

		if ( $page != "index" ) {
			Linker::doRedirect(
				array(
					'error',
					'exceptionPoorPage'
				),
				array(
					'page' => $page,
					'src' => $this->thispage,
				)
			);
		}

		if ( !$config->getSetting( 'LoggedOutDash' ) && $session->validityCheck() && !$session->loggedIn() ) {
			Linker::doRedirect( array( 'login' => NULL ), array( 'src' => $this->thispage ) );
		}

		$template->display( array(
			'name' => $_SESSION['username'],
		) );
	}
}