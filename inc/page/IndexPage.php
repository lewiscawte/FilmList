<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 19/11/14
 * Time: 14:38
 */

class IndexPage extends Page {

	public function execute( $page ) {
		$config = $this->getConfig();
		$session = $config->getSession();

		if( $page != "index" ) {
			http_redirect( Linker::intURLConstruct( 'error', 'exceptionPoorPage' ), array( 'page' => $page ), false, HTTP_REDIRECT_TEMP );
		}

		if( !$config->getSetting( 'LoggedOutDash' ) && $session->validityCheck() && !$session->loggedIn ) {
			http_redirect( Linker::intURLConstruct( 'login', '' ), array( 'src' => 'index' ), false, HTTP_REDIRECT_TEMP );
		}
	}
}