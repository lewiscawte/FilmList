<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 11/01/15
 * Time: 11:31
 */

class AdminPage extends Page {

	public function getTitle( $page ) {
		switch ( $page ) {
			case 'admin/index':
				$title = 'Admin Panel';
				break;
			case 'admin/addfilm':
				$title = 'Add New Film';
				break;
			default:
				$title = $this->getConfig()->getSetting( 'Sitename' );
				$title = $title['config_value'];
				break;
		}

		// TODO: Review this code, intended structure is
		// 		something along the lines of "Add Film | SITENAME Admin Panel"
		$titleSitename = $this->getConfig()->getSetting( 'TitleSitename' );

		if ( boolval( $titleSitename['config_value'] ) ) {
			$sitename = $this->getConfig()->getSetting( 'Sitename' );
			$title .= " | " . $sitename['config_value'];
		}
		// END TODO

		return $title;
	}
}