<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 11/01/15
 * Time: 09:04
 * TODO: Integrate proper session handling in
 * TODO: in place of current testing/debugging returns
 */

class FLSessionHandler {
	private $context;

	public function __construct() {
		$context = new Context();
		$this->context = $context;
	}

	public function validityCheck() {
		return true;
	}

	public function loggedIn() {
		/*
		if( $_SESSION['loggedIn'] === true ) {
			$val = true;
		} else {
			$val = false;
		}

		return $val;
		*/

		return true;
	}
}