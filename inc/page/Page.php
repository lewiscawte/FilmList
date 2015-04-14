<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 26/11/14
 * Time: 15:09
 */

class Page extends Context {
	// Put stuff here? (global variables)

	/**
	 * Function to build a formatted string for the
	 * HTML page title (<title>).
	 *
	 * @param $page - pagename
	 * @return bool|mysqli_result|string
	 */
	public function getTitle( $page ) {
		switch ( $page ) {
			case 'listfilms':
				$title = 'List Films';
				break;
			case 'index':
				$title = 'Home';
				break;
			default:
				$title = $this->getConfig()->getSetting( 'Sitename' );
				$title = $title['config_value'];
				break;
		}

		// Do we put the Sitename in the title? Get the bool from the database
		// to tell us.
		$titleSitename = $this->getConfig()->getSetting( 'TitleSitename' );

		// Now if we do...
		if ( boolval( $titleSitename['config_value'] ) ) {
			// Get the sitename.
			$sitename = $this->getConfig()->getSetting( 'Sitename' );
			// And append it to our title string.
			$title .= " | " . $sitename['config_value'];
		}

		// Give it back, you thief!
		return $title;
	}
} 