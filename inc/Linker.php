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
		// Get the 'base url' from the database.
		$returnURL = $conf->getSetting( 'BaseURL' );

		// Check if there is a trailing slash.
		if ( substr( $returnURL, -1 ) !== "/" ) {
			// If there isn't, append one.
			$returnURL = $returnURL . "/";
		}
		// Append the requsted page.
		$returnURL = $returnURL . "?page=" . htmlspecialchars( $page );

		// If there is any referral info, append this.
		if ( isset( $ref ) ) {
			$returnURL = $returnURL . "&ref=" . htmlspecialchars( $ref );
		}

		// Return a nice, constructed internal (site) url as string.
		return $returnURL;
	}

	/**
	 * Function to redirect a person to a new page.
	 *
	 * @param array $location - $page and $pagetype
	 * @param array $extras - extra details, eg, src.
	 */
	public static function doRedirect( $location = array(), $extras = array() ) {
		$conf = new Config();
		$baseURL = $conf->getSetting( 'BaseURL' );

		// Add the page parameter so we're not stuck on the index.
		$baseURL .= '?page=' . $location['page'];

		if ( isset( $location['pagetype'] ) ) {
			// If a page does multiple things based on a switch or if
			// statement, we set that now.
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
				// Escape each part of the array and break it down into nicer,
				// more usable strings.
				$key = htmlspecialchars( array_keys( $extra ) );
				$value = htmlspecialchars( array_values( $extra ) );
				unset( $extra );

				// Dynamicly build on extra parameters on the URL.
				$redirectURL .= '&' . $key . '=' . $value;
				unset( $key, $value );
			}
		}

		// Do the 302 redirect.
		header( 'Location: ' . $redirectURL, true, 302 );
		unset( $redirectURL );
	}
}