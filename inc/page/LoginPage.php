<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 21/01/15
 * Time: 13:06
 */

class LoginPage extends Page {
	private $thispage = 'login';

	public function __construct() {

	}

	public function execute() {
		$template = $this->getTwig()->loadTemplate( 'login.twig' );


		$template->display( array(
			'name' => 'Foobar',
		) );
	}
}