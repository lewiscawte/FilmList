<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 26/11/14
 * Time: 15:09
 */

class Page extends Context {
	// Put stuff here?

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

		$titleSitename = $this->getConfig()->getSetting( 'TitleSitename' );

		if( boolval( $titleSitename['config_value'] ) ) {
			$sitename = $this->getConfig()->getSetting( 'Sitename' );
			$title .= " | " . $sitename['config_value'];
		}

		return $title;
	}
} 