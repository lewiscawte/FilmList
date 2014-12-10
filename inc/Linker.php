<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 19/11/14
 * Time: 14:55
 */

class Linker extends Context {

	private $conf;

	public function __construct() {
		$this->conf = $this->getConfig();
	}

	public static function intURLConstruct( $page, $ref ) {
		$conf = new Config();
		$returnURL = $conf->getSetting( 'BaseURL' );

		if( substr( $returnURL, -1 ) !== "/" ) {
			$returnURL = $returnURL . "/";
		}
		$returnURL = $returnURL . "?page=" . htmlspecialchars( $page );

		if( isset( $ref ) ) {
			$returnURL = $returnURL . "&ref=" . htmlspecialchars( $ref );
		}

		return $returnURL;
	}
} 