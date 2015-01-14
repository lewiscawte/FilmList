<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 11/01/15
 * Time: 11:31
 */

class ListFilms extends Page {
	private $offset = 0, $thispage = 'listfilms';

	public function __construct() {
		$flOffset = $_REQUEST['flOffset'];
		if( $flOffset !== 0 && is_int( $flOffset ) ) {
			$this->offset = htmlspecialchars( $flOffset );
		}
	}

	public function execute( $page ) {
		$dbr = $this->getDatabase()->doQuery( "SELECT * FROM film WHERE film_active = 1;" );

		//var_dump( $dbr->fetch_assoc( MYSQLI_ASSOC ) );

		while( $entry = $dbr->fetch_assoc() ) {
		//	var_dump( $entry );
			echo "Name: " . $entry['film_name'] . "(" . $entry['film_year'] . ")";
		}

	}
}