<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 19/11/14
 * Time: 14:38
 */

class IndexPage extends Page {
	// $thispage used for checking and title.
	private $thispage = 'index';

	public function execute( $page ) {
		// Get all our objects.
		$config = $this->getConfig();
		$session = $config->getSession();
		$template = $this->getTwig()->loadTemplate( 'index.twig' );

		if ( $page != "index" ) {
			// Redirect if the page parameter doesn't match index.
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

		// In a perfect world where login works, if you weren't logged in, you'd be redirected to the login page.
		if ( !$config->getSetting( 'LoggedOutDash' ) && $session->validityCheck() && !$session->loggedIn() ) {
			Linker::doRedirect( array( 'login' => NULL ), array( 'src' => $this->thispage ) );
		}

		// Pass variables to our twig template.
		$template->display( array(
			'name' => $_SESSION['username'],
		) );
	}
}