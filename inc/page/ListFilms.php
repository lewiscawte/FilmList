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
		$flOffset = isset( $_REQUEST['flOffset'] ) ? (int)$_REQUEST['flOffset'] : 0;

		if ( $flOffset !== 0 && is_int( $flOffset ) ) {
			$this->offset = htmlspecialchars( $flOffset );
		}
	}

	public function execute( $page ) {
		// $this->doPageCheck();
		$query = $this->queryLimit();

		$dbr = $this->getDatabase()->doQuery( $query );

		$x = 0;
		$films = array();

		while ( $entry = $dbr->fetch_assoc() ) {
			$x++;
			$films[$x] = $entry;
		}

		// These variables are now done with, clear their contents
		// from memory.
		unset( $entry, $x, $query );

		$template = $this->getTwig()->loadTemplate( 'fl.twig' );
		$template->display( array(
			'baseurl' => $baseURL = $this->getConfig()->getSetting( 'BaseURL' )['config_value'],
			'pagetitle' => $this->getTitle( $this->thispage ),
			'films' => $films,
		) );
	}

	private function queryLimit() {
		$conf = $this->getConfig();

		$baseQuery = "SELECT * FROM film WHERE film_active = 1";
		$limit = $conf->getSetting( 'ListLimit' );
		$limit = $limit['config_value'];

		if ( isset( $limit ) && $limit !== 0 ) {
			if ( $this->offset !== 0 ) {
				$limit = "LIMIT " . $this->offset . "," . $limit;
			} else {
				$limit = "LIMIT " . $limit;
			}
			$query = $baseQuery . " " . $limit;

			unset( $limit );
		} else {
			$query = $baseQuery;
		}

		$query = $query . ";";

		unset( $baseQuery );
		return $query;
	}
}