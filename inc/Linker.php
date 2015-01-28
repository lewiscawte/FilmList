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

	/**
	 * @param $page
	 * @param $ref
	 * @return bool|mysqli_result|string
	 */
	public static function intURLConstruct( $page, $ref ) {
		$conf = new Config();
		$returnURL = $conf->getSetting( 'BaseURL' );

		if ( substr( $returnURL, -1 ) !== "/" ) {
			$returnURL = $returnURL . "/";
		}
		$returnURL = $returnURL . "?page=" . htmlspecialchars( $page );

		if ( isset( $ref ) ) {
			$returnURL = $returnURL . "&ref=" . htmlspecialchars( $ref );
		}

		return $returnURL;
	}

	/**
	 * @param array $location - $page and $pagetype
	 * @param array $extras - extra details, eg, src.
	 */
	public static function doRedirect( $location = array(), $extras = array() ) {
		$conf = new Config();
		$baseURL = $conf->getSetting( 'BaseURL' );

		$baseURL .= '?page=' . $location['page'];

		if ( isset( $location['pagetype'] ) ) {
			$baseURL = + '&type=' . $location['pagetype'];
		}

		$redirectURL = $baseURL;

		// Dump the location array as it could be huge, we're only
		// after one key and value set.
		// Dump string from memory as it's unused now.
		unset( $location, $baseURL );

		if ( isset( $extras ) ) {
			// Get rid of junk
			array_unique( $extras );

			foreach ( $extras as $extra ) {
				$key = htmlspecialchars( array_keys( $extra ) );
				$value = htmlspecialchars( array_values( $extra ) );
				unset( $extra );

				$redirectURL .= '&' . $key . '=' . $value;
				unset( $key, $value );
			}
		}

		header( 'Location: ' . $redirectURL, true, 302 );
		unset( $redirectURL );
	}
} 